<?php
namespace Home\Controller;
use Think\Controller;

class GiftOrderController extends CommonController {
	public function __construct() {
		parent::__construct();
		C('DB_PREFIX', 'ac_');
		// $this->token= 'j7291iwusjau1271'; //促销员产品
		$this->token = 'h72uwjsu1278qisi'; //夜点用户产品
	}
	public function import() {
		$profile = M('PlatformUser')->where(array('auth_type' => 'wechat'))->field('profile_data,id')->select();
		$giftorders = array();
		if (!is_null($profile)) {
			foreach ($profile as $key => $value) {
				// echo $value['id'].$value['profile_data'];
				$profile_tmp = unserialize($value['profile_data']);
				$orders = explode(',', $profile_tmp['giftorders']);
				foreach ($orders as $key1 => $value1) {
					if ($value1 != '') {
						$order = array();
						$order['userid'] = $value['id'];
						$order['order_no'] = $value1;
						$giftorders[] = $order;
					}

				}
			}
		}
		$giftorder = M('GiftOrder');
		echo $giftorder->addAll($giftorders);
	}
	public function check() {
		$profile = M('PlatformUser')->where(array('auth_type' => 'wechat'))->field('profile_data,id')->select();
		$giftorders = array();
		if (!is_null($profile)) {
			foreach ($profile as $key => $value) {
				// echo $value['id'].$value['profile_data'];
				$profile_tmp = unserialize($value['profile_data']);
				if ($profile_tmp['giftorders'] != NULL) {
					$orders = explode(',', $profile_tmp['giftorders']);
					foreach ($orders as $key1 => $value1) {
						if ($value1 != '') {
							$order = array();
							$order['userid'] = $value['id'];
							$order['order_no'] = $value1;
							echo M('GiftOrder')->where(array('order_no' => $value1, 'userid' => '0'))->data($order)->save();
							$giftorders[] = $order;
							// if($value1 == 'YC15121600116'){
							// 	echo $value['id'];
							// }
						}

					}
				} else {
					echo 2;
				}

			}
		}
		// $giftorder = M('GiftOrder');
		// echo $giftorder->addAll($giftorders);
	}
	public function checkStatus() {

	}

