<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/8/20
 * Time: 21:09
 * 后台基类
 */

namespace app\common\controller;

use app\common\model\LogOperate;
use think\Controller;

class Base extends Controller
{
    private $t_range=' - ';

    protected function error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        // 排除不生成token的方法
        $exclude_action=['delete','index','ajaxOperate','authSave'];
        $current_action=$this->request->action(true);
        if($this->request->isAjax() && (!in_array($current_action,$exclude_action))){
            // ajax 提交 生成新的token;
            $token=request()->token();
            if(empty($data)){
                $data=$token;
            }else{
                $data['token']=$token;
            }
        }
        parent::error($msg, $url, $data, $wait = 5,$header);
        exit;
    }

    // 重构success
    protected function success($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        // 判断操作记录是否启用
        // 判断当前访问的是否在配置的操作模块中，是否在操作方法中
        $operate_module=get_cas_config('operate_module');
        $current_m=$this->request->module();
        if(!empty($operate_module) && in_array($current_m,$operate_module)){
             $operate_action=get_cas_config('operate_action');
              $current_a=$this->request->action(true);
             if(!empty($operate_action) && in_array($current_a,$operate_action)){
                // 操作记录数据
                $operate_data['uid']=$this->request->user_id;
                $operate_data['behavior']=$current_m;
                $operate_data['t']=date('Y-m-d H:i:s',time());
                //详情：方法名+控制器名称：数据id,多个用英文逗号隔开
                $id=$this->request->param('id');
                if(!empty($id) && is_array($id)){
                    $id=implode(',',$this->request->param('id'));
                }
                $id=': '.$id;
                $operate_data['details']=$this->request->postion_nav['a'].$this->request->postion_nav['m'].$id;
                // 写入操作记录表
                 $log_operate=new LogOperate();
                 $is_save=$log_operate->allowField(true)->save($operate_data);
                 if($is_save!==true)
                 {
                     trace($this->request->user_id.': '.$this->request->user_name.'用户操作记录写入失败'.print_r($operate_data,true),'log_operate');
                 }
             }
        }
        parent::success($msg, $url,  $this->request->param('id'), $wait = 5,$header);
        exit;
    }

    // 删除带有系统不可删除的数据
    protected function delSysData($id,$sys_id,$module)
    {
        if(!is_array($id) && ($id==$sys_id))
        {
            if($this->request->isAjax()){
                $this->error(lang('del_sys'));
            }else{
                return  redirect('index');
            }
        }
        if(is_array($id))
        {
            $id=array_unique(array_filter($id));
            $key = array_search($sys_id, $id);
            if ($key !== false)
                array_splice($id, $key, 1);

            if(empty($id))
            {
                if($this->request->isAjax()){
                    $this->error(lang('del_sys'));
                }else{
                    return  redirect('index');
                }
            }
            $id=implode(',',$id);
        }

        $is_del=$module->where('id','in',$id)->delete();
        if($is_del<1)
        {
            $this->error(lang('delete_fail'));
        }else
        {
            $this->success(lang('del_success'),url('index'));
        }
    }

    // 过滤ID
    protected function filterId()
    {
        $id=input('param.id');
        if(empty($id))
        {
            $this->error(lang('error_id'));
        }
        if(is_array($id))
        {
            $id=implode(",",array_unique(array_filter($id)));
        }
        return $id;
    }

    /**
     * formItem
     * @todo 表单元素调用
     * @param string $template 模板名称
     * @param array $data 模板数据
     * @return mixed
     */
    public function formItem($template,$data)
    {
        $this->view->engine->layout(false);
        $template='common@/field/'.$template;
        return $this->fetch($template,$data);
    }

    /**
     * formItem
     * @todo 页面调用
     * @param string $template 模板名称
     * @param array $data 模板数据
     * @return mixed
     */
    public function includePage($template,$data)
    {
        $this->view->engine->layout(false);
        $template='common@/'.$template;
        return $this->fetch($template,$data);
    }

    /**
     * dataRangeWhere
     * @todo 时间区间查询
     * @param string $t_range 时间日期字符串
     * @return array
     */
    public function dataRangeWhere($t_range)
    {
        $data=explode($this->t_range,$t_range);
        $t['start']=$data[0];
        $t['end']=$data[1];
        if(isset($t['start']) && isset($t['end']) && ($t['start']!=$t['end']))
        {
            $t_arr=[$t['start'],$t['end']];
            $w=['t','between time',$t_arr];
        }elseif (isset($t['start']))
        {
            $w=['t','>= time',$t['start']];
        }elseif (isset($t['end']))
        {
            $w=['t','<= time',$t['end']];
        }
        return $w;
    }

}