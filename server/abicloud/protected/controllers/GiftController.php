<?php

class GiftController extends ApiController {
	// protected token = 'j7291iwusjau1271'; //促销员产品
	//    protected $token = 'h6521ywhsjuq72y1'; //夜点用户产品测试token
	protected $token = 'h72uwjsu1278qisi'; //夜点用户产品
	// public function __construct() {
	// 	parent::__construct();
	// 	$this->token = 'h72uwjsu1278qisi'; //夜点用户产品
	// 	// $this->token = 'j7291iwusjau1271'; //促销员产品
	// }
	// /**
	//  * @return array action filters
	//  */
	// public function filters() {
	// 	return array(
	// 		'accessControl', // perform access control for CRUD operations
	// 	);
	// }
	//    /**
	//     * @return array action filters
	//     */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function accessRules() {
		return array(
			array('allow', // allow authenticated user to perform user 'view' and 'update' actions
				'actions' => array('view', 'update'),
				'users' => array('@'),
				'roles' => array('Member', 'member', 'reader'),
			),
			array('allow', // allow authenticated user to perform user 'view' and 'update' actions
				'actions' => array('index'),
				'users' => array('@'),
				'roles' => array('Member', 'member'),
			),
			array('allow', // allow admin user to perform all actions
				'users' => array('@'),
				'roles' => array('Super', 'super'),
			),
			array('deny', // deny all guest users to access user controller
				'actions' => array('index', 'create', 'delete', 'admin', 'view', 'update'),
				'users' => array('*'),
			),
		);
	}
	// public function actionTestOrder() {
	// 	$GiftOrder = new GiftOrder();
	// 	$GiftOrder->setAttributes(array('userid' => '999', 'order_no' => 'YC15122100064', 'is_check' => '1'));
	// 	if ($GiftOrder->save()) {
	// 		// echo '添加成功';
	// 	}
	// }
	public function actionTestGetList() {
//        if(!isset(Yii::app()->session['oooo'])){
		//            Yii::app()->session['oooo']=time();
		//        }
		//        echo Yii::app()->session['oooo'];
		//        unset(Yii::app()->session['oooo']);
		$f = new feedback();
//        $f->ktvid = 'sdf';
		//        $f->openid = 'sdfsdf';
		//        $f->errortype = '1';
		$f->setAttributes(array('ktvid' => '33333', 'openid' => 'sdff', 'errortype' => '1111'));
		$f->save();
		$f->setAttributes(array('ktvid' => '2222222'));
		$f->save();

	}

	//导入礼品数据
	public function actionGiftCheck() {
		$query = array();
		$query['user_acct'] = 'lsp';
		$tmp = $this->getProductList($query);
		if ($tmp['success']) {
			$data = $tmp['data']['product_list'];
			foreach ($data as $key => $value) {
				$data[$key]['product_mainpic'] = urldecode($value['product_mainpic']);
				$data[$key]['productsale_name'] = urldecode($value['productsale_name']);
				$data[$key]['productsale_cata3'] = urldecode($value['productsale_cata3']);
				$data[$key]['productsale_subname'] = urldecode($value['productsale_subname']);
				$data[$key]['productsale_subname'] = urldecode($value['productsale_subname']);
			}
		}
		// echo count($data);
		$plist = array();
		$model = new Gifts();
		foreach ($data as $key => $value) {
			// echo $value['product_id'];
			$pdetail = $this->getProductDetail($value['product_id']);
			// var_dump($pdetail["data"]);
			$pdetail["data"]['product_mainpic'] = urldecode($pdetail["data"]['product_mainpic']);
			$pdetail["data"]['productsale_name'] = urldecode($pdetail["data"]['productsale_name']);
			$pdetail["data"]['productsale_subname'] = urldecode($pdetail["data"]['productsale_subname']);
			$pdetail["data"]['productsale_abstr'] = urldecode($pdetail["data"]['productsale_abstr']);
			$pdetail["data"]['productsale_cata1'] = urldecode($pdetail["data"]['productsale_cata1']);
			$pdetail["data"]['productsale_cata2'] = urldecode($pdetail["data"]['productsale_cata2']);
			$pdetail["data"]['productsale_cata3'] = urldecode($pdetail["data"]['productsale_cata3']);
			$pdetail["data"]['productsale_cata4'] = urldecode($pdetail["data"]['productsale_cata4']);
			$pdetail["data"]['productsale_cata5'] = urldecode($pdetail["data"]['productsale_cata5']);
			$pdetail["data"]['productsale_cont1'] = urldecode($pdetail["data"]['productsale_cont1']);
			$pdetail["data"]['goods_score'] = intval($pdetail["data"]['goods_score']);
			$plist[] = $pdetail["data"];
			// var_dump($pdetail["data"]);
			$gift_tmp = $model->findByAttributes(array('product_id' => $pdetail["data"]['product_id']));
			// echo $gift_tmp->product_id;
			if (empty($gift_tmp)) {
				$_model = clone $model;
				$_model->setAttributes($pdetail["data"]);
				if ($_model->save()) {
					echo $pdetail["data"]['productsale_name'] . '添加成功';
				}
			}

		}

	}
	//导入促销员礼品数据
	public function actionGiftCXYCheck() {
		$this->token = 'j7291iwusjau1271';
		$query = array();
		$query['user_acct'] = 'lsp';
		$tmp = $this->getProductList($query);
		if ($tmp['success']) {
			$data = $tmp['data']['product_list'];
			foreach ($data as $key => $value) {
				$data[$key]['product_mainpic'] = urldecode($value['product_mainpic']);
				$data[$key]['productsale_name'] = urldecode($value['productsale_name']);
				$data[$key]['productsale_cata3'] = urldecode($value['productsale_cata3']);
				$data[$key]['productsale_subname'] = urldecode($value['productsale_subname']);
				$data[$key]['productsale_subname'] = urldecode($value['productsale_subname']);
			}
		}
		// echo count($data);
		$plist = array();
		$model = new Gifts();
		foreach ($data as $key => $value) {
			// echo $value['product_id'];
			$pdetail = $this->getProductDetail($value['product_id']);
			// var_dump($pdetail["data"]);
			$pdetail["data"]['product_mainpic'] = urldecode($pdetail["data"]['product_mainpic']);
			$pdetail["data"]['productsale_name'] = urldecode($pdetail["data"]['productsale_name']);
			$pdetail["data"]['productsale_subname'] = urldecode($pdetail["data"]['productsale_subname']);
			$pdetail["data"]['productsale_abstr'] = urldecode($pdetail["data"]['productsale_abstr']);
			$pdetail["data"]['productsale_cata1'] = urldecode($pdetail["data"]['productsale_cata1']);
			$pdetail["data"]['productsale_cata2'] = urldecode($pdetail["data"]['productsale_cata2']);
			$pdetail["data"]['productsale_cata3'] = urldecode($pdetail["data"]['productsale_cata3']);
			$pdetail["data"]['productsale_cata4'] = urldecode($pdetail["data"]['productsale_cata4']);
			$pdetail["data"]['productsale_cata5'] = urldecode($pdetail["data"]['productsale_cata5']);
			$pdetail["data"]['productsale_cont1'] = urldecode($pdetail["data"]['productsale_cont1']);
			$pdetail["data"]['goods_score'] = intval($pdetail["data"]['goods_score']);
			$pdetail["data"]['type'] = 0;
			$plist[] = $pdetail["data"];
			// var_dump($pdetail["data"]);
			$gift_tmp = $model->findByAttributes(array('product_id' => $pdetail["data"]['product_id']));
			// echo $gift_tmp->product_id;
			if (empty($gift_tmp)) {
				$_model = clone $model;
				$_model->setAttributes($pdetail["data"]);
				if ($_model->save()) {
					echo $pdetail["data"]['productsale_name'] . '添加成功';
				}
			}

		}

	}

	public function actionTest() {
		// $this->token = 'j7291iwusjau1271'; //促销员产品
		// echo $this->token;
		$query = array();
		$query['sellorder_belong'] = 'yuanlongyd';
		$lists = array();
		for ($i = 0; $i < 20; $i++) {
			// for ($i = 19; $i < 30; $i++) {
			$result = $this->getUserorderlist($query, $i + 1, 50);
			$list = $result['data']['sellorder_list'];
			$lists[] = $list;
		}
		echo json_encode($lists);
	}

	public function actionTestDate() {
		// $this->token = 'j7291iwusjau1271'; //促销员产品
		// echo $this->token;
		$query = array();
//		$query['sellorder_belong'] = 'yuanlongyd';
		//		$query['sellorder_datetime'] = 'yyyy-MM-dd HH:mm:ss~yyyy-MM-dd HH:mm:ss';
		$query['sellorder_datetime'] = urlencode('2016-01-14 00:00:00~2016-01-15 00:00:00');
		$lists = array();
		for ($i = 0; $i < 5; $i++) {
			$result = $this->getUserorderlistByDate($query, $i + 1, 50);
			$list = $result['data']['sellorder_list'];
			$lists[] = $list;
		}
		echo json_encode($lists);
	}

	public function actionTestc() {
		$this->token = 'j7291iwusjau1271'; //促销员产品
		// echo $this->token;
		$query = array();
		$query['sellorder_belong'] = 'yuanlongyd';
		$lists = array();
		for ($i = 0; $i < 10; $i++) {
			// for ($i = 19; $i < 30; $i++) {
			$result = $this->getUserorderlist($query, $i + 1, 50);
			$list = $result['data']['sellorder_list'];
			$lists[] = $list;
		}
		echo json_encode($lists);
	}

	public function actiontestunicom() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'orderId' => '',
			'giftname' => '',
			'giftcount' => '',
			'cost' => '',
			'phone' => '',
			'openid' => '',
			'order_result' => '',
		);
		// $request_type = Yii::app()->request->getRequestType();
		// if ('POST' != $request_type) {
		// 	$this->sendResults($result_array, self::BadRequest);
		// }

		// // Get post data
		// $post_data = Yii::app()->request->getPost('SubmitOrderRequest');
		// if (empty($post_data)) {
		// 	$post_data = file_get_contents("php://input");
		// }
		// $post_array = json_decode($post_data, true);
		$_phone = '18602163052';
		$_giftid = 'P0000001579';
		$_openid = 'okwyOwptIcAZmp4UJN7UdsyItcMs';
		$_giftcount = '1';
		$_points = '300';

		$_cost = $_points * $_giftcount;
		$_type = 'v';
		$order_result = $this->makeOrder($_type, $_phone, $_giftid, $_giftcount, $_cost, $_openid);
		if ((!$order_result) || is_array($order_result)) {
			// $result_array['msg'] = urldecode($order_result['data']);
			$result_array['msg'] = '提交订单失败1';
			// $result_array['more'] = $order_result;
			$result_array['result_msg'] = $order_result;
			$this->sendResults($result_array);
		}
		// echo $order_result;
		if ($order_result != null) {
			// die();
			$result_array['giftname'] = $_giftid;
			$result_array['openid'] = $_openid;
			$result_array['phone'] = $_phone;
			$result_array['cost'] = $_cost;
			$result_array['giftcount'] = $_giftcount;
			$result_array['result'] = self::Success;
			$result_array['order_result'] = $order_result;
			$result_array['msg'] = Yii::t('user', '下单成功!');
		}
		// die();
		$this->sendResults($result_array);

	}

	public function actionTestGetPoint() {
		echo Gifts::model()->getPointsbyid('P0000001579');
	}

	public function actionnh() {
//		$this->redirect('http://letsktv.chinacloudapp.cn/ktv/list.html');
		$result_array = array(
			'result' => self::BadRequest,
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}

		// Get query data
		$_openid = Yii::app()->request->getQuery('openid');
		$_ppp = Yii::app()->request->getQuery('ppp');
		$_openid = empty($_openid) ? '' : trim($_openid);
		$_ppp = empty($_ppp) ? '' : trim($_ppp);
		$new_ppp = md5($_openid . 'ylyato');
		$gift = Gifts::model()->findByPk(16);
		if ($gift->nhcount > 0) {
			$user = PlatformUser::model()->findByAttributes(array('openid' => $_openid));
			if ($user == null) {
				$this->redirect('http://letsktv.chinacloudapp.cn/ktv/gift-detail-nh.html');
//				$user_name = $_openid;
				//				$pass_word =$_openid;
				//				$display_name = '';
				//				$avatar_url = '';
				//				$cid = '';
				//				$type = 0;
				//				$auth_type = 'wechat';
				//				$UserC = new UserController();
				//				$user_new = $UserC->userRegister(array('username' => $user_name, 'password' => $pass_word, 'openid' => $user_name, 'display_name' => $display_name, 'avatar_url' => $avatar_url, 'cid' => $cid), TRUE, $type, TRUE, $auth_type);
				//				$user = $user_new;
			}
			$_userid = $user->id;
			$_order = GiftOrder::model()->findByAttributes(array('userid' => $_userid, 'special' => '2'));
			if ($_order != null) {
				$this->redirect('http://letsktv.chinacloudapp.cn/ktv/list.html');
			}
			if ($new_ppp == $_ppp) {
				$this->redirect('http://letsktv.chinacloudapp.cn/ktv/gift-detail-nh.html');
			} else {
				$this->redirect('http://letsktv.chinacloudapp.cn/ktv/list.html');
			}
		} else {
			$this->redirect('http://letsktv.chinacloudapp.cn/ktv/list.html');
		}

	}

