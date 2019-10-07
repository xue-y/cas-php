### CasPHP
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

修改源码部分,标记一下防止后期框架升级
- layui 表格工具栏添加操作可选项 `operateToolbar`
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
	ThinkPHP 框架不支持场景验证覆盖重写，版本 5.1.28 LTS，(https://github.com/top-think/think/issues/970)


待优化

	icon 图标选择器添加一个关闭按钮
	auth 权限列表因未找到合适的插件（因为其中判断条件较多）使用的是layui纯静态表格
	
构建form表单  

    `public function test(){
        $form=new Form();
        $data=['key1'=>'value1sdfsdfsd','key2'=>'value2','key3'=>'value3dsfsdf'];
        $value='key1';
        $href=['key1'=>'href1','key2'=>'href2'];
        $data2=explode(',','asdfdsfdsfdsfdsfds,b,c,d,e,f,g');
        $select_data=explode(',','1,2');
        $disable_data=explode(',','5,6');
        return $form->tabNav($data,$value,'','')->fieldItem([
            ['name'=>'select_multiple','type'=>'select_multiple','data'=>$data2,'select_data'=>$select_data,'disable_data'=>$disable_data],
            ['name'=>'radio','type'=>'radio','data'=>$data,'value'=>'key3'],
            ['name'=>'checkbox','type'=>'checkbox','data'=>$data,'value'=>'key3'],
            ['name'=>'checkbox_multiple','type'=>'checkbox_multiple','data'=>$data2,'select_data'=>$select_data,'disable_data'=>$disable_data],
            ['data'=>'date','type'=>'date'],
            ['data'=>'time','type'=>'time']
        ])->create();
    }`

搜索表单构造器  

	// 搜索框框
	$search_form=new searchForm();
	$search=$search_form->fieldItem([
		['name'=>'t','type'=>'date_range'],
		$search_name_field
	])->create();
	
	模板中调用
    {:action("common/Base/includePage",['template'=>'search','data'=>$search])}
	
### ThinkPHP 框架
请参阅 [ThinkPHP5 框架](https://github.com/top-think/think)。