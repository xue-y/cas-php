<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 定义路由

Route::any('/back/Lock','back/LockScreen/index'); // 锁屏
Route::get('captcha/[:id]', "\\think\\captcha\\CaptchaController@index"); // 验证码访问路径
//Route::any('plugin/[:_plugin]/[:_controller]/[:_action]', "\\app\\controller\\Plugin@index");// 插件