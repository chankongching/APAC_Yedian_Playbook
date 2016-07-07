<?php
(INAPP !== true) && die('Error !');

if(METHOD == 'GET') {
    exit(json_encode(array(
        'status'    => 0, 
        'error'     => '请求方式错误。'
    )));
} else {
    // 检查绑定信息
    $query = sprintf("select `uid`, `phone`, `status` from `%s%s` where `openid`='%s' limit 1;", 
        $C['db']['pfix'],
        'users',
        $DB->real_escape_string($_SESSION['fusionway_promo_girls_openid'])
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
        $data = $row;
    }
    if($data['status'] == 1) {
        die(json_encode(array(
            'status'    => 0, 
            'error'     => '已经绑定，无需重复绑定。'
        )));
    }

    // 获取数据源信息
    $query = sprintf("select `id`, `name`, `phone`, `status` from `%s%s` where `phone`='%s' limit 1;", 
        $C['db']['pfix'],
        'userdata',
        $DB->real_escape_string($data['phone'])
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
            'error'     => '没有此手机号码。'
        )));
    }
    while ($row = $source->fetch_assoc()) {
        $userdata = $row;
    }
    if($userdata['status'] == 1) {
        die(json_encode(array(
            'status'    => 0, 
            'error'     => '该手机号已被绑定。'
        )));
    }
    
    // 更新数据源信息
    $query = sprintf("update `%s%s` set `status`=1 where `phone`='%s' limit 1;", 
        $C['db']['pfix'],
        'userdata',
        $DB->real_escape_string($data['phone'])
    );
    $source = $DB->query($query);
    $DB->errno > 0 && die(json_encode(array(
        'status'    => 0, 
        'code'      => $DB->errno, 
        'error'     => $DB->error
    )));
    
    // 更新用户信息
    $query = sprintf("update `%s%s` set `status`=1, `datetime_bind`='%s' where `openid`='%s' limit 1;", 
        $C['db']['pfix'],
        'users',
        DATETIME, 
        $DB->real_escape_string($_SESSION['fusionway_promo_girls_openid'])
    );
    $source = $DB->query($query);
    $DB->errno > 0 && die(json_encode(array(
        'status'    => 0, 
        'code'      => $DB->errno, 
        'error'     => $DB->error
    )));
    
    exit(json_encode(array(
        'status'    => 1, 
        'error'     => '绑定成功。'
    )));
}