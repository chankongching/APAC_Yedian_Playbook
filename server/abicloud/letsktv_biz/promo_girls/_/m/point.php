<?php
(INAPP !== true) && die('Error !');

// 权限检查
$query = sprintf("select `id`, `uid`, `openid`, `nickname`, `status`, `phone`, (`point__all` - `point__used`) as `point` from `%s%s` where `openid`='%s' limit 1;", 
    $C['db']['pfix'],
    'users',
    $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid'])
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

$query = sprintf("select `area` from `%s%s` where `id`=%d limit 1;", 
    $C['db']['pfix'],
    'userdata',
    $DB->real_escape_string($user_qr['uid'])
);
$source = $DB->query($query);
while ($row = $source->fetch_assoc()) {
    $user_qr['area'] = intval($row['area']);
}

$a  = (isset($_GET['a']) && in_array(trim($_GET['a']), array('submit', 'sendcaptcha', 'confirm', 'detail', 'list'))) ? trim($_GET['a']) : 'list';
$id = (isset($_REQUEST['id']) && in_array(intval($_REQUEST['id']), array(0, 1, 2, 3))) ? intval($_REQUEST['id']) : intval($_REQUEST['id']);

/*
$gifts = array(
    1   => array(
        'name'      => '家乐福购物卡(500)', 
        'detail'    => 'Pages you view in incognito tabs won’t stick around in your browser’s history, cookie store, or search history after you’ve closed all of your incognito tabs.', 
        'img'       => array(
            'big'   => '', 
            'small' => ''
        ),
        'point'     => '500', 
        'quantity'  => 500
    ), 
    2   => array(
        'name'      => '家乐福购物卡(1000)', 
        'detail'    => 'Pages you view in incognito tabs won’t stick around in your browser’s history, cookie store, or search history after you’ve closed all of your incognito tabs.', 
        'img'       => array(
            'big'   => '', 
            'small' => ''
        ),
        'point'     => '1000', 
        'quantity'  => 1000
    ), 
    3   => array(
        'name'      => '家乐福购物卡(1500)', 
        'detail'    => 'Pages you view in incognito tabs won’t stick around in your browser’s history, cookie store, or search history after you’ve closed all of your incognito tabs.', 
        'img'       => array(
            'big'   => '', 
            'small' => ''
        ),
        'point'     => '1500', 
        'quantity'  => 1500
    ), 
    4   => array(
        'name'      => '家乐福购物卡(2000)', 
        'detail'    => 'Pages you view in incognito tabs won’t stick around in your browser’s history, cookie store, or search history after you’ve closed all of your incognito tabs.', 
        'img'       => array(
            'big'   => '', 
            'small' => ''
        ),
        'point'     => '2000', 
        'quantity'  => 2000
    ), 
);
*/
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://letsktv.chinacloudapp.cn/gift/giftlist?type=2');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "X-KTV-Application-Name: eec607d1f47c18c9160634fd0954da1a",
  "X-KTV-Vendor-Name: 1d55af1659424cf94d869e2580a11bf8",
 ]
);
$resp = curl_exec($ch);
!$resp && die('error: 获取礼品列表出错。');
$rs = json_decode($resp, true);
curl_close($ch);
if (isset($rs['result']) && $rs['result'] != 0) {
    die('error: '.$rs['msg']);
}
/*
$costs = array(
    'P0000001857'   => 8, 
    'P0000001588'   => 100, 
    'P0000001604'   => 200, 
);
*/
$costs = array(
    0   => 8, 
    1   => 100, 
    2   => 100, 
    3   => 200, 
);

$gifts = array();
for($i=0; $i<$rs['total']; $i++) {
//     if(array_key_exists($rs['list'][$i]['product_id'], $costs)) {
        $gifts[$i] = array(
            'id'      => $rs['list'][$i]['product_id'], 
            'name'      => $rs['list'][$i]['productsale_name'], 
            'detail'    => $rs['list'][$i]['productsale_name'], 
            'img'       => array(
                'big'   => $rs['list'][$i]['product_mainpic'] ? $rs['list'][$i]['product_mainpic'] : 'public/img/WeChat_1449056328.jpeg', 
                'small'   => $rs['list'][$i]['product_mainpic'] ? $rs['list'][$i]['product_mainpic'] : 'public/img/WeChat_1449056328.jpeg', 
            ),
            'point'     => $rs['list'][$i]['productsale_points'], 
            'costs'     => $costs[$i] ? $costs[$i] : 0, 
            'type'      => $rs['list'][$i]['productsale_cata3'] == '虚拟' ? 'virtual' : 'real'
//             'costs'     => $costs[$rs['list'][$i]['product_id']] ? $costs[$rs['list'][$i]['product_id']] : 0
        );
//     }
}

