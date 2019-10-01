<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-9
 * Time: 下午2:56
 * 安装控制器
 */

namespace app\install\controller;
use app\install\model\DbSql;
use think\Console;
use think\Controller;
use think\facade\Env;
use think\facade\Request;
use think\facade\Validate;


class Index extends Controller{

    private $pdo;

    //TODO 进入安装页面
    public function index()
    {
        return $this->fetch('/index');
    }

    //TODO 安装验证检查基本数据
    public function valiData()
    {
        // 安装前数据信息验证
        $post=trim_str(input("post."));
        return $this->vailAllData($post);
    }

    //TODO  执行安装
    public function install()
    {
        $post=trim_str(input("post."));
        if(!Request::isAjax() || empty($post)) // 非法请求
        {
            exit("error");
        }

        //---------------------------------------------------检查环境
        if(version_compare(PHP_VERSION,'5.5.0','<'))
        {
            exit("Current version of PHP".PHP_VERSION."less than5.5.0");
        }
        // 需要开启的扩展
        $extension=["PDO","pdo_mysql","gd","mbstring","curl"];
        for($i=0;$i<count($extension);$i++)
        {
            if(!extension_loaded($extension[$i]))
            {
                exit("Please open the PHP ".$extension[$i]." extension");
            }
        }

        //------------------------------------删除系统运行目录缓存
        Console::call('clear');

        //------------------------------------数据验证开始
        $validate = Validate::make([
            'host'=>'require|ip',
            'port'=>'require|integer',
            'dbuser'=>'require|regex:[\w]{2,20}',
            'dbpass'=>'require|regex:[\w\.\@\-]{2,20}',
            'dbname'=>'require|regex:[\w]{2,20}',
            'prefix'=>'regex:[a-z]{1,5}',
            'name' => 'require|length:2,20',
            'pass'=>'confirm:pass2',
            'pass2'=>'confirm:pass'
        ]);

        if (!$validate->check($post)) {
            exit($validate->getError());
        }

        if(empty($post["prefix"]))
        {
            $post["prefix"]=$post["dbname"].'_';
        }else
        {
            $post["prefix"].='_';
        }

        if(empty($post["charset"]))
        {
            $post["charset"]=config('default_char');
        }

        if(empty($post["pass"]))
        {
            $post["pass"]=encry(get_cas_config('default_pass'));
        }else
        {
            $post["pass"]=encry($post["pass"]);
        }
        //------------------------------------数据验证结束

        // 验证数据库连接
        $state=$this->vailAllData($post,true);
        if($state=="server_error")
        {
            exit($state);
        }

        // 如果连接成功  写入配置文件
        $c_f=Env::get('config_path').'database.php';

        if(!is_writable($c_f))
        {
            exit("config file no writable");
        }
        $this->confFileWrite($c_f,$post);

        try{

          if($post["table"]==1) // 如果数据中存在表---清空
            {
                $sql_table="show tables from {$post["dbname"]}";
                $res=$this->pdo->prepare($sql_table);//准备查询语句
                $res->execute(); //函数是用于执行已经预处理过的语句，只是返回执行结果成功或失败需要配合prepare函数使用
                $drop_table=$res->fetchAll(\PDO::FETCH_NUM);
                $res->closeCursor();

                if(!empty($drop_table))
                {
                    foreach($drop_table as $k=>$v)
                    {
                        $drop_table_str[$k]=$v[0];
                    }
                    $drop_table=implode("`,`",$drop_table_str);
                    $drop_sql="drop table `$drop_table`";
                    $this->pdo->exec($drop_sql); // 无返回值
                    if($this->pdo->errorCode()!='00000')
                    {
                        dump($this->pdo->errorInfo());
                        exit;
                    }
                }
            }

        // 如果数据库中存在表删除完成后在添加新数据
        $sql_table="show tables from {$post["dbname"]}";
        $sth = $this->pdo->prepare($sql_table);
        $sth->execute();
        $table_c=$sth->fetchAll(\PDO::FETCH_NUM);
        if(empty($table_c))
        {
            //--------------写入数据
            $sql=new DbSql();
            $sql_con=$sql->index($post["prefix"],$post["charset"]);

            foreach ($sql_con as $k=>$v)
            {
                $this->pdo->exec($v);
            }

            $admin_user_sql="INSERT INTO `{$post["prefix"]}admin_user` (`name`, `pass`,`r_id`) VALUES ('{$post['name']}', '{$post['pass']}',1);";
            $this->pdo->exec($admin_user_sql);
            $this->pdo=null; // 释放原来的资源

            // 创建路由文件
            $route=Env::get('route_path');
            $first_chmod=$chmod='';
            if(!is_writable($route))
            {
                $chmod=substr(base_convert(@fileperms($route),10,8),-4);
                $first_chmod=substr($chmod,0,1);
                if(intval($chmod)<666 && (!chmod($route,0666)))
                {
                   exit('route_path No write permission ');
                }
            }

            // 设置路由
			$install_route=$route."install.php";
            $install_route_con="<?php
            Route::any('install','install/index/index');";

			// 判断文件是否写入
			if(file_put_contents($install_route,$install_route_con) === false)
			{
				exit("write lock file fail,filename:".$install_route);
			}
            // 文件权限更改为原来的
			if($first_chmod!='')
            {
                chmod($route,$first_chmod.$chmod);
            }
			$json['status']='ok';
            $json['url']=get_cas_config('login_url');
            return $json;
         }

        }catch (\Exception $e){
            $error=$e->getFile().PHP_EOL.$e->getLine().PHP_EOL.$e->getMessage();
            exit($error);

        }
    }

