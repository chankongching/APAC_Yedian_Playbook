<?php
namespace Home\Controller;

use Think\Controller;

class JayEventController extends CommonController {
	public function __construct() {
		parent::__construct();
		$_contentType = 'application/json; charset=utf-8';
		header("Content-Type: $_contentType", true);
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Accept, Content-Type, X-KTV-Application-Name, X-KTV-Vendor-Name, X-KTV-Application-Platform, X-KTV-User-Token");
		$this->event_status = array('status' => 0);
		$this->address = "广州KPARTY量贩式KTV（赤岗店）";
	}

	private function getUserStatus($openid) {
		$orderinfo = M('jaycn_event_order', 'ac_')->where(array('openid' => $openid))->find();
		if ($orderinfo != null) {
			$roominfo = M('jaycn_event', 'ac_')->where(array('id' => $orderinfo['roomid']))->find();
			$users = M('jaycn_event_order', 'ac_')->where(array('roomid' => $roominfo['id']))->select();
			$userlist = array();
			foreach ($users as $key => $value) {
				$userlist[] = array('name' => $value['name'], 'sex' => intval($value['sex']), 'openid' => $value['openid']);
			}
			if ($roominfo != null) {
				return array('status' => 1, 'order_info' => array(
					'id' => intval($roominfo['id']),
					'nick_name' => $orderinfo['name'],
					'name' => $roominfo['name'],
					'duijiangma' => $orderinfo['order_qr_code'],
					'mobile' => $orderinfo['mobile'],
					'sex' => intval($orderinfo['sex']),
					'date' => $roominfo['date'] == 23 ? '2016-07-23' : '2016-07-24',
					'count' => intval($roominfo['count']),
					'total' => 10,
					'address' => $this->address,
					'user_list' => $userlist,
				));
			}

		} else {
			return array('status' => 0);
		}
	}

	private function getQrCode($orderid) {
		$qr = new QrcodeController();
		$urlinfo = $qr->CreateQrcodeOrder(array('type' => 'jaycn', 'orderid' => $orderid));
		return $urlinfo;
	}

	public function roomlist() {
		if (IS_POST) {
			$openid = I('post.openid');
			$list1 = M('jaycn_event', 'ac_')->where(array('date' => 23))->order('id')->field('name,total,count,id')->select();
			foreach ($list1 as $key => $value) {
				$list1[$key]['id'] = intval($value['id']);
				$list1[$key]['count'] = intval($value['count']);
				$list1[$key]['total'] = intval($value['total']);
			}
			$count1 = intval(M('jaycn_event', 'ac_')->where(array('date' => 23))->Sum('count'));

			$list2 = M('jaycn_event', 'ac_')->where(array('date' => 24))->order('id')->field('name,total,count,id')->select();
			foreach ($list2 as $key => $value) {
				$list2[$key]['id'] = intval($value['id']);
				$list2[$key]['count'] = intval($value['count']);
				$list2[$key]['total'] = intval($value['total']);
			}
			$count2 = intval(M('jaycn_event', 'ac_')->where(array('date' => 24))->Sum('count'));

			$data = array(
				'result' => 0,
				'event_status' => $this->event_status,
				'is_join' => $this->getUserStatus($openid),
				'msg' => 'get roomlist info success',
				'address' => $this->address,
				'roomlist' => array(
					'23' => array('total' => 200, 'count' => $count1, 'list' => $list1),
					'24' => array('total' => 200, 'count' => $count2, 'list' => $list2),
				),

			);
			die(json_encode($data, true));
		} else {
			die(json_encode(array('result' => 400, 'msg' => 'method error')));
		}

	}

	private function getRoomInfo($roomid) {
		$room = M('jaycn_event', 'ac_')->where(array('id' => $roomid))->find();
		if ($room != null) {
			return array('name' => $room['name'], 'date' => $room['date'] == 23 ? '2016-07-23' : '2016-07-24');
		} else {
			return null;
		}

	}

