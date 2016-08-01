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
//		if (Coupon::model()->hasusedtwocoupon($_userid)) {
		//			$result_array['msg'] = Yii::t('coupon', 'No Coupon data!');
		//			$result_array['result'] = self::ListNull;
		//			$result_array['wrong_msg'] = '超过今日使用上限';
		//			$this->sendResults($result_array);
		//			die();
		//		} else {
		//            $_list = Coupon::model()->getAvailableCouponList($_userid, $_offset, $_limit);
		//
		//            if ($_list == NULL) {
		//                $result_array['msg'] = Yii::t('coupon', 'No Coupon data!');
		//                $result_array['result'] = self::ListNull;
		//                $result_array['wrong_msg'] = '暂无可用兑酒券';
		//                $this->sendResults($result_array);
		//                die();
		//            }
		//            $result_array['msg'] = Yii::t('coupon', 'Get Coupon List Success');
		//            $result_array['result'] = self::Success;
		//            $result_array['list'] = $_list;
		//            $result_array['total'] = count($_list);
		//            $this->sendResults($result_array);
		//		}
		$couponList = Coupon::model()->getAvailableCouponList($_userid, $_offset, $_limit);
		if ($couponList['result'] == 0) {
			$result_array['msg'] = Yii::t('coupon', 'Get Coupon List Success');
			$result_array['result'] = self::Success;
			$result_array['list'] = $couponList['list'];
			$result_array['total'] = count($couponList['list']);
			// $result_array['debug'] = $couponList['list_not_is_available'];
			// $result_array['today'] = $couponList['today'];
			// $result_array['tomorrow'] = $couponList['tomorrow'];
			$this->sendResults($result_array);
		} elseif ($couponList['result'] == 1) {
			$result_array['msg'] = Yii::t('coupon', 'No Coupon data!');
			$result_array['result'] = self::ListNull;
			$result_array['wrong_msg'] = $couponList['msg'];
			// $result_array['debug'] = $couponList['list_not_is_available'];
			// $result_array['today'] = $couponList['today'];
			// $result_array['tomorrow'] = $couponList['tomorrow'];
			$this->sendResults($result_array);
		}

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

	private function is_subscribe($openid) {
		$url = 'http://letsktv.chinacloudapp.cn/wechat_ktv/Home/Event/is_subcribe/openid/' . $openid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$data = curl_exec($ch);
		// $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$sub_info = json_decode($data, true);
		if ($sub_info['result'] == 0) {
			return 1;
		} else {
			return 0;
		}

	}

	public function actiongetcouponbyshare() {
		$result_array = array(
			'result' => self::BadRequest,
			'is_subscribe' => 0,
			'is_guoqi' => 0,
			'is_lingguo' => 0,
			'is_lingguang' => 0,
			'coupon_status' => array(),
			'coupon' => array(),
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
			die();
		}
		$post_data = Yii::app()->request->getPost('GetCouponByshareRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		$post_array = json_decode($post_data, true);
		$_code = isset($post_array['code']) ? trim($post_array['code']) : 0;
		$_openid = isset($post_array['openid']) ? trim($post_array['openid']) : 0;
		if ($_code === 0 || $_openid === 0) {
			$result_array['msg'] = 'params errors';
			$this->sendResults($result_array);
		} else {
			$result_array['is_subscribe'] = $this->is_subscribe($_openid);
			$hashContent = UrlHash::model()->getContentByCode($_code);
			if ($hashContent != null) {
				$couponinfo = json_decode($hashContent, true);
			} else {
				$result_array['msg'] = 'code error';
				$this->sendResults($result_array);
			}
			$sharecouponid = $couponinfo['sharecouponid'];
			$couponshareinfo = CouponShare::model()->getInfoByid($sharecouponid);
			if ($couponshareinfo != null) {
				if ($couponshareinfo['expire_time'] <= time()) {
					$result_array['is_guoqi'] = 1;
				}

				if ($couponshareinfo['share_count'] >= 10) {
					$result_array['is_lingguang'] = 1;
				}
				$userid = PlatformUser::model()->findUserByOpenid($_openid);
				if ($userid != null) {
					$has_coupon = Coupon::model()->findbyuidandsource($userid, $sharecouponid);
					if ($has_coupon['status'] != 0) {
						$coupon = $has_coupon['coupon'];
						$result_array['coupon'] = $coupon;
						$result_array['coupon_status'] = CouponShare::model()->getcouponstatus($sharecouponid);
						$result_array['is_lingguo'] = 1;
						$result_array['result'] = self::Success;
						$result_array['msg'] = 'has owned this coupon ';

					} elseif ($has_coupon['status'] == 0 && $result_array['is_lingguang'] != 1 && $result_array['is_lingguo'] != 1 && $result_array['is_guoqi'] != 1) {
						$coupon_new = Coupon::model()->getNewCouponByShareCoupon($userid, $sharecouponid);
						if ($coupon_new['result'] == 0) {
							$result_array['coupon'] = $coupon_new['coupon'];
							$result_array['coupon_status'] = CouponShare::model()->getcouponstatus($sharecouponid);
							$result_array['msg'] = 'get coupon success';
							$result_array['result'] = self::Success;
						} else {
							$result_array['msg'] = 'get New Coupon Failed';
						}
					}
				} else {
					$result_array['msg'] = 'openid error';
				}

			} else {
				$result_array['msg'] = 'share coupon code error';
			}

		}
		$result_array['result'] = self::Success;
		$this->sendResults($result_array);
	}

}