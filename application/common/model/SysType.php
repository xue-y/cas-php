<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/17
 * Time: 10:43
 */

namespace app\common\model;

use think\Model;

class SysType extends Model
{

    public function delData($id)
    {
        return $this->where([['is_sys','<>',IS_SYS],['id','in',$id]])->delete();
    }

    public function getColumnByIds($id)
    {
       return $this->where([['is_sys','<>',IS_SYS],['id','in',$id]])->column('a_id');
    }

    public function updateField($id,$field,$value)
    {
        $where['id']=$id;
        return $this->where($where)->setField($field,$value);
    }

    public function getNextId(){
        $table=config('database.prefix').'sys_type';
        $database=config('database.database');
        $sql="SELECT AUTO_INCREMENT FROM information_schema.`tables` WHERE table_name='{$table}' AND table_schema='{$database}'";
        $result=$this->query($sql);
        return $result[0]["AUTO_INCREMENT"];
    }

}