	public function Test() {
		$query = array();
		$query['sellorder_belong'] = 'yuanlongyd';
		$lists = array();
		$ccc = 0;
		// for ($i = $ccc; $i < $ccc + 2; $i++) {
		for ($i = $ccc; $i < $ccc + 5; $i++) {
			// for ($i = 19; $i < 30; $i++) {
			$result = $this->getUserorderlist($query, $i + 1, 50);
			$list = $result['data']['sellorder_list'];
			$lists[] = $list;
		}
		foreach ($lists as $k => $v) {
			foreach ($v as $kk => $vv) {
				$data = array(
					'order_status' => urldecode($vv['order_status']),
					'sellorder_receivecell' => $vv['sellorder_receivecell'],
					'sellorder_belong' => $vv['sellorder_belong'],
					'sellorder_datetime' => urldecode($vv['sellorder_datetime']),
					'sellorder_id' => $vv['sellorder_id'],
					'sellorder_remarks' => urldecode($vv['sellorder_remarks']),
					'sellordergoods_goodsid' => $vv['sellordergoods_list'][0]['sellordergoods_goodsid'],
					'sellordergoods_id' => $vv['sellordergoods_list'][0]['sellordergoods_id'],
					'sellordergoods_mainpic' => urldecode($vv['sellordergoods_list'][0]['sellordergoods_mainpic']),
					'sellordergoods_name' => urldecode($vv['sellordergoods_list'][0]['sellordergoods_name']),
					'sellordergoods_num' => $vv['sellordergoods_list'][0]['sellordergoods_num'],
					'sellordergoods_orderid' => $vv['sellordergoods_list'][0]['sellordergoods_orderid'],
				);
				// echo M('GiftOrder')->where(array('order_no' => $vv['sellorder_no']))->Count();
				if (M('GiftOrder')->where(array('order_no' => $vv['sellorder_no']))->data($data)->save()) {
					// echo '2222222'.M()->getlastsql();
					// echo 'OK';
				} elseif (M('GiftOrder')->where(array('order_no' => $vv['sellorder_no']))->Count() == 0) {
					// echo '11111'.M()->getlastsql();
					$data['order_no'] = $vv['sellorder_no'];
					M('GiftOrder')->add($data);
				} else {
					// echo M()->getlastsql();die();
					// $data['order_no']=$vv['sellorder_no'];
					// M('GiftOrder')->add($data);
				}
			}
		}
		echo "ok" . $ccc;
		// echo json_encode($lists);
	}
	public function TestM() {
		$query = array();
//		$query['sellorder_belong'] = 'yuanlongyd';
		$query['sellorder_datetime'] = urlencode('2016-03-01 00:00:00~2016-03-15 00:00:00');

		$lists = array();
		$ccc = 0;
		for ($i = $ccc; $i < $ccc + 20; $i++) {
			$result = $this->getUserorderlist($query, $i + 1, 100);
			$list = $result['data']['sellorder_list'];
			$lists[] = $list;
		}
		foreach ($lists as $k => $v) {
			foreach ($v as $kk => $vv) {
				$data = array(
					"sellorder_belong" => urldecode($vv["sellorder_belong"]),
					"sellorder_id" => urldecode($vv["sellorder_id"]),
					"sellorder_no" => urldecode($vv["sellorder_no"]),
					"sellorder_custmodno" => urldecode($vv["sellorder_custmodno"]),
					"sellorder_custmdate" => urldecode($vv["sellorder_custmdate"]),
					"sellorder_datetime" => urldecode($vv["sellorder_datetime"]),
					"order_status" => urldecode($vv["order_status"]),
					"sellorder_cost" => urldecode($vv["sellorder_cost"]),
					"sellorder_amount" => urldecode($vv["sellorder_amount"]),
					"sellorder_syscost" => urldecode($vv["sellorder_syscost"]),
					"sellorder_sysamount" => urldecode($vv["sellorder_sysamount"]),
					"sellorder_actax" => urldecode($vv["sellorder_actax"]),
					"sellorder_freight" => urldecode($vv["sellorder_freight"]),
					"sellorder_insurance" => urldecode($vv["sellorder_insurance"]),
					"sellorder_othercosts" => urldecode($vv["sellorder_othercosts"]),
					"sellorder_discount" => urldecode($vv["sellorder_discount"]),
					"sellorder_adjamount" => urldecode($vv["sellorder_adjamount"]),
					"sellorder_total" => urldecode($vv["sellorder_total"]),
					"sellorder_cashdeduction" => urldecode($vv["sellorder_cashdeduction"]),
					"sellorder_pointdeduction" => urldecode($vv["sellorder_pointdeduction"]),
					"sellorder_masadeduction" => urldecode($vv["sellorder_masadeduction"]),
					"sellorder_payamount" => urldecode($vv["sellorder_payamount"]),
					"sellorder_receiver" => urldecode($vv["sellorder_receiver"]),
					"sellorder_receivetel" => urldecode($vv["sellorder_receivetel"]),
					"sellorder_receivecell" => urldecode($vv["sellorder_receivecell"]),
					"sellorder_receiveprov" => urldecode($vv["sellorder_receiveprov"]),
					"sellorder_receivecity" => urldecode($vv["sellorder_receivecity"]),
					"sellorder_receivecounty" => urldecode($vv["sellorder_receivecounty"]),
					"sellorder_receiveaddr" => urldecode($vv["sellorder_receiveaddr"]),
					"sellorder_receivepost" => urldecode($vv["sellorder_receivepost"]),
					"sellorder_receiveemail" => urldecode($vv["sellorder_receiveemail"]),
					"sellorder_remarks" => urldecode($vv["sellorder_remarks"]),
					"sellorder_deliverytime" => urldecode($vv["sellorder_deliverytime"]),
					"sellorder_remindtime" => urldecode($vv["sellorder_remindtime"]),
					"sellorder_project" => urldecode($vv["sellorder_project"]),
					"sellorder_itemdesc" => urldecode($vv["sellorder_itemdesc"]),
					"sellorder_owner" => urldecode($vv["sellorder_owner"]),
					"sellorder_warename" => urldecode($vv["sellorder_warename"]),
					"transorder_id" => urldecode($vv["transorder_id"]),
					'sellordergoods_goodsid' => $vv['sellordergoods_list'][0]['sellordergoods_goodsid'],
					'sellordergoods_id' => $vv['sellordergoods_list'][0]['sellordergoods_id'],
					'sellordergoods_mainpic' => urldecode($vv['sellordergoods_list'][0]['sellordergoods_mainpic']),
					'sellordergoods_name' => urldecode($vv['sellordergoods_list'][0]['sellordergoods_name']),
					'sellordergoods_num' => $vv['sellordergoods_list'][0]['sellordergoods_num'],
					'sellordergoods_orderid' => $vv['sellordergoods_list'][0]['sellordergoods_orderid'],
				);
				if (M('GiftOrder')->where(array('order_no' => $vv['sellorder_no']))->data($data)->save()) {

				} elseif (M('GiftOrder')->where(array('order_no' => $vv['sellorder_no']))->Count() == 0) {
					$data['order_no'] = $vv['sellorder_no'];
					M('GiftOrder')->add($data);
				} else {
				}
			}
		}
		echo "ok" . $ccc;
//		echo json_encode($lists);
		echo count($lists);
	}

