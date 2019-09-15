$(function () {
    // ajax  读取数据
    let vail_lang; // 定义全局 语言变量
     $.ajax({
        type: "POST",
        url : "http://tp.com/static/validator/js/language/zh_CN.json",
        dataType:'json',
        async:false, // 同步请求
        beforeSend:function(){
        },
        success:function(result,status,xhr) {
             vail_lang=result;
        },
        error:function(xhr){
            console.log(xhr);
             layui.use('layer', function(){
                 let layer = layui.layer;
                 layer.msg(xhr.status+': '+xhr.statusText);
                 layer.style(1, {
                     width: '60px',
                 });
             });
        },
        complete:function(){
        },
        timeout:2000
    });

    /*$.ajax({
        url:"/static/validator/js/language/zh_CN.jsonp",
        type:'get',
        async:false, // 同步请求
        dataType:'jsonp',
        jsonp: "callback",
        jsonpCallback:'cback',
        beforeSend:function(){
        },
        success:function(result,status,xhr) {
            vail_lang=result;
        },
        error:function(xhr){
            layer.msg(xhr.status+': '+xhr.statusText);
            layer.style(1, {
                width: '60px',
            });
           // alert(xhr.status+': '+xhr.statusText);
        },
        complete:function(){
        },
        timeout:2000
    });*/

    //placeholder 页面中的 input 提示
    $("input[type=text],input[type=password]").each(function(i,ele){
        let _this=$(this);
        let t_name=_this.attr('name');
        _this.attr("placeholder",vail_lang[t_name]);
    });
        let _this=$(this);

    // 用户密码
    let pass_regexp=/^[\w\.\@\-]{6,20}$/;

    // 数据库密码
    let pass_regexp_db=/^[\w\.\@\-]{2,20}$/;

    // 表单验证
    $('.b_from').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
         // 管理员个人信息 --------------------------  系统安装密码使用个人信息pass字段
            name: {
                validators: {
                    notEmpty: {
                        message: vail_lang.name_empty
                    },
                    stringLength: {
                        min: 2,
                        max: 20,
                        message: vail_lang.name
                    }
                }
            },
            pass_old:{
                validators: {
                    regexp: {
                        regexp: pass_regexp,
                        message:vail_lang.pass
                    }
                }
            },
            pass:{
                validators: {
                    regexp: {
                        regexp: pass_regexp,
                        message:vail_lang.pass
                    },
                    identical: {
                        field: 'pass2',
                    }
                }
            },
            pass2:{
                validators: {
                    regexp: {
                        regexp: pass_regexp,
                        message: vail_lang.pass
                    },
                    identical: {
                        field: 'pass',
                    }
                }
            },
            email: {
                validators: {
                    emailAddress: {
                    }
                }
            },    //------------------------------------安装系统验证
            host:{
                validators: {
                    notEmpty: {
                    },
                    ip: {
                    }
                }
            },
            port:{
                validators: {
                    notEmpty: {

                    },
                    digits:{
                        message: vail_lang.port
                    }
                }
            },
            dbuser:{
               validators: {
                   notEmpty: {
                   },
                   regexp: {
                       regexp: /^[\w]{2,20}$/,
                       message: vail_lang.dbuser
                   }
               }
            },
            dbname:{
                validators: {
                    notEmpty: {
                    },
                    regexp: {
                        regexp: /^[\w]{2,20}$/,
                        message: vail_lang.dbname
                    }
                }
            },
            dbpass:{
                validators: {
                    notEmpty: {
                    },
                    regexp: {
                        regexp: pass_regexp_db,
                        message:vail_lang.dbpass
                    }
                }
            },
            prefix:{
                validators: {
                    notEmpty: {
                    },
                    regexp: {
                        regexp: /^[a-z]{1,5}$/,
                        message: vail_lang.prefix
                    }
                }
            },//-安装验证结束-------------------------- 后台登录
            v_code:{
                validators: {
                    notEmpty: {
                        message:vail_lang.v_code_empty
                    },
                    regexp: {
                        regexp: /^[\w]{5}$/,
                        message: vail_lang.v_code
                    }
                }
            },
            repass: {
                validators: {
                    notEmpty: {
                        message: vail_lang.repass_empty
                    },
                    regexp: {
                        regexp: pass_regexp,
                        message: vail_lang.repass
                    }
                }
            },// 登录验证结束------------------------------- 找回密码
            reemail: {
                validators: {
                    notEmpty: {
                        message: vail_lang.reemail_empty
                    },
                    emailAddress: {
                        message: vail_lang.reemail
                    }
                }
            },//- 找回密码结束---------------------重设密码开始
            newpass:{
                validators: {
                    notEmpty: {
                        message: vail_lang.newpass_empty
                    },
                    regexp: {
                        regexp: pass_regexp,
                        message:vail_lang.newpass
                    },
                    identical: {
                        field: 'newpass2'
                    }
                }
            },
            newpass2:{
                validators: {
                    notEmpty: {
                        message: vail_lang.newpass2_empty
                    },
                    regexp: {
                        regexp: pass_regexp,
                        message: vail_lang.newpass2
                    },
                    identical: {
                        field: 'newpass'
                    }
                }
            }//----------------------------重设密码结束
        }
    });

    // 验证确认密码处理
   $('.b_from input.btn-success').click(function(){
        let pass_v=$('input[name=pass]').val();
        let pass2_v=$('input[name=pass2]').val();
        if(pass2_v!=pass_v)
        {
            $('input[name=pass],input[name=pass2]').parent().parent().removeClass('has-success').addClass('has-error');
            $('input[name=pass],input[name=pass2]').siblings('i').removeClass('glyphicon-ok').addClass('glyphicon-remove');
            $('input[name=pass],input[name=pass2]').siblings('small').removeAttr("style");
        }
    });
});