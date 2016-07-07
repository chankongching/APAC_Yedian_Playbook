<?php
(INAPP !== true) && die('Error !');

if($_SESSION['callcenter_role'] == 'operator') {
    $_online_users = memory('enable') ? memory('get', 'online_users') : array();
    foreach($_online_users as $k=>$v) {
        if($v == $_SESSION['callcenter_uid']) {
            unset($_online_users[$k]);
        }
    }
    $_online_users = array_unique($_online_users);
    $_online_users = array_values($_online_users);
    if (memory('enable')) {
        memory('set', 'online_users', $_online_users, 3600);
    }
}

$_SESSION = array();
session_destroy();

header('Location: '.URL);
exit;