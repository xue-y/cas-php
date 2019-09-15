CasPHP
便捷简明的 PHP 框架
Convenient and succinct（便捷的 简明的;言简意赅的）

核心框架：framework:      5.1.28  
助手函数：think-helper:   1.0.6   
验证码：  think-captcha:    2.0.2

前端组件： layui 	v2.5.4   
表格、弹窗、时间、上传、部分字体图标   
https://www.layui.com/

表单验证器： bootstrapValidator  v0.5.3  
http://bootstrapvalidator.com

编辑器： KindEditor  4.1.10   
http://kindeditor.net/

字体图标： Font Awesome  4.7.0    
http://fontawesome.dashgame.com/

页面布局框架： Bootstrap  v3.3.7   
http://getbootstrap.com

 
数据库字段定义  

    数据表是以模块名_控制器命名  
    是否系统内置;系统内置不可删除;1不删除,0可以删除   
    是否为菜单,0不是菜单,1菜单  
    是否启用,0禁用,1启用   
    是否属于基本权限;登录即可访问的页面;0不是,1是  
	
权限管理  

    权限控制只是控制了 模块/控制器,方法没有控制
    如果权限父级菜单禁用，子级是可以访问的，禁用只作用于设置的菜单
    权限菜单批量删除，删除的是勾选的菜单，如果子级未勾选将不会删除，只是无法显示出来
    权限/角色/用户【 禁用 】允许其他操作，只是用户不可访问

列表页面

    列表页面因 ajax 请求返回数据的方式不方面调试，表格使用的是 layui 转为静态表格的方式

添加修改

    所有添加修改页面不显示是否系统内置选项

删除

    删除勾选，可以勾选复选框，后端数据不会删除（行内没有删除图标的数据）系统内置的数据

layui 待修复问题

	1、弹窗第一次与第二次不一样大小
	2、静态表格无法重载
	3、表格右上方工具栏无法实现再次点击消失信息框

使用 ajax/直接跳转 提交的方法  

	登录、锁屏、重置密码、个人信息、删除、添加、修改
	
直接使用 ajax 提交的方法：

	系统安装、发送邮件、是否为菜单、是否启用、单个/多个排序
	
数据验证页面

    安装、登录、锁屏、个人信息、发送邮件、重置密码使用 validators.js 验证

语言包

    语言包只定义了中文，如需其他语言自行定义   
    
待修复bug

	登录、找回密码 验证码无法自动刷新

待优化

	icon 图标选择器添加一个关闭按钮
	auth 权限列表因未找到合适的插件（因为其中判断条件较多）使用的是layui纯静态表格

ThinkPHP 5.1
===============

ThinkPHP5.1对底层架构做了进一步的改进，减少依赖，其主要特性包括：

 + 采用容器统一管理对象
 + 支持Facade
 + 注解路由支持
 + 路由跨域请求支持
 + 配置和路由目录独立
 + 取消系统常量
 + 助手函数增强
 + 类库别名机制
 + 增加条件查询
 + 改进查询机制
 + 配置采用二级
 + 依赖注入完善
 + 中间件支持（V5.1.6+）


> ThinkPHP5的运行环境要求PHP5.6以上。


## 目录结构

初始的目录结构如下：

