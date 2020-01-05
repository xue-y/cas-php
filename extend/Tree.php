<?php

class Tree{

	/**
	 * @todo   createTreeArr 	 创建嵌套树形数组
	 * @param  [type] $list         要创建的数组
     * @param  array  $select       默认选中
     * @param  array  $disabled     默认禁止选中
	 * @param  string $index_field  数组索引字段名
	 * @param  string $pid_field    父节点字段名
	 * @param  string $child_field  子节点字段名
	 * @return array          		 成功返回数组
	 */
	public function getLayuiTreeArr($list,$select=[],$disabled=[],$index_field = 'id', $pid_field = 'pid', $child_field = "children")
	{
		$tree = [];
		$list = array_column($list, null, $index_field);
		foreach ($list as $v) {
			if (isset($list[$v[$pid_field]])) {
                $list[$v[$index_field]]['field']=$index_field.'[]';
                if(!empty($select) && in_array($v[$index_field],$select)){
                    $checked=true;
                }else{
                    $checked=false;
                }
                if(!empty($disabled) && in_array($v[$index_field],$disabled)){
                    $disabled=true;
                }else{
                    $disabled=false;
                }
                $list[$v[$index_field]]['checked']=$checked;
                $list[$v[$index_field]]['disabled']=$disabled;
				$list[$v[$pid_field]][$child_field][] = &$list[$v[$index_field]];
			} else {
			    // 顶级
                $list[$v[$index_field]]['field']=$index_field.'[]';
                if(!empty($select) && in_array($v[$index_field],$select)){
                    $checked=true;
                }else{
                    $checked=false;
                }
                if(!empty($disabled) && in_array($v[$index_field],$disabled)){
                    $disabled=true;
                }else{
                    $disabled=false;
                }
                $list[$v[$index_field]]['checked']=$checked;
                $list[$v[$index_field]]['disabled']=$disabled;
				$tree[] =& $list[$v[$index_field]];
			}
		}
		return $tree;
	}

	
	/**
	 * @todo   getTree 			创建嵌套树形数组,指定父级下所有的子节点
	 * @param  [type]  $list        要创建的数组
	 * @param  integer $pid         父节点值
	 * @param  integer $level       层级
	 * @param  string  $index_field 数组索引字段名
	 * @param  string  $pid_field   父节点字段名
     * @param  string  $name_level_field   层级名称字段
     * @param  string  $level_field   层级字段名
	 * @param  string  $child_field 子节点字段名
	 * @return array         		成功返回数组
	 */
	public function getTree($list, $pid = 0,$level=0,$index_field = 'id', $pid_field = 'pid', $name_level_field = 'name_level',$level_field='level', $child_field = "items")
	{
		$delimiter ="|—";
		$newList=array_column($list,null,$index_field);
		
		foreach ($newList as $value ) {
			if ($pid === $value[$pid_field]) {
			    // 顶级
				$level_num=isset($value[$level_field])?$value[$level_field]:$level;
				$newList[$value[$index_field]][$level_field]=$level_num;
				$newList[$value[$index_field]][$name_level_field]='';
				if(isset($value['module']))
				    $newList[$value[$index_field]]['url']=$value['module'];
				$tree[] = &$newList[$value[$index_field]];
			} elseif (isset($newList[$value[$pid_field]]))
			{
                // 从第二级开始  第一次进来都没有层级字段，需要赋值为1
                if(!isset($newList[$value[$pid_field]][$level_field])){
                    $newList[$value[$pid_field]][$level_field]=1;
                }
			    
               $level_num=$newList[$value[$pid_field]][$level_field]+1;
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
		// 如果顶级分类下没有一个下级，删除此分类，此步骤可以省略
		/*foreach ($tree as $k=>$v)
		{
			if(!isset($v[$child_field]))
				unset($tree[$k]);
		}*/
	    //dump($tree);
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
	public function getTreeSelect($list,$select='',$disabled='',$index_field = 'id', $name_field = 'name',$level_field='name_level', $child_field = "items")
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
     * @desc  展开下级时要根据层级循环次数 --- 弊端，父级选中子级也是 ---- 弊端
	 * @param  array  $list             嵌套树形数组
	 * @param  array  $select           默认选中ID
	 * @param  array  $disabled         禁止选中ID
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
                $class_node=$display='';
            }else{
                $class_node=' child-of-node-'.$v['pid'];
                $display=' style="display:none" ';
            }


			$tree.='<tr class="node-'.$v[$index_field].$class_node.'"'.$display.' data-id="'.$v[$index_field].'">';
            $tree.='<td><span  onclick="javascript:check_node($(this));"><input type="checkbox" name="id[]" lay-skin="primary" value="'.$v['id'].'" level="'.$v['level'].'" ></span></td>';
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
                $class_node=$display='';
            }else{
                $class_node=' child-of-node-'.$v['pid'];
                $display=' style="display:none" ';
            }

            if(!empty($v[$child_field])){
                $level_node.='<i class="fa fa-play expander" onclick="javascript:slide_node($(this));"></i>';
            }

            $tree.='<tr class="node-'.$v[$index_field].$class_node.'"'.$display.' data-id="'.$v[$index_field].'">';

            $tree.='<td>'.$level_node.$v[$level_field].'<span onclick="javascript:check_node($(this));"><input type="checkbox" name="id[]" level="'.$v['level'].'" lay-skin="primary" value="'.$v[$index_field].'" '.$disableded.$checkbox.'></span> ';

            $tree.= $v['name'].'</td></tr>'.PHP_EOL;
            if(isset($v[$child_field]) && is_array($v[$child_field])){
                $this->getTreeTableSelect($v[$child_field],$select,$disabled,$index_field,$level_field,$child_field);
            }
        }
        return $tree;
    }

}