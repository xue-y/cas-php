<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/8/18
 * Time: 13:19
 */

namespace app\pub\controller;

use app\install\model\DbSql;
use think\Controller;

class Test extends Controller
{

    
    public function index(){

       $dbSql=new DbSql();
       $sql=$dbSql->index('test_','utf8');
       dump($sql);
    }



}