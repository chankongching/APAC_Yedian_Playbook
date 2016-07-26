<?php

ini_set('date.timezone', 'Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

//初始化日志
$logHandler = new CLogFileHandler("../logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data) {
	foreach ($data as $key => $value) {
		echo "<font color='#00ff55;'>$key</font> : $value <br/>";
	}
}
header("Access-Control-Allow-Headers: Accept, Content-Type, X-KTV-Application-Name, X-KTV-Vendor-Name, X-KTV-Application-Platform, X-KTV-User-Token");

$post_array = $_POST;
if (empty($post_array)) {
	$post_data = file_get_contents("php://input");
	$post_array = json_decode($post_data, true);
}
$body = !empty($post_array["body"]) ? $post_array["body"] : "我是夜点收费员";
$attach = !empty($post_array["attach"]) ? $post_array["attach"] : "我是attach";
$Out_trade_no = !empty($post_array["trade_no"]) ? $post_array["trade_no"] . date("YmdHis") : WxPayConfig::MCHID . date("YmdHis");
$fee = !empty($post_array["fee"]) ? $post_array["fee"] : 1;
$starttime = date("YmdHis");
$expire = date("YmdHis", time() + 600);
$Goods_tag = !empty($post_array["Goods_tag"]) ? $post_array["Goods_tag"] : "Tag我是夜点收费员";
$notify_url = "http://letsktv.chinacloudapp.cn/paymentss/mypay/notify.php";
$Trade_type = !empty($post_array["Trade_type"]) ? $post_array["Trade_type"] : "JSAPI";
$openid = !empty($post_array["openid"]) ? $post_array["openid"] : "okwyOwpvP0WJfi0GhGxzQ5sDJMCY";

// var_dump($_POST);
// $body="test";
// $attach = "attach";
// $Out_trade_no = WxPayConfig::MCHID . date("YmdHis");
// $fee = 1;
// $starttime = date("YmdHis");
// $expire = date("YmdHis", time() + 600);
// $Goods_tag = "test";
// $notify_url = "http://paysdk.weixin.qq.com/example/notify.php";
// $Trade_type = "JSAPI";

//①、获取用户openid
$tools = new JsApiPay();
// $openId = $tools->GetOpenid();

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($body);
$input->SetAttach($attach);
$input->SetOut_trade_no($Out_trade_no);
$input->SetTotal_fee($fee);
$input->SetTime_start($starttime);
$input->SetTime_expire($expire);
$input->SetGoods_tag($Goods_tag);
$input->SetNotify_url($notify_url);
$input->SetTrade_type($Trade_type);
$input->SetOpenid($openid);
$order = WxPayApi::unifiedOrder($input);
// echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
// printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
$editAddress = $tools->GetEditAddressParameters();
die(json_encode(array('result' => 0, 'signinfo' => array('jsApiParameters' => json_decode($jsApiParameters, true), 'editAddress' => json_decode($editAddress)), 'order' => $order), true));
//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>