<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/9/15
 * Time: 8:17
 * 表单构建器类
 */

namespace app\common\controller;

use think\Controller;
use think\facade\Env;

class Form extends Base
{
    // 后期完善表单字段
    private $field_type="text,textarea,number,email,url,select,select_multiple,radio,checkbox,checkbox_multiple,date,date_range,dates_range,time,time_range,auto_upload,click_upload,lay_icon,all_icon,simple_editor,all_editor,select_city";
    // 默认字段类型
    private $default_field_type='text';

    // 变量赋值
    private $assign;

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
    public function formExtra($form_extra=''){
        $this->assign['form_extra']=$form_extra;
        return $this;
    }

    /**
     * tabNav
     * @todo 表单tabNav
     * @param array $tab_nav
     * @return $this
     * array(5) {
        ["data"] => array(2) {  //tab 数据 key=>$val 必填
            ["key1"] => string(6) "value1"
            ["key2"] => string(6) "value2"
        }
        ["value"] => string(4) "key1"   // 当前选中的值
        ["class"] => string(0) ""       // tabNav class
        ["extra"] => string(0) ""       // tabNav 额外样式与属性
        ["href"] => string(2) "id"   // href 参数值,默认锚点,可以是数组，数组key要与data key 对应起来
     }
     * array(5) {
        ["data"] => array(2) {
            ["key1"] => string(6) "value1"
            ["key2"] => string(6) "value2"
        }
        ["value"] => string(4) "key1"
        ["class"] => string(0) ""
        ["extra"] => string(0) ""
        ["href"] => array(2) {      数组，数组 key 要与 data 数据 key 对应起来
            ["key1"] => string(5) "href1"
            ["key2"] => string(5) "href2"
        }
    }
     */
    public function tabNav($data=[],$value=0,$class='',$extra='',$href='#'){
        if(empty($data)){
            return $this;
        }
        $this->assign['tab_nav']['data']=$data;
        $this->assign['tab_nav']['value']=$value;
        $this->assign['tab_nav']['class']=$class;
        $this->assign['tab_nav']['extra']=$extra;
        $this->assign['tab_nav']['href']=$href;
        return $this;
    }

    public function token($name='__token__'){
        $this->assign['token']=$name;
        return $this;
    }

    /**
     * hiddenItem
     * @param array $hidden_item 二维数组
     * @return $this
     * 字段属性 一维示例
     * array(3) {
        ["name"] => string(4) "t_id"
        ["value"] => int(1)
     }
     */
    public function hiddenItem($hidden_item=[]){
        $this->assign['hidden_data']=$hidden_item;
        return $this;
    }

    /**
     * fieldItem
     * @todo 表单字段元素
     * @param array $field_item 二维数组
     * @return $this
     * 字段属性 一维示例，extra 变量放在最后，style 是加在元素上的
     * array(7) {
        ["name"] => string(1) "t"
        ["type"] => string(10) "date_range" 
        ["class"] => string(9) "aa search"
        ["placeholder"] => string(12) "时间区间"
        ["extra"] => string(18) "style=color:#eee"
        ["style"] => string(18) "width:100px;height:20px" //
        ["value"] => string(10) "2019-10-10"
        ["data"] => array(0) {     // 数组数据
        }
        ["default_value"]=>string(2)"aa"
        ["select_data"]=array(0){   // 选中的数据
        }
        ["disable_data"]=array(0){  // 禁止选中的数据
        }
     }
     */
    public function fieldItem($field_item=[]){
        if(empty($field_item)){
            $this->assign['field_data']=$field_item;
            return $this;
        }
        $this->field_type=explode(',',$this->field_type);
        foreach ($field_item as $k=>$v)
        {
            if(empty($v)){
                unset($field_item[$k]);
                continue;
            }
            if(!isset($v['value']) && isset($v['default_value'])){
                $field_item[$k]['value']=$v['default_value'];
            }
            if(empty($v['field_type'])){
                $v['field_type']=$this->default_field_type;
            }else if(!in_array($v['field_type'],$this->field_type)){
                 unset($field_item[$k]);
                 continue;
            };
            if(($v['field_type']=='select') || ($v['field_type']=='select_multiple') || ($v['field_type']=='radio') || ($v['field_type']=='radio') || ($v['field_type']=='checkbox') || ($v['field_type']=='checkbox_multiple')){
                if(empty($v['data'])){
                    unset($field_item[$k]);
                    continue;
                }
                $field_item[$k]['placeholder']=empty($v['placeholder'])?lang('select'):$v['placeholder'];
            }
        }

        $field_type=[];// 特殊字段样式处理 使用key 值做下标
        foreach ($field_item as $k=>$v){
            if($v['field_type']=='simple_editor' || $v['field_type']=='all_editor'){
                $field_type['editor']=1;
            }else if($v['field_type']=='click_upload'){
                $field_type['uplaod']='click_upload';
            }else if($v['field_type']=='auto_upload'){
                $field_type['uplaod']='auto_upload';
            }else{
                $field_type[$v['field_type']]=1;
            }
        }

        $this->assign['form']['field_type']=$field_type;// 特殊字段样式处理
        $this->assign['form']['field_data']=$field_item;
        return $this;
    }

    /**
     * buttonItem
     * @todo 表单按钮
     * @param array $button_item 二维数组
     * @return $this
     * 按钮属性 一维示例
     *  array(3) {
            ["type"] => string(6) "submit"
            ["class"] => string(2) "aa"
            ["value"] => string(6) "提交"
        }
     */
    public function buttonItem($button_item=[]){
        $this->assign['form']['button_data']=$button_item;
        return $this;
    }

    // 额外extra_html
    public function extraHtml($extra_html=''){
        $this->assign['extra_html']=$extra_html;
        return $this;
    }

    public function create(){
        return $this->fetch('common@/form',$this->assign);
    }
}