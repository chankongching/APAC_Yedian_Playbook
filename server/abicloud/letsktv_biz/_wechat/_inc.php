<?php
(INAPP !== true) && die('Error !');

date_default_timezone_set('Asia/Shanghai');

header('Content-Type: application/json');

/*
define('WECHAT_APPID', 'wx1a8fbf2b1083d924');
define('WECHAT_APPSECRET', 'de9e90bc2b77719a7bf42df108b8a090');
*/
define('WECHAT_APPID', 'wxc5fd6e0da524eddd');
define('WECHAT_APPSECRET', '547525a7637054d2681b19836bb2beeb');

define('TIME', $_SERVER['REQUEST_TIME']);
define('METHOD', $_SERVER['REQUEST_METHOD']);
define('URL', curPageURL(true));
define('DATETIME', date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']));
define('ADDRESS', sprintf("%u", ip2long(getip())));

$C['db']['host'] = '127.0.0.1';
$C['db']['user'] = 'letsktv_biz';
$C['db']['pswd'] = 'OBjhe7UF3IsMIwPK';
$C['db']['char'] = 'utf8';
$C['db']['pcon'] = 0;
$C['db']['name'] = 'letsktv_biz_wechat';
$C['db']['pfix'] = 'letsktv_biz_';


$C['mem']['host'] = '127.0.0.1';
$C['mem']['port'] = 11211;
$C['mem']['pfix'] = 'letsktv_biz_wechat_';
$C['mem']['pcon'] = false;