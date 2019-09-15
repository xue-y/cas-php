<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/7
 * Time: 18:35
 */
// 视图语言包
return [
    "re_name"=>"记住用户名",
    "sign_fail"=>"用户名或密码错误或用户禁用",
    "login_user_fecord"=>"登录记录写入失败： ",
    "user_down_info"=>"下线提醒",
    "user_login_fail"=>"登录失败",
    "user_down_con"=>"此账号已在其他设备终端登录 %s 本地自动下线，如果不是本人操作 %s 请登录后修改密码或查看登录记录",
    "user_login_con"=>"登录超时或未登录",
    "not_user"=>"不存在此用户",
    "sign_not"=>"请先登录",
    "pub_login"=>"登录",
    "pub_pass"=>"忘记密码",
    "user_role_disable"=>"用户所属角色已禁用",
];
/*$root_path=\think\facade\Env::get('root_path');
$validator = file_get_contents($root_path.'public/static/validator/js/language/zh_CN.json',1);
$validator=json_decode($validator,true);
return array_merge($view,$validator);*/