<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/7
 * Time: 15:24
 */

namespace app\pub\controller;

use app\common\controller\Base;
use app\common\model\AdminUser;
use app\common\model\SysItem;
use app\common\model\SysType;
use app\common\model\AdminAuth;
use app\common\model\AdminRole;
use app\admin\validate\AdminUser as VAdminUser;
use think\Request;

class Login extends Base
{
   //TODO 登录视图页面
   public function index()
   {
       $assign=[];
       // 被迫下线提醒，不允许同一账号多设备登录
       if(!empty(cookie('user_down_info')))
       {
           $user_down_info=cookie('user_down_info');
           $assign['user_down_info']=$user_down_info;
           cookie('user_down_info',null);
       }
       // 登录信息丢失
       if(!empty(cookie('user_login_fail')))
       {
           $user_login_fail=cookie('user_login_fail');
           $assign['user_login_fail']=$user_login_fail;
           cookie('user_login_fail',null);
       }

       // 判断是否登录 如果已经登录直接跳转首页
       $token=session('token');
       if(!empty($token))
       {
            return  redirect(get_cas_config('back_default_index'));
       }

       // 判断是否记住用户名
       $assign['name']=get_web_name();
       return view('/login',$assign);
   }

   //TODO 登录验证页面
   public function sign(Request $request)
   {
       // 验证数据
       $post=$request->post();
       if(empty($post))
       {
           return  redirect(config('login_url'));
       }
       $validate =new VAdminUser();
       $result = $validate->scene('login')->check($post);
       if(!$result){
           $this->error($validate->getError());
       }
      //数据查询是否有此用户
       $admin_user=new AdminUser();
       $data=$admin_user->isUser($post['name']);
  	   if(empty($data))
  	   {
  		   $this->error(lang('sign_fail'));
  	   }

       // 判断用户所属角色是否启用
       $admin_role=new AdminRole();
       $is_enable=$admin_role->getFieldById($data['r_id'],'is_enable');
       if($is_enable<IS_ENABLE)
       {
          $this->error(lang('user_role_disable'));
       }

	     // 判断是否锁屏
       action('back/LockScreen/setLock');
       $new_pass=encry($post['repass']);
       if($data['pass']!==$new_pass)
       {
           // 记录密码错误次数 false直接返回错误信息，true 用于ajax ,返回token 数据
            action('back/LockScreen/passErrorNum',[$data['id']]);
       }else
       {
           // 删除缓存数据
           cache('lockscreen_'.$data['id'],null);
           cache('open_lockscreen_'.$data['id'],null);
       }
       //判断是否记住用户名
       if(isset($post['re_name']))
       {
           $cookie_prefix_n=config('cookie.cookie_user_n');
           cookie('admin_user_name',$post['name'],['expire'=>config('cookie.cookie_user_t'),'prefix'=>$cookie_prefix_n]);
        }else
       {
           cookie('admin_user_name',$post['name']);
       }

        //判断用户是否设置同一账号多个设备登录限制
        $time=time();
        $sys_sset=new SysItem();
        $sys_type=new SysType();
        $type_id=$sys_type->getFieldByName('system','id');
        $user_only_sign=$sys_sset->getValueByName($type_id,'user_only_sign');
        // 值为1 ，一个账号允许多设备终端登录；0 禁止
        if($user_only_sign!=get_cas_config('user_only_sign'))
        {
            // 先判断是否有人已经登录了
           $login_user_old=cache('login_time'.$data['id']);
           cookie('login_user_old',$login_user_old);
           cache('login_time'.$data['id'],$time);
        }

       // 存储登录信息
       think_crypt('id',$data['id'],$time);
       think_crypt('r_id',$data['r_id'],$time);
       session('token',$time);

       // 判断是否记录登录操作
       $auth=new AdminAuth();
       $is_enable=$auth->isEnable('back','Login');
       if($is_enable<IS_ENABLE){
            // 页面跳转后台首页
           if($this->request->isAjax()){
               $this->success('ok',url(get_cas_config('back_default_index')));
           }else{
               return  redirect(get_cas_config('back_default_index'));
           }
       }
       // 如果用户记录操作记录
       $ip=$request->ip(0,true);
       $agent=new \Agent();
       $shebei=$agent->getDeviceInfo();
       if(empty($shebei))
       {
           $shebei='unknown device';
       }
       $data = ['uid' => $data['id'], 't' => date('Y-m-d H:i:s',$time),'device'=>$shebei,'ip'=>$ip];
       $is_insert=db('log_login')->insert($data);
       if(empty($is_insert))
       {
           trace(lang('login_user_fecord').implode(' | ',$data),'login');
       }
       // 页面跳转后台首页
       if($this->request->isAjax()){
           $this->success('ok',url(get_cas_config('back_default_index')));
       }else{
           return  redirect(get_cas_config('back_default_index'));
       }
   }

    //TODO 管理员退出
   public function out()
   {
       $id=think_crypt("id",null,session('token'),false);
       outClearInfo($id);
       return redirect(get_cas_config('login_url'));
   }

}