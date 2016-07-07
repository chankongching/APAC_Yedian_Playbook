<?php
namespace Admin\Controller;
use Think\Controller;

class PlatformUserController extends CommonController {
	public function lists() {
//		$model = M('Order');
//		parent::lists('PlatformUser', 1, 1000);
		$this->display();
	}
	public function lists_ajax(){
		$table = 'ac_platform_user';
		$primaryKey = 'id';
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$columns = array(
			array('db' => 'id', 'dt' => 0),
			array('db' => 'openid', 'dt' => 1),
			array('db' => 'display_name', 'dt' => 2),
			array('db' => 'create_time', 'dt' => 3),
//			array('db' => 'userid', 'dt' =>4),
//			array('db' => 'address', 'dt' => 5),
//			array('db' => 'type', 'dt' =>6),
//			array('db' => 'type', 'dt' =>7,
//				'formatter' => function ($d,$row){
////					var_dump($row);die();
//					if($d == '2'){
//						return '商家版';
//					}elseif($d == '0'){
//						return "CallCenter";
//					}
//
//				}),
			array('db' => 'id', 'dt' =>4,'formatter'=>function($d,$row){
				return '<a href="'.U('update',array('id'=>$d)).'">查看</a>';
			}),

		);

		$this->ssp_lists_ajax($_POST,$table,$columns);

	}
	public function detail() {
		$koid = I('get.koid');
		if ($koid == null) {
			$this->redirect('PlatformUser/lists');
		} else {
			$orderinfo = M('Order')->where(array('id' => $koid))->find();
			if ($orderinfo != NULL) {
				$this->assign('orderinfo', $orderinfo);
				$this->display();
			} else {
				$this->redirect('PlatformUser/lists');
			}
		}
	}
	public function tuifenC() {
		$result = M('GiftOrderTuikuan')->distinct(true)->field('userid,order_no,points')->select();
		foreach ($result as $key => $value) {
			$co = M('GiftOrderTuikuan')->where(array('userid' => $value['userid'], 'order_no' => $value['order_no'], 'is_tuidan' => '0'))->Count();
			if ($co > 1) {
				echo $value['points'];
				echo $value['userid'];
				// M('UserPoints')->where(array('user_id' => $value['userid']))->setDec('points', $value['points']);
				$tmp = M('GiftOrderTuikuan')->where(array('userid' => $value['userid'], 'order_no' => $value['order_no']))->find();
				M('GiftOrderTuikuan')->where(array('id' => $tmp['id']))->data(array('is_tuidan' => 1))->save();
			} else {
				echo 'NNOOMM';
			}
		}
	}
	public function tuifen() {
		$Model = new \Think\Model();
		$result = $Model->query("select gorder.userid,gorder.id,user.`display_name`,gift.`productsale_points`,gorder.`sellorder_datetime`,order_no,gorder.sellordergoods_goodsid,gift.productsale_name as goodname,order_status,user.openid as oid from `ac_gift_order` gorder left join (`ac_platform_user` as user ,`ac_gifts` as gift) ON user.id= gorder.`userid` and gorder.sellordergoods_goodsid = gift.`product_id` where gorder.order_status='订单已取消' and gorder.userid >0 and gorder.is_tuikuan = 0");
		foreach ($result as $key => $value) {
			M('UserPoints')->where(array('user_id' => $value['userid']))->setInc('points', $value['productsale_points']);
			$userpoints = M('UserPoints')->where(array('user_id' => $value['userid']))->find();
			M('GiftOrder')->where(array('id' => $value['id']))->data(array('is_tuikuan' => '1'))->save();
			$got = array(
				'userid' => $value['userid'],
				'order_no' => $value['order_no'],
				'points' => $value['productsale_points'],
			);
			M('GiftOrderTuikuan')->add($got);
			$remark = $value['display_name'] . '您好，您在' . $value['sellorder_datetime'] . '兑换的' . $value['goodname'] . '兑换失败,' . $value['productsale_points'] . '积分已经退回到您的帐户，请注意查收。谢谢！';
			$info = array(
				'openid' => $value['oid'],
				'display_name' => $value['display_name'],
			);
			$this->sendPointChangeMessage($info, $value['productsale_points'], $userpoints['points'], '积分退还', '积分兑换失败，退回积分', $remark);
			echo $remark . '<br>';

		}
	}
	public function sendPointChangeMessage($info, $jifen, $yue, $title = '', $yuanyin = '', $remark = '') {
		$dataM = array();
		$dataM['template_id'] = 'sB9elydZv2PT-bSPozMgLbwrLmS0xIanf3RFk1jsJ4A';
		$dataM['url'] = '';
		$dataM['topcolor'] = '#FF0000';
		$dataM['touser'] = $info['openid'];
		$dataM['data'] = array(
			'first' => array(
				'value' => $title,
				'color' => '#000000',
			),
			'keyword1' => array(
				'value' => $info['display_name'],
				'color' => '#000000',
			),
			'keyword2' => array(
				'value' => date("Y-m-d h:i"),
				'color' => '#000000',
			),
			'keyword3' => array(
				'value' => $jifen,
				'color' => '#000000',
			),
			'keyword4' => array(
				'value' => $yue,
				'color' => '#000000',
			),
			'keyword5' => array(
				'value' => $yuanyin,
				'color' => '#000000',
			),
			'remark' => array(
				'value' => $remark,
				'color' => '#000000',
			),
		);
		$jsonstr = json_encode($dataM, true);
		$token_content = $this->httpGet('http://letsktv.chinacloudapp.cn/wechat/index.php?m=getToken');
		$token = json_decode($token_content, true);
		$post_url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $token['data'];
		return $this->http_post($post_url, $jsonstr);
	}
	// 发送http_post请求
	private function http_post($url, $param, $post_file = false) {
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

	private function httpGet($url) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);

		curl_close($ch);
		return $output;
	}
}