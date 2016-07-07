<?php
(INAPP !== true) && die('Error !');

if(isset($_POST['phone']) && !empty($_POST['phone'])) {
    // 检查手机号码格式
    $phone = trim($_POST['phone']);
    if(!preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}|18[0-9]{1}[0-9]{8}$/", $phone)){
        exit(json_encode(array(
            'status'    => 0, 
            'error'     => '手机号码格式不正确。'
        )));
    }
    
    // 检查系统中是否录入了此手机号
    $query = sprintf("select `name`, `phone`, `status` from `%s%s` where `phone`='%s' limit 1;", 
        $C['db']['pfix'],
        'userdata',
        $DB->real_escape_string($phone)
    );
    $source = $DB->query($query);
    $DB->errno > 0 && die(json_encode(array(
        'status'    => 0, 
        'code'      => $DB->errno, 
        'error'     => $DB->error
    )));
    if ($source->num_rows < 1) {
        exit(json_encode(array(
            'status'    => 0 ,
            'error'     => '没有此手机号码。'
        )));
    }
    while ($row = $source->fetch_assoc()) {
        $user = $row;
    }
    if($user['status'] == 1) {
        exit(json_encode(array(
            'status'    => 0 ,
            'error'     => '该手机号码已被其他微信号码绑定 。'
        )));
    }
    
    // 检查上一次验证码发送时间及是否已经验证过
    $query = sprintf("select `status`, `captcha_sendtime` from `%s%s` where `openid`='%s' limit 1;", 
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
    if ($source->num_rows == 1) {
        while ($row = $source->fetch_assoc()) {
            if($row['status'] == 1) {
                exit(json_encode(array(
                    'status'    => 0 ,
                    'error'     => '已经绑定，无需重复绑定。'
                )));
            }
            $captcha_sendtime = $row['captcha_sendtime'];
        }
    }
    if(isset($captcha_sendtime) && TIME < (strtotime($captcha_sendtime) + 120)) {
        exit(json_encode(array(
            'status'    => 0 ,
            'error'     => '请不要频繁发送验证码。', 
            'data'      => (strtotime($captcha_sendtime) + 120) - TIME
        )));
    }
    
    // 生成验证码
    $captcha = rand(100000,999999);
    
    // 更新验证码及手机信息
    $query = sprintf("update `%s%s` set `phone`='%s', `captcha`='%s', `captcha_sendtime`='%s' where `openid`='%s' limit 1;", 
        $C['db']['pfix'],
        'users',
        $DB->real_escape_string($phone), 
        $DB->real_escape_string($captcha), 
        $DB->real_escape_string(DATETIME), 
        $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid'])
    );
    $source = $DB->query($query);
    $DB->errno > 0 && die(json_encode(array(
        'status'    => 0, 
        'code'      => $DB->errno, 
        'error'     => $DB->error
    )));
    
    // 发送验证码
    $content = sprintf('您正在绑定夜点促销员平台，此次验证码是%d，如果不是本人操作，请忽略本条消息。【夜点应用】', 
        $captcha
    );
    $sendSms = new SendSmsHttp();
    $sendSms->mobile = $phone;
    $sendSms->content = $content;
    $res = $sendSms->send();
//     $res = true;
//     $res = sendSMS($phone, $content);
    if($res) {
        exit(json_encode(array(
            'status'    => 1, 
            'error'     => '验证码发送成功，请注意查收。'
//             'data'      => $user,
        )));
    } else {
        exit(json_encode(array(
            'status'    => 0 ,
            'error'     => $sendSms->errorMsg
        )));
    }
   
} else {
    exit(json_encode(array(
        'status'    => 0, 
        'error'     => '手机号码不能为空。'
    )));
}