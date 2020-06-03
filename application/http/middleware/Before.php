<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/9
 * Time: 13:43
 */

namespace app\http\middleware;

use app\common\model\AdminAuth;
use app\common\model\AdminRole;
use app\common\model\SysItem;
use app\common\model\SysType;
use think\facade\Env;

class Before
{
    //TODO  检验登录、权限
    public function handle($request, \Closure $next)
    {
        // 当前模块名称
       $current_m=$request->module();
       $code='captcha';

        // 如果访问的是安装模块，并且已经定义了路由代表已经安装过了，跳转登录页面
        if(($current_m =='install') && (!empty($request->routeInfo())))
        {
            return redirect(get_cas_config('login_url')); 
        }
        if(($current_m =='install') &&  empty($request->routeInfo()))
        {
            $request->view_tit=lang("install");
            return $next($request);
        }
        // 访问其他页面判断是否安装
        if($current_m!=='install')
        {
            $route=Env::get('route_path');
            $install_route=$route."install.php";
            if(!file_exists($install_route)){
              return redirect('install/Index/index'); 
            }  
        }

       // 不需要登录的模块
       $pub_module=get_cas_config('pub_module');
       if(in_array($current_m,$pub_module) || $code==$request->path())
       {
           $c=lcfirst($request->controller());
           $request->view_tit=get_cas_config('web_tit').'_'.lang($current_m."_".$c);
           return $next($request);
       }

        // 判断是否登录
        if(empty(session('token')))
        {
            // 清除登录信息
            out_clear_info();
            cookie('user_login_fail',USER_LOGIN_FAIL);
            return redirect(get_cas_config('login_url'));
        }

        $request->r_id=think_crypt('r_id',null,session('token'),false);
        $request->user_id=think_crypt("id",null,session('token'),false);

        // 管理员用户名单独处理
        $request->user_name= get_web_name();

        if(empty($request->user_name) || empty($request->r_id) || empty($request->user_id))
        {
            // 清除登录信息
            out_clear_info($request->user_id);
            cookie('user_login_fail',USER_LOGIN_FAIL);
            return redirect(get_cas_config('login_url'));
        }

        //判断用户是否设置同一账号多个设备登录限制
        $sys_sset=new SysItem();
        $sys_type=new SysType();
        $type_id=$sys_type->getFieldByName('system','id');
        $user_only_sign=$sys_sset->getValueByName($type_id,'user_only_sign');
        // 值为1 ，一个账号允许多设备终端登录；0 禁止
        if($user_only_sign!=get_cas_config('user_only_sign'))
        {
            // 先判断是否有人已经登录了
            $login_user=cache('login_time'.$request->user_id);
            if(!empty($login_user) && ($login_user!=session('token')))
            {
                // 清除登录信息
                out_clear_info($request->user_id);
                cookie('user_down_info',USER_DOWN_INFO);
                return redirect(config('login_url'));
            }
        }

        // 是否锁屏
        $lock=cache('open_lockscreen_'.$request->user_id); // 开启锁屏

        if(!empty($lock) )
        {
            if(!($request->controller()==='LockScreen' && $current_m==='back'))
            return redirect(get_cas_config('lock_screen'));//'back/LockScreen/index'
        }

        $admin_auth=new AdminAuth();
        $curren_c=$request->controller();

        // 判断是不是超级管理员
        $admin_role=new AdminRole();
        $a_id=$admin_role->getFieldById($request->r_id,'a_id');

        // 是否有权限访问
	   if(get_cas_config('admin_auth')!==$a_id)
	   {
            // 取得已经启用的权限id
            $auth_info=$admin_auth->getAuthInfo($current_m,$curren_c);
		
            // 如果 用户访问当前地址 数据库中不存在
            if(empty($auth_info))
            {
                return $this->errorUrl();
            }

            // 取得一条数据
           if((count($auth_info)==1) && (isset($auth_info[""]))){
               $auth_info_data=$auth_info[""];
           }elseif ((count($auth_info)>1) && (isset($auth_info[http_build_query($request->route())]))){
               $auth_info_data=$auth_info[http_build_query($request->route())];
           }else{
               return $this->errorUrl();
           }

            // 判断用户是否有访问权限
            $auth_ids=explode(',',$a_id);
            //如果访问的不是基本权限，并且权限id不在角色权限集合中
            if(($auth_info_data['is_auth']!=IS_AUTH) && (!in_array($auth_info_data['id'],$auth_ids)))
            {
                return $this->errorUrl();
            }
           // 当前控制器
           $postion_nav['c']=$auth_info_data['name'];
        }else{
           // 当前控制器
           $controller_w['module']=$current_m;
           $controller_w['controller']=$curren_c;
		   $curren_a=$request->action();
		   
		   if($curren_a!='index'){
			 // $controller_w['action']=$curren_a;
		   }else{
			  $controller_w['action']=''; 
		   }
		  
           //如果有禁用方法，超级管理员不受限制
           $controller=$admin_auth->getControllerName($controller_w);
	
           if(empty($controller)){
               //return $this->errorUrl();
           }
		   
		    // 先走当前方法名参数, 然后走默认index
			if(isset($controller[$curren_a])){
				$postion_nav['c']=$controller[$curren_a]; 
				$url_action=$curren_a;
			}else{
				$postion_nav['c']=isset($controller[""])?$controller[""]:'';
				$url_action=config('default_action');
			}
			
			// 如果配置url 参数
			/*if(empty($postion_nav['c'])){
               $postion_nav['c']=isset($controller[http_build_query($request->route())])?$controller[http_build_query($request->route())]:'';
			   echo $postion_nav['c'];
			   if(empty($postion_nav['c'])){
				  $postion_nav['c']=isset($controller[$curren_a])?$controller[$curren_a]:''; 
			   }
		    }*/
       }
	 
        // 当前位置 当前模块
        $postion_nav['m']=$admin_auth->getModuleName($current_m);
	    // 当前列表访问地址  当前控制器 模块名 ["controller"] => string(9) "User.test"  多层控制器
        $curren_mc=$current_m.'/'.str_replace('.','/',$curren_c);
		
		$postion_nav['url']=$curren_mc.'/'.$url_action;
	

        // 当前方法
        if(($request->action()=='save') || ($request->action()=='edit')){
             $postion_nav['a']=empty($request->param('id'))?lang('add'):lang('save');
        }else{
            $postion_nav['a']=lang($request->action());  
        }
        $request->postion_nav=$postion_nav;
        $request->view_tit=$postion_nav['m'].'_'.$postion_nav['c'].$postion_nav['a'];

        // 添加中间件执行代码
        return $next($request);
    }

    /* 错误页面跳转*/
    private function errorUrl()
    {
        // 错误提示标识
        cookie('no_access_rights',1);
        return  redirect('error/Error/_empty');
    }
}