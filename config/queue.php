<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
use think\facade\Env;

return [
   // 'connector' => 'Sync',
    'fail_repeat_delay'=>5,//如果执行失败，$delay为延迟时间重新执行
    'fail_exec_number'=>2,//通过这个方法可以检查这个任务已经重试了几次了
    //Redis驱动
    'connector' => 'redis',
    "expire"=>60,//任务过期时间默认为秒，禁用为null
    "default"=>"default",//默认队列名称

    "host"=>Env::get("redis.host", "127.0.0.1"),//Redis主机IP地址
    "port"=>Env::get("redis.port", 6379),//Redis端口
    "password"=>Env::get("redis.password", "123456"),//Redis密码
    "select"=>5,//Redis数据库索引
    "timeout"=>10,//Redis连接超时时间
    "persistent"=>false,//是否长连接

    //Database驱动
    //"connector"=>"Database",//数据库驱动
    //"expire"=>60,//任务过期时间，单位为秒，禁用为null
    //"default"=>"default",//默认队列名称
    //"table"=>"jobs",//存储消息的表明，不带前缀
    //"dsn"=>[],

    //Topthink驱动 ThinkPHP内部的队列通知服务平台
    //"connector"=>"Topthink",
    //"token"=>"",
    //"project_id"=>"",
    //"protocol"=>"https",
    //"host"=>"qns.topthink.com",
    //"port"=>443,
    //"api_version"=>1,
    //"max_retries"=>3,
    //"default"=>"default"
];
