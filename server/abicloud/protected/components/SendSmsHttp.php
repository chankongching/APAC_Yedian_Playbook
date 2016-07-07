<?php

class SendSmsHttp {
    private $sn = 'SDK-BBX-010-23609';
    private $pwd = '9609A6-e';
    public $mobile;
    public $content;
    public $ext = '';
    public $stime = '';
    public $msgfmt = '';
    public $rrid = '';
    public $errorMsg;
    
    public function send() {
        $flag = 0;
        $params = '';
        $argv = array(
            'sn'      => $this->sn, 
            'pwd'     => strtoupper(md5($this->sn.$this->pwd)), 
            'mobile'  => $this->mobile, 
            'content' => $this->content, 
            'ext'     => $this->ext, 
            'stime'   => $this->stime, 
            'msgfmt'  => $this->msgfmt, 
            'rrid'    => $this->rrid
        );
        foreach($argv as $key=>$value) {
            if ($flag != 0) {
                $params .= "&";
                $flag    = 1;
            }
            $params .= $key."=";
            $params .= urlencode($value);
            $flag    = 1;
        }
        $length = strlen($params);
        $fp = fsockopen("sdk.entinfo.cn", 8061, $errno, $errstr, 10) or exit($errstr."--->".$errno);
        $header  = "POST /webservice.asmx/mdsmssend HTTP/1.1\r\n";
        $header .= "Host:sdk.entinfo.cn\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: ".$length."\r\n";
        $header .= "Connection: Close\r\n\r\n";
        $header .= $params."\r\n";
        fputs($fp, $header);
        $inheader = 1;
        while (!feof($fp)) {
            $line = fgets($fp, 1024);
            if ($inheader && ($line == "\n" || $line == "\r\n")) {
                $inheader = 0;
            }
            if ($inheader == 0) {
        //        echo $line;
            }
        }
        $line   = str_replace("<string xmlns=\"http://entinfo.cn/\">", "", $line);
        $line   = str_replace("</string>", "", $line);
        $result = explode("-", $line);
        if(count($result) > 1) {
            $this->errorMsg = isset($line) ? $line : '未知错误';
//             echo $this->errorMsg."\n";
//             echo '时间: '.date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']).', 失败, 手机号码: '.$argv['mobile'].' 返回值为:'.$line.' 内容：'.$argv['content']."\n";
            return false;
        } else {
//             echo '时间: '.date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']).', 成功, 手机号码: '.$argv['mobile'].' 返回值为:'.$line.' 内容：'.$argv['content']."\n";
            return true;
        }
    }
}

/**
 * HTTP接口发送短信，参数说明见文档，需要安装CURL扩展
 * 
 * 使用示例：
 * $sendSms = new SendSmsHttp();
 * $sendSms->SpCode = '123456';
 * $sendSms->LoginName = 'abc123';
 * $sendSms->Password = '123abc';
 * $sendSms->MessageContent = '测试短信';
 * $sendSms->UserNumber = '15012345678,13812345678';
 * $sendSms->SerialNumber = '';
 * $sendSms->ScheduleTime = '';
 * $sendSms->ExtendAccessNum = '';
 * $sendSms->f = '';
 * $res = $sendSms->send();
 * echo $res ? '发送成功' : $sendSms->errorMsg;
 * 
 */
//class SendSmsHttp {
//
//    //private $_apiUrl = 'http://gd.ums86.com:8899/sms/Api/Send.do'; // 发送短信接口地址
//    private $_apiUrl = 'http://sms.api.ums86.com:8899/sms/Api/Send.do';
//    public $SpCode;
//    public $LoginName;
//    public $Password;
//    public $MessageContent;
//    public $UserNumber;
//    public $SerialNumber;
//    public $ScheduleTime;
//    public $ExtendAccessNum;
//    public $f;
//    public $errorMsg;
//
//    /**
//     * 发送短信
//     * @return boolean
//     */
//    public function send() {
//        $params = array(
//            "SpCode" => $this->SpCode,
//            "LoginName" => $this->LoginName,
//            "Password" => $this->Password,
//            "MessageContent" => iconv("UTF-8", "GB2312//IGNORE", $this->MessageContent),
//            "UserNumber" => $this->UserNumber,
//            "SerialNumber" => $this->SerialNumber,
//            "ScheduleTime" => $this->ScheduleTime,
//            "ExtendAccessNum" => $this->ExtendAccessNum,
//            "f" => $this->f,
//        );
//        $data = http_build_query($params);
//        $res = iconv('GB2312', 'UTF-8//IGNORE', $this->_httpClient($data));
//        $resArr = array();
//        parse_str($res, $resArr);
//
//        if (!empty($resArr) && $resArr["result"] == 0)
//            return true;
//        else {
//            if (empty($this->errorMsg))
//                $this->errorMsg = isset($resArr["description"]) ? $resArr["description"] : '未知错误';
//            return false;
//        }
//    }
//
//    /**
//     * POST方式访问接口
//     * @param string $data
//     * @return mixed
//     */
//    private function _httpClient($data) {
//        try {
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $this->_apiUrl);
//            curl_setopt($ch, CURLOPT_HEADER, 0);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//            $res = curl_exec($ch);
//            curl_close($ch);
//            return $res;
//        } catch (Exception $e) {
//            $this->errorMsg = $e->getMessage();
//            return false;
//        }
//    }
//
//}
