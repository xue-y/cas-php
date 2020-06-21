<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2020/6/18
 * Time: 12:46
 * UpFile 接口基类
 */

namespace app\plugin\controller;

use think\Controller;

class UpFile extends Controller
{
    // editor 上传文件 图片 falsh  视频 文件
    public function editorImg(){
        // 从配置中查询数据
        /*
         *  上传图片类型 根据后缀名判断是否允许类型
         *  上传图片单张大小
         *  上传图片一次最多上传张数
         * */
        /*array(1) {
            ["imgFile"] => array(5) {
                ["name"] => string(7) "111.png"
                ["type"] => string(9) "image/png"
                ["tmp_name"] => string(22) "C:\Windows\php3E9A.tmp"
                ["error"] => int(0)
                ["size"] => int(1260)
         }
        }*/
        dump($_FILES);
    }
}
