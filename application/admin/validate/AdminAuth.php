<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/18
 * Time: 14:21
 */

namespace app\admin\validate;

use think\Validate;

class AdminAuth extends Validate
{
    protected $regex=['url_name'=>'^[a-zA-Z]{2,30}$'];
    protected $message =[
        '__token__'=>'form_token'
        /*'pid.require' =>'pid',
        'name.require' => 'name_empty',
        'name.regex'=>'name',
        'nameame.length'=>'nameame',
        'icon.length'=>'icon',*/
    ];
    protected  $rule=[
        'pid' =>'require|isPid|egt:0',
        'name' =>'require|length:2,20',
        'module'=>'require|regex:url_name|length:2,20',
        'controller' =>'regex:url_name|length:2,20',
        'icon' =>'length:2,30',
        'sort' =>'number',
        '__token__'=>'require|token'
    ];

    protected function isPid($value,$rule='',$data){
        if($value==$data['id']){
            return false;
        }else{
            return true;
        }
    }
}