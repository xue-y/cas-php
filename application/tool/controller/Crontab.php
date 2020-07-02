<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2020/6/26
 * Time: 0:03
 * 定时任务管理页面
 */

namespace app\tool\controller;

use app\common\controller\Base;
use app\common\model\LogCrontab;
use app\common\model\ToolCrontab;
use Cron\CronExpression;
use think\Exception;
use think\facade\Log;
use think\facade\Validate;
use app\common\controller\SearchForm;

class Crontab extends Base
{

    //TODO index 列表
    public function index(){
        // 如果数据库一致
        $exec_status_data=[
            lang('fail'),
            lang('success'),
        ];
        // 搜索
        $search_form=new SearchForm();
        $assign['search']=$search_form->fieldItem([
            ['name'=>'name_action','placeholder'=>lang('tool_crontab')['name'].'/'.lang('tool_crontab')['action']],
            ['name'=>'exec_status','field_type'=>'select','placeholder'=>lang('tool_crontab')['exec_status'],'data'=>$exec_status_data],
            ['name'=>'task_status','field_type'=>'select','placeholder'=>lang('tool_crontab')['task_status'],'data'=>[lang('noexec'),lang('exec')]],
            /*['name'=>'exec_count','placeholder'=>lang('tool_crontab')['exec_count']],*/
            ['name'=>'is_enable','field_type'=>'select','placeholder'=>lang('is_enable_text'),'data'=>[lang('is_disable'),lang('is_enable')]]
        ])->create();

        // 查询
        $get=$this->request->get();
        $w=[];
        if(!empty($get['name_action'])){
            $w[]= ['action|name', 'like', '%'.$get['name_action'].'%'];
        }
        if(isset($get['is_enable']) && ($get['is_enable']!=='')){
            $w[]=['is_enable','=',$get['is_enable']];
        }
        if(isset($get['exec_status']) && ($get['exec_status']!=='')){
            $w[]=['exec_status','=',$get['exec_status']];
        }
        if(isset($get['task_status']) && ($get['task_status']!=='')){
            $w[]=['task_status','=',$get['task_status']];
        }

        $tool_crontab=new ToolCrontab();
        $assign['list']=$tool_crontab->getList($w);
        $this->assign($assign);
        return $this->fetch();
    }

    //TODO edit 添加、修改
    public function edit(){
        $id=input('id/d',0);
        if(!empty($id))
        {
            $tool_crontab=new ToolCrontab();
            $data=$tool_crontab->find($id);
            $exec_expire['value']=$data['exec_expire'];
            $this->assign('data',$data);
        }
        $exec_expire['data']=['0'=>lang('tool_crontab')['no_exec'],'1'=>lang('tool_crontab')['exec']];
        $exec_expire['name']='exec_expire';
        $this->assign('exec_expire',$exec_expire);
        return $this->fetch();
    }

    // save 执行页面
    public function save(){
        $post=$this->request->post();
        // 验证数据
        $message = [
            'name' =>lang('tool_crontab')['name_vail'],
            'action'=>lang('tool_crontab')['action_vail'],
            '__token__'=>lang('form_token')
        ];
        $validate = Validate::make([
            'name' => 'require|length:2,20',
            'action' => 'require|length:5,50|regex:/^[a-zA-Z_\.\/]+$/',
            '__token__'=>'require|token'
        ],$message);

        if (!$validate->check($post)) {
            $this->error($validate->getError());
        }
        $is_vail=$this->checkCrontabFormat($post['crontab']);
        if(!$is_vail){
            $this->error(lang('tool_crontab')['crontab_format_error']);
        }
        $post['next_time']=$this->getNextExecDate($post['crontab']);

        $tool_crontab=new ToolCrontab();
        // 数据处理
        if(empty($post['id'])){
            $msg_fail=lang('add_fail');
            $msg_success=lang('add_success');
            $result=$tool_crontab->save($post);
        }else{
            $msg_fail=lang('edit_fail');
            $msg_success=lang('edit_success');
            $id=$post['id'];
            unset($post['id']);
            $result=$tool_crontab->save($post,['id'=>$id]);
        }
        if($result){
            $this->success($msg_success,'index');
        }else{
            $this->error($msg_fail);
        }
    }

