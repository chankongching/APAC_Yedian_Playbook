<?php
error_reporting(0);
(!defined('INAPP') || INAPP !== true) && die('Access denied.');

/*
define('WECHAT_APPID',      'wxb86cc9c3a0c920b0');
define('WECHAT_APPSECRET',  '64accf3576f13a401da2cc159e29b3f6');
*/
define('WECHAT_APPID',      'wx90f8e48d4b4f5d8d');
define('WECHAT_APPSECRET',  'f8a6976a35feec19edf899daefd4d59a');

$C['db']['host']            = '127.0.0.1';
$C['db']['user']            = 'letsktv';
$C['db']['pswd']            = 'OBjhe7UF3IsMIwPK';
$C['db']['char']            = 'utf8';
$C['db']['pcon']            = 0;
$C['db']['name']            = 'letsktv_games';
$C['db']['pfix']            = 'GUESS_SONG_';

$C['mem']['host']           = '127.0.0.1';
$C['mem']['port']           = 11211;
$C['mem']['pfix']           = 'GUESS_SONG_';
$C['mem']['pcon']           = false;
