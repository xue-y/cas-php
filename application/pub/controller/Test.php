<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/8/18
 * Time: 13:19
 */

namespace app\pub\controller;

use app\Base;
use Crypt\Think;

class Test extends Base
{

    
    public function index(){
      
     return $this->fetch('/index');
    }

    public function create(){
    	return $this->fetch('/create');
    }

}