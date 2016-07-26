<?php
namespace Business\Controller;
use Think\Controller;

class UserController extends CommonController {
	public function insertNewDB() {
		$connection = array(
			'db_type' => 'mysql',
			'db_host' => '127.0.0.1',
			'db_user' => 'letsktv',
			'db_pwd' => 'OBjhe7UF3IsMIwPK',
			'db_port' => 3306,
			'db_name' => 'letsktv_wechat',
			'db_charset' => 'utf8',
		);
		$Users = M('logs_subscribe', 'letsktv_', $connection)->query("select DISTINCT IFNULL(letsktv_logs_subscribe.`FromUserName`,'000000') as openid from `letsktv_qrcodes`  left join `letsktv_logs_subscribe` on `letsktv_logs_subscribe`.`Ticket`=`letsktv_qrcodes`.`ticket` where letsktv_qrcodes.`detail` like '%KTV促销员%'");
		if ($Users != null) {
			foreach ($Users as $key => $value) {
				M('UserInfo', 'stat_')->add($value);
			}
			echo 'OK';
		}

	}
	public function insertNewDBN() {
		$connection = array(
			'db_type' => 'mysql',
			'db_host' => '127.0.0.1',
			'db_user' => 'letsktv',
			'db_pwd' => 'OBjhe7UF3IsMIwPK',
			'db_port' => 3306,
			'db_name' => 'letsktv_wechat',
			'db_charset' => 'utf8',
		);
		$Users = M('logs_subscribe', 'letsktv_', $connection)->query("select DISTINCT IFNULL(letsktv_logs_subscribe.`FromUserName`,'000000') as openid from `letsktv_qrcodes`  left join `letsktv_logs_subscribe` on `letsktv_logs_subscribe`.`Ticket`=`letsktv_qrcodes`.`ticket` where letsktv_qrcodes.`detail` not like '%KTV促销员%'");
		if ($Users != null) {
			foreach ($Users as $key => $value) {
				M('UserInfo', 'stat_')->add(array('openid' => $value['openid'], 'source' => 1));
			}
			echo 'OK';
		}

	}
}