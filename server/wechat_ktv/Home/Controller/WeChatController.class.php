<?php
namespace Home\Controller;
use Org\Util\Wechat;
use Think\Controller;

class WeChatController extends CommonController {
	public function __construct() {
		parent::__construct();
		$options = C('Wx_Options');
		$this->weObj = new TPWechatController($options);
	}
	public function Gateway() {
		$this->weObj->valid();
		$type = $this->weObj->getRev()->getRevType();
		// $this->weObj->text($this->weObj->getRevEventGeo())->reply();
		switch ($type) {
		case Wechat::MSGTYPE_TEXT:
			$this->weObj->text("hello, I'm wechat")->reply();
			exit;
			break;
		case Wechat::MSGTYPE_EVENT:

			$events = $this->weObj->getRevEvent();
			if ($events['key'] == 'MakeEWM') {
				$this->weObj->text($this->MakeEWM())->reply();
			}
			if ($events['key'] == 'Contactus') {
				$this->weObj->text('欢迎欢迎，热烈欢迎！')->reply();
			}
			if ($events['event'] == 'LOCATION') {
				$Geo = $this->weObj->getRevEventGeo();
				$this->saveLocation($Geo, $this->weObj->getRevFrom());
				// $this->weObj->text('欢迎欢迎，热烈欢迎！')->reply();
			}
			if ($events['event'] == Wechat::EVENT_SUBSCRIBE) {
				$scan_id = $this->weObj->getRevSceneId();
				$url = $this->getEventUrl($scan_id);
				if ($scan_id == '') {
					die();
				} elseif ($scan_id == 'event_djiuq') {
					if ($this->ismobileuser($this->weObj->getRevFrom())) {
						$news = array();
						$news[0] = array(
							'Title' => '兑酒券已放入您的账户！',
							// 'Description' => $url,
							'PicUrl' => 'http://t1.intelfans.com/uploads/event/chashou.png',
							'Url' => $url,
						);
						$this->weObj->news($news)->reply();
					} else {
						$news = array();
						$news[0] = array(
							'Title' => '您已经成功获取夜点兑酒券！',
							// 'Description' => $url,
							'PicUrl' => 'http://t1.intelfans.com/uploads/event/liji.png',
							'Url' => $url,
						);
						$this->weObj->news($news)->reply();
					}
				} else {
					if ($this->isgetcoupon($this->weObj->getRevFrom())) {
						$news = array();
						$news[0] = array(
							'Title' => '您已成功获取夜点兑酒券！',
							'Description' => '',
							'PicUrl' => 'http://t1.intelfans.com/uploads/event/liji.png',
							'Url' => $url,
						);
						$this->weObj->news($news)->reply();
					} else {
						$news = array();
						$news[0] = array(
							'Title' => '恭喜获得夜点兑酒券！',
							'Description' => '',
							'PicUrl' => 'http://t1.intelfans.com/uploads/event/chashou.png',
							'Url' => $url,
						);
						$this->weObj->news($news)->reply();
					}
				}

			}
			if ($events['event'] == Wechat::EVENT_SCAN) {
				$scan_id = $this->weObj->getRevSceneId();
				$url = $this->getEventUrl($scan_id);

				if ($this->isgetcoupon($this->weObj->getRevFrom())) {
					$news = array();
					$news[0] = array(
						'Title' => '您已成功获取夜点兑酒券！',
						'Description' => '',
						'PicUrl' => 'http://t1.intelfans.com/uploads/event/liji.png',
						'Url' => $url,
					);
					$this->weObj->news($news)->reply();
				} else {
					$news = array();
					$news[0] = array(
						'Title' => '恭喜获得夜点兑酒券！',
						'Description' => '',
						'PicUrl' => 'http://t1.intelfans.com/uploads/event/chashou.png',
						'Url' => $url,
					);
					$this->weObj->news($news)->reply();
				}

			}
			// $this->weObj->text('啦啦啦')->reply();
			break;
		case Wechat::MSGTYPE_LOCATION:
			$this->weObj->text('欢迎欢迎，热烈欢迎___！')->reply();
			break;
		case Wechat::MSGTYPE_IMAGE:
			break;
		default:
			$this->weObj->text("help info")->reply();
		}
	}

