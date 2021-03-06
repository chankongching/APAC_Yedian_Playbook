<?php

define('INAPP', true);

require_once './_inc.php';

$m = (isset($_GET['m']) && in_array(trim($_GET['m']), array('userinfo', 'getToken', 'history'))) ? trim($_GET['m']) : '';

$MC = new MC($C['mem']);

$DB = new mysqli($C['db']['host'], $C['db']['user'], $C['db']['pswd'], $C['db']['name']);
$DB->connect_errno && exit('DB Connection Error.');
$DB->query("SET character_set_connection=" . $C['db']['char'] . ", character_set_results=" . $C['db']['char'] . ", character_set_client=binary");

switch ($m) {
case 'userinfo':
    if (isset($_GET['code']) && !empty(trim($_GET['code']))) {
        $WX = new WX(array('appid' => WECHAT_APPID, 'secret' => WECHAT_APPSECRET));
        $getAuthorizationCode = $WX->getAuthorizationCode(trim($_GET['code']));
        $getAuthorizationCode['unionid'] = (isset($getAuthorizationCode['unionid']) && !empty(trim($getAuthorizationCode['unionid']))) ? trim($getAuthorizationCode['unionid']) : '';
        $query = sprintf("select `id`, `nickname`, `openid`, `headimgurl`, `status` from `%s%s` where `openid` = '%s' limit 1;",
            $C['db']['pfix'],
            'users',
            $getAuthorizationCode['openid']
        );
        $refilluserinfo = false;
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status'    => 0,
            'msg'       => $DB->errno
        )));
        if ($source->num_rows > 0) {
            while ($row = $source->fetch_assoc()) {
                $user = $row;
                $userinfo = $user;
            }
            $query = sprintf("update `%s%s` set `lastlogon`='%s', `lastaddress`=%d where `id`=%d limit 1;",
                $C['db']['pfix'],
                'users',
                DATETIME,
                ADDRESS,
                $user['id']
            );
            $DB->query($query);
            $DB->errno > 0 && die(json_encode(array(
                'status'    => 0,
                'msg'       => $DB->errno
            )));
            if (isset($user['lastlogon']) && $_SERVER['REQUEST_TIME'] - strtotime($user['lastlogon']) > 7200) {
                $refilluserinfo = true;
            }
        } else {
            $query = sprintf("insert into `%s%s` set `dateline`='%s', `address`=%d, `openid`='%s', `unionid`='%s', `access_token`='%s', `refresh_token`='%s';",
                $C['db']['pfix'],
                'users',
                DATETIME,
                ADDRESS,
                $DB->real_escape_string(trim($getAuthorizationCode['openid'])),
                $DB->real_escape_string(trim($getAuthorizationCode['unionid'])),
                $DB->real_escape_string(trim($getAuthorizationCode['access_token'])),
                $DB->real_escape_string(trim($getAuthorizationCode['refresh_token']))
            );
            $DB->query($query);
            $DB->errno > 0 && die(json_encode(array(
                'status'    => 0,
                'msg'       => $DB->errno
            )));
            $user['id']     = $DB->insert_id;
            $refilluserinfo = true;
        }
        if ($refilluserinfo == true) {
            $userinfo = $WX->getUserInfo(trim($getAuthorizationCode['access_token']), trim($getAuthorizationCode['openid']));
            $query = sprintf("update `%s%s` set `nickname`='%s', `sex`=%d, `province`='%s', `city`='%s', `country`='%s', `headimgurl`='%s', `privilege`='%s', `rawdata`='%s' where `id`=%d limit 1;",
                $C['db']['pfix'],
                'users',
                $DB->real_escape_string(trim($userinfo['nickname'])),
                $DB->real_escape_string(trim($userinfo['sex'])),
                $DB->real_escape_string(trim($userinfo['province'])),
                $DB->real_escape_string(trim($userinfo['city'])),
                $DB->real_escape_string(trim($userinfo['country'])),
                $DB->real_escape_string(trim($userinfo['headimgurl'])),
                (isset($userinfo['privilege']) && !empty($userinfo['privilege'])) ? $DB->real_escape_string(json_encode($userinfo['privilege'])) : '',
                $DB->real_escape_string(json_encode($userinfo)), 
                $user['id']
            );
            $DB->query($query);
            $DB->errno > 0 && die(json_encode(array(
                'status'    => 0,
                'msg'       => $DB->errno
            )));
        }
        exit(json_encode(array(
            'status'    => 1,
            'data'      => $userinfo
        )));
    } else {
        exit(json_encode(array(
            'status'    => 0,
            'error'       => 'code empty'
        )));
    }
    break;
case 'wxconfig':
/*
$WX = new WX(array('appid' => WECHAT_APPID, 'secret' => WECHAT_APPSECRET));
$url = (isset($_GET['url']) && !empty(trim($_GET['m']))) ? trim($_GET['url']) : null;
$wxconfig = $WX->getSignPackage($url);
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
exit(json_encode($wxconfig));
 */
    break;
case 'getToken':
    $WX = new WX(array('appid' => WECHAT_APPID, 'secret' => WECHAT_APPSECRET));
    exit(json_encode(array(
        'status'    => 1,
        'data'      => $WX->access_token
    )));
    break;
case 'history':
    header('Location: http://mp.weixin.qq.com/mp/getmasssendmsg?__biz=MzI2MTExMjYyOQ==#wechat_webview_type=1&wechat_redirect');
    exit;
    break;
default:
    die();
    break;
}
exit();

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
    private $pwd = '9609A6-f';
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