    // delete 删除
    public function delete(){
        $id=$this->filterId();
        $is_del=ToolCrontab::destroy($id);
        if($is_del) {
            $this->success(lang('del_success'),url('index'));
        }else {
            $this->error(lang('del_fail'));
        }
    }

    //TODO ajaxOperate 执行更新单个字段
    public function ajaxOperate()
    {
        $param=$this->request->param();
        if(empty($param['id']) || (intval($param['id'])<1))
        {
            $this->error(lang('error_id'),null,$param);
        }
        $id=intval($param['id']);
        $tool_crontab=new ToolCrontab();
        $result=$tool_crontab->updateField($id,'is_enable',$param['is_enable']);
        if($result)
        {
            $this->success(lang('is_enable_success'));
        }else
        {
            $this->error(lang('is_enable_fail'));
        }
    }

    /**
     * checkCrontabFormat 验证cron格式
     * @param $crontab
     * @return bool
     */
    private function checkCrontabFormat($crontab){
        if (CronExpression::isValidExpression($crontab)) {
             return true;
        } else {
            return false;
        }
    }

    /**
     * ajaxCheckCrontabFormat 前端验证crontab 格式
     * @return json bootstrapValidator ajax 限制指定格式
     */
    public function ajaxCheckCrontabFormat(){
        $crontab=$this->request->param('crontab');
        if(CronExpression::isValidExpression($crontab)){
            $data['valid']=true;
        }else{
            $data['valid']=false;
        }
        echo json_encode($data);
        exit;
    }

    /**
     * getNextExecDate 获取下次执行时间
     * @param $crontab
     * @return string
     */
    public function getNextExecDate($crontab=null,$current_time=null){
        if(!$crontab)return false;
        $cron = CronExpression::factory($crontab);
        return $cron->getNextRunDate($current_time, 0)->format('Y-m-d H:i');
    }

