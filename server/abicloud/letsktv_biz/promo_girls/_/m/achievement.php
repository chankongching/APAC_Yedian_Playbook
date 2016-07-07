<?php
(INAPP !== true) && die('Error !');

// 权限检查
$query = sprintf("select `id`, `uid`, `openid`, `nickname`, `name`, `status` from `%s%s` where `openid`='%s' limit 1;", 
    $C['db']['pfix'],
    'users',
    $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid'])
);
$source = $DB->query($query);
$DB->errno > 0 && die(json_encode(array(
    'status'    => 0, 
    'code'      => $DB->errno, 
    'error'     => $DB->error
)));
if ($source->num_rows < 1) {
    die('error: 系统内部错误，请联系管理员。');
}
while ($row = $source->fetch_assoc()) {
    if($row['status'] == 0) {
        header('Location: index.php');exit;
        die('未绑定用户无法查看业绩信息。');
    }
    $user_qr = $row;
}

$data = array(
    'invite'        => 0, 
    'point_all'     => 0, 
    'point_used'    => 0, 
    'today'         => 0
);

$query = sprintf("select sum(`count`) as `count` from `promogirls_qrcodes_scan_log` where `uid` = %d;", 
    $user_qr['id']
);
$source = $DB->query($query);
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        $data['invite'] = $row['count'];
    }
}

$query = sprintf("select `point__all`, `point__used` from `promogirls_users` where `id` = %d limit 1;", 
    $user_qr['id']
);
$source = $DB->query($query);
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        $data['point_all']  = intval($row['point__all']);
        $data['point_used'] = intval($row['point__used']);
    }
}

// 设置开始时间
if(intval((date('H', TIME))) >= 0 && intval((date('H', TIME))) < 8) {
    $dateline_start = strtotime(date('Y-m-d 00:00:00', $_SERVER['REQUEST_TIME']));
}
if(intval((date('H', TIME))) >= 8 && intval((date('H', TIME))) < 24) {
//     $dateline_start = strtotime(date('Y-m-d 09:00:00', $_SERVER['REQUEST_TIME']));
    $dateline_start = strtotime(date('Y-m-d 19:00:00', $_SERVER['REQUEST_TIME']));
}

// 设置结束时间
if(intval((date('H', TIME))) >= 0 && intval((date('H', TIME))) < 8) {
    $dateline_expire    = strtotime(date('Y-m-d 00:59:59', $_SERVER['REQUEST_TIME']));
    $expire             = strtotime(date('Y-m-d 07:59:59', $_SERVER['REQUEST_TIME']));
}
if(intval((date('H', TIME))) >= 8 && intval((date('H', TIME))) < 24) {
    $dateline_expire    = strtotime(date('Y-m-d 00:59:59', strtotime('+1 day')));
    $expire             = strtotime(date('Y-m-d 07:59:59', strtotime('+1 day')));
}
// 查询当前周期二维码
$query = sprintf("select `l`.`count` from `promogirls_qrcodes_scan_log` as `l` where `l`.`uid`=%d and `l`.`dateline_expire` = '%s' limit 1;", 
    $DB->real_escape_string($user_qr['id']), 
    $DB->real_escape_string(date('Y-m-d H:i:s', $dateline_expire))
);
$source = $DB->query($query);
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        $data['today']  = intval($row['count']);
    }
}

/*
require_once '_/_inc_point.php';
$query = sprintf("select * from `promogirls_qrcodes_scan_log` where `uid` = %s;", 
    $user_qr['uid']
);
$source = $DB->query($query);
if($source->num_rows > 0) {
    $point = 0;
    while($row = $source->fetch_assoc()) {
        $data['invite'] += intval($row['count']);
        $config = getConfig($row['dateline_expire']);
        if($row['dateline_expire'] < '2015-12-14 08:00:00') {
            $point__all = min($row['count'], $config['limit']) * $config['point'];
        } else {
            $point__all = (min($row['count'], $config['limit']) * $config['point']) + (max($row['count'] - $config['limit'], 0) * 100);
        }
        $point += $point__all;
    }
//     echo $point;
}
*/

require_once V.'header.php';
require_once V.'achievement.php';
require_once V.'footer.php';