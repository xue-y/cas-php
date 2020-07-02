<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2020/7/2
 * Time: 13:43
 * 字段上传，单独上传按钮
 */

namespace app\plugin\controller;

use app\common\controller\Base;
use think\Controller;
use upload\file\Local;
use app\common\model\SysItem;

class FieldUpfile extends Base
{
    private $config=[];

    /**
     * upLocal 上传图片
     */
    public function upLocal(){
        $this->config=$this->getConfig();
        $local=new Local($this->config);
        $local->exceUpload();
    }

    public function delete(){
        $local=new Local();
        $local->delFile();
    }

    // 获取配置项
    public function getConfig(){
        $sys_item=new SysItem();
        $config=$sys_item->getTypeSettings(3,'name','val');
        /**
         * 配置项加载说明
         *      加载库配置
         *      加载当前模块下配置
         *      加载数据库用户自定义配置
         * 后加载的配置会覆盖先加载的，后加载的优先级更高
         */
        $config=array_merge(config('fieldupfile.local'),$config);
        if($this->request->isAjax()){
            if(empty($config['up_file_url'])){
                $this->ajaxResult(0,lang('file_up_url_empty'));
            }else if(empty($config['del_file_url'])){
                $this->ajaxResult(0,lang('file_del_url_empty'));
            } else{
                $this->ajaxResult(1,'',$config);
            }
        }else{
            return $config;
        }
    }
}