    // 执行任务
    public function execCrontab(){

        // 设置永不超时
        set_time_limit(0);

        /**
         * 开始执行任务创建一个锁机制（创建一个锁文件），防止用户每分钟执行一次任务，当前定时任务没有完成
         * 不建议放在public目录，维护人员有时会误删除
         */
        $lock_file='../backups/crontab_lock.txt';
        if(is_file($lock_file)){
            Log::notice(lang('crontab_exec')['now_crontab_no_complete']);
            return;
        }

        //查询任务表，启用状态
        $tool_crontab=new ToolCrontab();
        $crontab=$tool_crontab->selecData();
        if(empty($crontab))return;

        // 创建锁文件
        file_put_contents($lock_file,'');
        if(!is_file($lock_file)){
            Log::error(lang('crontab_exec')['create_lock_file_fail']);
            return;
        }

        // 开始执行任务
        foreach ($crontab as $v){
            //记录当前任务开始执行时间
            if(empty($v['next_time']) || empty($v['action']))continue;
            $start_time_stamp=time();
            $curren_time=strtotime(date('Y-m-d H:i',$start_time_stamp));
            $interval_time=$curren_time-strtotime($v['next_time']);

            // 如果已经过期
            if($interval_time>0){
                // 如果过期不执行任务
                if($v['exec_expire']!=CRONTAB_EXEC){
                    // 记录当前任务状态
                    $this->noExecCrontab(EXPIRE,$tool_crontab,$v);
                    continue;// 跳出循环
                }else{
                    try{
						$crontab['expire']=EXPIRE;
                        $run_data=$this->run($v['action'],$v['fail_count']);
                    }catch (Exception $e){
                        $this->noExecCrontab(EXPIRE,$tool_crontab,$v);
                        Log::error($e->getMessage());
                        continue;
                    }
                }
            }
            else if($interval_time===0){
                // 执行任务
                try{
					$crontab['expire']=NO_EXPIRE;
                    $run_data=$this->run($v['action'],$v['fail_count']);
                }catch (Exception $e){
                    $next_time=strtotime($v['next_time'])+30;
                    $next_time=date("Y-m-d H:i",$next_time);
                    $this->noExecCrontab(NO_EXPIRE,$tool_crontab,$v,$next_time);
                    Log::error($e->getMessage());
                    continue;
                }
            }

            if(isset($run_data)){
                // 写入日志数据表
                $end_time_stamp=time();
                $end_time=date('Y-m-d H:i:s',$end_time_stamp);
                $log_crontab_data['crontab_id']=$v['id'];
				$log_crontab_data['name']=$v['name'];
                $log_crontab_data['start_time']=date('Y-m-d H:i:s',$start_time_stamp);
                $log_crontab_data['end_time']=$end_time;
                if(!empty($run_data['crontab_log_count'])){
                    $log_crontab_data['count']=$run_data['crontab_log_count'];
                }
                $log_crontab_data['status']=$run_data['exec_status'];
                $log_crontab=new LogCrontab();
                $is_insert=$log_crontab->save($log_crontab_data);
                if(!$is_insert){
                    Log::warning($v['id'].': '.lang('write_log_fail'));
                }

                // 更新任务表
                $crontab['exec_time']=$end_time;
                $crontab['exec_status']=$run_data['exec_status'];
                $crontab['exec_count']=$v['exec_count']+1;
                $crontab['task_status']=CRONTAB_IS_EXEC;
                // 如果执行过程不超过1分钟，下次执行会与最后一次执行时间相同
                // 在执行完成后加一个数，设置下次执行时间
                $run_time_stamp=$end_time_stamp-$start_time_stamp;
                if(($run_time_stamp)>0){
                    $next_time_stamp=$end_time_stamp;
                }else{
                    $next_time_stamp=$end_time_stamp+60;
                }
                $next_time=date("Y-m-d H:i",$next_time_stamp);
                $crontab['next_time']=$this->getNextExecDate($v['crontab'],$next_time);
                $is_update=$tool_crontab->allowField(true)->save($crontab,['id'=>$v['id']]);
                if(!$is_update){
                    Log::notice($v['id'].lang('crontab_exec')['update_crontab_status_fail']);
                }
            }
        }

        // 执行完任务，删除锁文件
        if(!@unlink($lock_file)){
            Log::error(lang('create_complete_del_fail'));
        }
    }

    /**
     * noExecCrontab 未执行的记录任务表
     * @param $tool_crontab object
     * @param $value array
     */
    private function noExecCrontab($expire,$tool_crontab,$value,$next_time=null){
        $data['exec_status']=FAIL;
        $data['task_status']=CRONTAB_NO_EXEC;
        $data['next_time']=$this->getNextExecDate($value['crontab'],$next_time);
		$data['expire']=$expire;
        $is_update=$tool_crontab->allowField(true)->save($data,['id'=>$value['id']]);
        if(!$is_update){
            Log::warning($value['id'].lang('crontab_exec')['update_crontab_status_fail']);
        }
    }

    /**
     * run 执行任务方法,如果已经过期执行任务只分为成功和失败
     * @param $action
     * @param $fail_count
     * @return mixed
     */
    private function run($action,$fail_count){
        $result=(bool)action($action);
        if($result){
            // 任务表
            $crontab['exec_status']=SUCEESS;
        }else{
            // 任务表
            $crontab['exec_status']=FAIL;
            // 尝试执行次数
            if($fail_count>0){
                for($i=1;$i<=$fail_count;$i++){
                    $results=(bool)action($action);
                    if($results){
                        $crontab['crontab_log_count']=$i;
                        $crontab['exec_status']=SUCEESS;
                        break;
                    }
                }// 循环结束
            }
        }
        return $crontab;
    }
}