$(function () {
        // ajax  读取数据
        /*$.ajax({
            type: "POST",
            url : "../validator/js/language/zh_CH.jsonp",
            dataType:'json',
            success:function(result)
            {
                console.log(result)
             },
            error:function(xhr){
                console.log(xhr.status);
            }
       });*/
    var vail_lang; // 定义全局 语言变量
    $.ajax({
        url:"/static/validator/js/language/zh_CN.jsonp",
        type:'get',
        async:false, // 同步请求
        dataType:'jsonp',
        jsonp: "callback",
        jsonpCallback:'cback',
        beforeSend:function(){
     //     $("#json_load").fadeIn(100);
        },
        success:function(result,status,xhr) {
            vail_lang=result;
        },
        error:function(xhr){
            alert(xhr.status+': '+xhr.statusText);
        },
        complete:function(){
    //       $("#json_load").fadeOut(100);
        },
        timeout:2000
    });

    //placeholder 页面中的 input 提示
    $("input[type=text],input[type=password]").each(function(i,ele){
        var _this=$(this);
        var t_name=_this.attr('name');
        _this.attr("placeholder",vail_lang[t_name]);
    });

    // 用户密码
  var pass_regexp=/^[\w\.\@\-]{6,20}$/;

    // 数据库密码
  var pass_regexp_db=/^[\w\.\@\-]{2,20}$/;

    // 表单验证
    $('.b_from').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            pid: {  // 权限表单添加--------------------
                validators: {
                    notEmpty: {
                        message:vail_lang.pid
                    }
                }
            },
            mc_name: {
                validators: {
                    notEmpty: {
                        message: vail_lang.mc_name_empty
                    },
                    regexp: {
                        regexp: /^[a-zA-Z\/]{2,20}$/,
                        message: vail_lang.mc_name
                    }
                }
            },
            biaoshi_name:{
                validators: {
                    stringLength: {
                        max: 20,
                        message: vail_lang.biaoshi_name
                    },
                }
            },
            icon:{
                validators: {
                    stringLength: {
                        max: 20,
                        message: vail_lang.icon
                    },
                }
            },//权限表单添加结束----------------------------------------------添加 管理员用户、客户开始
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
            pass:{
                validators: {
                    regexp: {
                        regexp: pass_regexp,
                        message:vail_lang.pass2
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
                        message: vail_lang.pass2
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
            },
            r_id: {
                validators: {
                    notEmpty: {
                        message: vail_lang.r_id
                    },
                    regexp: {
                        regexp: /[0-9]/,
                        message: vail_lang.r_id
                    }
                }
            },//添加客户结束----------------------------------------------添加角色开始
            r_n:{
                validators: {
                    notEmpty: {
                        message: vail_lang.r_n_empty
                    },
                    stringLength: {
                        min:2,
                        max: 20,
                        message: vail_lang.r_n
                    },
                }
            },
            r_d:{
                validators: {
                    stringLength: {
                        max: 20,
                        message:vail_lang.r_d
                    },
                }
            },//添加角色结束----------------------------------------------ai 导航 开始
            nav_name: {
                validators: {
                    notEmpty: {
                       message: vail_lang.nav_name_empty
                    },
                    regexp: {
                        regexp: /^[a-zA-Z\-\_]{2,20}$/,
                        message: vail_lang.nav_name
                    }
                }
            },
            nav_biaoshi:{
                validators: {
                    stringLength: {
                        max: 20,
                        message: vail_lang.nav_biaoshi
                    },
                }
            },
            keyword:{
                validators: {
                    stringLength: {
                        max: 20,
                        message: vail_lang.keyword
                    },
                }
            },
            description: {
                validators: {
                    stringLength: {
                        max: 50,
                     message: vail_lang.description
                    }
                }
            },//  ai 导航结束------------------------------------- ai 文章 开始
            tit: {
                validators: {
                    notEmpty: {
                        message:vail_lang.tit_empty
                    },
                    stringLength: {
                        min: 2,
                        max: 20,
                        message: vail_lang.tit
                    }
                }
            },//  ai 文章结束------------------------------------- 系统自定义变量 开始
            systype:{
                validators: {
                    stringLength: {
                        max: 10,
						 message: vail_lang.systype
                    }
                }
            },
            systid: {
                validators: {
                    notEmpty: {
                        message: vail_lang.systid
                    }
                }
            },
            syskey: {
                validators: {
                    notEmpty: {
                         message: vail_lang.syskey_empty
                    },
                    stringLength: {
                        max: 20,
                        message: vail_lang.syskey
                    }
                }
            },
            sysval: {
                validators: {
                    notEmpty: {
                       message:vail_lang.sysval_empty
                    },
                    stringLength: {
                        max:200,
                        message: vail_lang.sysval
                    }
                }
            },
            notes:{
                validators: {
                    stringLength: {
                        max: 50,
                        message: vail_lang.notes
                    }
                }
            },// 系统自定义变量 结束--------------------------------------安装系统验证
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
        var pass_v=$('input[name=pass]').val();
        var pass2_v=$('input[name=pass2]').val();
        if(pass2_v!=pass_v)
        {
            $('input[name=pass],input[name=pass2]').parent().parent().removeClass('has-success').addClass('has-error');
            $('input[name=pass],input[name=pass2]').siblings('i').removeClass('glyphicon-ok').addClass('glyphicon-remove');
            $('input[name=pass],input[name=pass2]').siblings('small').removeAttr("style");

        }
    });
   // 关闭 模态框
    $('button[data-dismiss=modal]').click(function(){
        $(this).parents(".modal").hide();
      //  $(".modal").hide();
    });
});