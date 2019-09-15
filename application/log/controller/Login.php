<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/20
 * Time: 12:59
 */

namespace app\log\controller;

use app\common\model\AdminUser;
use app\common\model\LogLogin;
use think\Controller;

class Login extends Controller
{
    //TODO 列表
    public function index($uid=null)
    {
        // 取得所有用户
        $admin_user=new AdminUser();
        $assign['name']=$admin_user->getColumnField();

        // 搜索默认值
        $assign["start_t"]=lang('log_start_t');
        $assign["end_t"]=lang('log_end_t');
        $assign['n_search']=lang('log_n_search');

       // 如果取得搜索条件
        $w=[];
        $post=$this->request->get();
		if(!empty($uid))
		{
			 $w[]=['uid','=',$uid];
		}elseif(!empty($post['name']) && (isset($assign['name'][$post['name']])))
        {
           $w[]=['uid','=',$post['name']];
           $assign['n_search']= $assign['name'][$post['name']];
        }
        $t=[];
        if(!empty($post['start']))
        {
            $is_t=strtotime($post['start']);
            if($is_t!=false)
            {
                $t['start']=$post['start'];
            }
        }
        if(!empty($post['end']))
        {
            $is_t=strtotime($post['end']);
            if($is_t!=false)
            {
                $t['end']=$post['end'];
            }
        }

        if(!empty($t))
        {
            if(isset($t['start']) && isset($t['end']) && ($t['start']!=$t['end']))
            {
                $t_arr=[$t['start'],$t['end']];
                $w[]=['t','between time',$t_arr];
                $assign["start_t"]=$t['start'];
                $assign["end_t"]=$t['end'];
            }elseif (isset($t['start']))
            {
                $w[]=['t','>= time',$t['start']];
                $assign["start_t"]=$t['start'];
            }elseif (isset($t['end']))
            {
                $w[]=['t','<= time',$t['end']];
                $assign["end_t"]=$t['end'];
            }
        }

        // 查询数据
        $back_login=new LogLogin();
        $assign['uid']=$uid;
        $assign['list']=$list=$back_login->getList($w);
    
        return view('common@/log_login',$assign);
    }

    //TODO 删除
    public function delete($uid=null)
    {
        $id=input('param.id');
        if(empty($id))
        {
            $this->error(lang('error_id'));
        }

        if(is_array($id)){
            $id=array_unique(array_filter($id));
            if(empty($id)){
                $this->error(lang('error_id'));
            }  
        }
        
        $back_login=new LogLogin();
        $is_del=$back_login->del($id,$uid);

        if($is_del==true)
        {
            $this->success(lang('del_success'),url('index'));
        }else
        {
            $this->error(lang('del_fail'));
        }
    }
}