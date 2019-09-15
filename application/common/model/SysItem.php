<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/8
 * Time: 19:23
 * 用户自定义配置
 */

namespace app\common\model;


use think\Model;

class SysItem extends Model
{
    /**
     * TODO 获取设置项的值
     * getValueByName
     * @param $type  设置项类型
     * @param $name  设置项值名称
     * @return mixed
     */
    public function getValueByName($type,$name){
        $where['t_id']=$type;
        $where['name']=$name;
        return $this->where($where)->value('val');
    }

    /**
     * getTypeSettings
     * @todo 取得某个分类下的设置项指定字段
     * @param int $t_id 分类ID
     * @param string $key 列数据key字段
     * @param string $val 列数据val字段
     * @return array
     */
    public function getTypeSettings($t_id,$key,$val)
    {
        return $this->where("t_id",'=',$t_id)->column($val,$key);
    }

    /**
     * delData
     * @todo 删除数据
     * @param string $id
     * @return bool
     * @throws \Exception
     */
    public function delData($id)
    {
        return $this->where([['is_sys','<>',IS_SYS],['id','in',$id]])->delete();
    }


    /**
     * getTypeItemData
     * @todo 获取某个分类下的数据
     * @param int $t_id 分类ID
     * @return mixed
     */
    public function getTypeItemData($t_id)
    {
        return $this->field('id,`name` as field_name,`describe` as label,val,type,notes')->whereTId($t_id)->order('sort asc,id asc')->select()->toArray();
    }

    // 设置配置项数据
    public function setItemData($data,$t_id){
        $where['t_id']=$t_id;
        $is_save=0;
        foreach ($data as $k=>$v)
        {
            $where['name']=$k;
            $is_save+=$this->where($where)->setField('val',$v);
            echo '<br/>';
            echo $this->getLastSql();
        }
    }



}