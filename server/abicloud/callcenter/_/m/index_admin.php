<?php
(INAPP !== true) && die('Error !');

$order_today = $order_today_done = $order_today_canceled = 0;
$orders = array();

$query = sprintf("select `id`, `username` from `%s%s` where `role`='operator' order by `id` asc;", 
    $C['db']['pfix'], 
    'cc_users'
);
$source = $DB->query($query);
$DB->errno > 0 && die('code: '.$DB->errno.', error: '.$DB->error);
while ($row = $source->fetch_assoc()) {
    $users[]                    = $row;
    $users_by_id[intval($row['id'])]    = $row['username'];
}

$count_mysql_param =  isset($id) ? 'and `o`.`cc_user` = '.$id : '';
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
    case 'dashbord':
        $count_all = $count_today = array();
        $query = sprintf("select count(1) as `count`, `cc_user` as `user`, `status` from `%s%s` where `time` > %d and `cc_status` > -1 group by `status`, `user`;", 
            $C['db']['pfix'], 
            'order', 
            strtotime(date('Y-m-d 00:00:00', TIME))
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die('code: '.$DB->errno.', error: '.$DB->error);
        while ($row = $source->fetch_assoc()) {
            $count_today[$row['user']]['all']           = isset($count_today[$row['user']]['all']) ? $count_today[$row['user']]['all'] : 0;
            $count_today[$row['user']]['all']          += intval($row['count']);
            $count_today[$row['user']][$row['status']]  = intval($row['count']);
            $count_today[$row['user']]['1']   = isset($count_today[$row['user']]['1'])   ? $count_today[$row['user']]['1']  : 0;
            $count_today[$row['user']]['3']   = isset($count_today[$row['user']]['3'])   ? $count_today[$row['user']]['3']  : 0;
            $count_today[$row['user']]['4']   = isset($count_today[$row['user']]['4'])   ? $count_today[$row['user']]['4']  : 0;
            $count_today[$row['user']]['7']   = isset($count_today[$row['user']]['7'])   ? $count_today[$row['user']]['7']  : 0;
            $count_today[$row['user']]['14']  = isset($count_today[$row['user']]['14'])  ? $count_today[$row['user']]['14'] : 0;
        }
        
        $query = sprintf("select count(1) as `count`, `cc_user` as `user`, `status` from `%s%s` where `cc_status` > -1 group by `status`, `user`;", 
            $C['db']['pfix'], 
            'order'
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die('code: '.$DB->errno.', error: '.$DB->error);
        while ($row = $source->fetch_assoc()) {
            $count_all[$row['user']]['all'] = isset($count_all[$row['user']]['all']) ? $count_all[$row['user']]['all'] : 0;
            $count_all[$row['user']]['all'] += intval($row['count']);
            $count_all[$row['user']][$row['status']] = intval($row['count']);
        }
    break;
    case 'today':
        $query = sprintf("select count(1) as `count`, `status` from `%s%s` as `o` where `o`.`time` > %d /* and `o`.`status` != 1 */ %s and `o`.`cc_status` > -1 group by `status`", 
            $C['db']['pfix'], 
            'order', 
            strtotime(date('Y-m-d 00:00:00', TIME)), 
            $count_mysql_param
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
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 1 and `o`.`time` > %d %s and `o`.`cc_status` > -1 order by `id` asc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $count_mysql_param
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']    = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'done':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 3 and `o`.`time` > %d %s and `o`.`cc_status` > -1 order by `id` desc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $count_mysql_param
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']    = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'confirmed':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 5 and `o`.`time` > %d %s and `o`.`cc_status` > -1 order by `id` desc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $count_mysql_param
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']    = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'rejected':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 4 and `o`.`time` > %d %s and `o`.`cc_status` > -1 order by `id` desc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $count_mysql_param
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']    = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'canceled':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 7 and `o`.`time` > %d %s and `o`.`cc_status` > -1 order by `id` desc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $count_mysql_param
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']    = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'expired':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 14 and `o`.`time` > %d %s and `o`.`cc_status` > -1 order by `id` desc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $count_mysql_param
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']    = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'all':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` in (1, 3, 4, 7, 14) and `o`.`time` > %d %s and `o`.`cc_status` > -1 order by `id` desc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    strtotime(date('Y-m-d 00:00:00', TIME)), 
                    $count_mysql_param
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']    = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
        }
    break;
    case 'history':
        $history = 1;
        $query = sprintf("select count(1) as `count`, `status` from `%s%s` as `o` where `o`.`cc_status` > -1 /* and `status` != 1 */ %s group by `status`", 
            $C['db']['pfix'], 
            'order', 
            $count_mysql_param
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
        $order_today_canceled    = isset($_count['7'])   ? $_count['7']  : 0;
        $order_today_expired    = isset($_count['14'])  ? $_count['14'] : 0;
        switch($type) {
            case 'todo':
                $orderby = 'asc';
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 1 and `o`.`cc_status` > -1 %s order by `id` asc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $count_mysql_param
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']    = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'done':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 3 and `o`.`cc_status` > -1 %s order by `id` desc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $count_mysql_param
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']    = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'confirmed':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 5 and `o`.`cc_status` > -1 %s order by `id` desc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $count_mysql_param
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']    = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'rejected':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 4 and `o`.`cc_status` > -1 %s order by `id` desc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $count_mysql_param
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']    = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'canceled':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 7 and `o`.`cc_status` > -1 %s order by `id` desc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $count_mysql_param
                );
                $source = $DB->query($query);
                $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
                if ($source->num_rows > 0) {
                    while ($row = $source->fetch_assoc()) {
                        $row['lasts']           = ($row['endtime'] - $row['starttime']) / 3600;
                        $row['time']            = $row['time'];
                        $row['starttime']       = date('Y-m-d H:i:s', $row['starttime']);
                        $row['endtime']         = date('Y-m-d H:i:s', $row['endtime']);
                        $row['update_time']    = $row['update_time'] ? $row['update_time'] : '-';
                        $orders[] = $row;
                    }
                }
            break;
            case 'expired':
                
                $query = sprintf("select 
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` = 14 and `o`.`cc_status` > -1 %s order by `id` desc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $count_mysql_param
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
                    `o`.`id`, `o`.`create_time` as `time`, `k`.`name`, `u`.`display_name`, `u`.`mobile`, `o`.`starttime`, `o`.`endtime`, `o`.`members`, `o`.`roomtype`, `o`.`status`, `o`.`cc_user`, `o`.`update_time`
                    from 
                    `%s%s` as `o`, 
                    `%s%s` as `u`, 
                    `%s%s` as `k` 
                    where 
                    `k`.`xktvid` = `o`.`ktvid` 
                    and 
                    `u`.`id` = `o`.`userid`
                    and 
                    `o`.`status` in (1, 3, 4, 7, 14) and `o`.`cc_status` > -1 %s order by `id` desc limit 500;", 
                    $C['db']['pfix'], 
                    'order', 
                    $C['db']['pfix'], 
                    'platform_user', 
                    $C['db']['pfix'], 
                    'xktv', 
                    $count_mysql_param
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