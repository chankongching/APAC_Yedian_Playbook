<?php
(INAPP !== true) && die('Error !');

// var_dump(METHOD);exit;
// print_r($_SERVER);

if (METHOD == 'POST') {
	$type = (isset($_POST['type']) && in_array(trim($_POST['type']), array('confirm', 'cancel'))) ? trim($_POST['type']) : exit(json_encode(array(
		'status' => 0,
		'error' => '参数 type 不正确。',
	)));
	$id = (isset($_POST['id']) && intval($_POST['id']) > 0) ? intval($_POST['id']) : exit(json_encode(array(
		'status' => 0,
		'error' => '参数 id 不正确。',
	)));
	$query = sprintf("select 1 from `%s%s` where `id`=%d and `cc_user`=%d limit 1;",
		$C['db']['pfix'],
		'order',
		$id,
		$_SESSION['callcenter_uid']
	);
	$source = $DB->query($query);
	$DB->errno > 0 && die('code: ' . $DB->errno . ', error:' . $DB->error);
	if ($source->num_rows < 1) {
		exit(json_encode(array(
			'status' => 0,
			'error' => '没有该订单，或该订单没有分配给您。',
		)));
	}
	switch ($type) {
	case 'confirm':
		$query = sprintf("update `%s%s` set `status`=%d, `update_time`='%s', `confirm_time`=%d where `id`=%d limit 1",
			$C['db']['pfix'],
			'order',
			3,
			DATETIME,
			TIME,
			$id
		);
		$sms = '您好，您已经成功预订%s%s，开始时间为%s年%s月%s日%s，持续时间为%s个小时，请提前半小时到店，凭借手机号码到前台消费。如有任何问题，请拨打夜点客服电话4006507351，感谢您对夜点的支持。【夜点应用】';
		break;
	case 'cancel':
		$query = sprintf("update `%s%s` set `status`=%d, `update_time`='%s', `confirm_time`=%d where `id`=%d limit 1",
			$C['db']['pfix'],
			'order',
			4,
			DATETIME,
			TIME,
			$id
		);
		$sms = '抱歉，您已经预定的%s%s，开始时间为%s年%s月%s日%s，持续时间为%s个小时，因预定房间已满，所以预定不成功。请尝试选择其他时段或者其他KTV。感谢您对夜点的支持。【夜点应用】';
		break;
	}
	$DB->query($query);
	$DB->errno > 0 && die(json_encode(array(
		'status' => 0,
		'error' => '服务器内部错误，请联系管理员。',
	)));
	$query = sprintf("select `k`.`name`, `o`.`roomtype`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`
        from
        `%s%s` as `o`,
        `%s%s` as `u`,
        `%s%s` as `k`
        where
        `k`.`xktvid` = `o`.`ktvid`
        and
        `u`.`id` = `o`.`userid`
        and
        `o`.`id` = %d and `o`.`cc_status` > -1 limit 1;",
		$C['db']['pfix'],
		'order',
		$C['db']['pfix'],
		'platform_user',
		$C['db']['pfix'],
		'xktv',
		$id
	);
	$source = $DB->query($query);
	$DB->errno > 0 && die(json_encode(array(
		'status' => 0,
		'error' => '服务器内部错误，请联系管理员。',
	)));
	if ($source->num_rows > 0) {
		while ($row = $source->fetch_assoc()) {
			$data = $row;
		}
		$mobile = $data['mobile'];
		$content = sprintf($sms,
			$data['name'],
			$roomtypes[$data['roomtype']],
			date('Y', $data['starttime']),
			date('m', $data['starttime']),
			date('d', $data['starttime']),
			date('H:i', $data['starttime']),
			($data['endtime'] - $data['starttime']) / 3600
		);
	}
	$sendSms = new SendSmsHttp();
	$sendSms->mobile = $mobile;
	$sendSms->content = $content;
	$res = $sendSms->send();
	if (!$res) {
		exit(json_encode(array(
			'status' => 0,
			'error' => '短信发送失败，请联系管理员。',
		)));
	}
	exit(json_encode(array(
		'status' => 1,
	)));
} else {
	$query = sprintf("select `id`, `username` from `%s%s` where `role`='operator' order by `id` asc;",
		$C['db']['pfix'],
		'cc_users'
	);
	$source = $DB->query($query);
	$DB->errno > 0 && die('code: ' . $DB->errno . ', error: ' . $DB->error);
	while ($row = $source->fetch_assoc()) {
		$users_by_id[$row['id']] = $row['username'];
	}

	$order = array();
	$referer = $_SERVER['HTTP_REFERER'];
	$id = (isset($_GET['id']) && intval($_GET['id']) > 0) ? intval($_GET['id']) : exit('ID 不正确，请返回。');
	$_uid_mysql_param = $_SESSION['callcenter_role'] == 'operator' ? ' and `o`.`cc_user`=' . $_SESSION['callcenter_uid'] : '';
	$query = sprintf("select
        `o`.`id`, `o`.`time`, `k`.`name`, `k`.`pretelephone`, `k`.`telephone`, `k`.`district`, `k`.`address`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
        from
        `%s%s` as `o`,
        `%s%s` as `u`,
        `%s%s` as `k`
        where
        `k`.`xktvid` = `o`.`ktvid`
        and
        `u`.`id` = `o`.`userid`
        and
        `o`.`id` = %d and `o`.`cc_status` > -1 %s limit 1;",
		$C['db']['pfix'],
		'order',
		$C['db']['pfix'],
		'platform_user',
		$C['db']['pfix'],
		'xktv',
		$id,
		$_uid_mysql_param
	);
	$source = $DB->query($query);
	$DB->errno > 0 && die('code: ' . $DB->errno . ', error:' . $DB->error);
	if ($source->num_rows > 0) {
		while ($row = $source->fetch_assoc()) {
			$row['time'] = date('Y-m-d H:i:s', $row['time']);
			$row['last'] = ($row['endtime'] - $row['starttime']) / 3600;
			$row['starttime'] = date('Y-m-d H:i:s', $row['starttime']);
			$row['endtime'] = date('Y-m-d H:i:s', $row['endtime']);
			$row['update_time'] = ($row['update_time'] && ($row['update_time'] !== $row['time'])) ? $row['update_time'] : '-';
			$order = $row;
		}
	}
//     print_r($order);exit;
	//     exit($query);
}
require_once V . 'order.php';