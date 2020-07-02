<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2020/6/30
 * Time: 18:10
 */

namespace app\common\model;


use think\Model;

class LogCrontab extends Model
{
    protected $name="log_crontab";
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