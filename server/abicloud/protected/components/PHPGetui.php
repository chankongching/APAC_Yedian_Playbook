<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PHPGetui
 *
 * @author WINGSUN
 */
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'php-getui' . DIRECTORY_SEPARATOR . 'IGt.Push.php');

class PHPGetui {

    const APPKEY = '8Yiv2QB0r39giOfe1m6AwA';
    const APPID = 'X4CkesaWUc9dyd87cS7lf5';
    const MASTERSECRET = 'Cb1ZrIccUM6q0U0dQxwBY7';
    const APPSECRET = 'AYBzQAtaw391gFfWzMBqF1';
    const HOST = 'http://sdk.open.api.igexin.com/apiex.htm';
    const ICON_URL = 'http://221.123.155.65/fenghuang/uploads/images/logo.png';
    const CID = '8aefd2b021e461946ecfc9a05cbcdae0';

    public static function pushToUser($cid, $title = '', $msg = '', $icon_url = '', $json_msg = '') {
        $igt = new IGeTui(self::HOST, self::APPKEY, self::MASTERSECRET);
        $template = self::IGtNotificationTemplate($title, $msg, $icon_url, $json_msg);

        $message = new IGtSingleMessage();
        $message->set_isOffline(true); //是否离线
        $message->set_offlineExpireTime(3600 * 12 * 1000); //离线时间
        $message->set_data($template); //设置推送消息类型
        //
        //接收方
        $target = new IGtTarget();
        $target->set_appId(self::APPID);
        $target->set_clientId(empty($cid) ? self::CID : $cid);

        $rep = $igt->pushMessageToSingle($message, $target);
        return $rep;
    }

    public static function pushToUserList($cid_array, $title, $msg = '', $icon_url = '', $json_msg = '') {
        putenv("needDetails=true");
        $igt = new IGeTui(self::HOST, self::APPKEY, self::MASTERSECRET);
        $template = self::IGtNotificationTemplate($title, $msg, $icon_url, $json_msg);

        $message = new IGtSingleMessage();
        $message->set_isOffline(true); //是否离线
        $message->set_offlineExpireTime(3600 * 12 * 1000); //离线时间
        $message->set_data($template); //设置推送消息类型

        $contentId = $igt->getContentId($message);
        //
        //接收方
        $targetList = array();
        foreach ($cid_array as $key => $cid) {
            $target = new IGtTarget();
            $target->set_appId(self::APPID);
            $target->set_clientId($cid);
            $targetList[] = $target;
        }
        //

        $rep = $igt->pushMessageToList($contentId, $targetList);
        return $rep;
    }

    public static function pushToUserListTrans($cid_array, $title, $msg = '', $msgtype = 0, $icon_url = '', $json_msg = '') {
        putenv("needDetails=true");
        $igt = new IGeTui(self::HOST, self::APPKEY, self::MASTERSECRET);
        $send_msg = array('title' => $title, 'content' => $msg, 'type' => $msgtype);
        if (2 == $msgtype) {
            $send_msg['image'] = $icon_url;
        }
        $send_json = json_encode($send_msg);
        $template = self::IGtTransmissionTemplate($send_json,$msg);

        $message = new IGtSingleMessage();
        $message->set_isOffline(true); //是否离线
        $message->set_offlineExpireTime(3600 * 12 * 1000); //离线时间
        $message->set_data($template); //设置推送消息类型

        $contentId = $igt->getContentId($message);
        //
        //接收方
        $targetList = array();
        foreach ($cid_array as $key => $cid) {
            $target = new IGtTarget();
            $target->set_appId(self::APPID);
            $target->set_clientId($cid);
            $targetList[] = $target;
        }
        //

        $rep = $igt->pushMessageToList($contentId, $targetList);
        return $rep;
    }

    public static function pushToAPP($title, $msg = '', $icon_url = '', $json_msg = '') {
        $igt = new IGeTui(self::HOST, self::APPKEY, self::MASTERSECRET);
        //消息模版：
        // 1.TransmissionTemplate:透传功能模板
        // 2.LinkTemplate:通知打开链接功能模板
        // 3.NotificationTemplate：通知透传功能模板
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        //$template = IGtNotyPopLoadTemplateDemo();
        //$template = IGtLinkTemplateDemo();
        $template = self::IGtNotificationTemplate($title, $msg, $icon_url, $json_msg);
        //$template = IGtTransmissionTemplateDemo();
        //个推信息体
        //基于应用消息体
        $message = new IGtAppMessage();

        $message->set_isOffline(true);
        $message->set_offlineExpireTime(3600 * 12 * 1000); //离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);


        $message->set_appIdList(array(self::APPID));
        $message->set_phoneTypeList(array('ANDROID'));
//	$message->set_provinceList(array('浙江','北京','河南'));
//	$message->set_tagList(array('开心'));

        $rep = $igt->pushMessageToApp($message);

        return $rep;
    }

