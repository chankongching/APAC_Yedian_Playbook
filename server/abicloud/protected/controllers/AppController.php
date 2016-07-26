<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AppController
 *
 * @author WINGSUN
 */
class AppController extends ApiController {

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function actionVersion() {
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
		$old_version = Yii::app()->request->getQuery('version');
		$old_versionCode = Yii::app()->request->getQuery('versionCode');
		$os = Yii::app()->request->getQuery('os');
		$os = empty($os) ? '' : $os;
		$app_type = Yii::app()->request->getQuery('type');
		$app_type = empty($app_type) ? 0 : intval($app_type);

		// log
		$version = Version::model()->getNewVersion($this->_appID, $old_version, $os, $old_versionCode, $app_type);
		if (!is_null($version) && !empty($version)) {
			// Create success
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'Get new version!');
			$result_array['need_update'] = true;
			$result_array['force_update'] = (1 == $version['force_update']) ? true : false;
			$result_array['new_version'] = $version['version'];
			$result_array['new_versionCode'] = intval($version['version_code']);
			$result_array['new_name'] = $version['name'];
			//$result_array['download_url'] = $this->createAbsoluteUrl('//') . '/uploads/' . $version['download_url'];
			//$result_array['download_url'] = Yii::app()->request->hostInfo . Yii::app()->baseUrl . '/uploads/' . $version['download_url'];
			$result_array['download_url'] = Yii::app()->createAbsoluteUrl('//') . '/site/appfile/' . $version['id'];
			$result_array['md5_file'] = Version::model()->getMD5ofVersion($version['id']);
		} else {
			//$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'No new version!');
			$result_array['need_update'] = false;
			$result_array['force_update'] = false;
			$result_array['new_version'] = '';
			$result_array['new_versionCode'] = 0;
			$result_array['new_name'] = '';
			$result_array['download_url'] = '';
			$result_array['md5_file'] = '';
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionUpload() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'sucess' => 0,
			'failed' => 0,
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('UploadRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace(print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		$total = isset($post_array['total']) ? intval($post_array['total']) : 0;
		$app_list = isset($post_array['list']) ? $post_array['list'] : array();

		if ($total > 0 && !empty($app_list)) {
			$_userid = Yii::app()->user->getId();
			$count_success = 0;
			$count_failed = 0;
			foreach ($app_list as $key => $app) {
				$app_name = isset($app['name']) ? $app['name'] : '';
				$app_title = isset($app['title']) ? $app['title'] : '';
				$app_version = isset($app['version']) ? $app['version'] : '';
				$app_description = isset($app['description']) ? $app['description'] : '';
				$app_publisher = isset($app['publisher']) ? $app['publisher'] : '';

				$_app = Applist::model()->importApp($_userid, $app_name, $app_title, $app_version, $app_description, $app_publisher);
				if (!is_null($_app) && !empty($_app)) {
					$count_success++;
				} else {
					$count_failed++;
				}
			}
			if ($count_success > 0 && $count_failed < 1) {
				// Create success
				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'Applications upload success!');

				// get rank fastest and slowest in current week
				$result_array['sucess'] = $count_success;
			} elseif ($count_success > 0 && $count_failed > 0) {
				// Create success
				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'Applications upload partrial success!');

				// get rank fastest and slowest in current week
				$result_array['sucess'] = $count_success;
				$result_array['failed'] = $count_failed;
			} else {
				$result_array['msg'] = Yii::t('user', 'Applications upload failed!');
				$result_array['failed'] = $count_failed;
			}
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionInfoUpload() {
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
		$post_data = Yii::app()->request->getPost('InfoRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		Yii::trace(print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		$info_type = isset($post_array['type']) ? intval($post_array['type']) : 0;
		$info_name = isset($post_array['name']) ? $post_array['name'] : '';
		$info_title = isset($post_array['title']) ? $post_array['title'] : '';
		$info_description = isset($post_array['description']) ? $post_array['description'] : '';
		$info_order_num = isset($post_array['order_num']) ? intval($post_array['order_num']) : 0;
		$info_createtime = isset($post_array['createtime']) ? intval($post_array['createtime']) : 0;

		if (isset($_FILES['files']) && !empty($_FILES['files'])) {
			$_userid = Yii::app()->user->getId();
			$_info = Infolist::model()->importInfo($_userid, $info_type, $info_name, $info_title, $info_description, $info_order_num, $info_createtime);
			if (!is_null($_info) && !empty($_info)) {
				// Create success
				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'Information upload success!');
			} else {
				$result_array['msg'] = Yii::t('user', 'Information upload failed!');
			}
		} else {
			$result_array['msg'] = Yii::t('user', 'Please upload a file!');
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionServertime() {
		$result_array = array(
			'result' => self::Success,
			'msg' => Yii::t('user', 'Success'),
			'unixtime' => time(),
		);

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionPushsignin() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'channel' => '',
			'token' => '',
			'server_url' => '',
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get query data
		$clientid = Yii::app()->request->getPost('clientid');
		$appkey = Yii::app()->request->getPost('appkey');
		$appsecret = Yii::app()->request->getPost('appsecret');

		// TODO current only support upgrade category
		$result_array['result'] = self::Success;
		$result_array['msg'] = Yii::t('user', 'Get push service!');
		$result_array['channel'] = 'UPGRADE_NOTIFY';
		$result_array['token'] = md5('UPGRADE_NOTIFY');
		$result_array['server_url'] = Yii::app()->request->getHostInfo() . ':8100/';

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionBaseInfo() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		$baseinfo = array(
			'banner' => array('count' => 1, 'lists' => array(
				// array('link' => 'http://letsktv.chinacloudapp.cn/dist/jaycnparty', 'pic' => '/uploads/event_img/banner/gdgmhbanner.png'),
				array('link' => 'http://letsktv.chinacloudapp.cn/dist/oneyuan', 'pic' => '/uploads/event_img/banner/20160623.jpg'),
				array('link' => 'http://letsktv.chinacloudapp.cn/wechat_ktv/home/event/enter', 'pic' => '/uploads/event_img/banner/20160620.jpg'),
			),
			),
			'poster' => array('count' => 5, 'lists' => array(
				// array('link' => 'http://letsktv.chinacloudapp.cn/dist/jaycnparty/', 'pic' => '/uploads/event_img/poster/gdgmh.png'),
				array('link' => 'http://letsktv.chinacloudapp.cn/dist/oneyuan/', 'pic' => '/uploads/event_img/poster/20160623.jpg'),
				array('link' => 'http://letsktv.chinacloudapp.cn/wechat_ktv/home/event/enter', 'pic' => '/uploads/event_img/poster/20160620.jpg'),
				array('link' => '#!/ktv?event=jq', 'pic' => '/uploads/event_img/poster/1.jpg'),
				array('link' => 'http://letsktv.chinacloudapp.cn/_tools/redirecttohistorymessage.php', 'pic' => '/uploads/event_img/poster/2.jpg'),
			)));
		$result_array['result'] = self::Success;
		$result_array['msg'] = 'get base info success';
		$result_array['baseinfo'] = $baseinfo;
		$this->sendResults($result_array);
	}

}
