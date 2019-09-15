<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/28
 * Time: 12:28
 */

namespace app\pub\controller;

use app\common\model\AdminUser;
use app\common\model\SysItem;
use app\common\model\SysType;
use app\admin\validate\AdminUser as VAdminUser;
use app\common\controller\Base;
use think\Db;

class Pass extends Base
{
    //TODO 找回密码
    public function index()
    {
        // 进入这个页面自动清除登录信息
        if((session('?token')))
        {
            session('token', null);
        }
        $sys_sset=new SysItem();
        $sys_type=new SysType();
        $type_id=$sys_type->getFieldByName('email','id');
        $assign['email_interval_t']=$sys_sset->getValueByName($type_id,'email_interval_t');
        return view('/pass',$assign);
    }

    //TODO 验证数据
    public function sendEmail()
    {
        // 收集数据
        $post=input("post.");
        if(empty($post))
        {
            $this->error(lang('error_page'));
        }

        // 验证数据
        $validate=new VAdminUser();
        $result =$validate->scene('pass_sendEmail')->check($post);
        if (!$result) {
            $this->error($validate->getError());
        }

        // 数据库验证用户名+邮箱 取得用户id
        $w[]=['name','=',$post['name']];
        $w[]=['email','=',$post['reemail']]; // 前端验证  -- 名称冲突，重新命名
        $id=Db::name('admin_user')->where($w)->field('id')->find();
        if(empty($id))
        {
            $this->error(lang('not_user'));
        }
        $data['id']=$id['id'];
        $data['name']=$post['name'];
        $data['email']=$post['reemail'];
        $data["behavior"]='pub_pass';
        // 发送邮件
        return action('pub/Activate/execSendEmail',['post'=>$data,'sys_sset'=>null]);
    }

    public function repass()
    {
        // 发送邮件后跳转到的页面 取得id
       if(!session('?email_info_t'))
       {
            $this->error(lang('error_page'),'index');
       }
       return $this->fetch('/repass');
    }

    //TODO 重置密码
    public function repassSave()
    {
        // 收集数据
        $post=input('post.');
        if(empty($post))
        {
            $this->error(lang('error_page'),'index');
        }

        // 发送邮件后跳转到的页面 取得id
        if(!session('?email_info_t'))
        {
            $this->error(lang('error_page'),'index');
        }

        // 取得加密key-- 解密 email_id 从邮箱传递的ID
        $t=session('email_info_t');
        $crypt_id=session('email_id');
        $u_id=crypt_str($crypt_id,$t,false);
        if(!is_numeric($u_id))
        {
            $this->error(lang('error_id'));
        }

        $data['pass']=$post['newpass'];
        $data['pass2']=$post['newpass2'];
        $data['__token__']=$post['__token__'];

        // 验证数据
        $validate =new VAdminUser();
        $result = $validate->scene('PassRepassSave')->check($data);
        if(!$result){
            $this->error($validate->getError());
        }

        // 取得原密码
       $admin_user=new AdminUser();
       $old_pass=$admin_user->getFieldById($u_id,'pass');
       if($old_pass===encry($data['pass']))
       {
           $this->success(lang('edit_success'),url('Login/index'));
       }

        // 更新数据
        $is_up=$admin_user->where('id','=',$u_id)->setField('pass',encry($data['pass']));
            
        if($is_up==1)
        {
            session('email_info_t',null);
            session('email_id',null);
            $this->success(lang('edit_success'),'Login/index');
        }else
        {
            $this->error(lang('edit_fail'));
        }
    }
}