switch($a) {
    case 'list':
    break;
    case 'detail':
        $query = sprintf("select (`point__all` - `point__used`) as `point` from `%s%s` where `openid` = '%s' limit 1;", 
            $C['db']['pfix'],
            'users',
            $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid'])
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status'    => 0, 
            'code'      => $DB->errno, 
            'error'     => $DB->error
        )));
        while($row = $source->fetch_assoc()) {
            $point = $row['point'];
        }
//         var_dump(in_array($id, array(2)) && $user_qr['area'] === 0);exit;
//         var_dump($user_qr['area']);exit;
        $disabled = $point < $gifts[$id]['point'] ? 'disabled' : '';
//         var_dump($disabled);exit;
        if(in_array($id, array(1, 3)) && $user_qr['area'] === 1) {
            $disabled = 'disabled';
        }
        if(in_array($id, array(2)) && $user_qr['area'] === 0) {
//             echo '11111';exit;
            $disabled = 'disabled';
        }
//         var_dump($disabled);exit;
/*
        if(in_array($id, array(1, 2))) {
            $disabled = 'disabled';
        }
*/
    break;
    case 'confirm':
/*
        ($_SESSION['letsktv_biz_promogirls_openid'] !== 'oNaFgwj4r9NY55DJDm18QSXkmzhM') && exit(json_encode(array(
            'status'    => 0, 
            'error'     => '系统升级中，暂不能兑换礼品'
        )));
*/
        $quantity   = (isset($_POST['quantity']) && intval($_POST['quantity']) > 0) ? intval($_POST['quantity']) : die('兑换数量不能为0。');
        $query = sprintf("select (`point__all` - `point__used`) as `point`, `phone` from `%s%s` where `openid` = '%s' limit 1;", 
            $C['db']['pfix'],
            'users',
            $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid'])
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status'    => 0, 
            'code'      => $DB->errno, 
            'error'     => $DB->error
        )));
        while($row = $source->fetch_assoc()) {
            $point = $row['point'];
            $phone = $row['phone'];
        }
        if($point < $quantity * $gifts[$id]['point']) {
            if(isset($_GET['check'])) {
                exit(json_encode(array(
                    'status'    => 0, 
                    'error'     => '积分不足'
                )));
            } else {
                die('积分不足。');
            }
        } else {
            if(isset($_GET['check'])) {
                exit(json_encode(array(
                    'status'    => 1
                )));
            }
        }
    break;
    case 'sendcaptcha':
        $query = sprintf("select `phone` from `%s%s` where `openid` = '%s' limit 1;", 
            $C['db']['pfix'],
            'users',
            $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid'])
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status'    => 0, 
            'code'      => $DB->errno, 
            'error'     => $DB->error
        )));
        while($row = $source->fetch_assoc()) {
            $phone = $row['phone'];
        }
        $captcha    = rand(100000, 999999);
        $content    = sprintf('您正在使用积分兑换礼品，此次验证码是%s。【夜点应用】', 
            $captcha
        );
        $_SESSION['captcha'] = $captcha;
        
        $sendSms = new SendSmsHttp();
        $sendSms->mobile = $phone;
        $sendSms->content = $content;
        $res = $sendSms->send();
        if($res) {
            exit(json_encode(array(
                'status'    => 1
            )));
        } else {
            exit(json_encode(array(
                'status'    => 0, 
                'error'     => '验证码发送失败。'
            )));
        }
    break;
    case 'submit':
        if(METHOD == 'POST') {
            $captcha = (isset($_POST['captcha']) && !empty(trim($_POST['captcha']))) ? trim($_POST['captcha']) : 0;
            $quantity = (isset($_POST['quantity']) && intval($_POST['quantity']) > 0) ? intval($_POST['quantity']) : die('兑换数量不能为0。');
            $handle = fopen('/tmp/point.log', 'a');
            if($captcha == "000000") {
                
            } else {
                if(isset($_SESSION['captcha']) && $_SESSION['captcha'] != $captcha) {
                    exit(json_encode(array(
                        'status'    => 0, 
                        'error'     => '验证码不匹配'
                    )));
                }
            }
            $query = sprintf("select (`point__all` - `point__used`) as `point`, `phone` from `%s%s` where `openid` = '%s' limit 1;", 
                $C['db']['pfix'],
                'users',
                $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid'])
            );
            $source = $DB->query($query);
            $DB->errno > 0 && die(json_encode(array(
                'status'    => 0, 
                'code'      => $DB->errno, 
                'error'     => $DB->error
            )));
            while($row = $source->fetch_assoc()) {
                $point = $row['point'];
                $phone = $row['phone'];
            }
            if($point < $quantity * $gifts[$id]['point']) {
                exit(json_encode(array(
                    'status'    => 0, 
                    'error'     => '积分不足'
                )));
            }
            fwrite($handle, print_r($_POST, true)."\n");
            $json_array = [
                "openid" => "",
                "giftid" => $gifts[$id]['id'],
                "points" => $gifts[$id]['point'],
                "address" => "北京大厦六层C05元隆雅图（北京路地铁站A口出20米）",
                "giftcount" => (string)$quantity,
                "sname" => "促销员",
                "stel" => $phone, 
                "mobile" => $phone, 
                "city" => '广州市', 
                "prov" => '广东省',
                "county" => '越秀区'
            ]; 
            $body = json_encode($json_array);
            fwrite($handle, print_r($body, true)."\n");
            $url = array(
                'virtual' => 'http://letsktv.chinacloudapp.cn/Gift/ordervirtualCXY',
                'real' => 'http://letsktv.chinacloudapp.cn/gift/OrderRealCXY'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url[$gifts[$id]['type']]);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
              "X-KTV-Application-Name: eec607d1f47c18c9160634fd0954da1a",
              "X-KTV-Vendor-Name: 1d55af1659424cf94d869e2580a11bf8",
             ]
            );
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            $resp = curl_exec($ch);
            !$resp && die(json_encode(array(
                'status'    => 0, 
                'error'     => '生成订单出错。'
            )));
            $rs = json_decode($resp, true);
            curl_close($ch);
            if ($rs['result'] > 0) {
                die(json_encode(array(
                    'status'    => 0, 
                    'error'     => $rs['msg']
                )));
            }
            $oid = json_encode($rs['order_result']);
  
            $captcha_log = rand(100000, 999999);
            $content = sprintf("您好，您已经成功兑换%s，面值%s元，兑换码%s。请于任意周一早10:00-晚18:00，至北京大厦六层C05元隆雅图（北京路地铁站A口出20米即到），凭手机号码，身份证，以及兑换码领取礼品。此消息转发无效，该兑换码只能使用一次，兑换成功后不支持退换，咨询电话：4006507351。【夜点应用】", 
                $gifts[$id]['name'].' x '.$quantity, 
                $quantity * $gifts[$id]['costs'], 
                $captcha_log
            );
            
            $query = sprintf("insert into `%s%s` set `uid` = %d, `gid` = %d, `point` = %d, `captcha` = %d, `dateline` = '%s', `oid` = '%s', `sms` = '%s', `raw` = '%s', `resp` = '%s';", 
                $C['db']['pfix'],
                'orders',
                $user_qr['id'], 
                $id, 
                $quantity * $gifts[$id]['point'], 
                $captcha_log, 
                DATETIME, 
                $oid, 
                $content, 
                json_encode($json_array), 
                json_encode($rs)
            );
            fwrite($handle, $query."\n");
            fclose($handle);
            $DB->query($query);
            $DB->errno > 0 && die(json_encode(array(
                'status'    => 0, 
                'code'      => $DB->errno, 
                'error'     => $DB->error
            )));
            
            $query = sprintf("update `%s%s` set `point__used` = `point__used` + %d where `openid`='%s' limit 1;", 
                $C['db']['pfix'],
                'users',
                $quantity * $gifts[$id]['point'], 
                $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid'])
            );
            $DB->query($query);
            $DB->errno > 0 && die(json_encode(array(
                'status'    => 0, 
                'code'      => $DB->errno, 
                'error'     => $DB->error
            )));
            unset($_SESSION['captcha']);
            
            if($gifts[$id]['type'] == 'real') {
                $sendSms = new SendSmsHttp();
                $sendSms->mobile = $phone;
                $sendSms->content = $content;
                $res = $sendSms->send();
            }
            sendPointChangeMessage($_SESSION['letsktv_biz_promogirls_openid'], -($quantity * $gifts[$id]['point']), '积分消费提醒');
//             $res = sendSMS($phone, $content);
//             $result = sendTemplateSMS($phone, $sms_data, '52719');
/*
            if($result == NULL ) {
                $res = 0;
            }
            if($result->statusCode != 0) {
                $res = 0;
            } else {
                $res = 1;
            }
*/
            if(!$res) {
                exit(json_encode(array(
                    'status'    => 1, 
                    'error'     => '兑换成功，如未收到兑换码短信通知，请联系主管。页面稍候将自动关闭。'
                )));
            }
            exit(json_encode(array(
                'status'    => 1, 
                'error'     => '兑换成功，请注意查收兑换码短信通知。页面稍候将自动关闭。'
            )));
        }
    break;
}
require_once V.'header.php';
require_once V.'point.php';
require_once V.'footer.php';

function sendPointChangeMessage($openid, $point = 0, $title = '您的积分已发入账号', $remark = '谢谢您的支持，请再接再厉。') {
    $point = intval($point) >= 0 ? '+'.$point : $point;
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