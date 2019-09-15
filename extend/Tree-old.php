<?php
/**
 * 通用的树型类，可以生成任何树型结构
 */
class Tree
{

    /**
     * 生成树型结构所需要的2维数组
     * @var array
     */
    public $arr = [];

    /**
     * 生成树型结构所需修饰符号，可以换成图片
     * @var array
     */
    public $icon = ['│', '├', '└'];
    public $nbsp = "&nbsp;";
    private $str = '';
    /**
     * @access private
     */
    public $ret = '';

    /**
     * 构造函数，初始化类
     * @param array 2维数组，例如：
     *      array(
     *      1 => array('id'=>'1','parent_id'=>0,'name'=>'一级栏目一'),
     *      2 => array('id'=>'2','parent_id'=>0,'name'=>'一级栏目二'),
     *      3 => array('id'=>'3','parent_id'=>1,'name'=>'二级栏目一'),
     *      4 => array('id'=>'4','parent_id'=>1,'name'=>'二级栏目二'),
     *      5 => array('id'=>'5','parent_id'=>2,'name'=>'二级栏目三'),
     *      6 => array('id'=>'6','parent_id'=>3,'name'=>'三级栏目一'),
     *      7 => array('id'=>'7','parent_id'=>3,'name'=>'三级栏目二')
     *      )
     * @return array
     */
    public function init($arr = [])
    {
        $this->arr = $arr;
        $this->ret = '';
        return is_array($arr);
    }

    /**
     * 得到父级以及父级同级数组
     * @param int
     * @return array
     */
    public function getParent($myId)
    {
        $newArr = [];
        if (!isset($this->arr[$myId]))
            return false;
        $pid = $this->arr[$myId]['parent_id'];
        $pid = $this->arr[$pid]['parent_id'];
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if ($a['parent_id'] == $pid)
                    $newArr[$id] = $a;
            }
        }
        return $newArr;
    }
	
	/**
	* 根据父id获得所有下级
	*/
	public function getAllChild($array,$id){
		$newArr = [];
		foreach ($array as $id => $a) {
			if ($a['parent_id'] == $id) {
				$newArr[$id] = $a;
				 unset($array[$id]);
				$this->getAllChild($array,$a['id']);
			}
        }
		return $newArr;
	}

    /**
     * 得到一级子级数组
     * @param int
     * @return array
     */
    public function getChild($myId)
    {
        $newArr = [];
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {

                if ($a['parent_id'] == $myId) {
                    $newArr[$id] = $a;
                }
            }
        }

        return $newArr ? $newArr : false;
    }

    /**
     * 得到当前位置数组
     * @param int
     * @return array
     */
    public function getPosition($myId, &$newArr)
    {
        $a = [];
        if (!isset($this->arr[$myId]))
            return false;
        $newArr[] = $this->arr[$myId];
        $pid      = $this->arr[$myId]['parent_id'];
        if (isset($this->arr[$pid])) {
            $this->getPosition($pid, $newArr);
        }
        if (is_array($newArr)) {
            krsort($newArr);
            foreach ($newArr as $v) {
                $a[$v['id']] = $v;
            }
        }
        return $a;
    }

    /**
     * 得到树型结构 html 格式
     * @param int ID，表示获得这个ID下的所有子级
     * @param string 生成树型结构的基本代码，例如："<option value=\$id \$selected>\$spacer\$name</option>"
     * @param int 被选中的ID，比如在做树型下拉框的时候需要用到
     * @return string
     */
    public function getTree($myId, $str, $sid = 0, $adds = '')
    {
        $number = 1;
        //一级栏目
        $child = $this->getChild($myId);

        if (is_array($child)) {
            $total = count($child);

            foreach ($child as $key => $value) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer   = $adds ? $adds . $j : '';
                $selected = $value['id'] == $sid ? 'selected' : '';
              
                $nstr     = '';
                $parentId = $value['parent_id'];
                @extract($value);

                eval("\$nstr = \"$str\";");

                $this->ret .= $nstr;
                $nbsp      = $this->nbsp;
                $this->getTree($value['id'], $str, $sid, $adds . $k . $nbsp);
                $number++;
            }
        }
        return $this->ret;
    }

    /**
     * 生成树型结构数组
     * @param int myID，表示获得这个ID下的所有子级
     * @param int $maxLevel 最大获取层级,默认不限制
     * @param int $level    当前层级,只在递归调用时使用,真实使用时不传入此参数
     * @return array
     */
    public function getTreeArray($myId, $maxLevel = 0, $level = 1)
    {
        $returnArray = [];

        //一级数组
        $children = $this->getChild($myId);

        if (is_array($children)) {
            foreach ($children as $child) {
                $child['_level']           = $level;
                $returnArray[$child['id']] = $child;
                if ($maxLevel === 0 || ($maxLevel !== 0 && $maxLevel > $level)) {

                    $mLevel = $level + 1;
                    $returnArray[$child['id']]["children"] = $this->getTreeArray($child['id'], $maxLevel, $mLevel);
                }
            }
        }

        return $returnArray;
    }

    //TODO 优化--- 数组转树形嵌套数组
    private function createTree($list, $index = 'id', $pidField = 'pid', $childField = "child")
    {
        $tree = [];
        $list = array_column($list, null, $index);
        foreach ($list as $v) {
            if (isset($list[$v[$pidField]])) {
                $list[$v[$pidField]][$childField][] = &$list[$v[$index]];
            } else {
                $tree[] =& $list[$v[$index]];
            }
        }
        return $tree;
    }

    /**
     * 同上一方法类似,但允许多选
     */
