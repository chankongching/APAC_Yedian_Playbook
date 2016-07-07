<?php
(INAPP !== true) && die('Error !');

$order_today = $order_today_done = $order_today_cancled = 0;
$orders = array();

$query = sprintf("select `xktvid` from `%s%s` where `type` = 2;", 
    $C['db']['pfix'], 
    'xktv'
);
$source = $DB->query($query);
$DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
$ignore = array('XKTV00000');
while ($row = $source->fetch_assoc()) {
    $ignore[] = $row['xktvid'];
}
$ignore = implode("', '", $ignore);
$ignore = "('".$ignore."')";

switch($a) {
    case 'check_todo':
/*
        $query = sprintf("select count(1) from `%s%s` where `create_time` < %d and `status` = 1;", 
            $C['db']['pfix'], 
            'order', 
            TIME - 60*5
        );
        exit($query);
*/
    break;
    case 'sjb':
        $ajax = (isset($_GET['ajax']) && in_array(trim($_GET['ajax']), array('add_detail', 'get_detail'))) ? trim($_GET['ajax']) : false;
        if($ajax) {
            $order_id = intval($_GET['order_id']) > 0 ? intval($_GET['order_id']) : exit('0');
            switch($ajax) {
                case 'add_detail':
                    $query = sprintf("update `%s%s` set `cc_detail` = '%s' where `id` = %d limit 1;", 
                        $C['db']['pfix'], 
                        'order', 
                        $DB->real_escape_string($_POST['detail']), 
                        $order_id
                    );
                    $source = $DB->query($query);
                    if($DB->affected_rows == 1) {
                        exit(json_encode(array('status' => 1)));
                    } else {
                        exit(json_encode(array('status' => 0)));
                    }
                break;
                case 'get_detail':
                    $query = sprintf("select `o`.`cc_detail` as `detail`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `k`.`name`, concat(`k`.`pretelephone`, '-', `k`.`telephone`) as `phone` from `%s%s` as `o`, `%s%s` as `u`, `%s%s` as `k` where `k`.`xktvid` = `o`.`ktvid` and `u`.`id` = `o`.`userid` and `o`.`id` = %d limit 1;", 
                        $C['db']['pfix'], 
                        'order', 
                        $C['db']['pfix'], 
                        'platform_user', 
                        $C['db']['pfix'], 
                        'xktv', 
                        $order_id
                    );
//                     exit($query);
                    $source = $DB->query($query);
                    if($source->num_rows > 0) {
                        while ($row = $source->fetch_assoc()) {
                            $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                            $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                            $data = $row;
                            $data['status'] = !empty($row['detail']) ? true : false;
                        }
                        if($data) {
                            exit(json_encode(array('status' => 1, 'data' => $data)));
                        }
                    }
                break;
            }
            exit;
        } else {
            $orderby = 'asc';
            $query = sprintf("select 
                `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time`, `o`.`cc_detail` 
                from 
                `%s%s` as `o`, 
                `%s%s` as `u`, 
                `%s%s` as `k` 
                where 
                `k`.`xktvid` = `o`.`ktvid` 
                and 
                `u`.`id` = `o`.`userid`
                and
                 `o`.`ktvid` in %s 
                and 
                `o`.`time` < %d 
                and 
                `o`.`time` > %d
                and
                `o`.`status` = 1 order by `id` asc;", 
                $C['db']['pfix'], 
                'order', 
                $C['db']['pfix'], 
                'platform_user', 
                $C['db']['pfix'], 
                'xktv', 
                $ignore, 
                TIME - 60*5, 
                TIME - 60*60*24*2
            );
            $source = $DB->query($query);
            $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
            if ($source->num_rows > 0) {
                while ($row = $source->fetch_assoc()) {
                    $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                    $row['time']            = $row['time'];
                    $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                    $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                    $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                    $orders[] = $row;
                }
            }
        }
    break;
    case 'today':
        $query = sprintf("select count(1) as `count`, `status` from `%s%s` as `o` where `o`.`ktvid` not in %s and `o`.`time` > %d and `o`.`cc_user` = %d and `o`.`cc_status` > -1 group by `status`", 
            $C['db']['pfix'], 
            'order', 
            $ignore, 
            strtotime(date('Y-m-d 00:00:00', TIME)), 
            $_SESSION['callcenter_uid']
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
        while ($row = $source->fetch_assoc()) {
            $row['count'] = intval($row['count']);
            $order_today += $row['count'];
            $_count[$row['status']] = $row['count'];
        }
        $order_today_todo       = isset($_count['1'])   ? $_count['1']  : 0;
        $order_today_done       = isset($_count['3'])   ? $_count['3']  : 0;
        $order_today_rejected   = isset($_count['4'])   ? $_count['4']  : 0;
        $order_today_confirmed  = isset($_count['5'])   ? $_count['5']  : 0;
        $order_today_canceled   = isset($_count['7'])   ? $_count['7']  : 0;
        $order_today_expired    = isset($_count['14'])  ? $_count['14'] : 0;
        switch($type) {
            case 'todo':
                $orderby = 'asc';
                $query = sprintf("select count(1) as `count` from `%s%s` as `o` where `o`.`ktvid` not in %s and `o`.`status` = 1 and `o`.`time` > %d and `o`.`cc_user` = 0 and `o`.`cc_status` > -1;", 
                    $C['db']['pfix'], 
                    'order', 
                    $ignore, 
                    strtotime(date('Y-m-d 00:00:00', TIME))
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                while ($row = $source->fetch_assoc()) {
                    $count = intval($row['count']);
                }
                $_limit = 0;
                $_limit = ceil($count / USERS);
                $query = sprintf("update `%s%s` set `cc_user`=%d where `ktvid` not in %s and `status` = 1 and `time` > %d and `cc_user` = 0 and `cc_status` > -1 order by `id` asc limit %d;", 
                    $C['db']['pfix'], 
                    'order', 
                    $_SESSION['callcenter_uid'], 
                    $ignore, 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $_limit
                );
                $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and
                     `o`.`ktvid` not in %s 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 1 and `o`.`time` > %d and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` asc;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $ignore, 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
                if(isset($_GET['ajax'])) {
                    $html_array = array();
                    foreach($orders as $order) {
                        $html_array[] = array(
                            'id'    => $order['id'],
                            'time'    => $order['time'],
                            'past'    => TIME - strtotime($order['time']),
                            'name'    => $order['name'],
                            'mobile'    => $order['mobile'],
                            'display_name'    => $order['display_name'],
                            'starttime'    => $order['starttime'],
                            'lasts'    => $order['lasts'].' 小时',
                            'status'    => '<span class="label label '.$orderstatusstyle[$order['status']].'">'.$orderstatus[$order['status']].'</span>',
                            'link'    => '<button type="button" class="btn btn-sm btn-default"><a class="fa fa-search" href="http://letsktv.chinacloudapp.cn/callcenter/index.php?m=order&amp;id='.$order['id'].'"></a></button>',
                        );
                    }
                    exit(json_encode(
                        $html_array
                    ));
                }
            break;
            case 'done':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and
                    `o`.`ktvid` not in %s 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 3 and `o`.`time` > %d and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` desc;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $ignore, 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'confirmed':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and
                    `o`.`ktvid` not in %s 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 5 and `o`.`time` > %d and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` desc;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $ignore, 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'rejected':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and
                    `o`.`ktvid` not in %s 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 4 and `o`.`time` > %d and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` desc;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $ignore, 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'canceled':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and
                    `o`.`ktvid` not in %s 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 7 and `o`.`time` > %d and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` desc;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $ignore, 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'expired':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and
                    `o`.`ktvid` not in %s 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 14 and `o`.`time` > %d and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` desc;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $ignore, 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'all':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and
                    `o`.`ktvid` not in %s 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` in (1, 3, 4, 7, 14) and `o`.`time` > %d and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` desc limit 1000;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $ignore, 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
        }
    break;
    case 'history':
        $history = 1;
        $query = sprintf("select count(1) as `count`, `status` from `%s%s` as `o` where `o`.`ktvid` not in %s and `o`.`cc_user` = %d and `o`.`cc_status` > -1 group by `status`", 
            $C['db']['pfix'], 
            'order', 
            $ignore, 
            $_SESSION['callcenter_uid']
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
        while ($row = $source->fetch_assoc()) {
            $row['count'] = intval($row['count']);
            $order_today += $row['count'];
            $_count[$row['status']] = $row['count'];
        }
        $order_today_todo       = isset($_count['1'])   ? $_count['1']  : 0;
        $order_today_done       = isset($_count['3'])   ? $_count['3']  : 0;
        $order_today_rejected   = isset($_count['4'])   ? $_count['4']  : 0;
        $order_today_confirmed  = isset($_count['5'])   ? $_count['5']  : 0;
        $order_today_canceled   = isset($_count['7'])   ? $_count['7']  : 0;
        $order_today_expired    = isset($_count['14'])  ? $_count['14'] : 0;
        switch($type) {
            case 'todo':
                $orderby = 'asc';
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 1 and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` asc;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'done':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 3 and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` desc;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'done':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 5 and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` desc;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'rejected':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 4 and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` desc;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'canceled':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 7 and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` desc;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'expired':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 14 and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` desc;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'all':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `u`.`mobile`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`update_time` 
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` in (1, 3, 4, 7, 14) and `o`.`cc_status` > -1 and `o`.`cc_user`=%d order by `id` desc limit 1000;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $_SESSION['callcenter_uid']
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']     = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
        }
    break;
}

require_once V.'orders.php';