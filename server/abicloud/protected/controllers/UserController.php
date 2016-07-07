<?php

class UserController extends ApiController {

	protected $rest = null;

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate() {
		$model = new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			if ($model->save()) {
				// Assign role to user
				$auth = Yii::app()->authManager;
				$auth->assign($model->role, $model->id);

				Yii::app()->user->setFlash('create', Yii::t('user', '{user} created successfully.', array('{user}' => $model->username)));
				$this->redirect(array('view', 'id' => $model->id));
			}
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id) {
		$model = $this->loadModel($id);
		$model->password = '';

		if (('super' != Yii::app()->user->getState('role')) && (Yii::app()->user->id != $model->id)) {
			//throw new CHttpException(403, Yii::t('user', 'You have no permission to perform this action!'));
			throw new Exception(Yii::t('user', 'You have no permission to perform this action!'));
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			if ($model->validate()) {
				if (isset($_POST['User']['password']) && !empty($_POST['User']['password'])) {
					$model->password = $_POST['User']['password'];
				} else {
					$model->password_changed = false;
				}

				if ($model->save()) {
					// Assign role to user
					User::assignUserRole($model->role, $model->id);

					Yii::app()->user->setFlash('update', Yii::t('user', '{user} updated successfully.', array('{user}' => $model->username)));
					$this->redirect(array('view', 'id' => $model->id));
				}
			}
		}

		$this->render('update', array(
			'model' => $model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id) {
		$model = $this->loadModel($id);
		if (1 == $model->id) {
			Yii::app()->user->setFlash('delete', Yii::t('user', '{user} can not be deleted.', array('{user}' => $model->username)));
			Yii::app()->end(CJSON::encode(array('redirect' => isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->createUrl('admin'))));
		}
		if ($model->delete()) {
			Yii::app()->user->setFlash('delete', Yii::t('user', '{user} deleted successfully.', array('{user}' => $model->username)));
		} else {
			Yii::app()->user->setFlash('delete', Yii::t('user', '{user} deleted failed.', array('{user}' => $model->username)));
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (isset($_GET['ajax']) || Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array('redirect' => isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->createUrl('admin')));
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
	}

	public function actionPointAdd() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		$post_data = Yii::app()->request->getPost('PointAdd');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		$post_array = json_decode($post_data, true);
		// var_dump($post_array);
		$_to = isset($post_array['to']) ? $post_array['to'] : '';
		$_from = isset($post_array['from']) ? $post_array['from'] : '';
		$_time = isset($post_array['time']) ? $post_array['time'] : '';
		$_type = isset($post_array['type']) ? $post_array['type'] : '';

		// $phtrecord = PointsHistoryTuijian::model()->findByAttributes(array('from_openid' => $_from));
		$phtrecord = PointsHistoryTuijian::model()->findAllBySql('select * from ac_points_history_tuijian where `from_openid`="' . $_from . '" and DATEDIFF(create_time,NOW())=0');
		$pcount = count($phtrecord);
		// var_dump($phtrecord);
		// die();
		// var_dump($phtrecord);die();
		if ($pcount > 10) {
			$result_array['msg'] = '超出每天获取积分上限';
			$this->sendResults($result_array);
		}
		if ($_from == '' && $_type == 'bwktvbossjuhui') {
			$bwr = PointsHistoryTuijian::model()->findAllByAttributes(array('to_openid' => $_to, 'from_openid' => '3'));
			if (count($bwr) > 0) {
				$result_array['msg'] = Yii::t('user', 'Already Add Point');
				$result_array['result'] = '401';
				$this->sendResults($result_array);
			}
		}

		$pht = new PointsHistoryTuijian();
		if ($_from == '' && $_type == 'Christmas') {
			$pht->from_openid = 0;

		} elseif ($_from == '' && $_type == 'ylyatonh') {
			$pht->from_openid = 2;
		} elseif ($_from == '' && $_type == 'bwktvbossjuhui') {
			$pht->from_openid = 3;
//			$user_to = PlatformUser::model()->findByAttributes(array('openid'=>$_to));
			//			if($user_to==null){
			//
			//			}else{
			//
			//			}
			//			$result_array['result']='400';
			//			$result_array['msg']=Yii::t('user','create user failed');
			//			$this->sendResults($result_array);
		} elseif ($_from == '' && $_type == '') {
			$pht->from_openid = 1;
		} else {
			$pht->from_openid = $_from;
		}
		$pht->to_openid = $_to;
		$pht->times = '1';
		$pht->save();

		// if (empty($phtrecord)) {
		// 	$pht = new PointsHistoryTuijian();
		// 	$pht->from_openid = $_from;
		// 	$pht->to_openid = $_to;
		// 	$pht->times = '1';
		// 	$pht->save();
		// 	}
		// } elseif (count($phtrecord) > 10) {
		// 	$result_array['msg'] = '已达到每天加分上限';
		// 	$this->sendResults($result_array);
		// }
		// var_dump($pht);
		// die();

		if (!empty($_to) && !empty($_from)) {
			// $_toinfo = PlatformUser::model()->findByAttributes(array('openid' => $_to));
			// if (!empty($_toinfo)) {
			// 	$_toid = $_toinfo['id'];
			// } else {
			// 	$user_name = $_to;
			// 	$pass_word = $_to;
			// 	$display_name = '';
			// 	$avatar_url = '';
			// 	$cid = '';
			// 	$type = 0;
			// 	$auth_type = 'wechat';
			// 	$user_new = $this->userRegister(array('username' => $user_name, 'password' => $pass_word, 'openid' => $user_name, 'display_name' => $display_name, 'avatar_url' => $avatar_url, 'cid' => $cid), TRUE, $type, TRUE, $auth_type);
			// 	$_toid = $user_new->id;
			// }
			// $_frominfo = PlatformUser::model()->findByAttributes(array('openid' => $_from));
			// if (!empty($_frominfo)) {
			// 	$_fromid = $_frominfo['id'];
			// } else {
			// 	$result_array['msg'] = '没有发送方用户信息';
			// 	$this->sendResults($result_array);
			// }
			// if ($this->addjifenfromto($_toid, $_fromid, 50)) {
			// 	$result_array['result'] = self::Success;
			// 	$result_array['msg'] = '请求成功，双方积分添加成功';

			// 	$this->sendResults($result_array);
			// } else {
			// 	$result_array['msg'] = '请求失败';
			// 	$this->sendResults($result_array);
			// }
			$result_array['msg'] = '请求失败';
			$this->sendResults($result_array);
		}
		if (empty($_from)) {

			$_toinfo = PlatformUser::model()->findByAttributes(array('openid' => $_to));

			if (!empty($_toinfo)) {
				$_toid = $_toinfo['id'];
				// die($_toid);
			} else {
				$user_name = $_to;
				$pass_word = $_to;
				$display_name = '';
				$avatar_url = '';
				$cid = '';
				$type = 0;
				$auth_type = 'wechat';
				$user_new = $this->userRegister(array('username' => $user_name, 'password' => $pass_word, 'openid' => $user_name, 'display_name' => $display_name, 'avatar_url' => $avatar_url, 'cid' => $cid), TRUE, $type, TRUE, $auth_type);
				if ($user_new == null) {
					$result_array['msg'] = Yii::t('user', 'Create User Failed');
					$result_array['result'] = '400';
					$this->sendResults($result_array);
				}
				$_toid = $user_new->id;

			}
			// if ($_type != '' && time() > 1451556000 && time() < 1451581199) {
			// echo $_type;die();
			// if(time() > 1451553600 && time() < 1451581199){
			// 	echo time();die();
			// }
			// $pphtrecord = PointsHistoryTuijian::model()->findAllBySql('select * from ac_points_history_tuijian where `from_openid`="0"');
			// $ppcount = count($pphtrecord);
			if ($_type == 'ylyatonh') {
				$result_array['msg'] = 'YL HuoDong Tianjia Success';
				$result_array['result'] = '0';
				$this->sendResults($result_array);
			}

			if ($_type == 'Christmas' && time() > 1451553600 && time() < 1451581199) {
				$_add_points = 2250;
			} elseif ($_type == 'ylyatonh') {
				$_add_points = 0;
			} elseif ($_type == 'bwktvbossjuhui') {
				$_add_points = 4500;
				if ($this->addjifenfromto($_toid, '', $_add_points, '3')) {
					$result_array['result'] = self::Success;
					$result_array['msg'] = Yii::t('user', 'Request Success' . $_add_points . '-' . $_toid);
					$this->sendResults($result_array);
				}
			} else {
				$_add_points = 300;
			}
			if ($this->addjifenfromto($_toid, '', $_add_points)) {
				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'Request Success' . $_add_points . '-' . $_toid);
				$this->sendResults($result_array);
			}
		}
		$this->sendResults($result_array);

		// echo $_to . $_form . $_time;
		// // $criteria = new CDbCriteria;
		// // $criteria->addBetweenCondition('time', 1, 4)
		// $record = PointsHistoryTuijian::model()->findByAttributes(array('from_openid' => $_form,'to_openid'=$_to));
		// if (!is_null($record) && !empty($record)) {
		// 	$this->sendResults($result_array);
		// }
		// $record = PointsHistoryTuijian::model()->findByAttributes(array('from_openid' => $_form,'WHERE time BETWEEN (UNIX_TIMESTAMP(now()-86440)) AND now()'));
		// if(count($record)<5){

		// }
	}

	public function addjifenfromto($toid, $fromid = '', $points = 50, $huodong = '') {
		if ($fromid != '') {
			$frominfo = UserPoints::model()->findByAttributes(array('user_id' => $fromid));
			if (!empty($frominfo)) {
				$frominfo->points = $frominfo->points + $points;
				// $frominfo->save();
				if ($frominfo->save()) {
					$info = PlatformUser::model()->findByAttributes(array('id' => $fromid));
					$this->sendPointChangeMessage($info, $points, $frominfo->points, '您好，您邀请的好友已经成功关注夜点娱乐！', '邀请好友成功关注夜点娱乐', '您可以在【个人中心】进行查询，以及在【积分兑换】板块中兑换各种精彩好礼！祝您夜生活愉快~');
				}
			} else {
				$UserPoints = new UserPoints();
				$UserPoints->points = $points;
				$UserPoints->user_id = $fromid;
				if ($UserPoints->save()) {
					$info = PlatformUser::model()->findByAttributes(array('id' => $fromid));
					$this->sendPointChangeMessage($info, $points, $UserPoints->points, '您好，您邀请的好友已经成功关注夜点娱乐！', '邀请好友成功关注夜点娱乐', '您可以在【个人中心】进行查询，以及在【积分兑换】板块中兑换各种精彩好礼！祝您夜生活愉快~');

				}
			}
		}
		$toinfo = UserPoints::model()->findByAttributes(array('user_id' => $toid));
		if (!empty($toinfo)) {
			// echo 'sssss2';
			$toinfo->points = $toinfo->points + $points;
			// $toinfo->save();
			if ($toinfo->save()) {
				$info = PlatformUser::model()->findByAttributes(array('id' => $toid));
				if ($fromid == '') {
					if ($huodong == '3') {
						$this->sendPointChangeMessage($info, $points, $toinfo->points, '欢迎关注夜点娱乐', '夜点祝您新年行大运。', '您可以通过下方菜单【夜点娱乐】板块，进入【积分兑换】，即可换取两张免费电影票。祝您猴年夜生活愉快~');
					} else {
						$this->sendPointChangeMessage($info, $points, $toinfo->points, '您好，您已成功关注夜点娱乐。', '首次关注夜店娱乐', '您可以在夜点娱乐微信下方菜单的【夜点生活】→【个人中心】查询积分，以及在【积分兑换】板块中兑换iPhone6S精彩好礼！邀请新用户还可以获得更多积分！祝您夜生活愉快~');
					}
				} else {
					$this->sendPointChangeMessage($info, $points, $toinfo->points, '您好，您已成功通过好友邀请功能关注夜点娱乐。', '成功通过好友邀请功能首次关注夜店娱乐', '您可以在夜点娱乐微信下方菜单的【夜点生活】→【个人中心】查询积分，以及在【积分兑换】板块中兑换iPhone6S精彩好礼！邀请新用户还可以获得更多积分！祝您夜生活愉快~');
				}

			}
		} else {
			$UserPoints = new UserPoints();
			$UserPoints->points = $points;
			$UserPoints->user_id = $toid;
			// $UserPoints->save();
			if ($UserPoints->save()) {
				$info = PlatformUser::model()->findByAttributes(array('id' => $toid));
				if ($fromid == '') {
					$this->sendPointChangeMessage($info, $points, $UserPoints->points, '您好，您已成功关注夜点娱乐。', '首次关注夜店娱乐', '您可以在夜点娱乐微信下方菜单的【夜点生活】→【个人中心】查询积分，以及在【积分兑换】板块中兑换iPhone6S精彩好礼！邀请新用户还可以获得更多积分！祝您夜生活愉快~');
				} else {
					$this->sendPointChangeMessage($info, $points, $UserPoints->points, '您好，您已成功通过好友邀请功能关注夜点娱乐。', '成功通过好友邀请功能首次关注夜店娱乐', '您可以在夜点娱乐微信下方菜单的【夜点生活】→【个人中心】查询积分，以及在【积分兑换】板块中兑换iPhone6S精彩好礼！邀请新用户还可以获得更多积分！祝您夜生活愉快~');
				}

			}
		}

		return true;

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

	/**
	 * Lists all models.
	 */
	public function actionIndex() {
		$dataProvider = new CActiveDataProvider('User');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin() {
		$model = new User('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['User'])) {
			$model->attributes = $_GET['User'];
		}

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id) {
		$model = User::model()->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404, Yii::t('files', 'The requested page does not exist.'));
		}

		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionQuickreg() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('Quickreg');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace(print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		if (is_null($post_array)) {
			$result_array['msg'] = Yii::t('user', 'Request parameter error!');
			$this->sendResults($result_array);
		}
		$new_display_name = isset($post_array['display_name']) ? $post_array['display_name'] : '';
		$new_avatar_url = isset($post_array['avatar_url']) ? $post_array['avatar_url'] : '';
		$new_regtype = isset($post_array['regtype']) ? $post_array['regtype'] : '';

		$racer = Racer::model()->getRacer($this->_appID, $this->_openID, $this->_token);
		if (is_null($racer)) {
			// Create new racer
			$racer = Racer::model()->createRacer($this->_appID, $this->_openID, $this->_token, $new_display_name, $new_avatar_url, $new_regtype);
		}

		if (!is_null($racer) && !empty($racer)) {
			// Create success
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'User {name} created success!', array('{name}' => $racer['display_name']));
			$result_array['regtype'] = $racer['regtype'];
			$result_array['openid'] = $racer['openid'];
			$result_array['display_name'] = $racer['display_name'];
			$result_array['avatar_url'] = $racer['avatar_url'];
		} else {
			self::log('Error Open ID: ' . $this->_openID, CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = Yii::t('user', 'User {name} can not created!', array('{name}' => $new_display_name));
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionProfile() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'type' => 0,
			'openid' => '',
			'display_name' => '',
			'avatar_url' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' == $request_type) {
			// Edit the profile
			// Get post data
			$post_data = Yii::app()->request->getPost('ProfileRequest');
			if (empty($post_data)) {
				$post_data = file_get_contents("php://input");
			}
			// log
			Yii::trace(print_r($post_data, TRUE));
			// Decode post data
			$post_array = json_decode($post_data, true);
			if (is_null($post_array)) {
				$result_array['msg'] = Yii::t('user', 'Request parameter error!');
				$this->sendResults($result_array);
			}
			$new_display_name = isset($post_array['display_name']) ? $post_array['display_name'] : '';
			$new_avatar_pic = isset($post_array['avatar_pic']) ? $post_array['avatar_pic'] : '';
			$new_pic_type = isset($post_array['pic_type']) ? $post_array['pic_type'] : '';

			$_userid = Yii::app()->user->getId();
			$_user = PlatformUser::model()->findByPk($_userid);
			if (is_null($_user)) {
				$result_array['msg'] = Yii::t('user', 'User with id {id} not exists!', array('{id}' => $_userid));
			} else {
				// Update this racer
				//$racer = Racer::model()->updateRacer($racer, $new_display_name, $new_avatar_pic, $new_pic_type);
			}

			if (!is_null($_user) && !empty($_user)) {
				// Doing update
				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'User profile of {name} updated!', array('{name}' => $_user->username));
				$result_array['type'] = intval($_user->type);
				$result_array['openid'] = $_user->openid;
				$result_array['display_name'] = $_user->display_name;
				$result_array['avatar_url'] = $_user->avatar_url;
			}
		} else if ('GET' == $request_type) {
			// Get profile information
			$_userid = Yii::app()->user->getId();
			$_user = PlatformUser::model()->findByPk($_userid);
			if (!is_null($_user) && !empty($_user)) {
				// Doing query
				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'User profile of {name} got!', array('{name}' => $_user->username));
				$result_array['type'] = intval($_user->type);
				$result_array['openid'] = $_user->openid;
				$result_array['display_name'] = $_user->display_name;
				$result_array['avatar_url'] = $_user->avatar_url;
			}
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionbindwechat() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'openid' => '',
			'display_name' => '',
			'avatar_url' => '',
			'mobile' => '',
			'user_id' => '');
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' == $request_type) {
			$post_data = Yii::app()->request->getPost('BindWechatRequest');
			if (empty($post_data)) {
				$post_data = file_get_contents("php://input");
			}
			$post_array = json_decode($post_data, true);
			// var_dump($post_array);die();
			$new_openid = isset($post_array['openid']) ? $post_array['openid'] : '';
			$new_display_name = isset($post_array['display_name']) ? $post_array['display_name'] : '';
			$new_avatar_url = isset($post_array['avatar_url']) ? $post_array['avatar_url'] : '';
			// echo $new_openid;die();
			$_userid = Yii::app()->user->getId();
			if ($new_openid == '' || $new_display_name == '') {
				$result_array['msg'] = '字段不能为空';
				$this->sendResults($result_array);
			}
			// echo $_userid;die();
			$_user = PlatformUser::model()->findByPk($_userid);
			// var_dump($_user);die();
			if (!isset($_user->openid) || $_user->openid == '') {
				$t_user = PlatformUser::model()->findByAttributes(array('openid' => $new_openid, "auth_type" => 'PHONE'));
				// var_dump($t_user);die();
				if (is_null($t_user)) {
					$_user->openid = $new_openid;
					$_user->display_name = $new_display_name;
					$_user->avatar_url = $new_avatar_url;
					$_user->password_changed = false;
					if ($_user->save()) {
						$result_array['msg'] = '绑定微信openid 成功';
						$result_array['result'] = self::Success;
						$result_array['openid'] = $new_openid;
						$result_array['display_name'] = $new_display_name;
						$result_array['avatar_url'] = $new_avatar_url;
						$result_array['mobile'] = $_user->mobile;
						$result_array['user_id'] = $_user->id;
						$this->sendResults($result_array);
					}
				}
				$result_array['msg'] = '该账号已经绑定openid1';
				$this->sendResults($result_array);
			}
			$result_array['msg'] = '该账号已经绑定openid';
			$this->sendResults($result_array);
		}
	}

	public function actionInfo() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'mobile' => '',
			'userid' => '',
			'display_name' => '',
			'avatar_url' => '',
			'gender' => 0,
			'address' => '',
			'real_name' => '',
