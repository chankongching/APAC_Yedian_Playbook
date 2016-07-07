<?php
(INAPP !== true) && die('Error !');
if(METHOD == 'GET') {
    require_once V.$m.'.php';
}
$username = (isset($_POST['username']) && !empty(trim($_POST['username']))) ? trim($_POST['username']) : '';
$password = (isset($_POST['password']) && !empty(trim($_POST['password']))) ? trim($_POST['password']) : '';

if($username == '') {
    $error = '请填写用户名。';
    require_once V.$m.'.php';
    exit();
}
if($password == '') {
    $error = '请填写密码。';
    require_once V.$m.'.php';
    exit();
}

$query = sprintf("select * from `%s%s` where `username`='%s' limit 1;", 
    $C['db']['pfix'], 
    'admin_users', 
    $DB->real_escape_string($username)
);
$source = $DB->query($query);
$DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
if ($source->num_rows < 1) {
    $error = '没有该用户。';
    require_once V.$m.'.php';
    exit();
}
while ($row = $source->fetch_assoc()) {
    $userinfo =  $row;
}
if(!password_verify($password, $userinfo['password'])) {
    $error = '密码不正确。';
    require_once V.$m.'.php';
}
$_SESSION['spr_admin_uid'] = $userinfo['id'];
$_SESSION['spr_admin_name'] = $userinfo['username'];
$_SESSION['spr_admin_role'] = $userinfo['role'];

header('Location: '.URL);
exit;