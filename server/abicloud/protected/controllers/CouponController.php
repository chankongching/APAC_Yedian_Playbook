<?php

class CouponController extends ApiController {
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

	public function actionlist() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
			die();
		}
		// Get query data
		$_offset = Yii::app()->request->getQuery('offset');
		$_limit = Yii::app()->request->getQuery('limit');
		$_offset = empty($_offset) ? 0 : intval($_offset);
		$_limit = empty($_limit) ? 100 : intval($_limit);
		$_userid = Yii::app()->user->getID();
		if ($_userid == null) {
			$result_array['msg'] = 'userid error';
			$this->sendResults($result_array);
			die();
		}
		Coupon::model()->checkExpire($_userid);
		$_list = Coupon::model()->getCouponList($_userid, $_offset, $_limit);

		if ($_list == NULL) {
			$result_array['msg'] = Yii::t('coupon', 'No Coupon data!');
			$result_array['result'] = self::ListNull;
			$this->sendResults($result_array);
			die();
		}
		$result_array['msg'] = Yii::t('coupon', 'Get Coupon List Success');
		$result_array['result'] = self::Success;
		$result_array['list'] = $_list;
		$result_array['total'] = count($_list);
		$this->sendResults($result_array);
	}

	public function actionavailablelist() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
			die();
		}
		// Get query data
		$_offset = Yii::app()->request->getQuery('offset');
		$_limit = Yii::app()->request->getQuery('limit');
		$_offset = empty($_offset) ? 0 : intval($_offset);
		$_limit = empty($_limit) ? 100 : intval($_limit);
		$_userid = Yii::app()->user->getID();
		if ($_userid == null) {
			$result_array['msg'] = 'userid error';
			$this->sendResults($result_array);
			die();
		}
		Coupon::model()->checkExpire($_userid);
		if (Coupon::model()->hasusedtwocoupon($_userid)) {
			$result_array['msg'] = Yii::t('coupon', 'No Coupon data!');
			$result_array['result'] = self::ListNull;
			$result_array['wrong_msg'] = '超过今日使用上限';
			$this->sendResults($result_array);
			die();
		} else {
		}
		$_list = Coupon::model()->getAvailableCouponList($_userid, $_offset, $_limit);

		if ($_list == NULL) {
			$result_array['msg'] = Yii::t('coupon', 'No Coupon data!');
			$result_array['result'] = self::ListNull;
			$result_array['wrong_msg'] = '暂无可用兑酒券';
			$this->sendResults($result_array);
			die();
		}
		$result_array['msg'] = Yii::t('coupon', 'Get Coupon List Success');
		$result_array['result'] = self::Success;
		$result_array['list'] = $_list;
		$result_array['total'] = count($_list);
		$this->sendResults($result_array);
	}

	public function actiondetail() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
			die();
		}
		$_couponid = Yii::app()->request->getQuery('couponid');
		if ($_couponid == null) {
			$result_array['msg'] = Yii::t('coupon', 'No couponid');
			$this->sendResults($result_array);
			die();
		}
		$_userid = Yii::app()->user->getID();
		$Couponinfo = Coupon::model()->getInfoByid($_couponid, $_userid);
		if ($Couponinfo == null) {
			$result_array['msg'] = Yii::t('coupon', 'No Coupon Info');
			$this->sendResults($result_array);
			die();
		}
		$result_array['data'] = $Couponinfo;
		$result_array['msg'] = Yii::t('coupon', 'Get Coupon Info Success');
		$result_array['result'] = self::Success;
		$this->sendResults($result_array);
	}

	public function actioncheckstatus() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'session' => $_SESSION,
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
			die();
		}
		$_couponid = Yii::app()->request->getQuery('couponid');
		if ($_couponid == null) {
			$result_array['msg'] = Yii::t('coupon', 'No couponid');
			$this->sendResults($result_array);
			die();
		}
		$_userid = Yii::app()->user->getID();
		$couponStatus = Coupon::model()->getStatus($_userid, $_couponid);
		if ($couponStatus != null) {
			$result_array['status'] = intval($couponStatus);
			$result_array['msg'] = Yii::t('coupon', 'Get Coupon Status Success');
			$result_array['result'] = self::Success;
		} else {
			$result_array['msg'] = Yii::t('coupon', 'No Coupon Info');
		}
		$this->sendResults($result_array);
	}

	public function actiongetcouponbyevents() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
			die();
		}
		$userid = Yii::app()->user->getID();
		$getresult = Coupon::model()->getCouponByEvents($userid);
		if ($getresult['result'] === 0) {
			$result_array['result'] = self::Success;
			$result_array['msg'] = 'get Coupon Success';
			$result_array['coupon'] = array('beer_type' => $getresult['beer_type'], 'count' => intval($getresult['count']), 'type' => intval($getresult['type']), 'coupon_name' => $getresult['name'], 'expire_time' => intval($getresult['expire_time']));
		} elseif ($getresult['result'] === 1) {
			$result_array['msg'] = 'get Coupon Failed,Coupon has Got';
			$result_array['coupon'] = $getresult['coupon'];
		}
		$this->sendResults($result_array);
	}

	public function actiongetcouponstatusbyevents() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
			die();
		}
		$userid = Yii::app()->user->getID();
		$getresult = Coupon::model()->getCouponStatusByEvents($userid);
		if ($getresult['result'] === 1) {
			$result_array['result'] = 1;
			$result_array['msg'] = 'has coupon';
			$result_array['coupon'] = $getresult;
		} elseif ($getresult['result'] === 0) {
			$result_array['result'] = 0;
			$result_array['msg'] = 'not has coupon';
			$result_array['coupon'] = $getresult['coupon'];
		}
		$this->sendResults($result_array);
	}

}