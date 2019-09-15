<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/15
 * Time: 16:06
 * 操作记录
 */

namespace app\http\middleware;


use app\common\model\AdminAuth;
use app\common\model\LogOperate;

class Operation
{
    public function handle($request, \Closure $next)
    {
        $record_action=['save','delete']; 
        $response = $next($request);
        $action=$request->action();
        $record=$request->param('record');
        if(in_array($action,$record_action) && ($record==1))
        {
            //模块id
            $admin_power=new AdminAuth();
            $m_id=$admin_power->mc_info($request->module(),'id');
            if(!isset($m_id))
            {
                trace($request->module().'on-existent',config('log.web_level'));
                $m_id=0;
            }else
            {
                $m_id=$m_id['id'];
            }

            // 记录操作
           $back_operate=new LogOperate();
           $nav=$request->param('postion_nav');
           $t=date('Y-m-d H:i:s');
           $operation_id=$request->param('operation_id');
           $details=lang('user').': '.$request->param('user_name').' |  '.$t.' | '.lang($action).$nav['c'].' | id: '.$operation_id;
           $is_add=$back_operate->save([
                'behavior' =>$m_id,
                't' =>$t,
                'uid'=>$request->param('user_id'),
                'details'=>$details
            ]);
            if(!isset($is_add))
            {
                trace(lang('operation_write_fail').$nav['m'].'--'.$details,config('log.web_level'));
            }
        }
        return $response;
    }
}