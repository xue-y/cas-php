<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2020/6/25
 * Time: 11:35
 * 消息队列日志
 */

namespace app\common\model;


use think\Model;

class LogQueue extends Model
{
    protected $name = 'log_queue';

    // 获取列表
    public function getList($w=null)
    {
        $list_rows=config('list_rows');
        if(!empty($w))
        {
            return $this->where($w)->paginate($list_rows,false,['query'=>request()->get()]);
        }
        return $this->paginate($list_rows);
    }
}