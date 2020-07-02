<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2020/6/27
 * Time: 14:11
 */

namespace app\sys\controller;

use app\common\controller\Base;
use app\common\controller\Form;
use app\common\model\SysItem;
use app\common\model\SysType;
use think\facade\Validate;

class Set extends Base
{
    public function index(){
        $t_id=input('param.t_id/d',1);
        $sys_item=new SysItem();
        $list=$sys_item->getTypeItemData($t_id);

        $sys_type=new SysType();
        // 构建表单
        $form=new Form();
        return $form->tabNav(
            $sys_type->column('describe','id'),$t_id,'','','t_id'
        )->method('post')->action(url('save'))->token()->hiddenItem([
            ['name'=>'t_id','value'=>$t_id]
        ])->fieldItem($list)->create();
    }

    public function save(){
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