<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/9/30
 * Time: 20:21
 * 搜索表单
 */

namespace app\common\controller;

use think\Controller;

class searchForm extends Controller
{
    // 变量赋值
    private $assign;
    // 搜索允许的字段类型
    private $field_type=['text','email','number','select','date','date_range','time','time_range'];
    // 默认字段类型
    private $default_field_type='text';

    //TODO 提交方式 默认get
    public function method($method='get'){
        $this->assign['method']=$method;
        return $this;
    }

    //TODO 提交地址 默认为空
    public function action($action=''){
        $this->assign['action']=$action;
        return $this;
    }

    // TODO 表单其他html,默认为空
    public function extra($extra=''){
        $this->assign['extra']=$extra;
        return $this;
    }

    /**
     * fieldItem
     * @todo 表单字段元素
     * @param array $field_item  二维数组
     * @return $this
     * 字段属性 一维示例
     * array(7) {
        ["name"] => string(1) "t"
        ["type"] => string(10) "date_range" // 必填
        ["class"] => string(9) "aa search"
        ["placeholder"] => string(12) "时间区间"
        ["extra"] => string(18) "style="color:#eee""
        ["value"] => string(10) "2019-10-10"
        ["data"] => array(0) {     // 数组数据
        }
     }
     */
    public function fieldItem($field_item=[]){
        if(empty($field_item)){
            $this->assign['field_data']=$field_item;
            return $this;
        }
        foreach ($field_item as $k=>$v)
        {
            if(empty($v)){
                unset($field_item[$k]);
                continue;
            }
            if(empty($v['type'])){
                $v['type']=$this->default_field_type;
            }else if(!in_array($v['type'],$this->field_type)){
                unset($field_item[$k]);
                continue;
            }

            $field_item[$k]['class']=isset($v['class'])?$v['class'].' search':'search';
            if($v['type']=='select'){
                if(empty($v['data'])){
                    unset($field_item[$k]);
                    continue;
                }
                $field_item[$k]['placeholder']=empty($v['placeholder'])?lang('select'):$v['placeholder'];
            }
            $field_item[$k]['value']=empty($v['value'])?$this->request->param($v['name']):$v['value'];
        }
        $this->assign['field_data']=$field_item;
        return $this;
    }

    /**
     * buttonItem
     * @todo 搜索按钮
     * @param array $button_item 二维数组
     * @return $this
     * 按钮属性 一维示例
     *  array(3) {
        ["type"] => string(6) "submit"
        ["class"] => string(2) "aa"
        ["value"] => string(6) "搜索"
      }
     */
    public function buttonItem($button_item=[]){
        $this->assign['button_data']=$button_item;
        return $this;
    }

    public function create(){
        return $this->assign;
    }
}