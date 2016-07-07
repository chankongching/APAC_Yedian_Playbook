<?php
(INAPP !== true) && die('Error !');

if (METHOD == 'POST') {
} else {
    if(AJAX) {
        $id     = (isset($_GET['id']) && intval($_GET['id']) > 0) ? intval($_GET['id']) : exit(json_encode(array('status' => 0, 'msg' => 'no id')));
        $status = (isset($_GET['status']) && in_array(trim($_GET['status']), array('0', '1'))) ? (trim($_GET['status']) == '1' ? 0 : 1) : exit(json_encode(array('status' => 0, 'msg' => 'no status')));
        
        $query = sprintf("update `%s%s` set status = %d where `id` = %d limit 1;", 
            $C['db']['pfix'], 
            'users', 
            $status, 
            $id
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status' => 0, 
            'msg'    => 'db error'
        )));
        if($source) {
            exit(json_encode(array(
                'status' => 1, 
                'data'   => array(
                    'status' => $status, 
                    'btn'    => ($status == 0) ? '已禁用' : '工作中', 
                    'a'      => ($status == 0) ? '启用' : '禁用', 
                    'style'  => ($status == 0) ? 'warning' : 'success' 
                )
            )));
        }
        exit;
    }
    exit(json_encode(array(
        'status' => 0, 
        'msg'    => 'error'
    )));
/*
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
*/
}