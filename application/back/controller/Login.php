<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/24
 * Time: 17:18
 * 当前用户登录记录
 */

namespace app\back\controller;

use think\Controller;

class Login extends Controller
{
    //TODO 当前用户的登录记录
    public function index()
    {
        $this->view->engine->layout('../../common/view/layout');
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