<?php

define('INAPP', true);
/*
if(date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) > '2016-01-31 01:10:00' && date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) <= '2016-01-31 14:30:00') {
    header('Content-Type: text/html; charset=utf-8');
    echo "<h1>系统在以下时间内维护，敬请谅解：<br />2016-01-30 01:10:00 至 2016-01-31 14:30:00</h1>";
    exit;
}
*/
require_once './_/_inc.php';