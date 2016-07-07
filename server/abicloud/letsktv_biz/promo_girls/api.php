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

$ticket = isset($_GET['ticket']) && !empty(trim($_GET['ticket'])) ? trim($_GET['ticket']) : exit();
if(isset($_GET['ticket']) && !empty(trim($_GET['ticket']))) {
    $ticket = trim($_GET['ticket']);
    $query = sprintf("select `id` as `tid`, `uid`, `openid`, `detail`, `dateline_expire` from `promogirls_qrcodes` where `ticket` = '%s' limit 1;", 
        $PG->real_escape_string($ticket)
    );
    $source = $PG->query($query);
    if($source->num_rows > 0) {
        while($row = $source->fetch_assoc()) {
            $query_sl = sprintf("insert into `promogirls_qrcodes_scan_log` (`uid`, `tid`, `count`, `dateline_expire`) values (%d, %d, 1, '%s') on duplicate key update `count` = `count` + 1;", 
                $row['uid'], 
                strtotime($row['dateline_expire']), 
                $row['dateline_expire']
            );
            $PG->query($query_sl);
            $query_slc = sprintf("select `count` from `promogirls_qrcodes_scan_log` where `uid` = %d and `dateline_expire` = '%s';", 
                $row['uid'], 
                $row['dateline_expire']
            );
            $source_slc = $PG->query($query_slc);
            if($source_slc->num_rows > 0) {
                while($row_slc = $source_slc->fetch_assoc()) {
                    $count = $row_slc['count'];
                    require_once '_/_inc_point.php';
                    $config = getConfig($row['dateline_expire']);
                    if($count <= $config['limit']) {
                        $point = $config['point'];
/*
                    } elseif($count > $config['limit'] && $count <= 100) {
                        $point = 100;
*/
                    } else {
                        $point = 0;
                    }
                    $query_up = sprintf("update `promogirls_users` set `point__all` = `point__all` + %d where `id` = %d limit 1;", 
                        $point, 
                        $row['uid']
                    );
                    $PG->query($query_up);
                    if($point > 0) {
                        sendPointChangeMessage($row['openid'], $point);
                    }
                }
            }
        }
    }
}

/*
require_once '_/_inc_point.php';
$query = sprintf("select * from `promogirls_qrcodes_scan_log` where `uid` = 77;");
$source = $PG->query($query);
if($source->num_rows > 0) {
    $point = 0;
    while($row = $source->fetch_assoc()) {
        $config = getConfig($row['dateline_expire']);
        if($row['dateline_expire'] < '2015-12-14 08:00:00') {
            $point__all = min($row['count'], $config['limit']) * $config['point'];
        } else {
            $point__all = (min($row['count'], $config['limit']) * $config['point']) + (max($row['count'] - $config['limit'], 0) * 100);
        }
        $point += $point__all;
    }
    echo $point;
}
*/

/*
$query = sprintf("select * from `promogirls_orders`");
$source = $PG->query($query);
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        $query = sprintf("update `promogirls_users` set `point__used` = `point__used` + %d where `id` = %d;", 
            $row['point'], 
            $row['uid']
        );
        $PG->query($query);
    }
    echo 'done.';
}
*/

/*
require_once '_/_inc_point.php';
$query = sprintf("select * from `promogirls_qrcodes_scan_log`");
$source = $PG->query($query);
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        $config = getConfig($row['dateline_expire']);
        if($row['dateline_expire'] < '2015-12-14 08:00:00') {
            $point__all = min($row['count'], $config['limit']) * $config['point'];
        } else {
            $point__all = (min($row['count'], $config['limit']) * $config['point']) + (max($row['count'] - $config['limit'], 0) * 100);
        }
        $row['point'] = $point__all;
        $query = sprintf("update `promogirls_users` set `point__all` = `point__all` + %d where `id` = %d;", 
            $point__all, 
            $row['uid']
        );
        $PG->query($query);
    }
    echo 'done.';
}
*/

/*
$query = sprintf("select count(1) as `count`, `openid`, `ticket` from `letsktv_promogirls_scan_log` group by `ticket`;");
$source = $WX->query($query);
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        $query_t = sprintf("select * from `promogirls_qrcodes` where `openid` = '%s' and `ticket` = '%s' limit 1;", 
            $row['openid'], 
            $row['ticket']
        );
        $source_t = $PG->query($query_t);
        if($source_t->num_rows > 0) {
            while($row_t = $source_t->fetch_assoc()) {
                $query_sl = sprintf("insert into `promogirls_qrcodes_scan_log` (`uid`, `tid`, `count`) values (%d, %d, %d) on duplicate key update `count` = `count` + 1;", 
                    $row_t['uid'], 
                    $row_t['id'], 
                    $row['count']
                );
                $PG->query($query_sl);
            }
        }
    }
    echo 'done.';
}
*/

/*
$query = sprintf("select `q`.`id`, `u`.`name`, `q`.`detail` from `promogirls_users` as `u`,  `promogirls_qrcodes` as `q` where `q`.`uid` = `u`.`id`;");
$source = $PG->query($query);
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        $name = $row['name'];
        $detail = explode('|', $row['detail']);
        $detail = $detail[1];
        $detail_new = 'KTV促销员:'.$name.'|'.$detail;
        $query_u = sprintf("update `promogirls_qrcodes` set `detail_new` = '%s' where `id` = %d limit 1;", 
            $PG->real_escape_string($detail_new), 
            $row['id']
        );
        $PG->query($query_u);
    }
    echo 'done.';
}
*/

/*
$query = sprintf("update `promogirls_qrcodes` as `q`, `promogirls_qrcodes_scan_log` as `l` set `l`.`dateline_expire` = `q`.`dateline_expire` where `q`.`id` = `l`.`tid`;");
$PG->query($query);
*/


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