~~~
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─common             公共模块目录（可以更改）
│  ├─module_name        模块目录
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  └─ ...            更多类库目录
│  │
│  ├─command.php        命令行定义文件
│  ├─common.php         公共函数文件
│  └─tags.php           应用行为扩展定义文件
│
├─config                应用配置目录
│  ├─module_name        模块配置目录
│  │  ├─database.php    数据库配置
│  │  ├─cache           缓存配置
│  │  └─ ...            
│  │
│  ├─app.php            应用配置
│  ├─cache.php          缓存配置
│  ├─cookie.php         Cookie配置
│  ├─database.php       数据库配置
│  ├─log.php            日志配置
│  ├─session.php        Session配置
│  ├─template.php       模板引擎配置
│  └─trace.php          Trace配置
│
├─route                 路由定义目录
│  ├─route.php          路由定义
│  └─...                更多
│
├─public                WEB目录（对外访问目录）
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─thinkphp              框架系统目录
│  ├─lang               语言文件目录
│  ├─library            框架类库目录
│  │  ├─think           Think类库包目录
│  │  └─traits          系统Trait目录
│  │
│  ├─tpl                系统模板目录
│  ├─base.php           基础定义文件
│  ├─console.php        控制台入口文件
│  ├─convention.php     框架惯例配置文件
│  ├─helper.php         助手函数文件
│  ├─phpunit.xml        phpunit配置文件
│  └─start.php          框架入口文件
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~

> router.php用于php自带webserver支持，可用于快速测试
> 切换到public目录后，启动命令：php -S localhost:8888  router.php
> 上面的目录结构和名称是可以改变的，这取决于你的入口文件和配置参数。

## 升级指导

原有下面系统类库的命名空间需要调整：

* think\App      => think\facade\App （或者 App ）
* think\Cache    => think\facade\Cache （或者 Cache ）
* think\Config   => think\facade\Config （或者 Config ）
* think\Cookie   => think\facade\Cookie （或者 Cookie ）
* think\Debug    => think\facade\Debug （或者 Debug ）
* think\Hook     => think\facade\Hook （或者 Hook ）
* think\Lang     => think\facade\Lang （或者 Lang ）
* think\Log      => think\facade\Log （或者 Log ）
* think\Request  => think\facade\Request （或者 Request ）
* think\Response => think\facade\Reponse （或者 Reponse ）
* think\Route    => think\facade\Route （或者 Route ）
* think\Session  => think\facade\Session （或者 Session ）
* think\Url      => think\facade\Url （或者 Url ）

原有的配置文件config.php 拆分为app.php cache.php 等独立配置文件 放入config目录。
原有的路由定义文件route.php 移动到route目录

## 命名规范

`ThinkPHP5`遵循PSR-2命名规范和PSR-4自动加载规范，并且注意如下规范：

### 目录和文件

*   目录不强制规范，驼峰和小写+下划线模式均支持；
*   类库、函数文件统一以`.php`为后缀；
*   类的文件名均以命名空间定义，并且命名空间的路径和类库文件所在路径一致；
*   类名和类文件名保持一致，统一采用驼峰法命名（首字母大写）；

### 函数和类、属性命名
*   类的命名采用驼峰法，并且首字母大写，例如 `User`、`UserType`，默认不需要添加后缀，例如`UserController`应该直接命名为`User`；
*   函数的命名使用小写字母和下划线（小写字母开头）的方式，例如 `get_client_ip`；
*   方法的命名使用驼峰法，并且首字母小写，例如 `getUserName`；
*   属性的命名使用驼峰法，并且首字母小写，例如 `tableName`、`instance`；
*   以双下划线“__”打头的函数或方法作为魔法方法，例如 `__call` 和 `__autoload`；

### 常量和配置
*   常量以大写字母和下划线命名，例如 `APP_PATH`和 `THINK_PATH`；
*   配置参数以小写字母和下划线命名，例如 `url_route_on` 和`url_convert`；

### 数据表和字段
*   数据表和字段采用小写加下划线方式命名，并注意字段名不要以下划线开头，例如 `think_user` 表和 `user_name`字段，不建议使用驼峰和中文作为数据表字段命名。

## 参与开发
请参阅 [ThinkPHP5 核心框架包](https://github.com/top-think/framework)。

## 版权信息

ThinkPHP遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2006-2018 by ThinkPHP (http://thinkphp.cn)

All rights reserved。

ThinkPHP® 商标和著作权所有者为上海顶想信息科技有限公司。

更多细节参阅 [LICENSE.txt](LICENSE.txt)