/*    public function getTreeMulti($myId, $str, $sid = 0, $adds = '')
    {
        $number = 1;
        $child  = $this->getChild($myId);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $a) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';

                $selected = $this->have($sid, $id) ? 'selected' : '';
                $nstr=null;
                @extract($a);
                eval("\$nstr = \"$str\";");
                $this->ret .= $nstr;
                $this->getTreeMulti($id, $str, $sid, $adds . $k . '&nbsp;');
                $number++;
            }
        }
        return $this->ret;
    }*/

    /**
	 * getTreeCategory 可以自定义html 标签样式，标签中必有变量 $spacer $selected
     * @param integer $myId 要查询的ID
     * @param string  $str  第一种HTML代码方式
     * @param string  $str2 第二种HTML代码方式
     * @param integer $sid  默认选中
     * @param integer $adds 前缀
     */
    public function getTreeCategory($myId, $str, $str2, $sid = 0, $adds = '')
    {
        $number = 1;
        $child  = $this->getChild($myId);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $a) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';

                $selected = $this->have($sid, $id) ? 'selected' : '';
                $nstr=null;
                @extract($a);
                if (empty($str2)) {
                    eval("\$nstr = \"$str\";");
                } else {
                    eval("\$nstr = \"$str2\";");
                }
                $this->ret .= $nstr;
                $this->getTreeCategory($id, $str, $str2, $sid, $adds . $k . '&nbsp;');
                $number++;
            }
        }
        return $this->ret;
    }



    /**
     * 获取子栏目json
     * Enter description here ...
     * @param unknown_type $myId
     */
    public function createSubJson($myId, $str = '')
    {
        $sub_cats = $this->getChild($myId);
        $n        = 0;
        if (is_array($sub_cats))
            foreach ($sub_cats as $c) {
                $data[$n]['id'] = iconv(CHARSET, 'utf-8', $c['catid']);
                if ($this->getChild($c['catid'])) {
                    $data[$n]['liclass']  = 'hasChildren';
                    $data[$n]['children'] = [['text' => '&nbsp;', 'classes' => 'placeholder']];
                    $data[$n]['classes']  = 'folder';
                    $data[$n]['text']     = iconv(CHARSET, 'utf-8', $c['catname']);
                } else {
                    if ($str) {
                        @extract(array_iconv($c, CHARSET, 'utf-8'));
                        eval("\$data[$n]['text'] = \"$str\";");
                    } else {
                        $data[$n]['text'] = iconv(CHARSET, 'utf-8', $c['catname']);
                    }
                }
                $n++;
            }
        return json_encode($data);
    }

    private function have($list, $item)
    {
        return (strpos(',,' . $list . ',', ',' . $item . ','));
    }

}

