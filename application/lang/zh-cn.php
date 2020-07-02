<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-9
 * Time: 下午4:09
 */
// 数据表字段注释 视图字段
$admin_user=[
    "name_takeeffect"=>"如果修改当前用户名下次登录生效",
    "default_pass"=>"如果密码为空使用默认密码 %s",
    "name"=>"用户名",
    'r_id'=>"角色ID",
];
$admin_role=[
    "name"=>"角色名称",
    "describe"=>"角色描述",
    "a_id"=>"admin_auth权限ID集合;多个ID中间用英文逗号分隔",
];
$admin_auth=[
    "url"=>"链接地址",
    "name"=>"权限标识名名称",
    "pid"=>"上级",
    "module"=>"模块名称",
    "controller"=>"控制器名称",
    "icon"=>"菜单图标",
    "action"=>"访问方法",
    "action_info"=>"默认访问方法,为空默认访问配置文件中的默认方法",
    "param"=>"访问参数",
    "param_info"=>"访问参数url字符串形式",
    "relation"=>"关联数据表",
    "relation_info"=>"不包含表前缀"
];
$sys_type=[
    "name"=>"分类名称",
    "describe"=>"分类标识名",
    "is_menu"=>"是否为系统配置子菜单,0不是菜单,1菜单",
    "a_id"=>"菜单ID"
];
$sys_item=[
    "t_id"=>"配置项分类",
    "t_id_info"=>"配置项分类ID",
    "name"=>"配置项名称",
    "describe"=>"配置项标识名",
	"describe_info"=>"名称必填，2--20个字符之间",
    "notes"=>"配置项说明",
    "val"=>"配置项值",
    "val_info"=>"配置项值;多个值中间用英文逗号分隔,日期格式：YYYY-mm-dd，日期区间格式YYYY-mm-dd - YYYY-mm-dd，时间格式：HH:mm:ss,时间区间HH:mm:ss - HH:mm:ss",
    "field_type"=>"表单字段类型",
];
$log_login=[  
    "uid"=>"登录用户ID",
    "t"=>"登录时间",
    "device"=>"登录设备",
    "ip"=>"登录IP",
];
$log_operate=[
    "uid"=>"操作用户ID",
    "t"=>"操作时间",
    "behavior"=>"操作行为",
    "details"=>"操作详情",
];

$log_queue=[

];

$back_index=[];

