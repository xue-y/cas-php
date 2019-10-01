<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/8
 * Time: 16:38
 */

namespace app\admin\validate;
use think\Validate;

class AdminUser extends Validate
{
    // message 字段验证信息与 前端 jsonp 语言包变量名一致  默认登录
    protected $regex=['pass'=>'^[\w\.\@\-]{6,20}$'];
    protected $message =[
        '__token__'=>'form_token',
    ];

    protected  $rule=[
        '__token__' => 'require|token',
        // 登录
        'name'      => 'require|length:2,20',
        'repass'    => 'require|regex:pass', // 确认必填密码
        'v_code'    => 'require|regex:[\w]{5}|captcha',
        // 个人信息 name 
        'oldpass'   => 'regex:pass',
        'pass'      => 'regex:pass',
        'pass2'     => 'confirm:pass|regex:pass',
        'email'     => 'email',
        // 添加修改用户
        'r_id'      => 'require|integer|egt:1',
        // 找回密码发送邮件
        'reemail'   => 'require|email'
    ];

    protected $scene=[
        'login'           => ['name', '__token__', 'repass', 'v_code'], // 用户登录
        'info_save'       => ['name'=>'require|unique:admin_user,name', '__token__', 'pass', 'pass2', 'oldpass'], // 个人信息
        'unlock'          => ['__token__', 'repass'],  // 解屏
        'pass_sendEmail'  => ['__token__','name','reemail','v_code'],  //找回密码验证邮箱
    ];

    //添加修改用户
    public function sceneUserSave()
    {
        return $this->only(['__token__','name','pass','pass2','r_id'])
            ->append('name', 'unique:admin_user,name');
    }

    //重设密码
    public function scenePassRepassSave()
    {
        return $this->only(['__token__','pass','pass2'])
            ->append('pass', 'require');
    }

    // 邮箱绑定解绑
    public function sceneInfoSendEmail(){
        return $this->only(['email'])
            ->append('email', 'require');
    }

    // 个人信息修改
    public function sceneInfoSave(){
        return $this->only(['__token__','name','pass','pass2'])
            ->append('name', 'unique:admin_user,name');
    }
}