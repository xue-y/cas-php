-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2019 �?09 �?15 �?07:09
-- 服务器版本: 5.5.53
-- PHP 版本: 5.6.27

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `aa`
--

-- --------------------------------------------------------

--
-- 表的结构 `cas_admin_auth`
--

CREATE TABLE IF NOT EXISTS `cas_admin_auth` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `controller` varchar(20) NOT NULL COMMENT '模块/控制器名称',
  `module` varchar(20) NOT NULL COMMENT '控制器',
  `name` varchar(20) NOT NULL COMMENT '模块/控制器标识名',
  `action` varchar(30) NOT NULL COMMENT '默认访问方法,为空默认访问配置文件中的默认方法',
  `param` varchar(50) NOT NULL COMMENT 'url参数',
  `pid` smallint(5) unsigned NOT NULL COMMENT '权限父级',
  `icon` varchar(30) NOT NULL DEFAULT 'fa-circle-o' COMMENT '菜单图标',
  `is_menu` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否为菜单;0不是菜单,1菜单',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用;0禁用,1启用',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '排序',
  `is_sys` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否系统内置;系统内置不可删除；1不删除,0可以删除',
  `is_auth` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否基本权限,0不是,1是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='权限管理' AUTO_INCREMENT=63 ;

--
-- 转存表中的数据 `cas_admin_auth`
--

