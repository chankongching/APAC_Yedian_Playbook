<?php
namespace Home\Controller;

use Think\Controller;

class ActivityController extends CommonController {
	public function __construct() {
		parent::__construct();
		$session_get_action = array('index');
		$session_not_get_action = array('makeSjCoupon', 'makeOrderQrcode');
		if (in_array(ACTION_NAME, $session_get_action)) {
			session(null);
		}
		if (!session('?openid')) {
			$wc = new WeChatController();
			$token = $wc->getOauthAccessToken();
			$openid = $token['openid'];
			$tmpmanager = M('ktvmanager')->where(array('openid' => $openid, 'status' => '1'))->find();
			if ($tmpmanager != NULL) {
				session('role', 'manager');
				session('ktvid', $tmpmanager['ktvid']);
			}
			$tmpempl = M('ktvemp')->where(array('openid' => $openid, 'status' => '1'))->find();
			if ($tmpempl != NULL) {
				session('role', 'emplyee');
				session('ktvid', $tmpempl['ktvid']);
			}
			// echo $openid;
			session('openid', $openid);
		}
		if (!in_array(ACTION_NAME, $session_not_get_action)) {
			if (!session('?openid') || !session('?role')) {
				$this->error('非法请求');
			}
		}
	}

	public function index() {
		// if (session('role') == 'emplyee') {
		$this->redirect('activity_list');
		// } else {
		// $this->error('请使用服务员账号');
		// }
	}

	//活动列表
	public function activity_list() {
		$this->display();
	}

	public function getResultBykey() {
		$result_array = array();
		if (IS_AJAX) {
			$key = I('post.key');
			$Qrcode = new QrcodeController();
			$content = $Qrcode->getContentBykey($key);
			if ($content['type'] == 'coupon_sj') {
				$rs = M('coupon', 'ac_')->where(array('id' => $content['coupon_id']))->find();
				if ($rs != null) {
					$timeLimit = $rs['expire_time'] - time();

					if ($timeLimit < 7200 && $timeLimit > 0) {
						if ($rs['status'] == '0') {
							M('order', 'ac_')->where(array('id' => $rs['orderid'], 'status' => 3))->save(array('status' => 5));
							M('querenma')->where(array('status' => 1, 'orderid' => $rs['orderid'], 'ktvid' => session('ktvid')))->save(array('status' => 0, 'update_time' => date("Y-m-d H:i:s"), 'update_user' => $this->getuidbyopenid(session('openid'))));
							$result_array['msg'] = 'scan success';
							$result_array['result'] = '0';
							$result_array['data'] = $this->getCouponContentByID($rs['type']);
							$result_array['coupon_id'] = $rs['id'];
							die(json_encode($result_array));
						} elseif ($rs['status'] == '1') {
							$result_array['msg'] = '扫码成功,优惠券已失效';
							$result_array['result'] = '400';
							die(json_encode($result_array));
						}

					} elseif ($timeLimit > 7200) {
						$result_array['msg'] = '扫码成功,兑换时间未到';
						$result_array['result'] = '400';
						die(json_encode($result_array));
					} else {
						M('coupon', 'ac_')->where(array('id' => $content['coupon_id']))->save(array('status' => '2', 'update_time' => date("Y-m-d H:i:s")));
						$result_array['msg'] = '扫码成功,优惠券过期';
						$result_array['result'] = '400';
						die(json_encode($result_array));
					}

				} else {
					M('coupon', 'ac_')->where(array('id' => $content['coupon_id']))->save(array('status' => '2', 'update_time' => date("Y-m-d H:i:s")));
					$result_array['msg'] = '扫码成功,优惠券过期或失效';
					$result_array['result'] = '400';
					die(json_encode($result_array));
				}

			} else {
				$result_array['msg'] = 'scan success,No Coupon Content';
				$result_array['result'] = '400';
				die(json_encode($result_array));
			}
		} else {
			$result_array['msg'] = 'scan success,Code Error';
			$result_array['result'] = '405';
			die(json_encode($result_array));
		}
	}
	public function dui_success() {
		if (IS_AJAX) {
			$result_array = array();
			$couponid = I('post.couponid');
			$rs = M('coupon', 'ac_')->where(array('id' => $couponid, 'status' => '0'))->find();
			if (M('coupon', 'ac_')->where(array('id' => $couponid, 'status' => '0'))->save(array('status' => '1', 'update_time' => date("Y-m-d H:i:s")))) {
				if (M('sj_record')->data(array('couponid' => $couponid,
					'count' => $this->getCount($rs['type']),
					'emp_openid' => session('openid'),
					'ktvid' => session('ktvid'),
					'userid' => $rs['userid'],
					'create_time' => date("Y-m-d H:i:s"),
					'coupon_type' => $rs['type'])
				)->add()) {
					$result_array['msg'] = 'dui success';
					$result_array['result'] = '0';
					$result_array['url'] = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FHome%2FActivity%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect';
					die(json_encode($result_array));
				} else {
					$result_array['msg'] = 'Coupon record save failed';
					$result_array['result'] = '400';
					die(json_encode($result_array));
				}
			} else {
				$result_array['msg'] = 'Coupon have dui';
				$result_array['result'] = '400';
				die(json_encode($result_array));
			}
		} else {
			echo 'suddecc';
//            $this->success('兑换成功',U('Activity/index'),5);
		}

	}

	protected function getCount($couponType) {
		$rs = M('coupon_type', 'ac_')->where(array('id' => $couponType))->find();
		if ($rs != null) {
			return $rs['count'];
		}
	}

	protected function getCouponContentByID($id) {
		$rs = M('coupon_type', 'ac_')->where(array('id' => $id))->find();
		if ($rs != null) {
			return $rs['name'] . '<br/>' . $rs['count'] . '瓶' . '<br/>' . $rs['desc'];
		}
	}

	public function makeSjCoupon() {
		if (IS_POST) {
			$post_data = file_get_contents("php://input");
			$post_array = json_decode($post_data, true);
			$coupon_id = $post_array['coupon_id'];
			if ($coupon_id != null) {
				$qr = new QrcodeController();
				$data = array();
				$data['type'] = 'coupon_sj';
				$data['coupon_id'] = $coupon_id;
				$qrimg = $qr->CreateQrcodeCoupon($data);
				die(json_encode(array('status' => 0, 'qrimg' => $qrimg['filename'], 'qrcodeid' => $qrimg['id']), true));
			} else {
				echo 'error';
			}
		}
	}

	public function makeOrderQrcode() {
		if (IS_POST) {
			$post_data = file_get_contents("php://input");
			$post_array = json_decode($post_data, true);
			// var_dump($post_array);
			$order_id = $post_array['order_id'];
			if ($order_id != null) {
				$qr = new QrcodeController();
				$data = array();
				$data['type'] = 'order';
				$data['order_id'] = $order_id;
				$qrimg = $qr->CreateQrcodeOrder($data);
				die(json_encode(array('status' => 0, 'qrimg' => $qrimg['filename'], 'qrcodeid' => $qrimg['id']), true));
			} else {
				echo 'error';
			}
		}
	}

	public function test() {
		$qr = new QrcodeController();
		$data = array();
		$data['type'] = 'coupon_sj';
		$data['coupon_id'] = '1';
		echo $qr->testCreateQrcode($data);
	}

	protected function getuidbyopenid($openid) {
		$result = M('ktvemp')->where(array('openid' => $openid))->find();
		return $result['id'];
	}

}