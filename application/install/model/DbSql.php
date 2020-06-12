<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/6
 * Time: 20:48
 * 安装系统 sql
 */

namespace app\install\model;

class DbSql
{
    private $sql=array();
    public function index($prefix,$charset)
    {
        $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}admin_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT, 
  `name` varchar(20) NOT NULL COMMENT '".lang('admin_user')['name']."',
  `pass` varchar(40) NOT NULL COMMENT '".lang('pass')."',
  `r_id` smallint(5) unsigned NOT NULL COMMENT '".lang('admin_user')['r_id']."',
  `email` varchar(40) NOT NULL COMMENT '".lang('email')."',
  `is_enable` tinyint(3) NOT NULL DEFAULT '1' COMMENT '".lang('is_enable')."',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset} COMMENT='".lang('menu_admin_user')."' AUTO_INCREMENT=1 ;";

     $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}admin_role` ( 
`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(10) NOT NULL COMMENT '".lang('admin_role')['name']."',
 `describe` varchar(20) NOT NULL COMMENT '".lang('admin_role')['describe']."',
 `a_id` varchar(255) NOT NULL COMMENT '".lang('admin_role')['a_id']."', 
 `is_enable` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '".lang('is_enable')."',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset} COMMENT='".lang('menu_admin_role')."' AUTO_INCREMENT=1 ;" ;

     $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}admin_auth` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `controller` varchar(20) NOT NULL COMMENT '".lang('admin_auth')['controller']."',
  `module` varchar(20) NOT NULL COMMENT '".lang('admin_auth')['module']."',
  `name` varchar(20) NOT NULL COMMENT '".lang('admin_auth')['name']."',
  `action` varchar(30) NOT NULL COMMENT '".lang('admin_auth')['action_info']."',
  `param` varchar(50) NOT NULL COMMENT '".lang('admin_auth')['param']."',
  `pid` smallint(5) unsigned NOT NULL COMMENT '".lang('admin_auth')['pid']."',
  `icon` varchar(20) NOT NULL COMMENT '".lang('admin_auth')['icon']."',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '".lang('sort')."',
  `is_menu` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '".lang('is_menu')."',
  `is_enable` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '".lang('is_enable')."',
  `is_sys` tinyint(1) NOT NULL DEFAULT '0' COMMENT '".lang('is_sys')."',
  `is_auth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '".lang('is_auth')."',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset} COMMENT='".lang('menu_admin_auth')."' AUTO_INCREMENT=1 ;";


     $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}log_login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` smallint(5) unsigned NOT NULL COMMENT '".lang('log_login')['uid']."',
  `t` datetime NOT NULL COMMENT '".lang('log_login')['t']."',
  `device` varchar(100) NOT NULL COMMENT '".lang('log_login')['device']."',
  `ip` varchar(20) NOT NULL COMMENT '".lang('log_login')['ip']."',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset} COMMENT='".lang('menu_log_login')."' AUTO_INCREMENT=1 ;";

     $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}log_operate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` smallint(5) unsigned NOT NULL COMMENT '".lang('log_operate')['uid']."',
  `t` datetime NOT NULL COMMENT '".lang('log_operate')['t']."',
  `behavior` smallint(5) unsigned NOT NULL COMMENT '".lang('log_operate')['behavior']."',
  `details` varchar(255) NOT NULL COMMENT '".lang('log_operate')['details']."',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset} COMMENT='".lang('menu_log_operate')."' AUTO_INCREMENT=1 ;";

     $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}sys_type` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '".lang('sys_type')['name']."',
  `describe` varchar(20) NOT NULL COMMENT '".lang('sys_type')['describe']."',
  `is_menu` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '".lang('sys_type')['is_menu']."',
  `is_sys` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '".lang('is_sys')."',
  `a_id` smallint(5) unsigned NOT NULL DEFAULT '0'  COMMENT '".lang('sys_type')['a_id']."',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset} COMMENT='".lang('menu_sys_stype')."' AUTO_INCREMENT=1 ;";

    $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}sys_item` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `t_id` smallint(5) unsigned NOT NULL COMMENT '".lang('sys_item')['t_id']."',
  `name` varchar(20) NOT NULL COMMENT '".lang('sys_item')['name']."',
  `describe` varchar(20) NOT NULL COMMENT '".lang('sys_item')['describe']."',
  `val` varchar(255) NOT NULL COMMENT '".lang('sys_item')['val']."',
  `type` varchar(10) NOT NULL DEFAULT 'text' COMMENT '".lang('sys_item')['type']."',
  `notes` varchar(100) DEFAULT NULL COMMENT '".lang('sys_item')['notes']."',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '".lang('sort')."',
  `is_sys` tinyint(1) NOT NULL DEFAULT '0' COMMENT '".lang('is_sys')."',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset} COMMENT='".lang('menu_sys_item')."' AUTO_INCREMENT=1 ;";

   $this->sql[]= "INSERT INTO `{$prefix}admin_auth` (`id`, `controller`, `module`, `name`, `action`, `param`, `pid`, `icon`, `is_menu`, `is_enable`, `sort`, `is_sys`, `is_auth`) VALUES
