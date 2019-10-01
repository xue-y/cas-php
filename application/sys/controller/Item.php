<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/17
 * Time: 16:45
 */

namespace app\sys\controller;

use app\common\controller\Base;
use app\common\controller\Form;
use app\common\model\SysItem;
use app\common\model\SysType;
use think\facade\Validate;

class Item extends Base
{
    // 默认字段类型
    private $default_field_type='text';
    private $field_type='text,number,email,url,textarea,date,date-range,time,time-range,img';

    //TODO 配置项列表页面
    public function index()
    {
        $sys_item=new SysItem();
        $t_id=input('param.t_id/d',1);
        $list=$sys_item->whereTId($t_id)->order('sort asc,id asc')->select();
        $sys_type=new SysType();
        $this->assign([
            'type'=>$sys_type->column('describe','id'),
            'list'=>$list,
            't_id'=>$t_id
        ]);
        return $this->fetch();
    }

    // TODO 进入添加、修改页面
    public function edit(){
        $t_id=input('param.t_id/d',1);
        $id=input('param.id/d');
        $sys_type=new SysType();
        $field_type=explode(',',$this->field_type);
        $this->assign([
            'type'=>$sys_type->column('describe','id'),
            't_id'=>$t_id,
            'field'=>$field_type
        ]);
        if(!empty($id)){
            $sys_item=new SysItem();
            $data=$sys_item->find($id);
            $this->assign('data',$data);
            return $this->fetch();
        }else{
            return $this->fetch('create');
        }
    }

    //TODO 执行添加、修改
    public function save(){
        // 系统配置限制不可修改 t_id,name ,只是前端做了限制
        $data=request()->post();
        if(empty($data)) {
            $this->error(lang('error_page'));
        }
        // 验证数据
        $validate = Validate::make([
            '__token__'=>'require|token',
            't_id'=>'require|number|egt:1',
            'name' => 'require|unique:sys_item,t_id^name|regex:[a-zA-Z\_\-]{2,20}',
            'describe' => 'require|length:2,20',
            'val' => 'max:255',
            'type'=> 'require|length:2,20',
            'notes'=>'max:100',
            'sort'=>'number'
        ]);
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }
        // 字段处理
        if(isset($data['is_sys']))unset($data['is_sys']);
        if(empty($data['field']))$data['field']=$this->default_field_type;
        // 处理数据
        $sys_item=new SysItem();
        if(empty($data['id'])){
            $is_save=$sys_item->allowField(true)->save($data);
            $msg_fail=lang('add_fail');
            $msg_success=lang('add_success');
        }else{
            // 返回 bool 类型  true
            $is_save=$sys_item->allowField(true)->save($data,['id'=>$data['id']]);
            $msg_fail=lang('edit_fail');
            $msg_success=lang('edit_success');
        }
        if($is_save===true) {
            $this->success($msg_success,url('index',['t_id'=>$data['t_id']]));
        }else {
            $this->error($msg_fail);
        }
    }

    //TODO 删除
    public function delete(){
        $id=input('param.id');
        if(empty($id))
        {
            $this->error(lang('error_id'));
        }
        if(is_array($id))
        {
            $current_id=$id[0];
            $id=implode(",",array_unique(array_filter($id)));
        }else{
            $current_id=$id;
        }
        // 删除配置
        $sys_item=new SysItem();
        // 返回 int 类型  1
        $is_del=$sys_item->delData($id);
        if($is_del) {
            // 取得t_id
            $t_id=$sys_item->getFieldById($current_id,'t_id');
            $this->success(lang('del_success'),url('index',['t_id'=>$t_id]));
        }else {
            $this->error(lang('del_fail'));
        }
    }

    // TODO 查看/设置配置项数据
    public function see()
    {
        $t_id=input('param.t_id/d',1);
        $sys_item=new SysItem();
        $list=$sys_item->getTypeItemData($t_id);

        $sys_type=new SysType();
        // 构建表单
        $form=new Form();
        return $form->tabNav(
            $sys_type->column('describe','id'),$t_id,'','','t_id'
         )->method('post')->action(url('seeSave'))->token()->hiddenItem([
             ['name'=>'t_id','value'=>$t_id]
        ])->fieldItem($list)->create();

    }

    //TODO 设置配置项数据
    public function seeSave(){
        $data=request()->post();
        // 验证数据
        $validate = Validate::make([
            '__token__'=>'require|token',
            't_id'=>'require|number|egt:1'
        ]);
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }
        $sys_item=new SysItem();
        $t_id=$data['t_id'];
        unset($data['t_id']);
        unset($data['__token__']);
        $is_save=$sys_item->setItemData($data,$t_id);
        if($is_save>=1) {
            $this->success(lang('update_success'),url('index',['t_id'=>$t_id]));
        }else {
            $this->error(lang('update_fail'));
        }
    }
}