<?php
namespace Home\Controller;

use Think\Controller;

class QrcodeController extends CommonController {
	public function __construct() {
		parent::__construct();
		vendor('phpqrcode.phpqrcode');
	}

	public function CreateQrcode($data, $level = 'L', $size = 6) {
		$path = 'Public/qrcode/';
		// 纠错级别：L、M、Q、H
		// 点的大小：1到10,用于手机端4就可以了
		// 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
		// $path = "images/";
		// 生成的文件名
		$mmm = rand(1, 10000);
		$fileName = $path . time() . '_' . $mmm . '_' . $size . '.png';
		$json_data = json_encode($data, true);
//        echo $json_data;
		$kdata = $this->createKey();
		$rs = M('qr_service')->add(array('key' => $kdata, 'content' => $json_data, 'file' => $fileName));
		\QRcode::png($kdata, $fileName, $level, $size);
		return array('filename' => $fileName, 'id' => $rs);
	}

	private function createKey($len = 32) {
		// 校验提交的长度是否合法
		if (!is_numeric($len) || ($len > 32) || ($len < 16)) {
			return;
		}
		// 获取当前时间的微秒
		list($u, $s) = explode(' ', microtime());
		$time = (float) $u + (float) $s;
		// 产生一个随机数
		$rand_num = rand(100000, 999999);
		$rand_num = rand($rand_num, $time);
		mt_srand($rand_num);
		$rand_num = mt_rand();
		// 产生SessionID
		$sess_id = md5(md5($time) . md5($rand_num));
		// 截取指定需要长度的SessionID
		$sess_id = substr($sess_id, 0, $len);
		return $sess_id;
	}

	public function getContentBykey($key) {
		$rs = M('qr_service')->where(array('key' => $key, 'status' => '1'))->field('content')->find();
		if ($rs != null) {
			return json_decode($rs['content'], true);
		} else {
			return null;
		}
	}

	public function CreateQrcodeCoupon($data) {
		return $this->CreateQrcode($data);
	}
	public function CreateQrcodeOrder($data) {
		return $this->CreateQrcode($data);
	}
}