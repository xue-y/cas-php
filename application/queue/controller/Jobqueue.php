<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2020/6/24
 * Time: 22:38
 * 消息队列
 */

namespace app\queue\controller;

use app\common\model\LogQueue;
use think\Controller;
use think\Queue;

class Jobqueue extends Controller
{
    /**
     * index
     * @param $job_done 执行方法名
     * @param $data 执行方法的参数数据
     * @param $delay 消息队列延迟执行时间
     */
    public function index($job_done,$data=[],$delay=null,$queue_name=null){

        // 快速获取到 消息日志ID
        $log_queue=new LogQueue();
        $queue_log['start_time']=date('Y-m-d H:i:s');
        $queue_log['name']=isset($queue_name)?$queue_name:'';
        $queue_log['action']=$job_done;

        $queue_id=$log_queue->insertGetId($queue_log);
        if(!$queue_id){
            $this->error(lang('queue_send_fail'));
        }

        //创建任务： 项目命名空间\控制器@方法
         $job_name = "app\queue\controller\Execjob";

        $job_data['data']=$data;
        $job_data['job_done']=$job_done; // 执行消息任务的方法名
        $job_data['log_id']=$queue_id;

        // 如果立即发送
        if(!$delay){
            $result = Queue::push($job_name, $job_data, $job_done);
        }else{
            //如果延时发送
			$result = Queue::later($delay, $job_name, $job_data, $job_done);
		}
        //dump($result);
        /*if ($result!==false) {
            // 消息列队执行成功
            $queue_log['stauts']=QUEUE_SUCEESS;
        } else {
            // '添加队列出错';
            $queue_log['stauts']=QUEUE_FAIL;
        }*/
        // 当前页面为同步页面
    }
}