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
    die(json_encode(array(
        'status'    => 0, 
        'error'     => '系统内部错误，请联系管理员。'
    )));
}
while ($row = $source->fetch_assoc()) {
    if($row['status'] == 0) {
        header('Location: index.php');exit;
        die('未绑定用户无法获取二维码。');
    }
    $user_qr = $row;
}

// 设置开始时间
if(intval((date('H', TIME))) >= 0 && intval((date('H', TIME))) < 8) {
    $dateline_start         = strtotime(date('Y-m-d 00:00:00', $_SERVER['REQUEST_TIME']));
    $dateline_start_checkin = strtotime(date('Y-m-d 08:00:00', strtotime('-1 day')));
}
if(intval((date('H', TIME))) >= 8 && intval((date('H', TIME))) < 24) {
//     $dateline_start = strtotime(date('Y-m-d 09:00:00', $_SERVER['REQUEST_TIME']));
    $dateline_start         = strtotime(date('Y-m-d 19:00:00', $_SERVER['REQUEST_TIME']));
    $dateline_start_checkin = strtotime(date('Y-m-d 08:00:00', $_SERVER['REQUEST_TIME']));
}

// 设置结束时间
if(intval((date('H', TIME))) >= 0 && intval((date('H', TIME))) < 8) {
    $expire                     = strtotime(date('Y-m-d 07:59:59', $_SERVER['REQUEST_TIME']));
    $dateline_expire            = strtotime(date('Y-m-d 00:59:59', $_SERVER['REQUEST_TIME']));
    $dateline_expire_checkin    = strtotime(date('Y-m-d 07:59:59', $_SERVER['REQUEST_TIME']));
}
if(intval((date('H', TIME))) >= 8 && intval((date('H', TIME))) < 24) {
    $expire                     = strtotime(date('Y-m-d 07:59:59', strtotime('+1 day')));
    $dateline_expire            = strtotime(date('Y-m-d 00:59:59', strtotime('+1 day')));
    $dateline_expire_checkin    = strtotime(date('Y-m-d 07:59:59', strtotime('+1 day')));
}

$query = sprintf("select `ktv_id` from `%s%s` where `openid` = '%s' and `dateline` between '%s' and '%s';", 
    $C['db']['pfix'],
    'checkin_history',
    $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid']),
    $DB->real_escape_string(date('Y-m-d H:i:s', $dateline_start_checkin)),
    $DB->real_escape_string(date('Y-m-d H:i:s', $dateline_expire_checkin))
);
$source = $DB->query($query);
$DB->errno > 0 && die(json_encode(array(
    'status'    => 0, 
    'error'     => $DB->error
)));
if($source->num_rows < 1) {
    header('Location: http://letsktv.chinacloudapp.cn/letsktv_biz/promo_girls/index.php?m=checkin');
    exit;
}
while ($row = $source->fetch_assoc()) {
    $xktv_id = $row['ktv_id'];
}

// 查询当前周期二维码
$query = sprintf("select `id`, `ticket` from `%s%s` where `openid`='%s' and `dateline` < '%s' and `expire` > '%s' limit 1;", 
    $C['db']['pfix'],
    'qrcodes', 
    $DB->real_escape_string($user_qr['openid']), 
    $DB->real_escape_string(DATETIME), 
    $DB->real_escape_string(DATETIME)
);
$source = $DB->query($query);
$DB->errno > 0 && die(json_encode(array(
    'status'    => 0, 
    'code'      => $DB->errno, 
    'error'     => $DB->error
)));
/*
if ($source->num_rows > 0) {
    // 有，直接给出ticket
    while ($row = $source->fetch_assoc()) {
        $qr =  $row['ticket'];
    }
} else {
    // 无，重新申请
*/
    
    $time_info  = json_encode(array(
        'dateline_start'    => $dateline_start, 
        'dateline_expire'   => $dateline_expire
    ));
    $detail     = 'KTV促销员:'.$user_qr['name'].'|'.$time_info.'|'.$xktv_id;
    $url = sprintf(QRAPI."api.php?m=getQR&start=%s&expire=%s&openid=%s&detail=%s", 
        $dateline_start, 
        $expire, 
        $_SESSION['letsktv_biz_promogirls_openid'], 
        $detail
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $resp = curl_exec($ch);
    !$resp && die('code: '.curl_errno($ch).', error: '.curl_error($ch));
    $rs = json_decode($resp, true);
    curl_close($ch);
    if (isset($rs['status']) && $rs['status'] == 0) {
        die('code: '.$rs['code'].', error: '.$rs['error']);
    }
    
    // 二维码积分及限额
    require_once DIR.'_inc_point.php';
    $config = getConfig();
    
    // 本地记录二维码信息
    $query = sprintf("insert into `%s%s` set `uid`=%d, `openid`='%s', `dateline`='%s', `expire` = '%s', `dateline_start` = '%s', `dateline_expire`='%s', `point`=%d, `limit`=%d, `action_name`='%s', `ticket`='%s', `detail`='%s', `raw`='%s'", 
        $C['db']['pfix'],
        'qrcodes', 
        $user_qr['id'], 
        $DB->real_escape_string($user_qr['openid']), 
        $DB->real_escape_string(DATETIME), 
        $DB->real_escape_string(date('Y-m-d H:i:s', $expire)), 
        $DB->real_escape_string(date('Y-m-d H:i:s', $dateline_start)), 
        $DB->real_escape_string(date('Y-m-d H:i:s', $dateline_expire)), 
        $config['point'], 
        $config['limit'], 
        $DB->real_escape_string('QR_SCENE'), 
        $DB->real_escape_string($rs['ticket']), 
        $DB->real_escape_string($detail), 
        $DB->real_escape_string(json_encode($rs))
    );
    $source = $DB->query($query);
    $DB->errno > 0 && die(json_encode(array(
        'status'    => 0, 
        'code'      => $DB->errno, 
        'error'     => $DB->error
    )));
    
    // 给出ticket
    $qr =  $rs['ticket'];
// }

require_once V.'header.php';
require_once V.'qrcode.php';
require_once V.'footer.php';