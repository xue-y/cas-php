### CasPHP
便捷简明的 PHP 框架,自适应后台管理界面   
Convenient and succinct（便捷的 简明的;言简意赅的）

核心框架：framework:      5.1.28  
助手函数：think-helper:   1.0.6   
验证码：  think-captcha:    2.0.2

前端组件： layui 	v2.5.4   
表格、弹窗、时间、部分字体图标   
https://www.layui.com/

表单验证器： bootstrapValidator  v0.5.3  
http://bootstrapvalidator.com

编辑器： UEeditor  1.4.3.3 
http://ueditor.baidu.com/website/index.html

字体图标： Font Awesome  4.7.0    
http://fontawesome.dashgame.com/

页面布局框架： Bootstrap  v3.3.7   
http://getbootstrap.com
 
聊天插件库： Workerman 3.0.15    
文档: http://doc2.workerman.net/   
手册: http://www.workerman.net/gatewaydoc/
下载Demo: http://doc2.workerman.net/
Liunx: http://www.workerman.net/download/GatewayWorker.zip
Window: http://www.workerman.net/download/GatewayWorker-for-win.zip

修改源码部分,标记一下防止后期框架升级
- ThinkPHP 分页添加参数  `paginate(config['additionalInfo'])` 
- bootstrapValidator [输入验证改为失去光标验证](https://blog.csdn.net/qq592304796/article/details/52475820)
 
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
    权限/角色/用户【 禁用 】，是禁止用户访问   
	如果是 tab 菜单，如果用户有控制器权限，tab 菜单没有权限也可方法[配置管理]
	
配置管理

	自定义字段名称与字段类型，原理是自定义表单

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
	
UEeditor 待修复问题

    1、截图已安装插件，还是提示未安装
    2、音乐试听没有声音
    	
直接使用 ajax 提交的方法：

	系统安装、发送邮件、是否为菜单、是否启用、单个/多个排序
	
数据验证页面

    安装、登录、锁屏、个人信息、发送邮件、重置密码使用 validators.js 验证

语言包

    语言包只定义了中文，如需其他语言自行定义   
    
待修复bug

	登录、找回密码 验证码无法自动刷新
	ThinkPHP 框架不支持场景验证覆盖重写，版本 5.1.28 LTS，(https://github.com/top-think/think/issues/970)


待优化

	icon 图标选择器添加一个关闭按钮
	auth 权限列表因未找到合适的插件（因为其中判断条件较多）使用的是layui纯静态表格
	
构建form表单  
    
    一个页面相同的字段类型如果可以有多个，字段类型名为字段类（class）名称，插件操作 class
    如果一个页面相同的字段类型只能有一个，不可重复，字段类型名 为字段ID ，插件操作 ID 
    fieldItem 字段说明：
    simple_editor, all_editor, click_upload 字段类型页面只可有一个，其他字段类型可以有多个
    click_upload, auto_upload 字段名 name = up_file[]，创建表单时无需设置
    name 字段name
    field_type 字段类型
    style 加载元素上样式
    extra 添加在元素底部
    value 字段值, 
    data  数组形式数据，用于 select checkbox redio click_upload字 段类型
     
    `public function test(){
        $form=new Form();
        $data=['key1'=>'value1sdfsdfsd','key2'=>'value2','key3'=>'value3dsfsdf'];
        $value='key1';
        $href=['key1'=>'href1','key2'=>'href2'];
        $data2=explode(',','asdfdsfdsfdsfdsfds,b,c,d,e,f,g');
        $select_data=explode(',','1,2');
        $disable_data=explode(',','5,6');
        return $form->tabNav($data,$value,'','')->fieldItem([
            ['name'=>'select_multiple','field_type'=>'select_multiple','data'=>$data2,'select_data'=>$select_data,'disable_data'=>$disable_data],
            ['name'=>'radio','field_type'=>'radio','data'=>$data,'value'=>'key3'],
            ['name'=>'checkbox','field_type'=>'checkbox','data'=>$data,'value'=>'key3'],
            ['name'=>'checkbox_multiple','field_type'=>'checkbox_multiple','data'=>$data2,'select_data'=>$select_data,'disable_data'=>$disable_data],
            ['data'=>'date','field_type'=>'date'],
            ['data'=>'time','field_type'=>'time']
        ])->create();
    }`

搜索表单构造器  

	// 搜索框框
	$search_form=new SearchForm();
	$search=$search_form->fieldItem([
		['name'=>'t','type'=>'date_range'],
		$search_name_field
	])->create();
	
	模板中调用
    {:action("common/Base/includePage",['template'=>'search','data'=>$search])}

消息队列

    开启redis服务
    监听服务 php think queue:listen --queue send_mail 
        详细命令：https://www.cnblogs.com/xuey/p/13100202.html
    访问页面调用
         $job_queue=new Jobqueue();
         $job_queue->index('send_mail',['php.develop@qq.com','星海','记录日志发送邮件'],null,'发送邮件');
         第一个参数是调用的函数名 send_mail() 是放在/application/common.php 中的函数
         第二个参数是调用函数的参数

定时任务

     执行定时任务 
          Liunx 系统 
          * * * * * /usr/bin/php /www/yoursite/public/index.php /tool/Crontab/execCrontab > /dev/null  2>&1 &

页面布局

	head头部
	nav导航
	模板布局
		layout模板布局
			列表页面
			添加/修改页面
				vail_form 数据处理验证
					 vaildator验证插件
			plugin 底部插件
		base_layout 基本布局
	
### ThinkPHP 框架
请参阅 [ThinkPHP5 框架](https://github.com/top-think/think)。
