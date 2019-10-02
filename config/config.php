<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/8/20
 * Time: 21:04
 */
define('IS_MENU',1);
define('IS_ENABLE',1);
define('IS_SYS',1);
define('IS_AUTH',1);
define('USER_LOGIN_FAIL',1);
define('USER_DOWN_INFO',1);
return [
    //自定义样式路径常量
    'com_img'   => '/static/img/',
    'com_css'   => '/static/css/',
    'com_js'    => '/static/js/',
    'cas_img'   => '/static/casphp/images/',
    'cas_css'   => '/static/casphp/css/',
    'cas_js'    => '/static/casphp/js/',
    'layui'     => '/static/layui/',
    'validator' => '/static/validator/',

    // 自定义配置
    'user_only_sign'=>1,//允许多设备终端登录,1 允许，0 禁止
    'lock_screen_val'=>1,// 1 用户锁屏

    // 默认密码
    'default_pass'=>'123456',
    'default_char'=>'utf8',
    // 加密秘钥
    'secret_key'=>'*./?(&%(*!~',
    //后台系统登录地址
    'login_url'=>'pub/Login/index',
    // 后台首页地址
    'back_default_index'=>'back/Index/main',
    // 后台锁屏地址-->request->path()路由地址---> 原地址; /back/Lock  ---> /back/LockScreen/index
    'lock_screen'=>'back/Lock/index',
    // 用户登录即可访问的模块,不需要分配权限
    'user_com_modular'=>'back',
    // 超级管理员 权限集合
    'admin_auth'=>'all',
    //tp 每页数据条数10
    'list_rows'=>10,
    // 不需要登录可访问的模块
    'pub_module'=>['pub','error'],
    // 记录操作的模块
    'operate_module'=>['admin'],
    // 记录操作的方法
    'operate_action'=>['save','ajaxOperate','delete'],
    // 框架名称
    'web_tit'=>'CasPHP系统',
    // 是否ajax 方式提交操作
    'ajax_submit'=>true,   // 默认不使用ajax
];