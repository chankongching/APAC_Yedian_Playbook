<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends CommonController {
	public function __construct() {
		parent::__construct();
		$session_get_action = array('bind', 'bindf', 'bindm', 'ktvmanage');
		if (in_array(ACTION_NAME, $session_get_action)) {
			// echo 'session destroy';
			// session('[destroy]');
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
			}
			// echo $openid;
			session('openid', $openid);
		}

	}

	public function index() {
	}

	public function bind() {
		// $this->display('bind');
		$this->redirect('bindNew');
	}

	/**
	 * 绑定服务员
	 */
	public function bindf() {
		// $this->display('bind');
		$this->redirect('bindfNew');
	}

	/**
	 *绑定管理员(经理)
	 */
	public function bindm() {
		// $this->display('bind');
		$this->redirect('bindmNew');
	}
	public function bindfNew() {
		$this->display('bindf');
	}
	public function bindmNew() {
		$this->display('bindm');
	}
	public function bindNew() {
		$this->display('bind');
	}
	public function InputPhone() {
		// echo session('openid');
		// echo session('role');
		if (!session('?role')) {
			$this->display('inputphone');
		} else {
			$this->error("您已经绑定了账号");
		}

	}
	public function BindPhone() {
		if (IS_POST) {
			if (session('role') != NULL) {
				$this->error('您已经绑定过了');
			}
			$phone = I('post.phone');
			$ktvmanager = M('ktvmanager')->where(array('phone' => $phone, 'status' => '1'))->find();
			if ($ktvmanager != null) {
				M('ktvmanager')->where(array('phone' => $phone, 'status' => '1'))->save(array('openid' => session('openid'), 'bind_time' => date('Y-m-d H:i:s', time())));
				$this->assign('username', $ktvmanager['name']);
				$this->assign('ktvname', $this->getktvinfo($ktvmanager['ktvid'], 'name'));
				$this->assign('managetel', '020-62993277');
				$this->display('bindSucc');
			} else {
				$this->error('手机号码不正确');
			}
		} else {
			$this->error();
		}
	}

	public function getktvinfo($id = '', $name = '') {
		$ktv = M('xktv', 'ac_');
		$ktvinfo = $ktv->where(array('id' => $id))->find();
		return $ktvinfo[$name];
	}
	public function fwylogin() {
		if (!session('?role')) {
			$this->display();
		} else {
			$this->error("您已经绑定了账号");
		}
	}

	public function complateinfo() {
		if (IS_POST) {
			$yzm = I("post.yzm");
			$result = $this->yanzheng($yzm);
			if ($result['status'] == '1') {
				session('yzm', $yzm);
				session('ktvid', $result['ktvid']);
				$this->display();
			} elseif ($result['status'] == '2') {
				$this->error('您已经绑定过了');
			} else {
				$this->error('验证码错误');
			}
		} else {
			$this->error();
		}
	}

	public function yanzheng($yzm = '') {
		$result = array('status' => '0');
		$emplyee = M('ktvemp')->where(array('openid' => session('openid')))->find();
		// var_dump($emplyee);die();
		if ($emplyee != null) {
			$result['status'] = '2';
			return $result;
		}
		if ($yzm != '') {
			$res = M('yzm')->where(array('yanzhengma' => $yzm, 'status' => '1'))->find();
			// var_dump($res);die();
			if ($res != null) {
				// $res->status='0';
				// M('yzm')->where(array('yanzhengma' => $yzm, 'status' => '1'))->data(array('status' => '0'))->save();
				$result['ktvid'] = $res['ktvid'];
				$result['status'] = '1';
				return $result;
			}
			return $result;
		} else {
			return $result;
		}
	}

	public function Bindfwy() {
		if (IS_POST) {
			$name = I("post.name");
			$phone = I('post.phone');
			// echo $phone;die();
			if (empty($name) || empty($phone)) {
				$this->error();
			} else {
				$result = M('ktvemp')->data(array('name' => $name, 'phone' => $phone, 'openid' => session('openid'), 'status' => '1', 'ktvid' => session('ktvid'), 'yzm' => session('yzm'), 'create_time' => date('Y-m-d H:i:s')))->add();
				if ($result) {
					if (M('yzm')->where(array('yanzhengma' => session('yzm'), 'status' => '1'))->data(array('status' => '0'))->save()) {
						M('xktv', 'ac_', array(
							'DB_TYPE' => 'mysql', // 数据库类型
							'DB_HOST' => 'localhost', // 服务器地址
							'DB_NAME' => 'abicloud', // 数据库名
							'DB_USER' => 'website', // 用户名
							'DB_PWD' => 'WebSite456', // 密码
							'DB_PORT' => '3306', // 端口
						))->where(array('id' => session('ktvid')))->data(array('type' => 2))->save();
					}
				}
				$this->Notify();
				// echo M()->getlastsql();die();
				$this->display('bindsuccfuy');
			}
		}
	}

	public function ktvmanage() {
		if (session('role') != 'manager') {
			$this->error('您不是管理员不能查看');
		}
		$this->redirect('ktvmanageNew');
		// $employ = M('ktvemp')->where(array('ktvid' => session('ktvid'), 'status' => '1'))->select();
		// $this->assign('emplcount', count($employ));
		// $this->assign('employ', $employ);
		// $this->display();
	}

	public function ktvmanageNew() {
		// $employ = M('ktvemp')->where(array('ktvid' => session('ktvid'), 'status' => '1'))->select();
		$employ = M('ktvemp')->where(array('ktvid' => session('ktvid')))->order('status desc')->select();
		$yzm = M('yzm', 'ydsjb_')->where(array('ktvid' => session('ktvid'), 'status' => 1))->select();
		$this->assign('emplcount', count($employ));
		$this->assign('employ', $employ);
		$this->assign('yzm_avail', $yzm);
		$this->display('ktvmanage');
	}

	public function updateemploy() {
		$result_array = array();
		if (!IS_POST) {
			$result_array['status'] = '0';
			$result_array['msg'] = 'POST方法错误';
			echo json_encode($result_array);
			exit();
		}
		if (!IS_AJAX) {
			$result_array['status'] = '0';
			$result_array['msg'] = '方法错误';
			echo json_encode($result_array);
			exit();
		}
		$id = I('post.id');
		$tmpemploy = M('ktvemp')->where(array('id' => $id))->find();
		if ($tmpemploy == null) {
			$result_array['status'] = '0';
			$result_array['msg'] = '参数错误';
			echo json_encode($result_array);
		} else {
			// $result = M('ktvemp')->where(array('id'=>$id))->data(array('status'=>'0'))->save();
			// $ktvemp =  M('ktvemp')->where(array('id' => $id))->find();
			//			$result = M('ktvemp')->where(array('id' => $id))->delete();
			$result = M('ktvemp')->where(array('id' => $id))->data(array('status' => '0', 'update_time' => date('Y-m-d H:i:s'), 'update_user' => $this->getuidbyopenid(session('openid'))))->save();
			M('yzm')->where(array('yanzhengma' => $tmpemploy['yzm']))->data(array('is_cancel' => '1'))->save();
			if ($result > 0) {
				$result_array['status'] = '1';
				$result_array['msg'] = '注销成功';
				echo json_encode($result_array);
			}
		}
	}

	protected function getuidbyopenid($openid) {
		$result = M('ktvmanager')->where(array('openid' => $openid))->find();
		return $result['id'];
	}

	public function sendsss() {
		// echo 'sdsdf';
		// die();
		if (IS_POST && IS_AJAX) {
			$result_array = array();
			$phone = I('post.phone');
			$ktvmanager = M('ktvmanager')->where(array('status' => '1', 'phone' => $phone))->find();
			if ($ktvmanager != NULL) {
				$yzm = rand(100000, 999999);
				// $this->sendSMS($phone, '您的验证码是' . $yzm . ',请在微信中填写');”感谢您使用夜点娱乐商家版，您的验证码是XXXXX 【夜点应用】
				$this->sendSMS($phone, '请在微信中填写 感谢您使用夜点娱乐商家版，您的验证码是 ' . $yzm);
				$result_array['status'] = '1';
				$result_array['msg'] = '成功';
				$result_array['yzm'] = $yzm;
				$result_array['phone'] = $phone;
				echo json_encode($result_array);
			} else {
				$result_array['status'] = '0';
				$result_array['msg'] = '失败';
				$result_array['phone'] = $phone;
				echo json_encode($result_array);
			}

		}

	}

	public function Notify() {
		# code...sendCustomMessage
		// if (IS_POST && IS_AJAX) {
		// $to_openid = 'ocEFCt9CPUjXRj0vBJVX9mw1HPvQ';
		$to_openid = session('openid');
		$empinfo = M('ktvemp')->where(array('openid' => $to_openid, 'status' => '1'))->find();
		$managerinfo = M('ktvmanager')->where(array('ktvid' => $empinfo['ktvid'], 'status' => '1'))->find();
		$form_openid = $managerinfo['openid'];
		$WeChat = new WeChatController();
		$to_data = array('touser' => $to_openid,
			'msgtype' => 'text',
			"text" => array(
				'content' => $empinfo['name'] . '恭喜您成功绑定成为KTV的操作员！'));
		$from_data = array('touser' => $form_openid,
			'msgtype' => 'text',
			"text" => array(
				'content' => $empinfo['name'] . '成功绑定成为KTV的操作员，请核实！'));

		$WeChat->sendCustomMessage($to_data);
		$WeChat->sendCustomMessage($from_data);
		// }
	}

	// public function Notify_test() {
	// 	# code...sendCustomMessage
	// 	// if (IS_POST && IS_AJAX) {
	// 	$to_openid = 'ocEFCt9CPUjXRj0vBJVX9mw1HPvQ';
	// 	echo $to_openid;
	// 	// $empinfo = M('ktvemp')->where(array('openid' => $to_openid, 'status' => '1'))->find();
	// 	// $managerinfo = M('ktvmanager')->where(array('ktvid' => $empinfo['ktvid'], 'status' => '1'))->find();
	// 	// $form_openid = $managerinfo['openid'];
	// 	$WeChat = new WeChatController();
	// 	$to_data = array('touser' => $to_openid,
	// 		'msgtype' => 'text',
	// 		"text" => array(
	// 			'content' => '谢谢关注'));
	// 	// {
	// 	//      "content":"Hello World"
	// 	// });
	// 	// $from_data = array('touser' => $form_openid,
	// 	//     'msgtype' => 'text',
	// 	//     'content' => '谢谢关注1');

	// 	var_dump($WeChat->sendCustomMessage($to_data));
	// 	// $WeChat->sendCustomMessage($from_data);
	// 	// }
	// }

	protected function sendSMS($mobile, $content) {
		header('Content-Type: text/html; charset=GBK');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pramga: no-cache");

		$username = "18702163052";
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
}