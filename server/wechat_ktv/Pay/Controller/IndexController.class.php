<?php
namespace Pay\Controller;
use Think\Controller;

class IndexController extends Controller {
	public function __construct() {
		parent::__construct();
		$this->wxpay_config = C('wxpay');
	}

	public function index() {
		echo 'sdf';
	}
	public function payapi() {
		vendor('WxPay/Data');
		vendor('WxPay/Api');
		header("Access-Control-Allow-Headers: Accept, Content-Type, X-KTV-Application-Name, X-KTV-Vendor-Name, X-KTV-Application-Platform, X-KTV-User-Token");

		$post_array = $_POST;
		if (empty($post_array)) {
			$post_data = file_get_contents("php://input");
			$post_array = json_decode($post_data, true);
		}
		$Out_trade_no = !empty($post_array["trade_no"]) ? $post_array["trade_no"] : $this->wxpay_config['MCHID'] . date("YmdHis");
		$prepay_order = M('prepay_order', 'ac_')->where(array('trade_no' => $Out_trade_no))->find();
		if ($prepay_order != null) {
			$jsApiParameters = array(
				'appId' => $prepay_order['appid'],
				'nonceStr' => $prepay_order['noncestr'],
				'package' => $prepay_order['package'],
				'paySign' => $prepay_order['paysign'],
				'signType' => $prepay_order['signtype'],
				'timeStamp' => $prepay_order['timestamp'],
			);
			die(json_encode(array('result' => 0, 'signinfo' => array('jsApiParameters' => $jsApiParameters)), true));
		} else {
			$prepay_order_id = M('prepay_order', 'ac_')->add(array('trade_no' => $Out_trade_no, 'create_time' => date('Y-m-d H:i:s', time()), 'update_time' => date('Y-m-d H:i:s', time())));
			if ($prepay_order_id > 0) {
				$orderinfo = $this->getOrderInfo($Out_trade_no);
				$body = $orderinfo['body'];
				$attach = $orderinfo['attach'];
				$fee = $orderinfo['fee'];
				$starttime = date("YmdHis");
				$expire = date("YmdHis", time() + 600);
				$Goods_tag = $orderinfo['Goods_tag'];
				$notify_url = "http://letsktv.chinacloudapp.cn/wechat_ktv/Pay/Notify/index";
				$Trade_type = "JSAPI";
				$openid = !empty($post_array["openid"]) ? $post_array["openid"] : "okwyOwpvP0WJfi0GhGxzQ5sDJMCY";
				$tools = new jssdkController();
				$input = new \WxPayUnifiedOrder();
				$input->SetBody($body);
				$input->SetAttach($attach);
				$input->SetOut_trade_no($Out_trade_no);
				$input->SetTotal_fee($fee);
				$input->SetTime_start($starttime);
				$input->SetTime_expire($expire);
				$input->SetGoods_tag($Goods_tag);
				$input->SetNotify_url($notify_url);
				$input->SetTrade_type($Trade_type);
				$input->SetOpenid($openid);
				$order = \WxPayApi::unifiedOrder($input);
				$jsApiParameters_str = $tools->GetJsApiParameters($order);
				$jsApiParameters = json_decode($jsApiParameters_str, true);
				$Paramerter_info = array(
					'appid' => $jsApiParameters['appId'],
					'prepay_id' => $order['prepay_id'],
					'noncestr' => $jsApiParameters['nonceStr'],
					'package' => $jsApiParameters['package'],
					'paysign' => $jsApiParameters['paySign'],
					'signtype' => $jsApiParameters['signType'],
					'timestamp' => $jsApiParameters['timeStamp'],
					'update_time' => date('Y-m-d H:i:s', time()),
				);
				$update_result = M('prepay_order', 'ac_')->where(array('id' => $prepay_order_id))->save($Paramerter_info);
				if ($update_result == 1) {
					die(json_encode(array('result' => 0, 'signinfo' => array('jsApiParameters' => $jsApiParameters)), true));
				}

			}

		}

	}

	public function getOrderInfo($ordercode = '') {
		if ($ordercode != '') {
			$orderinfo = M('order', 'ac_')->where(array('code' => $ordercode, 'status' => 17))->find();
			if ($orderinfo != null) {
				return array('body' => $orderinfo['ktvid'], 'attach' => $orderinfo['ktvid'], 'fee' => $orderinfo['price'] * 100, 'Goods_tag' => $orderinfo['roomtypeid']);
			}
		}
	}
}