//			'consignee' => '',
			//'consignees_address' => '',
			//			'consignees_phone' => '',
			'collectionids' => '',
			'giftorders' => '',
			'giftordernum' => '',
			'collectionnum' => '',
			'sname' => '',
			'stel' => '',
			'order' => 0,
			'favorites' => 0,
			'points' => 0,
			'prov' => '',
			'city' => '',
			'county' => '',
		);

		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' == $request_type) {
			// Edit the profile
			// Get post data
			$post_data = Yii::app()->request->getPost('UpdateUserInfoRequest');
			if (empty($post_data)) {
				$post_data = file_get_contents("php://input");
			}
			// log
			Yii::trace(print_r($post_data, TRUE));
			// Decode post data
			$post_array = json_decode($post_data, true);
			if (is_null($post_array)) {
				$result_array['msg'] = Yii::t('user', 'Request parameter error!');
				$this->sendResults($result_array);
			}

			$new_gender = isset($post_array['gender']) ? $post_array['gender'] : '0';
			$new_address = isset($post_array['address']) ? $post_array['address'] : '';
			$new_mobile = isset($post_array['mobile']) ? $post_array['mobile'] : '';
			$new_realname = isset($post_array['real_name']) ? $post_array['real_name'] : '';
//			$new_consignee = isset($post_array['consignee']) ? $post_array['consignee'] : '';
			$new_collectionids = isset($post_array['collectionids']) ? $post_array['collectionids'] : '';
			$new_giftorders = isset($post_array['giftorders']) ? $post_array['giftorders'] : '';
			//$new_consignees_address = isset($post_array['consignees_address']) ? $post_array['consignees_address'] : '';
			//			$new_consignees_phone = isset($post_array['consignees_phone']) ? $post_array['consignees_phone'] : '';
			$new_address = isset($post_array['address']) ? $post_array['address'] : '';
			$new_sname = isset($post_array['sname']) ? $post_array['sname'] : '';
			$new_stel = isset($post_array['stel']) ? $post_array['stel'] : '';
			$new_prov = isset($post_array['prov']) ? $post_array['prov'] : '';
			$new_city = isset($post_array['city']) ? $post_array['city'] : '';
			$new_county = isset($post_array['county']) ? $post_array['county'] : '';
			$_userid = Yii::app()->user->getId();
			$_user = PlatformUser::model()->findByPk($_userid);
			if (is_null($_user)) {
				$result_array['msg'] = Yii::t('user', 'User with id {id} not exists!', array('{id}' => $_userid));
			} else {
				if (!empty($new_mobile)) {
					$_user->mobile = $new_mobile;
				}
				// get profile information
				$_profile = array();
				if (!empty($_user->profile_data)) {
					$_profile = unserialize($_user->profile_data);
				}
				if (isset($new_gender)) {
					$_profile['gender'] = $new_gender;
				}
				if (!empty($new_address)) {
					$_profile['address'] = $new_address;
				}
				if (!empty($new_realname)) {
					$_profile['real_name'] = $new_realname;
				}
//				if (!empty($new_consignee)) {
				//					$_profile['consignee'] = $new_consignee;
				//				}
				//				if (!empty($new_consignees_address)) {
				//					$_profile['consignees_address'] = $new_consignees_address;
				//				}
				//				if (!empty($new_consignees_phone)) {
				//					$_profile['consignees_phone'] = $new_consignees_phone;
				//				}
				if (!empty($new_address)) {
					$_profile['address'] = $new_address;
				}
				if (!empty($new_sname)) {
					$_profile['sname'] = $new_sname;
				}
				if (!empty($new_stel)) {
					$_profile['stel'] = $new_stel;
				}
				if (!empty($new_prov)) {
					$_profile['prov'] = $new_prov;
				}
				if (!empty($new_city)) {
					$_profile['city'] = $new_city;
				}
				if (!empty($new_county)) {
					$_profile['county'] = $new_county;
				}
				if (!empty($new_collectionids)) {
					$_profile['collectionids'] = $new_collectionids;
				} else {
					//$_profile['collectionids'] = $new_collectionids;
				}
				if (!empty($new_giftorders)) {
					$_profile['giftorders'] = $new_giftorders;
				}
				$_user->profile_data = serialize($_profile);
				// now update
				$_user->password_changed = false;
				$_user->save();
			}

			if (!is_null($_user) && !empty($_user)) {
				// Doing update
				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'User profile of {name} updated!', array('{name}' => $_user->username));
				$result_array['mobile'] = ($_user->mobile);
				$result_array['userid'] = ($_user->id);
				$result_array['openid'] = $_user->openid;
				$result_array['display_name'] = $_user->display_name;
				$result_array['avatar_url'] = $_user->avatar_url;
				// get profile information
				$_profile = array();
				if (!empty($_user->profile_data)) {
					$_profile = unserialize($_user->profile_data);
				}
				if (isset($_profile['gender'])) {
					$result_array['gender'] = $_profile['gender'];
				}
				if (isset($_profile['address'])) {
					$result_array['address'] = $_profile['address'];
				}
				if (isset($_profile['real_name'])) {
					$result_array['real_name'] = $_profile['real_name'];
				}