	public function TestMC() {
		$query = array();
//		$query['sellorder_belong'] = 'yuanlongyd';
		$query['sellorder_datetime'] = urlencode('2016-01-18 00:00:00~2016-01-19 00:00:00');
		$lists = array();
		$ccc = 0;
		for ($i = $ccc; $i < $ccc + 1; $i++) {
			$result = $this->getUserorderlist($query, $i + 1, 10);
			$list = $result['data']['sellorder_list'];
			$lists[] = $list;
		}
		foreach ($lists as $k => $v) {
			foreach ($v as $kk => $vv) {
				echo json_encode($vv);
			}
		}
		echo "ok" . $ccc;
		// echo json_encode($lists);
	}
	public function TestMCC() {
		$query = array();
		$query['sellorder_datetime'] = urlencode('2016-01-14 00:00:00~2016-01-16 00:00:00');
		$lists = array();
		$ccc = 0;
		for ($i = $ccc; $i < $ccc + 1; $i++) {
			$result = $this->getUserorderlist($query, $i + 1, 10);
			$list = $result['data']['sellorder_list'];
			$lists[] = $list;
		}
		foreach ($lists as $k => $v) {
			foreach ($v as $kk => $vv) {
				echo json_encode($vv);
			}
		}
		echo "ok" . $ccc;
		// echo json_encode($lists);
	}

	protected function getUrlCN($svname, $param) {
		$base = 'http://ws9.ylytgift.com/YDService.ashx';
		$url = '?svname=' . $svname;
		$url .= '&svpa=' . $this->getsvpastrCN($param);
		return $base . $url;
	}

	protected function getsvpastrCN($param) {
		$data = array();
		$data['token'] = $this->token;
		$data['check'] = $this->getcheckdataCN($param);
		$data['param'] = $this->getparamdataCN($param);
		$svpastr = json_encode($data);
		return $svpastr;
	}

	protected function getcheckdataCN($param = '') {
		$chkstr = base64_encode(strtoupper(md5(strtoupper(md5($this->JSON($param))))));
		// echo $chkstr;
		return $chkstr;
	}

	protected function getparamdataCN($param = '') {
//		foreach ($param as $key => $value) {
		//			if (!is_array($param[$key])) {
		//
		//				$param[$key] = urlencode($value);
		//			}
		//		}
		//		return json_encode($param);
		foreach ($param as $key => $value) {
			if (!is_array($param[$key])) {

				$param[$key] = urlencode($value);
			} elseif (is_array($param[$key])) {
				foreach ($param[$key] as $k => $v) {
					$param[$key][$k] = urlencode($v);
				}
			}
		}
		return json_encode($param);
	}

