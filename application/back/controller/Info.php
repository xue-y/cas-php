<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/27
 * Time: 14:16
 * 管理员个人信息页面
 */

namespace app\back\controller;

use app\common\controller\Base;
use app\common\model\AdminUser;
use app\common\model\SysItem;
use app\common\model\SysType;
use app\admin\validate\AdminUser as VAdminUser;

class Info extends Base
{
    //TODO 视图页面
    public function index()
    {
        $admin_user=new AdminUser();
        $sys_sset=new SysItem();
        $sys_type=new SysType();
        $type_id=$sys_type->getFieldByName('email','id');
        $assign['email_interval_t']=$sys_sset->getValueByName($type_id,'email_interval_t');
        // 取得邮箱
        $assign['email']=$admin_user->getFieldById(request()->user_id,'email');
        return view('/info',$assign);
    }

    //TODO 绑定解绑邮件
    public function sendEmail()
    {
        $post=input("post.");
        if(empty($post))
        {
            $this->error(lang('reemail_empty'));
        }

        // 验证数据
        $validate =new VAdminUser();
        $result = $validate->scene('InfoSendEmail')->check($post);
        if(!$result){
            $this->error($validate->getError());
        }

        // 判断是否超过发送邮件总次数
        $sys_sset=new SysItem();
        $sys_type=new SysType();
        $type_id=$sys_type->getFieldByName('email','id');
        $email_send_c=$sys_sset->getValueByName($type_id,'email_send_c'); // 发送邮件总次数
        $u_id=request()->user_id;
        $email_send_num=cache('email_send_num_'.$u_id);
        if(isset($email_send_num) && ($email_send_num>=$email_send_c))
        {
            $email_t=$sys_sset->getValueByName($type_id,'email_t');
            $this->error(lang('email_send_c',[$email_send_c,$email_t]));
        }

        $data['name']=request()->user_name;
        $data['id']=request()->user_id;
        $data['email']=$post['email'];
        $data['behavior']=$post['behavior'];

        // 执行发送邮件 Execute Sending Mail
        return action('pub/Activate/execSendEmail',['post'=>$data,'sys_sset'=>$sys_sset]);
    }

    // TODO 更新用户信息
    public function save()
    {
        $post=input("post.");
        if(empty($post))
        {
            $this->error(lang('error_page'));
        }
        // 验证数据
        $validate=new VAdminUser();
        if (!$validate->scene('InfoSave')->check($post)) {
            $this->error($validate->getError());
        }

        $admin_user=new AdminUser();
        $id=request()->user_id;
        $data=[];
        // 修改密码
        if(!empty($post['pass_old']) && !empty($post['pass']))
        {
            // 原密码与新密码一致
            if($post['pass_old']===$post['pass'])
            {
                $this->error(lang('pass_oldpass'));
            }
            // 判断原密码是否正确
            $old_pass=$admin_user->getFieldById($id,'pass');
            if($old_pass!=encry($post['pass_old']))
            {
                $this->error(lang('pass_oldpass_error'));
            }
            $data["pass"]=encry($post["pass"]);
        }

        // 判断是否修改用户名
        if($post['name']!==request()->user_name)
        {
            // 验证用户名是否重复
            /*$is_name_unique=$admin_user->isNameUnique($post['name'],$id);
            if($is_name_unique>=1){
                $this->error(lang('admin_user')['name_unique']);
            }*/
            $data['name']=$post['name'];
        }

        if(!empty($data))
        {
           session_cache_limiter('private');
           $is_save=$admin_user->save($data,['id'=>$id]);
           if($is_save===true)
           {
               if(!empty($data['name']))update_web_name($post['name']);
               $this->success(lang('edit_success'));
           }else
           {
               $this->error(lang('edit_fail'));
           }
        }
        // 页面跳转后台首页
        if($this->request->isAjax()){
            $this->success(lang('edit_success'));
        }else{
            $this->redirect('index');
        }
    }
}