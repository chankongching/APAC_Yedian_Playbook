<?php

define('INAPP', true);

require_once './_inc.php';

$m = (isset($_GET['m']) && in_array(trim($_GET['m']), array('getQRpermanentByID', 'getQR', 'getAchievement', 'checkSubscribed'))) ? trim($_GET['m']) : die();

switch($m) {
    case 'checkSubscribed':
        $openid = isset($_GET['openid']) ? $_GET['openid'] : null;
        if(!$openid) {
            exit(json_encode(array(
                'status'    => 0, 
                'error'     => 'OpenID Empty!'
            )));
        }
        
        $DB = new mysqli($C['db']['host'], $C['db']['user'], $C['db']['pswd'], $C['db']['name']);
        $DB->connect_errno && exit('DB Connection Error.');
        $DB->query("SET character_set_connection=" . $C['db']['char'] . ", character_set_results=" . $C['db']['char'] . ", character_set_client=binary");
        $query = sprintf("select 1 from `%s%s` where `FromUserName` = '%s' and `Ticket` != '' limit 1;", 
            $C['db']['pfix'],
            'logs_subscribe', 
            $DB->real_escape_string($openid)
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status'    => 0, 
            'code'      => $DB->errno, 
            'error'     => $DB->error
        )));
//         exit($query);
        if ($source->num_rows > 0) {
            die(json_encode(array(
                'status'    => 1, 
                'msg'     => 'Subscribed.'
            )));
        } else {
            die(json_encode(array(
                'status'    => 0, 
                'error'     => 'Unsubscribed.'
            )));
        }
    break;
    case 'getAchievement':
        $params = isset($_POST['params']) ? $_POST['params'] : null;
        if (empty($params)) {
            $params = file_get_contents("php://input");
        }
        $params = json_decode($params, true);
        if(!isset($params['openid']) || empty($params['openid'])) {
            exit(json_encode(array(
                'status'    => 0, 
                'error'     => 'openid为空。'
            )));
        }
        
        $DB = new mysqli($C['db']['host'], $C['db']['user'], $C['db']['pswd'], $C['db']['name']);
        $DB->connect_errno && exit('DB Connection Error.');
        $DB->query("SET character_set_connection=" . $C['db']['char'] . ", character_set_results=" . $C['db']['char'] . ", character_set_client=binary");
        
        $query = sprintf("select `ticket` from `%s%s` where `openid`='%s';", 
            $C['db']['pfix'],
            'qrcodes', 
            $DB->real_escape_string($params['openid'])
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status'    => 0, 
            'code'      => $DB->errno, 
            'error'     => $DB->error
        )));
        if ($source->num_rows < 1) {
            die(json_encode(array(
                'status'    => 1, 
                'error'     => 'no data'
            )));
        }
        while ($row = $source->fetch_assoc()) {
            $tickets[] =  $DB->real_escape_string($row['ticket']);
        }
        
        // 这里可能会有性能问题
        $query = sprintf("select count(1) as `count`, `Ticket` from `%s%s` where `Ticket` in ('%s') group by `ticket`;", 
            $C['db']['pfix'],
            'promogirls_scan_log', 
            implode("', '", $tickets)
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status'    => 0, 
            'code'      => $DB->errno, 
            'error'     => $DB->error
        )));
        if ($source->num_rows < 1) {
            die(json_encode(array(
                'status'    => 1, 
                'error'     => 'no data'
            )));
        }
        while ($row = $source->fetch_assoc()) {
            $data[$row['Ticket']] =  $row['count'];
        }
        $rs = array(
            'status'    => 1, 
            'data'      => $data
        );
        exit(json_encode($rs));
    break;
    case 'getQR':
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Headers:Accept, Content-Type, X-KTV-Application-Name, X-KTV-Vendor-Name, X-KTV-Application-Platform, X-KTV-User-Token');
        $expire = (isset($_GET['expire']) && !empty($_GET['expire'])) ? trim($_GET['expire']) : die();
        $openid = (isset($_GET['openid']) && !empty($_GET['openid'])) ? trim($_GET['openid']) : die();
        $detail = (isset($_GET['detail']) && !empty($_GET['detail'])) ? trim($_GET['detail']) : '';
        $dateline_expire = date('Y-m-d H:i:s', $expire);
        // 
        $url = 'http://letsktv.chinacloudapp.cn/wechat/index.php?m=getToken';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($ch);
        !$resp && die('code: '.curl_errno($ch).', error: '.curl_error($ch));
        $rs = json_decode($resp, true);
        curl_close($ch);
        if (isset($rs['status']) && $rs['status'] == 0) {
            die('code: '.$rs['code'].', error: '.$rs['error']);
        }
        $access_token = $rs['data'];
        
        $DB = new mysqli($C['db']['host'], $C['db']['user'], $C['db']['pswd'], $C['db']['name']);
        $DB->connect_errno && exit('DB Connection Error.');
        $DB->query("SET character_set_connection=" . $C['db']['char'] . ", character_set_results=" . $C['db']['char'] . ", character_set_client=binary");
        
        //
        $query = sprintf("insert into `%s%s` set `user`='%s', `openid`='%s', `dateline`='%s', `dateline_expire`='%s', `action_name`='%s', `detail`='%s';", 
            $C['db']['pfix'],
            'qrcodes', 
            'letsktv',
            $openid, 
            $DB->real_escape_string(DATETIME), 
            $DB->real_escape_string($dateline_expire), 
            $DB->real_escape_string('QR_SCENE'), 
            $DB->real_escape_string($detail)
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status'    => 0, 
            'code'      => $DB->errno, 
            'error'     => $DB->error
        )));
        $scene_id = $DB->insert_id;
        
        $qr_request_data = array(
            'expire_seconds'    => $expire - TIME, 
            'action_name'       => 'QR_SCENE', 
            'action_info'       => array(
                'scene'         => array(
                    'scene_id'  => $scene_id
                )
            )
        );
        
        $ch = curl_init();
        $url = sprintf("https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s", $access_token);
        $body = json_encode($qr_request_data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $resp = curl_exec($ch);
        !$resp && die('code: '.curl_errno($ch).', error: '.curl_error($ch));
        $rs = json_decode($resp, true);
        curl_close($ch);
        if (isset($rs['errcode']) && $rs['errcode'] > 0) {
            die('code: '.$rs['errcode'].', error: '.$rs['errmsg']);
        }
        
        $query = sprintf("update `%s%s` set `ticket`='%s', `raw`='%s' where `id`='%s' limit 1;", 
            $C['db']['pfix'],
            'qrcodes', 
            $DB->real_escape_string($rs['ticket']), 
            $DB->real_escape_string(json_encode($rs)), 
            $DB->real_escape_string($scene_id)
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status'    => 0, 
            'code'      => $DB->errno, 
            'error'     => $DB->error
        )));
        $rs['scene_id'] = $scene_id;
        $rs['dateline_expire'] = $dateline_expire;
        exit(json_encode($rs));
    break;
    case 'getQRpermanentByID':
        $openid = (isset($_GET['openid']) && !empty($_GET['openid'])) ? trim($_GET['openid']) : die();
        $detail = (isset($_GET['detail']) && !empty($_GET['detail'])) ? trim($_GET['detail']) : die();
        // 
        $url = 'http://letsktv.chinacloudapp.cn/wechat/index.php?m=getToken';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resp = curl_exec($ch);
        !$resp && die('code: '.curl_errno($ch).', error: '.curl_error($ch));
        $rs = json_decode($resp, true);
        curl_close($ch);
        if (isset($rs['status']) && $rs['status'] == 0) {
            die('code: '.$rs['code'].', error: '.$rs['error']);
        }
        $access_token = $rs['data'];
        
        $DB = new mysqli($C['db']['host'], $C['db']['user'], $C['db']['pswd'], $C['db']['name']);
        $DB->connect_errno && exit('DB Connection Error.');
        $DB->query("SET character_set_connection=" . $C['db']['char'] . ", character_set_results=" . $C['db']['char'] . ", character_set_client=binary");
        
        //
        $query = sprintf("insert into `%s%s` set `user`='%s', `openid`='%s', `dateline`='%s', `dateline_expire`='%s', `action_name`='%s', `detail`='%s';", 
            $C['db']['pfix'],
            'qrcodes', 
            'letsktv',
            $openid, 
            $DB->real_escape_string(DATETIME), 
            null, 
            $DB->real_escape_string('QR_LIMIT_STR_SCENE'), 
            $DB->real_escape_string($detail)
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status'    => 0, 
            'code'      => $DB->errno, 
            'error'     => $DB->error
        )));
        $scene_id = $DB->insert_id;
        
        $qr_request_data = array(
            'action_name'       => 'QR_LIMIT_STR_SCENE', 
            'action_info'       => array(
                'scene'         => array(
                    'scene_str'  => $scene_id
                )
            )
        );
        
        $ch = curl_init();
        $url = sprintf("https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s", $access_token);
        $body = json_encode($qr_request_data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $resp = curl_exec($ch);
        !$resp && die('code: '.curl_errno($ch).', error: '.curl_error($ch));
        $rs = json_decode($resp, true);
        curl_close($ch);
        if (isset($rs['errcode']) && $rs['errcode'] > 0) {
            die('code: '.$rs['errcode'].', error: '.$rs['errmsg']);
        }
        
        $query = sprintf("update `%s%s` set `ticket`='%s', `raw`='%s' where `id`='%s' limit 1;", 
            $C['db']['pfix'],
            'qrcodes', 
            $DB->real_escape_string($rs['ticket']), 
            $DB->real_escape_string(json_encode($rs)), 
            $DB->real_escape_string($scene_id)
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status'    => 0, 
            'code'      => $DB->errno, 
            'error'     => $DB->error
        )));
        $rs['scene_id'] = $scene_id;
        exit(json_encode($rs));
    break;
}