    /**
     * 安装前验证所有数据信息
     * @param array $post 需要验证的提交数据
     * @param bool $is_return 是否返回，默认为false 直接输出
     * @rerun Void
     * */
    private function vailAllData($post)
    {
        //使用Db 总读取没有写入配置文件之前的原配置文件中的配置信息 访问模块是就已经加载了配置信息
        //使用pdo 判断数据库是否连接成功
        try{
            $dsn = "mysql:host=".$post["host"].";port=".$post["port"].";dbname=".$post["dbname"];
            $this->pdo = new \PDO($dsn,$post["dbuser"],$post["dbpass"]);
            $this->pdo->query("SET NAMES {$post['charset']}");

            $sql="show tables from {$post["dbname"]}";

            $res=$this->pdo->prepare($sql);//准备查询语句
            $res->execute(); //函数是用于执行已经预处理过的语句，只是返回执行结果成功或失败需要配合prepare函数使用
            $result=$res->fetchAll(\PDO::FETCH_NUM);

            if(!empty($result))// 数据中存在表
            {
                return "table_data";
            }else{   
                // 可以使用  数据库
               return "db_exis";
            }
        }catch (\Exception $e){
            // 连接数据库失败或没有数据库权限
            return 'server_error';
        }

        $this->pdo=$dsn=null;
    }
	
	//写入配置文件
	private function confFileWrite($filename,$data)
	{
		$config="<?php
			  return [
				// 数据库类型
				'type'            => 'mysql',
				// 服务器地址
				'hostname'        => '{$data["host"]}',
				// 数据库名
				'database'        => '{$data["dbname"]}',
				// 用户名
				'username'        => '{$data["dbuser"]}',
				// 密码
				'password'        => '{$data["dbpass"]}',
				// 端口
				'hostport'        => '{$data["port"]}',
				// 连接dsn
				'dsn'             => '',
				// 数据库连接参数
				'params'          => [],
				// 数据库编码默认采用utf8
				'charset'         => '{$data["charset"]}',
				// 数据库表前缀
				'prefix'          => '{$data["prefix"]}',
				// 数据库调试模式
				'debug'           => true,
				// 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
				'deploy'          => 0,
				// 数据库读写是否分离 主从式有效
				'rw_separate'     => false,
				// 读写分离后 主服务器数量
				'master_num'      => 1,
				// 指定从服务器序号
				'slave_no'        => '',
				// 是否严格检查字段是否存在
				'fields_strict'   => true,
				// 数据集返回类型
				'resultset_type'  => 'array',
				// 自动写入时间戳字段
				'auto_timestamp'  => false,
				// 时间字段取出后的默认时间格式
				'datetime_format' => 'Y-m-d H:i:s',
				// 是否需要进行SQL性能分析
				'sql_explain'     => false,
				// Builder类
				'builder'         => '',
				// Query类
				'query'           => '\\think\\db\\Query',
				// 是否需要断线重连
				'break_reconnect' => false,
				// 断线标识字符串
				'break_match_str' => [],
			];";
		// 写入配置文件
		if(file_put_contents($filename, $config) === false)
		{
			exit("config file write error");// 写入配置文件失败
		}
	}

}
?>