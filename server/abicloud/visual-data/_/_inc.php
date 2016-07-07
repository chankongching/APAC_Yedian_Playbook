<?php
(INAPP !== true) && die('Error !');
error_reporting(0);

date_default_timezone_set('Asia/Shanghai');

define('TIME', $_SERVER['REQUEST_TIME']);
define('METHOD', $_SERVER['REQUEST_METHOD']);
define('URL', curPageURL(true));
define('URI', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
define('DATETIME', date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']));
define('YESTERDAY', date('Y-m-d', strtotime('-1 day')));
define('ADDRESS', sprintf("%u", ip2long(getip())));
define('DIR', __DIR__.'/');
define('M', DIR.'m/');
define('V', DIR.'v/');

$C['db']['yedian']['host'] = '127.0.0.1';
$C['db']['yedian']['user'] = 'website';
$C['db']['yedian']['pswd'] = 'WebSite456';
$C['db']['yedian']['char'] = 'utf8';
$C['db']['yedian']['pcon'] = 0;
$C['db']['yedian']['port'] = 3306;
$C['db']['yedian']['name'] = 'abicloud';
$C['db']['yedian']['pfix'] = 'ac_';

$C['db']['spr']['host'] = '127.0.0.1';
$C['db']['spr']['user'] = 'letsktv_biz';
$C['db']['spr']['pswd'] = 'OBjhe7UF3IsMIwPK';
$C['db']['spr']['char'] = 'utf8';
$C['db']['spr']['pcon'] = 0;
$C['db']['spr']['port'] = 3306;
$C['db']['spr']['name'] = 'letsktv_biz_promogirls';
$C['db']['spr']['pfix'] = 'promogirls_';

$C['db']['wechat']['host'] = '127.0.0.1';
$C['db']['wechat']['user'] = 'letsktv';
$C['db']['wechat']['pswd'] = 'OBjhe7UF3IsMIwPK';
$C['db']['wechat']['char'] = 'utf8';
$C['db']['wechat']['pcon'] = 0;
$C['db']['wechat']['port'] = 3306;
$C['db']['wechat']['name'] = 'letsktv_wechat';
$C['db']['wechat']['pfix'] = 'letsktv_';

$C['mem']['host'] = '127.0.0.1';
$C['mem']['port'] = 11211;
$C['mem']['pfix'] = 'letsktv_visual_data_';
$C['mem']['pcon'] = false;

$C['user'] = array(
    1 => array('username' => 'admin', 'password' => 'admin'), 
    2 => array('username' => 'yedian_abi', 'password' => 'Iam@yedian'),
);


$m = (isset($_GET['m']) && in_array(trim($_GET['m']), array('logout', 'login', 'reservation', 'conversion', 'click'))) ? trim($_GET['m']) : 'reservation';

$MC = new MC($C['mem']);
session_start();

if($m !== 'login' && (!isset($_SESSION['visual_data_uid']) || intval($_SESSION['visual_data_uid']) < 1)) {
    $_SESSION = array();
    $_SESSION['callcenter_referer'] = URI;
    header('Location: '.URL.'?m=login');
    exit();
}
if($m == 'login' && (isset($_SESSION['visual_data_uid']) && intval($_SESSION['visual_data_uid']) > 0)) {
    header('Location: '.URL);
    exit();
}

$m !== 'api' && require_once M.$m.'.php';

function curPageURL($self = false) {
    $pageURL = 'http';
    if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= 's';
    }
    $pageURL .= '://';
    if(!in_array($_SERVER["SERVER_PORT"], array(80, 443))) {
        $pageURL .= $_SERVER["SERVER_NAME"] . ':' . $_SERVER["SERVER_PORT"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"];
    }
    $pageURL = $self === true ? $pageURL .= $_SERVER['PHP_SELF'] : $pageURL .= '/';
    return $pageURL;
}
function getip() {
    $unknown = 'unknown';
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if(false !== strpos($ip, ',')) {
        $ip = reset(explode(',', $ip));
    }
    return $ip;
}
function memory($cmd, $key = '', $value = '', $ttl = 0) {
    global $MC;
    if($cmd == 'enable') {
        return $MC->enable;
    } elseif($MC->enable && in_array($cmd, array('set', 'get', 'rm'))) {
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

function MySQLi($config) {
    $MySQLi = new mysqli($config['host'], $config['user'], $config['pswd'], $config['name'], $config['port']);
    $MySQLi->connect_errno && exit('DB Connection Error.');
    $MySQLi->query("SET character_set_connection=" . $config['char'] . ", character_set_results=" . $config['char'] . ", character_set_client=binary");
    return $MySQLi;
}

Class MC {
    private $obj;
    private $pfix;
    public $enable = null;
    
    public function __construct($config) {
        if(!empty($config['host'])) {
            if(class_exists('Memcached')) {
                $this->obj = new Memcached;
                $connect = $this->obj->addServer($config['host'], $config['port']);
            } else {
                $this->obj = new Memcache;
                if($config['pcon']) {
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
        if(class_exists('Memcached')) {
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
