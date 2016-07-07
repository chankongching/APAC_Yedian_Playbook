<?php
(INAPP !== true) && die('Error !');

date_default_timezone_set('Asia/Shanghai');

define('TIME', $_SERVER['REQUEST_TIME']);
define('AJAX', isset($_GET['ajax']) ? true : false);
define('METHOD', $_SERVER['REQUEST_METHOD']);
define('URL', curPageURL(true));
define('URI', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
define('DATETIME', date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']));
define('ADDRESS', sprintf("%u", ip2long(getip())));
define('DIR', __DIR__ . '/');
define('M', DIR . 'm/');
define('V', DIR . 'v/');

$C['db']['host'] = '127.0.0.1';
$C['db']['user'] = 'letsktv_biz';
$C['db']['pswd'] = 'OBjhe7UF3IsMIwPK';
$C['db']['char'] = 'utf8';
$C['db']['pcon'] = 0;
$C['db']['name'] = 'letsktv_biz_promogirls';
$C['db']['pfix'] = 'promogirls_';


$C['mem']['host'] = '127.0.0.1';
$C['mem']['port'] = 11211;
$C['mem']['pfix'] = 'letsktv_biz_promogirls_admin_';
$C['mem']['pcon'] = false;

$C['role'] = array(
    'test'      => '测试帐号', 
    'spr'       => 'SPR', 
    'bd'        => 'BD', 
    'assistant' => '助理'
);
$C['d_status'] = array(
    '0' => '未绑定', 
    '1' => '已绑定'
);
$C['d_status_style'] = array(
    '0' => 'danger', 
    '1' => 'success'
);
$C['status'] = array(
    '0' => '已禁用', 
    '1' => '工作中'
);
$C['status_style'] = array(
    '-1'    => 'danger', 
    '0'     => 'warning', 
    '1'     => 'success'
);

$m = (isset($_GET['m']) && in_array(trim($_GET['m']), array('spr', 'sprs', 'logout', 'login'))) ? trim($_GET['m']) : 'index';

$MC = new MC($C['mem']);
session_start();

if ($m !== 'login' && (!isset($_SESSION['spr_admin_uid']) || intval($_SESSION['spr_admin_uid']) < 1)) {
	$_SESSION = array();
	$_SESSION['callcenter_referer'] = URI;
	header('Location: ' . URL . '?m=login');
	exit();
}
if ($m == 'login' && (isset($_SESSION['spr_admin_uid']) && intval($_SESSION['spr_admin_uid']) > 0)) {
	header('Location: ' . URL);
	exit();
}

define('ROLE', isset($_SESSION['spr_admin_role']) ? $_SESSION['spr_admin_role'] : null);
$_SESSION['last_time'] = TIME;

$DB = new mysqli($C['db']['host'], $C['db']['user'], $C['db']['pswd'], $C['db']['name']);
$DB->connect_errno && exit('DB Connection Error.');
$DB->query("SET character_set_connection=" . $C['db']['char'] . ", character_set_results=" . $C['db']['char'] . ", character_set_client=binary");

$_raw_log = array('GET' => $_GET, 'POST' => $_POST, 'REQUEST' => $_REQUEST, 'SERVER' => $_SERVER);

$query = sprintf("insert into `%s%s` set `dateline`='%s', `raw`='%s';",
	$C['db']['pfix'],
	'admin_rawlog',
	$DB->real_escape_string(DATETIME),
	$DB->real_escape_string(json_encode($_raw_log))
);
$DB->query($query);
$DB->errno > 0 && die('code: ' . $DB->errno . ', error:' . $DB->error);

require_once M . $m . '.php';

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
		return $this->obj->get($this->pfix . $key);
	}
	public function set($key, $value, $ttl = 1440) {
		if (class_exists('Memcached')) {
			return $this->obj->set($this->pfix . $key, $value, time() + $ttl);
		} else {
			return $this->obj->set($this->pfix . $key, $value, MEMCACHE_COMPRESSED, $ttl);
		}
	}
	public function getMulti($keys) {
		return $this->obj->get($this->pfix . $keys);
	}
	function rm($key) {
		return $this->obj->delete($this->pfix . $key);
	}
	public function clear() {
		return $this->obj->flush();
	}
	public function inc($key, $step = 1) {
		return $this->obj->increment($this->pfix . $key, $step);
	}
	public function dec($key, $step = 1) {
		return $this->obj->decrement($this->pfix . $key, $step);
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
			'sn' => $this->sn,
			'pwd' => strtoupper(md5($this->sn . $this->pwd)),
			'mobile' => $this->mobile,
			'content' => $this->content,
			'ext' => $this->ext,
			'stime' => $this->stime,
			'msgfmt' => $this->msgfmt,
			'rrid' => $this->rrid,
		);
		foreach ($argv as $key => $value) {
			if ($flag != 0) {
				$params .= "&";
				$flag = 1;
			}
			$params .= $key . "=";
			$params .= urlencode($value);
			$flag = 1;
		}
		$length = strlen($params);
		$fp = fsockopen("sdk.entinfo.cn", 8061, $errno, $errstr, 10) or exit($errstr . "--->" . $errno);
		$header = "POST /webservice.asmx/mdsmssend HTTP/1.1\r\n";
		$header .= "Host:sdk.entinfo.cn\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . $length . "\r\n";
		$header .= "Connection: Close\r\n\r\n";
		$header .= $params . "\r\n";
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
		$line = str_replace("<string xmlns=\"http://entinfo.cn/\">", "", $line);
		$line = str_replace("</string>", "", $line);
		$result = explode("-", $line);
		if (count($result) > 1) {
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
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pramga: no-cache");

	$username = "18602163052";
	$password = base64_encode("122333");

	$content = urlencode(mb_convert_encoding($content, 'gbk', 'utf8'));
	$url = 'http://61.147.98.117:9001';
	$url = $url . "/servlet/UserServiceAPI?method=sendSMS&extenno=&isLongSms=0&username=" . $username . "&password=" . $password . "&smstype=1&mobile=" . $mobile . "&content=" . $content;
	$data = file_get_contents($url);
	if (strpos($data, "success") === false) {
		return false;
	} else {
		return true;
	}
}