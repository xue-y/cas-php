<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/21
 * Time: 10:03
 * 用户操作记录
 */

namespace app\log\controller;

use app\common\controller\Base;
use app\common\controller\SearchForm;
use app\common\model\AdminAuth;
use app\common\model\AdminUser;
use app\common\model\LogOperate;

class Operate extends Base
{
    /**
     * TODO 列表
     * @param null $uid
     * @return \think\response\View
     */
    public function index($uid=null)
    {
        // 取得所有用户
        $admin_user=new AdminUser();
        $assign['name']=$admin_user->getColumnField();

        // 取得记录操作行为的模块
        $admin_power=new AdminAuth();
        $assign['behavior']=$admin_power->getTopModule();

        // 如果取得搜索条件
        $w=[];
		$search_name_field=[];
		
        $get=$this->request->get();
        if(empty($uid)){
            $search_name_field=['name'=>'name','field_type'=>'select','data'=>$assign['name'],'placeholder'=>lang('log_n_search')];
        }
		if(!empty($uid))
		{
			 $w[] = ['uid', '=', $uid];
		}elseif(!empty($get['name']) && (isset($assign['name'][$get['name']])))
        {
            $w[]=['uid','=',$get['name']];
            $assign['n_search']= $assign['name'][$get['name']];
        }

        // 时间区间查询
        if(!empty($get['t'])){
            $w[]=$this->dataRangeWhere($get['t']);
        }
        // 行为查询
        if(!empty($get['behavior']) && (isset($assign['behavior'][$get['behavior']])))
        {
            $w[]=['behavior','=',$get['behavior']];
        }

        // 查询数据
        $back_operate=new LogOperate();
        $assign['uid']=$uid;

        $search_form=new SearchForm();
        $assign['search']=$search_form->fieldItem([
            ['name'=>'t','field_type'=>'date_range'],
            ['name'=>'behavior','field_type'=>'select','data'=>$assign['behavior'],'placeholder'=>lang('log_behavior_search')],
            $search_name_field
        ])->create();

        $assign['list']=$back_operate->getList($w);
        return view('common@/log_operate',$assign);
    }

    //TODO 删除
    public function delete($uid=null)
    {
        $id=$this->filterId();
        $log_operate=new LogOperate();
        $is_del=$log_operate->id_del($id,$uid);

        if($is_del==true)
        {
            $this->success(lang('del_success'),url('index'));
        }else
        {
            $this->error(lang('del_fail'));
        }
    }
}