//				if (isset($_profile['consignee'])) {
				//					$result_array['consignee'] = $_profile['consignee'];
				//				}
				//if (isset($_profile['consignees_address'])) {
				//    $result_array['consignees_address'] = $_profile['consignees_address'];
				//}
				//				if (isset($_profile['consignees_phone'])) {
				//					$result_array['consignees_phone'] = $_profile['consignees_phone'];
				//				}
				if (isset($_profile['address'])) {
					$result_array['address'] = $_profile['address'];
				}
				if (isset($_profile['sname'])) {
					$result_array['sname'] = $_profile['sname'];
				}
				if (isset($_profile['stel'])) {
					$result_array['stel'] = $_profile['stel'];
				}
				if (isset($_profile['prov'])) {
					$result_array['prov'] = $_profile['prov'];
				}
				if (isset($_profile['city'])) {
					$result_array['city'] = $_profile['city'];
				}
				if (isset($_profile['county'])) {
					$result_array['county'] = $_profile['county'];
				}
				if (isset($_profile['collectionids'])) {
					$result_array['collectionids'] = $_profile['collectionids'];
				}
				if (isset($_profile['giftorders'])) {
					$result_array['giftorders'] = $_profile['giftorders'];
				}

				// get user point
				$_points = UserPoints::model()->findByAttributes(array('user_id' => $_userid));
				if (!is_null($_points) && !empty($_points)) {
					$result_array['points'] = $_points['points'];
				}
				// get order count
				$result_array['order'] = RoomBooking::model()->getUserOrderCount($_userid);
				$collection_array = explode(',', $result_array['collectionids']);
				if ($collection_array[0] == '') {
					unset($collection_array[0]);
				}
