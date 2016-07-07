<?php
(INAPP !== true) && die('Error !');

// 权限检查
$query = sprintf("select `id`, `openid`, `nickname`, `headimgurl`, `status`, `phone`, `captcha`, `captcha_sendtime` from `%s%s` where `openid` = '%s' limit 1;",
    $C['db']['pfix'],
    'users',
    $DB->real_escape_string($_SESSION['fusionway_promo_girls_openid'])
);
$source = $DB->query($query);
$DB->errno > 0 && die('code: '.$DB->errno.', error: '.$DB->error);
if ($source->num_rows < 1) {
    die('未知错误，该用户不存在。');
}
while ($row = $source->fetch_assoc()) {
    $row['status'] = intval($row['status']);
    $user = $row;
}

// 未绑定
if($user['status'] == 0) {
    require_once M.'checkuser.php';
    exit();
}

require_once V.'header.php';
require_once V.'index.php';
require_once V.'footer.php';

exit();
