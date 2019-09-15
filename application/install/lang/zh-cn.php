<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-9
 * Time: 下午4:09
 * 安装视图 控制器返回信息 sql文件 语言包
 */
return [
    // install view
    "install"=>"后台管理系统安装向导",
    "explain"=>"安装前请手动创建空数据库，同时系统会自动清空运行目录 %s
                    语言会自动根据浏览器自动选择，如果列表中不存在本地语言，自动选择简体中文 %s
                    安装时使用的什么语言，数据表中的数据就是什么语言注释字段 %s
                    系统用户密码如果未空使用默认密码 : %s",
    "lang"=>"系统语言",
    "host"=>"主机名称",
    "port"=>"数据库端口",
    "dbuser"=>"数据库用户名",
    "dbpass"=>"数据库密码",
    "dbname"=>"数据库名称",
    "prefix"=>"数据库表前缀",
    "charset"=>"数据库字符编码",
    "name"=>"系统用户名",
    "pass"=>"用户密码",
    "pass2"=>"用户确认密码",
    "install_alter"=>"安装提示信息",
    "default_collation"=>"默认校对集",
    "install_submit"=>"安装系统",
     // 左菜单 数据表注释
    "menu_admin"=>"管理员管理",
    "menu_back"=>"后台管理",
    "menu_sys"=>"系统管理",
    "menu_log"=>"日志管理",
    "menu_admin_user"=>"管理员用户",
    "menu_admin_role"=>"管理员角色",
    "menu_admin_auth"=>"权限管理",
    "menu_back_index"=>"后台首页",
    "menu_back_info"=>"个人信息",
    "menu_log_login"=>"登录记录",
    "menu_log_operate"=>"操作记录",
    "menu_back_lock"=>"锁屏",
    "menu_sys_type"=>"配置管理",
    "menu_sys_sset"=>"配置项管理",
    "menu_sys_system"=>"系统配置",
    "menu_sys_email"=>"邮箱配置",
    "menu_sys_upfile"=>"上传配置",
    // 判断
    "is_enable"=>"是否启用;0禁用,1启用",
    "is_menu"=>"是否为菜单;0不是菜单,1菜单",
    "is_sys"=>"是否系统内置;系统内置不可删除;1不删除,0可以删除",
    "is_auth"=>"是否属于基本权限;登录即可访问的页面;0不是,1是",
    // 数据 设置变量
    "administrator"=>"超级管理员",
    "all_permissions"=>"超级管理员拥有全部权限",
    "user_only_sign"=>"是否允许同一账号多设备终端同时登陆1允许0不允许",
    "user_operate_auth"=>"记录用户哪些操作行为,admin_auth表中模块名称",
    "smtp_server"=>"SMTP邮箱服务器",
    "smtp_server_port"=>"SMTP服务器端口",
    "smtp_user_email"=>"SMTP服务器的用户邮箱账号",
    "smtp_pass"=>"SMTP服务器的用户密码",
    "pass_error_num"=>"管理员登录密码错误次数，超过限制自动封锁，lock_t 时间后自动解锁",
    "email_interval_t"=>"发送邮件时间间隔，时间单位秒",
    "email_send_c"=>"email_t 时间内可发送的邮件次数",
    "email_t"=>"邮件发送超过最大限额多长时间后可以再次发送，单位是秒",
    "email_activate_t"=>"发送邮件后邮件有效时间内激活，单位是秒",
];