$tool_crontab=[
    "name"=>"任务名称",
    "action"=>"执行方法名",
    "action_info"=>"/module/controller/action",
    "crontab"=>"执行周期",
    "crontab_info"=>"crontab格式",
    "next_time"=>"下次执行时间",
    "exec_time"=>"最后执行时间",
    "exec_status"=>"最后执行结果",
    "exec_count"=>"已执行次数",
    "task_status"=>"最后任务状态",
    "exec_expire"=>"过期是否执行",
    "exec"=>"执行",
    "no_exec"=>"不执行",
	"expire"=>"已过期",
    "no_expire"=>"未过期",
    "name_vail"=>"任务名不得为空,2-20个字符",
    "action_vail"=>"执行任务方法名必填,5-50个字符,大小写字母、下划线、.、/",
    "crontab_vail"=>"执行任务时间必填,5-50个字符",
    "crontab_format_error"=>"crontab格式错误",
    "get_next_time"=>"获取下次时间失败",
    "fail_count"=>"执行次数",
    "count_info"=>"任务失败尝试执行次数",
];
//任务执行
$crontab_exec=[
    "now_crontab_no_complete"=>"当前还有任务未执行完成",
    "create_lock_file_fail"=>"执行定时任务创建锁文件失败",
    "update_crontab_status_fail"=>"更新当前任务转态失败",
    "write_log_fail"=>"记录任务日志失败",
    "create_complete_del_fail"=>"任务执行完成删除任务锁文件失败"
];
/*
"warning"=>"警告！",
"show"=>"显示",
"hidden"=>"隐藏",
"operation_write_fail"=>"操作记录写入失败: 行为 "
*/
return [
    // 基本访问方法
    "index"=>"列表",
    "add"=>"添加",
    "edit"=>"更新",
    "save"=>"更新",
    "auth"=>"设置权限",
    "sort"=>"排序",
    "delete"=>"删除",
    "deletes"=>"批量删除",
    "operation"=>"操作",
    "ajaxoperate"=>"操作",
    "update_sort"=>"更新排序",
    "search"=>"搜索",
    "reset"=>"清空",
    "main"=>"首页",
    "see"=>"查看",
    // 执行操作提示语
    "close"=>"关闭",
    "cancel"=>"取消",
    "confirm"=>"确认",
    'fail'=>'失败',
    'success'=>'成功',
    'normal'=>'正常',
    'abnormal'=>'异常',
    "exec"=>"已执行",
    "noexec"=>"未执行",
    "expire"=>"过期",
    'select'=>'请选择',
    'select_data_empty'=>'select 字段数据不的为空',
    'select_empty'=>'请选择要操作的数据',
    "del_msg"=>"您确认要删除吗？",
    "prompt_message"=>"提示信息",
    "page_auto"=>"页面自动",
    "jump"=>"跳转",
    // 表单提交验证
    "captcha"=>"验证码错误",
    "form_token"=>"表单提交超时请重新提交",
    "no_access_rights"=>"您没有访问权限",
    "submit_save"=>"保存",
    "url_back"=>"返回",
    "data_empty"=>"暂无数据",
    // 页面视图字段 数据表字段
    "admin_user"=>$admin_user,
    "admin_role"=>$admin_role,
    "admin_auth"=>$admin_auth,
    "sys_type"=>$sys_type,
    "sys_item"=>$sys_item,
    "log_login"=>$log_login,
    "log_operate"=>$log_operate,
    "tool_crontab"=>$tool_crontab,
    "crontab_exec"=>$crontab_exec,
    // 错误
    "error_page"=>"页面错误或过期",
    "error_param"=>"参数错误",
    "error_id"=>"您访问的页面不存在",
    "error_empty"=>"您没有选择数据",
    "t_start"=>"开始时间",
    "t_end"=>"结束时间",
    // 控制器信息提示页面
    "not_role"=>"未知角色",
    "not_module"=>"未知模块",
    "not_type"=>"未知分类",
    "add_fail"=>"添加失败",
    "add_success"=>"添加成功",
    "edit_fail"=>"修改失败",
    "edit_success"=>"修改成功",
    "update_sort_fail"=>"更新排序失败",
    "update_sort_success"=>"更新排序成功",
    "update_fail"=>"未更新数据",
    "update_success"=>"更新成功",
    "del_fail"=>"删除失败",
    "delete_fail"=>"删除失败或系统内置不可删除",
    "del_success"=>"删除成功",
    "del_sys"=>"系统内置不可删除",
    //tit_模块名_控制器  模块下控制器 标题
    "tit_back_index"=>"后台首页",
    "tit_pub_pass"=>"找回密码",
    "tit_pub_repass"=>"重置密码",
    "tit_pub_activate"=>"激活邮箱重置密码",
    'tit_admin_user'=>'用户',
    'tit_admin_role'=>'角色',
    'tit_admin_auth'=>'权限',
    'tit_sys_type'=>'配置类别',
    'tit_sys_sset'=>'配置项',
    'tit_log_login'=>'日志记录',
    'tit_log_operate'=>'操作记录',
    // log 操作记录
    "log_start_t"=>"开始时间",
    "log_end_t"=>"结束时间",
    "log_n_search"=>"按用户名称",
    "log_behavior_search"=>"按操作行为",
    "log_data_c"=>"总数据条数：",
    "log_page_c"=>"共 %s 页",
    "log_list"=>"点击查看全部",
    "log_name"=>"登录用户",
    "log_user_del"=>"ID:%s 用户已删除",
    "log_shebei"=>"点击弹出/隐藏详细信息",
    // web LockScreen 锁屏 
    "lock_t"=>"登录密码错误解封时间，时间单位秒",
    "lock_screen"=>"锁屏",
    "unlock_tips"=>"欢迎回来",
    "unlock_screen"=>"请输入登录密码解锁",
    "unlock_error_max"=>"登录密码次数已达上限次数，自动封锁，%d小时候后自动解锁",
    "unlock_residue_degree"=>"密码错误，您还有 %s 次机会",
    "unlock_error"=>"解屏密码错误",
    "unlock"=>"开锁",
    // pass
    "pass"=>"新密码",
    "pass_text"=>"新密码",
    "pass2_text"=>"确认密码",
    "pass_old"=>"原密码",
    "pass_oldpass"=>"原密码与新密码不可一致",
    "pass_confirm"=>"两次密码不一致",
    "pass_oldpass_error"=>"原密码错误",
    "pass_empty_info"=>"密码为空默认不修改密码",
    "pass_repass"=>"重置密码",
    // 邮箱
    "email"=>"邮箱",
    "email_send"=>"发送邮件",
    "email_bind"=>"绑定邮箱",
    "email_ubind"=>"解除邮箱绑定",
    "email_activate"=>"用户你好，您在 %s，请点此链接 %s ，如果非本人操作请不要点击此链接",
    "email_send_fail"=>"邮件发送失败",
    "email_again_send"=>"重新发送",
    "email_send_success"=>"进入邮箱",
    "email_empty"=>"邮箱地址不得为空",
    "email_expire"=>"邮件已失效，请重新绑定激活",
    "email_send_c"=>"今日发送邮件已达到最大限度 %d 次，请 %d 小时后在继续操作",
    "email_alter_info"=>"如果收件箱没有收到邮件请稍后查看或查看垃圾箱",
    "queue"=>"消息队列",
    "queue_write_log_error"=>"记录消息列队日志",
    "queue_send_fail"=>"发送队列失败",
    "queue_name"=>"消息队列名称",
    "queue_action"=>"执行队列方法名",
    "task_exec_stauts"=>"执行状态",
    "is_abnormal"=>"是否异常",
	"is_expire"=>"是否过期",
    // 添加判断
    "is_enable_text"=>"是否启用",
    "is_menu_text"=>"是否为菜单",
    "is_sys_text"=>"是否系统内置",
    "is_auth_text"=>"基本权限",
    /*"is_enable_info"=>"0禁用,1启用",
    "is_menu_info"=>"0不是菜单,1菜单",
    "is_sys_info"=>"系统内置不可删除",*/
    "is_auth_info"=>"基本权限用户登录就可访问的页面",
    "is_sys_disable"=>"系统内置不可禁用",
    "is_menu_but"=>"是|否",
    "is_enable_but"=>"已启用|已禁用",
    "is_menu_success"=>"更新菜单成功",
    "is_menu_fail"=>"更新菜单失败",
    "is_enable_success"=>"更新启用/禁用成功",
    "is_enable_fail"=>"更新启用/禁用失败",
    "is_yes"=>"是",
    "is_no"=>"否",
    "is_enable"=>"启用",
    "is_disable"=>"禁用",
    "is_enable_end"=>"已启用",
     // back_index
    "login_user_info"=>"此账号 %s 在其他设备终端登录，如果不是本人，请修改密码查看",
    "login_out"=>"退出登录", 
    // 图片上传
    "img_up_empty"=>"您没有上传图片文件",
    "img_size_max"=>"您上传的图片大于 %d MB",
    "img_up_error_1"=>"超过php.ini允许的大小。",
    "img_up_error_2"=>"超过表单允许的大小。",
    "img_up_error_3"=>"图片只有部分被上传。",
    "img_up_error_4"=>"请选择图片。",
    "img_up_error_6"=>"找不到临时目录。",
    "img_up_error_7"=>"写文件到硬盘出错。",
    "img_up_error_8"=>"禁止上传的文件扩展名。",
    "img_up_error_9"=>"未知错误。",
    "img_mime_error"=>"不支持上传的图片类型",
];