	public function roomDetail() {
		$roomid = I('get.id');
		$user_result = M('jaycn_event_order', 'ac_')->where(array('roomid' => $roomid))->select();
		$userlist = array();
		foreach ($user_result as $key => $value) {
			$userlist[] = array('name' => $value['name'],
				'sex' => intval($value['sex']),
				'openid' => trim($value['openid']));
		}
		$room = $this->getRoomInfo($roomid);
		$roominfo = array('date' => $room['date'],
			'name' => $room['name'],
			'address' => '广州KPARTY量贩式KTV（赤岗店）',
			'id' => intval($roomid),
			'userlist' => $userlist,
			'total' => 10,
			'count' => count($userlist));

		$data = array('result' => 0, 'event_status' => $this->event_status, 'roominfo' => $roominfo);
		echo json_encode($data, true);
	}

	public function submit_info() {
		$array_result = array(
			'result' => 400,
			'msg' => 'submit error',
		);
		if (IS_POST) {
			$_mobile = I('post.mobile');
			$_name = I('post.nick_name');
			$_sex = I('post.sex');
			$_openid = I('post.openid');
			$_id = I("post.id");
			$mobile = isset($_mobile) ? trim($_mobile) : '';
			$id = isset($_id) ? intval($_id) : 0;
			$sex = isset($_sex) ? intval($_sex) : 0;
			$name = isset($_name) ? trim($_name) : '';
			$openid = isset($_openid) ? trim($_openid) : '';
			if (M("jaycn_event_order", 'ac_')->where(array('openid' => $openid))->find() == null) {
				if ($mobile != '' && $name != '' && $openid != '' && $id != 0) {
					$add_result = M('jaycn_event_order', 'ac_')->add(array('mobile' => $mobile, 'name' => $name, 'sex' => $sex, 'openid' => $openid, 'roomid' => $id, 'create_time' => date('Y-m-d H:i:s')));

					if ($add_result > 0) {
						M('jaycn_event', 'ac_')->where(array('id' => $id))->setInc('count', 1);
						$qrcode = $this->getQrCode($add_result);
						$array_result['debug'] = array('qr' => $qrcode);
						M('jaycn_event_order', 'ac_')->where(array('id' => $add_result))->save(array('order_qr_code' => '/wechat_ktv/' . $qrcode['filename']));
					}
					$array_result['msg'] = 'add user info success';
					$array_result['address'] = $this->address;
					$array_result['event_status'] = $this->event_status;
					$array_result['result'] = 0;
					$array_result['hexiaoma'] = '/wechat_ktv/' . $qrcode['filename'];
					die(json_encode($array_result));
				} else {
					$array_result['msg'] = 'params error';
					die(json_encode($array_result));
				}
			} else {
				$array_result['msg'] = 'has add';
				die(json_encode($array_result));
			}

		} else {
			die(json_encode($array_result));
		}
	}

	public function checkstatus() {
		if (IS_POST) {
			$openid = I('post.openid');
			$order = M('jaycn_event_order', 'ac_')->where(array('openid' => $openid))->find();
			if ($order != null) {
				$roomid = $order['roomid'];
				$roominfo = M('jaycn_event', 'ac_')->where(array('id' => $roomid))->find();
				if ($roominfo != null) {
					$orderlist = M('jaycn_event_order', 'ac_')->where(array('roomid' => $roomid))->select();
					if ($orderlist != null) {
						$userlist = array();
						foreach ($orderlist as $key => $value) {
							$userlist[] = array('name' => $value['name'], 'sex' => intval($value['sex']), 'openid' => $value['openid']);
						}
						$result_array = array(
							'result' => 0,
							'msg' => 'get info success',
							'Event_info' => array(
								'id' => intval($roomid),
								'name' => $roominfo['name'],
								'user_list' => $userlist,
								'date' => $roominfo['date'] == 23 ? '2016-07-23' : '2016-07-24',
								'total' => intval($roominfo['total']),
								'count' => intval(10 - count($userlist)),
								'hexiaoma' => $order['order_qr_code'],
								'address' => $this->address,
							),
						);
						die(json_encode($result_array));
					}
				}
			}

		} else {
			die(json_encode(array('result' => 400, 'msg' => 'method error')));
		}
	}

}