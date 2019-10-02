-- MySQL dump 10.13  Distrib 5.5.53, for Win32 (AMD64)
--
-- Host: localhost    Database: aa
-- ------------------------------------------------------
-- Server version	5.5.53

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cas_admin_auth`
--

DROP TABLE IF EXISTS `cas_admin_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cas_admin_auth` (
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
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COMMENT='权限管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cas_admin_auth`
--

LOCK TABLES `cas_admin_auth` WRITE;
/*!40000 ALTER TABLE `cas_admin_auth` DISABLE KEYS */;
INSERT INTO `cas_admin_auth` VALUES (1,'','admin','管理员管理','','',0,'fa-circle-o',1,1,2,1,0),(2,'','back','后台管理','','',0,'fa-home',1,1,1,1,1),(3,'','sys','配置管理','','',0,'fa-circle-o',1,1,6,1,0),(4,'','log','日志管理','','',0,'fa-book',1,1,7,1,0),(5,'user','admin','管理员用户','','',1,'fa-user-circle',1,1,1,1,0),(6,'Role','admin','管理员角色','','',1,'fa-circle-o',1,1,2,1,0),(7,'Auth','admin','权限管理','','',1,'fa-circle-o',1,1,3,1,0),(8,'Index','back','后台首页','','',2,'fa-home',1,1,1,1,1),(9,'Info','back','个人信息','','',2,'fa-circle-o',1,1,2,1,1),(10,'Login','back','登录记录','','',2,'fa-circle-o',1,1,3,1,1),(11,'Operate','back','操作记录','','',2,'fa-circle-o',1,1,4,1,1),(12,'Lock','back','锁屏','','',2,'fa-circle-o',1,1,5,1,1),(13,'Type','sys','配置分类','','',3,'fa-circle-o',1,1,1,1,0),(14,'Item','sys','系统设置','see','t_id=1',3,'fa-gears',1,1,3,1,0),(15,'Item','sys','邮箱设置','see','t_id=2',3,'fa-envelope-open-o',1,1,4,1,0),(16,'Item','sys','上传设置','see','t_id=3',3,'fa-cloud-upload',0,1,5,1,0),(17,'Login','log','登录记录','','',4,'fa-bookmark-o',1,1,0,1,0),(18,'Operate','log','操作记录','','',4,'fa-circle-o',1,1,0,1,0),(50,'Aa','aa','sdf','aa','',4,'fa-address-card-o',1,1,9,0,1),(59,'Item','sys','测试','see','t_id=12',3,'fa-circle-o',0,1,6,0,0),(61,'Item','sys','配置项管理','','',3,'fa-circle-o',1,1,2,0,0),(62,'Item','sys','配置项设置','see','',3,'fa-circle-o',1,1,1,0,0);
/*!40000 ALTER TABLE `cas_admin_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cas_admin_role`
--

DROP TABLE IF EXISTS `cas_admin_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cas_admin_role` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL COMMENT '角色名称',
  `describe` varchar(20) NOT NULL COMMENT '角色描述',
  `a_id` varchar(255) NOT NULL COMMENT 'admin_auth权限ID集合;多个ID中间用英文逗号分隔',
  `is_enable` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用;0禁用,1启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='管理员角色';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cas_admin_role`
--

LOCK TABLES `cas_admin_role` WRITE;
/*!40000 ALTER TABLE `cas_admin_role` DISABLE KEYS */;
INSERT INTO `cas_admin_role` VALUES (1,'超级管理员','超级管理员拥有全部权限','all',1),(24,'ewr','','',1),(26,'aaa','aaaa','',1);
/*!40000 ALTER TABLE `cas_admin_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cas_admin_user`
--

DROP TABLE IF EXISTS `cas_admin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cas_admin_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '用户名',
  `pass` varchar(40) NOT NULL COMMENT '系统用户密码',
  `r_id` tinyint(3) unsigned NOT NULL COMMENT '角色ID',
  `email` varchar(40) NOT NULL COMMENT '邮箱',
  `is_enable` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否启用;0禁用,1启用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `n` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='管理员用户';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cas_admin_user`
--

LOCK TABLES `cas_admin_user` WRITE;
/*!40000 ALTER TABLE `cas_admin_user` DISABLE KEYS */;
INSERT INTO `cas_admin_user` VALUES (1,'admin','a160e10adc3949ba59abbe56e057f20f883edd60',1,'',1),(9,'aaa','',26,'',1),(14,'技术专员','',24,'',1),(15,'科研人员','',24,'',1),(16,'cccc','',26,'',1);
/*!40000 ALTER TABLE `cas_admin_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cas_log_login`
--

DROP TABLE IF EXISTS `cas_log_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cas_log_login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` smallint(5) unsigned NOT NULL COMMENT '登录用户ID',
  `t` datetime NOT NULL COMMENT '登录时间',
  `device` varchar(100) NOT NULL COMMENT '登录设备',
  `ip` varchar(20) NOT NULL COMMENT '登录IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=394 DEFAULT CHARSET=utf8 COMMENT='登录记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cas_log_login`
--

LOCK TABLES `cas_log_login` WRITE;
/*!40000 ALTER TABLE `cas_log_login` DISABLE KEYS */;
INSERT INTO `cas_log_login` VALUES (255,1,'2019-09-02 19:49:15','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(368,1,'2019-09-12 17:53:22','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(361,1,'2019-09-11 17:20:33','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(269,1,'2019-09-04 21:13:42','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(270,1,'2019-09-04 21:23:27','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(369,1,'2019-09-13 11:23:46','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(370,1,'2019-09-14 17:38:19','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(304,1,'0000-00-00 00:00:00','',''),(305,1,'0000-00-00 00:00:00','',''),(306,1,'0000-00-00 00:00:00','',''),(307,1,'0000-00-00 00:00:00','',''),(308,1,'0000-00-00 00:00:00','',''),(309,1,'0000-00-00 00:00:00','',''),(310,1,'0000-00-00 00:00:00','',''),(311,1,'0000-00-00 00:00:00','',''),(312,1,'0000-00-00 00:00:00','',''),(313,1,'0000-00-00 00:00:00','',''),(314,1,'0000-00-00 00:00:00','',''),(315,1,'0000-00-00 00:00:00','',''),(316,1,'0000-00-00 00:00:00','',''),(317,1,'0000-00-00 00:00:00','',''),(318,1,'0000-00-00 00:00:00','',''),(319,1,'0000-00-00 00:00:00','',''),(320,1,'0000-00-00 00:00:00','',''),(321,1,'0000-00-00 00:00:00','',''),(322,1,'0000-00-00 00:00:00','',''),(323,1,'0000-00-00 00:00:00','',''),(324,1,'0000-00-00 00:00:00','',''),(325,1,'0000-00-00 00:00:00','',''),(326,1,'0000-00-00 00:00:00','',''),(327,1,'0000-00-00 00:00:00','',''),(328,1,'0000-00-00 00:00:00','',''),(329,1,'0000-00-00 00:00:00','',''),(330,1,'0000-00-00 00:00:00','',''),(331,1,'0000-00-00 00:00:00','',''),(332,1,'0000-00-00 00:00:00','',''),(333,1,'0000-00-00 00:00:00','',''),(384,1,'2019-09-22 19:54:01','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(348,1,'2019-09-06 08:19:25','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(347,1,'2019-09-05 14:13:10','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(349,1,'2019-09-06 09:24:41','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(350,1,'2019-09-06 11:45:58','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(351,1,'2019-09-06 19:29:32','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(352,1,'2019-09-07 17:45:48','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(357,1,'2019-09-10 21:02:21','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(358,1,'2019-09-10 22:27:14','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(359,1,'2019-09-10 22:44:30','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(360,1,'2019-09-11 15:47:00','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(362,1,'2019-09-11 17:21:50','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(363,1,'2019-09-11 17:32:34','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(364,1,'2019-09-11 17:53:38','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(365,1,'2019-09-11 17:58:11','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(366,1,'2019-09-11 18:01:34','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(367,1,'2019-09-11 18:45:54','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(371,1,'2019-09-15 08:23:05','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(372,1,'2019-09-16 22:35:15','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(373,1,'2019-09-16 22:56:54','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(374,1,'2019-09-16 23:35:25','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(375,1,'2019-09-16 23:36:33','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(376,1,'2019-09-16 23:39:18','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(377,1,'2019-09-16 23:39:49','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(378,1,'2019-09-16 23:41:04','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(379,1,'2019-09-16 23:44:44','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(380,1,'2019-09-16 23:45:25','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(381,1,'2019-09-17 00:05:24','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(382,1,'2019-09-17 09:59:21','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(383,1,'2019-09-22 13:26:37','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(387,1,'2019-09-27 19:31:39','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(388,1,'2019-09-29 19:30:44','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(389,1,'2019-09-30 20:02:33','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(390,1,'2019-10-01 09:48:42','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(391,1,'2019-10-01 21:29:07','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(392,1,'2019-10-01 21:43:27','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1'),(393,1,'2019-10-02 10:29:29','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safa','127.0.0.1');
/*!40000 ALTER TABLE `cas_log_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cas_log_operate`
--

DROP TABLE IF EXISTS `cas_log_operate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cas_log_operate` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `uid` smallint(5) unsigned NOT NULL COMMENT '操作用户ID',
  `t` datetime NOT NULL COMMENT '操作时间',
  `behavior` varchar(20) NOT NULL COMMENT '操作行为',
  `details` varchar(255) NOT NULL COMMENT '操作详情',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='操作记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cas_log_operate`
--

LOCK TABLES `cas_log_operate` WRITE;
/*!40000 ALTER TABLE `cas_log_operate` DISABLE KEYS */;
INSERT INTO `cas_log_operate` VALUES (3,1,'2019-09-30 23:57:04','0','删除管理员管理:17,18'),(2,1,'0000-00-00 00:00:00','1',''),(4,1,'2019-09-30 23:59:30','0','save管理员管理: 16'),(5,1,'2019-10-01 00:12:23','0','ajaxoperate管理员管理: 16'),(6,1,'2019-10-01 00:12:25','0','ajaxoperate管理员管理: 15'),(7,1,'2019-10-01 00:12:27','0','ajaxoperate管理员管理: 16'),(8,1,'2019-10-01 00:15:51','0','save管理员管理: '),(9,1,'2019-10-01 00:16:02','0','save管理员管理: 9'),(10,1,'2019-10-01 00:18:20','0','ajaxoperate管理员管理: 16'),(11,1,'2019-10-01 00:40:59','admin','ajaxoperate管理员管理: 15'),(12,1,'2019-10-01 00:41:01','admin','ajaxoperate管理员管理: 16'),(13,1,'2019-10-01 00:42:52','admin','ajaxoperate管理员管理: 16'),(14,1,'2019-10-01 10:40:55','admin','更新管理员管理: 14'),(15,1,'2019-10-01 11:07:06','admin','更新管理员管理: 16'),(16,1,'2019-10-01 11:07:13','admin','操作属性管理员管理: 14'),(17,1,'2019-10-01 12:27:52','admin','更新管理员管理: 50'),(18,1,'2019-10-01 12:27:59','admin','操作属性管理员管理: 50'),(19,1,'2019-10-01 13:35:08','admin','更新管理员管理: 50'),(20,1,'2019-10-01 13:35:17','admin','更新管理员管理: 50'),(21,1,'2019-10-01 13:35:26','admin','更新管理员管理: 50'),(22,1,'2019-10-01 13:40:34','admin','更新管理员管理: 50'),(23,1,'2019-10-01 13:40:56','admin','操作属性管理员管理: 18'),(24,1,'2019-10-01 13:41:51','admin','更新管理员管理: 50'),(25,1,'2019-10-01 13:42:09','admin','操作属性管理员管理: 18'),(26,1,'2019-10-01 13:42:16','admin','更新管理员管理: 50'),(27,1,'2019-10-01 13:42:24','admin','更新管理员管理: 50'),(28,1,'2019-10-01 13:42:33','admin','更新管理员管理: 50'),(29,1,'2019-10-01 13:42:37','admin','操作属性管理员管理: 50'),(30,1,'2019-10-01 13:42:43','admin','更新管理员管理: 50'),(31,1,'2019-10-01 17:47:42','admin','更新管理员管理: 50'),(32,1,'2019-10-01 18:09:30','admin','更新管理员管理: 17'),(33,1,'2019-10-02 11:28:34','admin','操作属性管理员管理: 16'),(34,1,'2019-10-02 11:28:44','admin','更新管理员管理: 9'),(35,1,'2019-10-02 11:29:02','admin','更新管理员管理: 14'),(36,1,'2019-10-02 11:29:53','admin','更新管理员管理: 15'),(37,1,'2019-10-02 12:31:58','admin','更新管理员管理: 16'),(38,1,'2019-10-02 12:38:11','admin','更新管理员管理: 16'),(39,1,'2019-10-02 12:39:31','admin','更新管理员管理: 16'),(40,1,'2019-10-02 12:48:54','admin','更新管理员管理: 16'),(41,1,'2019-10-02 12:54:51','admin','更新管理员管理: 16'),(42,1,'2019-10-02 13:09:06','admin','更新管理员管理: 16');
/*!40000 ALTER TABLE `cas_log_operate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cas_sys_item`
--

DROP TABLE IF EXISTS `cas_sys_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cas_sys_item` (
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
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='menu_sys_sset';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cas_sys_item`
--

LOCK TABLES `cas_sys_item` WRITE;
/*!40000 ALTER TABLE `cas_sys_item` DISABLE KEYS */;
INSERT INTO `cas_sys_item` VALUES (1,1,'user_only_sign','设备登录','0','text','user_only_sign',2,1),(2,1,'user_operate_auth','','admin','','user_operate_auth',0,1),(3,1,'pass_error_num','','50','','pass_error_num',0,1),(4,1,'lock_t','','86400','','lock_t',0,1),(5,2,'email_interval_t','邮件间隔时间','120','text','发送邮件间隔时间',0,1),(6,2,'smtp_server','','smtp.sina.com','','smtp_server',0,1),(7,2,'smtp_server_port','','25','','smtp_server_port',0,1),(8,2,'smtp_user_email','','','','smtp_user_email',0,1),(9,2,'smtp_pass','','','','smtp_pass',0,1),(10,2,'email_send_c','','50','','今日发送邮件已达到最大限度 %d 次，请 %d 小时后在继续操作',0,1),(11,2,'email_t','','86400','','email_t',0,1),(12,2,'email_activate_t','','18000','','email_activate_t',0,1),(15,1,'sd','sdf','12ww33','text','',0,0),(16,12,'time','时间','11:52:46','time','测试时间',0,0),(24,12,'dfsdf','sdfs','2019-09-06','date','',0,0),(25,12,'sdf','时间区间','2019-09-12 - 2019-10-17','date_range','',0,0),(26,12,'test','test','','all_icon','',0,0);
/*!40000 ALTER TABLE `cas_sys_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cas_sys_type`
--

DROP TABLE IF EXISTS `cas_sys_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cas_sys_type` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '设置分类名称',
  `describe` varchar(20) NOT NULL COMMENT '设置分类标识名',
  `is_menu` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否为系统配置子菜单,0不是菜单,1菜单',
  `is_sys` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统内置;系统内置不可删除；1不删除,0可以删除',
  `a_id` smallint(5) unsigned NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='menu_sys_stype';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cas_sys_type`
--

LOCK TABLES `cas_sys_type` WRITE;
/*!40000 ALTER TABLE `cas_sys_type` DISABLE KEYS */;
INSERT INTO `cas_sys_type` VALUES (1,'system','系统设置',1,1,14),(2,'email','邮箱设置',1,1,15),(3,'upfile','上传设置',0,1,16),(12,'sdfsdf','测试',0,0,59);
/*!40000 ALTER TABLE `cas_sys_type` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-02 13:09:57