//测试更新订单
	public function actiontestbyordernumber() {
		if ($this->CheckOrder('E0000178337')) {
			echo 'ok';
		} else {
			echo 'nokk';
		}
	}

	protected function CheckOrder($order_no = '') {
		$_userid = Yii::app()->user->getID();
		if ($_userid == null) {
			return false;
		}
		if ($order_no == '') {
			$gift_cout = GiftOrder::model()->getGiftOrderCount($_userid);
			$query = array();
			$query['sellorder_belong'] = 'yuanlongyd' . $_userid;
//		$query['sellorder_datetime'] = urlencode('2016-01-14 00:00:00~2016-01-16 00:00:00');
			$result = $this->getUserorderlist($query, 0, $gift_cout);
			if ($result['success'] == '0') {
				return false;
			} elseif ($result['success'] == '1') {
				$list = $result['data']['sellorder_list'];
//			var_dump($list);
				foreach ($list as $kk => $vv) {
					$data = array(
						'order_status' => urldecode($vv['order_status']),
						'sellorder_receivecell' => $vv['sellorder_receivecell'],
						'sellorder_belong' => $vv['sellorder_belong'],
						'sellorder_datetime' => urldecode($vv['sellorder_datetime']),
						'sellorder_id' => $vv['sellorder_id'],
						'sellorder_remarks' => urldecode($vv['sellorder_remarks']),
						'sellordergoods_goodsid' => $vv['sellordergoods_list'][0]['sellordergoods_goodsid'],
						'sellordergoods_id' => $vv['sellordergoods_list'][0]['sellordergoods_id'],
						'sellordergoods_mainpic' => urldecode($vv['sellordergoods_list'][0]['sellordergoods_mainpic']),
						'sellordergoods_name' => urldecode($vv['sellordergoods_list'][0]['sellordergoods_name']),
						'sellordergoods_num' => $vv['sellordergoods_list'][0]['sellordergoods_num'],
						'sellordergoods_orderid' => $vv['sellordergoods_list'][0]['sellordergoods_orderid'],
						'order_no' => $vv['sellorder_no'],
						'userid' => $_userid,
						"sellorder_custmodno" => $vv["sellorder_custmodno"],
						"sellorder_custmdate" => $vv["sellorder_custmdate"],
						"sellorder_cost" => $vv["sellorder_cost"],
						"sellorder_amount" => $vv["sellorder_amount"],
						"sellorder_syscost" => $vv["sellorder_syscost"],
						"sellorder_sysamount" => $vv["sellorder_sysamount"],
						"sellorder_actax" => $vv["sellorder_actax"],
						"sellorder_freight" => $vv["sellorder_freight"],
						"sellorder_insurance" => $vv["sellorder_insurance"],
						"sellorder_othercosts" => $vv["sellorder_othercosts"],
						"sellorder_discount" => $vv["sellorder_discount"],
						"sellorder_adjamount" => $vv["sellorder_adjamount"],
						"sellorder_total" => $vv["sellorder_total"],
						"sellorder_cashdeduction" => $vv["sellorder_cashdeduction"],
						"sellorder_pointdeduction" => $vv["sellorder_pointdeduction"],
						"sellorder_masadeduction" => $vv["sellorder_masadeduction"],
						"sellorder_payamount" => $vv["sellorder_payamount"],
						"sellorder_receiver" => urldecode($vv["sellorder_receiver"]),
						"sellorder_receivetel" => $vv["sellorder_receivetel"],
						"sellorder_receiveprov" => urldecode($vv["sellorder_receiveprov"]),
						"sellorder_receivecity" => urldecode($vv["sellorder_receivecity"]),
						"sellorder_receivecounty" => urldecode($vv["sellorder_receivecounty"]),
						"sellorder_receiveaddr" => urldecode($vv["sellorder_receiveaddr"]),
						"sellorder_receivepost" => $vv["sellorder_receivepost"],
						"sellorder_receiveemail" => $vv["sellorder_receiveemail"],
						"sellorder_deliverytime" => $vv["sellorder_deliverytime"],
						"sellorder_remindtime" => $vv["sellorder_remindtime"],
						"sellorder_project" => $vv["sellorder_project"],
						"sellorder_itemdesc" => $vv["sellorder_itemdesc"],
						"sellorder_owner" => $vv["sellorder_owner"],
						"sellorder_warename" => $vv["sellorder_warename"],
						"transorder_id" => $vv["transorder_id"],
					);
					if (GiftOrder::model()->updateOrder($data) == false) {
						return false;
					}
				}
			}
			return true;
		} else {
			$query = array();
			$query['sellorder_belong'] = 'yuanlongyd' . $_userid;
			$query['sellorder_id'] = $order_no;
			$result = $this->getUserorderstatuslist($query);
//            var_dump($result);
			//			if($result['success']='0'){
			//				echo '失败';
			//			}else{
			//				var_dump($result[data]);
			//			}
			return true;
		}

	}

	public function actionGiftOrderListNew() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get query data
		$_offset = Yii::app()->request->getQuery('offset');
		$_limit = Yii::app()->request->getQuery('limit');
		$_offset = empty($_offset) ? 0 : intval($_offset);
		$_limit = empty($_limit) ? 100 : intval($_limit);
		$_userid = Yii::app()->user->getID();
		$this->CheckOrder();
		if ($_userid == null) {
			$result_array['msg'] = 'userid error';
			$this->sendResults($result_array);
		}
		$_list = GiftOrder::model()->getOrderList($_userid, $_offset, $_limit);
		if ($_list == NULL) {
			$result_array['msg'] = Yii::t('user', 'No Order data!');
			$result_array['result'] = self::ListNull;
			$this->sendResults($result_array);
		}
		$result_array['msg'] = Yii::t('gift', 'Get Order List Success');
		$result_array['result'] = self::Success;
		$result_array['list'] = $_list;
		$result_array['total'] = count($_list);
		$this->sendResults($result_array);
	}

	public function actionOrderDetail() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		$_orderid = Yii::app()->request->getQuery('orderid');
		if ($_orderid == null) {
			$result_array['msg'] = Yii::t('gift', 'No Orderid');
			$this->sendResults($result_array);
		}
		$_userid = Yii::app()->user->getID();
		$orderinfo = GiftOrder::model()->getOrderInfoByid($_orderid, $_userid);
		if ($orderinfo == null) {
			$result_array['msg'] = Yii::t('gift', 'No Order Info');
			$this->sendResults($result_array);
		}
		$result_array['data'] = $orderinfo;
		$result_array['msg'] = Yii::t('gift', 'Get Order Info Success');
		$result_array['result'] = self::Success;
		$this->sendResults($result_array);
	}

	public function actiongiftorderlist() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'total' => 0,
			'list' => array(),
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		//$_orderlists = Yii::app()->request->getQuery('orderlists');

		$result_array['msg'] = Yii::t('user', 'No Order data!');
		$_userid = Yii::app()->user->getId();
		if (empty($_userid) || !isset($_userid)) {
			$result_array['msg'] = 'user data error';
			$this->sendResults($result_array);
		}
		$_user = PlatformUser::model()->findByPk($_userid);
		$profile = unserialize($_user->profile_data);
		if (!empty($profile['giftorders']) && isset($profile['giftorders'])) {
//			$_orderlists = $profile['giftorders'];
		} else {
			$result_array['msg'] = 'gift orders is null';
			$this->sendResults($result_array);
		}
		$_orderlists = $profile['giftorders'];
		$search_array = $this->getGiftlistByNums($_orderlists);
		if (!empty($search_array)) {
			// get XTV array
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'GiftOrder list success!');
			$result_array['list'] = $search_array;
			$result_array['total'] = count($search_array);
		} else {
			$result_array['msg'] = Yii::t('user', 'No List data!');
			$result_array['result'] = self::ListNull;
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function getGiftlistByNums($orderlists = '') {
		// $orderlists = 'YC15120400031,YC15120400032';
		// $orderlists = ",YC15120700012";
		// $orderlists = ",YC15121700012";
		// $orderlists = "YC15120700033,YC15120700014";
		$sellorder_nos = explode(',', $orderlists);
		$lists = array();
		$query = array();
		$_userid = Yii::app()->user->getId();
		$query['sellorder_belong'] = 'yuanlongyd' . $_userid;
		foreach ($sellorder_nos as $key => $value) {
			if ($value != '') {
				$query['sellorder_no'] = $value;
				$result = $this->getUserorderlist($query);
				// var_dump($result);die();
				if ($result['success'] == '0') {

				} elseif ($result['success'] == '1') {
					if ($result['data']['ordercount'] > 0) {
						$listtmp = $result['data']['sellorder_list'][0];
						$lists[] = array(
							"sellorder_id" => $listtmp['sellorder_id'],
							"sellorder_no" => $listtmp['sellorder_no'],
							"sellorder_datetime" => urldecode($listtmp['sellorder_datetime']),
							"order_status" => urldecode($listtmp['order_status']),
							"sellordergoods_name" => urldecode($listtmp['sellordergoods_list'][0]['sellordergoods_name']),
							"sellordergoods_mainpic" => urldecode($listtmp['sellordergoods_list'][0]['sellordergoods_mainpic']),
							"sellordergoods_num" => $listtmp['sellordergoods_list'][0]['sellordergoods_num'],
							"sellordergoods_unitpoints" => $listtmp['sellordergoods_list'][0]['sellordergoods_unitpoints'],
							"sellordergoods_totalpoints" => $listtmp['sellordergoods_list'][0]['sellordergoods_totalpoints'],
						);
					}

					// $lists[]=$listtmp;
				}
			}
		}
		$query['sellorder_belong'] = 'yuanlongyd';
		foreach ($sellorder_nos as $key => $value) {
			if ($value != '') {
				$query['sellorder_no'] = $value;
				$result = $this->getUserorderlist($query);
				// var_dump($result);die();
				if ($result['success'] == '0') {

				} elseif ($result['success'] == '1') {
					if ($result['data']['ordercount'] > 0) {
						$listtmp = $result['data']['sellorder_list'][0];
						$lists[] = array(
							"sellorder_id" => $listtmp['sellorder_id'],
							"sellorder_no" => $listtmp['sellorder_no'],
							"sellorder_datetime" => urldecode($listtmp['sellorder_datetime']),
							"order_status" => urldecode($listtmp['order_status']),
							"sellordergoods_name" => urldecode($listtmp['sellordergoods_list'][0]['sellordergoods_name']),
							"sellordergoods_mainpic" => urldecode($listtmp['sellordergoods_list'][0]['sellordergoods_mainpic']),
							"sellordergoods_num" => $listtmp['sellordergoods_list'][0]['sellordergoods_num'],
							"sellordergoods_unitpoints" => $listtmp['sellordergoods_list'][0]['sellordergoods_unitpoints'],
							"sellordergoods_totalpoints" => $listtmp['sellordergoods_list'][0]['sellordergoods_totalpoints'],
						);
					}

					// $lists[]=$listtmp;
				}
			}
		}

// die();
		// echo json_encode($lists);die();

		return $lists;
	}

	public function actiongiftdetail() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		$result_array['msg'] = Yii::t('gift', 'Gift ID error');
		$_giftid = Yii::app()->request->getQuery('giftid');
		$gift_info = Gifts::model()->giftdetail($_giftid);
//        var_dump($gift_info);die();
		if ($gift_info != null) {
			$result_array['result'] = 0;
			$result_array['msg'] = Yii::t('user', 'Get Gift Detail success');
			$result_array['data'] = $gift_info;
		}
		$this->sendResults($result_array);

	}

	public function actionGiftlist($value = '') {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'total' => 0,
			'list' => array(),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		$result_array['msg'] = Yii::t('gift', 'No Gift data!');
		// $type = isset($post_array['type']) ? $post_array['type'] : '';
		$_offset = Yii::app()->request->getQuery('offset');
		$_limit = Yii::app()->request->getQuery('limit');
		$_type = Yii::app()->request->getQuery('type');
		$_offset = empty($_offset) ? 0 : intval($_offset);
		$_limit = empty($_limit) ? 100 : intval($_limit);

		$search_array = $this->getgiftlist($_type, $_offset, $_limit);

		if (!empty($search_array)) {
			// get XTV array
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('gift', 'Get gift list success!');
			$result_array['list'] = $search_array;
			$result_array['total'] = count($search_array);
		} else {
			$result_array['result'] = self::ListNull;
			$result_array['msg'] = 'List is Null';
		}
		$this->sendResults($result_array);

	}

	public function actionorderreal($value = '') {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),

		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('SubmitOrderRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		$post_array = json_decode($post_data, true);
		$_name = isset($post_array['sname']) ? trim($post_array['sname']) : '';
		$_phone = isset($post_array['stel']) ? trim($post_array['stel']) : '';
		$_address = isset($post_array['address']) ? trim($post_array['address']) : '';
		$_giftid = isset($post_array['giftid']) ? trim($post_array['giftid']) : '';
		$_giftcount = isset($post_array['giftcount']) ? strval($post_array['giftcount']) : '';
		$_prov = isset($post_array['prov']) ? trim($post_array['prov']) : '';
		$_city = isset($post_array['city']) ? trim($post_array['city']) : '';
		$_county = isset($post_array['county']) ? trim($post_array['county']) : '';
		if ($_name == '' || $_phone == "" || $_address == "" || $_prov == "" || $_city == "" || $_county == "" || $_giftcount == "" || $_giftid == "") {
			$result_array['msg'] = 'order detail wrong';
			$this->sendResults($result_array);
		}
		$gift = Gifts::model()->findByAttributes(array('product_id' => $_giftid));
		if (is_object($gift)) {
			$_points = $gift->productsale_points;
		} else {
			$result_array['msg'] = 'gift is null';
			$this->sendResults($result_array);
		}
		$_addr = array('city' => $_city, 'prov' => $_prov, 'county' => $_county, 'address' => $_address);
		$_cost = $_points * $_giftcount;
		$_type = 'r';
		$order_result = $this->makeOrder($_type, $_phone, $_giftid, $_giftcount, $_cost, 0, $_name, $_addr);

		if (!$order_result || is_array($order_result)) {
			$result_array['msg'] = '提交订单失败';
			if (is_array($order_result)) {
				$result_array['msg'] = urldecode($order_result['data']);
			}
			$result_array['result_msg'] = $order_result;
			$this->sendResults($result_array);
		}

		// echo $order_result;
		if ($order_result != null) {
			// die();
			$result_array['giftname'] = $_giftid;
			$result_array['address'] = $_address;
			$result_array['phone'] = $_phone;
			$result_array['cost'] = $_cost;
			$result_array['name'] = $_name;
			$result_array['city'] = $_city;
			$result_array['prov'] = $_prov;
			$result_array['county'] = $_county;
			$result_array['giftcount'] = $_giftcount;
			$result_array['result'] = self::Success;
			$result_array['order_result'] = $order_result;
			$result_array['msg'] = Yii::t('user', '下单成功!');
		}
		// die();
		$this->sendResults($result_array);

	}

	public function actionOrderRealCXY() {
		$this->token = 'j7291iwusjau1271';
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),

		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('SubmitOrderRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		$post_array = json_decode($post_data, true);
		$_name = isset($post_array['sname']) ? $post_array['sname'] : '';
		$_phone = isset($post_array['stel']) ? $post_array['stel'] : '';
		$_address = isset($post_array['address']) ? $post_array['address'] : '';
		$_giftid = isset($post_array['giftid']) ? $post_array['giftid'] : '';
		$_giftcount = isset($post_array['giftcount']) ? $post_array['giftcount'] : '';
		$_points = isset($post_array['points']) ? $post_array['points'] : '';
		$_prov = isset($post_array['prov']) ? $post_array['prov'] : '';
		$_city = isset($post_array['city']) ? $post_array['city'] : '';
		$_county = isset($post_array['county']) ? $post_array['county'] : '';

		$_addr = array('city' => $_city, 'prov' => $_prov, 'county' => $_county, 'address' => $_address);
		$_cost = $_points * $_giftcount;
		$_type = 'c';
		$order_result = $this->makeOrder($_type, $_phone, $_giftid, $_giftcount, $_cost, 0, $_name, $_addr);
		if (!$order_result || is_array($order_result)) {
			$result_array['msg'] = '提交订单失败';
			$result_array['result'] = $order_result;
			$this->sendResults($result_array);
		}

		// echo $order_result;
		if ($order_result != null) {
			// die();
			$result_array['giftname'] = $_giftid;
			$result_array['address'] = $_address;
			$result_array['phone'] = $_phone;
			$result_array['cost'] = $_cost;
			$result_array['name'] = $_name;
			$result_array['giftcount'] = $_giftcount;
			$result_array['result'] = self::Success;
			$result_array['order_result'] = $order_result;
			$result_array['msg'] = Yii::t('user', '下单成功!');
		}
		// die();
		$this->sendResults($result_array);

	}
	public function actionordervirtualCXY() {
		$this->token = 'j7291iwusjau1271';
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
			die();
		}

		// Get post data
		$post_data = Yii::app()->request->getPost('SubmitOrderRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		$post_array = json_decode($post_data, true);
		$_phone = isset($post_array['mobile']) ? $post_array['mobile'] : '';
		$_giftid = isset($post_array['giftid']) ? $post_array['giftid'] : '';
		$_giftcount = isset($post_array['giftcount']) ? $post_array['giftcount'] : '';
		if ($_phone == "" || $_giftid == "" || $_giftcount == "") {
			$result_array['msg'] = 'order detail wrong';
			unset(Yii::app()->session['buying']);
			$this->sendResults($result_array);
			die();
		}
		$gift = Gifts::model()->findByAttributes(array('product_id' => $_giftid));
		if (is_object($gift)) {
			$_points = $gift->productsale_points;
		} else {
			$result_array['msg'] = 'gift is null';
			unset(Yii::app()->session['buying']);
			$this->sendResults($result_array);
			die();
		}

		$_cost = $_points * $_giftcount;
		$_type = 'cv';
		$order_result = $this->makeOrder($_type, $_phone, $_giftid, $_giftcount, $_cost);

		if ((!$order_result) || is_array($order_result)) {

			$result_array['msg'] = urldecode($order_result['data']);
			$result_array['result_msg'] = $order_result;
			unset(Yii::app()->session['buying']);
			$this->sendResults($result_array);
			die();
		}
		// echo $order_result;
		if ($order_result != null) {

			$result_array['giftid'] = $_giftid;
			$result_array['phone'] = $_phone;
			$result_array['cost'] = $_cost;
			$result_array['giftcount'] = $_giftcount;
			$result_array['result'] = self::Success;
			$result_array['order_result'] = $order_result;
			$result_array['msg'] = Yii::t('user', '下单成功!');
		}
		// die();
		unset(Yii::app()->session['buying']);
		$this->sendResults($result_array);
		die();
	}

	public function actionordervirtual($value = '') {

		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
			die();
		}

		if (!isset(Yii::app()->session['buying'])) {
			$nnow = time();
			Yii::app()->session['buying'] = $nnow;
		} else {
			$result_array['msg'] = 'buying';
			$result_array['result'] = '410';
			$this->sendResults($result_array);
			die();
		}

		// Get post data
		$post_data = Yii::app()->request->getPost('SubmitOrderRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		$post_array = json_decode($post_data, true);
		$_phone = isset($post_array['mobile']) ? $post_array['mobile'] : '';
		$_giftid = isset($post_array['giftid']) ? $post_array['giftid'] : '';
		$_giftcount = isset($post_array['giftcount']) ? $post_array['giftcount'] : '';
		$_nh = isset($post_array['nh']) ? $post_array['nh'] : '';
		if ($_phone == "" || $_giftid == "" || $_giftcount == "") {
			$result_array['msg'] = 'order detail wrong';
			unset(Yii::app()->session['buying']);
			$this->sendResults($result_array);
			die();
		}
		$gift = Gifts::model()->findByAttributes(array('product_id' => $_giftid));
		if (is_object($gift)) {
			$_points = $gift->productsale_points;
		} else {
			$result_array['msg'] = 'gift is null';
			unset(Yii::app()->session['buying']);
			$this->sendResults($result_array);
			die();
		}

		$_cost = $_points * $_giftcount;
		$_type = 'v';
		$_userid = Yii::app()->user->getId();
		$userinfo = PlatformUser::model()->findByAttributes(array('id' => $_userid));
		if ($userinfo != null) {
//            if ($userinfo->giftorderstatus == '1') {
			//                $result_array['msg'] = 'order status error';
			//                unset(Yii::app()->session['buying']);
			//                $this->sendResults($result_array);
			//            } elseif ($userinfo->giftorderstatus == '0') {
			//                $userinfo->giftorderstatus = '1';
			//                if ($userinfo->save()) {
			//
			//                } else {
			//                    $result_array['msg'] = 'order status Error';
			//                    unset(Yii::app()->session['buying']);
			//                    $this->sendResults($result_array);
			//                }
			//            }
		} else {
			$result_array['msg'] = 'userid_error';
			unset(Yii::app()->session['buying']);
			$this->sendResults($result_array);
			die();
		}
		$order_result = $this->makeOrder($_type, $_phone, $_giftid, $_giftcount, $_cost, $_nh);

		if ((!$order_result) || is_array($order_result)) {
			$userinfo->giftorderstatus = '0';
			$userinfo->save();
			$result_array['msg'] = urldecode($order_result['data']);
			$result_array['result_msg'] = $order_result;
			unset(Yii::app()->session['buying']);
			$this->sendResults($result_array);
			die();
		}
		// echo $order_result;
		if ($order_result != null) {
			$userinfo->giftorderstatus = '0';
			$userinfo->save();
			// die();
			$result_array['giftid'] = $_giftid;
			$result_array['phone'] = $_phone;
			$result_array['cost'] = $_cost;
			$result_array['giftcount'] = $_giftcount;
			$result_array['result'] = self::Success;
			$result_array['order_result'] = $order_result;
			$result_array['msg'] = Yii::t('user', '下单成功!');
		}
		// die();
		unset(Yii::app()->session['buying']);
		$this->sendResults($result_array);
		die();
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
		// echo $post_url;
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
		//  curl_setopt($ch,CURLOPT_HEADER, false);

		$output = curl_exec($ch);

		curl_close($ch);
		return $output;
	}

	protected function getgiftlist($type = '', $offset = '', $limit = '') {
		$gifts = array();
		if ($type == 1) {
			$gifts = Gifts::model()->giftlist(1, $offset, $limit);

		} elseif ($type == 2) {
			$gifts = Gifts::model()->giftlist(0, $offset, $limit);
		}
		return $gifts;
	}

	protected function makeOrder($type, $phone, $giftid, $count, $cost, $nh = 0, $name = '', $address = array()) {
		if ($type == 'v') {
			$_userid = Yii::app()->user->getId();
			$userinfo = PlatformUser::model()->findByPk($_userid);
			$userpoints = UserPoints::model()->findByAttributes(array('user_id' => $_userid));
			if ($nh == 1) {
				$gg = Gifts::model()->findByAttributes(array('product_id' => $giftid, 'is_nh' => '1'));
				if (!empty($gg)) {
					if ($gg->nhcount > 0) {
						$gg->nhcount = $gg->nhcount - 1;
						$gg->save();
					} else {
						return array('msg' => 'count is 0');
					}
				} else {
					return array('msg' => 'nh is over');
				}
			} else {
				if (is_null($userpoints)) {
					$userpoints = new UserPoints();
					$userpoints->user_id = $_userid;
					$userpoints->points = 0;
					$userpoints->save();
				}
				$userpoints = UserPoints::model()->findByAttributes(array('user_id' => $_userid));
				if ($userpoints->points - $cost < 0) {
					return array('msg' => '积分不足');
				}
				$userpoints->points = $userpoints->points - $cost;
				$userpoints->save();
			}

			$param = array();
			$param['sellorder_belong'] = 'yuanlongyd' . $_userid;
			$param['sellorder_receivecell'] = $phone;
			$param['sellorder_protid'] = $giftid;
			if (!isset(Yii::app()->session['buying']) || time() - Yii::app()->session['buying'] > 60) {
				$userpoints = UserPoints::model()->findByAttributes(array('user_id' => $_userid));
				$userpoints->points = $userpoints->points + $cost;
				$userpoints->save();
				return array('msg' => 'much times buy');
			}
			$GiftOrder = new GiftOrder();
			$GiftOrder->userid = $_userid;
			$GiftOrder->sellorder_belong = 'yuanlongyd' . $_userid;
			$GiftOrder->save();
			$result = $this->makeOrderVirtual($param);
			if ($result['success'] == '0') {
				if ($nh == 1) {

				} else {
					$userpoints = UserPoints::model()->findByAttributes(array('user_id' => $_userid));
					$userpoints->points = $userpoints->points + $cost;
					$userpoints->save();
				}
				return $result;

			} elseif ($result['success'] == '1') {
				$tade_no = $result['data']['sellorder_no'];
//                $GiftOrder = new GiftOrder();
				$order_array = array(
					'userid' => $_userid,
					'order_no' => $tade_no,
					'is_check' => '1',
					'sellorder_belong_user' => $param['sellorder_belong'],
				);
				if ($nh == 1) {
					$order_array['special'] = '2';
				}
				$GiftOrder->setAttributes($order_array);
				if ($GiftOrder->save()) {
					Gifts::model()->count_jian($giftid);
				}
				$this->CheckOrder();
				if ($nh == 1) {

				} else {
					$this->sendPointChangeMessage($userinfo, -$cost, $userpoints->points, '您好，您已成功使用积分兑换礼品。', '成功兑换礼品', '欢迎您继续通过邀请好友或者参加各种夜点活动获得更多积分，更多精彩好礼等您来拿！');
				}
			}
		} elseif ($type == 'r') {
			$_userid = Yii::app()->user->getId();
			$userinfo = PlatformUser::model()->findByPk($_userid);
			$userpoints = UserPoints::model()->findByAttributes(array('user_id' => $_userid));
			if (is_null($userpoints)) {
				$userpoints = new UserPoints();
				$userpoints->user_id = $_userid;
				$userpoints->points = 0;
				$userpoints->save();
			}
			$userpoints = UserPoints::model()->findByAttributes(array('user_id' => $_userid));
			if ($userpoints->points - $cost < 0) {
				return array('msg' => '积分不足');
			}
			$userpoints->points = $userpoints->points - $cost;
			$userpoints->save();
			$param = array();
			$goods_list = array();
			$good = array();
			$good['product_id'] = $giftid;
			$good['product_num'] = $count;
			$goods_list[] = $good;
			$param['sellorder_belong'] = 'yuanlongyd' . $_userid;
			$param['sellorder_receiver'] = $name;
			$param['sellorder_receivecell'] = $phone;
			$param['sellorder_receiveprov'] = $address['prov'];
			$param['sellorder_receivecity'] = $address['city'];
			$param['sellorder_receivecounty'] = $address['county'];
			$param['sellorder_receiveaddr'] = $address['address'];
			$param['goods_list'] = $goods_list;
			$result = $this->makeOrderReal($param);
			// var_dump($result);die();
			if ($result['success'] == '0') {
				$userpoints = UserPoints::model()->findByAttributes(array('user_id' => $_userid));
				$userpoints->points = $userpoints->points + $cost;
				$userpoints->save();
				return $result;
			} elseif ($result['success'] == '1') {
				$tade_no = $result['data']['sellorder_no'];
				$GiftOrder = new GiftOrder();
				$order_array = array('userid' => $_userid, 'order_no' => $tade_no, 'is_check' => '1');
				$GiftOrder->setAttributes($order_array);
				if ($GiftOrder->save()) {
					Gifts::model()->count_jian($giftid);
				}
				$this->CheckOrder();
				$this->sendPointChangeMessage($userinfo, -$cost, $userpoints->points, '您好，您已成功使用积分兑换礼品。', '成功兑换礼品', '欢迎您继续通过邀请好友或者参加各种夜点活动获得更多积分，更多精彩好礼等您来拿！');
			}
		} elseif ($type == 'c') {
			$param = array();
			$goods_list = array();
			$good = array();
			$good['product_id'] = $giftid;
			$good['product_num'] = $count;
			$goods_list[] = $good;
			$param['sellorder_belong'] = 'yuanlongyd';
			$param['sellorder_receiver'] = $name;
			$param['sellorder_receivecell'] = $phone;
			$param['sellorder_receiveprov'] = $address['prov'];
			$param['sellorder_receivecity'] = $address['city'];
			$param['sellorder_receivecounty'] = $address['county'];
			$param['sellorder_receiveaddr'] = $address['address'];
			$param['goods_list'] = $goods_list;
			$result = $this->makeOrderReal($param);
			// var_dump($result);die();
			if ($result['success'] == '0') {
				return $result;
				// return false;
			} elseif ($result['success'] == '1') {
				$tade_no = $result['data']['sellorder_no'];
			}
		} elseif ($type == 'cv') {

			$param = array();
			$param['sellorder_belong'] = 'yuanlongyd';
			$param['sellorder_receivecell'] = $phone;
			$param['sellorder_protid'] = $giftid;

			$result = $this->makeOrderVirtual($param);
			if ($result['success'] == '0') {

				return $result;

			} elseif ($result['success'] == '1') {
				$tade_no = $result['data']['sellorder_no'];
			}
		}
		return $tade_no;
	}

	protected function getUrlCN($svname, $param) {
		$base = 'http://ws9.ylytgift.com/YDService.ashx';
//        $base = 'http://210.51.10.14:9015/YDService.ashx';
		$url = '?svname=' . $svname;
		$url .= '&svpa=' . $this->getsvpastrCN($param);
		return $base . $url;
	}

	protected function getsvpastrCN($param) {
		$data = array();
		$data['token'] = $this->token;
		$data['check'] = $this->getcheckdataCN($param);
		$data['param'] = $this->getparamdataCN($param);
		$svpastr = json_encode($data);
		return $svpastr;
	}

	protected function getcheckdataCN($param = '') {
		$chkstr = base64_encode(strtoupper(md5(strtoupper(md5($this->JSON($param))))));
		// echo $chkstr;
		return $chkstr;
	}

	protected function getparamdataCN($param = '') {
		foreach ($param as $key => $value) {
			if (!is_array($param[$key])) {

				$param[$key] = urlencode($value);
			} elseif (is_array($param[$key])) {
				foreach ($param[$key] as $k => $v) {
					if (!is_array($param[$key][$k])) {
						$param[$key][$k] = urlencode($v);
					} else {
						foreach ($param[$key][$k] as $kk => $vv) {
							$param[$key][$k][$kk] = urlencode($vv);
						}
					}

				}
			}
		}
		return json_encode($param);
	}

	// 根据产品ID获取产品详情
	public function getProductDetail($product_id = '') {
		$query = array();
		$query['user_acct'] = 'lp';
		$query['product_id'] = $product_id;
		$param = array();
		$param['query'] = $query;
		$url = $this->getUrlCN('Query_Protdetail', $param);
		return $this->get_curl_json($url);
	}

	// 获取产品列表
	public function getProductList($query, $page = '', $perpage_num = '', $order = '') {
		$param = array();
		if ($order != '') {
			$param['order'] = $order;
		}
		if ($page != '') {
			$param['page'] = $page;
		}
		if ($perpage_num != '') {
			$param['perpage_num'] = $perpage_num;
		}
		$param['query'] = $query;
		$url = $this->getUrlCN('Query_Protlist', $param);
		// echo $url;
		return $this->get_curl_json($url);
	}

	// 获取产品列表
	public function getAreaList($query, $page = '', $perpage_num = '', $order = '') {
		$param = array();
		if ($order != '') {
			$param['order'] = $order;
		}
		if ($page != '') {
			$param['page'] = $page;
		}
		if ($perpage_num != '') {
			$param['perpage_num'] = $perpage_num;
		}
		$param['query'] = $query;
		$url = $this->getUrlCN('Query_Regionlist', $param);
		// echo $url;
		return $this->get_curl_json($url);
	}

	// 实物下单接口
	public function makeOrderReal($param) {
		// $url = $this->getUrl('Creat_Userorder', $param);
		$url = $this->getUrlCN('Creat_Userorder', $param);

		return $this->get_curl_json($url);

	}

	// 虚拟下单接口
	public function makeOrderVirtual($param) {

		$url = $this->getUrlCN('Creat_UserorderVirtual', $param);
		// echo $url;die();
		return $this->get_curl_json($url);
	}

	//查询订单接口
	public function getUserorderstatuslist($query, $page = '', $perpage_num = '', $order = '') {
		$param = array();
		if ($order != '') {
			$param['order'] = $order;
		}
		if ($page != '') {
			$param['page'] = $page;
		}
		if ($perpage_num != '') {
			$param['perpage_num'] = $perpage_num;
		}
		$param['query'] = $query;
		$url = $this->getUrlCN('Query_Userorderstatuslist', $param);
		// echo $url;
		return $this->get_curl_json($url);
	}

	// 查询所有订单接口
	public function getUserorderlist($query, $page = '', $perpage_num = '', $order = '') {
		$param = array();
		if ($order != '') {
			$param['order'] = $order;
		}
		if ($page != '') {
			$param['page'] = $page;
		}
		if ($perpage_num != '') {
			$param['perpage_num'] = $perpage_num;
		}
		$param['query'] = $query;
		$url = $this->getUrlCN('Query_Userorderlist', $param);
		// echo $url;
		return $this->get_curl_json($url);
	}

	// 通用的一些方法

	protected function get_curl_json($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			print_r(curl_error($ch));
		}
		curl_close($ch);
		return json_decode($result, TRUE);
	}

	public function encodeOperations(&$array, $function, $tocode = false, $oldcode = false, $apply_to_keys_also = false) {
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$this->encodeOperations($array[$key], $function, $apply_to_keys_also);
			} else {
				if ($tocode && $oldcode) {
					if (function_exists(mb_convert_encoding)) {
						$value = mb_convert_encoding($value, $tocode, $oldcode);
					} else {
						return "error";
					}
				}

				$array[$key] = $function($value);
			}

			if ($apply_to_keys_also && is_string($key)) {
				$new_key = $function($key);
				if ($new_key != $key) {
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
		return $array;
	}

	public function JSON($array) {

		// echo 'this is JSON';
		$this->encodeOperations($array, 'urlencode', true);
		$json = json_encode($array);
		return urldecode($json);
	}

	// 获取产品列表
	public function test1() {
		$query = array();
		$query['user_acct'] = 'lsp';
		return $this->getProductList($query);
	}

	// 测试实物下单
	public function test2() {
		$param = array();
		$goods_list = array();
		$good = array();
		$good['product_id'] = 'P0000001587';
		$good['product_num'] = '5';
		$goods_list[] = $good;
		$good['product_id'] = 'P0000001584';
		$good['product_num'] = '2';
		$goods_list[] = $good;
		$param['sellorder_belong'] = 'yuanlongyd';
		$param['sellorder_receiver'] = '王大力';
		$param['sellorder_receivetel'] = '13811111112';
		$param['sellorder_receivecell'] = '13811111112';
		$param['sellorder_receiveprov'] = '北京市';
		$param['sellorder_receivecity'] = '市辖区';
		$param['sellorder_receivecounty'] = '东城区';
		$param['sellorder_receiveaddr'] = '东西路口南102号';
		$param['goods_list'] = $goods_list;
		return $this->makeOrder($param);
	}

	// 测试获取地区列表接口
	public function test3() {
		$query = array();
		$query['region_type'] = 'county';
		$query['region_id'] = 'cities_110200';
		return $this->getAreaList($query);
	}

	// 测试虚拟下单
	public function test4() {
		$param = array();
		$param['sellorder_belong'] = urlencode('yuanlongyd');
		$param['sellorder_receivecell'] = '15910536601';
		$param['sellorder_protid'] = 'P0000001571';
		return $this->makeOrderVirtual($param);
	}

	//测试获取产品信息接口
	public function test5() {
		return $this->getProductDetail('P0000001578');
	}

	// 测试订单列表接口
	public function test6() {
		$query = array();
		$query['sellorder_belong'] = 'yuanlongyd';
		return $this->getUserorderlist($query);
	}

	// 测试获取订单状态接口
	public function test7() {
		$query = array();
		$query['sellorder_belong'] = 'yuanlongyd';

		$query['sellorder_id'] = 'E0000178202';
		return $this->getUserorderstatuslist($query);
	}
}
