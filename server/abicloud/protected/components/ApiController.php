<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiController
 *
 * @author SUNJOY
 */
class ApiController extends CController implements HttpResponse {
	//*************************************************************************
	//* Constants
	//*************************************************************************
	//.........................................................................
	//. Success/Status (2xx)
	//.........................................................................

	/**
	 * 2 minute
	 */
	const VALID_CODE_TIME = 120;
	const SMS_API_URL = 'http://sms.api.ums86.com:8899/sms/Api/';
	const SMS_SPCODE = '203360';
	const SMS_UID = 'ktvsms';
	const SMS_PWD = 'xyz123';
	// Let{x}s KTV验证码{xxxxxxxx}请输入验证码完成手机绑定{x}请您注意保管{x}
	// 您的验证码{xxxxxx}请在页面中输入完成验证{x}不要把此验证码泄露给任何人{x}如非本人操作{x}请忽略此短信{x}
	// 您的手机验证码为{xxxxxxx}请勿泄露于他人{x}请勿回复{x}如非本人操作请忽略{x}
	// 您重置后的密码为{xxxxxxxxxxxxxxxxxxxx}请重新登录{xxxxxxxxxxx}系统并修改密码{x}谢谢{x}
	//const SMS_VERIFY_TEMPLATE = "Let's KTV验证码SMS_CODE，请输入验证码完成手机绑定，请您注意保管。";
	//     const SMS_VERIFY_TEMPLATE = "夜点验证码：SMS_CODE，请输入验证码完成手机绑定，请您注意保管。";
	const SMS_VERIFY_TEMPLATE = "感谢您下载并注册夜点，预订KTV要派对不排队，您的验证码是SMS_CODE【夜点应用】";
	// const SMS_VERIFY_TEMPLATE = "【夜点娱乐】您的验证码是：SMS_CODE，感谢您使用夜点娱乐。";
	const SMS_VERIFY_TEMPLATE_PHONE = "您正在使用积分兑换礼品，此次验证码是SMS_CODE。【夜点娱乐】";
	const SMS_RESET_TEMPLATE = "您重置后的密码为SMS_PASSWORD，请重新登录 Let's KTV系统并修改密码，谢谢！";

	/**
	 * @var int
	 */
	const Success = 0;
	const ListNull = 450;
	const DEFAULT_AUTH_TYPE = 'KTV';
	const TABLE_AUTH_TYPE = 'KTVTABLE';
	const PAD_AUTH_TYPE = 'KTVPAD';
	const STAFF_AUTH_TYPE = 'KTVSTAFF';
	const PHONE_AUTH_TYPE = 'PHONE';
	const WEBAPP_AUTH_TYPE = 'WEBAPP';

	/**
	 * 10 minutes
	 */
	const USER_VALID_TIME = 10;
	const USER_IDLE_TIME = 600;

	public $_other_auth_types = array(self::TABLE_AUTH_TYPE, self::PAD_AUTH_TYPE, self::STAFF_AUTH_TYPE, self::PHONE_AUTH_TYPE, self::WEBAPP_AUTH_TYPE);

	/**
	 *
	 * @var string
	 */
	protected $_vendorKey = '';
	protected $_appKey = '';
	protected $_openID = '';
	protected $_token = '';
	protected $_appPlatform = 1;
	protected $_vendorID = 0;
	protected $_appID = 0;
	protected $_roomID = 0;
	protected $_roomKey = '';
	protected $_racerID = 0;
	protected $_checkinCode = '';
	protected $_appUser = null;

	const SERVICE_APPLICATION_NAME = 'X_KTV_APPLICATION_NAME';
	const SERVICE_VENDOR_NAME = 'X_KTV_VENDOR_NAME';
	const SERVICE_APPLICATION_PLATFORM = 'X_KTV_APPLICATION_PLATFORM';
	const SERVICE_OPENID = 'X_KTV_USER_ID';
	const SERVICE_TOKEN = 'X_KTV_USER_TOKEN';
	const SERVICE_ROOMID = 'X_KTV_ROOM_ID';
	const HTTP_SERVICE_APPLICATION_NAME = 'HTTP_X_KTV_APPLICATION_NAME';
	const HTTP_SERVICE_VENDOR_NAME = 'HTTP_X_KTV_VENDOR_NAME';
	const HTTP_SERVICE_APPLICATION_PLATFORM = 'HTTP_X_KTV_APPLICATION_PLATFORM';
	const HTTP_SERVICE_OPENID = 'HTTP_X_KTV_USER_ID';
	const HTTP_SERVICE_TOKEN = 'HTTP_X_KTV_USER_TOKEN';
	const HTTP_SERVICE_ROOMID = 'HTTP_X_KTV_ROOM_ID';

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/column1';

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();

	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();

	/**
	 * @var array API items.
	 */
	public static $api_array = array(
		'user/oauthregister', 'user/oauthsession',
		'user/register', 'user/login', 'user/loginbyphone', 'user/logout', 'user/checkin', 'user/session', 'user/profile', 'user/resetpass', 'user/oauthlogin', 'user/qrcheckin',
		'player/medialist', 'player/mediainfo', 'player/playlist', 'player/playedlist', 'player/playlistupdate', 'player/addtoplaylist', 'player/mediasearch', 'player/recommendlist', 'player/toplist', 'player/pinyinsearch',
		'player/artistcategory', 'player/artistsearch', 'player/songbyartist', 'player/songbyalphabet', 'player/musiccharts', 'player/songbychart', 'player/artistbycategory',
		'product/category', 'product/listbycategory', 'product/listbyname', 'product/detail', 'shopcart/add', 'shopcart/delete', 'shopcart/update', 'shopcart/cart', 'order/create', 'order/save', 'order/detail', 'order/list', 'order/history', 'order/update',
		'product/setmenuclass', 'product/setmenuitem', 'order/billinfo', 'order/submit',
		'app/version', 'app/calllog', 'app/servertime', 'app/pushsignin',
		'stb/stbregister', 'stb/stbmediainfo', 'stb/stbplaylist', 'stb/stbcheckexpire', 'stb/stbcheckstart', 'stb/stbstatus', 'stb/stbmedialist', 'user/tableregister',
		'user/inroomlist',
		'user/xktvdistrict', 'user/xktvlist', 'user/xktvsearchlist',
		'user/info', 'user/sendcode', 'user/sendcode1', 'user/phoneverify', 'user/processpoints', 'user/getpointshistory',
		'booking/roomlist', 'booking/submitorder', 'booking/updateorder', 'booking/orderlist', 'feedback/feedback', 'feedback/comment', 'booking/addcollection', 'booking/cancelcollection', 'gift/giftlist', 'gift/orderreal', 'gift/ordervirtual', 'gift/giftorderlist', 'user/PointAdd',
		'gift/OrderRealCXY', 'user/bindwechat', 'gift/giftdetail', 'booking/deleteorder', 'user/addcollection',
		'user/delcollection', 'user/collectionlist', 'booking/xktvcoords', 'gift/giftorderlistnew', 'gift/orderdetail', 'tongji/click', 'tongji/browse', 'tongji/errorreport',
		'gift/testbytoken', 'coupon/list', 'coupon/detail', 'coupon/checkstatus', 'coupon/availablelist', 'booking/Orderdetail', 'booking/orderdetail', 'booking/checkstatus', 'coupon/getcouponbyevents', 'coupon/getcouponstatusbyevents', 'booking/gettaocaninfo', 'booking/gettaocanlist', 'booking/makesure', 'booking/Submitorder_new', 'app/baseinfo', 'user/loginbywebapp', 'user/registerwebapp', 'booking/CityList',
        'feedback/commentapp','coupon/getcouponbyshare',
	);

