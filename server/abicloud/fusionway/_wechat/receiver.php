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
    private static $db              = array('db_host'=> '127.0.0.1', 'db_user' => 'fusionway', 'db_pswd' => 'OBjhe7UF3IsMIwPK', 'db_char' => 'utf8', 'db_name' => 'fusionway_wechat', 'db_tpre' => 'fusionway_', 'db_port' => 3306);
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
    private static $appid           = 'wxbf643add612855f6';
    private static $appsecret       = '276e598506832f0a533360841068d7bf';
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
                
                $DB = new mysqli(self::$db['db_host'], self::$db['db_user'], self::$db['db_pswd'], self::$db['db_name'], self::$db['db_port']);
                $DB->connect_errno && exit('DB Connection Error.');
                $DB->query("SET character_set_connection=" . self::$db['db_char'] . ", character_set_results=" . self::$db['db_char'] . ", character_set_client=binary");
                
                $query = sprintf("insert into `%s%s` set `user`='fusionway', `dateline`='%s', `raw`='%s';", 
                    self::$db['db_tpre'], 
                    'logs', 
                    $DB->real_escape_string(date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])),
                    $DB->real_escape_string(trim(json_encode(self::$data)))
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die();
                self::$log_id = $DB->insert_id;
                
                $query = sprintf("insert into `%s%s` 
                    (`user`, `subscribe`, `log_id`, `dateline`, `ToUserName`, `FromUserName`, `CreateTime`, `MsgType`, `Event`, `EventKey`, `Ticket`) 
                    values 
                    ('%s', %d, %d, '%s', '%s', '%s', %d, '%s', '%s', '%s', '%s')
                    ON DUPLICATE KEY UPDATE 
                    `update`='%s', 
                    `subscribe`=%d
                    ;", 
                    self::$db['db_tpre'], 
                    'logs_subscribe', 
                    $DB->real_escape_string('fusionway'),
                    1, 
                    $DB->real_escape_string(self::$log_id),
                    $DB->real_escape_string(DATETIME),
                    $DB->real_escape_string(self::$data['TOUSERNAME']),
                    $DB->real_escape_string(self::$data['FROMUSERNAME']),
                    $DB->real_escape_string(self::$data['CREATETIME']),
                    $DB->real_escape_string(self::$data['MSGTYPE']),
                    $DB->real_escape_string(self::$data['EVENT']),
                    $DB->real_escape_string(self::$data['EVENTKEY']),
                    $DB->real_escape_string(self::$data['TICKET']), 
                    $DB->real_escape_string(DATETIME), 
                    1
                );
                $DB->query($query);
                $DB->errno > 0 && die();
                
                $content = '欢迎关注夜点促销员管理平台，请在［我的］－［绑定信息］中绑定您的信息。';
                break;
            case 'unsubscribe':
                
                $DB = new mysqli(self::$db['db_host'], self::$db['db_user'], self::$db['db_pswd'], self::$db['db_name'], self::$db['db_port']);
                $DB->connect_errno && exit('DB Connection Error.');
                $DB->query("SET character_set_connection=" . self::$db['db_char'] . ", character_set_results=" . self::$db['db_char'] . ", character_set_client=binary");
                
                $query = sprintf("insert into `%s%s` set `user`='fusionway', `dateline`='%s', `raw`='%s'", 
                    self::$db['db_tpre'], 
                    'logs', 
                    $DB->real_escape_string(date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])),
                    $DB->real_escape_string(trim(json_encode(self::$data)))
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die();
                self::$log_id = $DB->insert_id;
                
                $query = sprintf("insert into `%s%s` 
                    (`user`, `log_id`, `dateline`, `ToUserName`, `FromUserName`, `CreateTime`, `MsgType`, `Event`)
                    values
                    ('%s', %d, '%s', '%s', '%s', %d, '%s', '%s')
                    ON DUPLICATE KEY UPDATE 
                    `update`='%s'
                    ;", 
                    self::$db['db_tpre'], 
                    'logs_unsubscribe', 
                    $DB->real_escape_string('fusionway'),
                    $DB->real_escape_string(self::$log_id),
                    $DB->real_escape_string(DATETIME),
                    $DB->real_escape_string(self::$data['TOUSERNAME']),
                    $DB->real_escape_string(self::$data['FROMUSERNAME']),
                    $DB->real_escape_string(self::$data['CREATETIME']),
                    $DB->real_escape_string(self::$data['MSGTYPE']),
                    $DB->real_escape_string(self::$data['EVENT']), 
                    $DB->real_escape_string(DATETIME)
                );
                $DB->query($query);
                $DB->errno > 0 && die();
                
                $query = sprintf("update `%s%s` set `subscribe`=%d where `FromUserName`='%s' limit 1;", 
                    self::$db['db_tpre'], 
                    'logs_subscribe', 
                    0, 
                    $DB->real_escape_string(self::$data['FROMUSERNAME'])
                );
                $DB->query($query);
                $DB->errno > 0 && die();
                
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
                case 'A':
                    $content = "小编已经拿小本本记下啦，谢谢支持！祝你夜生活愉快哦！";
                break;
                case 'B':
                    $content = "小编已经拿小本本记下啦，谢谢支持！祝你夜生活愉快哦！";
                break;
                case 'C':
                    $content = "小编已经拿小本本记下啦，谢谢支持！祝你夜生活愉快哦！";
                break;
                case 'D':
                    $content = "小编已经拿小本本记下啦，谢谢支持！祝你夜生活愉快哦！";
                break;
                case 'E':
                    $content = "小编已经拿小本本记下啦，谢谢支持！祝你夜生活愉快哦！";
                break;
                case 'contactus':
                    $content = "Hello，欢迎关注我们！\n\n有关于夜点的问题或者建议就请留言吧，我们的客服代表会认真答复的！\n\n也欢迎你和小编互动，搞不好会有惊喜哦！\n\n祝你夜生活愉快！";
                break;
                default:
                    $bad    = array('差', '烂', '一般', '不好');
                    $good   = array('你好', 'Hi', 'hello');
/*
                    if() {
                        
                    }
*/
                    if(preg_match("/(abc|烂一般|不好)?/is", $keyword)) {
                        $content = "Hello，你的反馈我们已经收到啦！\n\n我们的客服代表会认真答复！请保持关注，这样你会看到在大家的帮助下我们的成长有多快！\n\n祝你今天有个好心情！";
                    }
                    if(preg_match("/(abc)?/is", $keyword)) {
                        $content = "Hello，你的留言我们已经收到啦！\n\n是不是想找人吹水？欢迎和小编互动，搞不好会有惊喜！\n\n祝你夜生活愉快哦！"; 
                    }
                    $content = "小编已经拿小本本记下啦，谢谢支持！祝你夜生活愉快哦！";
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