    private static function IGtNotyPopLoadTemplate($title, $msg = '', $icon_url = '', $pop_title, $pop_msg = '', $pop_img_url = '', $left_btn_text = '下载', $right_btn_text = '取消') {
        $template = new IGtNotyPopLoadTemplate();

        $template->set_appId(self::APPID); //应用appid
        $template->set_appkey(self::APPKEY); //应用appkey
        //通知栏
        $template->set_notyTitle($title); //通知栏标题
        $template->set_notyContent($msg); //通知栏内容
        $template->set_notyIcon(empty($icon_url) ? self::ICON_URL : $icon_url); //通知栏logo
        $template->set_isBelled(true); //是否响铃
        $template->set_isVibrationed(true); //是否震动
        $template->set_isCleared(true); //通知栏是否可清除
        //弹框
        $template->set_popTitle($pop_title); //弹框标题
        $template->set_popContent($pop_msg); //弹框内容
        $template->set_popImage($pop_img_url); //弹框图片
        $template->set_popButton1($left_btn_text); //左键
        $template->set_popButton2($right_btn_text); //右键
        //下载
        $template->set_loadIcon($pop_img_url); //弹框图片
        $template->set_loadTitle("");
        $template->set_loadUrl("");
        $template->set_isAutoInstall(false);
        $template->set_isActived(true);

        return $template;
    }

    private static function IGtLinkTemplate($title, $msg = '', $icon_url = '', $url = '') {
        $template = new IGtLinkTemplate();
        $template->set_appId(self::APPID); //应用appid
        $template->set_appkey(self::APPKEY); //应用appkey
        $template->set_title($title); //通知栏标题
        $template->set_text($msg); //通知栏内容
        $template->set_logo(empty($icon_url) ? self::ICON_URL : $icon_url); //通知栏logo
        $template->set_isRing(true); //是否响铃
        $template->set_isVibrate(true); //是否震动
        $template->set_isClearable(true); //通知栏是否可清除
        $template->set_url($url); //打开连接地址
        // iOS推送需要设置的pushInfo字段
        //$template ->set_pushInfo($actionLocKey,$badge,$message,$sound,$payload,$locKey,$locArgs,$launchImage);
        //$template ->set_pushInfo("",2,"","","","","","");
        return $template;
    }

    private static function IGtNotificationTemplate($title, $msg = '', $icon_url = '', $json_msg = '') {
        $template = new IGtNotificationTemplate();
        $template->set_appId(self::APPID); //应用appid
        $template->set_appkey(self::APPKEY); //应用appkey
        $template->set_transmissionType(1); //透传消息类型
        $template->set_transmissionContent($json_msg); //透传内容
        $template->set_title(empty($title) ? 'Hello!' : $title); //通知栏标题
        $template->set_text($msg); //通知栏内容
        $template->set_logo(empty($icon_url) ? self::ICON_URL : $icon_url); //通知栏logo
        $template->set_isRing(true); //是否响铃
        $template->set_isVibrate(true); //是否震动
        $template->set_isClearable(true); //通知栏是否可清除
        // iOS推送需要设置的pushInfo字段
        //$template ->set_pushInfo($actionLocKey,$badge,$message,$sound,$payload,$locKey,$locArgs,$launchImage);
        //$template ->set_pushInfo("test",1,"message","","","","","");
        return $template;
    }

    private static function IGtTransmissionTemplate($json_msg = '', $msg = '') {
        $template = new IGtTransmissionTemplate();
        $template->set_appId(self::APPID); //应用appid
        $template->set_appkey(self::APPKEY); //应用appkey
        $template->set_transmissionType(2); //透传消息类型
        $template->set_transmissionContent($json_msg); //透传内容
        //iOS推送需要设置的pushInfo字段
        //$template ->set_pushInfo($actionLocKey,$badge,$message,$sound,$payload,$locKey,$locArgs,$launchImage);
        $template ->set_pushInfo("test", 1, $msg, "", "", "", "", "");
        return $template;
    }

}
