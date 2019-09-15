<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/9
 * Time: 16:55
 */

namespace app\back\controller;

use app\common\controller\Base;
use app\common\model\AdminUser;
use app\common\model\SysItem;
use app\common\model\SysType;
use app\admin\validate\AdminUser as VAdminUser;
use think\facade\Cache;

class LockScreen extends Base
{
    public function index()
    {
        // 开启锁屏
        $this->setLock();
        return view('/lock',['name'=>input('param.user_name')]);
    }

    //TODO 解锁
    public function unlock()
    {
        // 收集数据
        $post=input("post.");
        // 验证数据
        if(empty($post))
        {
            $this->error(lang('unlock_error'));
        }
        $validate =new VAdminUser();
        $result = $validate->scene('unlock')->check($post);
        if(!$result){
            $this->error($validate->getError());
        }
        // 取得原数据
        $u_id=request()->user_id;
        $admin_user=new AdminUser();
        $data_pass=$admin_user->getFieldById($u_id,'pass');
        if(empty($data_pass))
        {
            $this->error(lang('unlock_error'));
        }
        //验证密码是否正确
        $new_pass=encry($post['repass']);
        if($data_pass!==$new_pass)
        {
            $this->passErrorNum($u_id);
        }else
        {
            // 清除缓存
            Cache::rm('lockscreen_'.$u_id); // 锁屏次数
            Cache::rm('open_lockscreen_'.$u_id); //锁屏标记
            $this->success('ok',url(get_cas_config('back_default_index')));
        }
    }

    /**
     * TODO 记录登录密码错误次数
     * @param  $id 用户ID
     * @return void
     * */
    public function passErrorNum($u_id)
    {
        $sys_sset=new SysItem();
        $sys_type=new SysType();
        // 记录在缓存文件中
        $error_num=Cache::get('lockscreen_'.$u_id);
        // 锁屏时间
        $type_id=$sys_type->getFieldByName('system','id');
        $lock_t=intval($sys_sset->getValueByName($type_id,'lock_t'));
        if(empty($error_num))
        {
            Cache::set('lockscreen_'.$u_id,1,$lock_t);
        }else
        {
            Cache::inc('lockscreen_'.$u_id);
        }

        // 从配置数据中查询配置项
        $type_id=$sys_type->getFieldByName('system','id');
        $pass_error_num=$sys_sset->getValueByName($type_id,'pass_error_num');
        $lock_t=max(3600,$lock_t)/3600;
        if(($error_num+1)>=$pass_error_num)
        {
            $this->error(lang('unlock_error_max',[$lock_t]),null,null,10);
        }else
        {
            $num=$pass_error_num-($error_num+1);
            $this->error(lang('unlock_residue_degree',[$num]));
        }
    }

    // 设置锁屏
    public function setLock()
    {
        if(!Cache::has('open_lockscreen_'.request()->user_id))
        {
            Cache::set('open_lockscreen_'.request()->user_id,get_cas_config('lock_screen_val'),config('cache.lock_t'));
        }
    }
}