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

    //判断用户名是否唯一 isNameUnique
    public function isNameUnique($n,$id=null)
    {
        $w[]=['name','=',$n];
        if(isset($id))
            $w[]=['id','<>',$id];
        return $this->where($w)->count();
    }

    // 根据用户id 取得一个、多个字段 一条数据
    public function id_fields($id,$field)
    {
        return $this->field($field)->find($id);
    }

    // 根据多个id 取得单个字段 返回多条数据
    public function id_fields_num($ids,$field='r_id')
    {
        return $this->whereIn('id',$ids)->column($field,'id');
    }

    // 根据用户id 取得单个字段
    public function uid_field($uid,$field)
    {
       return $this->getFieldById($uid,$field);
    }

}