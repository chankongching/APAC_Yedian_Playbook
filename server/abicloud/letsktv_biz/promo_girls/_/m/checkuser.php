<?php
(INAPP !== true) && die('Error !');

if(METHOD == 'GET') {
    require_once V.'header.php';
    require_once V.'checkuser.php';
    require_once V.'footer.php';
} else {
    if(isset($_POST['phone']) && !empty($_POST['phone']) && isset($_POST['captcha']) && !empty($_POST['captcha']) ) {
        // 检查手机号码格式
        $phone = trim($_POST['phone']);
        if(!preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}|18[0-9]{1}[0-9]{8}$/", $_POST['phone'])){
            exit(json_encode(array(
                'status'    => 0, 
                'error'     => '手机号码格式不正确。'
            )));
        }
        
        // 检查手机及验证码信息
        $query = sprintf("select `status`, `phone`, `captcha` from `%s%s` where `openid`='%s' limit 1;", 
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
            $data = $row;
        }
        if($data['status'] == 1) {
            die(json_encode(array(
                'status'    => 0, 
                'error'     => '已经绑定，无需重复绑定。'
            )));
        }
        if (trim($_POST['phone']) !== $data['phone']) {
            die(json_encode(array(
                'status'    => 0, 
                'error'     => '请先发送验证码。'
            )));
        }
        if(trim($_POST['captcha']) !== $data['captcha']) {
            die(json_encode(array(
                'status'    => 0, 
                'error'     => '验证码不正确。'
            )));
        }
        
        // 获取数据源信息
        $query = sprintf("select `id`, `name`, `phone`, `status` from `%s%s` where `phone`='%s' limit 1;", 
            $C['db']['pfix'],
            'userdata',
            $DB->real_escape_string($_POST['phone'])
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
        
        // 更新绑定信息
        $query = sprintf("update `%s%s` set `uid`=%d, `name`='%s' where `openid`='%s' limit 1;", 
            $C['db']['pfix'],
            'users',
            $DB->real_escape_string($userdata['id']),
            $DB->real_escape_string($userdata['name']),
            $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid'])
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status'    => 0, 
            'code'      => $DB->errno, 
            'error'     => $DB->error
        )));
        die(json_encode(array(
            'status'    => 1, 
            'error'     => '请确认信息。', 
            'data'      => $userdata
        )));
    } else {
        die(json_encode(array(
            'status'    => 0, 
            'error'     => '请将信息填写完整。'
        )));
    }
}