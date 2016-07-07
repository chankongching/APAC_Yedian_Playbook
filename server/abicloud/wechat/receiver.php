<?php
date_default_timezone_set('Asia/Shanghai');
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

define("TOKEN", "ZwkQjKLZH7i8sn");
define('TIME', $_SERVER['REQUEST_TIME']);
define('DATETIME', date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']));
//PrspL33OlzgKUTafBpH16FRckaEOkwvqwWf9WJBXCnG
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
    private static $db              = array('db_host'=> '127.0.0.1', 'db_user' => 'letsktv', 'db_pswd' => 'OBjhe7UF3IsMIwPK', 'db_char' => 'utf8', 'db_name' => 'letsktv_wechat', 'db_tpre' => 'letsktv_', 'db_port' => 3306);
    //全局xml头部
    private static $xml_head        = '<xml><ToUserName>%s</ToUserName><FromUserName>%s</FromUserName><CreateTime>%s</CreateTime><MsgType>%s</MsgType>';
    //文本类型的主体
    private static $xml_text_body   = '<Content>%s</Content>';
    private static $xml_imge_body   = '<MsgType>%s</MsgType><Image><MediaId>%s</MediaId></Image>';
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
    private static $appid           = 'wx90f8e48d4b4f5d8d';
    private static $appsecret       = 'f8a6976a35feec19edf899daefd4d59a';
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
                
                $query = sprintf("insert into `%s%s` set `user`='letsktv', `dateline`='%s', `raw`='%s';", 
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
                    $DB->real_escape_string('letsktv'),
                    1, 
                    $DB->real_escape_string(self::$log_id),
                    $DB->real_escape_string(DATETIME),
                    $DB->real_escape_string(self::$data['TOUSERNAME']),
                    $DB->real_escape_string(self::$data['FROMUSERNAME']),
                    $DB->real_escape_string(self::$data['CREATETIME']),
                    $DB->real_escape_string(self::$data['MSGTYPE']),
                    $DB->real_escape_string(self::$data['EVENT']),
                    $DB->real_escape_string(self::$data['EVENTKEY']),
                    isset(self::$data['TICKET']) ? $DB->real_escape_string(self::$data['TICKET']) : '', 
                    $DB->real_escape_string(DATETIME), 
                    1
                );
                $DB->query($query);
                $DB->errno > 0 && die();
                
                if(isset(self::$data['TICKET']) && self::$data['TICKET'] == 'gQEB8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL21UbURQdGpsMXdtdWRMTWFwQlZYAAIE7YtqVgMEAAAAAA==') {
                    $content = "终于等到你~还好我没放弃~欢迎关注【夜点娱乐】！\n\n“我是估歌王”活动火热进行中！活动截止1月5日中午12点，中奖名单将在1月10日前公布。\n\n来夜点，你可以一键预订广州KTV，我们的口号是：“要派对，不排队”！\n\n来【夜点娱乐】，绝对有你想要的！";
                } elseif(isset(self::$data['TICKET'])) {
                    $handle = fopen('/tmp/fromticketto.log', 'a');
                    $query = sprintf("select 1, `update` from `%s%s` where `FromUserName` = '%s' limit 1;", 
                        self::$db['db_tpre'], 
                        'logs_subscribe', 
                        $DB->real_escape_string(self::$data['FROMUSERNAME'])
                    );
                    $source = $DB->query($query);
                    if ($source->num_rows > 0) {
                        while($row = $source->fetch_assoc()) {
                            $update = $row['update'];
                        }
                        $query = sprintf("select `id`, `detail`, `openid` as `from`, `scaned` from `%s%s` where `ticket` = '%s' limit 1;", 
                            self::$db['db_tpre'], 
                            'qrcodes', 
                            $DB->real_escape_string(self::$data['TICKET'])
                        );
                        $source = $DB->query($query);
                        if ($source->num_rows > 0) {
                            while ($row = $source->fetch_assoc()) {
                                $url    = $row['detail'];
                                $from   = $row['from'];
                                $to     = self::$data['FROMUSERNAME'];
                                $tid    = $row['id'];
                                $scaned = intval($row['scaned']);
                            }
                            $query = sprintf("update `%s%s` set `scaned` = `scaned` + 1 where `id` = %d limit 1;", 
                                self::$db['db_tpre'], 
                                'qrcodes', 
                                $tid
                            );
                            $DB->query($query);
                            
                            if(filter_var($url, FILTER_VALIDATE_URL) && $from != $to && empty($update)) {
                                // 扫码邀请加积分
                                fwrite($handle, "url: ".$url."\tfrom: ".$from."\tto: ".$to."\tticket: ".self::$data['TICKET']."\n");
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 2);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                  "X-KTV-Application-Name: eec607d1f47c18c9160634fd0954da1a",
                                  "X-KTV-Vendor-Name: 1d55af1659424cf94d869e2580a11bf8",
                                  'Content-Type:application/json'
                                 ]
                                );
                                $json_array = [
                                    "to" => $to,
                                    "time" => TIME,
                                    "from" => $from
                                ]; 
                                $body = json_encode($json_array);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                                $resp = curl_exec($ch);
                                fwrite($handle, $resp."\n");
                                curl_close($ch);
                            } elseif(stristr($url, 'KTV促销员') !== false && empty($update) && $scaned < 1) {
                                // 促销员邀请
                                //fwrite($handle, $url."\n");
                                $url = explode('|', $url);
                                $info = isset($url[2]) ? $url[2] : false;
                                $url = $url[1];
                                $url = json_decode($url, true);
                                //fwrite($handle, print_r($url, true)."\n");
                                if($url && is_array($url) && !empty($url) && TIME >= $url['dateline_start'] && TIME <= $url['dateline_expire']) {
                                    $query = sprintf("insert into `%s%s` (`openid`, `ticket`, `count`) values ('%s', '%s', 1) on duplicate key update `count` = `count` + 1;", 
                                        self::$db['db_tpre'], 
                                        'promogirls_scan_log', 
                                        $from, 
                                        self::$data['TICKET']
                                    );
                                    //fwrite($handle, $query."\n");
                                    $DB->query($query);
                                    fwrite($handle, "url: http://letsktv.chinacloudapp.cn/user/PointAdd\tfrom: KTV促销员\tto: ".$to."\tticket: ".self::$data['TICKET']."\n");
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, 'http://letsktv.chinacloudapp.cn/user/PointAdd');
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                      "X-KTV-Application-Name: eec607d1f47c18c9160634fd0954da1a",
                                      "X-KTV-Vendor-Name: 1d55af1659424cf94d869e2580a11bf8",
                                      'Content-Type:application/json'
                                     ]
                                    );
                                    $json_array = [
                                        "to" => $to,
                                        "time" => TIME
                                    ]; 
                                    $body = json_encode($json_array);
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                                    $resp = curl_exec($ch);
                                    fwrite($handle, $resp."\n");
                                    curl_close($ch);
                                    unset($ch);
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, 'http://letsktv.chinacloudapp.cn/letsktv_biz/promo_girls/api.php?ticket='.self::$data['TICKET']);
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    $resp = curl_exec($ch);
                                    curl_close($ch);
                                    if($info) {
                                        $__openid = self::$data['FROMUSERNAME'];
                                        $__url = 'http://letsktv.chinacloudapp.cn/wechat_ktv/Home/WeChatApi/getOpenidStatus?openid='.$__openid.'&scanid='.$info;
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $__url);
                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        $resp = curl_exec($ch);
                                        !$resp && die();
                                        curl_close($ch);
                                        $resp = json_decode($resp, true);
                                        
                                        if(is_array($resp) && !empty($resp)) {
                                            self::$type = 'news';
                                            $content[] = $resp['news'];
                                        }
                                    }
                                    unset($info);
                                }
                            } elseif(stristr($url, '20160511Wechat') !== false || stristr($url, '20160513_') !== false) {
                                $query = sprintf("update `%s%s` set `scaned` = `scaned` + 1 where `id` = %d limit 1;", 
                                    self::$db['db_tpre'], 
                                    'qrcodes', 
                                    $tid
                                );
                                $DB->query($query);
                                
                                $info = explode('_', $url);
                                self::$type = 'news';
                                
                                if(stristr($url, '20160511Wechat') !== false) {
                                    $param = $info[1];
                                }
                                if(stristr($url, '20160513_') !== false) {
                                    $param = 'event_djiuq';
                                }
                                
                                $__openid = self::$data['FROMUSERNAME'];
                                $__url = 'http://letsktv.chinacloudapp.cn/wechat_ktv/Home/WeChatApi/getOpenidStatus?openid='.$__openid.'&scanid='.$param;
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $__url);
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                $resp = curl_exec($ch);
                                !$resp && die();
                                curl_close($ch);
                                $resp = json_decode($resp, true);
                                $content[] = $resp['news'];
                                
                                unset($info, $param);
                            }
                        }
                    }
                    fclose($handle);
                }
                
                if(!isset($content)) {
                    $content = htmlspecialchars("终于等到你~还好我没放弃~\nHello~欢迎关注【夜点娱乐】，我是KTV达人小夜！\n\n在夜点您可以：\n
<a href=\"http://letsktv.chinacloudapp.cn/wechat_ktv/home/event/enter\">领取免费兑酒券</a>\n\n<a href=\"http://letsktv.chinacloudapp.cn/wechat/index.php?m=oneyuan\">夜点0元秒杀</a>\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/ktv/\">一键预订广州KTV</a>\n\n您还可以尝试着跟小夜对话哦，想预订哪家KTV？告诉我就好~\n");
                }
                break;
            case 'unsubscribe':
                
                $DB = new mysqli(self::$db['db_host'], self::$db['db_user'], self::$db['db_pswd'], self::$db['db_name'], self::$db['db_port']);
                $DB->connect_errno && exit('DB Connection Error.');
                $DB->query("SET character_set_connection=" . self::$db['db_char'] . ", character_set_results=" . self::$db['db_char'] . ", character_set_client=binary");
                
                $query = sprintf("insert into `%s%s` set `user`='letsktv', `dateline`='%s', `raw`='%s'", 
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
                    $DB->real_escape_string('letsktv'),
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
                
                exit();
                break;
            case 'TEXT':
                $content = '';
                break;
            case 'SCAN':
                
                $DB = new mysqli(self::$db['db_host'], self::$db['db_user'], self::$db['db_pswd'], self::$db['db_name'], self::$db['db_port']);
                $DB->connect_errno && exit('DB Connection Error.');
                $DB->query("SET character_set_connection=" . self::$db['db_char'] . ", character_set_results=" . self::$db['db_char'] . ", character_set_client=binary");
                
                $query = sprintf("insert into `%s%s` set `user`='letsktv', `dateline`='%s', `raw`='%s';", 
                    self::$db['db_tpre'], 
                    'logs', 
                    $DB->real_escape_string(date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])),
                    $DB->real_escape_string(trim(json_encode(self::$data)))
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die();
                self::$log_id = $DB->insert_id;
                
                if(isset(self::$data['TICKET']) && self::$data['TICKET'] == 'gQHt7zoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL0JqbnhtZTNsdlFuRWVTeWwxaFZYAAIE9ItqVgMEAAAAAA==') {
                    self::$type = 'news';
                    $content[] = array(
                        'title' => '全城悬赏“超级估歌王”，赏金等你拿！', 
                        'description'=>'', 
                        'picUrl'=>'https://mmbiz.qlogo.cn/mmbiz/TAQPDicjviavTC6ffgqeHyVbicJDgxuHjbFicr18meUjrTDiaR3X9nBRHVOyic1DwJDLvrt11ibbhr3dxuLaPflRR4dBA/0?wx_fmt=jpeg', 
                        'url'=>'http://letsktv.chinacloudapp.cn/games/GUESS_SONG'
                    );
                } elseif(isset(self::$data['TICKET']) && self::$data['TICKET'] == 'gQEB8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL21UbURQdGpsMXdtdWRMTWFwQlZYAAIE7YtqVgMEAAAAAA==') {
                    $content = "终于等到你~还好我没放弃~欢迎关注【夜点娱乐】！\n\n“我是估歌王”活动火热进行中！活动截止1月5日中午12点，中奖名单将在1月10日前公布。\n\n来夜点，你可以一键预订广州KTV，我们的口号是：“要派对，不排队”！\n\n来【夜点娱乐】，绝对有你想要的！";
                } else {
                    $query = sprintf("select `id`, `detail`, `openid` as `from`, `scaned` from `%s%s` where `ticket` = '%s' limit 1;", 
                        self::$db['db_tpre'], 
                        'qrcodes', 
                        $DB->real_escape_string(self::$data['TICKET'])
                    );
                    $source = $DB->query($query);
                    if ($source->num_rows > 0) {
                        while ($row = $source->fetch_assoc()) {
                            $url        = $row['detail'];
                            $tid        = $row['id'];
                            $from       = $row['from'];
                            $scaned     = intval($row['scaned']);
                        }
                        $query = sprintf("update `%s%s` set `scaned` = `scaned` + 1 where `id` = %d limit 1;", 
                            self::$db['db_tpre'], 
                            'qrcodes', 
                            $tid
                        );
                        $DB->query($query);
                        if(stristr($url, 'KTV促销员') !== false && empty($update) && $scaned < 1) {
                            // 促销员推销
                            $url = explode('|', $url);
                            $info = isset($url[2]) ? $url[2] : false;
                            $url = $url[1];
                            $url = json_decode($url, true);
                            if($url && is_array($url) && !empty($url) && TIME >= $url['dateline_start'] && TIME <= $url['dateline_expire']) {
                                $query = sprintf("insert into `%s%s` (`openid`, `ticket`, `count`) values ('%s', '%s', 1) on duplicate key update `count` = `count` + 1;", 
                                    self::$db['db_tpre'], 
                                    'promogirls_scan_log', 
                                    $from, 
                                    self::$data['TICKET']
                                );
                                $DB->query($query);
                                
/*
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'http://letsktv.chinacloudapp.cn/user/PointAdd');
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 2);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                  "X-KTV-Application-Name: eec607d1f47c18c9160634fd0954da1a",
                                  "X-KTV-Vendor-Name: 1d55af1659424cf94d869e2580a11bf8",
                                  'Content-Type:application/json'
                                 ]
                                );
                                $json_array = [
                                    "to" => $to,
                                    "time" => TIME
                                ]; 
                                $body = json_encode($json_array);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                                $resp = curl_exec($ch);
                                fwrite($handle, $resp."\n");
                                curl_close($ch);
                                unset($ch);
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'http://letsktv.chinacloudapp.cn/letsktv_biz/promo_girls/api.php?ticket='.self::$data['TICKET']);
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                $resp = curl_exec($ch);
                                curl_close($ch);
*/
                                if($info) {
                                    $__openid = self::$data['FROMUSERNAME'];
                                    $__url = 'http://letsktv.chinacloudapp.cn/wechat_ktv/Home/WeChatApi/getOpenidStatus?openid='.$__openid.'&scanid='.$info;
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $__url);
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    $resp = curl_exec($ch);
                                    !$resp && die();
                                    curl_close($ch);
                                    $resp = json_decode($resp, true);
                                    
                                    if(is_array($resp) && !empty($resp)) {
                                        self::$type = 'news';
                                        $content[] = $resp['news'];
                                    }
                                }
                                unset($info);
                            }
                        } elseif(stristr($url, '20160511Wechat') !== false || stristr($url, '20160513_') !== false) {
                            $info = explode('_', $url);
                            self::$type = 'news';
                            
                            if(stristr($url, '20160511Wechat') !== false) {
                                $param = $info[1];
                            }
                            if(stristr($url, '20160513_') !== false) {
                                $param = 'event_djiuq';
                            }
                            
                            $__openid = self::$data['FROMUSERNAME'];
                            $__url = 'http://letsktv.chinacloudapp.cn/wechat_ktv/Home/WeChatApi/getOpenidStatus?openid='.$__openid.'&scanid='.$param;
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $__url);
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $resp = curl_exec($ch);
                            
                            if(!$resp) {
                              die();
                            }
                            curl_close($ch);
                            
                            $resp = json_decode($resp, true);
                            $content[] = $resp['news'];
                            
                            unset($info, $param, $__openid, $__url);
                        }
                    }
                }
                
                if(!isset($content)) {
                    $content = "终于等到你~还好我没放弃~欢迎关注【夜点娱乐】！\n\n在这里你可以一键预订广州KTV，我们的口号是：“要派对，不排队”！参与活动获得夜点积分，可以兑换iPhone等惊喜好礼哦~\n\n我们会精选广州KTV优惠资讯、夜生活故事等精彩内容，“夜蒲达人”们不要错过哦！\n\n来【夜点娱乐】，绝对有你想要的！\n\n".htmlspecialchars('免费夜点兑酒券疯抢中，新老用户均可领取！即刻通过夜点<a href="http://letsktv.chinacloudapp.cn/dist/#!/ktv/">预定K房</a>并到店即可当场兑换啤酒！<a href="http://letsktv.chinacloudapp.cn/wechat_ktv/home/event/enter">点此领取夜点兑酒券</a>');
                }
                break;
            case 'LOCATION':
                self::location();
                $content = '';
                break;
            default :
            case 'VIEW':
                $content = '';
                break;
            default :
            case 'CLICK':
                $content = "Hello，欢迎关注夜点娱乐！\n\n有关于夜点的问题或者建议请您留言，我们的客服代表会认真答复的！\n\n和小编互动，搞不好还会有意外惊喜哦！\n\n也可以拨打电话 4006507351 联系我们哦~\n\n用夜点预订K房，惊喜不间断！";
                break;
            default :
                $content = '';
                break;
        }
        $content =  isset($content) ? $content : '';
        return self::transmit($content);
    }

    /**
    * 当前类型为文本型，需要判断用户所输入的内容，然后做出相应判断
    */
    private static function text() {
        
        $DB = new mysqli(self::$db['db_host'], self::$db['db_user'], self::$db['db_pswd'], self::$db['db_name'], self::$db['db_port']);
        $DB->connect_errno && exit('DB Connection Error.');
        $DB->query("SET character_set_connection=" . self::$db['db_char'] . ", character_set_results=" . self::$db['db_char'] . ", character_set_client=binary");
        
        $query = sprintf("insert into `%s%s` set `user`='letsktv', `dateline`='%s', `raw`='%s';", 
            self::$db['db_tpre'], 
            'logs', 
            $DB->real_escape_string(date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])),
            $DB->real_escape_string(trim(json_encode(self::$data)))
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die();
        self::$log_id = $DB->insert_id;
        
        $keyword = (string) self::$data['CONTENT'];
        if(trim($keyword) != '') {
            switch($keyword) {
                case 'contactus':
                    $content = "Hello，欢迎关注夜点娱乐！\n\n有关于夜点的问题或者建议请您留言，我们的客服代表会认真答复的！\n\n和小编互动，搞不好还会有意外惊喜哦！\n\n也可以拨打电话 4006507351 联系我们哦~\n\n用夜点预订K房，惊喜不间断！";
                break;
                case 'GGK':
                    self::$type = 'news';
                    $content[] = array(
                        'title' => '全城悬赏“超级估歌王”，赏金等你拿！', 
                        'description'=>'全城悬赏“超级估歌王”，赏金等你拿！全城悬赏“超级估歌王”，赏金等你拿！全城悬赏“超级估歌王”，赏金等你拿！全城悬赏“超级估歌王”，赏金等你拿！全城悬赏“超级估歌王”，赏金等你拿！全城悬赏“超级估歌王”，赏金等你拿！全城悬赏“超级估歌王”，赏金等你拿！全城悬赏“超级估歌王”，赏金等你拿！全城悬赏“超级估歌王”，赏金等你拿！全城悬赏“超级估歌王”，赏金等你拿！', 
                        'picUrl'=>'https://mmbiz.qlogo.cn/mmbiz/TAQPDicjviavTC6ffgqeHyVbicJDgxuHjbFicr18meUjrTDiaR3X9nBRHVOyic1DwJDLvrt11ibbhr3dxuLaPflRR4dBA/0?wx_fmt=jpeg'
                    );
                break;
                default:
                    $_content = false;
                    
                    if(!$_content) {
                        if($keyword == '0') {
                            $_content = "您好，小夜正在娇羞地赶来，请您稍作等待哦~\n\n您也可以拨打客服电话400-650-7351咨询您的问题，感谢您对小夜的支持与理解！";
                        }
                    }
                    
                    if(!$_content) {
                        $keywords = array('预', '约', '订', '定', '房', '包厢', '包间', 'KTV', '唱K');
                        foreach($keywords as $match) {
                            if(stristr($keyword, $match) !== FALSE) {
                                $_content = htmlspecialchars("Hello，超开心您来关注夜点娱乐！KTV预订，您可以点击下方链接，也可以点击公众号左下角的【夜点娱乐】进行预订哦~\n用夜点预订KTV，惊喜不间断！\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/ktv/\">点此进行KTV预订</a>\n\n免费夜点兑酒券疯抢中，新老用户均可领取！即刻通过夜点预订K房并到店即可当场兑换啤酒！<a href=\"http://letsktv.chinacloudapp.cn/wechat_ktv/home/event/enter\">点此领取夜点兑酒券</a>\n\n【没有解决您的问题？您还可以回复数字0，呼唤小夜来为您解答~】");
                            }
                        }
                        unset($keywords);
                    }
                    
                    if(!$_content) {
                        $keywords = array('流量');
                        foreach($keywords as $match) {
                            if(strstr($keyword, $match) !== FALSE) {
                                $_content = htmlspecialchars("Hello，超开心您来关注夜点娱乐！目前流量包是可以用您的积分兑换，积分可以在夜点不定期设置的参与互动送积分活动中获得哦！\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/store/P0000001578\">点击兑换移动30MB流量包</a>\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/store/P0000001580\">点击兑换电信30MB流量包</a>\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/store/P0000001579\">点击兑换联通50MB流量包</a>\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/store\">点击进入积分兑换页面</a>\n\n1元抢黄金档4小时欢唱，还送20罐百威，果盘及小食若干，不醉不归哦！<a href=\"http://letsktv.chinacloudapp.cn/wechat/index.php?m=oneyuan\">点我抢</a>\n\n【没有解决您的问题？您还可以回复数字0，呼唤小夜来为您解答~】");
                            }
                        }
                        unset($keywords);
                    }
                    
                    if(!$_content) {
                        $keywords = array('优惠', '活动', '赠');
                        foreach($keywords as $match) {
                            if(stristr($keyword, $match) !== FALSE) {
                                $_content = htmlspecialchars("您好，感谢您对小夜的关注哦！\n\n目前免费夜点兑酒券正在疯抢中，新老用户均可领取！即刻通过夜点预订K房并到店即可当场兑换啤酒！<a href=\"http://letsktv.chinacloudapp.cn/wechat_ktv/home/event/enter\">点此领取夜点兑酒券</a>\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/events\">点击查看最新精彩活动</a>\n\n【没有解决您的问题？您还可以回复数字0，呼唤小夜来为您解答~】");
                            }
                        }
                        unset($keywords);
                    }
                    
                    if(!$_content) {
                        $keywords = array('wifi', 'wi-fi', '无线', '密码', '密码', '上网');
                        foreach($keywords as $match) {
                            if(stristr($keyword, $match) !== FALSE) {
                                $_content = htmlspecialchars("您好，感谢对夜点的关注！Wi-Fi密码您可以询问所在KTV的工作人员哦，祝您玩的开心！\n\n免费夜点兑酒券疯抢中，新老用户均可领取！即刻通过夜点预订K房并到店即可当场兑换啤酒！<a href=\"http://letsktv.chinacloudapp.cn/wechat_ktv/home/event/enter\">点此领取夜点兑酒券</a>\n\n
【没有解决您的问题？您还可以回复数字0，呼唤小夜来为您解答~】");
                            }
                        }
                        unset($keywords);
                    }
                    
                    if(!$_content) {
                        $keywords = array('积分', '礼', '奖', '红包');
                        foreach($keywords as $match) {
                            if(stristr($keyword, $match) !== FALSE) {
                                $_content = htmlspecialchars("目前在小夜为您推送的精彩内容中，会不定期设置一些送积分的活动呦，积分可以兑换iPhone等惊喜好礼，记得来参加哦！\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/store\">点击进入积分兑换页面</a>\n\n1元抢黄金档4小时欢唱，还送20罐百威，果盘及小食若干，不醉不归哦！<a href=\"http://letsktv.chinacloudapp.cn/wechat/index.php?m=oneyuan\">点我抢</a>\n\n【没有解决您的问题？您还可以回复数字0，呼唤小夜来为您解答~】");
                            }
                        }
                        unset($keywords);
                    }
                    
                    if(!$_content) {
                        $keywords = array('邀请', '好友', '朋友', '二维码', '扫码', '关注');
                        foreach($keywords as $match) {
                            if(stristr($keyword, $match) !== FALSE) {
                                $_content = htmlspecialchars("您好！想要邀请好友关注么？只需要在【个人中心】页面右上方的找到【邀请好友】按钮，然后按照提示推荐好友关注夜点娱乐就好啦~\n\n
<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/user\">点此进入个人中心</a>\n\n1元抢黄金档4小时欢唱，还送20罐百威，果盘及小食若干，不醉不归哦！<a href=\"http://letsktv.chinacloudapp.cn/wechat/index.php?m=oneyuan\">点我抢</a>\n\n【没有解决您的问题？您还可以回复数字0，呼唤小夜来为您解答~】");
                            }
                        }
                        unset($keywords);
                    }
                    
                    if(!$_content) {
                        $keywords = array('订', '定', 'KTV', 'ktv', '唱歌', '唱k', '唱K', 'K歌', 'k歌');
                        foreach($keywords as $match) {
                            if(stristr($keyword, $match) !== FALSE) {
                                $_content = htmlspecialchars("Hello，超开心您来关注夜点娱乐！KTV预订，您可以点击下方链接，也可以点击公众号左下角的【立即预订】进行预订哦~\n\n用夜点预订KTV，惊喜不间断！\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/ktv/\">点此进行KTV预订</a>\n\n免费夜点兑酒券疯抢中，新老用户均可领取！即刻通过夜点预订K房并到店即可当场兑换啤酒！<a href=\"http://letsktv.chinacloudapp.cn/wechat_ktv/home/event/enter\">点此领取夜点兑酒券</a>\n\n【没有解决您的问题？您还可以回复数字0，呼唤小夜来为您解答~】");
                            }
                        }
                    }
                    
                    if(!$_content) {
                        $keywords =  array('兑', '对', '券', '卷', '酒', '送', '领', '用', '核销');
                        foreach($keywords as $match) {
                            if(stristr($keyword, $match) !== FALSE) {
                                $_content = htmlspecialchars("免费夜点兑酒券疯抢中，新老用户均可领取哦！即刻通过夜点预订K房并到店即可当场兑换啤酒！\n\n<a href=\"http://letsktv.chinacloudapp.cn/wechat_ktv/home/event/enter\">点此领取夜点兑酒券</a>\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/coupon\">点此查看您的兑酒券</a>\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/ktv/\">点此使用兑酒券预订KTV</a>\n\n【没有解决您的问题？您还可以回复数字0，呼唤小夜来为您解答~】");
                            }
                        }
                    }
                    
                    if(!$_content) {
                        $keywords =  array('0元', '秒杀', '派对', '黄金', '套餐', '抢');
                        foreach($keywords as $match) {
                            if(stristr($keyword, $match) !== FALSE) {
                                $_content = htmlspecialchars("夜点0元秒杀4小时欢唱，还送20罐百威，果盘及小食若干，不醉不归哦！<a href=\"http://letsktv.chinacloudapp.cn/wechat/index.php?m=oneyuan\">点我抢</a>\n\n【没有解决您的问题？您还可以回复数字0，呼唤小夜来为您解答~】");
                            }
                        }
                    }
                    
                    if(!$_content) {
                        $__ktvs = self::ktvs();
                        foreach($__ktvs as $k=>$v) {
                            if(stristr($k, $keyword) !== FALSE) {
                                $_content_data[] = ($v);
                            }
                        }
                        unset($k, $v);
                        if(is_array($_content_data) && !empty($_content_data)) {
                            $_content = htmlspecialchars('免费夜点兑酒券疯抢中，新老用户均可领取！即刻通过夜点<a href="http://letsktv.chinacloudapp.cn/dist/#!/ktv/">预定K房</a>并到店即可当场兑换啤酒！<a href="http://letsktv.chinacloudapp.cn/wechat_ktv/home/event/enter">点此领取夜点兑酒券</a>'."\n点击进入KTV预定快速通道：\n\n");
                            foreach($_content_data as $k=>$v) {
                                $_content .= htmlspecialchars('<a href="http://letsktv.chinacloudapp.cn/dist/#!/ktv/'.$v[0].'">'.($k+1).'. '.$v[1].'</a>')."\n\n";
                            }
                            $_content .= htmlspecialchars('<a href="http://letsktv.chinacloudapp.cn/dist/#!/ktv/">'.($k+2).'. 更多附近KTV</a>');
                        }
                        unset($k, $v);
                    }
                    
                    if($_content === false) {
                        $content = htmlspecialchars("您好，超开心您来关注夜点娱乐！在这里您可以：\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/ktv/\">预订KTV</a>\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/events\">查看最新精彩活动</a>\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/store\">进行积分兑换</a>\n\n<a href=\"http://letsktv.chinacloudapp.cn/dist/#!/user\">进入您的个人中心</a>\n\n<a href=\"http://letsktv.chinacloudapp.cn/wechat/index.php?m=history\">查看更多精彩内容</a>\n\n【没有解决您的问题？您还可以回复数字0，呼唤小夜来为您解答~】");
                    } else {
                        $content = $_content;
                    }
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
        $DB = new mysqli(self::$db['db_host'], self::$db['db_user'], self::$db['db_pswd'], self::$db['db_name'], self::$db['db_port']);
        $DB->connect_errno && exit('DB Connection Error.');
        $DB->query("SET character_set_connection=" . self::$db['db_char'] . ", character_set_results=" . self::$db['db_char'] . ", character_set_client=binary");
        
        $query = sprintf("insert into `%s%s` set `user`='letsktv', `dateline`='%s', `raw`='%s';", 
            self::$db['db_tpre'], 
            'logs', 
            $DB->real_escape_string(date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])),
            $DB->real_escape_string(trim(json_encode(self::$data)))
        );
        $source = $DB->query($query);
*/

        if(self::$data) {
            $url = sprintf("http://letsktv.chinacloudapp.cn/wechat_ktv/Home/WeChat/saveGeo?lat=%.6f&lng=%.6f&openid=%s", 
                self::$data['LATITUDE'], 
                self::$data['LONGITUDE'], 
                self::$data['FROMUSERNAME']
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($ch);
            curl_close($ch);
        }
        return self::transmit('');
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
                         $news_body .= sprintf(self::$xml_news_body, $v['title'], $v['description'] ? $v['description'] : '', $v['picUrl'], $v['url']);
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
    private static function showmessage($message = 'success') {
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
    
    public static function ktvs() {
        return json_decode('{"\u5e7f\u84c4\u4e13\u5bb6\u6751":["XKTV00037","\u5e7f\u84c4\u7535\u7ad9\u4e13\u5bb6\u6751\u9152\u5e97"],"\u661f\u8f89":["XKTV00038","\u661f\u8f89KTV"],"\u559c\u6ee1\u5802":["XKTV00039","\u559c\u6ee1\u5802KTV"],"\u62c9\u6590\u6b4c":["XKTV00040","\u62c9\u6590\u6b4cKTV"],"\u7545\u60f3\u56fd\u5ea6":["XKTV00041","\u7545\u60f3\u56fd\u5ea6(\u4ece\u5316\u5e97)"],"\u9038\u6cc9\u56fd\u9645\u5927":["XKTV00046","\u9038\u6cc9\u56fd\u9645\u5927\u9152\u5e97"],"\u51b2\u51fb\u6ce2\u9152\u57ce":["XKTV00047","\u51b2\u51fb\u6ce2\u9152\u57ceKTV"],"\u9f99\u6c5f":["XKTV00048","\u9f99\u6c5fKTV"],"\u7fe0\u5c9b\u5ea6\u5047\u6751":["XKTV00049","\u7fe0\u5c9b\u5ea6\u5047\u6751"],"\u559c\u6765\u7545":["XKTV00050","\u559c\u6765\u7545\u91cf\u8d29\u5f0fKTV"],"\u5bb6\u4e50\u8fea":["XKTV00008","\u5bb6\u4e50\u8fea\u91cf\u8d29\u5f0fKTV(\u9ec4\u9601\u5e97)"],"\u80dc\u9f99\u5bcc\u6c27":["XKTV00031","\u80dc\u9f99\u5bcc\u6c27\u91cf\u8d29KTV(\u949f\u6751\u8857\u5e97)"],"\u597d\u58f0\u97f3":["XKTV00067","\u597d\u58f0\u97f3(\u82b1\u90fd\u5e97)"],"\u540c\u5e86\u6b4c\u4f1a":["XKTV00011","\u540c\u5e86\u6b4c\u4f1a\u91cf\u8d29\u5f0fKTV"],"\u5b9d\u4e50\u8fea":["XKTV00027","\u5b9d\u4e50\u8fea\u91cf\u8d29KTV(\u756a\u79ba\u5e97)"],"\u7f18\u4e50\u8fea":["XKTV00052","\u7f18\u4e50\u8fea\u91cf\u8d29\u5f0fKTV"],"KING PARTY":["XKTV00053","KING PARTY\u91cf\u8d29\u5f0fKTV(\u4e1c\u6c47\u57ce\u5e97) "],"\u9b45\u529b\u6d3e\u5bf9":["XKTV00056","\u9b45\u529b\u6d3e\u5bf9\u91cf\u8d29\u5f0fKTV(\u65b0\u5858\u5e97) "],"\u6e29\u838e":["XKTV00058","\u6e29\u838eKTV"],"\u91d1\u551b":["XKTV00059","\u91d1\u551b\u91cf\u8d29\u5f0fKTV"],"\u51ef\u4e50\u6c47":["XKTV00060","\u51ef\u4e50\u6c47KTV\u91cf\u8d29\u5f0f "],"\u4e50\u9ea6":["XKTV00061","\u4e50\u9ea6\u91cf\u8d29\u5f0fKTV "],"\u559c\u805a":["XKTV00183","\u559c\u805a"],"\u5802\u4f1a\u5929\u6cb3":["XKTV00184","\u5802\u4f1a(\u5929\u6cb3\u5e97)"],"\u5929\u9f99\u6b4c\u6c47":["XKTV00185","\u5929\u9f99\u6b4c\u6c47\u91cf\u8d29\u5f0fKTV(\u4f53\u80b2\u4e2d\u5fc3\u5e97)"],"\u5323\u5b50KBOX":["XKTV00186","\u5323\u5b50KBOX KTV(\u73e0\u6c5f\u65b0\u57ce\u5e97)"],"K\u6b4c\u738b":["XKTV00035","K\u6b4c\u738b(\u756a\u79ba\u5e97)"],"\u6f6e\u6c47PTV":["XKTV00189","\u5e7f\u4e1c\u6f6e\u6c47PTV(\u6f6e\u6c47PartyKTV)"],"\u51ef\u4e50\u4f1a\u91cf\u8d29":["XKTV00190","\u51ef\u4e50\u4f1a\u91cf\u8d29KTV"],"\u6b4c\u835f\u91cf\u8d29":["XKTV00193","\u6b4c\u835f\u91cf\u8d29KTV(\u5143\u5c97\u5e97)"],"\u6b4c\u835fPTV":["XKTV00194","\u6b4c\u835fPTV(\u9f99\u6d1e\u5e97) "],"\u62c9\u9614\u97f3\u4e50ptv":["XKTV00195","\u62c9\u9614\u97f3\u4e50ptv KTV "],"\u65b0\u529b":["XKTV00196","\u65b0\u529bKTV"],"\u9f99\u4f1a":["XKTV00197","\u9f99\u4f1a\u9152\u5e97KTV"],"\u62fc\u97f3":["XKTV00198","\u62fc\u97f3\u91cf\u8d29\u5f0fKTV "],"\u76db\u6b4c":["XKTV00200","\u76db\u6b4cKTV(\u957f\u5174\u5e97\uff09"],"\u6cb3\u68e0\u6708\u8272":["XKTV00202","\u6cb3\u68e0\u6708\u8272KTV"],"\u90c1\u91d1\u9999":["XKTV00203","\u90c1\u91d1\u9999KTV"],"COCO":["XKTV00204","COCO KTV(\u8f66\u9642\u5e97)"],"\u6f6e\u6d3e":["XKTV00205","\u6f6e\u6d3eKTV"],"\u7231\u5c1a\u91cf\u8d29":["XKTV00206","\u7231\u5c1a\u91cf\u8d29KTV(\u4e1c\u5703\u5e97)"],"\u91d1\u7545":["XKTV00148","\u91d1\u7545(\u767d\u4e91\u5e97)"],"\u5e1d\u58f9\u56fd\u9645":["XKTV00214","\u5e1d\u58f9\u56fd\u9645\u91cf\u8d29\u5f0fKTV"],"\u8c6a\u60c5":["XKTV00221","\u8c6a\u60c5KTV"],"\u68e0\u4f1a":["XKTV00222","\u68e0\u4f1a(\u5409\u5c71\u5e97)"],"\u4e50\u9986\u4f1a":["XKTV00224","\u4e50\u9986\u4f1aKTV "],"\u68e0\u4f1a\u54c6\u96f7\u54aa":["XKTV00225","\u68e0\u4f1aKTV \u6216 \u54c6\u96f7\u54aa\u91cf\u7248\u5f0fKTV"],"\u9999":["XKTV00226","\u9999\u91cf\u8d29\u5f0fKTV"],"\u7ec5\u5531\u6c47":["XKTV00227","\u7ec5\u5531\u6c47"],"ALL IN KTV CLUB":["XKTV00230","ALL IN KTV CLUB"],"\u540d\u8c6a":["XKTV00252","\u540d\u8c6aKTV(\u767d\u4e91\u5e97)"],"\u91d1\u5ea7":["XKTV00101","\u91d1\u5ea7KTV\uff08\u68e0\u6eaa\u5e97\uff09"],"Crystal\u9e6d\u97f3\u670b\u4e3b\u9898":["XKTV00246","Crystal\u9e6d\u97f3\u670b\u4e3b\u9898KTV"],"\u9177K":["XKTV00248","\u9177K KTV"],"TOP\u661f\u6d3e\u5bf9":["XKTV00158","TOP\u661f\u6d3e\u5bf9KTV(\u5e7f\u5dde\u5e97) "],"\u4e3d\u5f71\u76db\u4f1a":["XKTV00159","\u4e3d\u5f71\u76db\u4f1aKTV"],"\u6b4c\u795e":["XKTV00089","\u6b4c\u795eKTV(\u4e1c\u5cfb\u5e7f\u573a\u5e97) "],"\u661f\u6b4c\u4f1a":["XKTV00161","\u661f\u6b4c\u4f1a\u91cf\u8d29\u5f0fKTV"],"\u661f\u90fd\u4f1a":["XKTV00162","\u661f\u90fd\u4f1aKTV"],"\u540d\u661f\u4f1a":["XKTV00019","\u540d\u661f\u4f1a\u91cf\u8d29\u5f0fKTV"],"\u6566\u7687":["XKTV00164","\u6566\u7687KTV"],"KK\u65b0\u65f6\u5c1a":["XKTV00165","KK\u65b0\u65f6\u5c1a\u91cf\u8d29\u5f0fKTV"],"\u7559\u58f0\u4f1a\u6240":["XKTV00166","\u7559\u58f0\u4f1a\u6240KTV"],"\u732a\u7b3c\u57ce\u5be8":["XKTV00167","\u732a\u7b3c\u57ce\u5be8\u91cf\u8d29\u5f0fKTV"],"\u52b2\u6d3e":["XKTV00240","\u52b2\u6d3e\u91cf\u8d29\u5f0fKTV\uff08\u767d\u4e91\u5e97\uff09"],"\u795e\u66f2":["XKTV00169","\u795e\u66f2KTV"],"KPARTY":["XKTV00173","KPARTY\u91cf\u8d29\u5f0fKTV(\u8d64\u5c97\u5e97) "],"\u91d1\u77ff\u98df\u5531":["XKTV00174","\u91d1\u77ff\u98df\u5531 "],"\u5fc5\u7231\u6b4c\u91cf\u8d29":["XKTV00175","\u5fc5\u7231\u6b4c\u91cf\u8d29KTV"],"\u5802\u4f1a\u6d77\u5370":["XKTV00176","\u5802\u4f1a(\u6d77\u5370\u5e97)"],"\u65b0\u805a\u70b9\u91cf\u8d29":["XKTV00177","\u65b0\u805a\u70b9\u91cf\u8d29KTV"],"\u9999\u5c9b\u5c0f\u7b51":["XKTV00178","\u9999\u5c9b\u5c0f\u7b51(\u6c5f\u5357\u5927\u9053\u4e2d\u5e97)"],"\u9189\u5ba2\u5427":["XKTV00179","\u9189\u5ba2\u5427KTV"],"\u7cd6\u679c":["XKTV00180","\u7cd6\u679cKTV(\u524d\u8fdb\u8def\u5e97)"],"\u98de\u626c88\u9152\u5427":["XKTV00182","\u98de\u626c88\u9152\u5427(\u77f3\u6eaa\u5e97) "],"\u6f6e\u6d3e\u91cf\u8d29":["XKTV00254","\u6f6e\u6d3e\u91cf\u8d29KTV(\u5357\u6d32\u5e97) "],"\u7231\u5c1a":["XKTV00092","\u7231\u5c1aKTV(\u767d\u4e91\u5e97)"],"\u7ef4\u7eb3\u65af":["XKTV00002","\u7ef4\u7eb3\u65afKTV"],"K\u5148\u751f":["XKTV00003","K\u5148\u751f\u91cf\u8d29\u5f0fKTV\uff08\u6865\u5357\u5e97\uff09"],"\u7b2c\u4e03\u611f\u89c9":["XKTV00004","\u7b2c\u4e03\u611f\u89c9\u91cf\u8d29\u5f0fKTV"],"\u4f17\u6c47":["XKTV00005","\u4f17\u6c47KTV"],"\u6d3b\u529b\u65e0\u9650":["XKTV00006","\u6d3b\u529b\u65e0\u9650KTV(\u5927\u77f3\u5e97) "],"\u97f3\u7687":["XKTV00012","\u97f3\u7687\u91cf\u8d29\u5f0fKTV"],"\u98de\u626c88":["XKTV00013","\u98de\u626c88\u91cf\u8d29\u5f0fKTV(\u756a\u79ba\u5e97)"],"\u5802\u4f1a\u756a\u79ba":["XKTV00015","\u5802\u4f1a(\u756a\u79ba\u5e97)"],"\u540d\u661f\u4f1a\u6d1b\u6eaa":["XKTV00018","\u540d\u661f\u4f1aKTV(\u6d1b\u6eaa\u5e97)"],"\u52b2\u6d3e\u5357\u6751":["XKTV00020","\u52b2\u6d3e\u91cf\u8d29\u5f0fKTV\uff08\u5357\u6751\u5e97\uff09"],"\u52b2\u6d3e\u949f\u6751":["XKTV00021","\u52b2\u6d3e\u91cf\u8d29\u5f0fKTV\uff08\u949f\u6751\u5e97)"],"\u52b2\u6d3e\u4e1c\u6d8c":["XKTV00022","\u52b2\u6d3e\u91cf\u8d29\u5f0fKTV\uff08\u4e1c\u6d8c\u5e97)"],"\u52b2\u6d3e\u5316\u9f99":["XKTV00023","\u52b2\u6d3e\u91cf\u8d29\u5f0fKTV\uff08\u5316\u9f99\u5e97)"],"\u65b0\u6d3e\u6982\u5ff5":["XKTV00024","\u65b0\u6d3e\u6982\u5ff5KTV(\u60e0\u4fe1\u5927\u53a6\u5e97)"],"\u4e50\u6d3e":["XKTV00025","\u4e50\u6d3e\u91cf\u8d29\u5f0fKTV"],"\u54aa\u4e50":["XKTV00026","\u54aa\u4e50\u91cf\u8d29KTV"],"\u6d77\u8c5a\u97f3\u6c27\u5427":["XKTV00029","\u6d77\u8c5a\u97f3\u6c27\u5427\u91cf\u8d29\u5f0fKTV "],"\u661f\u5929\u5730":["XKTV00030","\u661f\u5929\u5730\u91cf\u8d29\u5f0fKTV (\u5357\u6751\u5e97)"],"\u96c5\u51e1\u8fbe":["XKTV00032","\u96c5\u51e1\u8fbeKTV"],"\u6b22\u6b4c":["XKTV00033","\u6b22\u6b4c\u91cf\u8d29\u5f0fKTV"],"\u6b22\u5531":["XKTV00034","\u6b22\u5531KTV(\u756a\u79ba\u5e97)"],"\u6b22\u805a":["XKTV00036","\u6b22\u805a\u91cf\u8d29KTV"],"ABC":["XKTV00105","ABC\u91cf\u8d29\u5f0fKTV(\u677e\u5357\u5e97) "],"\u65b0\u6d3e":["XKTV00171","\u65b0\u6d3eKTV(\u5317\u4ead\u5e7f\u573a\u5e97)"],"\u76c8\u70b9V-BOX":["XKTV00172","\u76c8\u70b9V-BOX KTV"],"\u9b54\u65b9":["XKTV00247","\u9b54\u65b9KTV(\u5357\u6751\u4e07\u8fbe\u5e97)"],"\u5c1a\u8fb0":["XKTV00250","\u5c1a\u8fb0\u91cf\u8d29\u5f0fKTV(\u841d\u5c97\u4e07\u8fbe\u5e97)"],"\u4e94\u7ebf\u8c31":["XKTV00253","\u4e94\u7ebf\u8c31\u91cf\u8d29\u5f0fKTV"],"\u7545\u4eab123":["XKTV00093","\u7545\u4eab123\u91cf\u8d29\u5f0fKTV"],"\u548f\u4e50\u6c47\u91cf\u8d29":["XKTV00241","\u548f\u4e50\u6c47\u91cf\u8d29KTV(\u767d\u5bab\u65d7\u8230\u5e97)"],"\u6a31\u6843\u82b1":["XKTV00095","\u6a31\u6843\u82b1KTV"],"\u6b22\u4e50\u7545\u5c97\u8d1d":["XKTV00096","\u6b22\u4e50\u7545\u91cf\u8d29\u5f0fKTV(\u5c97\u8d1d\u5e97)"],"\u6b22\u4e50\u7545\u897f\u69ce":["XKTV00097","\u6b22\u4e50\u7545\u91cf\u8d29\u5f0fKTV(\u897f\u69ce\u5e97)"],"\u987a\u666f":["XKTV00099","\u987a\u666fKTV"],"K\u5f71\u65f6\u5c1a":["XKTV00100","K\u5f71\u65f6\u5c1a\u91cf\u8d29\u5f0fKTV"],"\u6f6e\u6c47":["XKTV00102","\u6f6e\u6c47KTV(\u5bcc\u529b\u6843\u56ed\u5e97) "],"\u9ea6\u9738":["XKTV00103","\u9ea6\u9738KTV"],"\u7545":["XKTV00104","\u7545KTV"],"\u98d9\u6b4c":["XKTV00106","\u98d9\u6b4c\u91cf\u8d29\u5f0fKTV "],"\u94f6\u67dc":["XKTV00107","\u94f6\u67dcKTV"],"\u65b0\u6b4c\u5feb\u7ebf":["XKTV00108","\u65b0\u6b4c\u5feb\u7ebfKTV (\u65b0\u5e02\u5e97)"],"\u559c\u6b4c":["XKTV00109","\u559c\u6b4c\u91cf\u8d29\u5f0fKTV"],"\u5802\u4f1a\u673a\u573a":["XKTV00110","\u5802\u4f1a(\u673a\u573a\u5e97)"],"\u9f50\u4e50\u91cf\u8d29":["XKTV00112","\u9f50\u4e50\u91cf\u8d29KTV "],"\u91d1\u535a":["XKTV00113","\u91d1\u535aKTV"],"K8":["XKTV00114","K8 \u91cf\u8d29\u5f0fKTV"],"\u5bb6\u4e50\u8fea\u91cf\u8d29":["XKTV00115","\u5bb6\u4e50\u8fea\u91cf\u8d29KTV(\u767d\u4e91\u5e97)"],"\u6d77\u61ac":["XKTV00116","\u6d77\u61acKTV"],"\u5929\u7a7a\u516c\u9986":["XKTV00117","\u5929\u7a7a\u516c\u9986KTV"],"\u9753\u58f0\u8d22\u667a\u5e7f\u573a":["XKTV00118","\u9753\u58f0\u91cf\u8d29\u5f0fKTV(\u8d22\u667a\u5e7f\u573a\u5e97) "],"\u9753\u58f0\u4e07\u6c11":["XKTV00119","\u9753\u58f0\u91cf\u8d29\u5f0fKTV(\u4e07\u6c11\u5e97)"],"\u9753\u58f0\u6c5f\u9ad8\u745e\u9686":["XKTV00120","\u9753\u58f0\u91cf\u8d29\u5f0fKTV(\u6c5f\u9ad8\u745e\u9686\u5e97) "],"\u97f3\u4e50\u6c47\u91cf\u8d29":["XKTV00121","\u97f3\u4e50\u6c47\u91cf\u8d29KTV"],"\u6b4c\u805a":["XKTV00122","\u6b4c\u805a\u91cf\u8d29\u5f0fKTV"],"\u591c\u5c0f\u732bPTV":["XKTV00123","\u591c\u5c0f\u732b\u91cf\u8d29\u5f0fPTV"],"\u548f\u4e50\u4f1a\u91cf\u8d29":["XKTV00124","\u548f\u4e50\u4f1a\u91cf\u8d29KTV(\u767d\u5bab\u65d7\u8230\u5e97) "],"\u57ce\u5e02\u751f\u6d3b":["XKTV00125","\u57ce\u5e02\u751f\u6d3b\u91cf\u8d29\u5f0fKTV"],"\u6b4c\u57ce":["XKTV00126","\u6b4c\u57ce\u91cf\u8d29\u5f0fKTV "],"\u96f6\u8ddd\u79bb\u91cf\u8d29":["XKTV00127","\u96f6\u8ddd\u79bb\u91cf\u8d29KTV "],"\u51ef\u6b4c":["XKTV00128","\u51ef\u6b4c\u91cf\u8d29\u5f0fKTV"],"\u5927\u5496":["XKTV00129","\u5927\u5496KTV"],"\u9b45\u529b\u91d1\u838e":["XKTV00130","\u9b45\u529b\u91d1\u838e"],"\u97f3\u4e3a\u7231":["XKTV00131","\u97f3\u4e3a\u7231KTV"],"\u80dc\u9f99\u6c47":["XKTV00132","\u80dc\u9f99\u6c47\u91cf\u8d29\u5f0fKTV"],"\u65b0\u6b4c":["XKTV00134","\u65b0\u6b4cKTV"],"\u54c6\u6765\u54aa\u9152\u5427":["XKTV00135","\u54c6\u6765\u54aa\u9152\u5427KTV "],"\u65f6\u5c1a100-":["XKTV00136","\u65f6\u5c1a100-KTV(\u767d\u4e91\u5e97)"],"\u540d\u9986":["XKTV00137","\u540d\u9986KTV"],"\u551b\u738b":["XKTV00139","\u551b\u738bKTV"],"\u7cd6\u4e4b\u679c":["XKTV00142","\u7cd6\u4e4b\u679c\u91cf\u8d29\u5f0fKTV"],"\u51ef\u6b4c\u4f1a":["XKTV00143","\u51ef\u6b4c\u4f1a\u91cf\u8d29\u5f0fKTV"],"\u4e50\u738b":["XKTV00144","\u4e50\u738b"],"\u4e16\u7eaa\u6b4c\u6f6e":["XKTV00146","\u4e16\u7eaa\u6b4c\u6f6e"],"\u5927\u90fd\u4f1a":["XKTV00147","\u5927\u90fd\u4f1aKTV"],"\u597d\u83b1\u575e":["XKTV00151","\u597d\u83b1\u575e\u91cf\u8d29\u5f0fKTV"],"\u5361\u8fea":["XKTV00152","\u5361\u8fea\u91cf\u8d29\u5f0fKTV "],"\u597d\u5ba2\u6765":["XKTV00153","\u597d\u5ba2\u6765\u91cf\u8d29\u5f0fKTV"],"\u4e50\u6ee1\u5802":["XKTV00155","\u4e50\u6ee1\u5802"],"\u91d1\u5927\u949f":["XKTV00156","\u91d1\u5927\u949fKTV"],"\u6b4c\u5229\u4e9a":["XKTV00243","\u6b4c\u5229\u4e9a"],"\u5361\u5361":["XKTV00192","\u5361\u5361KTV "],"\u7231\u7434\u6d77":["XKTV00199","\u7231\u7434\u6d77KTV(\u6885\u82b1\u56ed\u5e97) "],"\u4eae\u6b4c":["XKTV00232","\u4eae\u6b4cKTV"],"\u540c\u60a6\u6c47":["XKTV00233","\u540c\u60a6\u6c47KTV"],"\u6765\u6d3e\u5bf9PTV":["XKTV00238","\u6765\u6d3e\u5bf9PTV"],"\u7545\u6b4c":["XKTV00242","\u7545\u6b4c"],"\u84dd\u8272\u6d3e\u5bf9":["XKTV00249","\u84dd\u8272\u6d3e\u5bf9"],"\u6b4c\u89c6\u8fbe":["XKTV00062","\u6b4c\u89c6\u8fbe\u91cf\u8d29\u5f0fKTV "],"\u6b4c\u8c23":["XKTV00063","\u6b4c\u8c23KTV"],"\u534e\u590f\u5a31\u4e50\u57ce":["XKTV00064","\u534e\u590f\u5a31\u4e50\u57ce "],"\u597d\u4e50\u8fea":["XKTV00066","\u597d\u4e50\u8feaKTV(\u82b1\u90fd\u5e97)"],"\u83b1\u65af\u4e50":["XKTV00068","\u83b1\u65af\u4e50"],"\u5b9d\u4e50\u8fea\u91cf\u8d29":["XKTV00069","\u5b9d\u4e50\u8fea\u91cf\u8d29KTV(\u82b1\u90fd\u5e97)"],"\u6b27\u4e4b\u8bfa\u65f6\u5c1a\u6d3e\u5bf9":["XKTV00070","\u6b27\u4e4b\u8bfa\u65f6\u5c1a\u6d3e\u5bf9KTV(\u72ee\u5cad\u5e97) "],"\u6b22\u5531\u91cf\u8d29":["XKTV00071","\u6b22\u5531\u91cf\u8d29KTV (\u82b1\u90fd\u5e97)"],"99\u91d1\u67dc":["XKTV00073","99\u91d1\u67dc\u91cf\u8d29\u5f0fKTV"],"\u7545\u4e50\u8fea":["XKTV00074","\u7545\u4e50\u8feaKTV"],"\u7545\u4e50\u8fea\u72ee\u5cad":["XKTV00075","\u7545\u4e50\u8feaKTV(\u72ee\u5cad\u5e97) "],"Neway":["XKTV00080","Neway\u91cf\u8d29\u5f0fKTV(\u65b0\u5149\u5e97) "],"823":["XKTV00083","823KTV"],"\u77f3\u5934\u8bb0":["XKTV00088","\u77f3\u5934\u8bb0\u91cf\u8d29\u5f0fKTV"],"\u597d\u6b4c":["XKTV00090","\u597d\u6b4cKTV"],"\u6b22\u4e50\u7545":["XKTV00098","\u6b22\u4e50\u7545(\u9ec4\u5c90\u5e97)"],"\u661f\u9645":["XKTV00042","\u661f\u9645\u9152\u5e97"],"\u65f6\u5c1aK-100\u4e3b\u9898\u5f0f":["XKTV00209","\u65f6\u5c1aK-100\u4e3b\u9898\u5f0fKTV(\u5f00\u53d1\u533a\u5e97)"],"\u4e00\u4f11\u6b4c":["XKTV00215","\u4e00\u4f11\u6b4c\u91cf\u8d29\u5f0fKTV "],"\u94f6\u6d77":["XKTV00076","\u94f6\u6d77KTV"],"COCO.K":["XKTV00077","COCO.K(\u8d8a\u79c0\u5e97)"],"\u5802\u4f1a\u7f24\u7f24":["XKTV00078","\u5802\u4f1a(\u7f24\u7f24\u5e97)"],"\u7231\u97f3\u4e50":["XKTV00081","\u7231\u97f3\u4e50\uff08I music\uff09\u91cf\u8d29\u5f0fKTV"],"P-PASS\u6d3e\u6b4c":["XKTV00082","P-PASS \u6216 \u6d3e\u6b4cKTV(\u6c5f\u6e7e\u5e97)"],"\u65f6\u5c1a\u6d3e\u5bf9":["XKTV00085","\u65f6\u5c1a\u6d3e\u5bf9KTV"],"\u6d3b\u529b\u65e0\u9650\u4e2d\u534e\u5e7f\u573a":["XKTV00086","\u6d3b\u529b\u65e0\u9650KTV(\u4e2d\u534e\u5e7f\u573a\u5e97)"],"\u6d3b\u529b\u65e0\u9650\u5317\u4eac\u8def":["XKTV00087","\u6d3b\u529b\u65e0\u9650KTV(\u5317\u4eac\u8def\u5e97) "],"\u6b4c\u54e5":["XKTV00181","\u6b4c\u54e5\u91cf\u8d29\u5f0fKTV"],"\u767e\u5a01CEO\u91cf\u8d29":["XKTV00228","\u767e\u5a01CEO\u91cf\u8d29KTV "],"\u534e\u5a01\u8fbe\u91cf\u8d29":["XKTV00188","\u534e\u5a01\u8fbe\u91cf\u8d29KTV"],"\u4f70\u97f3":["XKTV00191","\u4f70\u97f3KTV"],"\u7ef4\u4e5f\u7eb3":["XKTV00208","\u7ef4\u4e5f\u7eb3KTV"],"020":["XKTV00210","020KTV"],"\u6b4c\u6f6e":["XKTV00212","\u6b4c\u6f6e\u91cf\u8d29\u5f0fKTV "],"\u5e7f\u4e50\u7eafK\u65b0\u6982\u5ff5":["XKTV00216","\u5e7f\u4e50\u7eafK\u65b0\u6982\u5ff5KTV"],"\u7545\u60f3\u56fd\u5ea6\u9ec4\u57d4\u65d7\u8230":["XKTV00217","\u7545\u60f3\u56fd\u5ea6(\u9ec4\u57d4\u65d7\u8230\u5e97 )"],"\u7545\u60f3\u56fd\u5ea6\u4e1c\u533a":["XKTV00218","\u7545\u60f3\u56fd\u5ea6\uff08\u4e1c\u533a\u5e97\uff09"],"\u661f\u5ba2\u4e50party":["XKTV00219","\u661f\u5ba2\u4e50partyKTV(\u9ec4\u57d4\u5e97) "],"\u57d4\u90fd":["XKTV00220","\u57d4\u90fd\u91cf\u8d29\u5f0fKTV"],"\u6b4c\u4e50\u4f1a":["XKTV00223","\u6b4c\u4e50\u4f1aKTV"],"\u7231\u5c1a\u4e3b\u9898\u91cf\u8d29":["XKTV00231","\u7231\u5c1a\u4e3b\u9898\u91cf\u8d29KTV(\u5f00\u53d1\u533a\u4e1c\u533a\u5e97) "]}', true);
    }
}