<?php
define('INAPP', true);
date_default_timezone_set('Asia/Shanghai');

$WXDBCONFIG = array('db_host'=> '127.0.0.1', 'db_user' => 'letsktv', 'db_pswd' => 'OBjhe7UF3IsMIwPK', 'db_char' => 'utf8', 'db_name' => 'letsktv_wechat', 'db_tpre' => 'letsktv_', 'db_port' => 3306);
$WX = new mysqli($WXDBCONFIG['db_host'], $WXDBCONFIG['db_user'], $WXDBCONFIG['db_pswd'], $WXDBCONFIG['db_name'], $WXDBCONFIG['db_port']);
$WX->connect_errno && exit('DB Connection Error.');
$WX->query("SET character_set_connection=" . $WXDBCONFIG['db_char'] . ", character_set_results=" . $WXDBCONFIG['db_char'] . ", character_set_client=binary");


$PGDBCONFIG = array('db_host'=> '127.0.0.1', 'db_user' => 'letsktv_biz', 'db_pswd' => 'OBjhe7UF3IsMIwPK', 'db_char' => 'utf8', 'db_name' => 'letsktv_biz_promogirls', 'db_tpre' => 'promogirls_', 'db_port' => 3306);
$PG = new mysqli($PGDBCONFIG['db_host'], $PGDBCONFIG['db_user'], $PGDBCONFIG['db_pswd'], $PGDBCONFIG['db_name'], $PGDBCONFIG['db_port']);
$PG->connect_errno && exit('DB Connection Error.');
$PG->query("SET character_set_connection=" . $PGDBCONFIG['db_char'] . ", character_set_results=" . $PGDBCONFIG['db_char'] . ", character_set_client=binary");

$openid     = isset($_GET['openid']) && !empty(trim($_GET['openid'])) ? trim($_GET['openid']) : exit('openid');
$order      = isset($_GET['order']) && !empty(trim($_GET['order'])) ? trim($_GET['order']) : exit('order');
$confirm    = isset($_GET['confirm']) && !empty(trim($_GET['confirm'])) ? trim($_GET['confirm']) : exit('confirm');
$point = 250;

if(isset($openid) && !empty(trim($openid))) {
    $query_from = sprintf("select `dateline`, `ticket` from `letsktv_logs_subscribe` where `FromUserName` = '%s' limit 1;", 
        $WX->real_escape_string($openid)
    );
    $source_from = $WX->query($query_from);
    if($source_from->num_rows > 0) {
        while($row_from = $source_from->fetch_assoc()) {
            $ticket     = $row_from['ticket'];
            $dateline   = $row_from['dateline'];
            if($dateline > '2016-05-16 00:00:00') {
                $query_spr = sprintf("select `openid` from `letsktv_qrcodes` where `ticket` = '%s' limit 1;", 
                    $WX->real_escape_string($ticket)
                );
                $source_spr = $WX->query($query_spr);
                if($source_spr->num_rows > 0) {
                    while($row_spr = $source_spr->fetch_assoc()) {
                        $spr = $row_spr['openid'];
//                         $spr = 'oNaFgwj4r9NY55DJDm18QSXkmzhM';
                        $query_log = sprintf("insert into `promogirls_customer_order_log` (`spr`, `customer`, `order`, `confirm`, `dateline`) values ('%s', '%s', '%s', '%s', '%s') ON DUPLICATE KEY UPDATE `dateline` = '%s';", 
                            $PG->real_escape_string($spr), 
                            $PG->real_escape_string($openid), 
                            $PG->real_escape_string(date('Y-m-d H:i:s', $order)), 
                            $PG->real_escape_string(date('Y-m-d H:i:s', $confirm)), 
                            $PG->real_escape_string(date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])), 
                            $PG->real_escape_string(date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']))
                        );
                        $PG->query($query_log);
                        if($PG->affected_rows === 1) {
                            $query_up = sprintf("update `promogirls_users` set `point__all` = `point__all` + %d where `openid` = '%s' limit 1;", 
                                $point, 
                                $PG->real_escape_string($spr)
                            );
                            $PG->query($query_up);
                            if(intval($PG->affected_rows) > 0) {
                                sendPointChangeMessage($spr, $point);
                            } else {
                                exit('no spr.');
                            }
                        } else {
                            exit('data exists.');
                        }
                    }
                } else {
                    exit('no ticket.');
                }
            } else {
                exit('too early.');
            }
        }
    } else {
        exit('openid not found.');
    }
}

function sendPointChangeMessage($openid, $point = 0, $title = '您的积分已发入账号', $remark = '谢谢您的支持，请再接再厉。') {
    $point = intval($point) >= 0 ? '+'.$point : '-'.$point;
    $dataM = array();
    $dataM['template_id'] = 'OPhROSyTgsbdUlghPLafPut_gj11rIvFBoGOouwBesg';
    $dataM['url'] = 'http://letsktv.chinacloudapp.cn/letsktv_biz/promo_girls/index.php?m=achievement';
    $dataM['topcolor'] = '#FF0000';
    $dataM['touser'] = $openid;
    $dataM['data'] = array(
        'first' => array(
            'value' => $title,
            'color' => '#000000',
        ),
        'keyword1' => array(
            'value' => $point,
            'color' => '#000000',
        ),
        'keyword2' => array(
            'value' => date("Y-m-d H:i:s"),
            'color' => '#000000',
        ),
        'remark' => array(
            'value' => $remark,
            'color' => '#000000',
        ),
    );
    $jsonstr = json_encode($dataM, true);
    $token_content = httpGet('http://letsktv.chinacloudapp.cn/letsktv_biz/_wechat/index.php?m=getToken');
    $token = json_decode($token_content, true);
    $post_url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $token['data'];
    return http_post($post_url, $jsonstr);
}
function http_post($url, $param, $post_file = false) {
    $oCurl = curl_init();
    if (stripos($url, "https://") !== FALSE) {
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
    }
    if (is_string($param) || $post_file) {
        $strPOST = $param;
    } else {
        $aPOST = array();
        foreach ($param as $key => $val) {
            $aPOST[] = $key . "=" . urlencode($val);
        }
        $strPOST = join("&", $aPOST);
    }
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($oCurl, CURLOPT_POST, true);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);
    if (intval($aStatus["http_code"]) == 200) {
        return $sContent;
    } else {
        return false;
    }
}
function httpGet($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
