<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/10
 * Time: 11:23
 * 角色管理模型
 */

namespace app\common\model;

use think\Model;

class AdminRole extends Model
{
    protected $name='admin_role';

    /**
     * TODO 根据角色id 取得字段值
     * getFieldsById
     * @param int $id
     * @param string $fields 需要查询的字段名
     * @return array|null|\PDOStatement|string|Model
     */
    public function getFieldsById($id,$fields)
    {
         return $this->field($fields)->find($id);
    }

    /**
     * toggleField
     * @todo 更新单个字段
     * @param $id 数据ID
     * @param $field 更新字段
     * @param $value 需要更新的值
     * @return int
     */
    public function updateField($id,$field,$value)
    {
        $where=[
            ['id','=',$id]
        ];
        if(($field=='is_enable') || ($field=='a_id'))
        {
            // 内置不可修改为禁用
            $where[]=['a_id','<>',get_cas_config('admin_auth')];
        }
        return $this->where($where)->setField($field,$value);
    }

    public function getUserRole($where=[]){
        if(empty($where)){
            return $this->column('name','id'); // 所有角色
        }else{
            return $this->where($where)->column('name','id'); // 所有角色
        }
    }
}