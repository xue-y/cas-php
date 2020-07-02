<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2020/6/27
 * Time: 21:15
 */

namespace app\common\model;


use think\Model;

class ToolCrontab extends Model
{
    protected $autoWriteTimestamp = 'datetime';
	private $data_sort='sort desc,id desc';

    /**
     * getList
     * @param null $w 查询条件
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($w=null)
    {
        $list_rows=config('list_rows');
        if(!empty($w))
        {
            return $this->where($w)->order($this->data_sort)->paginate($list_rows,false,['query'=>request()->get()]);
        }
        return $this->order($this->data_sort)->paginate($list_rows);
    }

    /**
     * getExecTimeAttr 最后执行时间
     * @param $value
     * @return mixed|string
     */
    public function getExecTimeAttr($value)
    {
        if(strtolower($value)<1){
            return '';
        }else{
            return $value;
        }
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
        $where['id']=$id;
        return $this->where($where)->setField($field,$value);
    }

    /**
     * selecData 查询任务
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function selecData(){
       return $this->field('id,name,action,crontab,exec_count,exec_expire,next_time,fail_count')->where('is_enable','=',IS_ENABLE)->order($this->data_sort)->select();
    }

}