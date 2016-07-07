<?php
namespace Home\Controller;
use Think\Controller;

class WeChatApiController extends CommonController {
	public function __construct() {
		parent::__construct();
		$options = C('Wx_Options');
		$this->weObj = new TPWechatController($options);
	}
	protected function sendmsg($openid, $msg) {
		$data = array('touser' => $openid,
			'msgtype' => 'text',
			"text" => array(
				'content' => $msg));
		return $this->weObj->sendCustomMessage($data);
	}

	protected function sendPointChangeMessage($info, $jifen, $yue, $title = '', $yuanyin = '', $remark = '') {
		$dataM = array();
		$dataM['template_id'] = 'sB9elydZv2PT-bSPozMgLbwrLmS0xIanf3RFk1jsJ4A';
		$dataM['url'] = '';
		$dataM['topcolor'] = '#FF0000';
		$dataM['touser'] = $info['openid'];
		$dataM['data'] = array(
			'first' => array(
				'value' => $title,
				'color' => '#000000',
			),
			'keyword1' => array(
				'value' => $info['display_name'],
				'color' => '#000000',
			),
			'keyword2' => array(
				'value' => date("Y-m-d h:i"),
				'color' => '#000000',
			),
			'keyword3' => array(
				'value' => $jifen,
				'color' => '#000000',
			),
			'keyword4' => array(
				'value' => $yue,
				'color' => '#000000',
			),
			'keyword5' => array(
				'value' => $yuanyin,
				'color' => '#000000',
			),
			'remark' => array(
				'value' => $remark,
				'color' => '#000000',
			),
		);
		$this->weObj->sendTemplateMessage($dataM);
	}

	public function PointChangMessage() {
		if (IS_POST) {
			$jifen = I("post.jifen");
			$yue = I("post.yue");
			$title = I("post.title");
			$yuanyin = I("post.yuanyin");
			$remark = I("post.remark");
			$uid = I("post.uid");
			$userinfo = M('PlatformUser', 'ac_', array(
				'DB_TYPE' => 'mysql', // 数据库类型
				'DB_HOST' => 'localhost', // 服务器地址
				'DB_NAME' => 'abicloud', // 数据库名
				'DB_USER' => 'website', // 用户名
				'DB_PWD' => 'WebSite456', // 密码
				'DB_PORT' => '3306', // 端口
			));
			$touser = $userinfo->where(array('id' => $uid))->find();
			if ($touser != NULL) {
				$this->sendPointChangeMessage($touser, $jifen, $yue, $title, $yuanyin, $remark);
			}
		} else {
			die(json_encode(array('error' => '400'), true));
		}

	}

	public function sendMsgToUid() {
		if (IS_POST) {
			$uid = I('uid');
			$msg = I('msg');
			$userinfo = M('PlatformUser', 'ac_', array(
				'DB_TYPE' => 'mysql', // 数据库类型
				'DB_HOST' => 'localhost', // 服务器地址
				'DB_NAME' => 'abicloud', // 数据库名
				'DB_USER' => 'website', // 用户名
				'DB_PWD' => 'WebSite456', // 密码
				'DB_PORT' => '3306', // 端口
			));
			$touser = $userinfo->where(array('id' => $uid))->find();
			if ($touser != NULL) {
				$openid = $touser['openid'];
				if ($openid != NULL) {
					$result = $this->sendmsg($openid, $msg);
					die(json_encode($result, true));
				}
			}
		} else {
			die(json_encode(array('error' => '400'), true));
		}
	}
	public function getOpenidStatus() {
		$openid = I('get.openid');
		$scanid = I('get.scanid');
		if ($openid != null) {
			$news = $this->getOpenidStatuss($openid, $scanid);
			if ($news === false) {
				die(json_encode(0));
			}
//			switch ($status) {
			//			case '1':
			//				$news = array(
			//					'title' => '兑酒券已放入您的账户！',
			//					'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/TAQPDicjviavQ9nBp2HWov6h358SRSanW4zUQia8icXGWOtOa3iaMwSkQ4sEU7X5ia1pic9ZI2ibBYIqqutOVCR2A3968g/0?wx_fmt=jpeg',
			//					'url' => $url,
			//				);
			//				break;
			//			case '2':
			//				$news = array(
			//					'title' => '您已经成功获取夜点兑酒券！',
			//					'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/TAQPDicjviavQ9nBp2HWov6h358SRSanW4zUQia8icXGWOtOa3iaMwSkQ4sEU7X5ia1pic9ZI2ibBYIqqutOVCR2A3968g/0?wx_fmt=jpeg',
			//					'url' => $url,
			//				);
			//				break;
			//			case '3':
			//				$news = array(
			//					'title' => '您已成功获取夜点兑酒券！',
			//					'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/TAQPDicjviavQ9nBp2HWov6h358SRSanW4zUQia8icXGWOtOa3iaMwSkQ4sEU7X5ia1pic9ZI2ibBYIqqutOVCR2A3968g/0?wx_fmt=jpeg',
			//					'url' => $url,
			//				);
			//				break;
			//			case '4':
			//				$news = array(
			//					'title' => '恭喜获得夜点兑酒券！',
			//					'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/TAQPDicjviavQ9nBp2HWov6h358SRSanW4zUQia8icXGWOtOa3iaMwSkQ4sEU7X5ia1pic9ZI2ibBYIqqutOVCR2A3968g/0?wx_fmt=jpeg',
			//					'url' => $url,
			//				);
			//				break;
			//
			//			default:
			//				# code...
			//				break;
			//			}
			die(json_encode(array('news' => $news[0])));
		}
	}