	protected function isgetcoupon($openid = '') {
		if ($openid != '') {
			$userinfo = M('platform_user', 'ac_')->where(array('openid' => $openid))->find();
			if ($userinfo != null) {
				$coupon = M('coupon', 'ac_')->where(array('userid' => $userinfo['id'], 'type' => array('IN', array(10, 11, 12))))->find();
				if ($coupon != null) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	protected function ismobileuser($openid = '') {
		if ($openid != '') {
			$mobile = M('event_mobile', 'ac_')->where(array('openid' => $openid))->find();
			if ($mobile != null) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	protected function getEventUrl($ktvid = '') {
		if ($ktvid != '') {
			if ($ktvid == 'event_djiuq') {
				return 'http://t1.intelfans.com/wechat_ktv/Home/Event/enter';
			} else {
				if (M('xktv', 'ac_')->where(array('xktvid' => $ktvid, 'type' => 2, 'status' => 1))->find() != null) {
					return 'http://t1.intelfans.com/wechat_ktv/Home/Event/enter/ktvid/' . $ktvid;
				} else {
					return 'aaaaa';
				}
			}

		} else {
			return 'bbbbb';
		}
	}

	public function saveGeo() {
		if (IS_GET) {
			$Geo = array();
			$Geo['x'] = I('get.lat');
			$Geo['y'] = I('get.lng');
			$from_openid = I('get.openid');
			$this->saveLocation($Geo, $from_openid);
		}
	}

	protected function saveLocation($Geo, $from_openid) {
		if (M('platform_user', 'ac_')->where(array('openid' => $from_openid))->count() > 0) {
			M('platform_user', 'ac_')->where(array('openid' => $from_openid))->save(array('lat' => $Geo['x'], 'lng' => $Geo['y']));
		} else {
			M('platform_user', 'ac_')->add(array('openid' => $from_openid,
				'lat' => $Geo['x'],
				'lng' => $Geo['y'],
				'auth_type' => 'wechat',
				'username' => $from_openid,
				'role' => 'reader',
			)
			);
		}

	}
	public function createMenu() {
		// echo $this->weObj->getMenu();
		//设置菜单
		$newmenu = array(
			"button" => array(
				array('type' => 'view', 'name' => '立即预订', 'url' => C('server_host') . '/wechat_ktv/Home/WeChat/GoUrl/url/1'),
				array('name' => '独享优惠', 'sub_button' => array(
                    array('type' => 'view', 'name' => '杰迷派对', 'url' => C('server_host') . '/dist/jaycnparty'),
					array('type' => 'view', 'name' => '免费KTV派对', 'url' => C('server_host') . '/wechat_ktv/Home/Event/hjd'),
					array('type' => 'view', 'name' => '免费兑酒券', 'url' => C('server_host') . '/wechat_ktv/Home/Event/enter'),
					array('type' => 'view', 'name' => '精彩内容', 'url' => C('server_host') . '/_tools/redirecttohistorymessage.php'),
					// array('type' => 'view', 'name' => '我是估歌王', 'url' => C('server_host') . '/wechat_ktv/Home/WeChat/GoUrl/url/2'),
				)),
				array('type' => 'click', 'name' => '联系我们', 'key' => 'Contactus'),
			),
		);
		$result = $this->weObj->createMenu($newmenu);
		var_dump($result);
	}

	public function deleteMenu() {
		$this->weObj->deleteMenu();
	}

	public function GoUrl() {
		$url = I('get.url');
		if ($url != null) {
			if ($url == 1) {
				header('Location:' . C('server_host') . '/dist/');
			} elseif ($url == 2) {
				header('Location:' . C('server_host') . '/games/GUESS_SONG/');
			} elseif ($url == 3) {
				header('Location:' . C('server_host') . '/_tools/redirecttohistorymessage.php');
			} elseif ($url == 4) {
				header('Location:' . C('server_host') . '/dist/#!/user');
			} elseif ($url == 5) {
				header('Location:' . C('server_host') . '/dist/#!/order');
			} elseif ($url == 6) {
				header('Location:' . C('server_host') . '/dist/#!/store');
			} elseif ($url == 7) {
				header('Location:' . C('server_host') . '/games/20160128__Lucky_Draw/index/');
			}
		}
	}

	public function getMenu() {
		$menu = $this->weObj->getMenu();
		var_dump($menu);
		echo json_encode($menu);
	}

	public function getOauthUrl() {
		// $callback='http://letsktv.chinacloudapp.cn/wechatshangjia/Index/bind';
		// $callback='http://letsktv.chinacloudapp.cn/wechatshangjia/Index/ktvmanage';
		$callback = C('server_host') . '/wechat_ktv/Home/WeChat/getopenid';
		$state = 'OK';
		$scope = 'snsapi_base';
		$res = $this->weObj->getOauthRedirect($callback, $state, $scope);
		var_dump($res);
	}
	public function test() {
		$userinfo = $this->weObj->getUserInfo('oQPyxvx4Mqv5FRAArsMfyX1gRa2I');
		var_dump($userinfo);
	}
	public function getsign() {
		header('Access-Control-Allow-Origin:*');
		$result_array = array();
		if (!IS_GET && !IS_AJAX) {
			$result_array['status'] = 0;
			$result_array['msg'] = '方法错误';
			echo json_encode($result_array, true);
			return false;
		}
		$url = urldecode(I('get.url'));
		$sign = $this->getJsSign(htmlspecialchars_decode($url), 0, '', 'wx90f8e48d4b4f5d8d');
		$result_array['status'] = 1;
		$result_array['msg'] = '成功';
		$result_array['sign'] = $sign;
		echo json_encode($result_array, true);
	}

	public function getopenid() {
		header('Access-Control-Allow-Origin:*');
		if (IS_GET) {
			$token = $this->getOauthAccessToken();
//			var_dump($token);
			if ($token == false) {
				echo json_encode(array('msg' => 'get openid failed', 'result' => '400'), true);
				return false;
			} else {
				$openid = $token['openid'];
				$userinfo = $this->weObj->getUserInfo($openid);
//				var_dump($userinfo);die();
				//				echo json_encode($userinfo);die();

				echo json_encode(array('msg' => 'get openid success', 'result' => 0, 'openid' => $openid, 'display_name' => $userinfo['nickname'],
					'avatar_url' => $userinfo['headimgurl'], 'sex' => $userinfo['sex'],
				), true);
			}

		}

	}
	protected function getopenidbycode() {

	}
	public function MakeEWM() {
		$openid = $this->weObj->getRevFrom();
		$res = M('ktvmanager')->where(array('openid' => $openid, 'status' => 1))->find();
		$ecount = M('ktvemp')->where(array('status' => 1, 'ktvid' => $res['ktvid']))->Count('openid');
		$ercount = M('yzm')->where(array('ktvid' => $res['ktvid'], 'is_cancel' => 0))->Count('ktvid');
		if ($ercount >= 5) {
			$msg = '您的验证码数已经到达上限，如需申请更多员工账号，请联系夜点商务拓展人员';
			return $msg;
		}
		// return $ecount;
		if ($ecount >= 5) {
			$msg = '您的员工数量已经到达上限，如需申请更多员工账号，请联系夜点商务拓展人员';
			return $msg;
		}
		if (is_null($res)) {
			$msg = '您不是管理员，请先绑定以后再获取';
		} else {
			$yzm = rand(100000, 999999);
			$yz = M('yzm');
			$yz->yanzhengma = $yzm;
			$yz->ktvid = $res['ktvid'];
			$yz->status = 1;
			$yz->add();
			$msg = '您已经生成了新的验证码[' . $yzm . ']';
		}
		return $msg;
	}

	public function getOauthAccessToken() {
		return $this->weObj->getOauthAccessToken();
	}
//    获取access_token
	public function getToken() {

		$token = $this->weObj->checkAuth();

		// $token = $this->weObj->checkJingAuth();
		if ($token == false) {
			die(json_encode(array('status' => 0, 'data' => 'failed get token')));
		} else {
			die(json_encode(array('status' => 1, 'data' => $token)));
		}
	}

	public function sendTemplateMessage() {
		if (IS_POST) {
			$post_data = file_get_contents("php://input");
			$post_array = json_decode($post_data, true);
			$ktvid = isset($post_array["ktvid"]) ? $post_array["ktvid"] : '';
			$userid = isset($post_array["userid"]) ? $post_array["userid"] : '';
			$orderid = isset($post_array["orderid"]) ? $post_array["orderid"] : '';
			$order = M('order', 'ac_', array(
				'DB_TYPE' => 'mysql', // 数据库类型
				'DB_HOST' => 'localhost', // 服务器地址
				'DB_NAME' => 'abicloud', // 数据库名
				'DB_USER' => 'website', // 用户名
				'DB_PWD' => 'WebSite456', // 密码
				'DB_PORT' => '3306', // 端口
			));
			$orderinfo = $order->where(array('id' => $orderid))->find();
			$user = M('platform_user', 'ac_', array(
				'DB_TYPE' => 'mysql', // 数据库类型
				'DB_HOST' => 'localhost', // 服务器地址
				'DB_NAME' => 'abicloud', // 数据库名
				'DB_USER' => 'website', // 用户名
				'DB_PWD' => 'WebSite456', // 密码
				'DB_PORT' => '3306', // 端口
			));
			$userinfo = $user->where(array('id' => $userid))->find();
			$ktv = M('xktv', 'ac_', array(
				'DB_TYPE' => 'mysql', // 数据库类型
				'DB_HOST' => 'localhost', // 服务器地址
				'DB_NAME' => 'abicloud', // 数据库名
				'DB_USER' => 'website', // 用户名
				'DB_PWD' => 'WebSite456', // 密码
				'DB_PORT' => '3306', // 端口
			));
			$ktvinfo = $ktv->where(array('id' => $ktvid))->find();
			$dataM = array();
			$dataM['template_id'] = 'q7Q7O0bSMp--l9Y-Dq1UxhEghaW0ujMVnTQ_At7iUTE';
			$dataM['url'] = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect';
			$dataM['topcolor'] = '#FF0000';
			$dataM['data'] = array(
				'first' => array(
					'value' => '有新订单，请处理',
					'color' => '#000000',
				),
				'keyword1' => array(
					'value' => $userinfo['display_name'],
					// 'value' => $userid,
					'color' => '#000000',
				),
				'keyword2' => array(
					'value' => date("Y-m-d H:i:s", $orderinfo['starttime']),
					'color' => '#000000',
				),
				'keyword3' => array(
					'value' => $ktvinfo['address'],
					'color' => '#000000',
				),
				'keyword4' => array(
					'value' => $orderinfo['id'],
					'color' => '#000000',
				),
				'remark' => array(
					'value' => '点击处理订单',
					'color' => '#000000',
				),
			);
			$ktvemp = M('ktvemp');
			$employees = $ktvemp->where(array('ktvid' => $ktvid, 'status' => 1))->select();
			if ($employees != null) {
				foreach ($employees as $key => $value) {
					$dataM['touser'] = $value['openid'];
					$this->weObj->sendTemplateMessage($dataM);
				}
			}

		}

	}
	public function sendTemplateCancelMessage() {
		if (IS_POST) {
			$post_data = file_get_contents("php://input");
			$post_array = json_decode($post_data, true);
			$ktvid = isset($post_array["ktvid"]) ? $post_array["ktvid"] : '';
			$userid = isset($post_array["userid"]) ? $post_array["userid"] : '';
			$orderid = isset($post_array["orderid"]) ? $post_array["orderid"] : '';
			$order = M('order', 'ac_', array(
				'DB_TYPE' => 'mysql', // 数据库类型
				'DB_HOST' => 'localhost', // 服务器地址
				'DB_NAME' => 'abicloud', // 数据库名
				'DB_USER' => 'website', // 用户名
				'DB_PWD' => 'WebSite456', // 密码
				'DB_PORT' => '3306', // 端口
			));
			$orderinfo = $order->where(array('id' => $orderid))->find();
			$user = M('platform_user', 'ac_', array(
				'DB_TYPE' => 'mysql', // 数据库类型
				'DB_HOST' => 'localhost', // 服务器地址
				'DB_NAME' => 'abicloud', // 数据库名
				'DB_USER' => 'website', // 用户名
				'DB_PWD' => 'WebSite456', // 密码
				'DB_PORT' => '3306', // 端口
			));
			$userinfo = $user->where(array('id' => $userid))->find();
			$ktv = M('xktv', 'ac_', array(
				'DB_TYPE' => 'mysql', // 数据库类型
				'DB_HOST' => 'localhost', // 服务器地址
				'DB_NAME' => 'abicloud', // 数据库名
				'DB_USER' => 'website', // 用户名
				'DB_PWD' => 'WebSite456', // 密码
				'DB_PORT' => '3306', // 端口
			));
			$ktvinfo = $ktv->where(array('id' => $ktvid))->find();
			$dataM = array();
			$dataM['template_id'] = 'LO5TMElDIlhk-Y5D1AnnvLDiLPaelsiU0hP023X9xpM';
			// $dataM['url'] = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect';
			$dataM['topcolor'] = '#FF0000';
			$dataM['data'] = array(
				'first' => array(
					'value' => '客户取消订单，请查看',
					'color' => '#000000',
				),
				'keyword1' => array(
					'value' => $orderinfo['id'],
					'color' => '#000000',
				),
				'keyword2' => array(
					'value' => $userinfo['display_name'],
					// 'value' => $userid,
					'color' => '#000000',
				),

				'keyword3' => array(
					'value' => $userinfo['mobile'],
					'color' => '#000000',
				),

				'remark' => array(
					'value' => '点击处理订单',
					'color' => '#000000',
				),
			);
			$ktvemp = M('ktvemp');
			$employees = $ktvemp->where(array('ktvid' => $ktvid, 'status' => 1))->select();
			if ($employees != null) {
				foreach ($employees as $key => $value) {
					$dataM['touser'] = $value['openid'];
					$this->weObj->sendTemplateMessage($dataM);
				}
			}

		}

	}
	public function sendPointChangeMessage() {
		$dataM = array();
		$dataM['template_id'] = 'sB9elydZv2PT-bSPozMgLbwrLmS0xIanf3RFk1jsJ4A';
		$dataM['url'] = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect';
		$dataM['topcolor'] = '#FF0000';
		$dataM['touser'] = 'okwyOwpvP0WJfi0GhGxzQ5sDJMCY';
		$dataM['data'] = array(
			'first' => array(
				'value' => '1',
				'color' => '#000000',
			),
			'keyword1' => array(
				'value' => '2',
				'color' => '#000000',
			),
			'keyword2' => array(
				'value' => '3',
				'color' => '#000000',
			),
			'keyword3' => array(
				'value' => '4',
				'color' => '#000000',
			),
			'keyword4' => array(
				'value' => '5',
				'color' => '#000000',
			),
			'keyword5' => array(
				'value' => '6',
				'color' => '#000000',
			),
			'remark' => array(
				'value' => '7',
				'color' => '#000000',
			),
		);
		$jsonstr = json_encode($dataM, true);
		$token_content = $this->httpGet('http://letsktv.chinacloudapp.cn/wechat/index.php?m=getToken');
		$token = json_decode($token_content, true);
		$post_url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $token['data'];
		// echo $post_url;
		echo $this->http_post($post_url, $jsonstr);

	}
	// 发送http_post请求
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

	private function httpGet($url) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//  curl_setopt($ch,CURLOPT_HEADER, false);

		$output = curl_exec($ch);

		curl_close($ch);
		return $output;
	}

// LO5TMElDIlhk-Y5D1AnnvLDiLPaelsiU0hP023X9xpM
	public function sendCustomMessage($data) {
		if ($data != null) {
			// var_dump($data);
			return $this->weObj->sendCustomMessage($data);
		}
	}

	public function getJsSign($url = '') {
		return $this->weObj->getJsSign($url, 0, '', 'wx90f8e48d4b4f5d8d');
	}

	public function getUserList($list) {
		return $this->weObj->getUserInfoList($list);
	}

	public function checkopenid($openid) {
		return $this->weObj->getUserInfo($openid);
	}

	public function getQRUrl() {
		$id = I('get.qrcode');
		$qrcode = $this->weObj->getQRCode($id, 1);
		var_dump($qrcode);
	}
}
