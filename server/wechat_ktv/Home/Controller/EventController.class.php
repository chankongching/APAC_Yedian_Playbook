<?php
namespace Home\Controller;
use Think\Controller;

class EventController extends CommonController {
	public function __construct() {
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Accept, Content-Type, X-KTV-Application-Name, X-KTV-Vendor-Name, X-KTV-Application-Platform, X-KTV-User-Token");
	}

	public function push() {
		$this->display();
	}

	public function index() {

	}

	public function gdt() {
		redirect('http://letsktv.chinacloudapp.cn/dist/event/');
	}

	public function hjd() {
		redirect('http://letsktv.chinacloudapp.cn/dist/oneyuan');
	}
	public function getKtvinfo() {
		if (IS_GET) {
			$ktvid = session('xktvid');
			if ($ktvid != null) {
				$search = '/^ZCG*$/';
				if (preg_match($search, $ktvid)) {
					die(json_encode(array('result' => 0,
						'msg' => 'get ZCG info success',
						'is_zcg' => 1,
					)));
				}
				$ktvinfo = M('xktv', 'ac_')->where(array('xktvid' => $ktvid))->find();
				if ($ktvinfo != NULL) {
					die(json_encode(array('result' => 0,
						'msg' => 'get KTV info success',
						'info' => array('name' => $ktvinfo['name'],
							'description' => $ktvinfo['description'],
							'lat' => $ktvinfo['lat'],
							'lng' => $ktvinfo['lng'],
							'id' => intval($ktvinfo['id']),
							'ktvid' => $ktvinfo['xktvid'],
							'room_pic_big' => '/uploads/room/' . $ktvinfo['bpic1'],
							'room_pic_small' => '/uploads/room/' . $ktvinfo['spic1'],
						))));
				} else {
					die(json_encode(array(
						'result' => 1,
						'msg' => 'get ktv info failed')));
				}
			} else {
				die(json_encode(array(
					'result' => 1,
					'msg' => 'get ktv info failed')));
			}
		} else {

			die(json_encode(array(
				'result' => 1,
				'msg' => 'Method failed')));
		}

	}

	public function enter() {
		if (I('get.ktvid') != null) {
			session('xktvid', I('get.ktvid'));
		}
		redirect('http://letsktv.chinacloudapp.cn/dist/event/');
	}
	public function show() {

	}

	public function is_subcribe() {
		$openid = I('get.openid');
		$options = C('Wx_Options');
		$this->weObj = new TPWechatController($options);
		$userinfo = $this->weObj->getUserInfo($openid);
		if ($userinfo['subscribe'] === 1) {
			die(json_encode(array('result' => 0, 'msg' => 'has subcribe')));
		} elseif ($userinfo['subscribe'] === 0) {
			die(json_encode(array('result' => 1, 'msg' => 'not subcribe')));
		} else {
			die(json_encode(array('result' => 400, 'msg' => 'openid error')));
		}
	}

	public function ktv_recommend() {
		$ktvlist = M('xktv', 'ac_')->where(array('id' => array('IN', array(102, 189, 180, 1, 85, 206)), 'status' => 1))->field('id,xktvid as ktvid,description,name,district,address,telephone,spic1 as room_pic_small,bpic1 as room_pic_big')->select();
		foreach ($ktvlist as $key => $value) {
			$ktvlist[$key]['room_pic_small'] = '/uploads/room/' . $value['room_pic_small'];
			$ktvlist[$key]['room_pic_big'] = '/uploads/room/' . $value['room_pic_big'];
		}
		die(json_encode(array('result' => 0, 'msg' => 'get recommend_ktv success', 'data' => $ktvlist, 'count' => count($ktvlist))));
	}

	public function addMobile() {
		if (IS_GET) {
			$openid = I('get.openid');
			$mobile = I('get.mobile');
			// $coupon_type = array(13, 14, 15);
//			$coupon_type = array(16, 17, 18, 19, 20, 21, 22, 23, 24);
            $coupon_type = array(44,45,46);
			if (M('event_mobile', 'ac_')->add(array('mobile' => $mobile, 'openid' => $openid, 'create_time' => date('Y-m-d H:i:s'))) > 0) {
				$userinfo = M('platform_user', 'ac_')->where(array('openid' => $openid))->find();
				$coupon = M('coupon', 'ac_')->where(array('userid' => $userinfo['id'], 'available' => 0, 'type' => array('IN', $coupon_type)))->find();
				if ($coupon != null) {
					if (M('coupon', 'ac_')->where(array('userid' => $userinfo['id'], 'available' => 0, 'type' => array('IN', $coupon_type)))->save(array('available' => 1, 'status' => 0)) > 0) {

						$coupon1 = M('coupon', 'ac_')->where(array('userid' => $userinfo['id'], 'available' => 1, 'type' => array('IN', $coupon_type)))->find();
						if (M('event_mobile', 'ac_')->where(array('mobile' => $mobile, 'openid' => $openid))->save(array('couponid' => $coupon1['id'], 'update_time' => date('Y-m-d H:i:s'))) > 0) {
							die(json_encode(array('result' => 0, 'msg' => 'add moible success')));
						}
					} else {
						die(json_encode(array('result' => 0, 'msg' => 'add moible success')));
					}
				}

			}
		}
	}

	public function CloseHJD() {
		if (IS_GET) {
			$ss = I('get.ss');
			$actid = I('get.act');
			$status = I('get.status');
			if ($ss == 'yedianyule') {
				if ($status != 1 && $status != 2) {
					$result = M('oneyuan_event', 'ac_')->where(array('actid' => $actid))->select();
					echo '<table>';
					foreach ($result as $key => $value) {
						echo '<tr>';
						echo '<td>' . $value['mobile'] . '</td><td>' . $value['userid'] . '</td><td>' . $value['zhongjiangma'] . '</td><td>' . $value['create_time'] . '</td>';
						echo '</tr>';
					}
					echo '</table>';
				} else {
					$status = $status == 2 ? 0 : 1;
					M('oneyuan', 'ac_')->where(array('id' => $actid))->save(array('status' => $status));
					echo 'stop / start OK<br />';
				}

			} else {
				echo 'HeheM';
			}
		}
	}

}