<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/24
 * Time: 17:20
 * 用户操作记录
 */

namespace app\back\controller;

use think\Controller;

class Operate extends Controller
{

    //TODO 当前用户的操作记录
    public function index()
    {
        $this->view->engine->layout('../../common/view/layout');
        $id=request()->user_id;
        return action('log/Operate/index',$id);
    }

    //TODO 删除当前用户的操作记录
    public function delete()
    {
        $id=request()->user_id;
        return action('log/Operate/delete',$id);
    }

}