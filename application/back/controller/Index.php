<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/8
 * Time: 10:49
 * 后台系统首页
 */

namespace app\back\controller;

use app\common\model\AdminAuth;
use app\common\model\AdminRole;
use think\Controller;
use Tree;

class Index extends Controller
{
    //TODO 后台系统主页
    public function main()
    {
        // 左边菜单
       $admin_auth=new AdminAuth();
       $admin_role=new AdminRole();

       // 根据不同权限显示不同的左菜单
       //dump($this->request->r_id);
       $a_id=$admin_role->getFieldById(input('param.r_id'),'a_id');
       if(empty($a_id))
       {
           $left_menu=$admin_auth->getComMenu();
       }else if($a_id==get_cas_config('admin_auth'))
       {
           $left_menu=$admin_auth->getMenu();
       }else
       {
           $left_menu=$admin_auth->getMenu($a_id);
       }

       $tree=new Tree();
       $assign['menu']=$menu=$tree->getTree($left_menu);
       $assign['name']=$this->request->user_name;
       $assign['id']=$this->request->user_id;
       // 此账号其他设备终端登录提醒
        $login_user_old=cookie('login_user_old');
        $assign['login_user']='';
        if(!empty($login_user_old))
        {
            $assign['login_user']=date('Y-m-d H:i:s',$login_user_old);
            cookie('login_user_old',null);
        }
       return view('/main',$assign);
    }

    //TODO 后台系统 子首页
    public function index()
    {
        return view('/index');
    }

}