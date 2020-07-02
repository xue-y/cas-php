<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2020/6/30
 * Time: 19:43
 */

namespace app\log\controller;

use app\common\controller\Base;
use app\common\controller\SearchForm;
use app\common\model\LogCrontab;
use app\common\model\ToolCrontab;

class Crontab extends Base
{
	    //TODO 列表
    public function index(){

        // 搜索框框
        $search_form=new SearchForm();
        $assign['search']=$search_form->fieldItem([
            ['name'=>'name','placeholder'=>lang('tool_crontab')['name']],
            ['name'=>'stauts','field_type'=>'select','placeholder'=>lang('task_exec_stauts'),'data'=>[lang('fail'),lang('success')]],
            ['name'=>'count','field_type'=>'select','placeholder'=>lang('is_abnormal'),'data'=>[1=>lang('abnormal'),2=>lang('normal')]]
        ])->create();

        // 查询
        $w=[];
        $get=$this->request->get();
        if(!empty($get['name'])){
			// 根据任务名称 查询ID	
			$w[]= ['name', 'like', '%'.$get['name'].'%']; 
        }
        if(isset($get['stauts']) && ($get['stauts']!=='')){
            $w[]=['status','=',$get['stauts']];
        }
        if(isset($get['count'])){
            if($get['count']==1){
                $w[]=['status','=',$get['count']];
            }else if($get['count']>1){
                $w[]=['status','>',1];
            }
        }

        // 列表展示
        $log_crontab=new LogCrontab();
        $assign['list']=$log_crontab->getList($w);

        return view('/log_crontab',$assign);
    }

    //TODO 删除
    public function delete(){
        $id=$this->filterId(false);
        $is_del=LogCrontab::destroy($id);
        if($is_del)
        {
            $this->success(lang('del_success'),url('index'));
        }else
        {
            $this->error(lang('del_fail'));
        }
    }
}