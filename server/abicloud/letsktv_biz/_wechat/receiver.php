<?php
date_default_timezone_set('Asia/Shanghai');
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

define("TOKEN", "ZwkQjKLZH7i8sn");
define('DATETIME', date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']));
//lieGHCDlpFKCLDwGvgzm5TXkVec77FrRQtKv6A0I7bd
// error_reporting(0);

new WeixinCallbackApi();

class WeixinCallbackApi {
/*
    private $db;
    private $db_pfix;
*/
    private static $log_id;
    private static $data            = array();
    private static $cat             = array(1, 2, 3, 4, 5, 16, 32, 34, 35, 93, 24, 92);
    //数据库连接信息
    private static $db              = array('db_host'=> '127.0.0.1', 'db_user' => 'letsktv_biz', 'db_pswd' => 'OBjhe7UF3IsMIwPK', 'db_char' => 'utf8', 'db_name' => 'letsktv_biz_wechat', 'db_tpre' => 'letsktv_biz_', 'db_port' => 3306);
    //全局xml头部
    private static $xml_head        = '<xml><ToUserName>%s</ToUserName><FromUserName>%s</FromUserName><CreateTime>%s</CreateTime><MsgType>%s</MsgType>';
    //文本类型的主体
    private static $xml_text_body   = '<Content>%s</Content>';
    //图文类型的头部
    private static $xml_news_head   = '<ArticleCount>%d</ArticleCount><Articles>';
    //图文主体
    private static $xml_news_body   = '<item><Title>%s</Title><Description>%s</Description><PicUrl>%s</PicUrl><Url>%s</Url></item>';
    //图文结尾
    private static $xml_news_foot   = '</Articles>';
    //音乐主题
    private static $xml_music_body  = '<Music><Title>%s</Title><Description>%s</Description><MusicUrl>%s</MusicUrl><HQMusicUrl>%s</HQMusicUrl></Music>';
    //全局尾部
    private static $xml_foot        = '<FuncFlag>%d</FuncFlag></xml>';
    //类型
    private static $type            = 'text';
    private static $mem             = '';
    
    /**
    * 服务号才会用到 申请到的appid 和 appsecret。
    * $accesstoken动态获取，有效期7200秒
    */
    private static $appid           = 'wxc5fd6e0da524eddd';
    private static $appsecret       = '547525a7637054d2681b19836bb2beeb';
    private static $accesstoken     = '';
    
    //构造函数
    public function __construct() {
        if(isset($_GET['echostr']) && self::valid()) {
            self::showmessage($_GET['echostr']);
        }
        
/*
        //初始化memcached
        self::$mem = new memcached();
        self::$mem->addServer('127.0.0.1', 11211);
*/

        //将xml转换为数组
        self::xml2array();
        
        if(in_array(($type=self::$data['MSGTYPE']), array('text', 'event', 'location', 'link', 'image')) ) {
            self::showmessage(self::$type());
        } else {
            self::showmessage('Unknown Event');
        }
    }

    /**
    * 接受事件，判断类型
    * 订阅号基本上只能用subscribe
    * 至于unsubscribe没什么大用，可以记录下那个取消关注
    */
    private static function event() {
        switch (self::$data['EVENT']) {
            case "subscribe":
                $content = htmlspecialchars("您好，欢迎关注夜点促销员管理平台！\n\n回复数字【8】可查看促销员最新奖励机制。");
                break;
            case 'unsubscribe':
                $content = '';
                break;
            case 'SCAN':
                $content = '';
                break;
            case 'VIEW':
                $content = '';
                break;
            default :
            case 'CLICK':
                $content = '';
                break;
            default :
                $content = '';
                break;
        }
        return self::transmit($content);
    }

    /**
    * 当前类型为文本型，需要判断用户所输入的内容，然后做出相应判断
    */
    private static function text() {
        $keyword = self::$data['CONTENT'];
        if(!empty($keyword)) {
            switch($keyword) {
                case '3':
                    $content = "更多功能，敬请期待。";
                break;
                case '8':
                    $content = htmlspecialchars("<a href=\"http://letsktv.chinacloudapp.cn/letsktv_biz/_wechat/index.php?m=history\">点击查看最新奖励机制</a>");
                break;
                case '码':
                case '二维码':
                    $content = htmlspecialchars("您可以点击公众号左下角的【我的二维码】来获取您的专属二维码。\n\n回复数字【8】可查看促销员最新奖励机制。");
                break;
                case '积分':
                case '对换':
                case '换':
                case '兑':
                case '兑换':
                    $content = htmlspecialchars("您可以点击公众号正下方的【积分兑换】来兑换您的奖品。\n\n回复数字【8】可查看促销员最新奖励机制。");
                break;
                case '业绩':
                    $content = htmlspecialchars("您可以点击公众号右下方的【我的】-【我的业绩】来查看您的业绩。\n\n回复数字【8】可查看促销员最新奖励机制。");
                break;
                case '绑':
                case '新增':
                case '添加':
                    $content = htmlspecialchars("您可以点击公众号右下方的【我的】-【信息绑定】来绑定您的信息。\n\n回复数字【8】可查看促销员最新奖励机制。");
                break;
                case '签到':
                    $content = htmlspecialchars("您可以点击公众号右下方的【我的】-【签到】进行签到。\n\n回复数字【8】可查看促销员最新奖励机制。");
                break;
                default:
                    $content = htmlspecialchars("您好，欢迎关注夜点促销员管理平台！\n\n回复数字【8】可查看促销员最新奖励机制。");
                break;
            }
            return self::transmit($content);
        } else {
            return "input something";
        }
    }

    /**
    * 接收用户发送过来的位置信息
    * 收到的字段主要有 MsgType、Location_X、Location_Y、Scale、Label
    */
    private static function location() {
/*
        $content = '北纬:'.self::$data['LOCATION_X']."\n东经:".self::$data['LOCATION_Y']."\n缩放系数".self::$data['SCALE']."\n详细地址:".self::$data['LABEL'];
        return self::transmit($content);
*/
    }

    /**
    * 发送一段音乐给用户
    * 需要提供的内容：title、description、musicurl、hqurl
    * 其中hqurl为高清音频链接，musicurl为低音质链接，wifi环境下有限使用高清音频
    */
    private static function music() {
/*
        self::$type = 'music';
        return array(
            'title'=> '光辉岁月', 
            'description'=>'《Band 5 - 世纪组合》', 
            'musicurl'=>'http://222.73.28.101:8000/live_proxy_dll?cmd=play&id=1043514230&start=2013-12-20_00:00:01&offset=0&rand=472576', 
            'hqurl'=>'http://api.jamendo.com/get2/stream/track/redirect/?id=1088938');
*/
    }

    /**
    * 接收用户发送过来的图片
    */
    private static function image() {
/*
        $content = self::$data['PICURL'] . self::$data['MEDIAID'] . self::$data['MSGTYPE'];
        self::transmit('已经收到了你的图片咯～');
*/
    }

    /**
    * 接收用户发送过来的链接
    */
    private static function link() {
/*
        $content = self::$data['TITLE'] . self::$data['DESCRIPTION'] . self::$data['URL'];
        self::transmit('已经收到咯～');
*/
    }

    /**
    * 格式化文本成为xml格式
    */
    private static function transmit($content, $flag = 0) {
        $format = '';
        $content = self::formatCode($content);
        if (is_array($content)) {
            switch(self::$type) {
                case 'news':
                    $news_body = '';
                    foreach($content as $v){
                         $news_body .= sprintf(self::$xml_news_body, $v['title'], $v['description'], $v['picUrl'], $v['url']);
                    }
                    $format = sprintf(self::$xml_head.self::$xml_news_head.$news_body.self::$xml_news_foot.self::$xml_foot, self::$data['FROMUSERNAME'], self::$data['TOUSERNAME'], time(), self::$type, count($content), $flag);
                break;
                case 'music':
                    $format = sprintf(self::$xml_head.self::$xml_music_body.self::$xml_foot, self::$data['FROMUSERNAME'], self::$data['TOUSERNAME'], time(), self::$type, $content['title'], $content['description'], $content['musicurl'], $content['hqurl'], $flag);
                break;
                default:
                    exit('type error');
                break;
            }
        } else {
            $format = sprintf(self::$xml_head.self::$xml_text_body.self::$xml_foot, self::$data['FROMUSERNAME'], self::$data['TOUSERNAME'], time(), self::$type, $content, $flag);
        }
        return $format;
    }

    /**
    * sprint 遇到%会出问题，所以需要转换
    */
    private static function formatCode($data) {
        if (is_array($data)) {
            return array_map('self::formatCode', $data);
        }
        return str_ireplace('%', '%%', $data);
    }

    /**
    * 从xml格式转换成数组
    */
    private static function xml2array() {
        $opened = array();
        $array = array();
        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : null, $xmlarray);
        $arrsize = sizeof($xmlarray);
        for( $j = 0; $j < $arrsize; $j++ ) {
            $val = $xmlarray[$j];
            switch($val["type"]){
                case "open":
                    $opened[$val["tag"]] = $array;
                    unset($array);
                    break;
                case "complete":
                    if(is_array($val["value"]) && count($val["value"])>1) {
                        $array[$val["tag"]][] = $val["value"];
                    } else {
                        $array[$val["tag"]] = trim($val["value"]);
                    }
                break;
                case "close":
                    $opened[$val["tag"]] = $array;
                    $array = $opened;
                break;
            }
        }
        self::$data = isset($array['XML']) ? $array['XML'] : null;
        unset($opened, $array, $xml_parser, $xmlarray, $arrsize, $j, $val);
    }

    /**
    * 验证URL有效性
    */
    private static function valid() {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];    
        
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    
    /**
    * 输出消息，并终止程序
    */
    private static function showmessage($message = '') {
        exit($message);
    }

    /**
    * 点击菜单拉取消息时的事件推送
    */
    private static function onClickMenu($key) {
/*
        switch($key) {
            case 'V1001_TODAY_MUSIC':
                $content = "你点的是今日歌曲";
            break;
            case 'V1001_TODAY_SINGER':
                $content = "你点的是歌手简介";
            break;
            case 'V1001_HELLO_WORLD':
                $content = "喔～你好～";
            break;
            case 'V1001_GOOD':
                $content = "非常赞";
            break;
            default:
                $content = "好像出了点什么错误哦";
            break;
        }
        return $content;
*/
    }

    /**
    * 点击菜单跳转链接时的事件推送
    */
    private static function onViewMenu($key) {
        
    }

    /**
    * 发送客服消息到用户
    * 订阅号没有此项功能
    */
    private static function sendMessageToUser($openid, $message, $type = 'text') {
/*
        if(!self::$accesstoken = self::$mem->get('access_token')) {
            if(($data = self::getAccessToken()) !== true) {
                self::showmessage($data);
            } else {
                self::$accesstoken = self::$mem->get('access_token');
            }
        }
        $postdata = '{"touser": "'.$openid.'", "msgtype": "'.$type.'", "text": { "content": "'.$message.'"} }';
        array('touser' => $openid, 'msgtype' => $type, 'text' => array('content'=> $message));
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.self::$accesstoken;
        $returndata = self::getUrl($url, $postdata, 'POST');
        if($returndata['errcode'] != 0) {
            self::showmessage($returndata['errmsg']);
        }
        return $returndata;
*/
    }

    /**
    * 获得AccessToken，并存放在memcached中
    * 普通订阅号没有此项功能
    */
    private static function getAccessToken() {
/*
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::$appid."&secret=".self::$appsecret;
        $data = self::getUrl($url);
        if($data['errcode']) {
            return $data['errmsg'];
        } else {
            self::$mem->set('access_token', $data['access_token'], time()+7200);
            self::$mem->set('token_expires', $data['expires_in'], time()+7200);
            return true;
        }
*/
    }

    /**
    * 用curl执行get或者post获得json数组
    */
    private static function getUrl($url, $data = array(), $method = 'GET') {
        $ch = curl_init();
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1); // 发送一个常规的Post请求
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        }
        curl_setopt($ch, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)'); // 模拟用户使用的浏览器
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    /**
    * 转换编码
    */
    private static function _gbk2utf8($data) {
        if (is_array($data)) {
            return array_map('self::_gbk2utf8', $data);
        }
        return mb_convert_encoding($data, "UTF-8", "GBK");
    }

    /**
    * 析构函数
    */
    public function __destruct() {
        self::$data             = NULL;
        self::$cat              = NULL;
        self::$xml_head         = NULL;
        self::$xml_text_body    = NULL;
        self::$xml_news_head    = NULL;
        self::$xml_news_body    = NULL;
        self::$xml_news_foot    = NULL;
        self::$xml_music_body   = NULL;
        self::$xml_foot         = NULL;
        self::$mem              = NULL;
        self::$type             = NULL;
    }
}