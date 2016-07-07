<?php
(INAPP !== true) && die('Error !');

date_default_timezone_set('Asia/Shanghai');

header('Content-Type: application/json');

define('WECHAT_APPID', 'wxbf643add612855f6');
define('WECHAT_APPSECRET', '276e598506832f0a533360841068d7bf');

define('TIME', $_SERVER['REQUEST_TIME']);
define('METHOD', $_SERVER['REQUEST_METHOD']);
define('URL', curPageURL(true));
define('DATETIME', date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']));
define('ADDRESS', sprintf("%u", ip2long(getip())));

$C['db']['host'] = '127.0.0.1';
$C['db']['user'] = 'fusionway';
$C['db']['pswd'] = 'OBjhe7UF3IsMIwPK';
$C['db']['char'] = 'utf8';
$C['db']['pcon'] = 0;
$C['db']['name'] = 'fusionway_wechat';
$C['db']['pfix'] = 'fusionway_';


$C['mem']['host'] = '127.0.0.1';
$C['mem']['port'] = 11211;
$C['mem']['pfix'] = 'fusionway_wechat_';
$C['mem']['pcon'] = false;