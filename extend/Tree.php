<?php
/**
 * Class Tree
 *  树形数组嵌套
 */

class Tree{
	
	/**
	 * @todo   getTree 			创建嵌套树形数组,指定父级下所有的子节点
	 * @param  [type]  $list        要创建的数组
	 * @param  integer $pid         父节点值
	 * @param  integer $level       层级
	 * @param  string  $index_field 数组索引字段名
	 * @param  string  $pid_field   父节点字段名
     * @param  string  $name_level_field   层级分隔符
     * @param  string  $level_field   层级字段名
	 * @param  string  $child_field 子节点字段名
	 * @return array         		成功返回数组
	 */
	public function getTree($list, $pid = 0,$level=0,$index_field = 'id', $pid_field = 'pid', $name_level_field = 'name_level',$level_field='level', $child_field = "items")
	{
		$delimiter ="|—";
		// 子级分配层级是父级未分配层级，导致层级错误

		// 按 PID 排序
		/*$last_names = array_column($list,$pid_field);
		array_multisort($last_names,SORT_ASC,$list);*/
		$newList = array_column($list->toArray(),null,$index_field);

		foreach ($newList as $value ) {
			
			if($pid==$value[$pid_field]){
				// 顶级
				$newList[$value[$index_field]][$level_field]=0;
				$newList[$value[$index_field]][$name_level_field]='';
				if(isset($value['module'])){
					$newList[$value[$index_field]]['url']=$value['module'];
				}
				$tree[] = &$newList[$value[$index_field]];
			}elseif(isset($newList[$value[$pid_field]])){
				if(isset($newList[$value[$pid_field]][$level_field])){
					$level_num=$newList[$value[$pid_field]][$level_field]+1;
				}else{
					// 查找父级
					$level_num=$newList[$value[$index_field]][$level_field]=1;
				}
				
                $newList[$value[$index_field]][$level_field]=$level_num;
                $newList[$value[$index_field]][$name_level_field]=str_repeat($delimiter,$level_num);
                if(isset($value['module'])){
                   parse_str($value['param'],$value['param']);
                   $action=empty($value['action'])?config('default_action'):$value['action'];
                   $newList[$value[$index_field]]['url']=$value['module'].'/'.$value['controller'].'/'.$action;
               }
				$newList[$value[$pid_field]][$child_field][] = &$newList[$value[$index_field]];   
			}
		}
		
		return $tree;
	}

	/**
	 * @todo   getTreeSelect 		下拉框树形结构,选中禁止单个
	 * @param  [type] $list           	嵌套树形数组
	 * @param  string $select         	默认选中ID
	 * @param  string $disabled       	禁止选中ID
	 * @param  string $index_field     	索引字段名
	 * @param  string $name_field      	标题字段名
	 * @param  string $level_field 		层级标识字段名
	 * @param  string $child_field     	子节点字段名
	 * @return string                	html下拉框
	 */
	public function getTreeSelect($list,$select='',$disabled='',$index_field = 'id', $name_field = 'name',$level_field='name_level',$child_field = "items")
	{
		static $tree='';
		foreach($list as $k=>$v){
			$selected=$v[$index_field] == $select?' selected="selected" ':'';
			$disableded=$v[$index_field] == $disabled?' disabled="disabled" ':'';
			$tree.='<option value="'.$v[$index_field].'" '.$selected.$disableded.'>'.$v[$level_field].$v[$name_field].'</option>'.PHP_EOL;
			if(isset($v[$child_field]) && is_array($v[$child_field])){
				$this->getTreeSelect($v[$child_field],$select,$disabled,$index_field,$name_field,$level_field,$child_field);
			}
		}
		return $tree;
	}

