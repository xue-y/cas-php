// 弹层
layui.use('layer', function(){
    let layer = layui.layer;
});
//功能：根据用户输入的Email跳转到相应的电子邮箱首页
function gotoEmail(mail) {
    $t = mail.split('@')[1];
    $t = $t.toLowerCase();
    if ($t == '163.com') {
        return 'mail.163.com';
    } else if ($t == 'vip.163.com') {
        return 'vip.163.com';
    } else if ($t == '126.com') {
        return 'mail.126.com';
    } else if ($t == 'qq.com' || $t == 'vip.qq.com' || $t == 'foxmail.com') {
        return 'mail.qq.com';
    } else if ($t == 'gmail.com') {
        return 'mail.google.com';
    } else if ($t == 'sohu.com') {
        return 'mail.sohu.com';
    } else if ($t == 'tom.com') {
        return 'mail.tom.com';
    } else if ($t == 'vip.sina.com') {
        return 'vip.sina.com';
    } else if ($t == 'sina.com.cn' || $t == 'sina.com') {
        return 'mail.sina.com.cn';
    } else if ($t == 'tom.com') {
        return 'mail.tom.com';
    } else if ($t == 'yahoo.com.cn' || $t == 'yahoo.cn') {
        return 'mail.cn.yahoo.com';
    } else if ($t == 'tom.com') {
        return 'mail.tom.com';
    } else if ($t == 'yeah.net') {
        return 'www.yeah.net';
    } else if ($t == '21cn.com') {
        return 'mail.21cn.com';
    } else if ($t == 'hotmail.com') {
        return 'www.hotmail.com';
    } else if ($t == 'sogou.com') {
        return 'mail.sogou.com';
    } else if ($t == '188.com') {
        return 'www.188.com';
    } else if ($t == '139.com') {
        return 'mail.10086.cn';
    } else if ($t == '189.cn') {
        return 'webmail15.189.cn/webmail';
    } else if ($t == 'wo.com.cn') {
        return 'mail.wo.com.cn/smsmail';
    } else if ($t == '139.com') {
        return 'mail.10086.cn';
    } else {
        return '';
    }
};


// 全选
function check_all(){
    $('thead tr').on('click','.layui-form-checkbox',function(){
        if($(this).hasClass('layui-form-checked')){
            $('tbody>tr').each(function(i,e){
                $(this).find('.layui-form-checkbox').addClass('layui-form-checked');
                $(this).find('input[type="checkbox"]').attr('checked','checked');
            });
        }else{
            $('tbody>tr').each(function(i,e){
                $(this).find('.layui-form-checkbox').removeClass('layui-form-checked');
                $(this).find('input[type="checkbox"]').removeAttr('checked');
            });
        }
    });
}

/**
  * slide_node 折叠菜单
  * @param objec obj 当前操作对象
  **/
/** 
function slide_node(obj){

    let id=obj.closest('tr').attr('data-id');
    if(obj.hasClass('rotate-90')){
        obj.removeClass('rotate-90').addClass('rotate-0');
    }else{
        obj.addClass('rotate-90').removeClass('rotate-0');
    }
    let class_name='child-of-node-'+id;
  
    $('tbody>tr.'+class_name).each(function(){
        $(this).stop(true).slideToggle(100);
		
    });
}
**/
/**
  * slide_node 折叠菜单
  * @param objec obj 当前操作对象
  **/
function slide_node(obj){
	let obj_parent=obj.closest('tr');
	let level_bottom=obj_parent.attr('data-level');
	let num = obj_parent.index();// 取得第几个
	let but=true;
	if(obj.hasClass('rotate-90')){
        obj.removeClass('rotate-90').addClass('rotate-0');
		but=false;
    }else{
        obj.addClass('rotate-90').removeClass('rotate-0');
    }
	 // 选中元素
	let obj_siblings=obj.closest('tr').nextAll();
	obj_siblings.each(function(i,e){
		
		
		let le=$(this).attr('data-level');
		if (le == level_bottom){
			return false;
		}else if(le > level_bottom){
			// 判断展开还是关闭
			if(but){
				// 执行展开, 展开只展开一级
				if((le-1)==level_bottom){
					$(this).stop(true).slideDown(100);
				}
			}else{
				// 执行折叠，折叠所有子集
				$(this).stop(true).slideUp(100);
				if($(this).find('i.expander').hasClass('rotate-90')){
					$(this).find('i.expander').removeClass('rotate-90').addClass('rotate-0');
				}
			}
		}
	});
}

/**
  * check_node 选中节点 子级父级互相选中
  * @param objec obj 当前操作对象
  * */