function curPageURL($self = false) {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= 's';
    }
    $pageURL .= '://';
    if (!in_array($_SERVER["SERVER_PORT"], array(80, 443))) {
        $pageURL .= $_SERVER["SERVER_NAME"] . ':' . $_SERVER["SERVER_PORT"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"];
    }
    $pageURL = $self === true ? $pageURL .= $_SERVER['PHP_SELF'] : $pageURL .= '/';
    return $pageURL;
}
function getip() {
    $unknown = 'unknown';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if (false !== strpos($ip, ',')) {
        $ip = reset(explode(',', $ip));
    }
    return $ip;
}
function memory($cmd, $key = '', $value = '', $ttl = 0) {
    global $MC;
    if ($cmd == 'enable') {
        return $MC->enable;
    } elseif ($MC->enable && in_array($cmd, array('set', 'get', 'rm'))) {
        switch ($cmd) {
            case 'set':return $MC->set($key, $value, $ttl);
            break;
            case 'get':return $MC->get($key);
            break;
            case 'rm':return $MC->rm($key);
            break;
            case 'inc':return $MC->inc($key, 1);
            break;
        }
    }
    return null;
}

Class MC {
    private $obj;
    private $pfix;
    public $enable = null;
    
    public function __construct($config) {
        if (!empty($config['host'])) {
            if (class_exists('Memcached')) {
                $this->obj = new Memcached;
                $connect = $this->obj->addServer($config['host'], $config['port']);
            } else {
                $this->obj = new Memcache;
                if ($config['pcon']) {
                    $connect = @$this->obj->pconnect($config['host'], $config['port']);
                } else {
                    $connect = @$this->obj->connect($config['host'], $config['port']);
                }
            }
            $this->enable = $connect ? true : false;
            $this->pfix = $connect ? $config['pfix'] : $config['pfix'];
        }
    }
    public function get($key) {
        return $this->obj->get($this->pfix.$key);
    }
    public function set($key, $value, $ttl = 1440) {
        if (class_exists('Memcached')) {
            return $this->obj->set($this->pfix.$key, $value, time() + $ttl);
        } else {
            return $this->obj->set($this->pfix.$key, $value, MEMCACHE_COMPRESSED, $ttl);
        }
    }
    public function getMulti($keys) {
        return $this->obj->get($this->pfix.$keys);
    }
    function rm($key) {
        return $this->obj->delete($this->pfix.$key);
    }
    public function clear() {
        return $this->obj->flush();
    }
    public function inc($key, $step = 1) {
        return $this->obj->increment($this->pfix.$key, $step);
    }
    public function dec($key, $step = 1) {
        return $this->obj->decrement($this->pfix.$key, $step);
    }
}