	/**
	 * @todo   getTreeTable         树形结构表格，默认选项是根据layui静态表格定义
     * @desc  展开下级时要根据层级循环次数 --- 弊端
	 * @param  array  $list             嵌套树形数组
	 * @param  string $index_field      索引字段名
	 * @param  string $level_field 		 层级标识字段名
	 * @param  string $child_field      子节点字段名
	 * @return string                   html表格
	 */
	public function getTreeTable($list,$index_field = 'id',$level_field='name_level', $child_field = "items")
	{
		static $tree='';
		$nbsp='&nbsp; &nbsp; ';
		
		foreach($list as $k=>$v){
            $level_node=str_repeat($nbsp,$v['level']);
            if($v['pid']<1){
                $display='';
            }else{
                $display=' style="display:none" ';
            }

			$tree.='<tr '.$display.' data-level="'.$v['level'].'" data-id="'.$v[$index_field].'" >';
            $tree.='<td><span  onclick="javascript:check_node($(this));"><input type="checkbox" name="id[]" lay-skin="primary" value="'.$v['id'].'" ></span></td>';
            if(config('app_debug')){
                $tree.='<td>'.$v[$index_field].'</td>
                        <td class="layui-elip">'.$v[$level_field].$v['url'].'</td>';
            }

            if(!empty($v[$child_field])){
                $level_node.='<i class="fa fa-play expander" onclick="javascript:slide_node($(this));"></i>';
            }

            $tree.='<td class="layui-elip">'.$level_node.$v[$level_field].$v['name'].'</td>
                    <td class="sort"><input class="form-control" name="sort['.$v[$index_field].']" value="'.$v['sort'].'"></td>';

            if($v['is_menu']==IS_MENU){
                $checked="checked='checked'"; 
            }else{
                $checked='';
            }
            $tree.='<td>
                        <input type="checkbox" name="is_menu" lay-skin="switch" lay-filter="is_menu" lay-text="'.lang('is_menu_but').'" value="'.$v[$index_field].'" '.$checked.'>
                     </td>';

            if($v['is_enable']==IS_ENABLE){
                //$is_enable_but='open';
                $checked="checked='checked'";
            }else{
                //$is_enable_but='close';
                $checked="";
            }

            // 系统内置不可点击禁用
            $is_sys='';
            if($v['is_sys']==IS_SYS){
        		$tree.='<td title="'.lang('is_sys_disable').'"><div class="layui-unselect layui-form-switch layui-form-onswitch"><em>'.lang('is_enable_end').'</em><i></i></div></td>';
                    
            }else{
				$tree.='<td '.$is_sys.'>
                        <input type="checkbox"  name="is_enable" lay-skin="switch" lay-filter="is_enable" lay-text="'.lang('is_enable_but').'" '.$checked.' value="'.$v[$index_field].'">
                       </td>';
            }
            
            $operation='<td class="layui-elip">';
            // 限制层级
			if($v['level']<3){
                $operation.='<a href="'.url('edit',['pid'=>$v[$index_field]]).'"><i class="fa  fa-plus-square-o text-info"></i></a>';
            }
            $operation.='<a href="'.url('edit',['id'=>$v[$index_field]]).'" ><i class="fa fa-edit text-warning"></i></a>';
			if($v['is_sys']!=IS_SYS)
			{
                $operation.='<a lay-event="del" data-href="'.url('delete',['id'=>$v[$index_field]]).'" href="#" ><i class="fa fa-trash-o text-danger"></i></a></td>';
            }
            $tree.=$operation.'</tr>'.PHP_EOL;
			if(isset($v[$child_field]) && is_array($v[$child_field])){
				$this->getTreeTable($v[$child_field],$index_field,$level_field,$child_field);
			}
		}
		return $tree;
	}

    /**
     * @todo   getTreeTableSelect   树形结构表格，可以折叠菜单，分配权限，可以勾选子级
     * @param  array  $list             嵌套树形数组
     * @param  array  $select           默认选中ID
     * @param  array  $disabled         禁止选中ID
     * @param  string $index_field      索引字段名
     * @param  string $level_field 		 层级标识字段名
     * @param  string $child_field      子节点字段名
     * @return string                   html表格
     */
    public function getTreeTableSelect($list,$select=[],$disabled=[],$index_field = 'id',$level_field='name_level', $child_field = "items")
    {
        static $tree='';
        $nbs='&nbsp; &nbsp; ';
        $checkbox=$disableded='';
        foreach($list as $k=>$v){
			if(!isset($v[$index_field]))  continue;;
            if((!empty($disabled)) && (in_array($v[$index_field],$disabled))){
                $disableded=' disabled="true" ';
            }else{
                $disableded=' ';
            }
            if((!empty($select)) && (in_array($v[$index_field],$select))){
                $checkbox=' checked="checked" ';
            }else{
                $checkbox=' ';
            }
            $level_node=str_repeat($nbs,$v['level']);

            if($v['pid']<1){
                $display='';
            }else{
                $display=' style="display:none" ';
            }

            if(!empty($v[$child_field])){
                $level_node.='<i class="fa fa-play expander" onclick="javascript:slide_node($(this));"></i>';
            }

            $tree.='<tr '.$display.' data-level="'.$v['level'].'">';

            $tree.='<td>'.$level_node.$v[$level_field].'<span onclick="javascript:check_node($(this));"><input type="checkbox" name="id[]" lay-skin="primary" value="'.$v[$index_field].'" '.$disableded.$checkbox.'></span> ';

            $tree.= $v['name'].'</td></tr>'.PHP_EOL;
            if(isset($v[$child_field]) && is_array($v[$child_field])){
                $this->getTreeTableSelect($v[$child_field],$select,$disabled,$index_field,$level_field,$child_field);
            }
        }
        return $tree;
    }

}