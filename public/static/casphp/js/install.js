/**
 * Created by Administrator on 17-9-17.
 * 安装部分 验证文字 语言包--缺少
 */
$(document).ready(function(e) {
    let table_status=1;
    let app_debug="{:config('app_debug')}";
    // 弹层
    layui.use('layer', function(){
      let layer = layui.layer;
    });

    $('#dbname').blur(function(){
        for(let i=0;i<5;i++)
        {
            if($('input.form-control').eq(i).val()=='')
            {
                  return;
            }
        }
        let num=$(this).siblings('.glyphicon-ok').length;//如果字符验证正确  验证数据库信息是否正确
        if(num==1)
        {
            //发送ajax请求
            $.ajax({
                type:"post",
                url:"install/Index/valiData",
                data:$('form').serialize(),
                success: function(data,status,xhr){
                   data=data.replace(/\<\?php/g,"");
                    if(app_debug){
                       console.log(data);
                    }
                   data=JSON.parse(data);
                    switch (data.data){
                        case 'db_exis':
                            table_status='db_exis';
                            $('#dbname').siblings('.help-block').eq(0).css({'display':'block'}).html("信息正确，已存在此数据库，连接成功");
                            break;
                        case 'server_error':
                            $('#dbname').siblings('.help-block').eq(0).css({'display':'block','color':'#f00'}).html("连接服务器失败");
                            break;
                        case "db_ok":
                            $('#dbname').siblings('.help-block').eq(0).css({'display':'block','color':'#f00'}).html("数据库不存在请手动创建并授予权限");
                            break;
                        case 'table_data':
                            //询问框
                            //怎么控制layui弹出层第二次调用宽度减少问题
                            layer.confirm('数据库存在数据，请问确定删除吗？', {
                                offset: '100px',
                                area:'300px',
                                btn: ['确定','取消'] //按钮
                            }, function(){
                                $('.table_data').val(1);
                                table_status="db_exis";
                                $('#dbname').siblings('.help-block').eq(0).css('display','none');
                                layer.close(layer.index);
                            }, function(){
                                $('.table_data').val(0);
                                layer.msg('请先清空数据库再安装，如有数据请先备份', {
                                    offset: '150px',
                                    time: 2000, //20s后自动关闭
                                });
                            });  
                            break;
                        default:
                             layer.alert('未知错误');
                            break;
                    }
                },
                error: function(xhr,status,errorinfo){
                    if(app_debug){
                       console.log(xhr);
                    }
                    layer.alert(status+': '+errorinfo);
                },
                timeout:2000
            });
        }
    });

    // 提交表单
    $('.b_from').on('success.form.bv',function(e){
        e.preventDefault();
        var $form = $(e.target);
        if(table_status==="db_exis")
        {
            e.preventDefault();
            $.ajax({
                type:"POST",
                url:$form.attr('action'),
                data:$('form').serialize(),
                beforeSend: function(){
                    layer.load(1, {shade: [0.3, '#000'],offset: '150px'});
                },
                success: function(data,status,xhr){
                    if(app_debug){
                        console.log(data);
                    }
                    data=data.replace(/\<\?php/g,"");
                    data=JSON.parse(data);

                    // 加载 图片 消失
                    layer.closeAll('loading');
                    if(data.code!=1)
                    {
                        layer.alert(data.msg, {icon: 5});
                        return false;
                    }else
                    {
                        layer.msg("安装成功进入系统", {icon: 6});
                        let t=setTimeout(function(){window.location.href='/'+data.data;clearTimeout(t);},150)
                    }
                },
                error: function(xhr,status,errorinfo){
                    if(app_debug){
                       console.log(xhr);
                    }
                    layer.closeAll('loading');
                    layer.alert(status+': '+errorinfo, {icon: 5});
                },
                timeout:5000
            });
        }
        else
        {
            layui.use('layer', function(){
                var layer = layui.layer;
                layer.alert("连接数据库信息错误", {icon: 5});
            });
        }
    });
});
