<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/15
 * Time: 16:21
 */

namespace app\common\model;

use think\Model;

class LogOperate extends Model
{
    /*protected $autoWriteTimestamp = true;
    // 关闭自动写入update_time字段
    protected $updateTime = false;
    protected $createTime = 't';*/

    public function getList($w=null)
    {
        $list_rows=config('list_rows');
        if(!empty($w))
        {
            return $this->where($w)->order('t desc,id desc')->paginate($list_rows,false,['query'=>request()->get()]);
        }
        return $this->order('t desc,id desc')->paginate($list_rows);
    }

    /**根据id 删除
     * @param $uid 指定用户id
     * @param $ids 删除元素的id
     * */
    public function id_del($ids,$uid=null)
    {
        if(empty($uid))
        { // 可以删除所有人的
            return  $this->destroy($ids);
        }else
        {// 只可删除自己的
            $w[]=['uid','=',$uid];
            $w[]=['id','in',$ids];
            // 添加 where 条件 使用 destroy 提示没有此方法
            return $this->where($w)->delete();
        }
    }
}