	/**
	 * @var array API session items.
	 */
	public static $api_session_array = array(
		'user/checkin', 'user/profile',
		'product/category', 'product/listbycategory', 'product/listbyname', 'product/detail', 'shopcart/add', 'shopcart/delete', 'shopcart/update', 'shopcart/cart', 'order/create', 'order/save', 'order/detail', 'order/list', 'order/history', 'order/update',
		'product/setmenuclass', 'product/setmenuitem', 'order/billinfo', 'order/submit',
		'player/medialist', 'player/mediainfo', 'player/playlist', 'player/playedlist', 'player/playlistupdate', 'player/addtoplaylist', 'player/mediasearch', 'player/recommendlist', 'player/toplist', 'player/pinyinsearch',
		'player/artistcategory', 'player/artistsearch', 'player/songbyartist', 'player/songbyalphabet', 'player/musiccharts', 'player/songbychart', 'player/artistbycategory',
		'user/xktvdistrict', 'user/xktvlist', 'user/xktvsearchlist',
		'user/info', 'user/phoneverify', 'user/processpoints', 'user/getpointshistory',
		'booking/roomlist', 'booking/submitorder', 'booking/updateorder', 'booking/cancelorder', 'booking/orderlist',
		'booking/addcollection', 'booking/cancelcollection', 'gift/giftorderlist', 'gift/orderreal', 'gift/ordervirtual', 'user/bindwechat', 'booking/deleteorder',
		'user/addcollection', 'user/delcollection', 'user/collectionlist', 'gift/giftorderlistnew', 'gift/orderdetail', 'tongji/click', 'tongji/browse',
		'gift/testbytoken', 'feedback/feedback', 'feedback/comment', 'coupon/list', 'coupon/detail', 'coupon/checkstatus', 'tongji/errorreport', 'coupon/availablelist', 'booking/Orderdetail', 'booking/orderdetail', 'booking/checkstatus', 'coupon/getcouponbyevents', 'coupon/getcouponstatusbyevents', 'booking/gettaocaninfo', 'booking/gettaocanlist', 'booking/makesure', 'booking/Submitorder_new',
        'feedback/commentapp'
	);

	/**
	 * @var array API token items.
	 */
	public static $api_token_array = array(
		'logout', 'checkin', 'profile',
		'user/logout', 'user/checkin', 'user/profile',
		'product/category', 'product/listbycategory', 'product/listbyname', 'product/detail', 'shopcart/add', 'shopcart/delete', 'shopcart/update', 'shopcart/cart', 'order/create', 'order/save', 'order/detail', 'order/list', 'order/history', 'order/update',
		'product/setmenuclass', 'product/setmenuitem', 'order/billinfo', 'order/submit',
		'player/medialist', 'player/mediainfo', 'player/playlist', 'player/playedlist', 'player/playlistupdate', 'player/addtoplaylist', 'player/mediasearch', 'player/recommendlist', 'player/toplist', 'player/pinyinsearch',
		'player/artistcategory', 'player/artistsearch', 'player/songbyartist', 'player/songbyalphabet', 'player/musiccharts', 'player/songbychart', 'player/artistbycategory',
		'user/xktvdistrict', 'user/xktvlist', 'user/xktvsearchlist',
		'user/info', 'user/phoneverify', 'user/processpoints', 'user/getpointshistory',
		'booking/roomlist', 'booking/submitorder', 'booking/updateorder', 'booking/orderlist', 'gift/giftorderlist', 'gift/orderreal', 'gift/ordervirtual',
		'user/bindwechat', 'booking/deleteorder', 'user/addcollection', 'user/delcollection', 'user/collectionlist',
		'gift/giftorderlistnew', 'gift/orderdetail', 'tongji/click', 'tongji/browse',
		'gift/testbytoken', 'feedback/feedback', 'feedback/comment',
		'coupon/list', 'coupon/detail', 'coupon/availablelist', 'booking/Orderdetail', 'booking/orderdetail', 'booking/checkstatus', 'coupon/getcouponbyevents', 'coupon/getcouponstatusbyevents', 'booking/gettaocaninfo', 'booking/gettaocanlist', 'booking/makesure', 'booking/Submitorder_new',
	);

	/**
	 * @var array API room id items.
	 */
	public static $api_room_array = array(
		'shopcart/add', 'shopcart/delete', 'shopcart/update', 'shopcart/cart', 'order/create', 'order/save', 'order/detail', 'order/list', 'order/update',
		'product/category', 'product/listbycategory', 'product/setmenuclass', 'product/setmenuitem', 'order/billinfo', 'order/submit',
		'player/playlist', 'player/playedlist', 'player/playlistupdate', 'player/addtoplaylist',
	);

