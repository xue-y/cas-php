<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/9/15
 * Time: 8:17
 * 表单构建类
 */

namespace app\common\controller;

use think\Controller;
use think\facade\Env;

class Form extends Controller
{
    // 后期完善表单字段
    public $field="text,textarea,number,email,url,select,ordio,date,date-range,time,time-range,img,upload-img,layui-icon,all-icon,simple-editor,all-editor";

    private $html;
    // 默认字段类型
    private $default_field_type='text';

}