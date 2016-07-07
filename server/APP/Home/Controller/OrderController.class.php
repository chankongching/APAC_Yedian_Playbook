<?php
namespace Home\Controller;

use Think\Controller;

class OrderController extends CommonController {
	public function __construct() {
		parent::__construct();
		$session_get_action = array('index', 'index_history', 'QRyzm');
		if (in_array(ACTION_NAME, $session_get_action)) {
			session(null);
		}
		// echo 'sdfasdf'.$_SESSION['openid'].'121212121';
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
				$sjq_status = $this->checkSJ();
//                echo $sjq_status;die();
				if ($sjq_status != false) {
					session('sjq', $sjq_status);
				}
//                echo session('sjq');die();
			}
			// echo $openid;
			session('openid', $openid);
		}

		if (!session('?time_condition')) {
			session('time_condition', 0);
		}
		if ($_GET['r_mark'] == 'history') {
			session('time_condition', 1);

		} elseif ($_GET['r_mark'] == 'today') {
			session('time_condition', 0);
		}

		if (session('time_condition') == 1) {
			$this->today_condition_timestamp = array(
				'LT',
				mktime(0, 0, 0, date('m'), date('d'), date('Y')),
			);
			$this->today_condition = array(
				'LT',
				date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d'), date('Y'))),
			);
			$this->total_title = '历史纵览';
			$this->order_today = '历史订单';
		} elseif (session('time_condition') == 0) {
			$this->today_condition_timestamp = array('BETWEEN', array(mktime(0, 0, 0, date('m'), date('d'), date('Y')), mktime(23, 59, 59, date('m'), date('d'), date('Y'))));
			$this->today_condition = array('BETWEEN', array(date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d'), date('Y'))), date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d'), date('Y')))));
		}

	}

	public function testopenid() {
		session(null);
		if (IS_GET && I('get.openid') != '') {
			$openid = I('get.openid');
			// $session_get_action = array('bind', 'bindf', 'bindm', 'ktvmanage');
			// if (in_array(ACTION_NAME, $session_get_action)) {
			// 	// echo 'session destroy';
			// 	// session('[destroy]');
			// 	session(null);
			// }
			// echo 'sdfasdf'.$_SESSION['openid'].'121212121';
			if (!session('?openid')) {
				// $wc = new WeChatController();
				// $token = $wc->getOauthAccessToken();
				// $openid = $token['openid'];
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
		}
		var_dump($_SESSION);
	}

	protected function checkSJ() {
		$rs = M('xktv', 'ac_')->where(array('id' => session('ktvid')))->find();
		if ($rs != null && $rs['sjq'] > 0) {
			return $rs['sjq'];
		} else {
			return false;
		}
	}

	public function MakeOrder() {
		// echo 'sdfsf';die();
		if (session('role') == 'manager') {
			$order = M('Order', 'ac_', array(
				'DB_TYPE' => 'mysql', // 数据库类型
				'DB_HOST' => 'localhost', // 服务器地址
				'DB_NAME' => 'abicloud', // 数据库名
				'DB_USER' => 'website', // 用户名
				'DB_PWD' => 'WebSite456', // 密码
				'DB_PORT' => '3306', // 端口
			));
			$where = array();
			$where['ktvid'] = $this->getxktvid(session('ktvid'));
			$where['time'] = $this->today_condition_timestamp;
			$order_result = $order->where($where)->select();
			// $employees = array();
			$result_total = array('weichuli' => 0, 'youfang' => 0, 'wufang' => 0, 'yonghucancel' => 0, 'timeout' => 0, 'daodianqueren' => 0, 'duijiushui' => 0);
			foreach ($order_result as $key => $value) {
				if ($value['status'] == 1) {
					$result_total['weichuli']++;
				}
				if ($value['status'] == 3) {
					$result_total['youfang']++;
				}
				if ($value['status'] == 4) {
					$result_total['wufang']++;
				}
				if ($value['status'] == 7) {
					$result_total['yonghucancel']++;
				}
				if ($value['status'] == 14) {
					$result_total['timeout']++;
				}

			}
			$result_total['daodianqueren'] = M('querenma')->where(array('ktvid' => session('ktvid'), 'status' => '0', 'update_time' => $this->today_condition))->count();
			$result_total['duijiushui'] = M('sj_record')->where(array('ktvid' => session('ktvid'), 'create_time' => $this->today_condition))->count();
//			$employees = M('ktvemp')->where(array('ktvid' => session('ktvid'), 'status' => '1'))->select();
			$employees = M('ktvemp')->where(array('ktvid' => session('ktvid')))->select();
			foreach ($employees as $key => $value) {
				$e_tatal = array();
				$e_tatal['youfang'] = M('orderhistory')->where(array('to_do' => '3', 'openid' => $value['openid'], 'create_time' => $this->today_condition))->count();
				$e_tatal['wufang'] = M('orderhistory')->where(array('to_do' => '4', 'openid' => $value['openid'], 'create_time' => $this->today_condition))->count();
				$e_tatal['daodianqueren'] = M('querenma')->where(array('ktvid' => session('ktvid'), 'update_user' => $value['id'], 'update_time' => $this->today_condition))->count();
				$e_tatal['duijiushui'] = M('sj_record')->where(array('ktvid' => session('ktvid'), 'emp_openid' => $value['openid'], 'create_time' => $this->today_condition))->count();
				$employees[$key]['total'] = $e_tatal;
			}
			$this->assign('result_total', $result_total);
			$this->assign('order_count_total', count($order_result));
			$this->assign('employees', $employees);
			$this->total_title = isset($this->total_title) ? $this->total_title : '今日概况';
			$this->display('index');
		}
		if (session('role') == 'emplyee') {
			$order = M('Order', 'ac_', array(
				'DB_TYPE' => 'mysql', // 数据库类型
				'DB_HOST' => 'localhost', // 服务器地址
				'DB_NAME' => 'abicloud', // 数据库名
				'DB_USER' => 'website', // 用户名
				'DB_PWD' => 'WebSite456', // 密码
				'DB_PORT' => '3306', // 端口
			));
			$where = array();
			$where['status'] = '1';
			$where['ktvid'] = $this->getxktvid(session('ktvid'));
			$where['time'] = $this->today_condition_timestamp;
			$order_list = $order->where($where)->select();
			$count_list = count($order_list);
			foreach ($order_list as $key => $value) {
				// $order_list[$key]['time'] = ($value['endtime'] - $value['starttime']) / 3600;
				$order_list[$key]['time'] = date('Y-m-d H:i:s', $value['time']);
				$order_list[$key]['starttime'] = date("Y-m-d H:i:s", $value['starttime']);
				$order_list[$key]['endtime'] = date("Y-m-d H:i:s", $value['endtime']);
				$order_list[$key]['ktvname'] = $this->getxktvinfo(session('ktvid'), 'name');
				if ($order_list[$key]['taocantype'] == 0) {
					$order_list[$key]['roomtype'] = $this->getRoomTypeNameByTaocan($value['taocanid']);
				} elseif ($order_list[$key]['taocantype'] == 1) {
					$order_list[$key]['roomtype'] = $this->getRoomTypeNameById($value['roomtypeid']);
				}
				$order_list[$key]['userinfo'] = array(
					'display_name' => $this->getuserinfo($value['userid'], 'display_name'),
					'mobile' => $this->getuserinfo($value['userid'], 'mobile'),
				);
				$order_list[$key]['coupon_info_data'] = array(
					'name' => $this->getCouponInfo($value['couponid'], 'name'),
					'count' => $this->getCouponInfo($value['couponid'], 'count'),
				);
				$order_list[$key]['taocan'] = array(
					'price' => $this->getTaocanInfoByid($value['taocanid'], 'yd_price'),
					'desc' => $this->getTaocanInfoByid($value['taocanid'], 'desc'),
				);
				// $order_list[$key]['djq'] = $this->getCouponInfo($value['couponid']);

			}
			$where = array();
			$where['ktvid'] = session('ktvid');
			$where['openid'] = session('openid');
			$where['create_time'] = $this->today_condition;
			$order_history = M('orderhistory')->where($where)->select();
			if ($order_history != NULL) {
				$order_history_count = count($order_history);
				foreach ($order_history as $key => $value) {
					$order_history_info = $order->where(array('id' => $value['oid']))->find();
					// $order_history[$key]['time'] = ($order_history_info['endtime'] - $order_history_info['starttime']) / 3600;
					// $order_history[$key]['starttime'] = date("Y-m-d H:i:s", $order_history_info['starttime']);
					// $order_history[$key]['makeordertime'] = date("Y-m-d H:i:s", $order_history_info['time']);
					// $order_history[$key]['members'] = $order_history_info['members'];
					// $order_history[$key]['djq'] = $this->getCouponInfo($order_history_info['couponid']);
					// if ($order_history_info['roomtype'] == '1') {
					// 	$order_history[$key]['roomtype'] = '大包';
					// }
					// if ($order_history_info['roomtype'] == '2') {
					// 	$order_history[$key]['roomtype'] = '中包';
					// }
					// if ($order_history_info['roomtype'] == '3') {
					// 	$order_history[$key]['roomtype'] = '小包';
					// }
					// $order_history[$key]['phone'] = $this->getuserinfo($order_history_info['userid'], 'mobile');
					// $order_history[$key]['name'] = $this->getuserinfo($order_history_info['userid'], 'display_name');
					$order_history[$key]['time'] = date('Y-m-d H:i:s', $order_history_info['time']);
					$order_history[$key]['starttime'] = date("Y-m-d H:i:s", $order_history_info['starttime']);
					$order_history[$key]['endtime'] = date("Y-m-d H:i:s", $order_history_info['endtime']);
					$order_history[$key]['ktvname'] = $this->getxktvinfo(session('ktvid'), 'name');
					if ($order_history[$key]['taocantype'] == 0) {
						$order_history[$key]['roomtype'] = $this->getRoomTypeNameByTaocan($order_history_info['taocanid']);
					} elseif ($order_history[$key]['taocantype'] == 1) {
						$order_history[$key]['roomtype'] = $this->getRoomTypeNameById($value['roomtypeid']);
					}
					$order_history[$key]['userinfo'] = array(
						'display_name' => $this->getuserinfo($order_history_info['userid'], 'display_name'),
						'mobile' => $this->getuserinfo($order_history_info['userid'], 'mobile'),
					);
					$order_history[$key]['coupon_info_data'] = array(
						'name' => $this->getCouponInfo($order_history_info['couponid'], 'name'),
						'count' => $this->getCouponInfo($order_history_info['couponid'], 'count'),
					);
					$order_history[$key]['taocan'] = array(
						'price' => $this->getTaocanInfoByid($order_history_info['taocanid'], 'yd_price'),
						'desc' => $this->getTaocanInfoByid($order_history_info['taocanid'], 'desc'),
					);
					if ($order_history_info['status'] == '3') {

						$order_history[$key]['status_info'] = '有房';
					}
					if ($order_history_info['status'] == '4') {

						$order_history[$key]['status_info'] = '无房';
					}
					if ($order_history_info['status'] == '7') {

						$order_history[$key]['status_info'] = '用户取消';
					}
					if ($order_history_info['status'] == '14') {

						$order_history[$key]['status_info'] = '自动取消';
					}
					if ($order_history_info['status'] == '5') {

						$order_history[$key]['status_info'] = '已确认到店';
					}
				}
			} else {
				$order_history_count = 0;
			}
			$this->assign('empty_order', '<span class="empty">当前没有未处理订单</span>');
			$this->assign('empty_order_history', '<span class="empty">当前没有已处理订单</span>');
			$this->assign('order', $order_list);
			$this->assign('order_history', $order_history);
			$this->assign('count', $count_list);
			$this->assign('count_order_history', $order_history_count);
			$this->assign('openid', session('openid'));
			$this->order_today = isset($this->order_today) ? $this->order_today : '今日订单';
			$this->display('epl-index');
		}
	}

	protected function getTaocanInfoByid($value = '', $key = 'yd_price') {
		if ($value != '') {
			$taocaninfo = M('taocan_content', 'ac_')->where(array('id' => $value))->find();
			if ($taocaninfo != null) {
				return $taocaninfo[$key];
			}
		}
	}

	protected function getRoomTypeNameByTaocan($value = '') {
		if ($value != '') {
			$taocaninfo = M('taocan_content', 'ac_')->where(array('id' => $value))->find();
			if ($taocaninfo != null) {
				$roominfo = M('taocan_roomtype', 'ac_')->where(array('id' => $taocaninfo['roomtype']))->find();
				if ($roominfo != null) {
//					return 'ddddd';
					return $roominfo['name'];
				}
			}
		}
	}

	protected function getRoomTypeNameById($value = '') {
		if ($value != '') {
			$roominfo = M('taocan_roomtype', 'ac_')->where(array('id' => $value))->find();
			if ($roominfo != null) {
				return $roominfo['name'];
			}
		}
	}

	protected function getCouponInfo($id = 0, $key = 'name') {
		if ($id > 0) {
			$coupon = M('coupon', 'ac_')->where(array('id' => $id))->find();
			$couponType = M('coupon_type', 'ac_')->where(array('id' => $coupon['type']))->find();
			return $couponType[$key];
		} else {
			return '无';
		}
	}

	public function index() {
		if (!session('?openid') || !session('?role')) {
			// var_dump($_SERVER);
			$this->error('非法请求');
		}
		$this->redirect('MakeOrder');

	}

	/**
	 * 服务员确认验证码 并送积分 入口文件
	 */
	public function QRyzm() {
		if (!session('?openid') || !session('?role')) {
			// var_dump($_SERVER);
			$this->error('非法请求');
		}
		if (session('role') == 'emplyee') {
			$this->redirect('yzm_check');
		} else {
			$this->error('对不起,您不是服务员');
		}

	}

	/**
	 * 服务员确认验证码并送积分
	 */
	public function yzm_check() {
		if (IS_POST) {
			if (IS_AJAX) {
				$yzm = I('post.yzm');
				$qrminfo = M('querenma')->where(array('yzm' => $yzm, 'status' => '1', ktvid => session('ktvid')))->find();
				$orderid = $qrminfo['orderid'];
				$orderinfo = M('order', 'ac_')->where(array('id' => $orderid))->find();
				if ($orderinfo != NULL) {
					if (abs($orderinfo['starttime'] - time()) < 3600) {
						$add_points = 2250;
						if (M('querenma')->where(array('yzm' => $yzm, 'status' => '1', ktvid => session('ktvid')))->data(array('status' => '0', 'update_user' => $this->getuidbyopenid(session('openid')), 'update_time' => date('Y-m-d H:i:s')))->save()) {
							// $userPoints = M('UserPoints', 'ac_', array(
							//     'DB_TYPE' => 'mysql', // 数据库类型
							//     'DB_HOST' => 'localhost', // 服务器地址
							//     'DB_NAME' => 'abicloud', // 数据库名
							//     'DB_USER' => 'website', // 用户名
							//     'DB_PWD' => 'WebSite456', // 密码
							//     'DB_PORT' => '3306', // 端口
							// ));
							// if ($userPoints->where(array('user_id' => $qrminfo['uid']))->setInc('points', $add_points)) {
							//     $cur_points = $userPoints->where(array('user_id' => $qrminfo['uid']))->find();
							//     $this->sendpointchange($qrminfo['uid'], '+' . $add_points, $cur_points['points'], '您好，您已经成功到店消费，赠送的积分已经到账，请查收。', '成功到店消费', '感谢您对夜点娱乐的支持，如果您对此次积分变动有任何疑问，请拨打客服电话：020-66695818。');
							//     die(json_encode(array('result' => '0', 'msg' => "成功验证"), true));
							// } else {
							//     die(json_encode(array('result' => '401', 'msg' => "加分失败"), true));
							// }

							if (M('order', 'ac_')->where(array('id' => $orderid))->data(array('status' => '5', 'update_time' => date('Y-m-d H:i:s')))->save()) {
								die(json_encode(array('result' => '0', 'msg' => "成功验证"), true));
							}

						} else {
							die(json_encode(array('result' => '400', 'msg' => "没有找到对应的验证码", 'yzm' => $yzm), true));
						}
					} else {
						die(json_encode(array('result' => '402', 'msg' => '您好，此订单的到店核销时间与预定时间不符合，无法进行到店核销。有问题请致电：020-66695818')));
					}
				} else {
					die(json_encode(array('result' => '403', 'msg' => '订单信息错误')));
				}

			} else {
				$yzm = I('post.yzm');
				$qrminfo = M('querenma')->where(array('yzm' => $yzm, 'status' => '1', ktvid => session('ktvid')))->find();

				if ($qrminfo != NULL) {
					$orderinfo = array();
					$order_info = M('order', 'ac_')->where(array('id' => $qrminfo['orderid']))->find();
					if ($order_info != NULL) {
						if ($order_info['roomtype'] == '1') {
							$orderinfo['roomtype'] = '小包';
						}
						if ($order_info['roomtype'] == '2') {
							$orderinfo['roomtype'] = '中包';
						}
						if ($order_info['roomtype'] == '3') {
							$orderinfo['roomtype'] = '大包';
						}

						$orderinfo['num'] = $order_info['members'];
						$orderinfo['time'] = date("Y年m月d日 H时i分", $order_info['starttime']);
					}
					$userinfo = M('PlatformUser', 'ac_')->where(array('id' => $qrminfo['uid']))->find();
					if ($userinfo != NULL) {
						$orderinfo['tel'] = $userinfo['mobile'];
						$orderinfo['name'] = $userinfo['display_name'];
					}
					$orderinfo['yzm'] = $yzm;

					$orderid = $qrminfo['orderid'];
					$cur_order = M('order', 'ac_')->where(array('id' => $orderid))->find();
					if ($cur_order != NULL) {
						if (abs($cur_order['starttime'] - time()) < 3600) {
							$this->assign('timeup', '1');
						} else {
							$this->assign('timeup', '0');
						}
					}
					$this->assign('orderinfo', $orderinfo);
					$this->display('check_succ');

				} else {
					$this->error('找不到相应的订单,请核对验证码,并重新输入');
				}
			}

		} else {
			$this->display('yzm_index');
		}
	}

	public function getuserinfo($id = '', $key = '') {
		if ($id != '') {
			$userinfo = M('platform_user', 'ac_');
			$user = $userinfo->where(array('id' => $id))->find();
			return $user[$key];
		}
	}

	public function getxktvid($ktvid = '') {
		$xktv = M('xktv', 'ac_');
		$result = $xktv->where(array('id' => $ktvid))->find();
		if ($result != null) {
			return $result['xktvid'];
		} else {
			return false;
		}
	}

	public function getxktvinfo($ktvid = '', $key = '') {
		$xktv = M('xktv', 'ac_');
		$result = $xktv->where(array('id' => $ktvid))->find();
		if ($result != null) {
			return $result[$key];
		} else {
			return false;
		}
	}

	public function order_history() {
		$order = M('Order', 'ac_');
		$where = array();
		$where['time'] = $this->today_condition;
		$where['status'] = array('neq', '1');
		$where['ktvid'] = $this->getxktvid(session('ktvid'));
		$order_list = $order->where($where)->order('id desc')->select();
		$count_list = count($order_list);
		foreach ($order_list as $key => $value) {
			$order_list[$key]['time'] = ($value['endtime'] - $value['starttime']) / 3600;
			$order_list[$key]['starttime'] = date("Y-m-d H:i:s", $value['starttime']);
			$order_list[$key]['makeordertime'] = date("Y-m-d H:i:s", $value['time']);
			$order_list[$key]['djq'] = $this->getCouponInfo($value['couponid']);
			if ($value['roomtype'] == '1') {
				$order_list[$key]['roomtype'] = '大包';
			}
			if ($value['roomtype'] == '2') {
				$order_list[$key]['roomtype'] = '中包';
			}
			if ($value['roomtype'] == '3') {
				$order_list[$key]['roomtype'] = '小包';
			}
			$order_list[$key]['phone'] = $this->getuserinfo($value['userid'], 'mobile');
			$order_list[$key]['name'] = $this->getuserinfo($value['userid'], 'display_name');
			if ($value['status'] == '3') {

				$order_list[$key]['status_info'] = '有房';
			}
			if ($value['status'] == '4') {

				$order_list[$key]['status_info'] = '无房';
			}
			if ($value['status'] == '7') {

				$order_list[$key]['status_info'] = '用户取消';
			}
			if ($value['status'] == '14') {

				$order_list[$key]['status_info'] = '自动取消';
			}
			if ($value['status'] == '5') {

				$order_list[$key]['status_info'] = '已确认到店';
			}

		}
		$this->assign('order', $order_list);
		$this->assign('count', $count_list);
		$this->display();
	}

	public function ordersubmit() {
		$result_array = array();
		if (IS_POST) {
			if (IS_AJAX) {
				$order_id = I('post.id');
				$order_status = I('post.status');
//				$openid = I('post.openid');
				$openid = session('openid');
				// echo $order_status.'sasdfsdf';
				// die();
				$order = M('Order', 'ac_', array(
					'DB_TYPE' => 'mysql', // 数据库类型
					'DB_HOST' => 'localhost', // 服务器地址
					'DB_NAME' => 'abicloud', // 数据库名
					'DB_USER' => 'website', // 用户名
					'DB_PWD' => 'WebSite456', // 密码
					'DB_PORT' => '3306', // 端口
				));
				$result = $order->where(array('id' => $order_id, 'status' => '1'))->data(array('status' => $order_status, 'update_time' => date('Y-m-d H:i:s')))->save();
				if ($result) {
					M('orderhistory')->add(array('to_do' => $order_status, 'openid' => $openid, 'ktvid' => session('ktvid'), 'create_time' => date('Y-m-d H:i:s'), 'oid' => $order_id));
					$result_order = $order->where(array('id' => $order_id))->find();
					$phone = $this->getuserinfo($result_order['userid'], 'mobile');
					// $ktvname = $result_order['ktvid'];
					$ktvname = $this->getxktvinfo(session('ktvid'), 'name');
					if ($result_order['roomtype'] == '1') {
						$ktvtype = '大包';
					}
					if ($result_order['roomtype'] == '2') {
						$ktvtype = '中包';
					}

					if ($result_order['roomtype'] == '3') {
						$ktvtype = '小包';
					}

					// $order_starttime = $this->getTime($result_order['starttime']);
					$order_starttime = date("Y年m月d日H:i", $result_order['starttime']);
					$order_time = ($result_order['endtime'] - $result_order['starttime']) / 3600;

					if ($order_status == '3') {
						// $yzm = rand(0, 999999);
						// $qinfo = array('yzm' => $yzm,
						// 	'uid' => $result_order['userid'],
						// 	'ktvid' => session('ktvid'),
						// 	'orderid' => $order_id,
						// 	'create_time' => date('Y-m-d H:i:s'),
						// 	'create_user' => $this->getuidbyopenid($openid),
						// );
						// $qresult = M('querenma')->add($qinfo);
						// if ($qresult) {
						// $msg_succ = '您好，您已经成功预订' . $ktvname . $ktvtype . '，开始时间为' . $order_starttime . '，持续时间为' . $order_time . '个小时，请提前半小时到店，凭借验证码' . $yzm . '到前台消费。如有任何问题，请拨打夜点客服电话020-66695818，感谢您对夜点的支持。【夜点】';
						$msg_succ = '您好，您已经成功预订' . $ktvname . $ktvtype . '，开始时间为' . $order_starttime . '，持续' . $order_time . '个小时。请按时到店，凭借订单详情中的二维码到前台验证，如果有任何问题，请拨打夜点客服电话400-650-7351。【夜点】';
						// $this->sendSMS($phone, $msg_succ);
						$this->sendmsg($result_order['userid'], $msg_succ);
						// }
						if (session('?sjq')) {
							$coupon = M('coupon', 'ac_');
							$coup = $coupon->where(array('orderid' => $order_id, 'status' => 3, 'available' => 1))->find();
							if ($coup != null) {
								$coupon->where(array('orderid' => $order_id, 'status' => 3))->save(array('status' => 0, 'update_time' => date('Y-m-d H:i:s')));
								$coupon_info = M('coupon_type', 'ac_')->where(array('id' => $coup['type']))->find();
								$msg_succ = '您好，您已经获得兑酒券一张，到店消费后，可联系服务人员兑换' . $coupon_info['name'] . '。如有任何疑问，请打电话400-650-7351。';
								$this->sendmsg_jq($result_order['userid'], $msg_succ);
							}
//                            $rss = $coupon->add(array('orderid'=>$order_id,'userid'=>$result_order['userid'],'type'=>session('sjq'),'expire_time'=>$result_order['starttime']+3600));
							//                            $QRcode = new QrcodeController();
							//                            $data = array();
							//                            $data['type'] = 'coupon_sj';
							//                            $data['coupon_id'] = $rss;
							//                            $res = $QRcode->CreateQrcodeCoupon($data);
							//                            if($coupon->where(array('id'=>$rss))->save(array('qrcodeid'=>$res['id'],'qrcode_img'=>$res['filename']))){
							//                                $msg_succ = '您好，夜点应用';
							//                                $this->sendmsg_jq($result_order['userid'], $msg_succ);
							//                            }
							//
						}

					} else {

						if (session('?sjq')) {
							// $coup = $coupon->where(array('orderid' => $order_id, 'status' => 3))->find();
							$order_info = M('order', 'ac_')->where(array('id' => $order_id))->find();
							if ($order_info != null && $order_info['couponid'] > 0) {
								if (M('coupon', 'ac_')->where(array('id' => $order_info['couponid'], 'is_available' => 0))->save(array('is_available' => 1, 'update_time' => date('Y-m-d H:i:s')))) {
									$msg_fail = '抱歉，您已经预定的' . $ktvname . $ktvtype . '，开始时间为' . $order_starttime . '，持续时间为' . $order_time . '个小时，因预定房间已满，所以预定不成功。请尝试选择其他时段或者其他KTV。您已经绑定的兑酒券将在5分钟之内返还到您的帐户中，下次预定还可使用。感谢您对夜点的支持。如果有任何问题，请拨打夜点客服电话400-650-7351。【夜点】';
									// $this->sendSMS($phone, $msg_fail);
									$this->sendmsg($result_order['userid'], $msg_fail);
								}
							}

						} else {
							$msg_fail = '抱歉，您已经预定的' . $ktvname . $ktvtype . '，开始时间为' . $order_starttime . '，持续时间为' . $order_time . '个小时，因预定房间已满，所以预定不成功。请尝试选择其他时段或者其他KTV。感谢您对夜点的支持。如果有任何问题，请拨打夜点客服电话400-650-7351。【夜点】';
							// $this->sendSMS($phone, $msg_fail);
							$this->sendmsg($result_order['userid'], $msg_fail);
						}
					}
					$result_array['status'] = '2';
					$result_array['msg'] = '更新成功';
					echo json_encode($result_array, true);
				} else {
					$result_array['status'] = '3';
					$result_array['msg'] = '订单已经被处理';
					echo json_encode($result_array, true);
				}

			} else {
				$result_array['status'] = '1';
				$result_array['msg'] = '方法错误';
				echo json_encode($result_array, true);
			}
		} else {
			$result_array['status'] = '0';
			$result_array['msg'] = '方法错误';
			echo json_encode($result_array, true);
		}
	}

	protected function getuidbyopenid($openid) {
		$result = M('ktvemp')->where(array('openid' => $openid))->find();
		return $result['id'];
	}

	public function getTime($value = '') {
		$value = '1450432800';
		echo date("Y年m月d日H:i", $value);
		return '';
	}

	/**
	 * 管理员查看
	 */
	public function orderlist() {
		$type = I('get.type');
		// echo $type;
		$openid = I('get.openid');
		$where = array();
		$where['ktvid'] = $this->getxktvid(session('ktvid'));
		$where['time'] = $this->today_condition_timestamp;
		switch ($type) {
		case 'weichuli':
			$this->type = '[未处理]的';
			$where['status'] = 1;
			break;
		case 'youfang':
			$this->type = '[有房]的';

			if ($openid != null) {
				$this->empname = $this->getempnamebyopenid($openid);
				$result = M('orderhistory')->where(array('openid' => $openid, 'create_time' => $this->today_condition, 'to_do' => '3'))->select();
				if ($result != NULL) {
					$oids = array();
					foreach ($result as $key => $value) {
						$oids[] = $value['oid'];
					}
					$where['id'] = array('IN', $oids);
				} else {
					$where['id'] = '-1';
				}

			} else {
				$where['status'] = '3';
			}
			# code...
			break;
		case 'wufang':
			$this->type = '[无房]的';
			if ($openid != null) {
				$this->empname = $this->getempnamebyopenid($openid);
				$result = M('orderhistory')->where(array('openid' => $openid, 'create_time' => $this->today_condition, 'to_do' => '4'))->select();
				if ($result != NULL) {
					$oids = array();
					foreach ($result as $key => $value) {
						$oids[] = $value['oid'];
					}
					$where['id'] = array('IN', $oids);
				} else {
					$where['id'] = '-1';
				}

			} else {
				$where['status'] = '4';
			}
			# code...
			break;
		case 'timeout':
			$this->type = '[超时]的';
			$where['status'] = 14;
			# code...
			break;
		case 'yonghucancel':
			$this->type = '[用户取消]的';
			$where['status'] = 7;
			# code...
			break;
		case 'daodianqueren':
			$this->type = '[到店确认]的';
			if ($openid != null) {
				$this->empname = $this->getempnamebyopenid($openid);
				$result = M('querenma')->where(array('update_user' => $this->getuidbyopenid($openid), 'create_time' => $this->today_condition, 'status' => '0'))->select();
				if ($result != NULL) {
					$oids = array();
					foreach ($result as $key => $value) {
						$oids[] = $value['orderid'];
					}
					$where['id'] = array('IN', $oids);
				} else {
					$where['id'] = '-1';
				}
			} else {
				$where['status'] = '5';
			}
			# code...
			break;
		default:
			break;
		}
		$order = M('Order', 'ac_');
		$order_result = $order->where($where)->select();
		$count_result = count($order_result);
		foreach ($order_result as $key => $value) {
			$order_result[$key]['time'] = ($value['endtime'] - $value['starttime']) / 3600;
			$order_result[$key]['starttime'] = date("Y-m-d H:i:s", $value['starttime']);
			$order_result[$key]['makeordertime'] = date("Y-m-d H:i:s", $value['time']);
			$order_result[$key]['djq'] = $this->getCouponInfo($value['couponid']);
			if ($value['roomtype'] == '1') {
				$order_result[$key]['roomtype'] = '大包';
			}
			if ($value['roomtype'] == '2') {
				$order_result[$key]['roomtype'] = '中包';
			}
			if ($value['roomtype'] == '3') {
				$order_result[$key]['roomtype'] = '小包';
			}
			$order_result[$key]['phone'] = $this->getuserinfo($value['userid'], 'mobile');
			$order_result[$key]['name'] = $this->getuserinfo($value['userid'], 'display_name');
			if ($value['status'] == '1') {

				$order_result[$key]['status_info'] = '未处理';
			}
			if ($value['status'] == '3') {

				$order_result[$key]['status_info'] = '有房';
			}
			if ($value['status'] == '4') {

				$order_result[$key]['status_info'] = '无房';
			}
			if ($value['status'] == '5') {

				$order_result[$key]['status_info'] = '到店确认完成';
			}
			if ($value['status'] == '7') {

				$order_result[$key]['status_info'] = '用户取消';
			}
			if ($value['status'] == '14') {

				$order_result[$key]['status_info'] = '自动取消';
			}
			$orderhistory = M('orderhistory')->where(array('oid' => $value['id'], 'create_time' => $this->today_condition))->find();
			$emp = M('ktvemp')->where(array('openid' => $orderhistory['openid']))->find();
			if ($type == 'daodianqueren') {
				$querenma = M('querenma')->where(array('orderid' => $value['id']))->find();
				$emp_queren = M('ktvemp')->where(array('id' => $querenma['update_user']))->find();
				$order_result[$key]['fuwuyuanqueren'] = $emp_queren['name'];
			}
			$order_result[$key]['fuwuyuan'] = $emp['name'];
		}
		$this->assign('count', $count_result);
		$this->assign('order', $order_result);
		if (session('time_condition') == 0) {
			$this->assign('today', '今天');
		} elseif (session('time_condition') == 1) {
			$this->assign('today', '历史');
		}
		$this->display();
	}

	protected function getempnamebyopenid($openid) {
		$empinfo = M('ktvemp')->where(array('openid' => $openid, 'ktvid' => session('ktvid')))->find();
		return $empinfo['name'];
	}

	protected function sendSMS($mobile, $content) {
		$ch = curl_init();
		$url = C('server_host') . '/_tools/phonenum.php?phone=' . $mobile . '&content=' . $content;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}

	protected function sendmsg($uid, $content) {
		$url = C('server_host') . '/wechat_ktv/Home/WeChatApi/sendMsgToUid';
		$this->http_post($url, array('uid' => $uid, 'msg' => $content));
	}

	protected function sendmsg_jq($uid, $content) {
		$url = C('server_host') . '/wechat_ktv/Home/WeChatApi/sendMsgToUid';
		$this->http_post($url, array('uid' => $uid, 'msg' => $content));
	}

	protected function sendpointchange($uid, $jifen, $yue, $title, $yuanyin, $remark) {
		$url = C('server_host') . '/wechat_ktv/Home/WeChatApi/PointChangMessage';
		$this->http_post($url, array(
			'uid' => $uid,
			'jifen' => $jifen,
			'yue' => $yue,
			'title' => $title,
			'yuanyin' => $yuanyin,
			'remark' => $remark,
		));
	}

	private function http_post($url, $param, $post_file = false) {
		$oCurl = curl_init();
		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
		}
		if (is_string($param) || $post_file) {
			$strPOST = $param;
		} else {
			$aPOST = array();
			foreach ($param as $key => $val) {
				$aPOST[] = $key . "=" . urlencode($val);
			}
			$strPOST = join("&", $aPOST);
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCurl, CURLOPT_POST, true);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			return $sContent;
		} else {
			return false;
		}
	}

	public function test() {
		// $msg_succ = '您好，您已经成功预订' . '百威CEO大包' . '，开始时间为' . '2012年12月4日19:00' . '，持续时间为' . '6' . '个小时，请提前半小时到店，凭借手机号码到前台消费。如有任何问题，请拨打夜点客服电话020-66695818，感谢您对夜点的支持。';
		$msg_succ = '抱歉，您已经预定的百威CEO大包，开始时间为2012年12月4日19:00，持续时间为6个小时，因预定房间已满，所以预定不成功。请尝试选择其他时段或者其他KTV。感谢您对夜点的支持。【夜点应用】';
		// 抱歉，您已经预定的百威CEO大包，开始时间为2012年12月4日19:00，持续时间为6个小时，因预定房间已满，所以预定不成功。请尝试选择其他时段或者其他KTV。感谢您对夜点的支持。
		// $msg_succ = '你好你好';
		// echo urlencode($msg_succ);
		// echo urldecode(urlencode($msg_succ));
		echo $this->sendSMS('18612420949', urlencode($msg_succ));
	}

	public function duijiu() {
		$duijiu_records = M('sj_record')->where(array('ktvid' => session('ktvid')))->select();
		$duijiu_count_total = 0;
		$duijiu_count_hexiao = 0;
		$duijiu_count_weihexiao = 0;
		if ($duijiu_records != null) {
			foreach ($duijiu_records as $key => $value) {
				$duijiu_count_total += $value['count'];
			}
		}
		$this->duijiu_count_total = $duijiu_count_total;
		// $this->duijiu_count_hexiao = M('hexiao_record', 'ydsjb_')->where(array('ktvid' => session('ktvid')))->sum('count');
		$this->duijiu_count_hexiao = M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'status' => 2))->sum('count');
		$this->duijiu_count_hexiao_shenqingzhong = M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'status' => array('IN', array(0, 1))))->sum('count');
		$this->duijiu_count_hexiao_shenqingzhong = $this->duijiu_count_hexiao_shenqingzhong == null ? 0 : $this->duijiu_count_hexiao_shenqingzhong;
		$this->duijiu_count_weihexiao = $this->duijiu_count_total - $this->duijiu_count_hexiao - $this->duijiu_count_hexiao_shenqingzhong;
		$this->display();
	}

	public function duijiuxiangqing() {
		$this->hexiaolist = M('songjiushenqing', 'ydsjb_')->where(array('ktvid' => session('ktvid')))->select();
		$this->display();
	}

	public function duijiu_geren() {
		$openid = I('openid');
		$this->username = $this->getempnamebyopenid($openid);
		$this->display();
	}

	// protected function sendSMS($mobile, $content) {
	// 	header('Content-Type: text/html; charset=GBK');
	// 	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	// 	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	// 	header("Cache-Control: no-cache, must-revalidate");
	// 	header("Pramga: no-cache");

	// 	$username = "18702163052";
	// 	$password = base64_encode("122333");

	// 	$content = urlencode(mb_convert_encoding($content, 'gbk', 'utf8'));
	// 	$url = 'http://61.147.98.117:9001';
	// 	$url = $url . "/servlet/UserServiceAPI?method=sendSMS&extenno=&isLongSms=0&username=" . $username . "&password=" . $password . "&smstype=1&mobile=" . $mobile . "&content=" . $content;
	// 	$data = file_get_contents($url);
	// 	if (strpos($data, "success") === false) {
	// 		return false;
	// 	} else {
	// 		return true;
	// 	}
	// }

	// public function test() {
	// 	$order = M('Order', 'ac_', array(
	// 		'DB_TYPE' => 'mysql', // 数据库类型
	// 		'DB_HOST' => 'localhost', // 服务器地址
	// 		'DB_NAME' => 'abicloud', // 数据库名
	// 		'DB_USER' => 'website', // 用户名
	// 		'DB_PWD' => 'WebSite456', // 密码
	// 		'DB_PORT' => '3306', // 端口
	// 	));
	// 	$result = $order->select();
	// }
}