	/**
	 * @var array API check in items.
	 */
	public static $api_checkin_array = array(
		'product/category', 'product/listbycategory', 'product/listbyname', 'product/detail', 'shopcart/add', 'shopcart/delete', 'shopcart/update', 'shopcart/cart', 'order/create', 'order/save', 'order/detail', 'order/list', 'order/history', 'order/update',
		'product/setmenuclass', 'product/setmenuitem', 'order/billinfo', 'order/submit',
		'player/artistcategory', 'player/artistsearch', 'player/songbyartist', 'player/songbyalphabet', 'player/musiccharts', 'player/songbychart', 'player/artistbycategory',
		'player/medialist', 'player/mediainfo', 'player/playlist', 'player/playedlist', 'player/playlistupdate', 'player/addtoplaylist', 'player/mediasearch', 'player/recommendlist', 'player/toplist', 'player/pinyinsearch',
	);

	/**
	 *
	 * @var array API statistics items
	 */
	public static $api_stat_array = array(
		'player/mediasearch', 'player/songbyartist', 'player/songbyalphabet', 'player/songbychart',
		'order/submit',
		'user/checkin',
	);

	/**
	 *
	 * @var string Current call API controller and action
	 */
	public $api_current_call = '';

	/**
	 * Initialize controller and populate request object
	 */
	public function init() {
		parent::init();

		if (!empty($_SERVER[self::SERVICE_APPLICATION_NAME])) {
			$this->_appKey = $_SERVER[self::SERVICE_APPLICATION_NAME];
		}
		if (!empty($_SERVER[self::HTTP_SERVICE_APPLICATION_NAME])) {
			$this->_appKey = $_SERVER[self::HTTP_SERVICE_APPLICATION_NAME];
		}

		if (!empty($_SERVER[self::SERVICE_VENDOR_NAME])) {
			$this->_vendorKey = $_SERVER[self::SERVICE_VENDOR_NAME];
		}
		if (!empty($_SERVER[self::HTTP_SERVICE_VENDOR_NAME])) {
			$this->_vendorKey = $_SERVER[self::HTTP_SERVICE_VENDOR_NAME];
		}

		if (!empty($_SERVER[self::SERVICE_APPLICATION_PLATFORM])) {
			$this->_appPlatform = intval($_SERVER[self::SERVICE_APPLICATION_PLATFORM]);
		}
		if (!empty($_SERVER[self::HTTP_SERVICE_APPLICATION_PLATFORM])) {
			$this->_appPlatform = intval($_SERVER[self::HTTP_SERVICE_APPLICATION_PLATFORM]);
		}

		if (!empty($_SERVER[self::SERVICE_OPENID])) {
			$this->_openID = $_SERVER[self::SERVICE_OPENID];
		}
		if (!empty($_SERVER[self::HTTP_SERVICE_OPENID])) {
			$this->_openID = $_SERVER[self::HTTP_SERVICE_OPENID];
		}

		if (!empty($_SERVER[self::SERVICE_TOKEN])) {
			$this->_token = $_SERVER[self::SERVICE_TOKEN];
		}
		if (!empty($_SERVER[self::HTTP_SERVICE_TOKEN])) {
			$this->_token = $_SERVER[self::HTTP_SERVICE_TOKEN];
		}

		if (!empty($_SERVER[self::SERVICE_ROOMID])) {
			$this->_roomKey = $_SERVER[self::SERVICE_ROOMID];
		}
		if (!empty($_SERVER[self::HTTP_SERVICE_ROOMID])) {
			$this->_roomKey = $_SERVER[self::HTTP_SERVICE_ROOMID];
		}
		if (!empty($this->_roomKey)) {
			//$room = Room::model()->findByAttributes(array('roomid' => $this->_roomKey));
			//if (!is_null($room) && !empty($room)) {
			//    $this->_roomID = intval($room->id);
			//}
		}

		$_post_array = $_POST;
		$_get_array = $_GET;

		//self::log("API call header:\n" . print_r($_SERVER, true), 'trace', $this->id);
		if (!empty($_post_array)) {
			self::log("API POST request:\n" . print_r($_post_array, true), 'trace', $this->id);
		}
		if (!empty($_get_array)) {
			self::log("API GET request:\n" . print_r($_get_array, true), 'trace', $this->id);
		}
	}

	/**
	 * @param mixed $data Could be object, array, or simple type
	 * @param bool  $prettyPrint
	 *
	 * @return null|string
	 */
	public function jsonEncode($data, $prettyPrint = false) {
		$_data = ($data);

		if (version_compare(PHP_VERSION, '5.4', '>=')) {
			$_options = JSON_UNESCAPED_SLASHES | (false !== $prettyPrint ? JSON_PRETTY_PRINT : 0);

			return json_encode($_data, $_options);
		}

		$_json = str_replace('\/', '/', json_encode($_data));

		return $prettyPrint ? $this->pretty_json($_json) : $_json;
	}

	/**
	 * Indents a flat JSON string to make it more human-readable.
	 * Stolen from http://recursive-design.com/blog/2008/03/11/format-json-with-php/
	 * and adapted to put spaces around : characters, then cleaned up.
	 *
	 * @param string $json The original JSON string to process.
	 *
	 * @return string Indented version of the original JSON string.
	 */
	public function pretty_json($json) {
		$_result = null;
		$_pos = 0;
		$_length = strlen($json);
		$_indentString = '  ';
		$_newLine = PHP_EOL;
		$_lastChar = null;
		$_outOfQuotes = true;

		for ($_i = 0; $_i < $_length; $_i++) {
			//	Grab the next character in the string.
			$_char = $json[$_i];

			// Put spaces around colons
			if ($_outOfQuotes && ':' == $_char && ' ' != $_lastChar) {
				$_result .= ' ';
			}

			if ($_outOfQuotes && ' ' != $_char && ':' == $_lastChar) {
				$_result .= ' ';
			}

			// Are we inside a quoted string?
			if ('"' == $_char && '\\' != $_lastChar) {
				$_outOfQuotes = !$_outOfQuotes;
				// If this character is the end of an element,
				// output a new line and indent the next line.
			} else if (($_char == '}' || $_char == ']') && $_outOfQuotes) {
				$_result .= $_newLine;
				$_pos--;
				for ($_j = 0; $_j < $_pos; $_j++) {
					$_result .= $_indentString;
				}
			}

			//	Add the character to the result string.
			$_result .= $_char;

			//	If the last character was the beginning of an element output a new line and indent the next line.
			if ((',' == $_char || '{' == $_char || '[' == $_char) && $_outOfQuotes) {
				$_result .= $_newLine;

				if ('{' == $_char || '[' == $_char) {
					$_pos++;
				}

				for ($_j = 0; $_j < $_pos; $_j++) {
					$_result .= $_indentString;
				}
			}

			$_lastChar = $_char;
		}

		return $_result;
	}

