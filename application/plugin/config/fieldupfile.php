<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2020/7/2
 * Time: 13:49
 */
return [
    'local'=>[
        'uploadTmpDir'=>'form_upload_tmp',// 此目录是相对调用页面的目录创建文件夹
        'uploadDir'=>'form_upload',// 此目录是相对调用页面的目录创建文件夹
        'maxFileAge'=>3600,  // Remove temp file if it is older than the max age and is not the current file
        'file_root_dir'=>\think\facade\Env::get('app_path'),// 文件存放目录
        'file_url_dir'=>'/', // 文件访问域名路径
        'del_file_url'=>"/plugin/FieldUpfile/delete", // 必传
        'up_file_url'=>"/plugin/FieldUpfile/upLocal" ,// 必传
        'up_field_name'=>'up_file' //
    ],
];