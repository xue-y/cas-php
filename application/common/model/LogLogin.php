<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/9
 * Time: 9:59
 */

namespace app\common\model;

use think\Model;

class LogLogin extends Model
{
    /**
     * getList 查看登录记录
     * @param $uid 指定用户id
     * @param $w 过滤条件
     * @return array
     * */
    public function getList($w=null)
    {
        $list_rows=get_cas_config('list_rows');
        if(!empty($w))
        {
            return $this->where($w)->order('t desc,id desc')->paginate($list_rows,false,['query'=>request()->get()]);
        }
        return $this->order('t desc,id desc')->paginate($list_rows);
    }

    /**
     * del  根据id 删除记录
     * @param $uid 指定用户id
     * @param $ids array 删除的id
     * @return bool
     * */
    public function del($ids,$uid=null)
    {
        if(empty($uid))
        { // 可以删除所有人的
            return $this->destroy($ids);
        }else
        {// 只可删除自己的
            $w[]=['uid','=',$uid];
            $w[]=['id','in',$ids];
            // 添加 where 条件 使用 destroy 提示没有此方法
            return $this->where($w)->delete();
        }
    }

}