	/**
	 * @param mixed  $result
	 * @param int    $code
	 * @param string $format
	 * @param string $as_file
	 * @param bool   $exitAfterSend
	 *
	 * @throws \DreamFactory\Platform\Exceptions\BadRequestException
	 * @return bool|\Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function sendResults($result, $code = self::Ok, $format = 'json', $as_file = null, $exitAfterSend = true) {
		self::log('sendResults: ' . $this->api_current_call, 'trace', $this->id);

		//	Some REST services may handle the response, they just return null
		if (is_null($result)) {
			Yii::app()->end(0, TRUE);

			return;
		}
		self::log('Results: ' . print_r($result, true), 'trace', $this->id);
		// Set all response as 200
		$code = self::Ok;

		switch ($format) {
		case 'json':
			$_contentType = 'application/json; charset=utf-8';

			if (!is_string($result)) {
				$result = $this->jsonEncode($result);
			}

			// JSON if no callback
			if (isset($_GET['callback'])) {
				$result = "{$_GET['callback']}($result);";
			}
			break;

		case 'xml':
			$_contentType = 'application/xml';
			$result = '<?xml version="1.0" ?>' . "<dfapi>$result</dfapi>";
			break;

		case 'csv':
			$_contentType = 'text/csv';
			break;

		default:
			$_contentType = 'application/octet-stream';
			break;
		}

		/* gzip handling output if necessary */
		ob_start();
		ob_implicit_flush(0);

		if (!headers_sent()) {
			// headers
			//$code = static::getHttpStatusCode($code);
			//$_title = static::getHttpStatusCodeTitle($code);
			$_title = $code;
			header("HTTP/1.1 $code $_title");
			// CORS support?
			//header('Access-Control-Allow-Origin: *', true);
			header("Content-Type: $_contentType", true);
			//	IE 9 requires hoop for session cookies in iframes
			header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"', true);

			if (!empty($as_file)) {
				header("Content-Disposition: attachment; filename=\"$as_file\";", true);
			}
		}

		// send it out
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Accept, Content-Type, X-KTV-Application-Name, X-KTV-Vendor-Name, X-KTV-Application-Platform, X-KTV-User-Token");
		echo $result;

		// flush output and destroy buffer
		ob_end_flush();

		// log to trace
		if ($exitAfterSend) {
			Yii::app()->end(0, TRUE);
		}

