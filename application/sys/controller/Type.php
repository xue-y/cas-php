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
    private $table="sys_type";
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
        unset($data["__token__"]);

        $sys_type=new SysType();
        $admin_auth=new AdminAuth();

        Db::startTrans();

        // 如果是修改
        if(!empty($data['id']))
        {
            $msg_fail=lang('edit_fail');
            $msg_success=lang('edit_success');
            $old_data=$sys_type->field('is_menu,a_id')->find($data['id']);
            // 如果菜单更改了层级,不属于当前控制器，菜单清空了 a_id
            if(empty($old_data['a_id']) && ($data["is_menu"]==IS_MENU)){
                $a_id=$this->addAuthMeun($data);
                if(!$a_id){
                    Db::rollback();
                    $this->error($msg_fail);
                }
                $data['a_id']=$a_id;
            }else{
                $data['a_id']=$old_data['a_id'];
                // 判断菜单表是否存在当前菜单
                $w['id']=$old_data['a_id'];
                $w['relation']=$this->table;
                $is_exis_meun=$admin_auth->where($w)->count();
                // 如果存在并更改了菜单
                if(($is_exis_meun==1) && ($data["is_menu"]!=$old_data['is_menu'])){
                    // 更新 菜单表数据
                    $is_update=$admin_auth->updateField($data['a_id'],'is_menu',$data["is_menu"]);
                    if(!$is_update){
                        Db::rollback();
                        $this->error($msg_fail);
                    }
                }
            }

            // 更新配置项分类表
           $is_save=$sys_type->allowField(true)->save($data,['id'=>$data['id']]);
            if(!$is_save){
                Db::rollback();
                $this->error($msg_fail);
            }
        }
        else {  // 如果是添加
            $msg_fail=lang('add_fail');
            $msg_success=lang('add_success');
            // 更新系统分类
            $id=$sys_type->allowField(true)->insertGetId($data);
            if(!$id){
                Db::rollback();
                $this->error($msg_fail);
            }
            // 更新菜单
            $data['id']=$id;
            $a_id=$this->addAuthMeun($data);
            if(!$a_id){
                Db::rollback();
                $this->error($msg_fail);
            }
            // 更新系统分类
            $is_update=$sys_type->updateField($id,'a_id',$a_id);
            if(!$is_update){
                Db::rollback();
                $this->error($msg_fail);
            }
        }
        Db::commit();
        $this->success($msg_success,'index');
    }

    //TODO 删除
    public function delete()
    {
        $id=$this->filterId();
        $sys_type=new SysType();
        // 根据ID 取得菜单ID
        $a_id=$sys_type->getColumnByIds($id);
        // 删除菜单
        $auth_is_del=AdminAuth::destroy($a_id); // 如果菜单已经删除了，会删除失败
        // 删除配置
        $is_del=$sys_type->delData($id);
        if($is_del) {
            $this->success(lang('del_success'),url('index'));
        }else {
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
        $sys_type=new SysType();
        $admin_auth=new AdminAuth();

        // 根据ID 取得 Aid
        $a_id=$sys_type->getFieldById($id,'a_id');

        Db::startTrans();

        // 如果菜单更改了层级，不属于当前控制器，菜单清空了 a_id ,重新添加一个菜单
        if(empty($a_id) && ($param["is_menu"]==IS_MENU)){
            $id_data=$sys_type->find($id);
            $id_data['is_menu']=$param['is_menu'];
            $a_id=$this->addAuthMeun($id_data); // 添加菜单
            $result=$sys_type->save(['is_menu'=>$param['is_menu'],'a_id'=>$a_id],['id'=>$param['id']]);
        }else{
            // 判断菜单表是否存在当前菜单是否存在
            $w['id']=$a_id;
            $w['relation']=$this->table;
            $is_exis_meun=$admin_auth->where($w)->count();

            if($is_exis_meun==1){
                // 更新菜单表
                $is_update=$admin_auth->updateField($a_id,'is_menu',$param["is_menu"]);
                if(!$is_update){
                    Db::rollback();
                    $this->error(lang('is_menu_fail'));
                }
            }else{
                //取出当前数据 如果菜单删除了，当前配置分类表还有 a_id,从新添加菜单
                $id_data=$sys_type->find($id);
                $id_data['is_menu']=$param['is_menu'];
                $this->addAuthMeun($id_data); // 添加菜单
            }
            $result=$sys_type->updateField($id,'is_menu',$param['is_menu']);
        }

        $this->update_meun_alert($result);
    }

    // TODO 更新菜单信息提示
    private function update_meun_alert($result){
        if($result)
        {
            Db::commit();
            $this->success(lang('is_menu_success'));
        }else
        {
            Db::rollback();
            $this->error(lang('is_menu_fail'));
        }
    }

    // 添加菜单
    private function addAuthMeun($data){
        $admin_auth=new AdminAuth();
        $curren_m=$this->request->module();// 当前模块名
        if(!empty($data['a_id'])){
            $auth_data['id']=$data['a_id'];
        }
        $auth_data['module']=$curren_m;
        $auth_data['pid']=$admin_auth->getFieldByModule($curren_m,'id');
        $auth_data['controller']='Item';
        $auth_data['action']='see';
        $auth_data['name']=$data['describe'];
        $auth_data['param']='t_id='.$data['id'];
        $auth_data['relation']="sys_type";
        $auth_data['is_menu']=$data['is_menu'];
        $result=$admin_auth->allowField(true)->save($auth_data);
        if(!$result){
            Db::rollback();
            $this->error(lang('add_fail'));
        }
        return $admin_auth->id;
    }

}