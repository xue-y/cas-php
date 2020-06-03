<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/10
 * Time: 15:16
 */

namespace app\admin\controller;

use Tree;
use app\common\model\AdminRole;
use app\common\model\AdminAuth;
use app\common\controller\Base;
use think\facade\Validate;

class Role extends Base
{
    //TODO 列表
    public function index()
    {
        $admin_role=new AdminRole();
        $list=$admin_role->select();
        $this->assign('list',$list);
        return $this->fetch();
    }

    //TODO 进入添加/修改页面
    public function edit()
    {
        $id=input('param.id/d');
        $admin_role=new AdminRole();
        $data['a_id']=0;
        if(!empty($id))
        {
            $data=$admin_role->find($id);
            if(empty($data))
            {
                $this->error(lang('error_id'));
            }
        }
        $this->assign('data',$data);
        return $this->fetch();
    }

    //TODO 执行修改
    public function save()
    {
        $data=request()->post();
        if(empty($data)){
            $this->error(lang('error_page'));
        }

        $validate = Validate::make([
            'name' => 'require|length:2,20',
            'describe' => 'max:20',
            '__token__'=>'require|token'
        ]);
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }

        $admin_role=new AdminRole();
        if(empty($data['id'])){
            if(isset($data["id"]))unset($data["id"]);
            $is_save=$admin_role->allowField(true)->save($data);
            $msg_fail=lang('add_fail');
            $msg_success=lang('add_success');
        }else{
            // 判断是不是修改的是不是系统内置角色，如果是不可修改是否启用
            $admin_role_id=$admin_role->getFieldByAId(get_cas_config('admin_auth'),'id');
            if($admin_role_id==$data['id']){
                if(isset($data['a_id']))unset($data['a_id']);
            }
            $is_save=$admin_role->allowField(true)->save($data,['id'=>$data['id']]);
            $msg_fail=lang('edit_fail');
            $msg_success=lang('edit_success');
        }
        if($is_save!==true)
        {
            $this->error($msg_fail);
        }else
        {
            $this->success($msg_success,url('index'));
        }
    }

    // 权限设置界面
    public function auth(){
        $id=input('param.id/d');
        if(empty($id))
        {
            $this->error(lang('error_id'));
        }
        // 判断是否是系统角色
        $admin_role=new AdminRole();
        // 根据角色取得 a_id;
        $a_id=$admin_role->getFieldById($id,'a_id');
        $select=$disable=[];
        $role_sys=0;
        $admin_auth=new AdminAuth();
        $auth_list=$admin_auth->geAuthList();
		
		
        if($a_id!==get_cas_config('admin_auth')){
            $role_sys=1;
            $select=explode(',',$a_id);
        }else{
            $disable=array_column($auth_list,'id');
        }
        $tree=new Tree();
        $auth_list=$tree->getTree($auth_list);
	
        $data['id']=$id;
        $data['role_sys']=$role_sys;
        $data['auth_list']=$tree->getTreeTableSelect($auth_list,$select,$disable);
        $this->assign('data',$data);
        return $this->fetch();
    }

    // 权限设置提交
    public function authSave(){
        $data=$this->request->post();
        if(empty($data['r_id']))
        {
            $this->error(lang('error_id'));
        }
        if(empty($data['id'])){
            $this->error(lang('error_empty'));
        }

        // 排除系统角色
        $a_id=array_unique(array_filter($data['id']));
        if(empty($a_id)){
            $this->error(lang('error_empty'));
        }
        $a_id=implode(',',$a_id);
        $admin_role=new AdminRole();
        $result=$admin_role->updateField($data['r_id'],'a_id',$a_id);
        if($result==1)
        {
            $this->success(lang('edit_success'),url('index'));
        }else
        {
            $this->error(lang('edit_fail'));
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
        // 移除系统内置角色
        $admin_role=new AdminRole();
        $admin_role_id=$admin_role->getFieldByAId(get_cas_config('admin_auth'),'id');
        $this->delSysData($id,$admin_role_id,$admin_role);
     }

    //TODO ajax 执行更新单个字段
    public function ajaxOperate()
    {
        $param=$this->request->param();
        if(empty($param['id']) || (intval($param['id'])<1))
        {
            $this->error(lang('error_id'),null,$param);
        }
        $id=intval($param['id']);
        $admin_role=new AdminRole();
        $result=$admin_role->updateField($id,'is_enable',$param['is_enable']);
        if($result==1)
        {
            $this->success(lang('is_enable_success'));
        }else
        {
            $this->error(lang('is_enable_fail'));
        }
    }
}