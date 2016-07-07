<?php
(INAPP !== true) && die('Error !');

date_default_timezone_set('Asia/Shanghai');

define('TIME', $_SERVER['REQUEST_TIME']);
define('METHOD', $_SERVER['REQUEST_METHOD']);
define('URL', curPageURL(true));
define('URI', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
define('DATETIME', date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']));
define('ADDRESS', sprintf("%u", ip2long(getip())));
define('DIR', __DIR__ . '/');
define('M', DIR . 'm/');
define('V', DIR . 'v/');
define('CONFIRM_ORDER', '您好，您已经成功预订%s%s，开始时间为%s年%s月%s日%s，持续时间为%s个小时，请提前半小时到店，凭借手机号码到前台消费。如有任何问题，请拨打夜点客服电话4006507351，感谢您对夜点的支持。');
define('CANCEL_ORDER', '抱歉，您已经预定的%s%s，开始时间为%s年%s月%s日%s，持续时间为%s个小时，因预定房间已满，所以预定不成功。请尝试选择其他时段或者其他KTV。感谢您对夜点的支持。');

$C['db']['host'] = '127.0.0.1';
$C['db']['user'] = 'website';
$C['db']['pswd'] = 'WebSite456';
$C['db']['char'] = 'utf8';
$C['db']['pcon'] = 0;
$C['db']['name'] = 'abicloud';
$C['db']['pfix'] = 'ac_';

$C['mem']['host'] = '127.0.0.1';
$C['mem']['port'] = 11211;
$C['mem']['pfix'] = 'letsktv_callcenter_';
$C['mem']['pcon'] = false;

$roomtypes = array(
	1 => '小包',
	2 => '中包',
	3 => '大包',
);
$orderstatus = array(
	1 => '待处理',
	3 => '已确定',
	4 => '无房间',
	5 => '已到店',
	7 => '用户取消',
	14 => '超时未处理',
);
$orderstatusstyle = array(
	1 => 'label label-primary',
	3 => 'label label-success',
	4 => 'label label-danger',
	5 => 'label label-success',
	7 => 'label label-warning',
	14 => 'label label-info',
);

array(
	1 => 'todo',
	3 => 'done',
	4 => 'rejected',
	5 => 'confirmed',
	7 => 'cancled',
	14 => 'expired',
);

$m = (isset($_GET['m']) && in_array(trim($_GET['m']), array('order', 'logout', 'login'))) ? trim($_GET['m']) : 'index';

$MC = new MC($C['mem']);
session_start();

if ($m !== 'login' && (!isset($_SESSION['callcenter_uid']) || intval($_SESSION['callcenter_uid']) < 1)) {
	$_SESSION = array();
	$_SESSION['callcenter_referer'] = URI;
	header('Location: ' . URL . '?m=login');
	exit();
}
if ($m == 'login' && (isset($_SESSION['callcenter_uid']) && intval($_SESSION['callcenter_uid']) > 0)) {
	header('Location: ' . URL);
	exit();
}

define('ROLE', isset($_SESSION['callcenter_role']) ? $_SESSION['callcenter_role'] : null);
$_SESSION['last_time'] = TIME;
/*
$_online_users = memory('enable') ? memory('get', 'online_users') : false;
var_dump($_online_users);
 */

$DB = new mysqli($C['db']['host'], $C['db']['user'], $C['db']['pswd'], $C['db']['name']);
$DB->connect_errno && exit('DB Connection Error.');
$DB->query("SET character_set_connection=" . $C['db']['char'] . ", character_set_results=" . $C['db']['char'] . ", character_set_client=binary");

$_raw_log = array('GET' => $_GET, 'POST' => $_POST, 'REQUEST' => $_REQUEST, 'SERVER' => $_SERVER);

$query = sprintf("insert into `%s%s` set `dateline`='%s', `raw`='%s';",
	$C['db']['pfix'],
	'cc_rawlog',
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