<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
//应用公共文件

/** 
 * TODO 转义并去除空格字符
 * @param $str 要转义的字符
 * @return  返回转义后的字符
 * */
function trim_str($str)
{
    if(!is_array($str))
    {
        if(!get_magic_quotes_gpc())
        {
            return strip_tags(addslashes(trim($str)));
        }else
        {
            return strip_tags(trim($str));
        }
    }
    foreach($str as $k=>$v)
    {
       $str[$k]=trim_str($v);
    }
    return $str;
}

/**
 * TODO addslashes 反转义字符
 * @param $str 要转义的字符
 * @return  返回转义后的字符
 * */
function str_slashes($str)
{
    if(!is_array($str))
    {
      return  stripslashes($str);
    }
    foreach($str as $k=>$v)
    {
        $str[$k]=str_slashes($v);
    }
    return $str;
}

/**
 * TODO 获取管理员用户名
 * crypt_web_name
 * @return null|string
 */
function get_web_name()
{
    $n_key='admin_user_name';
    $cookie_prefix_n=config('cookie.cookie_user_n');

    if(\think\facade\Cookie::has($n_key,$cookie_prefix_n))
    {
        $n_val=\think\facade\Cookie::get($n_key,$cookie_prefix_n); // 登录是记住用户名
    }else
    {
        $n_val=\think\facade\Cookie::get($n_key); // 登录时没有记住用户名
    }

    if(empty($n_val))
    {
        return null;
    }
    return $n_val;
}

/**
 * update_web_name
 * @todo 更新管理员用户名
 * @param $admin_user_name
 */
function update_web_name($admin_user_name){
    $n_key='admin_user_name';
    $cookie_prefix_n=config('cookie.cookie_user_n');
    if(\think\facade\Cookie::has($n_key,$cookie_prefix_n)){
        \think\facade\Cookie::set('admin_user_name',$admin_user_name,['expire'=>config('cookie.cookie_user_t'),'prefix'=>$cookie_prefix_n]);
    }else{
        \think\facade\Cookie::set('admin_user_name',$admin_user_name);
    }
}

/**
 * TODO get_cas_config 获取自定义配置项值
 * @param  string $name 配置项名称
 * @return string|array|false  配置项值,不存在返回false
 */
function get_cas_config($name){
    return config("config.$name") ;
}


// 密码加密处理
function encry($pass)
{
    $secret_key=get_cas_config('secret_key');
    $before=substr(sha1($secret_key),5,4);
    $after=substr(sha1($secret_key),25,4);
    return $before.md5($pass).$after;
}

/**
 * TODO 数据加密解密并存储
 * @param string $key 加密key
 * @param string|int|array $data 加密数据
 * @param string|int $secret 加密秘钥，默认为空，值为配置文件中的secret_key
 * @param bool $behavior 加密TRUE解密FALSE
 * @return  加密void解密返回值
 */
function think_crypt($key,$data,$secret='',$behavior=true)
{  
    $secret_key=empty($secret)?get_cas_config('secret_key'):$secret;
    if($behavior==true)
    {
        $k=\Crypt\Think::encrypt($key,$secret_key);
        $v=\Crypt\Think::encrypt($data,$k);
        cookie($k,$v);
    }else
    {
        $k=\Crypt\Think::encrypt($key,$secret_key);
        $v=cookie($k);
        return \Crypt\Think::decrypt($v,$k);
    }    
}

/** 
 * TODO think_crypt_once() 一次性加密
 * @param  string $key 加密key
 * @param  int  $val 加密值
 * @param  bool $behavior 加密TRUE,解密FALSE默认加密
 * @return 加密void解密值
 */
function think_crypt_once($key,$val,$behavior=true)
{
    if($behavior===true)
    {
        $k=\Crypt\Base64::encrypt($key,session('token'));
        $v=\Crypt\Base64::encrypt($val,$k);
        cookie($k,$v);
    }else
    {
        $k=\Crypt\Base64::encrypt($key,session('token'));
        $v=cookie($k);
        cookie($k,null);
        return \Crypt\Base64::decrypt($v,$k);
    }
}

/**
 * TODO crypt_str 加密解密返回字符串
 * @param  string  $str      要加密的字符串
 * @param  string  $key      加密的key
 * @param  boolean $behavior 加密true,解密false,默认加密
 * @param  int     $crypt    加密方式 1:base64 2:Crypt 3:Think ，默认3
 * @return string            加密/解密后的字符串
 */
function crypt_str($str,$key,$behavior=true,$crypt=3)
{
    switch ($crypt){
        case 1:
            if($behavior==true)
            {
                return \Crypt\Base64::encrypt($str,$key);
            }else
            {
                return \Crypt\Base64::decrypt($str,$key);
            }
        break;
        case 2:
            if($behavior==true)
            {
                return \Crypt\Think::encrypt($str,$key);
            }else
            {
                return \Crypt\Think::decrypt($str,$key);
            }
        break;
        default:
            if($behavior==true)
            {
                return \Crypt\Crypt::encrypt($str,$key);
            }else
            {
                return \Crypt\Crypt::decrypt($str,$key);
            }
        break;
    }
}

// 权限数组排序 二维数组 多维不起作用
function arr_sort($data)
{
    foreach ($data as $key => $row)
    {
        $sort[$key] = $row['sort'];
        $id[$key]  = $row['id'];
    }
    array_multisort($sort, SORT_ASC,$id, SORT_ASC,  $data);
    return $data;
}

//  退出是需要清除的信息
function out_clear_info($uid=null)
{
    $cookie_prefix=config('cookie.prefix');
    cookie(null,$cookie_prefix);
    session(null);
    if($uid){
        cache('login_time'.$uid,null);
    }
}

/**
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
 * @return string
 */
function rand_string($len=6,$type='',$addChars='') {
    $str ='';
    switch($type) {
        case 0:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 1:
            $chars= str_repeat('0123456789',3);
            break;
        case 2:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
            break;
        case 3:
            $chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 4:
            $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
            break;
        default :
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
            break;
    }
    if($len>10 ) {//位数过长重复字符串一定次数
        $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
    }
    if($type!=4) {
        $chars   =   str_shuffle($chars);
        $str     =   substr($chars,0,$len);
    } else{
        // 中文随机字
        for($i=0;$i<$len;$i++){
          $str.= msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
        }
    }
    return $str;
}

