<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/8/20
 * Time: 21:09
 * 后台基类
 */

namespace app\common\controller;

use think\App;
use think\Controller;

class Base extends Controller
{
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
        // 记录操作记录
        // 取得配置项中模块 记录操作的方法名，如果当前的方法名在配置中
        // 写入操作记录表
        // 操作记录 
        // 行为：控制器；详情：方法名+控制器名称：数据id,多个用英文逗号隔开
        parent::success($msg, $url, $data, $wait = 5,$header);
        exit;
    }

    // 删除带有系统不可删除的数据
    protected function delSysData($id,$sys_id,$module){
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


}