<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestController
 *
 * @author SUNJOY
 */
class MytestController extends CController {

	protected $rest = null;

// WEB Service URI
	const ERP_WEBSERVICE_URI = 'http://192.168.2.9/V8WebService.dll/wsdl/IMain';
	const ERP_USERID = 'dba';
	const ROOM_ID = 1;

	protected $test_room_id = 1;
	protected $erp_userid = 'dba';

	public function init() {
		parent::init();
		$this->rest = new RESTClient();
		$this->rest->initialize(array('server' => 'http://rbnldev.chinacloudapp.cn/'));
		$this->rest->set_header('X-KTV-Application-Name', 'eec607d1f47c18c9160634fd0954da1a');
		$this->rest->set_header('X-KTV-Vendor-Name', '1d55af1659424cf94d869e2580a11bf8');
		$this->rest->set_header('X-KTV-User-ID', '');
		//$this->rest->set_header('X-KTV-Room-ID', Yii::app()->user->getState('myTestRoom'));
		$this->rest->set_header('X-KTV-User-Token', Yii::app()->user->getState('myTestToken'));

		//$this->erp_userid = (empty(Yii::app()->params['erp_api_userid']) ? self::ERP_USERID : Yii::app()->params['erp_api_userid']);
		//$this->test_room_id = (empty(Yii::app()->params['test_room_id']) ? self::ROOM_ID : Yii::app()->params['test_room_id']);
	}

	public function actionErpTest() {
		$soapClient = new SoapClient(empty(Yii::app()->params['erp_api_url']) ? self::ERP_WEBSERVICE_URI : Yii::app()->params['erp_api_url'], array('cache_wsdl' => WSDL_CACHE_BOTH, 'keep_alive' => true));
		var_dump($soapClient->WebTest());
		var_dump($soapClient->GETDBVersion());
		var_dump($soapClient->WS_UserVerify($this->erp_userid, 'iloveu'));
		var_dump($soapClient->WS_GetBillInfo('270'));
		var_dump($soapClient->WS_GetMenuClass('270', $this->erp_userid, '0'));
		var_dump($soapClient->WS_GetMenuItem('270', '140', $this->erp_userid, '', '0'));
		//var_dump($soapClient->WS_GetSetMenuClass('270', '14001'));
		//var_dump($soapClient->WS_GetSetMenuItem('270', '14001', '01'));
		unset($soapClient);
	}

	public function actionOauthLogin() {
		$rest = $this->rest;

		$post_array = array(
			'openid' => 'openid1985',
			'token' => '1234567856543',
			'display_name' => 'openid1985',
			'avatar_url' => 'http://www.sina.com.cn',
			'type' => 1,
		);

		$post_json = json_encode($post_array);

		$json = $rest->post('user/oauthlogin', array('OpenLoginRequest' => $post_json));
// TODO: for test only
		$result = json_decode($json, TRUE);
		if (!empty($result) && !is_null($result['result']) && $result['result'] == 0) {
			Yii::app()->user->setState('myTestToken', $result['token']);
		} else {
			Yii::app()->user->setState('myTestToken', '');
		}
		Yii::app()->user->setState('myTestRoom', '');

		$this->displayTest('user/oauthlogin', 'POST', array('OpenLoginRequest' => $post_json), $json);
	}

