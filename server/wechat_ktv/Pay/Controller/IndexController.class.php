<?php
namespace Pay\Controller;
use Think\Controller;

class IndexController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->wxpay_config = C('wxpay');
    }

    public function index() {
		echo 'sdf';
	}
	public function payapi(){
        vendor('WxPay/Data');
        vendor('WxPay/Api');
        header("Access-Control-Allow-Headers: Accept, Content-Type, X-KTV-Application-Name, X-KTV-Vendor-Name, X-KTV-Application-Platform, X-KTV-User-Token");

        $post_array = $_POST;
        if (empty($post_array)) {
            $post_data = file_get_contents("php://input");
            $post_array = json_decode($post_data, true);
        }
        $body = !empty($post_array["body"]) ? $post_array["body"] : "我是夜点收费员";
        $attach = !empty($post_array["attach"]) ? $post_array["attach"] : "我是attach";
        $Out_trade_no = !empty($post_array["trade_no"]) ? $post_array["trade_no"] . date("YmdHis") : $this->wxpay_config['MCHID'] . date("YmdHis");
        $fee = !empty($post_array["fee"]) ? $post_array["fee"] : 1;
        $starttime = date("YmdHis");
        $expire = date("YmdHis", time() + 600);
        $Goods_tag = !empty($post_array["Goods_tag"]) ? $post_array["Goods_tag"] : "Tag我是夜点收费员";
        $notify_url = "http://letsktv.chinacloudapp.cn/paymentss/mypay/notify.php";
        $Trade_type = !empty($post_array["Trade_type"]) ? $post_array["Trade_type"] : "JSAPI";
        $openid = !empty($post_array["openid"]) ? $post_array["openid"] : "okwyOwpvP0WJfi0GhGxzQ5sDJMCY";
        $tools = new jssdkController();
//        var_dump($tools);
        $input = new \WxPayUnifiedOrder();
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
        $order = \WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $editAddress = $tools->GetEditAddressParameters();
        die(json_encode(array('result' => 0, 'signinfo' => array('jsApiParameters' => json_decode($jsApiParameters, true), 'editAddress' => json_decode($editAddress)), 'order' => $order), true));
    }
}