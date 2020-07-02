<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/20
 * Time: 12:59
 * 用户日志记录
 */

namespace app\log\controller;

use app\common\controller\Base;
use app\common\controller\SearchForm;
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
		
        $get=$this->request->get();
        if(empty($uid)){
            $search_name_field=['name'=>'name','field_type'=>'select','data'=>$assign['name'],'placeholder'=>lang('log_n_search')];
        }
		if(!empty($uid))
		{
			 $w[]=['uid','=',$uid];
		}elseif(!empty($get['name']) && (isset($assign['name'][$get['name']])))
        {
           $w[]=['uid','=',$get['name']];
        }

        // 时间区间查询
          if(!empty($get['t'])){
              $w[]=$this->dataRangeWhere($get['t']);
          }

        // 查询数据
        $back_login=new LogLogin();
        $assign['uid']=$uid;
        $assign['list']=$back_login->getList($w);

        // 搜索框框
        $search_form=new SearchForm();
        $assign['search']=$search_form->fieldItem([
            ['name'=>'t','field_type'=>'date_range'],
            $search_name_field
        ])->create();
        
        return view('common@/log_login',$assign);
    }

    //TODO 删除
    public function delete($uid=null)
    {
        $id=$this->filterId();
        $log_login=new LogLogin();
        $is_del=$log_login->del($id,$uid);

        if($is_del)
        {
            $this->success(lang('del_success'),url('index'));
        }else
        {
            $this->error(lang('del_fail'));
        }
    }
}