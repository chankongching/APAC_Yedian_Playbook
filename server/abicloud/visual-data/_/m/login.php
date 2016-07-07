<?php
(INAPP !== true) && die('Error !');

if(METHOD == 'POST') {
    $user = array(
        'username' => $_POST['username'], 
        'password' => $_POST['password']
    );
/*
    print_r($user);
    print_r($C['user']);
*/
    $uid = array_search($user, $C['user'], true);
    if($uid > 0) {
        $_SESSION['visual_data_uid'] = $uid;
    }
    header('Location: ./');
} else {
    require_once V.$m.'.php';
}