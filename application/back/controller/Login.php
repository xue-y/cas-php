<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/24
 * Time: 17:18
 */

namespace app\back\controller;

class Login
{
    //TODO 当前用户的登录记录
    public function index()
    {
        $id=request()->user_id;
       return action('log/Login/index',$id);
    }

    //TODO 删除当前用户的操作记录
    public function delete()
    {
        $id=request()->user_id;
        return action('log/Login/delete',$id);
    }
}