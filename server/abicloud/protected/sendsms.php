<?php

// '非常抱歉，由于系统繁忙或您选择的包房已被订满，包房预订失败。建议您选择其他KTV或稍候再试。【夜点应用】';
$sendSms = new SendSmsHttp();
$sendSms->mobile = '18602163052';
$sendSms->content = '非常抱歉，由于系统繁忙或您选择的包房已被订满，包房预订失败。建议您选择其他KTV或稍候再试。【夜点应用】';
$res = $sendSms->send();

class SendSmsHttp {
    private $sn = 'SDK-BBX-010-23609';
    private $pwd = '9609A6-f';
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