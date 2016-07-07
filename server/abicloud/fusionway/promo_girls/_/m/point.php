<?php
(INAPP !== true) && die('Error !');

// 权限检查
$query = sprintf("select `id`, `uid`, `openid`, `nickname`, `status` from `%s%s` where `openid`='%s' limit 1;", 
    $C['db']['pfix'],
    'users',
    $DB->real_escape_string($_SESSION['fusionway_promo_girls_openid'])
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
        die('未绑定用户无法使用积分商城。');
    }
    $user_qr = $row;
}

$a = (isset($_GET['a']) && in_array(trim($_GET['a']), array('submit', 'confirm', 'detail', 'list'))) ? trim($_GET['a']) : 'list';

switch($a) {
    case 'list':
        require_once V.'header.php';
        require_once V.'point.php';
        require_once V.'footer.php';
    break;
    case 'detail':
        require_once V.'header.php';
        require_once V.'point.php';
        require_once V.'footer.php';
    break;
    case 'confirm':
        require_once V.'header.php';
        require_once V.'point.php';
        require_once V.'footer.php';
    break;
    case 'submit':
        require_once V.'header.php';
        require_once V.'point.php';
        require_once V.'footer.php';
    break;
}