<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-20
 * Time: 下午1:43
 * 自定义 curl 函数
 */

namespace Think;


class Curl {

    // post 请求
    public function https_post($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public function https_get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /** curl post/get请求 url 返回数据
     * @parem  $url 请求url
     * @parem  $data 请求参数数据 post 用到
     * @parem  $cookie 设置cookie
     * @return 返回请求的数据
     * */
    public function http_curl_url($url,$data=null,$cookie=null)
    {
        // 初始化 curl
        $curl = curl_init();

        // 设置URL和相应的选项 curl_setopt — 设置一个cURL传输选项。
        //1.由 curl_init() 返回的 cURL 句柄; 2.需要设置的CURLOPT_XXX选项;3.将设置在option选项上的值
        curl_setopt($curl, CURLOPT_URL, $url); // url
        curl_setopt($curl, CURLOPT_HEADER, 0); // 将头文件的信息作为数据流输出； 1 为输出 ；0 不输出

        //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出：
        //1 或者 true为不输出，0 或false  直接输出到页面上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);

        // https请求 不验证证书和hosts 请求数据时使用，跳过验证； 例如参数 返回数据
        //CURLOPT_SSL_VERIFYPEER 禁用后cURL将终止从服务端进行验证  默认为true
        //如果CURLOPT_SSL_VERIFYPEER(默认值为2)被启用，CURLOPT_SSL_VERIFYHOST需要被设置成TRUE否则设置为FALSE。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        // 如果传送数据---- 使用post 方式
        if(isset($data) && !empty($data))
        {
            //设置post方式提交 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
            //表示是否启用第二个option，这里为CURLOPT_POST，设置为1，表示启用时会发送一个常规的POST请求
            curl_setopt($curl, CURLOPT_POST, 1);

            /*这个参数可以通过urlencoded后的字符串类似'para1=val1&para2=val2&...'或使用一个以字段名为键值，
            字段数据为值的数组。如果value是一个数组，Content-Type头将会被设置成multipart/form-data。
             * */
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        // 如果传递 HTTP请求中 的 Cookie
        if(isset($cookie) && !empty($cookie))
        {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }

        // 抓取URL并把它传递给浏览器 执行curl 返回页面数据
        $outdata = curl_exec($curl);
        // 关闭curl 连接
        curl_close($curl);
        return $outdata;
    }

	// 返回json 类型数据
	function curl_request($url,$data=array(),$https=array(),$cookie=null,$json=false){
		// 初始化 curl
		$curl = curl_init();
		// 设置 url
		curl_setopt($curl, CURLOPT_URL, $url);
		
		//证书验证
		if(empty($https))
		{
			// http请求 不验证证书和 hosts 请求数据时使用，跳过验证； 例如参数 返回数据
			// CURLOPT_SSL_VERIFYPEER 禁用后cURL将终止从服务端进行验证  默认为true
			// 如果CURLOPT_SSL_VERIFYPEER(默认值为2)被启用，CURLOPT_SSL_VERIFYHOST需要被设置成TRUE否则设置为FALSE。
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			
		}else{
			curl_setopt($curl, CURLOPT_SSLCERTTYPE, $https['PEM']);
			curl_setopt($curl, CURLOPT_SSLCERT, $https['cert_path']);
			curl_setopt($curl, CURLOPT_SSLKEYTYPE, $https['PEM']);
			curl_setopt($curl, CURLOPT_SSLKEY, $https['key_path']);
		}

		// 如果发送数据
		if (!empty($data)) 
		{
			//将头文件的信息作为数据流输出； 1 为输出 ；0 不输出    
			curl_setopt($curl, CURLOPT_HEADER, 0);
			//发送JSON数据，
			if(($json==true) && is_array($data)){
				 curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type:application/json;charset=utf-8','Content-Length:'.strlen($data)));
				$data = json_encode($data,JSON_UNESCAPED_SLASHES);
			}        
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);      
		}
		
		// 如果传递 HTTP请求中 的 Cookie
		if(isset($cookie) && !empty($cookie))
		{
			curl_setopt($curl, CURLOPT_COOKIE, $cookie);
		}
		
		//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出：1 或者 true为不输出，0 或false  直接输出到页面上
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//设置连接超时时间
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
		//设置请求超时时间
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		
		// 也可将参数放入数组一次传入 curl_setopt_array($ch, $params); //传入curl参数

		// 执行
		$result = curl_exec($curl);
		// 获取错误信息
		$errorno = curl_errno($curl);
		if ($errorno) {
			return $errorno;
		}else{
			return $result
		}
		// 关闭连接
		curl_close($curl);
	}
	
	/**
     * @param $url 请求地址
     * @param $filename 文件名
     * @param $path 文件临时路径
     * @param string $type 文件类型
     * @return mixed
     * @url https://blog.csdn.net/notaloney/article/details/101370497
     */
    function curl_post_file($url,$header=[],$filename,$path,$type = 'text/plain')
    {
        //php 5.5以上的用法
        if (class_exists('\CURLFile')) {
            $data = array(
                'file' => new \CURLFile(realpath($path), $type, $filename),
            );
        } else {
            //5.5以下会走到这步
            $data = array(
                'file'=>'@'.realpath($path).";type=".$type.";filename=".$filename,
            );
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, false);
        if(!isset($header)){
            $header=array('Content-Type:application/json;charset=utf-8','Content-Length:'.strlen($data));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $return_data = curl_exec($ch);
        curl_close($ch);
        return $return_data;
    }

} 