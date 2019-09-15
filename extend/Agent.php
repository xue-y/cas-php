<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-27
 * Time: 下午5:27
 */

class Agent {

    /**
     * TODO 获取登录用户设备信息
     * getDeviceInfo
     * @param bool $shebei 返回类型,true如果查找到返回设备名称,false 返回bool值
     * @return string|bool
     */
    public function getDeviceInfo($shebei=true){
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
            if(isset($shebei))
                return $_SERVER['HTTP_X_WAP_PROFILE'];
            else
                return TRUE;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])){
            if(isset($shebei))
            {
                return $_SERVER['HTTP_VIA'];
            }else
            {
                return stristr($_SERVER['HTTP_VIA'], "wap") ? TRUE : FALSE;// 找不到为flase,否则为TRUE
            }
        }
        // 判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            if(isset($shebei))
                return $_SERVER['HTTP_USER_AGENT'];
            else
                return TRUE;
           $clientkeywords = array (
                'mobile',
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'Chrome',
                'Firefox',
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']),$matches)){
                if(isset($shebei))
                    return $matches;
                else
                    return TRUE;
            }
        }
        if (isset ($_SERVER['HTTP_ACCEPT'])){ // 协议法，因为有可能不准确，放到最后判断
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== FALSE) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === FALSE || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))){
                if(isset($shebei))
                    return $_SERVER['HTTP_ACCEPT'];
                else
                    return TRUE;
            }
        }
        return FALSE;
    }

    //获取浏览器类型
    public function getBrowser() {
        $agent   = $_SERVER['HTTP_USER_AGENT'];
        if(strpos($agent, 'MicroMessenger') !== false){
            echo "wx";
        }else if(strpos($agent, 'QQ') !== false){
            echo "qq";
        }
        else{
            echo "other";
        }
    }
    // 如果是https 连接返回true ,否则返回false
    public function is_https() {
        if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
            return true;
        } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }
        return false;
    }

} 