(1, '', 'admin', '".lang('menu_admin')."', '', '', 0, 'fa-circle-o', 1, 1, 2, 1, 0),
(2, '', 'back', '".lang('menu_back')."', '', '', 0, 'fa-home', 1, 1, 1, 1, 1),
(3, '', 'sys', '".lang('menu_sys')."', '', '', 0, 'fa-circle-o', 1, 1, 6, 1, 0),
(4, '', 'log', '".lang('menu_log')."', '', '', 0, 'fa-book', 1, 1, 7, 1, 0),
(5, 'user', 'admin', '".lang('menu_admin_user')."', '', '', 1, 'fa-user-circle', 1, 1, 1, 1, 0),
(6, 'Role', 'admin', '".lang('menu_admin_role')."', '', '', 1, 'fa-circle-o', 1, 1, 2, 1, 0),
(7, 'Auth', 'admin', '".lang('menu_admin_auth')."', '', '', 1, 'fa-circle-o', 1, 1, 3, 1, 0),
(8, 'Index', 'back', '".lang('menu_back_index')."', '', '', 2, 'fa-home', 1, 1, 1, 1, 1),
(9, 'Info', 'back', '".lang('menu_back_info')."', '', '', 2, 'fa-circle-o', 1, 1, 2, 1, 1),
(10, 'Login', 'back', '".lang('menu_log_login')."', '', '', 2, 'fa-circle-o', 1, 1, 3, 1, 1),
(11, 'Operate', 'back', '".lang('menu_log_operate')."', '', '', 2, 'fa-circle-o', 1, 1, 4, 1, 1),
(12, 'Lock', 'back', '".lang('menu_back_lock')."', '', '', 2, 'fa-circle-o', 1, 1, 5, 1, 1),
(13, 'Type', 'sys', '".lang('menu_sys_type')."', '', '', 3, 'fa-circle-o', 1, 1, 1, 1, 0),
(14, 'Item', 'sys', '".lang('menu_sys_system')."', 'see', 't_id=1', 3, 'fa-gears', 1, 1, 3, 1, 0),
(15, 'Item', 'sys', '".lang('menu_sys_email')."', 'see', 't_id=2', 3, 'fa-envelope-open-o', 1, 1, 4, 1, 0),
(16, 'Item', 'sys', '".lang('menu_sys_upfile')."', 'see', 't_id=3', 3, 'fa-cloud-upload', 0, 1, 5, 1, 0),
(17, 'Item', 'sys', '".lang('menu_sys_item')."', '', '', 3, 'fa-circle-o', 1, 1, 2, 0, 0),
(18, 'Item', 'sys', '".lang('menu_sys_sset')."', 'see', 'see', 3, 'fa-circle-o', 1, 1, 1, 0, 0),
(19, 'Login', 'log', '".lang('menu_log_login')."', '', '', 4, 'fa-bookmark-o', 1, 1, 0, 1, 0),
(20, 'Operate', 'log', '".lang('menu_log_operate')."', '', '', 4, 'fa-circle-o', 1, 1, 0, 1, 0);";

  $admin_auth=get_cas_config('admin_auth');

   $this->sql[]="INSERT INTO `{$prefix}admin_role`  (`id`, `name`, `describe`, `a_id`, `is_enable`) VALUES
(1, '".lang('administrator')."', '".lang('all_permissions')."','".$admin_auth."',1);";

   $this->sql[]="INSERT INTO `{$prefix}sys_type` (`id`, `name`, `describe`, `is_menu`, `is_sys`) VALUES
(1, 'system', '".lang('menu_sys_system')."', 0, 1),
(2, 'email', '".lang('menu_sys_email')."', 0, 1),
(3, 'upfile', '".lang('menu_sys_upfile')."', 0, 1);";

    $this->sql[]="INSERT INTO `{$prefix}sys_item`  (`id`, `t_id`, `name`, `describe`, `val`, `type`, `notes`, `is_sys`) VALUES
(1, 1, 'user_only_sign', 'user_only_sign', '0', '', '".lang('user_only_sign')."', 1),
(3, 1, 'pass_error_num', 'pass_error_num', '5', '', '".lang('pass_error_num')."', 1),
(4, 1, 'lock_t', 'lock_t', '86400', '', '".lang('lock_t')."', 1),
(5, 2, 'email_interval_t', 'email_interval_t', '120', '', '".lang('email_interval_t')."', 1),
(6, 2, 'smtp_server', 'smtp_server', 'smtp.sina.com', '', '".lang('smtp_server')."', 1),
(7, 2, 'smtp_server_port', 'smtp_server_port', '25', '', '".lang('smtp_server_port')."', 1),
(8, 2, 'smtp_user_email', 'smtp_user_email', '', '', '".lang('smtp_user_email')."', 1),
(9, 2, 'smtp_pass', 'smtp_pass', '', '', '".lang('smtp_pass')."', 1),
(10, 2, 'email_send_c', 'email_send_c', '50', '', '".lang('email_send_c')."', 1),
(11, 2, 'email_t', 'email_t', '86400', '', '".lang('email_t')."', 1),
(12, 2, 'email_activate_t', 'email_activate_t', '1800', '', '".lang('email_activate_t')."', 1);";

        return $this->sql;
    }

}