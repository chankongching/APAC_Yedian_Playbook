<?php

class TongjiController extends ApiController {
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

	public function actionClick() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		$post_data = Yii::app()->request->getPost('TongjiClick');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		$post_array = json_decode($post_data, true);
		$click_array = isset($post_array['click']) ? $post_array['click'] : array();
		if (count($click_array) == 0) {
			$result_array['msg'] = 'array is null';
			$this->sendResults($result_array);
		}
		$click_count = 0;
		foreach ($click_array as $key => $value) {
			if ($value != NULL) {
				$click = new TongjiClick();
				$click->x = $value['x'];
				$click->y = $value['y'];
				$click->url = $value['url'];
				if ($click->save()) {
					$click_count++;
				}
			}
		}
		$result_array['count'] = $click_count;
		$result_array['msg'] = Yii::t('tongji', 'add click success');
		$result_array['result'] = self::Success;
		$this->sendResults($result_array);

	}

	public function actionBrowse() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'add browse failed'),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		$post_data = Yii::app()->request->getPost('TongjiBrowse');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		$post_array = json_decode($post_data, true);
		$brow = isset($post_array['browse']) ? $post_array['browse'] : '';
		if ($brow == '') {
			$result_array['msg'] = 'browse info is null';
			$this->sendResults($result_array);
		}
		$browse = new TongjiBrowse();
		$browse->url = $brow['url'];
		if ($browse->save()) {
			$result_array['msg'] = Yii::t('tongji', 'add browse info success');
			$result_array['result'] = self::Success;
			$this->sendResults($result_array);
		}
		$result_array['result'] = 440;
		$this->sendResults($result_array);
	}

	public function actionErrorReport() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'add reporterror failed'),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		$post_array = json_decode($post_data, true);
		$content = isset($post_array['content']) ? $post_array['content'] : '';
		$_userid = Yii::app()->user->getId();
		$reporterror = new WechatError();
		$reporterror->userid = $_userid;
		$reporterror->Content = $content;
		if ($reporterror->save()) {
			$result_array['msg'] = Yii::t('tongji', 'add error report success');
			$result_array['result'] = self::Success;
			$this->sendResults($result_array);
		}
		$result_array['result'] = 440;
		$this->sendResults($result_array);
	}
}