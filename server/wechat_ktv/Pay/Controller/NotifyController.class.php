<?php
namespace Pay\Controller;
use Think\Controller;

class NotifyController extends Controller {
	public function __construct() {
		parent::__construct();

		vendor('WxPay/Data');
		vendor('WxPay/Api');
	}

	public function index() {
		$xml = $GLOBALS["HTTP_RAW_POST_DATA"];
		if ($xml != '') {
			M('tmp', 'ver_')->add(array('content' => $xml, 'create_time' => time()));
			$result = \WxPayDataBase::FromXml($xml);
			if ($result['result_code'] == 'SUCCESS') {
				$ii = M('wxpaynotify', 'ac_')->add($result);
				$update_result = M('prepay_order', 'ac_')->where(array('trade_no' => $result['out_trade_no']))->save(array('status' => 1, 'update_time' => date('Y-m-d H:i:s', time())));
				if ($update_result == 1) {
					M('order', 'ac_')->where(array('code' => $result['out_trade_no']))->save(array('status' => 18, 'update_time' => date('Y-m-d H:i:s', time())));
				}
				echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
			}

		}

		// die(json_encode($result));

	}
}