function check_node(obj) {
    //console.log(event.target.tagName);
    let obj_parent = obj.closest('tbody');
    let num = obj.closest('tr').index();// 取得第几个
    let chk = obj_parent.find("span>input[type='checkbox']");
	let obj_level = obj_parent.find('tr');
    let count = obj_parent.find('tr').length;
	
    let level_top = level_bottom = obj.closest('tr').attr('data-level');//chk.eq(num).attr('level');

    // 选中父级
    for (let i = num; i >= 0; i--) {
        let le = obj_level.eq(i).attr('data-level');
        if (le < level_top) {
            chk.eq(i).siblings('.layui-form-checkbox').addClass('layui-form-checked');
            chk.eq(i).prop("checked", true);
            level_top = level_top - 1;
        }
    }
    // 选中子级
    for (var j = num + 1; j < count; j++) {
        var le = obj_level.eq(j).attr('data-level');
        if (chk.eq(num).prop("checked")) {
            if (le > level_bottom) {
                chk.eq(j).siblings('.layui-form-checkbox').addClass('layui-form-checked');
                chk.eq(j).prop("checked", true);
            }
            else if (le == level_bottom) {
                break;
            }
        } else {
            if (le > level_bottom) {
                chk.eq(j).siblings('.layui-form-checkbox').removeClass('layui-form-checked');
                chk.eq(j).prop("checked", false);
            } else if (le == level_bottom) {
                break;
            }
        }
    }
}

/**
 * updateSort 批量排序
 * @param  string  url    访问地址
 * @param  boole   debug  是否开启debug
 */
function updateSort(url,debug)
{
    $('div[lay-event="sort"]').click(function(){
        $.ajax({
          type:"post",
          data:$('form').serialize(),
          url:url,
          success:function(data){
             layer.alert(data.msg,function(){
                // 手动刷新页面
                window.location.reload();
             });
          },
          error:function(xhr,status,error){
            // 调试模式
            if(debug){
                console.log(xhr);
            }
            layer.alert(status+':'+error);
          },
          //dataType:'json',
          timeout:3000
        });
    })
}

/**
 * switchEvent 工具开关事件切换操作
 * @param  object  _this   当前操作this
 * @param  object  obj     当前操作元素
 * @param  string  event   操作事件名称
 * @param  string  url     访问地址
 * @param  bool    debug   是否开启debug
 * @return 
 */
function switchEvent(_this,obj,url,app_debug){

    let text=_this.attr('lay-text').split('|');
    let val=(obj.elem.checked==true)?1:0; // 现在的值
    let old_val=(obj.elem.checked==true)?0:1; // 原来的值

    $.post(url,{'id':obj.value,[obj.event]:val},function(data)
    {
        // 调试模式
        if(app_debug){
            console.log(data);
        }
        if(data.code!=1){
            // 更新失败还原样式
            if(old_val==1){
                // 选中状态/
                _this.attr("checked","checked");
                _this.prop("checked","checked");
                _this.siblings('.layui-form-switch').addClass('layui-form-onswitch');
            }else{
                // 未选中状态
                _this.attr("checked",false);
                _this.prop("checked",false);
                _this.siblings('.layui-form-switch').removeClass('layui-form-onswitch');
            }
            // 数据库的设计与layui 相反 
            _this.siblings('.layui-form-switch').find('em').text(text[val]);
        }
        layer.alert(data.msg);
     },'json').fail(function(xhr,status,error){
        // 调试模式
        if(app_debug){
            console.log(xhr);
        }
        // 更新失败还原样式
        if(old_val==1){
            // 选中状态/
            _this.attr("checked","checked");
            _this.prop("checked","checked");
            _this.siblings('.layui-form-switch').addClass('layui-form-onswitch');
        }else{
            // 未选中状态
            _this.attr("checked",false);
            _this.prop("checked",false);
            _this.siblings('.layui-form-switch').removeClass('layui-form-onswitch');
        }
        // 数据库的设计与layui 相反 
        _this.siblings('.layui-form-switch').find('em').text(text[val]);
        layer.alert(status+':'+error);
    });
}

/**
 * 使用bootsrap vail 插件表单提交
 * @param form 表单元素
 * @param app_debug 是否开启debug
 * @param jump 操作成功是否跳转页面
 * @param success_msg 操作成功是否输出成功信息
 */
function form_submit(form,app_debug,jump,success_msg){
    //success.form.bv
    form.on('success.form.bv', function (e) {
        e.preventDefault();
        ajax_request(form,app_debug,jump,success_msg);
        return false;
    });
}

/**
 * 发送ajax 请求
 */
function ajax_request(form,app_debug,jump,success_msg){
    let url=form.attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function (data, status, xhr) {
            // 判断是否是开发模式
            if (app_debug) {
                console.log(data);
            }
            if (data.code == 1) { // 成功
                if((jump==true) && data.url){
                    if(success_msg){
                        layer.alert(data.msg,function(){
                            window.location.href=data.url;
                        });
                    }else{
                        window.location.href=data.url;
                    }
                }else{
                    layer.alert(data.msg, {icon: 6});
                }
            } else {
                if (data.data) {
                    form.find("input[name='__token__']").val(data.data);
                }
                layer.alert(data.msg, {icon: 5});
            }
        },
        error: function (xhr, status, errorinfo) {
            // 判断是否是开发模式
            if (app_debug) {
                console.log(xhr);
            }
            layer.alert(status + ': ' + errorinfo,{icon:5});
        },
        timeout: 3000
    });
}