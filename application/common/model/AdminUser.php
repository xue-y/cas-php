<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/8
 * Time: 18:16
 */

namespace app\common\model;


use think\Model;

class AdminUser extends Model
{
    // 根据用户名查询是否存在此用户
    public function isUser($name)
    {
        $where['name']=$name;
        $where['is_enable']=IS_ENABLE;
       return $this->where($where)->find();
    }

    /**
     * TODO 取得 一列数据
     * getColumnField
     * @param string $field
     * @param string $key
     * @return array
     */
    public function getColumnField($field='name',$key="id")
    {
        return  $this->column($field,$key);
    }

    // 取得所有用户
    public function getList()
    {
        return $this->field('pass',true)->select();
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
        if(($field=='is_enable'))
        {
            // 内置不可修改为禁用--从数据库查询角色ID
            $where[]=['r_id','<>',1];
        }
        return $this->where($where)->setField($field,$value);
    }

}