<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/17
 * Time: 10:37
 */

namespace app\sys\controller;

use app\common\controller\Base;
use app\common\model\AdminAuth;
use app\common\model\SysType;
use think\Db;
use think\facade\Validate;

class Type extends Base
{
    public function index()
    {
        $sys_type=new SysType();
        $this->assign('list',$sys_type->select());
        return $this->fetch();
    }

    //TODO 进入添加、修改页面
    public function edit()
    {
        // js 方式进入页面 修改失败返回页面 ERR_CACHE_MISS
        session_cache_limiter('private');

        $id=input('param.id/a');
        if(!empty($id))
        {
            $sys_type=new SysType();
            $data=$sys_type->find($id);
            if(empty($data))
            {
                $this->error(lang('error_page'));
            }
            $this->assign("data",$data);
        }
        return $this->fetch();
    }

    //TODO 执行添加、修改
    public function save()
    {
        $data=request()->post();
        if(empty($data))
        {
            $this->error(lang('error_page'));
        }
        // 验证数据
        $validate = Validate::make([
            '__token__'=>'require|token',
             'name' => 'require|unique:sys_type,name|regex:[a-zA-Z\_\-]{2,20}',
             'describe' => 'require|length:2,20',
             'is_menu' => 'number',
         ]);
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }

        $sys_type=new SysType();

        // 菜单
        $admin_auth=new AdminAuth();
        $curren_m=$this->request->module();// 当前模块名
        $auth_data['controller']='Item';
        $auth_data['action']='see';
        $auth_data['name']=$data['describe'];
        $auth_data['param']='t_id='.$sys_type->getNextId();
        //$admin_auth['param']='type='.$data['name'];
        $auth_data['is_menu']=$data['is_menu'];

        Db::startTrans();
        // 判断是添加还是修改
        if(empty($data['id'])){
            if(isset($data["id"]))unset($data["id"]);
            // 添加菜单
            $auth_data['module']=$curren_m;
            $auth_data['pid']=$admin_auth->getFieldByModule($curren_m,'id');
            $admin_auth->allowField(true)->save($auth_data);
            if(!$admin_auth->id){
                Db::rollback();
                $this->error(lang('add_fail'));
            }
            $data['a_id']=$admin_auth->id;
            $is_save=$sys_type->allowField(true)->save($data);
            $msg_fail=lang('add_fail');
            $msg_success=lang('add_success');
        }else{
            // 更新菜单，取得菜单ID
            $a_id=$sys_type->getFieldById($data['id'],'a_id');
            $auth_is_save=$admin_auth->save($auth_data,['id'=>$a_id]);
            if(!$auth_is_save){
                Db::rollback();
                $this->error(lang('edit_fail'));
            }
            // 限制系统名称不可修改,只是前端做了限制
            $is_save=$sys_type->allowField(true)->save($data,['id'=>$data['id']]);
            $msg_fail=lang('edit_fail');
            $msg_success=lang('edit_success');
        }
        // 跳转页面
        if($is_save===true) {
            Db::commit();
            $this->success($msg_success,url('index'));
        }else {
            Db::rollback();
            $this->error($msg_fail);
        }
    }

    //TODO 删除
    public function delete()
    {
        $id=$this->filterId();
        $sys_type=new SysType();

        // 根据ID 取得菜单ID
        $a_id=$sys_type->getColumnByIds($id);
        // 删除菜单
        Db::startTrans();
        $auth_is_del=AdminAuth::destroy($a_id);
        if($auth_is_del!=true){
            Db::rollback();
            $this->error(lang('del_fail'));
        }
        // 删除配置
        $is_del=$sys_type->delData($id);
        if($is_del) {
            Db::commit();
            $this->success(lang('del_success'),url('index'));
        }else {
            Db::rollback();
            $this->error(lang('del_fail'));
        }
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

        // 根据ID 取得 Aid
        $sys_type=new SysType();
        $a_id=$sys_type->getFieldById($id,'a_id');
        // 菜单ID是 默认值 ，如果设置为菜单
        if(empty($a_id) && $param['is_menu']==IS_MENU){
           // 如果不存在菜单ID 添加数据
            //取出当前数据
            $id_data=$sys_type->find($id);
            // 组装菜单数据
            $curren_m=$this->request->module();// 当前模块名
            $auth_data['controller']='Item';
            $auth_data['action']='see';
            $auth_data['name']=$id_data['describe'];
            $auth_data['is_menu']=$param['is_menu'];
            $auth_data['param']='t_id='.$sys_type->getNextId();
            $auth_data['module']=$curren_m;
            $admin_auth=new AdminAuth();
            $auth_data['pid']=$admin_auth->getFieldByModule($curren_m,'id');

            Db::startTrans();
            $admin_auth->allowField(true)->save($auth_data);
            if(!$admin_auth->id){
                Db::rollback();
                $this->error(lang('is_menu_fail'));
            }
            $sys_data['is_menu']=$param['is_menu'];
            $sys_data['a_id']=$admin_auth->id;
            $result=$sys_type->allowField(true)->save($sys_data,['id'=>$id]);
            $this->update_meun_alert($result);
        }else{
            // 更新admin_anth
            Db::startTrans();
            $admin_auth=new AdminAuth();
            $result=$admin_auth->updateField($a_id,'is_menu',$param['is_menu']);
            if($result!=1)
            {
                Db::rollback();
                $this->error(lang('is_menu_fail'));
            }
            $result=$sys_type->updateField($id,'is_menu',$param['is_menu']);
            $this->update_meun_alert($result);
        }
    }

    // TODO 更新菜单信息提示
    private function update_meun_alert($result){
        if($result==1)
        {
            Db::commit();
            $this->success(lang('is_menu_success'));
        }else
        {
            Db::rollback();
            $this->error(lang('is_menu_fail'));
        }
    }
}