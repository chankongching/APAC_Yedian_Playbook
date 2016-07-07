<?php
namespace Home\Controller;
use Think\Controller;

class UserInfoController extends CommonController {
	public function index() {

	}
	public function GetUserInfo() {
		$user_ListS = M('platform_user', 'ac_')->where(array('auth_type' => 'wechat', 'id' => array('GT', '154191')))->field('openid')->select();
		$ccount = 0;
		$userlist = array();

		$wechatObj = new WeChatController();
		foreach ($user_ListS as $key => $value) {
			if ($ccount < 99) {
				if ($wechatObj->checkopenid($value['openid'])) {
					$userlist[] = array('openid' => $value['openid'], "lang" => "zh-CN");
					$ccount++;
				}
			} else {
				$wechat_user_info = $wechatObj->getUserList($userlist);
				$user_new = array();
				foreach ($wechat_user_info['user_info_list'] as $key => $value) {
					if ($value['subscribe'] == 1) {
						$user_new[] = $value;
					}
				}
				echo M('wechat_user_info', 'ac_')->addAll($user_new);
				// die();
				echo $ccount;
				echo 'ok';
				$ccount = 0;
				unset($userlist);
			}
		}
	}
}
