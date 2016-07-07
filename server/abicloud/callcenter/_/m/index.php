<?php
(INAPP !== true) && die('Error !');

$query = sprintf("select `xktvid` from `%s%s` where `type` = 2;", 
    $C['db']['pfix'], 
    'xktv'
);
$source = $DB->query($query);
$DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
$ignore = array('XKTV00000', 'XKTV01158');
while ($row = $source->fetch_assoc()) {
    $ignore[] = $row['xktvid'];
}
$ignore = implode("', '", $ignore);
$ignore = "('".$ignore."')";

$expire_baseline = TIME - 60 * 15;
$query = sprintf("update `%s%s` set `status`=14 where `starttime` > %d and `status`=1 and `ktvid` not in %s;", 
    $C['db']['pfix'], 
    'order', 
    $expire_baseline, 
    $ignore
);
// exit($query);

$DB->query($query);
$DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);

$_online_users = memory('enable') ? memory('get', 'online_users') : array();
if($_online_users) {
    $_online_users = array_unique($_online_users);
    $_online_users = array_values($_online_users);
} else {
    $_online_users = array();
}

if(ROLE == 'admin') {
    $a      = (isset($_GET['a'])    && in_array(trim($_GET['a']), array('dashbord', 'history', 'today', 'check_todo'))) ? trim($_GET['a']) : 'dashbord';
    $type   = (isset($_GET['type']) && in_array(trim($_GET['type']), array('all', 'todo', 'done', 'confirmed', 'rejected', 'canceled', 'expired'))) ? trim($_GET['type']) : 'all';
    $id     = (isset($_GET['id'])   && intval($_GET['id']) > 0) ? intval($_GET['id'])   : null;
    require_once M.$m.'_admin.php';
} elseif(ROLE == 'operator') {
    $a      = (isset($_GET['a'])    && in_array(trim($_GET['a']), array('history', 'today', 'check_todo', 'sjb'))) ? trim($_GET['a']) : 'today';
    $type   = (isset($_GET['type']) && in_array(trim($_GET['type']), array('all', 'todo', 'done', 'confirmed', 'rejected', 'canceled', 'expired'))) ? trim($_GET['type']) : 'todo';

    $query = sprintf("select count(1) as `count` from `%s%s` where `role`='operator'", 
        $C['db']['pfix'], 
        'cc_users'
    );
    $source = $DB->query($query);
    $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
    while ($row = $source->fetch_assoc()) {
        $count = intval($row['count']);
    }
    define('USERS', $count);
    require_once M.$m.'_operator.php';
}