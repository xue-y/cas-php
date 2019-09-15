<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/9
 * Time: 10:00
 */

namespace app\common\model;

use think\Model;

class AdminAuth extends Model
{
    /**
     * getMenu  menu
     * 取得权限,这里只取 2 层 --- 后台系统左菜单
     * @param string|array $a_id 默认null ,如果传权限id，根据id 查询
     * @return array
     * */
    public function getMenu($a_id=null)
    {
        $where['is_menu']=IS_MENU;
        $where['is_enable']=IS_ENABLE;
        if(!empty($a_id))
        {
            $where['id']=['in',$a_id];
            $menu=$this->getComMenu()+$this->where($where)->field('id,name,module,controller,action,param,pid,icon')->order('sort asc,id asc')->select()->toArray();
        }else
        {
            $menu=$this->where($where)->field('id,name,module,controller,action,param,pid,icon')->order('sort asc,id asc')->select()->toArray();
        }
        return $menu;
    }

    /**
     * TODO 取得用户登录即可有的权限菜单
     * userComMenu   user_com_modular
     * @return 模块下所有的权限数据
     * */
    public function getComMenu()
    {
        $where['is_menu']=IS_MENU;
        $where['is_enable']=IS_ENABLE;
        $where['is_auth']=IS_AUTH;
        return $this->where($where)->field('id,name,module,controller,action,param,pid,icon')->order('sort asc,id asc')->select()->toArray();
    }

    /**
     * getAuthInfo
     * @todo 根据控制器模块名取得已启用的权限信息
     * @param $module 模块名称
     * @param $controller 控制器名称
     * @return array|null 返回权限信息
     */
    public function getAuthInfo($module,$controller)
    {
        $where['is_enable']=IS_ENABLE;
        $where['module']=$module;
        $where['controller']=$controller;
        return $this->where($where)->find();
    }

    /**
     * 判断控制是否启用
     * @param  [type]  $name [description]
     * @return boolean       [description]
     */
    public function isEnable($module,$controller)
    {
        $where=[
            ['module','=',$module],
            ['controller','=',$controller]
        ];
        return $this->where($where)->value('is_enable');
    }

    // 权限列表
    public function getList($where=[],$except_field='icon')
    {
        if(empty($where)){
            return $this->field($except_field,true)->order('sort asc,id asc')->select()->toArray();
        }else{
            return $this->field($except_field,true)->order('sort asc,id asc')->where($where)->select()->toArray();
        }
    }

    // 权限分配
    public function geAuthList(){
        return $this->field('id,pid,name')->where('is_auth','<>',IS_AUTH)->order('sort asc,id asc')->select()->toArray();
    }

    // 控制器
    public function setControllerAttr($value)
    {
        return ucfirst($value);
    }
    

    // 根据ID删除数据
    public function delData($id)
    {
        // 排除系统内置的id
        $id_arr=$this->where([['is_sys','<>',IS_SYS],['id','in',$id]])->column('id');
        if(empty($id_arr)) return false;
        $is_del=$this->destroy($id_arr);
        if($is_del){
            return $id_arr;
        }else{
            return false;
        }
    }

    // 字体图标
    public function getIconAttr($value){
        if(preg_match('/^fa-/',$value)){
            return 'fa '.$value;
        }else if(preg_match('/^glyphicon-/',$value)){
            return 'glyphicon '.$value;
        }else if(preg_match('/^layui-icon-/',$value)){
            return 'layui-icon '.$value;
        }else{
            return $value;
        }
    }

    /**
     * toggleField
     * @todo 更新单个字段
     * @param $id 数据ID
     * @param $field 更新字段
     * @param $value 需要更新的值
     * @return int
     */
    public function updateField($id,$field,$value)
    {
        $where=[
            ['id','=',$id]
        ];
        if($field=='is_enable')
        {
            // 内置不可修改为禁用
            $where[]=['is_sys','<>',IS_SYS];
        }
        return $this->where($where)->setField($field,$value);
    }


    // 根据id 取得数据-- 修改
    public function getDataById($id)
    {
        return $this->field('id,is_sys',true)->find($id);
    }

    // 根据id 查询字段
    public function id_field($id,$field)
    {
        $data=$this->field($field)->find($id);
        return $data[$field];
    }

    /**
     * @todo 判断name 在数据表中是否唯一
     * @param $name 需要验证的值
     * @param $id 排除主键ID默认null
     * @return int 查询个数
     * */
    public function checkUrlUnique($module,$controller,$id=null)
    {
        $where=[
            ['module','=',$module],
            ['controller','=',$controller],
        ];
        if(!empty($id))
        {
            $where[]=['id','<>',$id];
            $c=$this->where($where)->count();
        }else
        {
            $c=$this->where($where)->count();
        }
        return $c;
    }

    //TODO 更新排序key=id val=sort_val
    public function updateSort($data)
    {
        $prefix=config('database.prefix');
        $id=implode(",",array_keys($data));
        $where="";
        foreach ($data as $k=>$v)
        {
            $where.=" WHEN ".$k. " THEN ".$v;
        }
        $sql="UPDATE `{$prefix}admin_auth` 
            SET sort = CASE id 
                $where
            END
            WHERE id IN ($id)";

        //如果数据非法或者查询错误则返回 false ，否则返回影响的记录数
        return $this->execute($sql);
    }


    /** 
     * @todo  获取顶级菜单模块
     * @return array
     * */
    public function getTopModule($filed='name')
    {
        $operate_module=config('operate_module');
        return $this->where([['pid','=','0'],['name','in',$operate_module]])->column($filed,'id');
    }

    /**
     * 取得可以分配的权限
     * @return array
     * */
    public function role_power()
    {
        // 排除公共默认权限 --- 排除不分配的权限
        $com_modular=config('user_com_modular');
        $except_module=config('except_module');

         array_push($except_module,$com_modular);
         $id=$this->mc_id($except_module);
         return $this->field('biaoshi_name as n,id,pid')->whereNotIn('id',$id)->select();
    }

    // 根据模块名称取得id
    public function mc_id($name)
    {
        if(is_array($name))
        {
        $id=$this->where('name','in',$name)->field('id')->select();
        }else
        {
         $id=$this->where('name','=',$name)->field('id')->select();
        }
        if(empty($id))
            return $id;
        $id=$id->toArray();
        return array_column($id,'id');
    }

}