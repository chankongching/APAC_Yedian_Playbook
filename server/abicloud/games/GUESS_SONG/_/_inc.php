<?php
(!defined('INAPP') || INAPP !== true) && die('Access denied.');

define('TIME', $_SERVER['REQUEST_TIME']);
define('METHOD', $_SERVER['REQUEST_METHOD']);
define('URL', curPageURL(true));
define('DATETIME', date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']));
define('ADDRESS', sprintf("%u", ip2long(getip())));

$cnf = __DIR__.'/_cfg.php';
!file_exists($cnf) && die('Configuration error.');
require_once $cnf;

$m = (isset($_GET['m']) && in_array(trim($_GET['m']), array('index', 'authorization', 'api'))) ? trim($_GET['m']) : 'index';
$a = ($m == 'api' && isset($_GET['a']) && in_array(trim($_GET['a']), array('get_times', 'toplist', 'friends', 'submit', 'getToken')))   ? trim($_GET['a']) : '';

session_start();
$MC = new MC($C['mem']);

$DB = new mysqli($C['db']['host'], $C['db']['user'], $C['db']['pswd'], $C['db']['name']);
$DB->connect_errno && exit('DB Connection Error.');
$DB->query("SET character_set_connection=".$C['db']['char'].", character_set_results=".$C['db']['char'].", character_set_client=binary");

Class WX {
    private $appid;
    private $secret;
    public  $access_token;
    public  $MC;
    public  $ticket;

    public function __construct($config) {
        $this->appid    = $config['appid'];
        $this->secret   = $config['secret'];
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
        if(isset($rs['errcode']) && $rs['errcode'] > 0) {
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
        if(isset($rs['errcode']) && $rs['errcode'] > 0) {
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
            "url"       => $url,
            "string"    => $string,
            "appId"     => $this->appid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "signature" => $signature
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
        if(empty($data) || !$data) {
            $data = $this->_getJsApiTicket();
            if(memory('enable') && !empty($data)) {
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
        if(isset($rs['errcode']) && $rs['errcode'] > 0) {
            die('Error: "' . $rs['errmsg'] . '" - Code: ' . $rs['errcode']);
        } else {
            return $rs;
        }
    }
    private function getAccessToken() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://letsktv.chinacloudapp.cn/wechat/index.php?m=getToken');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($ch);

        !$resp && die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        $rs = json_decode($resp, true);
        curl_close($ch);

        $this->access_token = $rs['access_token'];
    }
/*
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
        if(isset($rs['errcode']) && $rs['errcode'] > 0) {
            die('Error: "' . $rs['errmsg'] . '" - Code: ' . $rs['errcode']);
        } else {
            return $rs;
        }
    }
*/
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
function curPageURL($self = false) {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= 's';
    }
    $pageURL .= '://';
    if (!in_array($_SERVER["SERVER_PORT"], array(80, 443))) {
        $pageURL .= $_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"];
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
function memory($cmd, $key='', $value='', $ttl = 0) {
    global $MC;
    if($cmd == 'enable') {
        return  $MC->enable;
    } elseif($MC->enable && in_array($cmd, array('set', 'get', 'rm'))) {
        switch ($cmd) {
            case 'set': return $MC->set($key, $value, $ttl); break;
            case 'get': return $MC->get($key); break;
            case 'rm': return $MC->rm($key); break;
            case 'inc': return $MC->inc($key, 1); break;
        }
    }
    return null;
}
function sub_dir($str = '', $level = 3){
    if($str == ''){
        return false;
    } else {
        $sub_dir = '';
        for($i=0; $i<$level; $i++){
            $sub_dir .= ($i==0) ? substr($str, $i*6, 6) : DIRECTORY_SEPARATOR.substr($str, $i*6, 6);
        }
        return $sub_dir;
    }
}
function getImage($url, $save_dir='', $filename='', $type=0) {
    if(trim($url) == '') {
        return array('file_name'=>'', 'save_path'=>'', 'error'=>1);
    }
    if(trim($save_dir) == '') {
        $save_dir='./';
    }
    if(trim($filename) == '') {
        $ext = strrchr($url, '.');
        if($ext != '.gif' && $ext != '.jpg') {
            return array('file_name'=>'', 'save_path'=>'', 'error'=>3);
        }
        $filename = time() . $ext;
    }
    if(0 !== strrpos($save_dir, '/')) {
        $save_dir .= '/';
    }
    if(!file_exists($save_dir) && !mkdir($save_dir, 0777, true)) {
        return array('file_name'=>'', 'save_path'=>'', 'error'=>5);
    }
    if($type) {
        $ch = curl_init();
        $timeout =  5;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $img = curl_exec($ch);
        curl_close($ch);
    } else {
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
    }
    if(is_string($img)) {
        $json = json_decode($img, true);
        if(isset($json['errcode'])) {
            exit(json_encode(array(
                'status' => 0,
                'msg' => '从微信获取图片失败。'
            )));
        }
    }
    $fp2 = @fopen($save_dir . $filename, 'w');
    fwrite($fp2, $img);
    fclose($fp2);
    unset($img, $url);
    return array('file_name'=>$filename, 'save_path'=>$save_dir.$filename, 'error'=>0);
}
