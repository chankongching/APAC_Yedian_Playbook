<?php

define('INAPP', true);

header('Content-Type: text/html; charset=utf-8');

require_once './_inc.php';

// $m = (isset($_GET['m']) && in_array(trim($_GET['m']), array('wxconfig', 'userinfo', 'getToken'))) ? trim($_GET['m']) : '';
$m = (isset($_GET['m']) && in_array(trim($_GET['m']), array('authorization', 'wxconfig', 'getToken', 'get_code', 'get_openid', 'get_user_info', 'get_wxconfig', 'get_access_token', 'show_params', 'history', 'oneyuan'))) ? trim($_GET['m']) : '';

$MC = new MC($C['mem']);

/*
$DB = new mysqli($C['db']['host'], $C['db']['user'], $C['db']['pswd'], $C['db']['name']);
$DB->connect_errno && exit('DB Connection Error.');
$DB->query("SET character_set_connection=" . $C['db']['char'] . ", character_set_results=" . $C['db']['char'] . ", character_set_client=binary");
*/

switch ($m) {
    case 'get_code':
        $backurl = (isset($_GET['backurl']) && trim($_GET['backurl']) !== '' && filter_var(trim($_GET['backurl']), FILTER_VALIDATE_URL)) ? trim($_GET['backurl']) : '';
        $snsapi_userinfo_url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=got_code#wechat_redirect', 
            WECHAT_APPID,
            urlencode(URL.'?m=get_code&backurl='.$backurl)
        );
        if($_GET['state'] == 'got_code') {
            if($backurl) {
                $backurl = build_backurl($backurl, http_build_query(array('code' => $_GET['code']), '', '&'));
                header('Location: '.$backurl);exit;
            } else {
                header("Access-Control-Allow-Origin: *");
                header('Content-type: application/json');
                exit(json_encode(array(
                    'status'    => 1,
                    'data'      => $_GET['code']
                )));
            }
        } else {
            header('Location: '.$snsapi_userinfo_url);exit;
        }
    break;
    case 'get_openid':
        $backurl = (isset($_GET['backurl']) && trim($_GET['backurl']) !== '' && filter_var(trim($_GET['backurl']), FILTER_VALIDATE_URL)) ? trim($_GET['backurl']) : '';
        $snsapi_userinfo_url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=got_code#wechat_redirect', 
            WECHAT_APPID,
            urlencode(URL.'?m=get_openid&backurl='.$backurl)
        );
        if($_GET['state'] == 'got_code') {
            $MC = new MC($C['mem']);
            $WX = new WX();
            $basic_info = $WX->getAuthorizationCode(trim($_GET['code']));
            if($backurl) {
                $backurl = build_backurl($backurl, http_build_query($basic_info, '', '&'));
//                 exit($backurl);
                header('Location: '.$backurl);exit;
            } else {
                header("Access-Control-Allow-Origin: *");
                header('Content-type: application/json');
                exit(json_encode(array(
                    'status'    => 1,
                    'data'      => $basic_info
                )));
            }
        } else {
            header('Location: '.$snsapi_userinfo_url);exit;
        }
    break;
    case 'get_user_info':
        $backurl = (isset($_GET['backurl']) && trim($_GET['backurl']) !== '' && filter_var(trim($_GET['backurl']), FILTER_VALIDATE_URL)) ? trim($_GET['backurl']) : '';
        $snsapi_userinfo_url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=got_code#wechat_redirect', 
            WECHAT_APPID,
            urlencode(URL.'?m=get_user_info&backurl='.$backurl)
        );
        if($_GET['state'] == 'got_code') {
            $MC = new MC($C['mem']);
            $WX = new WX();
            $basic_info = $WX->getAuthorizationCode(trim($_GET['code']));
            $user = $WX->getUserInfo(trim($basic_info['access_token']), trim($basic_info['openid']));
            $user = array_merge($user, $basic_info);
            if($backurl) {
                $backurl = build_backurl($backurl, http_build_query($user, '', '&'));
                header('Location: '.$backurl);exit;
            } else {
                header("Access-Control-Allow-Origin: *");
                header('Content-type: application/json');
                exit(json_encode(array(
                    'status'    => 1,
                    'data'      => $user
                )));
            }
        } else {
            header('Location: '.$snsapi_userinfo_url);exit;
        }
    break;
    case 'get_wxconfig':
        $url = (isset($_GET['url']) && trim($_GET['url']) !== '') ? trim($_GET['url']) : exit(json_encode(array(
            'status'    => 0, 
            'error'     => 'url empty.'
        )));
        $MC = new MC($C['mem']);
        $WX = new WX();
        $wxconfig = $WX->getSignPackage($url);
        header("Access-Control-Allow-Origin: *");
        header('Content-type: application/json');
        exit(json_encode($wxconfig));
    break;
    case 'get_access_token':
        $MC = new MC($C['mem']);
        $WX = new WX();
        header("Access-Control-Allow-Origin: *");
        header('Content-type: application/json');
        exit(json_encode(array(
            'status'    => 1, 
            'data'      => $WX->access_token
        )));
    break;
    case 'show_params':
        var_dump($_GET);
        exit;
    break;
    case 'userinfo':
        if (isset($_GET['code']) && !empty(trim($_GET['code']))) {
            $WX = new WX(array('appid' => WECHAT_APPID, 'secret' => WECHAT_APPSECRET));
            $getAuthorizationCode = $WX->getAuthorizationCode(trim($_GET['code']));
            $userinfo = $WX->getUserInfo(trim($getAuthorizationCode['access_token']), trim($getAuthorizationCode['openid']));
            exit(json_encode($userinfo));
        } else {
            echo 'no code';
        }
    break;
    case 'wxconfig':
        $WX = new WX(array('appid' => WECHAT_APPID, 'secret' => WECHAT_APPSECRET));
        $url = (isset($_GET['url']) && !empty(trim($_GET['m']))) ? trim($_GET['url']) : null;
        $wxconfig = $WX->getSignPackage($url);
        header("Access-Control-Allow-Origin: *");
        header('Content-type: application/json');
        exit(json_encode($wxconfig));
    break;
    case 'getToken':
        header('Content-Type: application/json');
        $WX = new WX(array('appid' => WECHAT_APPID, 'secret' => WECHAT_APPSECRET));
        if($WX->access_token) {
            exit(json_encode(array(
                'status'    => 1, 
                'data'      => $WX->access_token
            )));
        } else {
            exit(json_encode(array(
                'status'    => 0
            )));
        }
    break;
    case 'history':
        header('Location: http://mp.weixin.qq.com/mp/getmasssendmsg?__biz=MzI2NDA4OTIzNQ==#wechat_webview_type=1&wechat_redirect');
        exit;
    break;
    case 'oneyuan':
        header('Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx90f8e48d4b4f5d8d&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fdist%2Foneyuan%2F&response_type=code&scope=snsapi_base&state=state#wechat_redirect');
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
        $this->appid    = isset($config['appid']) ? $config['appid'] : WECHAT_APPID;
        $this->secret   = isset($config['secret']) ? $config['secret'] : WECHAT_APPSECRET;
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

        !$resp && die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        $rs = json_decode($resp, true);
        curl_close($ch);
        if (isset($rs['errcode']) && $rs['errcode'] > 0) {
            die('Error: "' . $rs['errmsg'] . '" - Code: ' . $rs['errcode']);
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

        !$resp && die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        $rs = json_decode($resp, true);
        curl_close($ch);
        if (isset($rs['errcode']) && $rs['errcode'] > 0) {
            die('Error: "' . $rs['errmsg'] . '" - Code: ' . $rs['errcode']);
        } else {
            return $rs;
        }
    }
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

        !$resp && die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        $rs = json_decode($resp, true);
        curl_close($ch);
        if (isset($rs['errcode']) && $rs['errcode'] > 0) {
            die('Error: "' . $rs['errmsg'] . '" - Code: ' . $rs['errcode']);
        } else {
            return $rs;
        }
    }
    private function getAccessToken() {
        $url = sprintf('http://letsktv.chinacloudapp.cn/wechat_ktv/Home/WeChat/getToken');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($ch);
        $resp = json_decode($resp, true);
        $this->access_token = $resp['data'];
/*
        $data = memory('enable') ? memory('get', 'access_token') : '';
        if (empty($data) || !$data) {
            $data = $this->_getAccessToken();
            if (memory('enable') && !empty($data)) {
                memory('set', 'access_token', $data, $data['expires_in'] - 60);
            }
        }
        $this->access_token = $data['access_token'];
*/
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

        !$resp && die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        $rs = json_decode($resp, true);
        curl_close($ch);
        if (isset($rs['errcode']) && $rs['errcode'] > 0) {
            die('Error: "' . $rs['errmsg'] . '" - Code: ' . $rs['errcode']);
        } else {
            return $rs;
        }
    }
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
function build_backurl($url, $params=null) {
    if($params){
        if(!strpos($url, "?")) {
            $url .= "?".$params;
        } else {
            $url .= "&".$params;
        }
    }
    return $url;
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

