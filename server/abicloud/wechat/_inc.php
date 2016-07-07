<?php
(INAPP !== true) && die('Error !');

date_default_timezone_set('Asia/Shanghai');

header('Content-Type: application/json');

define('WECHAT_APPID', 'wx90f8e48d4b4f5d8d');
define('WECHAT_APPSECRET', 'f8a6976a35feec19edf899daefd4d59a');

define('TIME', $_SERVER['REQUEST_TIME']);
define('METHOD', $_SERVER['REQUEST_METHOD']);
define('URL', curPageURL(true));
define('DATETIME', date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']));
define('ADDRESS', sprintf("%u", ip2long(getip())));

$C['db']['host'] = '127.0.0.1';
$C['db']['user'] = 'letsktv';
$C['db']['pswd'] = 'OBjhe7UF3IsMIwPK';
$C['db']['char'] = 'utf8';
$C['db']['pcon'] = 0;
$C['db']['name'] = 'letsktv_wechat';
$C['db']['pfix'] = 'letsktv_';


$C['mem']['host'] = '127.0.0.1';
$C['mem']['port'] = 11211;
$C['mem']['pfix'] = 'letsktv_wechat_';
$C['mem']['pcon'] = false;