//				$giftorder_array = explode(',',$result_array['giftorders']);
				//				if($giftorder_array[0]==''){
				//					unset($giftorder_array[0]);
				//				}
				$giftorders = GiftOrder::model()->findAllByAttributes(array('userid' => $_userid));
				$result_array['collectionnum'] = count($collection_array);
				$result_array['giftordernum'] = count($giftorders);
			}
		} else if ('GET' == $request_type) {
			// Get profile information
			$_userid = Yii::app()->user->getId();
			$_user = PlatformUser::model()->findByPk($_userid);
			if (!is_null($_user) && !empty($_user)) {
				// Doing query
				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'User profile of {name} got!', array('{name}' => $_user->username));
				$result_array['mobile'] = ($_user->mobile);
				$result_array['userid'] = ($_user->id);
				$result_array['openid'] = $_user->openid;
				$result_array['display_name'] = $_user->display_name;
				$result_array['avatar_url'] = $_user->avatar_url;
				$result_array['lng'] = floatval($_user->lng) == 0 ? -1 : floatval($_user->lng);
				$result_array['lat'] = floatval($_user->lat) == 0 ? -1 : floatval($_user->lat);
				// get profile information
				$_profile = array();
				if (!empty($_user->profile_data)) {
					$_profile = unserialize($_user->profile_data);
				}
				if (isset($_profile['gender'])) {
					$result_array['gender'] = $_profile['gender'];
				}
				if (isset($_profile['address'])) {
					$result_array['address'] = $_profile['address'];
				}
				if (isset($_profile['real_name'])) {
					$result_array['real_name'] = $_profile['real_name'];
				}
//				if (isset($_profile['consignee'])) {
				//					$result_array['consignee'] = $_profile['consignee'];
				//				}
				if (isset($_profile['address'])) {
					$result_array['address'] = $_profile['address'];
				}
				if (isset($_profile['sname'])) {
					$result_array['sname'] = $_profile['sname'];
				}
				if (isset($_profile['stel'])) {
					$result_array['stel'] = $_profile['stel'];
				}
				if (isset($_profile['prov'])) {
					$result_array['prov'] = $_profile['prov'];
				}
				if (isset($_profile['city'])) {
					$result_array['city'] = $_profile['city'];
				}
				if (isset($_profile['county'])) {
					$result_array['county'] = $_profile['county'];
				}
				if (isset($_profile['collectionids'])) {
					$result_array['collectionids'] = $_profile['collectionids'];
				}
				if (isset($_profile['giftorders'])) {
					$result_array['giftorders'] = $_profile['giftorders'];
				}
				//if (isset($_profile['consignees_address'])) {
				//    $result_array['consignees_address'] = $_profile['consignees_address'];
				//}
				//				if (isset($_profile['consignees_phone'])) {
				//					$result_array['consignees_phone'] = $_profile['consignees_phone'];
				//				}

				// get user point
				$_points = UserPoints::model()->findByAttributes(array('user_id' => $_userid));
				if (!is_null($_points) && !empty($_points)) {
					$result_array['points'] = intval($_points['points']);
				}
				// get order count
				$result_array['order'] = intval(RoomBooking::model()->getUserOrderCount($_userid));
				$collection_array = explode(',', $result_array['collectionids']);
				if ($collection_array[0] == '') {
					unset($collection_array[0]);
				}
//				$giftorder_array = explode(',',$result_array['giftorders']);
				//				if($giftorder_array[0]==''){
				//					unset($giftorder_array[0]);
				//				}
				$giftorders = GiftOrder::model()->findAllByAttributes(array('userid' => $_userid));
				Coupon::model()->checkExpire($_userid);
				RoomBooking::model()->checkExpire($_userid);
				$couponsCount = Coupon::model()->getCouponCount($_userid);

				$result_array['collectionnum'] = count($collection_array);
				$result_array['giftordernum'] = count($giftorders);
				$result_array['couponnum'] = intval($couponsCount);
			}
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionAddCollection() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('ContactRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		$post_array = json_decode($post_data, true);
		$_xktvid = isset($post_array['xktvid']) ? $post_array['xktvid'] : '';
		if ($_xktvid != '') {
			$_userid = Yii::app()->user->getId();
			if (isset($_userid)) {
				$_user = PlatformUser::model()->findByPk($_userid);
				if (!empty($_user->profile_data)) {
					$_profile = unserialize($_user->profile_data);
				}
				if (isset($_profile['collectionids'])) {
					$fav = $_profile['collectionids'];
				} else {
					$fav = '';
				}

				$favs = explode(',', $fav);
//				var_dump(in_array($_xktvid,$favs));die();
				if (in_array($_xktvid, $favs) != true) {
					$favs[] = $_xktvid;
					$_profile['collectionids'] = implode(',', $favs);

					$_user->profile_data = serialize($_profile);
//				echo $_user->profile_data;die();
					if ($_user->save()) {
						$result_array['msg'] = Yii::t('user', 'Add collection success');
						$result_array['result'] = self::Success;
						$this->sendResults($result_array);
					} else {

					}
				} else {
					$result_array['msg'] = Yii::t('user', 'Already Add');
					$this->sendResults($result_array);
				}

			}

		} else {
			$result_array['msg'] = Yii::t('user', 'Xktvid is null');
			$this->sendResults($result_array);
		}
	}
	public function actionDelCollection() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('ContactRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		$post_array = json_decode($post_data, true);
		$_xktvid = isset($post_array['xktvid']) ? $post_array['xktvid'] : '';
		if ($_xktvid != '') {
			$_userid = Yii::app()->user->getId();
			if (isset($_userid)) {
				$_user = PlatformUser::model()->findByPk($_userid);
				if (!empty($_user->profile_data)) {
					$_profile = unserialize($_user->profile_data);
				}
				if (isset($_profile['collectionids'])) {
					$fav = $_profile['collectionids'];
				} else {
					$fav = '';
				}

				$favs = explode(',', $fav);
//				var_dump(in_array($_xktvid,$favs));die();
				if (in_array($_xktvid, $favs) == true) {
					if (($key = array_search($_xktvid, $favs)) !== false) {
						unset($favs[$key]);
					}
//					$favs[]=$_xktvid;
					$_profile['collectionids'] = implode(',', $favs);

					$_user->profile_data = serialize($_profile);
//				echo $_user->profile_data;die();
					if ($_user->save()) {
						$result_array['msg'] = Yii::t('user', 'Del collection success');
						$result_array['result'] = self::Success;
						$this->sendResults($result_array);
					} else {

					}
				} else {
					$result_array['msg'] = Yii::t('user', 'Already Del');
					$this->sendResults($result_array);
				}

			}

		} else {
			$result_array['msg'] = Yii::t('user', 'Xktvid is null');
			$this->sendResults($result_array);
		}
	}

	public function actionCollectionList() {
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

		// Get query data
		$_offset = Yii::app()->request->getQuery('offset');
		$_limit = Yii::app()->request->getQuery('limit');
		$_offset = empty($_offset) ? 0 : intval($_offset);
		$_limit = empty($_limit) ? 100 : intval($_limit);
		$_userid = Yii::app()->user->getId();
		if (isset($_userid)) {
			$_user = PlatformUser::model()->findByPk($_userid);
			if (!empty($_user->profile_data)) {
				$_profile = unserialize($_user->profile_data);
			}
			if (isset($_profile['collectionids'])) {
				$fav = $_profile['collectionids'];
			} else {
				$result_array['result'] = self::ListNull;
				$result_array['msg'] = Yii::t('user', 'No Collections');
				$this->sendResults($result_array);
			}
			$favs = explode(',', $fav);
			if ($favs[0] == '') {
				unset($favs[0]);
			}
			$criteria = new CDbCriteria;
			$criteria->addInCondition('xktvid', $favs);
			$criteria->offset = $_offset;
			$criteria->limit = $_limit;
			$criteria->order = 'update_time desc';
			$collections_array = Xktv::model()->getCollection($criteria);
			if (!empty($collections_array)) {
				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'Get Collections Success');
				$result_array['total'] = count($collections_array);
				$result_array['list'] = $collections_array;
			} else {
				$result_array['result'] = self::ListNull;
				$result_array['msg'] = 'List is Null';
			}

			$this->sendResults($result_array);

		}

		$this->sendResults($result_array);
	}

	public function actionContact() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('ContactRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace(print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		$contact_content = isset($post_array['content']) ? $post_array['content'] : '';

		$contact = Contact::model()->createContact($this->_racerID, $contact_content);
		if (!is_null($contact) && !empty($contact)) {
			// Create success
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'Contact send success!');
		} else {
			$result_array['msg'] = Yii::t('user', 'Contact send failure!');
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionOauthRegister() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'type' => 0,
			'openid' => '',
			'token' => '',
			'display_name' => '',
			'avatar_url' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('OauthRegisterRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace('Oauth Register: ' . print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		if (is_null($post_array)) {
			$result_array['msg'] = Yii::t('user', 'Request parameter error!');
			$this->sendResults($result_array);
		}
		$user_name = isset($post_array['openid']) ? $post_array['openid'] : '';
		$pass_word = $user_name;
		$display_name = isset($post_array['display_name']) ? $post_array['display_name'] : '';
		$avatar_url = isset($post_array['avatar_url']) ? $post_array['avatar_url'] : '';
		//$type = isset($post_array['type']) ? intval($post_array['type']) : 0;
		$type = 0;
		$auth_type = isset($post_array['type']) ? ($post_array['type']) : self::DEFAULT_AUTH_TYPE;

		try {
			$_user = $this->userRegister(array('username' => $user_name, 'password' => $pass_word, 'openid' => $user_name, 'display_name' => $display_name, 'avatar_url' => $avatar_url), TRUE, $type, TRUE, $auth_type);
		} catch (Exception $ex) {
			self::log('User OAuth Register and Log in error: ' . $user_name . ' | ' . $pass_word, CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = $ex->getMessage();
			// Set response information
			$this->sendResults($result_array);
		}
		if (!is_null($_user)) {
			// Login success
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'User {name} registered success!', array('{name}' => $user_name));
			$result_array['type'] = intval($_user->type);
			$result_array['openid'] = $_user->openid;
			$result_array['token'] = session_id();
			$result_array['display_name'] = $_user->display_name;
			$result_array['avatar_url'] = $_user->avatar_url;
		} else {
			self::log('User OAuth Register and Log in error: ' . $user_name . ' | ' . $pass_word, CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = Yii::t('user', 'User {name} registered failed!', array('{name}' => $user_name));
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionOauthlogin() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'token' => '',
			'display_name' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('OpenLoginRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace('Oauth login: ' . print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		if (is_null($post_array)) {
			$result_array['msg'] = Yii::t('user', 'Request parameter error!');
			$this->sendResults($result_array);
		}
		$user_name = isset($post_array['openid']) ? $post_array['openid'] : '';
		$pass_word = $user_name;
		$display_name = isset($post_array['display_name']) ? $post_array['display_name'] : '';
		$avatar_url = isset($post_array['avatar_url']) ? $post_array['avatar_url'] : '';
		//$type = isset($post_array['type']) ? intval($post_array['type']) : 0;
		$type = 0;
		$auth_type = isset($post_array['type']) ? ($post_array['type']) : self::DEFAULT_AUTH_TYPE;
		$cid = isset($post_array['cid']) ? $post_array['cid'] : '';

		try {
			$_user = $this->userRegister(array('username' => $user_name, 'password' => $pass_word, 'openid' => $user_name, 'display_name' => $display_name, 'avatar_url' => $avatar_url, 'cid' => $cid), TRUE, $type, TRUE, $auth_type);
		} catch (Exception $ex) {
			self::log('User OAuth logged in error: ' . $user_name . ' | ' . $pass_word, CLogger::LEVEL_ERROR, $this->id);
			self::log($ex->getMessage(), CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = $ex->getMessage();
			// Set response information
			$this->sendResults($result_array);
		}
		if (!is_null($_user)) {
			// Login success
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'User {name} logged in success!', array('{name}' => $user_name));
			//$result_array['type'] = intval($_user->type);
			//$result_array['openid'] = $_user->openid;
			$result_array['token'] = session_id();
			$result_array['display_name'] = $_user->display_name;
			//$result_array['avatar_url'] = $_user->avatar_url;
			// update avatar
			//$_user->display_name = empty($_display_name) ? $_theUser->display_name : $_display_name;
			//$_user->avatar_url = empty($_avatar_url) ? $_theUser->avatar_url : $_avatar_url;
			//$_user->save();
		} else {
			self::log('User Oauth Register and Log in error: ' . $user_name . ' | ' . $pass_word, CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = Yii::t('user', 'User {name} logged in failed!', array('{name}' => $user_name));
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionOauthSession() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'type' => 0,
			'openid' => '',
			'token' => '',
			'display_name' => '',
			'avatar_url' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('OauthSessionRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace(print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		$user_name = isset($post_array['openid']) ? $post_array['openid'] : '';
		$pass_word = $user_name;
		$oauth_token = isset($post_array['token']) ? $post_array['token'] : '';
		$auth_type = isset($post_array['type']) ? ($post_array['type']) : self::DEFAULT_AUTH_TYPE;

		$_user = $this->userLogin($user_name, $pass_word, 0, true, $auth_type);
		if (!is_null($_user)) {
			// Login success
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'User {name} logged in success!', array('{name}' => $user_name));
			$result_array['type'] = intval($_user->type);
			$result_array['openid'] = $_user->openid;
			$result_array['token'] = session_id();
			$result_array['display_name'] = $_user->display_name;
			$result_array['avatar_url'] = $_user->avatar_url;
		} else {
			self::log('User Logged in error: ' . $user_name . ' | ' . $pass_word, CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = Yii::t('user', 'User {name} logged in failed!', array('{name}' => $user_name));
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionSendcode1() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'verifycode' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('VerifyCodeRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace('Sendcode: ' . print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		if (is_null($post_array)) {
			$result_array['msg'] = Yii::t('user', 'Request parameter error!');
			$this->sendResults($result_array);
		}

		$mobile = isset($post_array['mobile']) ? $post_array['mobile'] : '';
		if (empty($mobile)) {
			$result_array['msg'] = Yii::t('user', 'Request parameter error!');
			$this->sendResults($result_array);
		}

		// generate sms code
		// 生成随机验证码
		$code = $this->createRandNumberByLength(6);
		$curtime = time();
		$verifycode = $curtime . ':' . $mobile . ':' . $code;
		$_verifycode = SmsVerify::model()->find('mobile=:mobile', array(':mobile' => $mobile));
		if (null !== $_verifycode) {
			$_curCodeArray = explode(':', $_verifycode->code);
			if (is_array($_curCodeArray) && isset($_curCodeArray[2])) {
				$_time = intval($_curCodeArray[0]);
				$_mobile = $_curCodeArray[1];
				$_code = $_curCodeArray[2];
				if ($mobile != $_mobile) {
					$result_array['msg'] = Yii::t('user', 'Mobile number dismatched!');
					$this->sendResults($result_array);
				}
				$_time_wait = self::VALID_CODE_TIME - ($curtime - $_time);
				if ($_time_wait > 0) {
					$result_array['msg'] = Yii::t('user', '请 ' . $_time_wait . ' 秒钟后再获取验证码');
					$this->sendResults($result_array);
				}
			}
			// update the code
			$_verifycode->code = $verifycode;
			$_verifycode->save();
		} else {
			$_verifycode = new SmsVerify();
			$_verifycode->mobile = $mobile;
			$_verifycode->code = $verifycode;
			$_verifycode->save();
		}

		// send sms to mobile
		//$sms_msg = '您好，你的验证码为：' . $code . '，请在' . self::VALID_CODE_TIME . '秒内使用。';
		$sms_msg = str_replace('SMS_CODE', $code, self::SMS_VERIFY_TEMPLATE_PHONE);
		Yii::trace('Send SMS: ' . $sms_msg);
		// hide sms code
		//$code = 'XXXXXX';
		$num_tail = rand(1000, 9999);
		/*
			          $req_array = array(
			          'SpCode' => self::SMS_SPCODE,
			          'LoginName' => self::SMS_UID,
			          'Password' => self::SMS_PWD,
			          'MessageContent' => $sms_msg, // TODO need convert to GBK or GB2312
			          'UserNumber' => $mobile, // TODO , seperated
			          'SerialNumber' => date('YmdHis', time()) . $num_tail,
			          'ScheduleTime' => '',
			          'ExtendAccessNum' => '',
			          'f' => 1,
			          );
			          Yii::trace('Sendcode request: ' . print_r($req_array, TRUE));

			          $this->rest = new RESTClient();
			          $this->rest->initialize(array('server' => self::SMS_API_URL));
			          $this->rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=gb2312');
			          $json = $rest->post('Send.do', $req_array);
			          Yii::trace('Sendcode result: ' . print_r($json, TRUE));
		*/

		$sendSms = new SendSmsHttp();
		$sendSms->mobile = $mobile;
		$sendSms->content = $sms_msg;
		$res = $sendSms->send();
/*
$sendSms = new SendSmsHttp();
$sendSms->SpCode = self::SMS_SPCODE;
$sendSms->LoginName = self::SMS_UID;
$sendSms->Password = self::SMS_PWD;
$sendSms->MessageContent = $sms_msg;
$sendSms->UserNumber = $mobile;
$sendSms->SerialNumber = date('YmdHis', time()) . $num_tail;
$sendSms->ScheduleTime = '';
$sendSms->ExtendAccessNum = '';
$sendSms->f = 1;
$res = $sendSms->send();
 */
		if ($res) {
			$ret_msg = '您好，你的验证码已经发送到你注册的手机，请查收，并在' . self::VALID_CODE_TIME . '秒内使用。';
			$result_array['result'] = self::Success;
		} else {
			$ret_msg = $sendSms->errorMsg;
		}

		// call sms interface
		// demo response
		//$result_array['result'] = self::Success;
		$result_array['msg'] = $ret_msg;
		$result_array['verifycode'] = $code;

		$this->sendResults($result_array);
	}

	public function actionSendcode() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'verifycode' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('VerifyCodeRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace('Sendcode: ' . print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		if (is_null($post_array)) {
			$result_array['msg'] = Yii::t('user', 'Request parameter error!');
			$this->sendResults($result_array);
		}

		$mobile = isset($post_array['mobile']) ? $post_array['mobile'] : '';
		if (empty($mobile)) {
			$result_array['msg'] = Yii::t('user', 'Request parameter error!');
			$this->sendResults($result_array);
		}

		// generate sms code
		// 生成随机验证码
		$code = $this->createRandNumberByLength(6);
		$curtime = time();
		$verifycode = $curtime . ':' . $mobile . ':' . $code;
		$_verifycode = SmsVerify::model()->find('mobile=:mobile', array(':mobile' => $mobile));
		if (null !== $_verifycode) {
			$_curCodeArray = explode(':', $_verifycode->code);
			if (is_array($_curCodeArray) && isset($_curCodeArray[2])) {
				$_time = intval($_curCodeArray[0]);
				$_mobile = $_curCodeArray[1];
				$_code = $_curCodeArray[2];
				if ($mobile != $_mobile) {
					$result_array['msg'] = Yii::t('user', 'Mobile number dismatched!');
					$this->sendResults($result_array);
				}
				$_time_wait = self::VALID_CODE_TIME - ($curtime - $_time);
				if ($_time_wait > 0) {
					$result_array['msg'] = Yii::t('user', '请 ' . $_time_wait . ' 秒钟后再获取验证码');
					$this->sendResults($result_array);
				}
			}
			// update the code
			$_verifycode->code = $verifycode;
			$_verifycode->save();
		} else {
			$_verifycode = new SmsVerify();
			$_verifycode->mobile = $mobile;
			$_verifycode->code = $verifycode;
			$_verifycode->save();
		}

		// send sms to mobile
		//$sms_msg = '您好，你的验证码为：' . $code . '，请在' . self::VALID_CODE_TIME . '秒内使用。';
		$sms_msg = str_replace('SMS_CODE', $code, self::SMS_VERIFY_TEMPLATE);
		Yii::trace('Send SMS: ' . $sms_msg);
		// hide sms code
		//$code = 'XXXXXX';
		$num_tail = rand(1000, 9999);
		/*
			          $req_array = array(
			          'SpCode' => self::SMS_SPCODE,
			          'LoginName' => self::SMS_UID,
			          'Password' => self::SMS_PWD,
			          'MessageContent' => $sms_msg, // TODO need convert to GBK or GB2312
			          'UserNumber' => $mobile, // TODO , seperated
			          'SerialNumber' => date('YmdHis', time()) . $num_tail,
			          'ScheduleTime' => '',
			          'ExtendAccessNum' => '',
			          'f' => 1,
			          );
			          Yii::trace('Sendcode request: ' . print_r($req_array, TRUE));

			          $this->rest = new RESTClient();
			          $this->rest->initialize(array('server' => self::SMS_API_URL));
			          $this->rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=gb2312');
			          $json = $rest->post('Send.do', $req_array);
			          Yii::trace('Sendcode result: ' . print_r($json, TRUE));
		*/

		$sendSms = new SendSmsHttp();
		$sendSms->mobile = $mobile;
		$sendSms->content = $sms_msg;
		$res = $sendSms->send();
/*
$sendSms = new SendSmsHttp();
$sendSms->SpCode = self::SMS_SPCODE;
$sendSms->LoginName = self::SMS_UID;
$sendSms->Password = self::SMS_PWD;
$sendSms->MessageContent = $sms_msg;
$sendSms->UserNumber = $mobile;
$sendSms->SerialNumber = date('YmdHis', time()) . $num_tail;
$sendSms->ScheduleTime = '';
$sendSms->ExtendAccessNum = '';
$sendSms->f = 1;
$res = $sendSms->send();
 */
		if ($res) {
			$ret_msg = '您好，你的验证码已经发送到你注册的手机，请查收，并在' . self::VALID_CODE_TIME . '秒内使用。';
			$result_array['result'] = self::Success;
		} else {
			$ret_msg = $sendSms->errorMsg;
		}

		// call sms interface
		// demo response
		//$result_array['result'] = self::Success;
		$result_array['msg'] = $ret_msg;
		$result_array['verifycode'] = $code;

		$this->sendResults($result_array);
	}

	public function actionPhoneverify() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'mobile' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('PhoneVerifyRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace('PhoneVerifyRequest: ' . print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		if (is_null($post_array)) {
			$result_array['msg'] = Yii::t('user', 'Request parameter error!');
			$this->sendResults($result_array);
		}

		$mobile = isset($post_array['mobile']) ? $post_array['mobile'] : '';
		if (empty($mobile)) {
			$result_array['msg'] = Yii::t('user', 'Request parameter error!');
			$this->sendResults($result_array);
		}
		$verify_code = isset($post_array['verifycode']) ? $post_array['verifycode'] : '';

		// TODO check verify code
		$_verifycode = SmsVerify::model()->find('mobile=:mobile', array(':mobile' => $mobile));
		if (null !== $_verifycode) {
			$_curCodeArray = explode(':', $_verifycode->code);
			if (is_array($_curCodeArray) && isset($_curCodeArray[2])) {
				$_time = intval($_curCodeArray[0]);
				$_mobile = $_curCodeArray[1];
				$_code = $_curCodeArray[2];
				if ($_mobile != $mobile) {
					$result_array['msg'] = '验证手机号错误';
					$this->sendResults($result_array);
				}
				if ($_code != $verify_code) {
					$result_array['msg'] = '验证码错误';
					$this->sendResults($result_array);
				}
				$_time_wait = self::VALID_CODE_TIME - (time() - $_time);
				if ($_time_wait < 0) {
					$result_array['msg'] = '验证码过期，请重新获取验证码';
					$this->sendResults($result_array);
				}
			} else {
				$result_array['msg'] = '验证码错误';
				$this->sendResults($result_array);
			}
		} else {
			$result_array['msg'] = '验证手机号错误';
			$this->sendResults($result_array);
		}

		// call sms interface
		// demo response
		$result_array['result'] = self::Success;
		$result_array['msg'] = '手机号码验证成功！';
		$result_array['mobile'] = $mobile;

		$this->sendResults($result_array);
	}

	public function actionRegister() {
		// echo time();
		// die();
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			//'type' => 0,
			//'openid' => '',
			'token' => '',
			//'avatar_url' => '',
			'display_name' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('RegisterRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace('Register: ' . print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		if (is_null($post_array)) {
			$result_array['msg'] = Yii::t('user', 'Request parameter error!');
			$this->sendResults($result_array);
		}
		$user_name = isset($post_array['mobile']) ? $post_array['mobile'] : '';
		$pass_word = isset($post_array['password']) ? $post_array['password'] : '';
		$user_nickname = isset($post_array['display_name']) ? $post_array['display_name'] : '';
		$user_type = isset($post_array['type']) ? $post_array['type'] : '';
		$verify_code = isset($post_array['verifycode']) ? $post_array['verifycode'] : '';
		$cid = isset($post_array['cid']) ? $post_array['cid'] : '';

		// TODO check verify code
		$curtime = time();
		$_verifycode = SmsVerify::model()->find('mobile=:mobile', array(':mobile' => $user_name));
		if (null !== $_verifycode) {
			$_curCodeArray = explode(':', $_verifycode->code);
			if (is_array($_curCodeArray) && isset($_curCodeArray[2])) {
				$_time = intval($_curCodeArray[0]);
				$_mobile = $_curCodeArray[1];
				$_code = $_curCodeArray[2];
				if ($_mobile != $user_name) {
					$result_array['msg'] = '验证手机号错误';
					$this->sendResults($result_array);
				}
				if ($_code != $verify_code) {
					$result_array['msg'] = '验证码错误';
					$this->sendResults($result_array);
				}
				$_time_wait = self::VALID_CODE_TIME - ($curtime - $_time);
				if ($_time_wait < 0) {
					$result_array['msg'] = '验证码过期，请重新获取验证码';
					$this->sendResults($result_array);
				}
			} else {
				$result_array['msg'] = '验证码错误';
				$this->sendResults($result_array);
			}
		} else {
			$result_array['msg'] = '验证手机号错误';
			$this->sendResults($result_array);
		}

		// now try to register user
		try {
			$_user = $this->userRegister(array('username' => $user_name, 'password' => $pass_word, 'display_name' => $user_nickname, 'type' => $user_type, 'cid' => $cid), TRUE);
		} catch (Exception $ex) {
			self::log('User Register and Log in error: ' . $user_name . ' | ' . $pass_word, CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = $ex->getMessage();
			// Set response information
			$this->sendResults($result_array);
		}
		if (!is_null($_user)) {
			if (strtoupper($user_type) == 'PHONE') {
				$_user->mobile = $user_name;
				$_user->password_changed = false;
				$_user->save();
			}
			// Login success
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'User {name} registered success!', array('{name}' => $user_name));
			//$result_array['type'] = intval($_user->type);
			//$result_array['openid'] = $_user->openid;
			$result_array['token'] = session_id();
			//$result_array['display_name'] = $_user->display_name;
			//$result_array['avatar_url'] = $_user->avatar_url;
		} else {
			self::log('User Register and Log in error: ' . $user_name . ' | ' . $pass_word, CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = Yii::t('user', 'User {name} registered failed!', array('{name}' => $user_name));
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionLogin() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			//'type' => 0,
			//'openid' => '',
			'token' => '',
			'display_name' => '',
			//'avatar_url' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('LoginRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		self::log(print_r($post_data, TRUE), 'trace', $this->id);
		// Decode post data
		$post_array = json_decode($post_data, true);
		$user_name = isset($post_array['mobile']) ? $post_array['mobile'] : '';
		$pass_word = isset($post_array['password']) ? $post_array['password'] : '';
		$user_type = isset($post_array['type']) ? $post_array['type'] : '';
		$cid = isset($post_array['cid']) ? $post_array['cid'] : '';

		$_oauth_type = self::DEFAULT_AUTH_TYPE;

		if (!empty($user_type)) {
			if (in_array(strtoupper($user_type), $this->_other_auth_types)) {
				$_oauth_type = strtoupper($user_type);
			}
		}
		// echo $user_name . '---' . $pass_word . '----' . self::DEFAULT_AUTH_TYPE;die();
		$_user = $this->userLogin($user_name, $pass_word, 0, true, $_oauth_type);
		if (!is_null($_user)) {
			// Login success
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'User {name} logged in success!', array('{name}' => $user_name));
			//$result_array['type'] = intval($_user->type);
			//$result_array['openid'] = $_user->openid;
			$result_array['token'] = session_id();
			$result_array['display_name'] = empty($_user->display_name) ? $user_name : $_user->display_name;
			//$result_array['avatar_url'] = $_user->avatar_url;
			// update user cid
			try {
				$_profile = array();
				if (!empty($_user->profile_data)) {
					$_profile = unserialize($_user->profile_data);
				}
				$_profile['cid'] = $cid;
				$_user->saveAttributes(array(
					'profile_data' => serialize($_profile),
				));
			} catch (Exception $ex) {

			}
		} else {
			self::log('User Logged in error: ' . $user_name . ' | ' . $pass_word, CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = Yii::t('user', 'User {name} logged in failed!', array('{name}' => $user_name));
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionLoginbyphone() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			//'type' => 0,
			//'openid' => '',
			'token' => '',
			'display_name' => '',
			//'avatar_url' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('LoginRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		self::log(print_r($post_data, TRUE), 'trace', $this->id);
		// Decode post data
		$post_array = json_decode($post_data, true);
		// var_dump($post_array);
		$user_name = isset($post_array['mobile']) ? $post_array['mobile'] : '';
		$pass_word = isset($post_array['password']) ? $post_array['password'] : '';
		$user_type = isset($post_array['type']) ? $post_array['type'] : '';
		$cid = isset($post_array['cid']) ? $post_array['cid'] : '';

		$_oauth_type = self::DEFAULT_AUTH_TYPE;

		if (!empty($user_type)) {
			if (in_array(strtoupper($user_type), $this->_other_auth_types)) {
				$_oauth_type = strtoupper($user_type);
			}
		}
		// echo $user_name . '---' . $pass_word . '----' . self::DEFAULT_AUTH_TYPE;die();
		// die();
		$_user = $this->userLogin($user_name, $pass_word, 0, true, $_oauth_type);
		if (!is_null($_user)) {
			// Login success
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'User {name} logged in success!', array('{name}' => $user_name));
			//$result_array['type'] = intval($_user->type);
			//$result_array['openid'] = $_user->openid;
			$result_array['token'] = session_id();
			$result_array['display_name'] = empty($_user->display_name) ? $user_name : $_user->display_name;
			//$result_array['avatar_url'] = $_user->avatar_url;
			// update user cid
			try {
				$_profile = array();
				if (!empty($_user->profile_data)) {
					$_profile = unserialize($_user->profile_data);
				}
				$_profile['cid'] = $cid;
				$_user->saveAttributes(array(
					'profile_data' => serialize($_profile),
				));
			} catch (Exception $ex) {

			}
		} else {
			self::log('User Logged in error: ' . $user_name . ' | ' . $pass_word, CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = Yii::t('user', 'User {name} logged in failed!', array('{name}' => $user_name));
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionLogout() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		// Check request type
		//$request_type = Yii::app()->request->getRequestType();
		//if ('POST' != $request_type) {
		//    $this->sendResults($result_array, self::BadRequest);
		//}
		// Log out now
		$_userid = $this->userLogout();
		$_user = PlatformUser::model()->findByPk($_userid);
		if (!is_null($_user) && !empty($_user)) {
			// Doing query
			//CheckinUser::model()->deleteAllByAttributes(array('uid' => $_userid));
			$result_array['msg'] = Yii::t('user', 'User {name} logged out success!', array('{name}' => $_user->username));
		} else {
			$result_array['msg'] = Yii::t('user', 'User logged out success!');
		}
		// Log out success
		$result_array['result'] = self::Success;

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionResetpass() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			//'type' => 0,
			//'openid' => '',
			'new_password' => '',
			//'display_name' => '',
			//'avatar_url' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('ResetPassRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		self::log('Reset password: ' . print_r($post_data, TRUE), 'trace', $this->id);
		// Decode post data
		$post_array = json_decode($post_data, true);
		if (is_null($post_array)) {
			$result_array['msg'] = Yii::t('user', 'Request parameter error!');
			$this->sendResults($result_array);
		}
		$user_name = isset($post_array['mobile']) ? $post_array['mobile'] : '';
		$verify_code = isset($post_array['verifycode']) ? $post_array['verifycode'] : '';

		// check sms code
		$_verifycode = SmsVerify::model()->find('mobile=:mobile', array(':mobile' => $user_name));
		if (null !== $_verifycode) {
			$_curCodeArray = explode(':', $_verifycode->code);
			if (is_array($_curCodeArray) && isset($_curCodeArray[2])) {
				$_time = intval($_curCodeArray[0]);
				$_mobile = $_curCodeArray[1];
				$_code = $_curCodeArray[2];
				if ($_mobile != $user_name) {
					$result_array['msg'] = '验证手机号错误';
					$this->sendResults($result_array);
				}
				if ($_code != $verify_code) {
					$result_array['msg'] = '验证码错误';
					$this->sendResults($result_array);
				}
				$_time_wait = self::VALID_CODE_TIME - ($curtime - $_time);
				if ($_time_wait < 0) {
					$result_array['msg'] = '验证码过期，请重新获取验证码';
					$this->sendResults($result_array);
				}
			} else {
				$result_array['msg'] = '验证码错误';
				$this->sendResults($result_array);
			}
		} else {
			$result_array['msg'] = '验证手机号错误';
			$this->sendResults($result_array);
		}

		// now reset password
		$_newpassword = $this->userResetPassword(array('username' => $user_name));
		if (!empty($_newpassword)) {
			// Rest password success
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'User {name} password reset success!', array('{name}' => $user_name));
			//$result_array['type'] = intval($_user->type);
			//$result_array['openid'] = $_user->openid;
			$result_array['new_password'] = $_newpassword;
			//$result_array['display_name'] = $_user->display_name;
			//$result_array['avatar_url'] = $_user->avatar_url;
			// send sms to user ?
			$sms_msg = str_replace('SMS_PASSWORD', $_newpassword, self::SMS_RESET_TEMPLATE);
			$num_tail = rand(1000, 9999);

			$sendSms = new SendSmsHttp();
			$sendSms->mobile = $mobile;
			$sendSms->content = $sms_msg;
			$res = $sendSms->send();
/*
$sendSms = new SendSmsHttp();
$sendSms->SpCode = self::SMS_SPCODE;
$sendSms->LoginName = self::SMS_UID;
$sendSms->Password = self::SMS_PWD;
$sendSms->MessageContent = $sms_msg;
$sendSms->UserNumber = $mobile;
$sendSms->SerialNumber = date('YmdHis', time()) . $num_tail;
$sendSms->ScheduleTime = '';
$sendSms->ExtendAccessNum = '';
$sendSms->f = 1;
$res = $sendSms->send();
 */
		} else {
			self::log('User password reset error: ' . $user_name, CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = Yii::t('user', 'User {name} password reset failed!', array('{name}' => $user_name));
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionCheckin() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			//'type' => 0,
			//'openid' => '',
			'stburl' => '',
			'roomid' => 0,
			'roomname' => '',
			//'avatar_url' => '',
		);
		// Check request type
		//$request_type = Yii::app()->request->getRequestType();
		//if ('POST' != $request_type) {
		//    $this->sendResults($result_array, self::BadRequest);
		//}
		// log
		//Yii::trace(print_r($post_data, TRUE));

		$cid = Yii::app()->request->getPost('cid');
		if (empty($cid)) {
			$cid = Yii::app()->request->getQuery('cid');
		}

		self::log('Checkin code: ' . $cid, CLogger::LEVEL_INFO, $this->id);

		if (empty($cid)) {
			$result_array['msg'] = Yii::t('user', 'QR code invalid!');
			$this->sendResults($result_array);
		}

		// Get room
		$room = Yii::app()->db->createCommand()
			->select('r.id, r.roomid, r.name, c.duration, c.expire')
			->from('{{checkin_code}} c')
			->join('{{room}} r', 'c.room_id = r.id')
			->where('c.code=:code', array(':code' => $cid))
			->queryRow();

		if (is_null($room) || empty($room)) {
			$result_array['msg'] = Yii::t('user', 'Room id does not exists!');
			$this->sendResults($result_array);
		}
		// TODO: check qr code expire status
		$_qr_expire_time = intval($room['expire']);
		$_room_duration_hour = intval($room['duration']);
		if ($_qr_expire_time != 0 && $_qr_expire_time < time()) {
			// expired
			$result_array['result'] = self::Forbidden;
			$result_array['msg'] = Yii::t('user', 'Your check in service is expired!');
			$this->sendResults($result_array);
		}

		//
		$room_id = intval($room['id']);
		$roomid = $room['roomid'];
		$roomname = $room['name'];
		if ($room_id <= 0) {
			$result_array['msg'] = Yii::t('user', 'Room id invalid!');
			$this->sendResults($result_array);
		}

		// Get STB
		$stb = Yii::app()->db->createCommand()
			->select('d.ip')
			->from('{{device_state}} ds')
			->join('{{room}} r', 'ds.room_id = r.id')
			->join('{{device}} d', 'ds.device_id = d.id')
			->where('r.id=:id AND d.type=:type', array(':id' => $room_id, ':type' => 1))
			->queryRow();

		if (is_null($stb) || empty($stb)) {
			$result_array['msg'] = Yii::t('user', 'STB id does not exists!');
			$this->sendResults($result_array);
		}
		//$stburl = 'http://' . $stb['ip'];
		$stburl = $stb['ip'];

		// TODO: record to singer check in status
		$_uid = Yii::app()->user->getId();
		$_uid = empty($_uid) ? 0 : intval($_uid);
		if ($_uid > 0) {
			try {
				$_checkinuser = CheckinUser::model()->findByAttributes(array('uid' => $_uid, 'room_id' => $room_id));
				if (!is_null($_checkinuser) && !empty($_checkinuser)) {
					$_checkinuser->checkin_time = time();
					$_checkinuser->save();
				} else {
					$_checkinuser = new CheckinUser;
					$_checkinuser->checkin_time = time();
					$_checkinuser->uid = $_uid;
					$_checkinuser->room_id = $room_id;
					$_checkinuser->save();
				}
			} catch (Exception $ex) {
				self::log('Set check in status error: ' . $ex->getMessage(), CLogger::LEVEL_ERROR, $this->id);
			}
		}

		// TODO: record room checkin status with the first user check in
		$_checkinstate = CheckinState::model()->findByAttributes(array('room_id' => $room_id));
		if (is_null($_checkinstate) || empty($_checkinstate)) {
			$_checkinstate = new CheckinState;
			$_checkinstate->room_id = $room_id;
			$_checkinstate->code = $cid;
			$_checkinstate->start_time = time();
			if ($_qr_expire_time == 0 && $_room_duration_hour == 0) {
				$_checkinstate->expire = 0;
			} elseif ($_qr_expire_time != 0 && $_room_duration_hour == 0) {
				$_checkinstate->expire = $_qr_expire_time;
			} else {
				$_checkinstate->expire = time() + $_room_duration_hour * 3600;
			}
			$_checkinstate->save();
		}

		// TODO: sync to clear store shop cart
		Yii::app()->runController('shopcart/clear/roomid/' . $room_id);

		// Set result
		$result_array['result'] = self::Success;
		$result_array['roomid'] = $roomid;
		$result_array['roomname'] = $roomname;
		$result_array['stburl'] = $stburl;

		$result_array['msg'] = Yii::t('user', 'Checked in success!');

		// TODO: start device
		// Set response information
		$this->sendResults($result_array);
	}

	public function actionTableregister() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'deviceid' => '',
			'backgroundurl' => '',
			'downloadqrurl' => '',
			'checkinqrurl' => '',
			//'checkincode' => '',
			'checkinurl' => '',
			'wechaturl' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}

		// Get post data
		$post_data = Yii::app()->request->getPost('RegistrationRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace(print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);

		// Get query data
		$_stburl = isset($post_array['ip']) ? ($post_array['ip']) : '';
		$_imei = isset($post_array['mac']) ? ($post_array['mac']) : '';

		if (!empty($_imei) && !empty($_stburl)) {
			$_baseurl = Yii::app()->createAbsoluteUrl('/');
			$_backgroundUrl = (empty(Yii::app()->params['background_url']) ? $_baseurl . '/uploads/background.png' : Yii::app()->params['background_url']);
			$_downloadUrl = (empty(Yii::app()->params['download_url']) ? $_baseurl . '/uploads/download.png' : Yii::app()->params['download_url']);
			$_wechatUrl = (empty(Yii::app()->params['wechat_url']) ? $_baseurl . '/uploads/wechat.png' : Yii::app()->params['wechat_url']);
			$result_array['backgroundurl'] = $_backgroundUrl;
			$result_array['downloadqrurl'] = $_downloadUrl;
			$result_array['wechaturl'] = $_wechatUrl;
			// create wechat download qr code
			$qrdata = Yii::app()->createAbsoluteUrl('/') . '/download.html';
			$result_array['downloadqrurl'] = $this->getQRCodeUrl($qrdata);

			// log
			$_device = Device::model()->findByAttributes(array('imei' => $_imei, 'type' => 2, 'status' => 0));
			if (!is_null($_device) && !empty($_device)) {
				// Update exists device
				$_deviceid = $_device->id;
				$_device->ip = $_stburl;

				if ($_device->save()) {
					$result_array['result'] = self::Success;
					$result_array['deviceid'] = $_device->imei;
					// Check device bund status
					$_device_state = DeviceState::model()->findByAttributes(array('device_id' => $_deviceid, 'status' => 0));
					if (!is_null($_device_state) && !empty($_device_state)) {
						$result_array['msg'] = Yii::t('user', 'Table registration success!');

						// get qR code and other url
						$result_array['checkinurl'] = $this->getCheckinCode($_deviceid);
						//$result_array['checkincode'] = $this->getCheckinCode($_deviceid, true);
						$siteurl = Yii::app()->createAbsoluteUrl('/');
						$downloadurl = Yii::app()->createAbsoluteUrl('/') . '/site/download/';
						$checkinurl = $result_array['checkinurl'];
						$qrcodeinfo = $downloadurl . 'SITEURL-' . base64url_encode($siteurl) . '/CHECKINURL-' . base64url_encode($checkinurl);
						$result_array['checkinqrurl'] = $this->getQRCodeUrl($qrcodeinfo);
						//$result_array['checkinqrurl'] = $this->getQRCodeUrl($result_array['checkincode']);
					} else {
						$result_array['msg'] = Yii::t('user', 'Table registration success, but can not work before assign to a room!');
					}
				} else {
					$result_array['msg'] = Yii::t('user', 'Table registration failed!');
				}
			} else {
				// Add new device
				$newdevice = new Device();
				$newdevice->imei = $_imei;
				$newdevice->ip = $_stburl;
				$newdevice->type = 2; // 2 TAble
				$newdevice->status = 0;
				$newdevice->name = 'Table ' . $_imei;
				if ($newdevice->save()) {
					$result_array['result'] = self::Success;
					$result_array['msg'] = Yii::t('user', 'Table registration success, but can not work before assign to a room!');
				} else {
					$result_array['msg'] = Yii::t('user', 'Table registration failed!');
				}
			}
		} else {
			$result_array['msg'] = Yii::t('user', 'Request parameters illegal!');
		}

		// Set response information
		$this->sendResults($result_array);
	}

	/**
	 * Get device check in code data
	 * @param integer $device_id
	 * @return string Check in code data
	 */
	public function getCheckinCode($device_id, $only_code = false) {
		// Get room id by device id
		$_room_id = 0;
		$_devicestate = DeviceState::model()->findByAttributes(array('device_id' => $device_id));
		if (is_null($_devicestate) || empty($_devicestate)) {
			return '';
		}
		$_room_id = $_devicestate->room_id;
		// get qR code
		$qrcode = CheckinCode::model()->findByAttributes(array('room_id' => $_room_id));
		if (is_null($qrcode)) {
			return '';
		}

		// check in data
		if ($only_code) {
			$qrdata = $qrcode->code;
		} else {
			$qrdata = Yii::app()->createAbsoluteUrl('/') . '/user/checkin/?cid=' . $qrcode->code;
		}
		return ($qrdata);
	}

	/**
	 * get the qr code url
	 * @param string $qrdata
	 * @return string
	 */
	public function getQRCodeUrl($qrdata) {
		$_baseurl = Yii::app()->createAbsoluteUrl('/');
		$qr_filename = 'QR_' . md5($qrdata) . '.png';
		$qr_filePath = (empty(Yii::app()->params['qrcode_folder']) ? (Yii::getPathOfAlias('webroot.uploads') . DIRECTORY_SEPARATOR . 'qr') : Yii::app()->params['qrcode_folder']);
		$qr_fileUrl = (empty(Yii::app()->params['qrcode_url']) ? $_baseurl . '/uploads/qr' : Yii::app()->params['qrcode_url']);
		$qr_fullFilePath = $qr_filePath . DIRECTORY_SEPARATOR . $qr_filename;
		$qr_fullUrl = $qr_fileUrl . '/' . $qr_filename;

		// check to create the qr code now
		if (!is_file($qr_fullFilePath)) {
			$code = new QRCode($qrdata);
			$code->create($qr_fullFilePath);
		}
		return $qr_fullUrl;
	}

	public function actionInroomList() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'list' => array(),
		);

		$roomid = '';
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' == $request_type) {
			$roomid = Yii::app()->request->getPost('roomid');
		} else if ('GET' == $request_type) {
			$roomid = Yii::app()->request->getQuery('roomid');
		}
		// Get room checked in user list
		$user_list = array();
		if (!empty($roomid)) {
			$cur_room = Room::model()->findByAttributes(array('roomid' => $roomid));
			if (!is_null($cur_room) && !empty($cur_room)) {
				// Add demo user
				if (!empty(Yii::app()->params['room_user_list'])) {
					$room_demo_user_list = Yii::app()->params['room_user_list'];
					if (is_array($room_demo_user_list)) {
						foreach ($room_demo_user_list as $kkey => $kobj) {
							$room_demo_user = $kobj;
							if (!empty($room_demo_user) && is_array($room_demo_user)) {
								$user_list[] = array(
									'username' => $room_demo_user['nickname'],
									'nickname' => $room_demo_user['nickname'],
									'avatarurl' => Yii::app()->createAbsoluteUrl('//') . '/avatar/' . $room_demo_user['avatarurl'],
								);
							}
						}
					}
				}
				// Add online user
				$cur_checked_in_users = $cur_room->checkinUsers;
				foreach ($cur_checked_in_users as $key => $obj) {
					$cur_user = $obj->u;
					$user_list[] = array(
						'username' => $cur_user->username,
						'nickname' => empty($cur_user->display_name) ? $cur_user->username : $cur_user->display_name,
						'avatarurl' => $cur_user->avatar_url,
					);
				}

				$result_array['result'] = self::Success;
				$result_array['list'] = $user_list;
				$result_array['msg'] = Yii::t('user', 'Room {name} checked in user list got!', array('{name}' => $roomid));
			} else {
				$result_array['msg'] = Yii::t('user', 'Room Id incorrect or no checked in user!');
			}
		} else {
			$result_array['msg'] = Yii::t('user', 'Room Id must not be empty!');
		}
		// Set response information
		$this->sendResults($result_array);
	}

	public function actionQrcheckin() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			//'type' => 0,
			//'openid' => '',
			'siteurl' => '',
			'stburl' => '',
			'roomid' => 0,
			'token' => '',
			//'avatar_url' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('QrCheckinRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace('QR Checkin: ' . print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		if (is_null($post_array)) {
			$result_array['msg'] = Yii::t('user', 'Request parameter error!');
			$this->sendResults($result_array);
		}

		$cid = isset($post_array['cid']) ? $post_array['cid'] : '';
		if (empty($cid)) {
			$result_array['msg'] = Yii::t('user', 'QR code invalid!');
			$this->sendResults($result_array);
		}

		// Get room
		$room = Yii::app()->db->createCommand()
			->select('r.id, r.roomid, r.name, c.duration, c.expire')
			->from('{{checkin_code}} c')
			->join('{{room}} r', 'c.room_id = r.id')
			->where('c.code=:code', array(':code' => $cid))
			->queryRow();

		if (is_null($room) || empty($room)) {
			$result_array['msg'] = Yii::t('user', 'Room id does not exists!');
			$this->sendResults($result_array);
		}
		// TODO: check qr code expire status
		$_qr_expire_time = intval($room['expire']);
		$_room_duration_hour = intval($room['duration']);
		if ($_qr_expire_time != 0 && $_qr_expire_time < time()) {
			// expired
			$result_array['msg'] = Yii::t('user', 'Your check in service is expired!');
			$this->sendResults($result_array);
		}

		//
		$room_id = intval($room['id']);
		$roomid = $room['roomid'];
		$roomname = $room['name'];
		if ($room_id <= 0) {
			$result_array['msg'] = Yii::t('user', 'Room id invalid!');
			$this->sendResults($result_array);
		}

		// Get STB
		$stb = Yii::app()->db->createCommand()
			->select('d.ip')
			->from('{{device_state}} ds')
			->join('{{room}} r', 'ds.room_id = r.id')
			->join('{{device}} d', 'ds.device_id = d.id')
			->where('r.id=:id AND d.type=:type', array(':id' => $room_id, ':type' => 1))
			->queryRow();

		if (is_null($stb) || empty($stb)) {
			$result_array['msg'] = Yii::t('user', 'STB id does not exists!');
			$this->sendResults($result_array);
		}
		//$stburl = 'http://' . $stb['ip'];
		$stburl = $stb['ip'];
		$siteurl = 'http://' . Yii::app()->request->userHostAddress . '/abiktv';

		// Oauth log in check
		$user_name = isset($post_array['openid']) ? $post_array['openid'] : '';
		$pass_word = $user_name;
		$display_name = isset($post_array['display_name']) ? $post_array['display_name'] : '';
		$avatar_url = isset($post_array['avatar_url']) ? $post_array['avatar_url'] : '';
		//$type = isset($post_array['type']) ? intval($post_array['type']) : 0;
		$type = 0;
		$auth_type = isset($post_array['type']) ? ($post_array['type']) : self::DEFAULT_AUTH_TYPE;

		try {
			$_user = $this->userRegister(array('username' => $user_name, 'password' => $pass_word, 'openid' => $user_name, 'display_name' => $display_name, 'avatar_url' => $avatar_url), TRUE, $type, TRUE, $auth_type);
		} catch (Exception $ex) {
			self::log('User OAuth logged in error: ' . $user_name . ' | ' . $pass_word, CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = $ex->getMessage();
			// Set response information
			$this->sendResults($result_array);
		}
		if (!is_null($_user)) {
			// Login success
			//$result_array['result'] = self::Success;
			//$result_array['msg'] = Yii::t('user', 'User {name} logged in success!', array('{name}' => $user_name));
			//$result_array['type'] = intval($_user->type);
			//$result_array['openid'] = $_user->openid;
			$result_array['token'] = session_id();
			//$result_array['display_name'] = $_user->display_name;
			//$result_array['avatar_url'] = $_user->avatar_url;
			// And then check in to room
			// TODO: record to singer check in status
			$_uid = Yii::app()->user->getId();
			$_uid = empty($_uid) ? 0 : intval($_uid);
			if ($_uid > 0) {
				try {
					$_checkinuser = CheckinUser::model()->findByAttributes(array('uid' => $_uid, 'room_id' => $room_id));
					if (!is_null($_checkinuser) && !empty($_checkinuser)) {
						$_checkinuser->checkin_time = time();
						$_checkinuser->save();
					} else {
						$_checkinuser = new CheckinUser;
						$_checkinuser->checkin_time = time();
						$_checkinuser->uid = $_uid;
						$_checkinuser->room_id = $room_id;
						$_checkinuser->save();
					}
				} catch (Exception $ex) {
					self::log('Set check in status error: ' . $ex->getMessage(), CLogger::LEVEL_ERROR, $this->id);
				}
			}

			// TODO: record room checkin status with the first user check in
			$_checkinstate = CheckinState::model()->findByAttributes(array('room_id' => $room_id));
			if (is_null($_checkinstate) || empty($_checkinstate)) {
				$_checkinstate = new CheckinState;
				$_checkinstate->room_id = $room_id;
				$_checkinstate->code = $cid;
				$_checkinstate->start_time = time();
				if ($_qr_expire_time == 0 && $_room_duration_hour == 0) {
					$_checkinstate->expire = 0;
				} elseif ($_qr_expire_time != 0 && $_room_duration_hour == 0) {
					$_checkinstate->expire = $_qr_expire_time;
				} else {
					$_checkinstate->expire = time() + $_room_duration_hour * 3600;
				}
				$_checkinstate->save();
			}

			// TODO: sync to clear store shop cart
			Yii::app()->runController('shopcart/clear/roomid/' . $room_id);

			// Set result
			$result_array['result'] = self::Success;
			$result_array['roomid'] = $roomid;
			$result_array['stburl'] = $stburl;
			$result_array['siteurl'] = $siteurl;

			$result_array['msg'] = Yii::t('user', 'User {name} logged in and checked in success!', array('{name}' => $user_name));
		} else {
			self::log('User Oauth Log and check in error: ' . $user_name . ' | ' . $pass_word, CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = Yii::t('user', 'User {name} logged in failed!', array('{name}' => $user_name));
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionProcesspoints() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			//'type' => 0,
			//'openid' => '',
			'points' => '',
			'total' => '',
			//'avatar_url' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('GetPointRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		self::log(print_r($post_data, TRUE), 'trace', $this->id);
		// Decode post data
		$post_array = json_decode($post_data, true);
		$activity_id = isset($post_array['activities_id']) ? $post_array['activities_id'] : '';
		$source_id = isset($post_array['source_id']) ? $post_array['source_id'] : '';

		// process point
		$result_array = self::processPoints(Yii::app()->user->getId(), $activity_id, $source_id);

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionGetpointshistory() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'total' => 0,
			//'openid' => '',
			'pointslist' => array(),
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

		$_userid = Yii::app()->user->getId();

		$criteria = new CDbCriteria();
		$criteria->condition = 'user_id = :user_id';
		$criteria->params = array(':user_id' => $_userid);

		$_count = intval(PointsHistory::model()->count($criteria));

		$criteria->offset = $_offset;
		$criteria->limit = $_limit;
		$criteria->order = 'duetime DESC';
		$points_list = PointsHistory::model()->findAll($criteria);
		if (!empty($points_list)) {
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'Get points list success!');
			$result_array['total'] = $_count;
			foreach ($points_list as $key => $value) {
				$result_array['pointslist'][] = array(
					'activities_id' => $value->activity_id,
					'activities_name' => $value->activity_name,
					'source_type' => $value->code,
					'points' => intval($value->points),
					'points_after' => intval($value->points_after),
					'date_time' => intval($value->duetime),
				);
			}
		} else {
			self::log('No points history!', CLogger::LEVEL_ERROR, $this->id);
			$result_array['msg'] = Yii::t('user', 'No points history!');
		}

		// Set response information
		$this->sendResults($result_array);
	}

}
