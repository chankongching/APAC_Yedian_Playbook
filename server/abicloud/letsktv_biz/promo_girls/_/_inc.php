<?php
/*
error_reporting(-1);
ini_set('display_errors', 1);
*/

(INAPP !== true) && die('Error !');

date_default_timezone_set('Asia/Shanghai');
header("Content-Type: text/html; charset=utf-8");
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Connection: close");

/* header('Content-Type: application/json'); */

/*
define('WECHAT_APPID', 'wx1a8fbf2b1083d924');
define('WECHAT_APPSECRET', 'de9e90bc2b77719a7bf42df108b8a090');
*/
define('WECHAT_APPID', 'wxc5fd6e0da524eddd');
define('WECHAT_APPSECRET', '547525a7637054d2681b19836bb2beeb');
define('DEBUG', false);
define('API', 'http://letsktv.chinacloudapp.cn/letsktv_biz/_wechat/');
define('QRAPI', 'http://letsktv.chinacloudapp.cn/wechat/');

define('TIME', $_SERVER['REQUEST_TIME']);
define('METHOD', $_SERVER['REQUEST_METHOD']);
define('URL', curPageURL(true));
define('DATETIME', date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']));
define('ADDRESS', sprintf("%u", ip2long(getip())));
define('DIR', __DIR__.'/');
define('M', DIR.'m/');
define('V', DIR.'v/');

$C['db']['host'] = '127.0.0.1';
$C['db']['user'] = 'letsktv_biz';
$C['db']['pswd'] = 'OBjhe7UF3IsMIwPK';
$C['db']['char'] = 'utf8';
$C['db']['pcon'] = 0;
$C['db']['name'] = 'letsktv_biz_promogirls';
$C['db']['pfix'] = 'promogirls_';


$C['mem']['host'] = '127.0.0.1';
$C['mem']['port'] = 11211;
// $C['mem']['pfix'] = 'letsktv_biz_promogirls_';
$C['mem']['pfix'] = 'letsktv_biz_promogirls_cxy_';
$C['mem']['pcon'] = false;

$m = (isset($_GET['m']) && in_array(trim($_GET['m']), array('point', 'achievement', 'qrcode', 'confirmuser', 'checkuser', 'sendcaptcha', 'code', 'api', 'checkin', 'checkin_fake'))) ? trim($_GET['m']) : 'index';

$MC = new MC($C['mem']);
session_start();

if($m !== 'code') {
    if(!isset($_SESSION['letsktv_biz_promogirls_openid']) || empty($_SESSION['letsktv_biz_promogirls_openid'])) {
        $_SESSION = array();
        $redirect_uri = URL.'?m=code';
        $url = sprintf('%s%s%s%s%s', 
            'https://open.weixin.qq.com/connect/oauth2/authorize?appid=', 
            WECHAT_APPID, 
            '&redirect_uri=', 
            urlencode($redirect_uri), 
            '&response_type=code&scope=snsapi_userinfo&state=state#wechat_redirect'
        );
        $_SESSION['letsktv_biz_promogirls_http_referer'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        header('Location: '.$url);
        exit();
    }
}

$DB = new mysqli($C['db']['host'], $C['db']['user'], $C['db']['pswd'], $C['db']['name']);
$DB->connect_errno && exit('DB Connection Error.');
$DB->query("SET character_set_connection=" . $C['db']['char'] . ", character_set_results=" . $C['db']['char'] . ", character_set_client=binary");

require_once M.$m.'.php';

Class WX {
    private $appid;
    private $secret;
    public $access_token;
    public $MC;
    public $ticket;

    public function __construct($config = array()) {
        $this->appid = $config['appid'] ? $config['appid'] : WECHAT_APPID;
        $this->secret = $config['secret'] ? $config['secret'] : WECHAT_APPSECRET;
        $this->getAccessToken();
    }
    public function getUserInfo($access_token, $openid) {
        $url = sprintf('https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN',
            $access_token,
            $openid
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($ch);

        !$resp && die(json_encode(array(
            'status'    => 0, 
            'code'      => curl_errno($ch), 
            'error'     => curl_error($ch)
        )));

        $rs = json_decode($resp, true);
        curl_close($ch);
        if (isset($rs['errcode']) && $rs['errcode'] > 0) {
            die(json_encode(array(
                'status'    => 0, 
                'code'      => $rs['errcode'], 
                'error'     => 'userinfo: '. $rs['errmsg']
            )));
        } else {
            return $rs;
        }
    }
    public function getAuthorizationCode($code) {
        $url = sprintf('https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code',
            $this->appid,
            $this->secret,
            $code
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($ch);

        !$resp && die(json_encode(array(
            'status'    => 0, 
            'code'      => curl_errno($ch), 
            'error'     => curl_error($ch)
        )));

        $rs = json_decode($resp, true);
        curl_close($ch);
        if (isset($rs['errcode']) && $rs['errcode'] > 0) {
            die(json_encode(array(
                'status'    => 0, 
                'code'      => $rs['errcode'], 
                'error'     => 'authorization_code: '.$rs['errmsg']
            )));
        } else {
            return $rs;
        }
    }
    private function getAccessToken() {
        $data = memory('enable') ? memory('get', 'access_token') : '';
        if (empty($data) || !$data) {
            $data = $this->_getAccessToken();
            if (memory('enable') && !empty($data) && $data['status'] == 1) {
                memory('set', 'access_token', $data, $data['data']['expires_in'] - 60);
            }
        }
        $this->access_token = $data['data']['access_token'];
    }
    private function _getAccessToken() {
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
            $this->appid,
            $this->secret
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($ch);

        !$resp && die(json_encode(array(
            'status'    => 0, 
            'code'      => curl_errno($ch), 
            'error'     => curl_error($ch)
        )));

        $rs = json_decode($resp, true);
        curl_close($ch);
        if (isset($rs['errcode']) && $rs['errcode'] > 0) {
            die(json_encode(array(
                'status'    => 0, 
                'code'      => $rs['errcode'], 
                'error'     => 'client_credential: '.$rs['errmsg']
            )));
        } else {
            return array(
                'status'    => 1, 
                'data'      => $rs
            );
        }
    }
/*
    public function getSignPackage($signUrl = null) {
        $this->getJsApiTicket();
        $jsapiTicket = $this->ticket;
        if ($signUrl) {
            $url = $signUrl;
        } else {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId" => $this->appid,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "signature" => $signature,
        );
        return $signPackage;
    }
    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    private function getJsApiTicket() {
        $data = memory('enable') ? memory('get', 'ticket') : '';
        if (empty($data) || !$data) {
            $data = $this->_getJsApiTicket();
            if (memory('enable') && !empty($data)) {
                memory('set', 'ticket', $data, $data['expires_in'] - 60);
            }
        }
        $this->ticket = $data['ticket'];
    }
    private function _getJsApiTicket() {
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=%s',
            $this->access_token
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($ch);

        !$resp && die(json_encode(array(
            'status'    => 0, 
            'code'      => curl_errno($ch), 
            'error'     => curl_error($ch)
        )));

        $rs = json_decode($resp, true);
        curl_close($ch);
        if (isset($rs['errcode']) && $rs['errcode'] > 0) {
            die(json_encode(array(
                'status'    => 0, 
                'code'      => $rs['errcode'], 
                'error'     => $rs['errmsg']
            )));
        } else {
            return array(
                'status'    => 1, 
                'data'      => $rs
            );
        }
    }
*/
}
function curPageURL($self = false) {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= 's';
    }
    $pageURL .= '://';
    if (!in_array($_SERVER["SERVER_PORT"], array(80, 443))) {
        $pageURL .= $_SERVER["SERVER_NAME"] . ':' . $_SERVER["SERVER_PORT"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"];
    }
    $pageURL = $self === true ? $pageURL .= $_SERVER['PHP_SELF'] : $pageURL .= '/';
    return $pageURL;
}
function getip() {
    $unknown = 'unknown';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if (false !== strpos($ip, ',')) {
        $ip = reset(explode(',', $ip));
    }
    return $ip;
}
function memory($cmd, $key = '', $value = '', $ttl = 0) {
    global $MC;
    if ($cmd == 'enable') {
        return $MC->enable;
    } elseif ($MC->enable && in_array($cmd, array('set', 'get', 'rm'))) {
        switch ($cmd) {
            case 'set':return $MC->set($key, $value, $ttl);
            break;
            case 'get':return $MC->get($key);
            break;
            case 'rm':return $MC->rm($key);
            break;
            case 'inc':return $MC->inc($key, 1);
            break;
        }
    }
    return null;
}

Class MC {
    private $obj;
    private $pfix;
    public $enable = null;
    
    public function __construct($config) {
        if (!empty($config['host'])) {
            if (class_exists('Memcached')) {
                $this->obj = new Memcached;
                $connect = $this->obj->addServer($config['host'], $config['port']);
            } else {
                $this->obj = new Memcache;
                if ($config['pcon']) {
                    $connect = @$this->obj->pconnect($config['host'], $config['port']);
                } else {
                    $connect = @$this->obj->connect($config['host'], $config['port']);
                }
            }
            $this->enable = $connect ? true : false;
            $this->pfix = $connect ? $config['pfix'] : $config['pfix'];
        }
    }
    public function get($key) {
        return $this->obj->get($this->pfix.$key);
    }
    public function set($key, $value, $ttl = 1440) {
        if (class_exists('Memcached')) {
            return $this->obj->set($this->pfix.$key, $value, time() + $ttl);
        } else {
            return $this->obj->set($this->pfix.$key, $value, MEMCACHE_COMPRESSED, $ttl);
        }
    }
    public function getMulti($keys) {
        return $this->obj->get($this->pfix.$keys);
    }
    function rm($key) {
        return $this->obj->delete($this->pfix.$key);
    }
    public function clear() {
        return $this->obj->flush();
    }
    public function inc($key, $step = 1) {
        return $this->obj->increment($this->pfix.$key, $step);
    }
    public function dec($key, $step = 1) {
        return $this->obj->decrement($this->pfix.$key, $step);
    }
}

class SendSmsHttp {
    private $sn = 'SDK-BBX-010-23609';
    private $pwd = '9609A6-e';
    public $mobile;
    public $content;
    public $ext = '';
    public $stime = '';
    public $msgfmt = '';
    public $rrid = '';
    public $errorMsg;
    
    public function send() {
        $flag = 0;
        $params = '';
        $argv = array(
            'sn'      => $this->sn, 
            'pwd'     => strtoupper(md5($this->sn.$this->pwd)), 
            'mobile'  => $this->mobile, 
            'content' => $this->content, 
            'ext'     => $this->ext, 
            'stime'   => $this->stime, 
            'msgfmt'  => $this->msgfmt, 
            'rrid'    => $this->rrid
        );
        foreach($argv as $key=>$value) {
            if ($flag != 0) {
                $params .= "&";
                $flag    = 1;
            }
            $params .= $key."=";
            $params .= urlencode($value);
            $flag    = 1;
        }
        $length = strlen($params);
        $fp = fsockopen("sdk.entinfo.cn", 8061, $errno, $errstr, 10) or exit($errstr."--->".$errno);
        $header  = "POST /webservice.asmx/mdsmssend HTTP/1.1\r\n";
        $header .= "Host:sdk.entinfo.cn\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: ".$length."\r\n";
        $header .= "Connection: Close\r\n\r\n";
        $header .= $params."\r\n";
        fputs($fp, $header);
        $inheader = 1;
        while (!feof($fp)) {
            $line = fgets($fp, 1024);
            if ($inheader && ($line == "\n" || $line == "\r\n")) {
                $inheader = 0;
            }
            if ($inheader == 0) {
        //        echo $line;
            }
        }
        $line   = str_replace("<string xmlns=\"http://entinfo.cn/\">", "", $line);
        $line   = str_replace("</string>", "", $line);
        $result = explode("-", $line);
        if(count($result) > 1) {
            $this->errorMsg = isset($line) ? $line : '未知错误';
//             echo $this->errorMsg."\n";
//             echo '时间: '.date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']).', 失败, 手机号码: '.$argv['mobile'].' 返回值为:'.$line.' 内容：'.$argv['content']."\n";
            return false;
        } else {
//             echo '时间: '.date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']).', 成功, 手机号码: '.$argv['mobile'].' 返回值为:'.$line.' 内容：'.$argv['content']."\n";
            return true;
        }
    }
}
function sendSMS($mobile, $content) {
    header('Content-Type: text/html; charset=GBK');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
    header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); 
    header("Cache-Control: no-cache, must-revalidate"); 
    header("Pramga: no-cache"); 

    $username   = "18702163052";
    $password   = base64_encode("122333");

    $content    = urlencode(mb_convert_encoding($content, 'gbk', 'utf8'));
    $url        = 'http://61.147.98.117:9001';
    $url        = $url."/servlet/UserServiceAPI?method=sendSMS&extenno=&isLongSms=0&username=".$username."&password=".$password."&smstype=1&mobile=".$mobile."&content=".$content;
    $data       = file_get_contents($url);
    if(strpos($data, "success") === false) {
        return false;
    } else {
        return true;
    }
}

function sendTemplateSMS($to, $datas, $tempId) {
    $debugMode      = false;

    $accountSid     = '8a48b551488d07a80148a6c6b9c80b2c';
    $accountToken   = '3469ffd06e824c879e5d79ebb7868f54';
    $serverPort     = '8883';
    $softVersion    = '2013-12-26';

    if($debugMode === true) {
        //Development
        $appId      = '8a48b551488d07a80148a6c7d1b70b31';
        $serverIP   = 'sandboxapp.cloopen.com';
        $tplId      = '1';
    } else {
        //Production
        $appId      = '8a48b5515147eb6d01515d10e79e365c';
        $serverIP   = 'app.cloopen.com';
        $tplId      = '5117';
    }
    $rest = new REST($serverIP, $serverPort, $softVersion);
    $rest->setAccount($accountSid, $accountToken);
    $rest->setAppId($appId);
    
    return $rest->sendTemplateSMS($to, $datas, $tempId);
}

class REST {
    private $AccountSid;
    private $AccountToken;
    private $AppId;
    private $ServerIP;
    private $ServerPort;
    private $SoftVersion;
    private $Batch;
    private $BodyType   = "json";
    private $enabeLog   = true;
    private $Filename   = "/tmp/SMS_Verify.log";
    private $Handle;
    
    function __construct($ServerIP, $ServerPort, $SoftVersion) {
        $this->Batch        = date("YmdHis");
        $this->ServerIP     = $ServerIP;
        $this->ServerPort   = $ServerPort;
        $this->SoftVersion  = $SoftVersion;
        $this->Handle       = fopen($this->Filename, 'a');
    }
    
    function setAccount($AccountSid, $AccountToken) {
        $this->AccountSid   = $AccountSid;
        $this->AccountToken = $AccountToken;
    }
    
    function setAppId($AppId) {
        $this->AppId = $AppId;
    }
    
    function showlog($log) {
        if($this->enabeLog != false){
            fwrite($this->Handle, $log."\n");
        }
    }
    
    function curl_post($url, $data, $header, $post=1) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, $post);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        if($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        
        if($result == FALSE) {
            if($this->BodyType == 'json') {
                $result = "{\"statusCode\":\"172001\",\"statusMsg\":\"网络错误\"}";
            } else {
                $result = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Response><statusCode>172001</statusCode><statusMsg>网络错误</statusMsg></Response>";
            }
        }
        curl_close($ch);
        return $result;
    }
    
    function sendTemplateSMS($to, $datas, $tempId) {
        $auth = $this->accAuth();
        if($auth != "") {
            $this->showlog("auth faild = code:".$auth->statusCode);
            return $auth;
        }
        if($this->BodyType == "json") {
            $data = "";
            for($i=0; $i<count($datas); $i++) {
                $data = $data."'".$datas[$i]."',";
            }
            $body = "{'to':'$to','templateId':'$tempId','appId':'$this->AppId','datas':[".$data."]}";
//            echo $body;exit;
        } else {
            $data = "";
            for($i=0; $i<count($datas); $i++){
                $data = $data."<data>".$datas[$i]."</data>";
            }
            $body = "<TemplateSMS><to>$to</to><appId>$this->AppId</appId><templateId>$tempId</templateId><datas>".$data."</datas></TemplateSMS>";
        }
        $sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        $url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/TemplateSMS?sig=$sig";
        $this->showlog("request url = ".$url);
        $this->showlog("request body = ".$body);
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        $result = $this->curl_post($url, $body, $header);
        $this->showlog("response body = ".$result);
        if($this->BodyType == "json") {
            $datas = json_decode($result);
        } else {
            $datas = simplexml_load_string(trim($result, " \t\n\r"));
        }
//        if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误';
//        }
        if($datas->statusCode == 0) {
            if($this->BodyType == "json") {
                $datas->TemplateSMS = $datas->templateSMS;
                unset($datas->templateSMS);
            }
        }
        fclose($this->Handle);
        return $datas;
    }
    
    function accAuth() {
        if($this->ServerIP == "") {
            $data = new stdClass();
            $data->statusCode   = '172004';
            $data->statusMsg    = 'IP为空';
            return $data;
        }
        if($this->ServerPort <= 0) {
            $data = new stdClass();
            $data->statusCode   = '172005';
            $data->statusMsg    = '端口错误（小于等于0）';
            return $data;
        }
        if($this->SoftVersion == "") {
            $data = new stdClass();
            $data->statusCode   = '172013';
            $data->statusMsg    = '版本号为空';
            return $data;
        }
        if($this->AccountSid == "") {
            $data = new stdClass();
            $data->statusCode   = '172006';
            $data->statusMsg    = '主帐号为空';
            return $data;
        }
        if($this->AccountToken == "") {
            $data = new stdClass();
            $data->statusCode   = '172007';
            $data->statusMsg    = '主帐号令牌为空';
            return $data;
        }
        if($this->AppId == "") {
            $data = new stdClass();
            $data->statusCode   = '172012';
            $data->statusMsg    = '应用ID为空';
            return $data;
        }
    }
}