		return $result;
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array();
		/*
			          return array(
			          array('allow', // allow authenticated user to perform user 'view' and 'update' actions
			          'controllers' => array('user'),
			          'actions' => array('view', 'update'),
			          'users' => array('@'),
			          'roles' => array('member', 'reader'),
			          ),
			          array('allow', // allow authenticated user to perform user 'view' and 'update' actions
			          'controllers' => array('user'),
			          'actions' => array('index'),
			          'users' => array('@'),
			          'roles' => array('member'),
			          ),
			          array('allow', // allow admin user to perform all actions
			          'users' => array('@'),
			          'roles' => array('super'),
			          ),
			          array('deny', // deny all guest users to access user controller
			          'controllers' => array('user'),
			          'users' => array('*'),
			          ),
			          );
		*/
	}

	/**
	 * Get client ip address
	 * @return string Client ip address
	 */
	public static function getRealIpAddr() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if (!empty($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip = '0.0.0.0';
		}
		return $ip;
	}

	public static function recordStatistics($api = '', $id = '') {
		$ip = self::getRealIpAddr();
		$cur_time = time();
		$request_param = serialize($_REQUEST);
		$server_param = serialize($_SERVER);
		$stat_sql = "INSERT INTO {{statistics}} (calltime, call_api, call_id, call_ip, req_param, head_param) VALUES ( :p1, :p2, :p3, :p4, :p5, :p6 )";
		Yii::app()->getDb()->createCommand()->setText($stat_sql)->execute(array(':p1' => $cur_time, ':p2' => $api, ':p3' => $id, ':p4' => $ip, ':p5' => $request_param, ':p6' => $server_param));
	}

	public function beforeAction($action) {
		if (parent::beforeAction($action)) {
			// TODO: clean idle user status
			// user valid time
			$valid_time = (empty(Yii::app()->params['user_valid_time']) ? self::USER_VALID_TIME : Yii::app()->params['user_valid_time']);
			// reset expired user checkin, forever empty, so comment
			//$expired_uids = YiiSession::model()->getExpiredUids($valid_time);
			//if (!empty($expired_uids)) {
			//    self::log('Expired Users: ' . print_r($valid_uids, true), CLogger::LEVEL_TRACE, $this->id);
			//    $criteria = new CDbCriteria;
			//    $criteria->compare('uid', $expired_uids);
			//    CheckinUser::model()->deleteAll($criteria);
			//}
			/*
	              // check valid user sessions, clean invalid users
	              $valid_uids = YiiSession::model()->getValidUids($valid_time);
	              if (!empty($valid_uids)) {
	              self::log('Valid Users: ' . print_r($valid_uids, true), CLogger::LEVEL_TRACE, $this->id);
	              $criteria = new CDbCriteria;
	              $criteria->addNotInCondition('uid', $valid_uids);
	              CheckinUser::model()->deleteAll($criteria);
	              } else {
	              self::log('Clean all checkin status ', CLogger::LEVEL_TRACE, $this->id);
	              CheckinUser::model()->deleteAll();
	              }
*/
			// clear idle users
			//$idle_time = (empty(Yii::app()->params['user_idle_time']) ? self::USER_IDLE_TIME : Yii::app()->params['user_idle_time']);
			//$idle_uids = YiiSession::model()->getIdleUids($idle_time);
			//if (!empty($idle_uids)) {
			//    self::log('Idle Users: ' . print_r($idle_uids, true), CLogger::LEVEL_TRACE, $this->id);
			//    $criteria = new CDbCriteria;
			//    $criteria->compare('uid', $idle_uids);
			//    CheckinUser::model()->deleteAll($criteria);
			//}
			// check current API call
			$this->api_current_call = $this->getId() . '/' . $action->id;
			self::log('Before action call: ' . $this->api_current_call, 'trace', $this->id);
			Yii::app()->session['current_api'] = $this->api_current_call;
			if (in_array($this->api_current_call, self::$api_array)) {
				// TODO record normal action history to statistics log
				//if (!in_array($this->api_current_call, self::$api_session_array)) {
				//    self::recordStatistics($this->api_current_call, 0);
				//}
				// Response format data
				$result_array = array(
					'result' => self::BadRequest,
					'msg' => Yii::t('user', 'Request method illegal!'),
				);
				if (!empty($this->_vendorKey)) {
					$vendorID = Vendor::model()->getVendorID($this->_vendorKey);
					$this->_vendorID = $vendorID;
				}
				if ($this->_vendorID <= 0) {
					self::log('Error Vendor Key: ' . $this->_vendorKey, CLogger::LEVEL_ERROR, $this->id);
					$result_array['msg'] = Yii::t('user', 'Illegal Vendor Key!');
					$this->sendResults($result_array, self::BadRequest);
				}

				if (!empty($this->_appKey)) {
					$appID = Application::model()->getApplicationID($this->_appKey);
					$this->_appID = $appID;
				}
				if ($this->_appID <= 0) {
					self::log('Error App Key: ' . $this->_appKey, CLogger::LEVEL_ERROR, $this->id);
					$result_array['msg'] = Yii::t('user', 'Illegal App Key!');
					$this->sendResults($result_array, self::BadRequest);
				}

				if (in_array($this->api_current_call, self::$api_token_array) && empty($this->_token)) {
					self::log('Empty Token Id', CLogger::LEVEL_ERROR, $this->id);
					$result_array['msg'] = Yii::t('user', 'Illegal Token Id!');
					$this->sendResults($result_array, self::BadRequest);
				}

				// check wheather qr code expired
				if (in_array($this->api_current_call, self::$api_checkin_array)) {
					$_room_id = $this->_roomID;

					// get available qR code
					$qrcode = CheckinCode::model()->findByAttributes(array('room_id' => $_room_id));
					if (!is_null($qrcode) && !empty($qrcode)) {
						$_qr_code = $qrcode->code;
						$this->_checkinCode = $qrcode->code;
						$_expire_time = intval($qrcode->expire);
						$_current_time = time();

						// TODO: also check room check in status, get the largest expire time
						$_checkinstate = CheckinState::model()->findByAttributes(array('room_id' => $_room_id, 'code' => $_qr_code));
						if (!is_null($_checkinstate) && !empty($_checkinstate)) {
							$_room_expire_time = $_checkinstate->expire;
							if ($_expire_time < $_room_expire_time) {
								$_expire_time = $_room_expire_time;
							}
						}
						// check expire time
						if ($_expire_time !== 0 && $_expire_time < $_current_time) {
							// TODO: Reset user check in list
							CheckinUser::model()->deleteAllByAttributes(array('room_id' => $_room_id));
							CheckinState::model()->deleteAllByAttributes(array('room_id' => $_room_id));

							self::log('Check in expired!', CLogger::LEVEL_ERROR, $this->id);
							$result_array['result'] = self::Forbidden;
							$result_array['msg'] = Yii::t('user', 'Check in expired!');
							$this->sendResults($result_array, self::BadRequest);
						}
					} else {
						// TODO: Reset user check in list
						CheckinUser::model()->deleteAllByAttributes(array('room_id' => $_room_id));
						CheckinState::model()->deleteAllByAttributes(array('room_id' => $_room_id));

						self::log('Check in expired!', CLogger::LEVEL_ERROR, $this->id);
						$result_array['result'] = self::Forbidden;
						$result_array['msg'] = Yii::t('user', 'Check in expired!');
						$this->sendResults($result_array, self::BadRequest);
					}
				}

				if (in_array($this->api_current_call, self::$api_room_array) && empty($this->_roomID)) {
					self::log('Empty Room Id', CLogger::LEVEL_ERROR, $this->id);
					$result_array['msg'] = Yii::t('user', 'Illegal Room Id!');
					$this->sendResults($result_array, self::BadRequest);
				}

				if (in_array($this->api_current_call, self::$api_session_array)) {
					// check valid session,
					try {
						// using userId from session
						$_userId = $this->validateSession($this->api_current_call);
						// TODO record user action history to statistics log
						self::recordStatistics($this->api_current_call, $_userId);
						// TODO auto check in to room ?
						if ($_userId > 0 && !empty($this->_roomID)) {
							try {
								$_checkinuser = CheckinUser::model()->findByAttributes(array('uid' => $_userId, 'room_id' => $this->_roomID));
								if (is_null($_checkinuser) || empty($_checkinuser)) {
									$_checkinuser = new CheckinUser;
									$_checkinuser->checkin_time = time();
									$_checkinuser->uid = $_userId;
									$_checkinuser->room_id = $this->_roomID;
									$_checkinuser->save();
								}
							} catch (Exception $ex) {
								self::log('Set check in status error: ' . $ex->getMessage(), CLogger::LEVEL_ERROR, $this->id);
							}
						}
					} catch (Exception $ex) {
						// reset expired session checkin status
						$result_array['result'] = self::Unauthorized;
						$result_array['msg'] = $ex->getMessage();
						$this->sendResults($result_array, self::BadRequest);
					}
				}
				// TODO Record day statistics log
				if (in_array($this->api_current_call, self::$api_stat_array)) {
					$_call_type = '';
					$_current_user = $this->getAppUser();
					if (!is_null($_current_user)) {
						$_call_type = $_current_user->auth_type;
					}
					AbiStat::apiDayCountStat($this->api_current_call, $_call_type);
				}
			}

			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param string  $api_call
	 * @throws Exception
	 * @return string
	 */
	public function validateSession($api_call) {
		// helper for non-browser-managed sessions
		$_sessionId = $this->_token;

		$_oldSessionId = session_id();
		if (!empty($_sessionId) && $_sessionId !== $_oldSessionId) {
			if (!empty($_oldSessionId)) {
				@session_unset();
				@session_destroy();
			}

			session_id($_sessionId);

			if (!session_start()) {
				self::log('Failed to start session "' . $_sessionId . '" from header: ' . print_r($_SERVER, true), CLogger::LEVEL_ERROR, $this->id);
			}
		}

		Yii::app()->session['current_api'] = $api_call;

		if (!Yii::app()->user->getIsGuest()) {
			return Yii::app()->user->getId();
		}

		throw new Exception("There is no valid session for the current request: " . $api_call . ", token: " . $this->_token);
	}

	/**
	 * @param string  $username
	 * @param string  $password
	 * @param integer $duration
	 * @param boolean $return_extras
	 * @param string  $auth_type
	 *
	 * @throws Exception
	 * @return boolean | PlatformUser
	 */
	public function userLogin($username, $password, $duration = 0, $return_extras = false, $auth_type = self::DEFAULT_AUTH_TYPE) {
		// clear old session
		$_oldSessionId = session_id();
		if (!empty($_oldSessionId)) {
			@session_write_close();
			@session_unset();
			@session_destroy();
			if (!session_start()) {
				self::log('Failed to clear session: ' . print_r($_SERVER, true), CLogger::LEVEL_ERROR, $this->id);
			}
		}

		/** @var PlatformUser $_user */
		$_user = PlatformUser::model()->loginRequest($username, $password, $duration, $auth_type);

		// write back login datetime
		$_user->saveAttributes(array(
			'last_login_time' => date("Y-m-d H:i:s", time()),
		));

		// TODO add points
		if (!in_array(strtoupper($auth_type), $this->_other_auth_types)) {
			//self::processPoints($_user['id'], '2', '1');
		}

		if ($return_extras) {
			// 	Additional stuff for session
			return $_user;
		}
		return true;
	}

	/**
	 *
	 */
	public function userLogout() {
		// helper for non-browser-managed sessions
		$_sessionId = $this->_token;

		$_userid = 0;
		if (!empty($_sessionId)) {
			session_write_close();
			session_id($_sessionId);

			if (session_start()) {
				$_userid = Yii::app()->user->getId();
				if (session_id() !== '') {
					@session_unset();
					@session_destroy();
				}
			}
		}

		// And logout browser session
		if (!Yii::app()->user->getIsGuest()) {
			Yii::app()->user->logout();
		}

		return $_userid;
	}

	/**
	 * @param array $data
	 * @param bool  $login
	 * @param integer  $type
	 * @param bool  $is_oauth
	 * @param string  $oauth_type
	 *
	 * @throws Exception
	 * @return PlatformUser
	 */
	public function userRegister($data, $login = true, $type = 0, $is_oauth = false, $oauth_type = self::DEFAULT_AUTH_TYPE) {

		$_username = isset($data['username']) ? $data['username'] : '';
		if (empty($_username)) {
			throw new Exception("The username field for registering a user can not be empty.");
		}

		$_newPassword = isset($data['password']) ? $data['password'] : '';
		if (empty($_newPassword)) {
			throw new Exception("The password field for registering a user can not be empty.");
		}
		$_oauth_type = isset($data['type']) ? $data['type'] : '';
		if (!empty($_oauth_type)) {
			if (in_array(strtoupper($_oauth_type), $this->_other_auth_types)) {
				$oauth_type = strtoupper($_oauth_type);
			}
		}
		$_cid = isset($data['cid']) ? $data['cid'] : '';

		$_roleId = 'reader';
		$_openid = isset($data['openid']) ? $data['openid'] : '';
		$_display_name = isset($data['display_name']) ? $data['display_name'] : $_username;
		$_avatar_url = isset($data['avatar_url']) ? $data['avatar_url'] : '';

		// Registration, check for email validation required
		//$_theUser = PlatformUser::model()->find('username=:username and type=:type', array(':username' => $_username, ':type' => $type));
		$_theUser = PlatformUser::model()->find('username=:username and auth_type=:type', array(':username' => $_username, ':type' => $oauth_type));
		if (null !== $_theUser) {
			// if user exists and is not oauth register, then throw register error
			if (true !== $is_oauth) {
				throw new Exception("A registered user already exists with the username '$_username'.");
			}
			// update avatar
			$_theUser->password = $_newPassword;
			$_theUser->display_name = empty($_display_name) ? $_theUser->display_name : $_display_name;
			$_theUser->avatar_url = empty($_avatar_url) ? $_theUser->avatar_url : $_avatar_url;
			// get profile information
			$_profile = array();
			if (!empty($_theUser->profile_data)) {
				$_profile = unserialize($_theUser->profile_data);
			}
			$_profile['cid'] = $_cid;
			$_theUser->profile_data = serialize($_profile);
			// now update
			$_theUser->save();
		} else {
			try {
				$_theUser = new PlatformUser();
				$_theUser->username = $_username;
				$_theUser->password = $_newPassword;
				$_theUser->password_repeat = $_newPassword;
				$_theUser->role = $_roleId;
				$_theUser->type = $type;
				$_theUser->auth_type = $oauth_type;
				$_theUser->openid = $_openid;
				$_theUser->display_name = $_display_name;
				$_theUser->avatar_url = $_avatar_url;

				// add profile information
				$_profile['cid'] = $_cid;
				$_theUser->profile_data = serialize($_profile);

				//$_theUser->setAttribute('password', $_newPassword);
				if (!$_theUser->save()) {
					$_arerror = print_r($_theUser->getErrors(), true);
					throw new Exception("Failed to save new user!\n{$_arerror}", 0);
				}
			} catch (Exception $ex) {
				throw new Exception("Failed to register new user!\n{$ex->getMessage()}", $ex->getCode());
			}
		}

		if ($login) {
			try {
				return $this->userLogin($_theUser->username, $_newPassword, 0, true, $oauth_type);
			} catch (Exception $ex) {
				throw new Exception("Registration complete, but failed to create a session.\n{$ex->getMessage()}", $ex->getCode());
			}
		}

		return $_theUser;
	}

	/**
	 * @param array $data
	 * @param bool  $logout
	 * @param bool  $return_extras
	 * @param string  $auth_type
	 *
	 * @throws Exception
	 * @return string new password
	 */
	public function userResetPassword($data, $logout = true, $type = 0, $auth_type = self::DEFAULT_AUTH_TYPE) {

		$_username = isset($data['username']) ? $data['username'] : '';
		if (empty($_username)) {
			throw new Exception("The username field for reset password can not be empty.");
		}

		$_newPassword = substr(uniqid(), 0, 8);
		if (empty($_newPassword)) {
			throw new Exception("The reset password generated error.");
		}

		// Registration, check for email validation required
		$_theUser = PlatformUser::model()->find('username=:username and type=:type', array(':username' => $_username, ':type' => $auth_type));
		if (null === $_theUser) {
			throw new Exception("User does not exists with the username '$_username'.");
		}

		try {
			$_theUser->password = $_newPassword;

			//$_theUser->setAttribute('password', $_newPassword);
			if (!$_theUser->save()) {
				$_arerror = print_r($_theUser->getErrors(), true);
				throw new Exception("Failed to reset password!\n{$_arerror}", 0);
			}
		} catch (Exception $ex) {
			throw new Exception("Failed to reset password!\n{$ex->getMessage()}", $ex->getCode());
		}

		if ($logout) {
			$this->userLogout();
		}

		return $_newPassword;
	}

	/**
	 * @param $arr
	 *
	 * @return array
	 */
	public function rearrangePostedFiles($arr) {
		$new = array();
		foreach ($arr as $key => $all) {
			if (is_array($all)) {
				foreach ($all as $i => $val) {
					$new[$i][$key] = $val;
				}
			} else {
				$new[0][$key] = $all;
			}
		}

		return $new;
	}

	/**
	 * @param string $folderPath
	 * @param array $files
	 * @param bool  $extract
	 * @param bool  $clean
	 * @param bool  $checkExist
	 *
	 * @return array
	 * @throws Exception
	 */
	public function _handlePostedFiles($folderPath, $files, $extract = false, $clean = false, $checkExist = false) {
		$out = array();
		$err = array();
		foreach ($files as $key => $file) {
			$name = $file['name'];
			$error = $file['error'];
			if ($error == UPLOAD_ERR_OK) {
				$tmpName = $file['tmp_name'];
				$contentType = $file['type'];
				$tmp = $this->_handleFile(
					$folderPath, $name, $tmpName, $contentType, $extract, $clean, $checkExist
				);
				$out[$key] = (isset($tmp['file']) ? $tmp['file'] : array());
			} else {
				$err[] = $name;
			}
		}
		if (!empty($err)) {
			$msg = 'Failed to upload the following files to folder ' . $folderPath . ': ' . implode(', ', $err);
			throw new Exception($msg);
		}

		return array('file' => $out);
	}

	/**
	 * @param        $dest_path
	 * @param        $dest_name
	 * @param        $source_file
	 * @param string $contentType
	 * @param bool   $extract
	 * @param bool   $clean
	 * @param bool   $check_exist
	 *
	 * @throws Exception
	 * @return array
	 */
	protected function _handleFile($dest_path, $dest_name, $source_file, $contentType = '', $extract = false, $clean = false, $check_exist = false) {
		$ext = pathinfo($source_file, PATHINFO_EXTENSION);

		$name = (empty($dest_name) ? basename($source_file) : $dest_name);
		$fullPathName = $this->fixFolderPath($dest_path) . $name;
		$this->moveFile($fullPathName, $source_file, $check_exist);

		//return array('file' => array(array('name' => $name, 'path' => $fullPathName)));
		return array('file' => (array('name' => $name, 'path' => $fullPathName)));
	}

	/**
	 * @param $path
	 *
	 * @return string
	 */
	public function fixFolderPath($path) {
		if (!empty($path)) {
			$path = rtrim($path, '/') . '/';
		}

		return $path;
	}

	/**
	 * @param $path
	 *
	 * @return string
	 */
	public function getParentFolder($path) {
		$path = rtrim($path, '/'); // may be a folder

		$marker = strrpos($path, '/');

		if (false === $marker) {
			return '';
		}

		return substr($path, 0, $marker);
	}

	/**
	 * @param string $path
	 * @param string $local_path
	 * @param bool   $check_exist
	 *
	 * @throws Exception
	 * @return void
	 */
	public function moveFile($path, $local_path, $check_exist = true) {
		// does local file exist?
		if (!file_exists($local_path)) {
			throw new Exception("File '$local_path' does not exist.");
		}
		// does this file already exist?
		if (is_file($path)) {
			if (($check_exist)) {
				throw new Exception("File '$path' already exists.");
			}
		}
		// does this file's parent folder exist?
		$_parent = $this->getParentFolder($path);
		$_parent = $this->fixFolderPath($_parent);
		if (!empty($_parent) && (!is_dir($_parent))) {
			if (false === @mkdir($_parent, 0777, true)) {
				throw new Exception("Folder '$_parent' does not exist. " . 'Failed to create folder: ' . $path);
			}
		}

		// create the file
		if (!rename($local_path, $path)) {
			throw new Exception("Failed to move file '$path'");
		}
	}

	/**
	 * Logs a message.
	 *
	 * @param string $message Message to be logged
	 * @param string $level Level of the message (e.g. 'error', 'info', 'trace', 'warning',
	 * @param string $category Message category to be logged
	 * 'error', 'info', see CLogger constants definitions)
	 */
	public static function log($message, $level = 'info', $category = 'Default') {
		Yii::log($message, $level, __CLASS__ . '.' . $category . '.' . $level);
	}

	/**
	 * 最简单的XML转数组
	 * @param string $xmlstring XML字符串
	 * @return array XML数组
	 */
	public function simplest_xml_to_array($xmlstring) {
		return json_decode(json_encode((array) simplexml_load_string($xmlstring)), true);
	}

	/**
	 * Get APP User Object
	 * @return PlatformUser
	 */
	public function getAppUser() {
		if ($this->_appUser === null) {
			$_uid = Yii::app()->user->getId();
			$_uid = empty($_uid) ? 0 : intval($_uid);
			if ($_uid > 0) {
				$this->_appUser = PlatformUser::model()->findByPk($_uid);
			}
		}
		return $this->_appUser;
	}

	public function createRandNumberByLength($length) {
		$length = (int) $length;
		if ($length === 0) {
			return '';
		}

		$rankNumberString = "";
		for ($i = 0; $i < $length + 1; $i++) {
			if ($i !== 0 && $i % 2 === 0) {
				$rankNumberString .= mt_rand(11, 99);
			}
		}

		if ($length % 2 === 0) {
			return $rankNumberString;
		} else {
			return $rankNumberString . mt_rand(1, 9);
		}
	}

	/**
	 * 发送个推信息给用户
	 * @param type $cid 用户个推ID
	 * @param type $msg 个推信息
	 * @param type $title 信息标题
	 * @return Array {result:successed_offline,taskId:xxx}  || {result:successed_online,taskId:xxx} || {result:error}
	 */
	public static function sendNotifyToUser($cid, $msg, $title = 'Notification', $type = 0) {
		self::log('Send notify | ' . $msg . ' | to CID: ' . $cid, 'info', 'notify');
		$push_array[] = $cid;

		ob_start();
		$result = PHPGetui::pushToUserListTrans($push_array, $title, $msg, $type);
		ob_end_clean();

		$ret_msg = isset($result['result']) ? $result['result'] : 'unknown';
		self::log('Send notify result: ' . $ret_msg . ' of CID: ' . $cid, 'info', 'notify');
		return $result;
	}

	/**
	 * 发送订单提醒给微信客户端处理
	 */
	public static function sendNotifyToWechat($xktvid, $userid, $orderid, $cancel = '') {
		$data = array("ktvid" => $xktvid, "userid" => $userid, "orderid" => $orderid);
		$data_string = json_encode($data);
		if ($cancel == 'cancel') {

			$ch = curl_init('http://letsktv.chinacloudapp.cn/wechatshangjia/WeChat/sendTemplateCancelMessage');
		} else {

			$ch = curl_init('http://letsktv.chinacloudapp.cn/wechatshangjia/WeChat/sendTemplateMessage');
		}
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);
		$result = curl_exec($ch);
	}

	public static function makeSjCoupon($xktvid, $userid, $orderid, $sjqtype, $expire_time) {

		$coupon = new Coupon();
		$coupon->orderid = $orderid;
		$coupon->userid = $userid;
		$coupon->type = $sjqtype;
		$coupon->expire_time = $expire_time;
		if ($coupon->save()) {
			$data = array("coupon_id" => $coupon->id);
			$data_string = json_encode($data);
			$ch = curl_init('http://letsktv.chinacloudapp.cn/wechatshangjia/Activity/makeSjCoupon');
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
			);
			$result = curl_exec($ch);
			$img_data = json_decode($result, true);
			if ($img_data['status'] == '0') {
				$coupon->qrcodeid = $img_data['qrcodeid'];
				$coupon->qrcode_img = $img_data['qrimg'];
				$coupon->save();
			}
		}

	}

	public static function makeOrderQrcode($orderid) {

		$data = array("order_id" => $orderid);
		$data_string = json_encode($data);
		$ch = curl_init('http://letsktv.chinacloudapp.cn/wechatshangjia/Activity/makeOrderQrcode');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);
		$result = curl_exec($ch);
		// var_dump($result);die();
		$img_data = json_decode($result, true);
		if ($img_data['status'] == '0') {
			return $img_data['qrimg'];
			// $coupon->qrcode_img = $img_data['qrimg'];
			// $coupon->save();
		}

	}

	/**
	 * 用户积分处理
	 * @param type $uid 用户ID
	 * @param type $activity_id 活动ID
	 * @param type $source_id 积分来源ID
	 * @return Array
	 */
	public static function processPoints($uid, $activity_id, $source_id) {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'points' => '',
			'total' => '',
		);
		if (empty($uid) || empty($activity_id) || empty($source_id)) {
			return $result_array;
		}

		$_qrcode = ActivityQrcode::model()->findByAttributes(array('activity_id' => $activity_id, 'code' => $source_id));
		if (!is_null($_qrcode) && !empty($_qrcode)) {
			$_userid = $uid;

			$_point = intval($_qrcode->points);
			$_userlimit = $_qrcode->userlimit;
			$_userdaylimit = $_qrcode->userdaylimit;
			$_usagelimit = $_qrcode->usagelimit;
			// check total points
			$_usagetotal = intval($_qrcode->usagetotal);
			$_usedtotal = intval($_qrcode->usedtotal);
			if (($_usedtotal + $_point) > $_usagetotal) {
				$result_array['msg'] = Yii::t('user', 'Exceed total points limitation!');
				return $result_array;
			}
			// check total limit
			//if (!empty($_usagelimit) && $_qrcode->usages >= $_usagelimit) {
			//    $result_array['msg'] = Yii::t('user', 'Exceed total limitation!');
			//    return $result_array;
			//}
			// check user limit
			if (!empty($_userlimit)) {
				$_usercount = PointsHistory::model()->countByAttributes(array('user_id' => $_userid, 'activity_id' => $activity_id, 'code' => $source_id));
				if (!empty($_usercount) && $_usercount >= $_userlimit) {
					$result_array['msg'] = Yii::t('user', 'Exceed user times limitation!');
					return $result_array;
				}
			}
			// update points record
			$_qrcode->usages = $_qrcode->usages + 1;
			$_qrcode->usedtotal = $_qrcode->usedtotal + $_point;
			$_qrcode->save();

			$_points_current = $_point;
			$_points_before = 0;
			// count user points
			$_userpoints = UserPoints::model()->findByAttributes(array('user_id' => $_userid));
			if (is_null($_userpoints) || empty($_userpoints)) {
				$_userpoints = new UserPoints;
				$_userpoints->user_id = $_userid;
				$_userpoints->status = 1;
				$_userpoints->points = 0;
				$_userpoints->rewards = 0;
				$_userpoints->redeems = 0;
			} else {
				$_points_before = $_userpoints->points;
			}
			if (1 == $activity_id) {
				$_userpoints->points = $_userpoints->points + $_point;
				$_userpoints->rewards = $_userpoints->rewards + $_point;
			} else if (2 == $activity_id) {
				$_userpoints->points = $_userpoints->points + $_point;
				$_userpoints->rewards = $_userpoints->rewards + $_point;
			} else if (3 == $activity_id) {
				$_userpoints->points = $_userpoints->points - $_point;
				$_userpoints->redeems = $_userpoints->redeems + $_point;
				$_points_current = 0 - $_point;
			}

			if ($_userpoints->points < 0) {
				$result_array['msg'] = Yii::t('user', 'No enough points!');
				return $result_array;
			} else {
				$_userpoints->save();
			}

			// add to point history
			$_pointshistory = new PointsHistory;
			$_pointshistory->user_id = $_userid;
			$_pointshistory->activity_id = $activity_id;
			$_pointshistory->code = $source_id;
			$_pointshistory->points_before = $_points_before;
			$_pointshistory->points = $_points_current;
			$_pointshistory->points_after = $_userpoints->points;
			$_pointshistory->duetime = time();

			$_pointshistory->save();
			self::log(' User ID ' . $uid . ': points from ' . $_points_before . ' to ' . $_userpoints->points, 'info', 'points');
			// success
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'Success!');
			$result_array['points'] = intval($_points_current);
			$result_array['total'] = $_userpoints->points;
		} else {
			self::log('No such activity and source to process points: ' . $activity_id . ' | ' . $source_id, CLogger::LEVEL_ERROR);
			$result_array['msg'] = Yii::t('user', 'Invalid activity and source id to process points!');
		}

		return $result_array;
	}

}
