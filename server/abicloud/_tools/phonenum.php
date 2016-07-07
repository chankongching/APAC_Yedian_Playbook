<?php
date_default_timezone_set('Asia/Shanghai');
header('Content-type: application/json');
define('SECRET', 'tFKz7pBiKBRXF2');

$phone = (isset($_GET['phone']) && !empty(trim($_GET['phone']))) ? trim($_GET['phone']) : die(json_encode(array(
	'status' => '0',
	'error' => 'Phone Number Empty.',
)));
$content = (isset($_GET['content']) && !empty(trim($_GET['content']))) ? trim($_GET['content']) : die(json_encode(array(
	'status' => '0',
	'error' => 'content Number Empty.',
)));
// !preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/", $phone) && die(json_encode(array(
//     'status'    => '0',
//     'error'     => 'Phone Number Invalid.'
// )));
// $content = (isset($_POST['content']) && !empty(trim($_POST['content']))) ? trim($_POST['content']) : die(json_encode(array(
//     'status'    => '0',
//     'error'     => 'Content Empty.'
// )));
// $authkey = (isset($_POST['authkey']) && !empty(trim($_POST['authkey']))) ? trim($_POST['authkey']) : die(json_encode(array(
//     'status'    => '0',
//     'error'     => 'Auth Key Empty.'
// )));
// ($authkey != md5(SECRET.$phone.$content)) && die(json_encode(array(
//     'status'    => '0',
//     'error'     => 'Verification Failed.'
// )));
// $captcha = rand(100000, 999999);
// $content = "您正在使用积分兑换礼品，此次验证码是".$captcha."。【夜点应用】";
$sendSms = new SendSmsHttp();
$sendSms->mobile = $phone;
$sendSms->content = urldecode($content);
$res = $sendSms->send();
if (!$res) {
	die(json_encode(array(
		'status' => '0',
		'error' => 'Failed.',
	)));
} else {
	// echo $sendSms->content.$sendSms->mobile;
	// var_dump($res);
	die(json_encode(array(
		'status' => '1',
		'error' => 'Success.',
		'msg' => $sendSms->content,
	)));
}

// $phone = '18602163052';
// $captcha = rand(100000, 999999);
//$content = '您正在绑定夜点促销员平台，此次验证码是'.$captcha.'，如果不是本人操作，请忽略本条消息。【夜点应用】';
//$content = '您正在使用积分兑换礼品，此次验证码是'.$captcha.'。【夜点应用】';
//$content = '您好，您已经成功兑换家乐福购物卡100元 x 4，面值400元，兑换码'.$captcha.'。请于任意周一早10:00-晚18:00，至北京大厦六层C05元隆雅图（北京路地铁站A口出20米即到），凭手机号码，身份证，以及兑换码领取礼品。此消息转发无效，该兑换码只能使用一次，兑换成功后不支持退换，咨询电话：020-66634577。【夜点应用】';
//$content = '您好，您已经成功预订百威CEO大包，开始时间为2012年12月4日19:00，持续时间为6个小时，请提前半小时到店，凭借手机号码到前台消费。如有任何问题，请拨打夜点客服电话4006507351，感谢您对夜点的支持。【夜点应用】';
//$content = '抱歉，您已经预定的百威CEO大包，开始时间为2012年12月4日19:00，持续时间为6个小时，因预定房间已满，所以预定不成功。请尝试选择其他时段或者其他KTV。感谢您对夜点的支持。【夜点应用】';

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
		}
		$line = str_replace("<string xmlns=\"http://entinfo.cn/\">", "", $line);
		$line = str_replace("</string>", "", $line);
		$result = explode("-", $line);
		if (count($result) > 1) {
//            $this->errorMsg = isset($line) ? $line : '未知错误';
			//            echo $this->errorMsg."\n";
			//            echo '时间: '.date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']).', 失败, 手机号码: '.$argv['mobile'].' 返回值为:'.$line.' 内容：'.$argv['content']."\n";
			return false;
		} else {
//            echo '时间: '.date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']).', 成功, 手机号码: '.$argv['mobile'].' 返回值为:'.$line.' 内容：'.$argv['content']."\n";
			return true;
		}
	}
}