	protected function getOpenidStatuss($openid = '', $scanid = '') {
		$url = $this->getEventUrl($scanid);
		if ($url === false) {
			return false;
		}
		if ($scan_id == 'event_djiuq') {
			if ($this->ismobileuser($openid)) {
				$news = array();
				$news[0] = array(
					'title' => '兑酒券已放入您的账户',
					'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/TAQPDicjviavQ9nBp2HWov6h358SRSanW4zUQia8icXGWOtOa3iaMwSkQ4sEU7X5ia1pic9ZI2ibBYIqqutOVCR2A3968g/0?wx_fmt=jpeg', //图1 立即
					'url' => $url,
				);
			} else {
				$news = array();
				$news[0] = array(
					'title' => '您已经成功获取夜点兑酒券',
					'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/TAQPDicjviavQ9nBp2HWov6h358SRSanW4zUQia8icXGWOtOa3iaMwSkQ4sEU7X5ia1pic9ZI2ibBYIqqutOVCR2A3968g/0?wx_fmt=jpeg', //图1 立即
					'url' => $url,
				);
			}
		} else {
			if ($this->isgetcoupon($openid)) {
				$news = array();
				$news[0] = array(
					'title' => '您已成功获取夜点兑酒券',
					'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/TAQPDicjviavQ9nBp2HWov6h358SRSanW4zUQia8icXGWOtOa3iaMwSkQ4sEU7X5ia1pic9ZI2ibBYIqqutOVCR2A3968g/0?wx_fmt=jpeg', //图1 立即
					'url' => $url,
				);
			} else {
				$news = array();
				$news[0] = array(
					'title' => '恭喜获得夜点兑酒券',
					'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/TAQPDicjviavQ9nBp2HWov6h358SRSanW4KKeUuxDp584oJuDuS2VJ3uMZbNBNRaQanKH8XYrnG2wibrY6yCiaBgqw/0?wx_fmt=jpeg', //图2 点击
					'url' => $url,
				);
			}
		}
		return $news;
	}

	protected function getEventUrl($ktvid = '') {
		if ($ktvid != '') {
			if ($ktvid == 'event_djiuq') {
				return 'http://letsktv.chinacloudapp.cn/wechat_ktv/Home/Event/enter';
			} else {
				if (M('xktv', 'ac_')->where(array('xktvid' => $ktvid, 'type' => 2, 'status' => 1))->find() != null) {
					return 'http://letsktv.chinacloudapp.cn/wechat_ktv/Home/Event/enter/ktvid/' . $ktvid;
				} else {
					return 'http://letsktv.chinacloudapp.cn/wechat_ktv/Home/Event/enter';
				}
			}

		} else {
			return false;
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
//	public function runspr(){
	//		$orders = M('order','ac_')->where(array('status'=>5,'create_time'=>array('BETWEEN',array('2016-05-17 00:00:00','2016-05-23 00:00:00'))))->select();
	//		foreach($orders as $key => $value){
	//
	//			$userinfo = M('platform_user','ac_')->where(array('id'=>$value['userid']))->find();
	//			if($userinfo!=null){
	//				$url = C('server_host') . '/letsktv_biz/promo_girls/_api.php?openid=' . $userinfo['openid'] . '&order=' . $value['time'] . '&confirm=' . strtotime($value['update_time']);
	//
	//				$curl = curl_init();
	//				//设置抓取的url
	//				curl_setopt($curl, CURLOPT_URL, $url);
	//				//设置头文件的信息作为数据流输出
	//				curl_setopt($curl, CURLOPT_HEADER, 1);
	//				//设置获取的信息以文件流的形式返回，而不是直接输出。
	//				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	//				//执行命令
	//				$data = curl_exec($curl);
	//				//关闭URL请求
	//				curl_close($curl);
	//				//显示获得的数据
	//				// print_r($data);
	//				echo $data;
	//			}
	//
	//		}
	//	}
}
