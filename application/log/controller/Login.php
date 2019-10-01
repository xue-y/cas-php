<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/20
 * Time: 12:59
 */

namespace app\log\controller;

use app\common\controller\Base;
use app\common\controller\searchForm;
use app\common\model\AdminUser;
use app\common\model\LogLogin;

class Login extends Base
{
    //TODO 列表
    public function index($uid=null)
    {
        // 取得所有用户
        $admin_user=new AdminUser();
        $assign['name']=$admin_user->getColumnField();

       // 如果取得搜索条件
        $w=[];
        $search_name_field=[];
        $post=$this->request->get();
        if(empty($uid)){
            $search_name_field=['name'=>'name','type'=>'select','data'=>$assign['name'],'placeholder'=>lang('log_n_search')];
        }
		if(!empty($uid))
		{
			 $w[]=['uid','=',$uid];
		}elseif(!empty($post['name']) && (isset($assign['name'][$post['name']])))
        {
           $w[]=['uid','=',$post['name']];
        }

        // 时间区间查询
          if(!empty($post['t'])){
              $w[]=$this->dataRangeWhere($post['t']);
          }

        // 查询数据
        $back_login=new LogLogin();
        $assign['uid']=$uid;
        $assign['list']=$list=$back_login->getList($w);

        // 搜索框框
        $search_form=new searchForm();
        $assign['search']=$search_form->fieldItem([
            ['name'=>'t','type'=>'date-range'],
            $search_name_field
        ])->create();

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