<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2020/6/24
 * Time: 22:40
 * 执行消息队列
 */

namespace app\queue\controller;

use app\common\model\LogQueue;
use think\Controller;
use think\facade\Cache;
use think\Loader;
use think\queue\Job;
use think\facade\Log;

class Execjob extends Controller
{
    public function fire(Job $job, $data)
    {
        // 实例化类
        if(!isset($log_queue)){
            $log_queue=new LogQueue();
        }
        //....这里执行具体的任务
        $action=$data['job_done'];
        $result=call_user_func_array($action,$data['data']);

        // 记录执行次数
        $log_id=$data['log_id'];
        if(!isset($get_log_id)){
            Cache::set('queue_'.$log_id,1);
            $count=1;
        }else{
            $count=Cache::get('queue_'.$log_id);
        }

        if($result)
        {
            $job->delete();
            //日志记录
            $queue_log['stauts']=QUEUE_SUCEESS;
            $queue_log['end_time']=date('Y-m-d H:i:s');
            $queue_log['count']=$count;

            $result=$log_queue->save($queue_log,['id'=>$log_id]);
            if(!$result){
                Log::warning(lang('queue').': ID'.$log_id.', '.$action.lang('queue_write_log_error'));
            }
        }else{
            $delay=config('config.fail_repeat_delay');
            $job->release($delay); //如果执行失败，$delay为延迟时间重新执行
        }

        $fail_exec_number=config('queue.fail_exec_number');
        if ($job->attempts() > $fail_exec_number) {
            //通过这个方法可以检查这个任务已经重试了几次了
            $job->delete();
            //日志记录
            $queue_log['stauts']=QUEUE_FAIL;
            $queue_log['end_time']=date('Y-m-d H:i:s');
            $queue_log['count']=$count;
            $result=$log_queue->save($queue_log,['id'=>$log_id]);
            if(!$result) {
                Log::warning(lang('queue') . ': ID' . $log_id . ', ' . $action . lang('queue_write_log_error'));
            }
        }
    }

    public function failed(Execjob $job)
    {
        // ...任务达到最大重试次数后，失败了
        $job->delete();
    }
}