INSERT INTO `cas_admin_auth` (`id`, `controller`, `module`, `name`, `action`, `param`, `pid`, `icon`, `is_menu`, `is_enable`, `sort`, `is_sys`, `is_auth`) VALUES
(1, '', 'admin', '管理员管理', '', '', 0, 'fa-circle-o', 1, 1, 2, 1, 0),
(2, '', 'back', '后台管理', '', '', 0, 'fa-home', 1, 1, 1, 1, 1),
(3, '', 'sys', '配置管理', '', '', 0, 'fa-circle-o', 1, 1, 6, 1, 0),
(4, '', 'log', '日志管理', '', '', 0, 'fa-circle-o', 1, 1, 7, 1, 0),
(5, 'user', 'admin', '管理员用户', '', '', 1, 'fa-user-circle', 1, 1, 1, 1, 0),
(6, 'Role', 'admin', '管理员角色', '', '', 1, 'fa-circle-o', 1, 1, 2, 1, 0),
(7, 'Auth', 'admin', '权限管理', '', '', 1, 'fa-circle-o', 1, 1, 3, 1, 0),
(8, 'Index', 'back', '后台首页', '', '', 2, 'fa-home', 1, 1, 1, 1, 1),
(9, 'Info', 'back', '个人信息', '', '', 2, 'fa-circle-o', 1, 1, 2, 1, 1),
(10, 'Login', 'back', '登录记录', '', '', 2, 'fa-circle-o', 1, 1, 3, 1, 1),
(11, 'Operate', 'back', '操作记录', '', '', 2, 'fa-circle-o', 1, 1, 4, 1, 1),
(12, 'Lock', 'back', '锁屏', '', '', 2, 'fa-circle-o', 1, 1, 5, 1, 1),
(13, 'Type', 'sys', '设置管理', '', '', 3, 'fa-circle-o', 1, 1, 1, 1, 0),
(14, 'Item', 'sys', '系统设置', 'see', 't_id=1', 3, 'fa-gears', 1, 1, 3, 1, 0),
(15, 'Item', 'sys', '邮箱设置', 'see', 't_id=2', 3, 'fa-envelope-open-o', 1, 1, 4, 1, 0),
(16, 'Item', 'sys', '上传设置', 'see', 't_id=3', 3, 'fa-cloud-upload', 1, 1, 5, 1, 0),
(17, 'Login', 'log', '登录记录', '', '', 4, 'fa-circle-o', 1, 1, 0, 1, 0),
(18, 'Operate', 'log', '操作记录', '', '', 4, 'fa-circle-o', 1, 1, 0, 1, 0),
(50, '', 'sdsds', 'sdf', '', '', 0, 'fa-circle-o', 1, 0, 9, 0, 0),
(59, 'Item', 'sys', '测试', 'see', 't_id=13', 3, 'fa-circle-o', 0, 1, 6, 0, 0),
(61, 'Item', 'sys', '配置项管理', '', '', 3, 'fa-circle-o', 1, 1, 2, 0, 0),
(62, 'Item', 'sys', '配置项设置', 'see', '', 3, 'fa-circle-o', 1, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `cas_admin_role`
--

CREATE TABLE IF NOT EXISTS `cas_admin_role` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL COMMENT '角色名称',
  `describe` varchar(20) NOT NULL COMMENT '角色描述',
  `a_id` varchar(255) NOT NULL COMMENT 'admin_auth权限ID集合;多个ID中间用英文逗号分隔',
  `is_enable` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用;0禁用,1启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员角色' AUTO_INCREMENT=26 ;

--
-- 转存表中的数据 `cas_admin_role`
--

INSERT INTO `cas_admin_role` (`id`, `name`, `describe`, `a_id`, `is_enable`) VALUES
(1, '超级管理员', '超级管理员拥有全部权限', 'all', 1),
(24, 'ewr', '', '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `cas_admin_user`
--

CREATE TABLE IF NOT EXISTS `cas_admin_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '用户名',
  `pass` varchar(40) NOT NULL COMMENT '系统用户密码',
  `r_id` tinyint(3) unsigned NOT NULL COMMENT '角色ID',
  `email` varchar(40) NOT NULL COMMENT '邮箱',
  `is_enable` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否启用;0禁用,1启用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `n` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员用户' AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `cas_admin_user`
--

INSERT INTO `cas_admin_user` (`id`, `name`, `pass`, `r_id`, `email`, `is_enable`) VALUES
(1, 'admin', 'a1604297f44b13955235245b2497399d7a93dd60', 1, '', 1),
(9, 'sdffsd', 'a160d41d8cd98f00b204e9800998ecf8427edd60', 24, '', 1),
(10, 'sdff', 'a160d41d8cd98f00b204e9800998ecf8427edd60', 24, '', 1),
(11, 'sdfsf', 'a160d41d8cd98f00b204e9800998ecf8427edd60', 25, '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `cas_log_login`
--

CREATE TABLE IF NOT EXISTS `cas_log_login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` smallint(5) unsigned NOT NULL COMMENT '登录用户ID',
  `t` datetime NOT NULL COMMENT '登录时间',
  `device` varchar(100) NOT NULL COMMENT '登录设备',
  `ip` varchar(20) NOT NULL COMMENT '登录IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='登录记录' AUTO_INCREMENT=372 ;

--
-- 转存表中的数据 `cas_log_login`
--

INSERT INTO `cas_log_login` (`id`, `uid`, `t`, `device`, `ip`) VALUES
(255, 1, '2019-09-02 19:49:15', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(368, 1, '2019-09-12 17:53:22', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(361, 1, '2019-09-11 17:20:33', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(269, 1, '2019-09-04 21:13:42', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(270, 1, '2019-09-04 21:23:27', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(369, 1, '2019-09-13 11:23:46', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(370, 1, '2019-09-14 17:38:19', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(304, 1, '0000-00-00 00:00:00', '', ''),
(305, 1, '0000-00-00 00:00:00', '', ''),
(306, 1, '0000-00-00 00:00:00', '', ''),
(307, 1, '0000-00-00 00:00:00', '', ''),
(308, 1, '0000-00-00 00:00:00', '', ''),
(309, 1, '0000-00-00 00:00:00', '', ''),
(310, 1, '0000-00-00 00:00:00', '', ''),
(311, 1, '0000-00-00 00:00:00', '', ''),
(312, 1, '0000-00-00 00:00:00', '', ''),
(313, 1, '0000-00-00 00:00:00', '', ''),
(314, 1, '0000-00-00 00:00:00', '', ''),
(315, 1, '0000-00-00 00:00:00', '', ''),
(316, 1, '0000-00-00 00:00:00', '', ''),
(317, 1, '0000-00-00 00:00:00', '', ''),
(318, 1, '0000-00-00 00:00:00', '', ''),
(319, 1, '0000-00-00 00:00:00', '', ''),
(320, 1, '0000-00-00 00:00:00', '', ''),
(321, 1, '0000-00-00 00:00:00', '', ''),
(322, 1, '0000-00-00 00:00:00', '', ''),
(323, 1, '0000-00-00 00:00:00', '', ''),
(324, 1, '0000-00-00 00:00:00', '', ''),
(325, 1, '0000-00-00 00:00:00', '', ''),
(326, 1, '0000-00-00 00:00:00', '', ''),
(327, 1, '0000-00-00 00:00:00', '', ''),
(328, 1, '0000-00-00 00:00:00', '', ''),
(329, 1, '0000-00-00 00:00:00', '', ''),
(330, 1, '0000-00-00 00:00:00', '', ''),
(331, 1, '0000-00-00 00:00:00', '', ''),
(332, 1, '0000-00-00 00:00:00', '', ''),
(333, 1, '0000-00-00 00:00:00', '', ''),
(334, 1, '0000-00-00 00:00:00', '', ''),
(335, 1, '0000-00-00 00:00:00', '', ''),
(336, 1, '0000-00-00 00:00:00', '', ''),
(337, 1, '0000-00-00 00:00:00', '', ''),
(338, 1, '0000-00-00 00:00:00', '', ''),
(339, 1, '0000-00-00 00:00:00', '', ''),
(348, 1, '2019-09-06 08:19:25', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(343, 1, '0000-00-00 00:00:00', '', ''),
(344, 1, '0000-00-00 00:00:00', '', ''),
(345, 1, '0000-00-00 00:00:00', '', ''),
(346, 1, '0000-00-00 00:00:00', '', ''),
(347, 1, '2019-09-05 14:13:10', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(349, 1, '2019-09-06 09:24:41', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(350, 1, '2019-09-06 11:45:58', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(351, 1, '2019-09-06 19:29:32', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(352, 1, '2019-09-07 17:45:48', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(357, 1, '2019-09-10 21:02:21', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(358, 1, '2019-09-10 22:27:14', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(359, 1, '2019-09-10 22:44:30', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(360, 1, '2019-09-11 15:47:00', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(362, 1, '2019-09-11 17:21:50', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(363, 1, '2019-09-11 17:32:34', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(364, 1, '2019-09-11 17:53:38', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(365, 1, '2019-09-11 17:58:11', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(366, 1, '2019-09-11 18:01:34', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(367, 1, '2019-09-11 18:45:54', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1'),
(371, 1, '2019-09-15 08:23:05', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa', '127.0.0.1');

-- --------------------------------------------------------

--
-- 表的结构 `cas_log_operate`
--

CREATE TABLE IF NOT EXISTS `cas_log_operate` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `uid` smallint(5) unsigned NOT NULL COMMENT '操作用户ID',
  `t` datetime NOT NULL COMMENT '操作时间',
  `behavior` smallint(5) unsigned NOT NULL COMMENT '操作行为',
  `details` varchar(255) NOT NULL COMMENT '操作详情',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='操作记录' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `cas_log_operate`
--

INSERT INTO `cas_log_operate` (`id`, `uid`, `t`, `behavior`, `details`) VALUES
(2, 1, '0000-00-00 00:00:00', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `cas_sys_item`
--

CREATE TABLE IF NOT EXISTS `cas_sys_item` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `t_id` tinyint(3) unsigned NOT NULL COMMENT '设置项分类ID',
  `name` varchar(20) NOT NULL COMMENT '设置项名称',
  `describe` varchar(20) NOT NULL COMMENT '设置项标识名',
  `val` varchar(255) NOT NULL COMMENT '设置项值;多个值中间用英文逗号分隔',
  `type` varchar(10) NOT NULL DEFAULT 'text' COMMENT '表单字段类型',
  `notes` varchar(100) DEFAULT NULL COMMENT '设置项说明',
  `sort` smallint(5) unsigned NOT NULL COMMENT '排序',
  `is_sys` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否系统内置;系统内置不可删除；1不删除,0可以删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='menu_sys_sset' AUTO_INCREMENT=26 ;

--
-- 转存表中的数据 `cas_sys_item`
--

INSERT INTO `cas_sys_item` (`id`, `t_id`, `name`, `describe`, `val`, `type`, `notes`, `sort`, `is_sys`) VALUES
(1, 1, 'user_only_sign', '设备登录', '0', 'text', 'user_only_sign', 0, 1),
(2, 1, 'user_operate_auth', '', 'admin', '', 'user_operate_auth', 0, 1),
(3, 1, 'pass_error_num', '', '50', '', 'pass_error_num', 0, 1),
(4, 1, 'lock_t', '', '86400', '', 'lock_t', 0, 1),
(5, 2, 'email_interval_t', '', '120', '', 'email_interval_t', 0, 1),
(6, 2, 'smtp_server', '', 'smtp.sina.com', '', 'smtp_server', 0, 1),
(7, 2, 'smtp_server_port', '', '25', '', 'smtp_server_port', 0, 1),
(8, 2, 'smtp_user_email', '', '', '', 'smtp_user_email', 0, 1),
(9, 2, 'smtp_pass', '', '', '', 'smtp_pass', 0, 1),
(10, 2, 'email_send_c', '', '50', '', '今日发送邮件已达到最大限度 %d 次，请 %d 小时后在继续操作', 0, 1),
(11, 2, 'email_t', '', '86400', '', 'email_t', 0, 1),
(12, 2, 'email_activate_t', '', '18000', '', 'email_activate_t', 0, 1),
(15, 1, 'sd', 'sdf', '', 'text', '', 0, 0),
(16, 12, 'time', '时间', '14:50:17', 'time', '测试时间', 0, 0),
(24, 12, 'dfsdf', 'sdfs', '121', 'text', '', 0, 0),
(25, 12, 'sdf', 'sdfds', '1212', 'text', '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `cas_sys_type`
--

CREATE TABLE IF NOT EXISTS `cas_sys_type` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '设置分类名称',
  `describe` varchar(20) NOT NULL COMMENT '设置分类标识名',
  `is_menu` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否为系统配置子菜单,0不是菜单,1菜单',
  `is_sys` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统内置;系统内置不可删除；1不删除,0可以删除',
  `a_id` smallint(5) unsigned NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='menu_sys_stype' AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `cas_sys_type`
--

INSERT INTO `cas_sys_type` (`id`, `name`, `describe`, `is_menu`, `is_sys`, `a_id`) VALUES
(1, 'system', '系统设置', 1, 1, 14),
(2, 'email', '邮箱设置', 1, 1, 15),
(3, 'upfile', '上传设置', 1, 1, 16),
(12, 'sdfsdf', '测试', 0, 0, 59);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