	// 根据产品ID获取产品详情
	public function getProductDetail($product_id = '') {
		$query = array();
		$query['user_acct'] = 'lp';
		$query['product_id'] = $product_id;
		$param = array();
		$param['query'] = $query;
		$url = $this->getUrlCN('Query_Protdetail', $param);
		return $this->get_curl_json($url);
	}
	// 获取产品列表
	public function getProductList($query, $page = '', $perpage_num = '', $order = '') {
		$param = array();
		if ($order != '') {
			$param['order'] = $order;
		}
		if ($page != '') {
			$param['page'] = $page;
		}
		if ($perpage_num != '') {
			$param['perpage_num'] = $perpage_num;
		}
		$param['query'] = $query;
		$url = $this->getUrlCN('Query_Protlist', $param);
		echo $url;
		return $this->get_curl_json($url);
	}
	// 获取产品列表
	public function getAreaList($query, $page = '', $perpage_num = '', $order = '') {
		$param = array();
		if ($order != '') {
			$param['order'] = $order;
		}
		if ($page != '') {
			$param['page'] = $page;
		}
		if ($perpage_num != '') {
			$param['perpage_num'] = $perpage_num;
		}
		$param['query'] = $query;
		$url = $this->getUrlCN('Query_Regionlist', $param);
		// echo $url;
		return $this->get_curl_json($url);
	}
	// 实物下单接口
	public function makeOrderReal($param) {
		// $url = $this->getUrl('Creat_Userorder', $param);
		$url = $this->getUrlCN('Creat_Userorder', $param);

		return $this->get_curl_json($url);

	}

	// 虚拟下单接口
	public function makeOrderVirtual($param) {

		$url = $this->getUrlCN('Creat_UserorderVirtual', $param);
		// echo $url;die();
		return $this->get_curl_json($url);
	}

	//查询订单接口
	public function getUserorderstatuslist($query, $page = '', $perpage_num = '', $order = '') {
		$param = array();
		if ($order != '') {
			$param['order'] = $order;
		}
		if ($page != '') {
			$param['page'] = $page;
		}
		if ($perpage_num != '') {
			$param['perpage_num'] = $perpage_num;
		}
		$param['query'] = $query;
		$url = $this->getUrlCN('Query_Userorderstatuslist', $param);
		// echo $url;
		return $this->get_curl_json($url);
	}
	// 查询所有订单接口
	public function getUserorderlist($query, $page = '', $perpage_num = '', $order = '') {
		$param = array();
		if ($order != '') {
			$param['order'] = $order;
		}
		if ($page != '') {
			$param['page'] = $page;
		}
		if ($perpage_num != '') {
			$param['perpage_num'] = $perpage_num;
		}
		$param['query'] = $query;
		$url = $this->getUrlCN('Query_Userorderlist', $param);
		// echo $url;
		return $this->get_curl_json($url);
	}

	// 通用的一些方法

	protected function get_curl_json($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			print_r(curl_error($ch));
		}
		curl_close($ch);
		return json_decode($result, TRUE);
	}

	public function encodeOperations(&$array, $function, $tocode = false, $oldcode = false, $apply_to_keys_also = false) {
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$this->encodeOperations($array[$key], $function, $apply_to_keys_also);
			} else {
				if ($tocode && $oldcode) {
					if (function_exists(mb_convert_encoding)) {
						$value = mb_convert_encoding($value, $tocode, $oldcode);
					} else {
						return "error";
					}
				}
				$array[$key] = $function($value);
			}

			if ($apply_to_keys_also && is_string($key)) {
				$new_key = $function($key);
				if ($new_key != $key) {
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
		return $array;
	}

	public function JSON($array) {
		// echo 'this is JSON';
		$this->encodeOperations($array, 'urlencode', true);
		$json = json_encode($array);
		return urldecode($json);
	}
}
