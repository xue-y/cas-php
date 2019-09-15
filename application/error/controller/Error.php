<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/9
 * Time: 14:11
 */

namespace app\error\controller;


use think\Controller;

class Error extends Controller
{
    //TODO 空方法
    public function _empty()
    {
        if(!empty(cookie('no_access_rights')))
        {
            cookie('no_access_rights',null);
            $this->redirect_page();
            exit;
        }
       require '404.html';
    }

    //TODO 错误提示页面-- 没有访问权限
    private function redirect_page()
    {
        $this->error(lang('no_access_rights'));
    }
}