	public function actionIndex() {
		echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Server API</title><head><body>';

		echo '<div style="text-align:center;">';
		//echo '<a href="' . $this->createUrl('mytest/erptest') . '">ERP 测试</a><br>';
		/*
	        echo '<a href="' . $this->createUrl('mytest/oauthlogin') . '">1. 登录KTV系统</a><br>';

	        $qrcode = CheckinCode::model()->findByAttributes(array('room_id' => $this->test_room_id));
	        if (is_null($qrcode)) {
	            throw new Exception('没有设置房间 ' . $this->test_room_id . ' !');
	        }

	        $siteurl = Yii::app()->createAbsoluteUrl('/');
	        $downloadurl = Yii::app()->createAbsoluteUrl('/') . '/site/download/';
	        $checkinurl = Yii::app()->createAbsoluteUrl('/') . '/user/checkin/?cid=' . $qrcode->code;

	        $qrdata = $downloadurl . 'SITEURL-' . base64url_encode($siteurl) . '/CHECKINURL-' . base64url_encode($checkinurl);
	        //$qrdata = $siteurl . '||' . Yii::app()->createAbsoluteUrl('/') . '/user/checkin/?cid=' . $qrcode->code;
	        //$qrdata = $qrcode->code;

	        $this->widget('application.extensions.qrcode.QRCodeGenerator', array(
	            'data' => $qrdata,
	            'checkExists' => true,
	            'subfolderVar' => false,
	            'matrixPointSize' => 5,
	            'displayImage' => true, // default to true, if set to false display a URL path
	            'errorCorrectionLevel' => 'L', // available parameter is L,M,Q,H
	            'matrixPointSize' => 4, // 1 to 10 only
	            'imageTagOptions' => array('style' => 'width:200px;')
	        ));
	        //echo '<br><a href="' . $this->createUrl('test/qrcheckin') . '">QR code login and check in</a><br>';
	        echo '<br><a href="' . $this->createUrl('mytest/checkin') . '">2. 点击登入房间</a><br>';
	        echo "<br><hr/><br>";
*/
		echo '</div>';
		//if (!empty(Yii::app()->user->getState('myTestRoom')) && !empty(Yii::app()->user->getState('myTestToken'))) {
		echo '<div style="text-align:center;">';
		echo '<a href="' . $this->createUrl('mytest/test') . '">进入' . "Let's KTV System" . ' API测试</a><br>';
		echo '</div>';
		//}
		//echo CHtml::link($qrdata, $qrdata);
		echo '</body></html>';
	}

	public function actionTest() {
		$this->render('test');
	}

	public function actionCheckin() {
		$rest = $this->rest;

		$qrcode = CheckinCode::model()->findByAttributes(array('room_id' => $this->test_room_id));
		if (is_null($qrcode)) {
			throw new Exception('No available QR Code!');
		}

		$json = $rest->post('user/checkin/?cid=' . $qrcode->code);

// TODO: for test only
		$result = json_decode($json, TRUE);
		if (!empty($result) && !is_null($result['result']) && $result['result'] == 0) {
			Yii::app()->user->setState('myTestRoom', $result['roomid']);
		} else {
			Yii::app()->user->setState('myTestRoom', '');
		}

		$this->displayTest('user/checkin/?cid=' . $qrcode->code, 'POST', array(), $json);
	}

	public function actionQrCheckin() {
		$rest = $this->rest;

		$qrcode = CheckinCode::model()->findByAttributes(array('room_id' => $this->test_room_id));
		if (is_null($qrcode)) {
			throw new Exception('No available QR Code!');
		}

		$post_array = array(
			'openid' => 'openid1985',
			'token' => '1234567856543',
			'display_name' => 'openid1985',
			'avatar_url' => 'http://www.sina.com.cn',
			'type' => 1,
			'cid' => $qrcode->code,
		);

		$post_json = json_encode($post_array);

		$json = $rest->post('user/qrcheckin', array('QrCheckinRequest' => $post_json));

// TODO: for test only
		$result = json_decode($json, TRUE);
		if (!empty($result) && !is_null($result['result']) && $result['result'] == 0) {
			Yii::app()->user->setState('myTestRoom', $result['roomid']);
			Yii::app()->user->setState('myTestToken', $result['token']);
		}

		$this->displayTest('user/qrcheckin', 'POST', array('QrCheckinRequest' => $post_json), $json);
	}

	public function displayTest($api, $request_type, $request_param, $response) {
		$_display = array();
		$_display['API URL'] = Yii::app()->createAbsoluteUrl('/') . '/' . $api;
		$_display[$request_type] = $request_param;
		$_response = json_decode($response, true);
		if (is_null($_response)) {
			$_response = $response;
		}
		$_display['RESPONSE'] = $_response;

//print("API URL: " . Yii::app()->createAbsoluteUrl($api) . "<br>\r\n<br>\r\n");
		//print($request_type . ":<br>\r\n");
		//print_r($request_param);
		//print("<br>\r\n<br>\r\nRESPONSE:<br>\r\n");
		//print_r($response);
		//print_r(json_encode($_display));
		echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Server API</title><head><body>';
		echo "<pre>";
		print_r($_display);
		echo "</pre>";
		echo '</body></html>';
	}
	public function actionDelOrder() {
		// $request_type = Yii::app()->request->getRequestType();
		// if ('GET' != $request_type) {
		// 	$this->sendResults($result_array, self::BadRequest);
		// }
		$orderid = $_GET['order_code'];
		// echo $orderid;
		$tmp = RoomBooking::model()->findByAttributes(array('code' => $orderid));
		// $tmp
		if ($tmp != NULL) {
			if ($tmp->delete() == '1') {
				echo '删除成功';
			}
		} else {
			echo 'code无效';
		}

	}

}
