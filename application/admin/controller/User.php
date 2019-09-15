<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/22
 * Time: 10:21
 * 限制不可添加超级管理员用户
 * 不可将用户角色修改为超级管理员
 * 不可删除角色是超级管理员用户
 * 当前用户、超级管理员用户不可修改自己的角色，修改密码需要原密码
 */

namespace app\admin\controller;

use app\common\controller\Base;
use app\common\model\AdminRole;
use app\common\model\AdminUser;
use app\admin\validate\AdminUser as VAdminUser;

class User extends Base
{
    //TODO 列表
    public function index()
    {
        $admin_role=new AdminRole();
        $assign['role']=$admin_role->getUserRole(); // 所有角色
        // 系统内置用户
        $assign['user_sys']=$admin_role->getFieldByAId(get_cas_config('admin_auth'),'id');
        // 所有用户
        $admin_user=new AdminUser();
        $assign['list']=$admin_user->getList();
        $this->assign($assign);
        return $this->fetch();
    }

    //TODO 进入添加、修改界面
    public function edit()
    {
        $admin_role=new AdminRole();
        // 排除超级管理员
        $where=[
            ['a_id','<>',get_cas_config('admin_auth')]
        ];
        $assign['role']=$admin_role->getUserRole($where); // 所有角色

        $id=input('param.id/d');
        if(!empty($id))
        {
            $admin_user=new AdminUser();
            $assign['data']=$admin_user->field('pass,email',true)->find($id);
        }
        $this->assign($assign);
        return $this->fetch();
    }

    // TODO 执行添加、修改
    public function save()
    {
        $data=request()->post();
        if(empty($data)) {
            $this->error(lang('error_page'));
        }

        // 验证数据
        $validate=new VAdminUser();
        if (!$validate->scene('UserSave')->check($data)) {
            $this->error($validate->getError());
        }

        // 限制不可添加/修改为 超级管理员角色
        $admin_role=new AdminRole();
        $user_sys=$admin_role->getFieldByAId(get_cas_config('admin_auth'),'id');
        if($data['r_id']==$user_sys)
        {
            $this->error(lang('role_select'));
        }

        $admin_user=new AdminUser();

        // 判断是修改还是添加----密码判断
        if(empty($data['id'])){
            if(empty($data["pass"])) {
                $data["pass"]=encry(config('default_pass'));
            }else {
                $data["pass"]=encry($data["pass"]);
            }
            $msg_fail=lang('add_fail');
            $msg_success=lang('add_success');
            if(isset($data['id']))unset($data['id']);
            $is_save=$admin_user->allowField(true)->save($data);
        }else{
            // 取得系统用户
            $user_sys_id=$admin_user->getFieldByRId($user_sys,'id');
            if($user_sys_id==$data['id']){
                $this->error(lang('error_page'));
            }
            if(!empty($data["pass"])){
                $data["pass"]=encry($data["pass"]);
            }
            $msg_fail=lang('edit_fail');
            $msg_success=lang('edit_success');
            $is_save=$admin_user->allowField(true)->save($data,['id'=>$data['id']]);
        }

        if($is_save!==true) {
            $this->error(lang($msg_fail));
        }else{
            $this->success(lang($msg_success),url('index'));
        }
    }

    //TODO 删除
    public function delete()
    {
        $id=input('param.id');
        if(empty($id))
        {
            $this->error(lang('error_id'));
        }
        // 取得系统内置角色
        $admin_role=new AdminRole();
        $user_sys=$admin_role->getFieldByAId(get_cas_config('admin_auth'),'id');
        // 根据角色取得内置用户
        $admin_user=new AdminUser();
        $admin_user_id=$admin_user->getFieldByRId($user_sys,'id');
        $this->delSysData($id,$admin_user_id,$admin_user);
    }

}