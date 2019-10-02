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
    protected $message =[
        '__token__'=>'form_token'
    ];

    protected  $rule=[
        'pid' =>'require|isPid|egt:0',
        'name' =>'require|length:2,20',
        'module'=>'require|regex:[a-zA-Z]{2,30}|length:2,20',
        'controller' =>'regex:[a-zA-Z]{2,30}|length:2,20',
        'action'=>'regex:[a-zA-Z]{2,30}|length:2,20',
        'icon' =>'length:2,30',
        'sort' =>'number',
        '__token__'=>'require|token'
    ];

    //TODO 选择父级
    protected function isPid($value,$rule='',$data){
        if($value==$data['id']){
            return false;
        }else{
            return true;
        }
    }
}