<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/18
 * Time: 9:51
 * 权限操作
 */

namespace app\admin\controller;

use app\common\controller\Base;
use app\common\model\AdminAuth;
use app\admin\validate\AdminAuth as VAdminAuth;
use think\Db;
use Tree;

class Auth extends Base
{
    //TODO 列表
    public function index()
    {
        $admin_auth=new AdminAuth();
        $list=$admin_auth->getList();
        $tree=new Tree();
        $list=$tree->getTree($list);
        $list=$tree->getTreeTable($list);
        $this->assign('list',$list);
        return $this->fetch();
    }

    //TODO 进入添加修改页面
    public function edit()
    {
        $id=input('param.id/d');
        $pid=input('param.pid/d');
        $admin_auth=new AdminAuth();
        $disabled=null;
        if(!empty($id))
        {
            // 查询当前数据
            $data=$admin_auth->getDataById($id)->getData();
            if(empty($data))
            {
                $this->error(lang('error_id'));
            }
            $data['id']=$id;
            $pid=$data['pid'];
            $disabled=$id;
            $assign['data']=$data;
        }

        /*$table_all = Db::query("show tables");
        $d = config('database.database');
        $s = 'Tables_in_' . $d;*/
       // $assign['relation']=
        $auth_list=$admin_auth->getList();
        $tree=new Tree();
        $auth_list=$tree->getTree($auth_list);
        $assign['auth_list']=$tree->getTreeSelect($auth_list,$pid,$disabled,'id','name');
        $this->assign($assign);
        return $this->fetch();
    }

    // TODO 执行添加/修改
    public function save()
    {
        // 获取数据并验证
        $data=request()->post();

        if(empty($data))
        {
            $this->error(lang('error_page'));
        }
        $validate = new VAdminAuth();
        // 有时提示表单令牌失效，但数据可以修改成功,页面直接跳转到列表
        session_cache_limiter('private');
        $result = $validate->check($data);
        if(!$result)
        {
            $this->error($validate->getError());
        }
   
        $admin_auth=new AdminAuth();
        // 判断模块控制器是否唯一
        $is_unique=$admin_auth->checkUrlUnique($data);
        if($is_unique>0)
        {
            $this->error(lang('auth_url_unique'));
        }
		
        if(empty($data["id"]))
        {
            if(isset($data["id"]))unset($data["id"]);
            $is_save=$admin_auth->allowField(true)->save($data);
            $msg_fail=lang('add_fail');
            $msg_success=lang('add_success');
            if($is_save)
            {
                $this->success($msg_success,url('index'));
            }else
            {
                $this->error($msg_fail);
            }
        }else
        {
            $msg_fail=lang('edit_fail');
            $msg_success=lang('edit_success');
            // 取得原数据
            $old_data=$admin_auth->field('is_menu,relation')->find($data["id"]);

            // 如果更新了菜单并且存在关联表,
            Db::startTrans();
            if(($old_data['is_menu']!=$data['is_menu']) && !empty($old_data['relation']) && ($old_data['relation']==$data['relation'])){
                $is_update=Db::name($old_data['relation'])->where('a_id','=',$data["id"])->setField('is_menu',$data['is_menu']);
                if(!$is_update){
                    Db::rollback();
                    $this->error($msg_fail);
                }
            }

            // 如果取消了关联,菜单更新了 relation
            if(!empty($old_data['relation']) && ($old_data['relation']!=$data['relation'])){
                $relation_updata['is_menu']=0;
                $relation_updata['a_id']=0;
                $is_update=Db::name($old_data['relation'])->where('a_id','=',$data["id"])->update($relation_updata);
                if(!$is_update){
                    Db::rollback();
                    $this->error($msg_fail);
                }
            }

            $is_save=$admin_auth->allowField(true)->save($data,['id'=>$data["id"]]);
            if($is_save){
                Db::commit();
                $this->success($msg_success,url('index'));
            }else{
                Db::rollback();
                $this->error($msg_fail);
            }
        }
    }

    //TODO 排序
    public function sort()
    {
        $post=request()->post('sort');
        if(empty($post))
        {
            $this->redirect("index");
        }
        $post=array_filter($post);

        $admin_auth=new AdminAuth();
        $is_sort=$admin_auth->updateSort($post);
        if(gettype($is_sort)!="integer")
        {
            $this->error(lang('update_sort_fail'));
        }else
        {
            $this->success(lang('update_sort_success'));
        }
    }

    //TODO 删除
    public function delete()
    {
        $id=$this->filterId();
        // 删除数据
        $admin_auth=new AdminAuth();
        $is_del=$admin_auth->delData($id);
        if(!$is_del)
        {
            $this->error(lang('delete_fail'));
        }else
        {
            $this->success(lang('del_success'),url('index'),$is_del);
        }

    }

    //TODO ajax 执行更新单个字段
    public function ajaxOperate()
    {
        $param=$this->request->param();
        if(empty($param['id']) || (intval($param['id'])<1))
        {
            $this->error(lang('error_id'));
        }
        $id=intval($param['id']);
        $admin_auth=new AdminAuth();
        Db::startTrans();
        switch ($param)
        {
            case isset($param['is_menu']):
                $type='is_menu';
                $result=$admin_auth->updateField($id,'is_menu',$param['is_menu']);
                if(!$result){
                    Db::rollback();
                    $this->error(lang($type.'_fail'));
                }
                // 如果当前菜单关联其他模型同步处理
                $relation=$admin_auth->where('id','=',$id)->value('relation');
                if(!empty($relation)){
                    // 这个地方使用 db 助手函数执行失败
                    $is_update=Db::name($relation)->where('a_id','=',$id)->setField('is_menu',$param['is_menu']);
                    if(!$is_update){
                        Db::rollback();
                        $this->error(lang($type.'_fail'));
                    }
                }
                break;
            case isset($param['is_enable']):
                $result=$admin_auth->updateField($id,'is_enable',$param['is_enable']);
                $type='is_enable';
                break;
            default:
                $this->error(lang('error_param'));
        }
        if($result)
        {
            Db::commit();
            $this->success(lang($type.'_success'));
        }else
        {
            Db::rollback();
            $this->error(lang($type.'_fail'));
        }
    }


}