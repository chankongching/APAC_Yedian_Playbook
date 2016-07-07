<?php
namespace Home\Controller;
use Org\Util\Wechat;
use Think\Controller;

class WeChatController extends CommonController {
	public function __construct() {
		parent::__construct();
		$options = C('wxoptions');
		$this->weObj = new TPWechatController($options);
	}
	public function Gateway() {
		$this->weObj->valid();
		$type = $this->weObj->getRev()->getRevType();
		switch ($type) {
		case Wechat::MSGTYPE_TEXT:
			$text = $this->weObj->getRevContent();
			if (trim($text) == '指南') {
//				$this->weObj->text("HHH")->reply();
				$znmsg = $this->weObj->getForeverMedia('1fXYTkmVtFK4S-bJEhtrMqu0g7K9SOyzX-jntpbUAgM');
				$this->weObj->news(array(
					"0" => array(
						'Title' => $znmsg['news_item'][0]['title'],
						'Description' => $znmsg['news_item'][0]['digest'],
						'PicUrl' => $znmsg['news_item'][0]['thumb_url'],
						'Url' => $znmsg['news_item'][0]['url'],
					),
				))->reply();
			} elseif (trim($text) == '0') {
				$this->weObj->text("您好，客服人员马上就来，请您稍作等待。\n\n您也可拨打客服电话400-650-7351咨询您的问题，感谢您对夜点娱乐的支持与理解。")->reply();
			} elseif (trim($text) == '1') {
				$this->weObj->text("KTV操作人员可点击以下链接快速进入相应界面：\n\n<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FIndex%2Fbindf&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">操作人员账号绑定</a>\n\n<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">处理订单</a>\n\n<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FConfirmOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">到店登记</a>\n\n如需人工帮助，请回复数字【0】")->reply();
			} elseif (trim($text) == '2') {
				$this->weObj->text("KTV管理人员可点击以下链接快速进入相应界面：\n\n<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FIndex%2Fbindm&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">管理人员账号绑定</a>\n\n<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FHome%2FVerify&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">酒水核销</a>\n\n<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FIndex%2Fktvmanage&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">员工管理</a>\n\n<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">订单管理</a>\n\n回复数字【8】可快速生成验证码\n\n如需人工帮助，请回复数字【0】")->reply();
			} elseif (trim($text) == '8') {
				$this->weObj->text($this->MakeEWM())->reply();
			} elseif (in_array(trim($text), array('绑定', '新增', '增加', '添加'))) {
				$this->weObj->text("您可点击以下链接快速进入相应界面：\n\n<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FIndex%2Fbindm&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">绑定管理人员</a>\n\n<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FIndex%2Fbindf&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">绑定操作人员</a>\n\n回复【指南】可以查看夜点商家版操作指南\n\n如需人工帮助，请回复数字【0】")->reply();
			} elseif (in_array(trim($text), array('兑酒', '核销', '兑', '酒', '核', '销'))) {
				$this->weObj->text("KTV管理人员可点击以下链接快速进入相应界面：\n\n<a href =\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FHome%2FVerify&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">酒水核销</a>\n\n回复【指南】可以查看夜点商家版操作指南\n\n如需人工帮助，请回复数字【0】")->reply();
			} elseif (in_array(trim($text), array('生成', '验证码', '串码'))) {
				$this->weObj->text("KTV管理人员可回复数字【8】快速生成验证码\n\n回复【指南】可以查看夜点商家版操作指南\n\n如需人工帮助，请回复数字【0】")->reply();
			} elseif (in_array(trim($text), array('登记', '处理'))) {
				$this->weObj->text("KTV操作人员可点击以下链接快速进入相应界面：\n\n<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">处理订单</a>\n\n<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FConfirmOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">到店登记</a>\n\n回复【指南】可以查看夜点商家版操作指南\n\n如需人工帮助，请回复数字【0】")->reply();
			} elseif (in_array(trim($text), array('管理', '查看'))) {
				$this->weObj->text("KTV管理人员可点击以下链接快速进入相应界面：\n\n<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect\">订单管理</a>\n\n回复【指南】可以查看夜点商家版操作指南\n\n如需人工帮助，请回复数字【0】")->reply();
			} else {
				$this->weObj->text("您好，感谢关注【夜点娱乐商家版】\n\n回复数字进入相应界面：\n\n【1】	KTV操作人员相关\n【2】	KTV管理人员相关\n\n回复【指南】可以查看夜点商家版操作指南\n\n如需人工帮助，请回复数字【0】")->reply();
			}
			exit;
			break;
		case Wechat::MSGTYPE_EVENT:
			$events = $this->weObj->getRevEvent();
//			$this->weObj->text($events['type'])->reply();
			if ($events['event'] == Wechat::EVENT_SUBSCRIBE) {
				$this->weObj->text("您好，感谢关注【夜点娱乐商家版】\n\n回复数字进入相应界面：\n\n【1】	KTV操作人员相关\n【2】	KTV管理人员相关\n\n回复【指南】可以查看夜点商家版操作指南\n\n如需人工帮助，请回复数字【0】")->reply();
			} elseif ($events['event'] == Wechat::EVENT_MENU_CLICK) {
				if ($events['key'] == 'MakeEWM') {
					$this->weObj->text($this->MakeEWM())->reply();
				}
			}
			break;
		case Wechat::MSGTYPE_IMAGE:
			break;
		default:
			$this->weObj->text("您好，欢迎关注【夜点娱乐商家版】。在这里您可以：处理KTV预定订单；获取最新夜点商家活动信息；了解KTV行业资讯。\n需要了解“夜点管家使用指南”请回复【指南】。\n祝您工作顺利，生活愉快！")->reply();

		}
	}
	public function createMenu() {
		// echo $this->weObj->getMenu();
		//设置菜单
		$newmenu = array(
			"button" => array(
				array('name' => '操作人员', 'sub_button' => array(
					array(
						'name' => '账号绑定',
						'type' => 'view',
						'url' => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FIndex%2Fbindf&response_type=code&scope=snsapi_base&state=OK#wechat_redirect",
					),
					array(
						'name' => '处理订单',
						'type' => 'view',
						'url' => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect",
					),
					array(
						'name' => '到店登记',
						'type' => 'view',
						'url' => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FConfirmOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect",
					),
//					array(
					//						'name' => '活动列表',
					//						'type' => 'view',
					//						'url' => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FHome%2FActivity%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect",
					//					),
				)),
				array(
					'name' => '管理人员',
					'sub_button' => array(
						array(
							'name' => '账号绑定',
							'type' => 'view',
							'url' => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FIndex%2Fbindm&response_type=code&scope=snsapi_base&state=OK#wechat_redirect",
						),
						array(
							'name' => '酒水核销',
							'type' => 'view',
							'url' => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FHome%2FVerify&response_type=code&scope=snsapi_base&state=OK#wechat_redirect",
						),
						array(
							'name' => '生成验证码', 'type' => 'click', 'key' => 'MakeEWM'),
						array(
							'name' => '员工管理',
							'type' => 'view',
							'url' => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FIndex%2Fktvmanage&response_type=code&scope=snsapi_base&state=OK#wechat_redirect",
						),
						array(
							'name' => '订单管理',
							'type' => 'view',
							'url' => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect",
						),
					)),
				array(
					'name' => '生意经',
					'type' => 'view',
					'url' => 'http://letsktv.chinacloudapp.cn/wechatshangjia/Home/WeChat/HistoryRedirect',
				),
			),
		);
		$result = $this->weObj->createMenu($newmenu);
	}

	public function HistoryRedirect() {
		header('Location:http://mp.weixin.qq.com/mp/getmasssendmsg?__biz=MzA3NDU3MjY1Ng==#wechat_webview_type=1&wechat_redirect');
	}

	public function getMenu() {
		$menu = $this->weObj->getMenu();
		var_dump($menu);
	}

	public function getMediaList() {
		$List = $this->weObj->getForeverList('news', 0, 100);
		$data = $this->weObj->getForeverMedia('1fXYTkmVtFK4S-bJEhtrMqu0g7K9SOyzX-jntpbUAgM');
		var_dump($data);
		var_dump($List);
	}

	public function getOauthUrl() {
		// $callback='http://letsktv.chinacloudapp.cn/wechatshangjia/Index/bind';
		// $callback='http://letsktv.chinacloudapp.cn/wechatshangjia/Index/ktvmanage';
		// $callback = 'http://letsktv.chinacloudapp.cn/wechatshangjia/Activity/dui_success';
		$callback = 'http://letsktv.chinacloudapp.cn/wechatshangjia/Home/Verify';
		$state = 'OK';
		$scope = 'snsapi_base';
		$res = $this->weObj->getOauthRedirect($callback, $state, $scope);
		var_dump($res);
	}

	public function MakeEWM() {
		$openid = $this->weObj->getRevFrom();
		$res = M('ktvmanager')->where(array('openid' => $openid, 'status' => 1))->find();
		$ecount = M('ktvemp')->where(array('status' => '1', 'ktvid' => $res['ktvid']))->Count('openid');
		$ercount = M('yzm')->where(array('ktvid' => $res['ktvid'], 'is_cancel' => '0'))->Count('ktvid');
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
			$yz->status = '1';
			$yz->create_time = date('Y-m-d H:i:s');
			$yz->add();
			$msg = '您已经生成了新的验证码[' . $yzm . ']';
		}
		return $msg;
	}

	public function getOauthAccessToken() {
		return $this->weObj->getOauthAccessToken();
	}

	public function sendTemplateMessage() {
		if (IS_POST) {
			$post_data = file_get_contents("php://input");
			$post_array = json_decode($post_data, true);
			$ktvid = isset($post_array["ktvid"]) ? $post_array["ktvid"] : '';
			$userid = isset($post_array["userid"]) ? $post_array["userid"] : '';
			$orderid = isset($post_array["orderid"]) ? $post_array["orderid"] : '';
			$order = M('order', 'ac_');
			$orderinfo = $order->where(array('id' => $orderid))->find();
			$user = M('platform_user', 'ac_');
			$userinfo = $user->where(array('id' => $userid))->find();
			$ktv = M('xktv', 'ac_');
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
			$employees = $ktvemp->where(array('ktvid' => $ktvid, 'status' => '1'))->select();
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
			$order = M('order', 'ac_');
			$orderinfo = $order->where(array('id' => $orderid))->find();
			$user = M('platform_user', 'ac_');
			$userinfo = $user->where(array('id' => $userid))->find();
			$ktv = M('xktv', 'ac_');
			$ktvinfo = $ktv->where(array('id' => $ktvid))->find();
			$dataM = array();
			$dataM['template_id'] = 'LO5TMElDIlhk-Y5D1AnnvLDiLPaelsiU0hP023X9xpM';
			// $dataM['url'] = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a8fbf2b1083d924&redirect_uri=http%3A%2F%2Fletsktv.chinacloudapp.cn%2Fwechatshangjia%2FOrder%2F&response_type=code&scope=snsapi_base&state=OK#wechat_redirect';
			$dataM['topcolor'] = '#FF0000';
			$dataM['data'] = array(
				'first' => array(
					'value' => '客户取消订单',
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
					'value' => '',
					'color' => '#000000',
				),
			);
			$ktvemp = M('ktvemp');
			$employees = $ktvemp->where(array('ktvid' => $ktvid, 'status' => '1'))->select();
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
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}

	public function sendCustomMessage($data) {
		if ($data != null) {
			return $this->weObj->sendCustomMessage($data);
		}
	}

	public function sendCustomMessageByApi() {
		if (IS_POST) {
			$post_data = file_get_contents("php://input");
			$post_array = json_decode($post_data, true);
			$msg = isset($post_array["msg"]) ? $post_array["msg"] : '';
			$toopenid = isset($post_array["openid"]) ? $post_array["topenid"] : '';
			$data = array('touser' => $toopenid, 'msgtype' => 'text', "text" => array(
				'content' => $msg));
			$result = $this->weObj->sendCustomMessage($data);
			echo json_encode($result, true);
			return $result;
		}

	}

	public function getJsSign($url = '') {
		return $this->weObj->getJsSign($url);
	}

	public function getUserInfo($openid) {
		return $this->weObj->getUserInfo($openid);
	}

	public function uinfo() {
		if (IS_GET) {
			$openid = I('get.openid');
			var_dump($this->getUserInfo($openid));
		}
	}
}