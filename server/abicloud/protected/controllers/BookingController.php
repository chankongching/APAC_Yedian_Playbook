<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BookingController
 *
 * @author wingsun
 */
class BookingController extends ApiController {

	/**
	 * Option for return new list after list updated
	 */
	const CHECK_STAFF_ONDUTY = true;
	const ONDUTY_EXPIRED_TIME = 60;
	const ORDER_EXPIRED_TIME = 3600;
	const ORDER_EXPIRED_STATUS = 4;
	const ORDER_PENDING_STATUS = 1;
	const ORDER_KTV_AVAILABLE_LIMIT = 100;
	const ORDER_USER_AVAILABLE_LIMIT = 1;

	public $ROOM_INVALID_ORDER_STATUS = array(4, 5, 7, 14);
	public $ROOM_ORDER_MODIFIED_STATUS = array(17, 18, 19, 20, 3, 4, 5, 7);
	public $ROOM_ORDER_USER_MODIFIED_STATUS = array(17, 18, 19, 20);
	public $ROOM_ORDER_CONFIRM_STATUS = array(3);
	public $ROOM_ORDER_KTVLIMIT_STATUS = array(3, 5);
	public $ROOM_ORDER_NEW_STATUS = array(1, 3);
	public $ROOM_ORDER_STAFF_CONFIRMED_STATUS = array(3, 4, 5, 7);
	public $ROOM_ORDER_COMPLETED_STATUS = array(5, 7);
	public $ROOM_ORDER_CANCELLED_STATUS = array(4, 7);
	public $ROOM_ORDER_STAFF_MODIFIED_STATUS = array(3, 4, 7);
	public $ROOM_ORDER_REWARD_STATUS = array(5);
	protected $user_id = 0;
	protected $user_name;

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function beforeAction($action) {
		if (parent::beforeAction($action)) {
			// Get user id
			if (isset(Yii::app()->user) && null !== Yii::app()->user) {
				$_user_id = Yii::app()->user->getId();
			} else {
				$_user_id = 0;
			}
			$_user_id = empty($_user_id) ? 0 : $_user_id;

			$_user = PlatformUser::model()->findByPk($_user_id);
			if (!is_null($_user)) {
				$this->user_id = $_user->id;
				$this->user_name = $_user->username;
			}
			return true;
		} else {
			return false;
		}
	}

	public function actionRoomlist() {
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

		// Get query data
		$_offset = Yii::app()->request->getQuery('offset');
		$_limit = Yii::app()->request->getQuery('limit');
		$_offset = empty($_offset) ? 0 : intval($_offset);
		$_limit = empty($_limit) ? 100 : intval($_limit);

		$_bookingtime = Yii::app()->request->getQuery('bookingtime');
		if (empty($_bookingtime)) {
			$result_array['msg'] = 'Must provide parameter of bookingtime!';
			$this->sendResults($result_array, self::BadRequest);
		}
		$_temptime = strtotime($_bookingtime);
		if (FALSE === $_temptime) {
			$result_array['msg'] = 'The format of bookingtime error!';
			$this->sendResults($result_array, self::BadRequest);
		}

		$_booking_time = $_temptime;
		$_booking_duration = Yii::app()->request->getQuery('duration');
		$_booking_duration = empty($_booking_duration) ? 2 : intval($_booking_duration);
		$_booking_duration = ($_booking_duration < 1) ? 2 : $_booking_duration;

		$_room_type = Yii::app()->request->getQuery('roomtype');
		$_room_type = empty($_room_type) ? 3 : intval($_room_type);
		$_room_type = ($_room_type < 1) ? 3 : $_room_type;

		// log
		self::log('Query: ' . Yii::app()->request->getQueryString(), 'trace', $this->id);

		//$_booking_time_start = $_booking_time - 1;
		//$_booking_time_end = $_booking_time + $_booking_duration * 60 * 60 + 1;
		$_booking_time_start = $_booking_time;
		$_booking_time_end = $_booking_time + $_booking_duration * 60 * 60 - 1;
		// TODO:
		// get booked room
		// room_booked = (booking_time between ($_booking_time_start, booking_time) or (booking_time + duration between($_booking_time_start, booking_time + duration)
		$between_criteria = new CDbCriteria();
		$between_criteria->addBetweenCondition('booking_time', $_booking_time_start, $_booking_time_end);
		$between_criteria->addBetweenCondition('expire', $_booking_time_start, $_booking_time_end, 'OR');
		$booked_criteria = new CDbCriteria();
		$booked_criteria->addNotInCondition('room_status', $this->ROOM_INVALID_ORDER_STATUS);
		$booked_criteria->mergeWith($between_criteria);

		// select * from ak_room_booking where (room_status NOT IN (, 14)) AND ((booking_time BETWEEN 1409800686 AND 1409815088) OR (expire BETWEEN 1409800686 AND 1409815088))
		$room_booked_list = RoomBooking::model()->findAll($booked_criteria);
		$room_booked_id_array = array();
		if (!is_null($room_booked_list) && !empty($room_booked_list)) {
			foreach ($room_booked_list as $key => $_room) {
				$room_booked_id_array[] = $_room->room_id;
			}
		}
		// checked in rooms
		$room_checkin_list = CheckinCode::model()->findAll('expire IS NULL OR expire = 0 OR expire > :starttime', array(':starttime' => $_booking_time_start));
		if (!is_null($room_checkin_list) && !empty($room_checkin_list)) {
			foreach ($room_checkin_list as $key => $_room) {
				$room_booked_id_array[] = $_room->room_id;
			}
		}

		// get available room
		// room_available = (room_type = $_room_type and roomid not in(room_booked))
		// $_count = room_available->count;
		$available_criteria = new CDbCriteria();
		if (!empty($room_booked_id_array)) {
			$available_criteria->addNotInCondition('id', $room_booked_id_array);
		}
		$available_criteria->compare('room_type', $_room_type);

		$_count = intval(Room::model()->count($available_criteria));
		//$_count = intval(Room::model()->with(array('roomExtra'=>array('select'=>'price,smallpic_url,bigpic_url','condition'=>"room_type=${_room_type}")))->count($available_criteria));

		$available_criteria->offset = $_offset;
		$available_criteria->limit = $_limit;
		//$room_list = Room::model()->with(array('roomExtra'=>array('select'=>'price,smallpic_url,bigpic_url','condition'=>"room_type=${_room_type}")))->findAll($available_criteria);
		$room_list = Room::model()->findAll($available_criteria);
		//print_r($room_list);die();
		if (!empty($room_list)) {
			// Get list success
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'Get room list success!');
			$result_array['total'] = $_count;
			foreach ($room_list as $key => $_room) {
				//if (!empty($_room->roomExtra) && isset($_room->roomExtra->room_type) && $_room->roomExtra->room_type == $_room_type) {
				//$_price = (empty($_room->roomExtra) || empty($_room->roomExtra->price)) ? 0 : $_room->roomExtra->price;
				//$_s_pic_url = (empty($_room->roomExtra) || empty($_room->roomExtra->smallpic_url)) ? '' : $_room->roomExtra->smallpic_url;
				//$_b_pic_url = (empty($_room->roomExtra) || empty($_room->roomExtra->bigpic_url)) ? '' : $_room->roomExtra->bigpic_url;
				$_price = (empty($_room->price)) ? 0 : $_room->price;
				$_s_pic_url = (empty($_room->smallpic_url)) ? '' : $_room->smallpic_url;
				$_b_pic_url = (empty($_room->bigpic_url)) ? '' : $_room->bigpic_url;
				$result_array['list'][] = array(
					'roomid' => $_room->roomid,
					'roomname' => $_room->name,
					'description' => $_room->description,
					'price' => floatval($_price),
					'smallpicurl' => $this->getRoomPicUrl($_s_pic_url, 0),
					'bigpicurl' => $this->getRoomPicUrl($_b_pic_url),
				);
				//}
			}
		} else {
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'No available room for booking!');
			$result_array['total'] = $_count;
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionCaculateorder() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			"order_amount" => 0,
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}

		// Get post data
		$post_data = Yii::app()->request->getPost('CaculateOrderRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		self::log('Room booking data: ' . print_r($post_data, TRUE), 'trace', $this->id);
		//Yii::trace(print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		$_ktvid = isset($post_array['ktvid']) ? $post_array['ktvid'] : '';
		$_roomtype = isset($post_array['roomtype']) ? $post_array['roomtype'] : '';
		$_starttime = isset($post_array['starttime']) ? $post_array['starttime'] : '';
		$_endtime = isset($post_array['endtime']) ? $post_array['endtime'] : '';
		$_members = isset($post_array['members']) ? intval($post_array['members']) : 1;

		if (empty($_ktvid)) {
			$result_array['msg'] = 'Must provide parameter of ktvid!';
			$this->sendResults($result_array, self::BadRequest);
		}
		if (empty($_roomtype)) {
			$result_array['msg'] = 'Must provide parameter of room type!';
			$this->sendResults($result_array, self::BadRequest);
		}
		if (empty($_starttime)) {
			$result_array['msg'] = 'Must provide parameter of start time!';
			$this->sendResults($result_array, self::BadRequest);
		}
		$_temptime = strtotime($_starttime);
		if (FALSE === $_temptime) {
			$result_array['msg'] = 'The format of starttime error!';
			$this->sendResults($result_array, self::BadRequest);
		}
		$_booking_time_start = $_temptime;

		if (empty($_endtime)) {
			$result_array['msg'] = 'Must provide parameter of end time!';
			$this->sendResults($result_array, self::BadRequest);
		}
		$_temptime = strtotime($_endtime);
		if (FALSE === $_temptime) {
			$result_array['msg'] = 'The format of endtime error!';
			$this->sendResults($result_array, self::BadRequest);
		}
		$_booking_time_end = $_temptime;

		$xktv_criteria = new CDbCriteria();
		$xktv_criteria->compare('xktvid', $_ktvid);
		$xktv = Xktv::model()->find($xktv_criteria);
		if (!is_null($xktv) && !empty($xktv)) {
			switch ($_roomtype) {
			case 1:
				$_price = $xktv->roombigprice;
				break;
			case 2:
				$_price = $xktv->roommediumprice;
				break;
			case 3:
				$_price = $xktv->roomsmallprice;
				break;
			default:
				$_price = $xktv->roomsmallprice;
				break;
			}

			$amount = floatval($_price * ($_booking_time_end - $_booking_time_start) / 3600);
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'Caculate order success!');
			$result_array['order_amount'] = floatval($amount);
		} else {
			$result_array['msg'] = Yii::t('user', 'No available room for booking!');
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionCancelorder() {
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
		$post_data = Yii::app()->request->getPost('CancelOrderRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		self::log('Room booking data: ' . print_r($post_data, TRUE), 'trace', $this->id);
		// Decode post data
		$post_array = json_decode($post_data, true);
		$_ordercode = isset($post_array['ordercode']) ? $post_array['ordercode'] : '';
		$_ktvid = isset($post_array['ktvid']) ? $post_array['ktvid'] : '';
		if (empty($_ordercode)) {
			$result_array['msg'] = Yii::t('user', 'Order Empty!');
			$this->sendResults($result_array, self::BadRequest);
		}
		$_order = RoomBooking::model()->findByAttributes(array('code' => $_ordercode, 'ktvid' => $_ktvid));

		if (!is_null($_order) && !empty($_order)) {
			$_order->status = 7;
			if ($_order->save()) {
				if ($_order->couponid > 0) {
					$coupon = Coupon::model()->findByPk($_order->couponid);
					$coupon->is_available = 1;
					if ($coupon->save()) {
						$result_array['coupon_msg'] = 'cancel coupon success';
					} else {

						$result_array['coupon_msg'] = 'cancel coupon failed';
					}
				}
				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'Cancel room booking success!');
//				Coupon::model()->ExpireByUserCancelOrder($this->user_id, $_order->id);
				// send order notify to KTV staff
				//				$_staff = PlatformUser::model()->findByAttributes(array('username' => $_ktvid));
				//				if (!is_null($_staff) && !empty($_staff)) {
				//					$cid = $_staff->cid;
				//					$title = "有用户取消了订单!";
				//					$msg = "有用户取消了订单!";
				//					self::sendNotifyToUser($cid, $msg, $title);
				//				}
				$_xktv = Xktv::model()->findByAttributes(array('xktvid' => $_ktvid));
				if ($_xktv['type'] == '2') {
					self::sendNotifyToWechat($_xktv['id'], $this->user_id, $_order->id, 'cancel');
				}
			} else {
				$result_array['msg'] = Yii::t('user', 'Cancel room booking failed!');
			}
		} else {
			$result_array['msg'] = Yii::t('user', 'Order Not Exist!');
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionDeleteorder() {
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
		$post_data = Yii::app()->request->getPost('CancelOrderRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		self::log('Room booking data: ' . print_r($post_data, TRUE), 'trace', $this->id);
		// Decode post data
		$post_array = json_decode($post_data, true);
		$_ordercode = isset($post_array['ordercode']) ? $post_array['ordercode'] : '';
		$_ktvid = isset($post_array['ktvid']) ? $post_array['ktvid'] : '';
		if (empty($_ordercode)) {
			$result_array['msg'] = Yii::t('user', 'Order Empty!');
			$this->sendResults($result_array, self::BadRequest);
		}
		$_order = RoomBooking::model()->findByAttributes(array('code' => $_ordercode, 'ktvid' => $_ktvid));

		if (!is_null($_order) && !empty($_order)) {
			$_order->deleted = 1;
			if ($_order->save()) {
				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'Delete booking success!');
				// send order notify to KTV staff
				//				$_staff = PlatformUser::model()->findByAttributes(array('username' => $_ktvid));
				//				if (!is_null($_staff) && !empty($_staff)) {
				//					$cid = $_staff->cid;
				//					$title = "有用户取消了订单!";
				//					$msg = "有用户取消了订单!";
				//					self::sendNotifyToUser($cid, $msg, $title);
				//				}
				//				$_xktv = Xktv::model()->findByAttributes(array('xktvid' => $_ktvid));
				//				if ($_xktv['type'] == '2') {
				//					self::sendNotifyToWechat($_xktv['id'], $this->user_id, $_order->id, 'cancel');
				//				}
			}
//			else {
			//				$result_array['msg'] = Yii::t('user', 'Cancel room booking failed!');
			//			}
		} else {
			$result_array['msg'] = Yii::t('user', 'Order Not Exist!');
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionSubmitorder() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			"order_invoice" => "",
			"order_code" => "",
			"order_amount" => 0,
			"order_status" => "",
			"order_time" => 0,
			"room_type" => 0,
			"room_name" => "",
			"description" => "",
			"smallpicurl" => "",
			"bigpicurl" => "",
			"starttime" => 0,
			"endtime" => 0,
			"members" => 0,
			"couponid" => 0,
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}

		// Get post data
		$post_data = Yii::app()->request->getPost('SubmitOrderRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		self::log('Room booking data: ' . print_r($post_data, TRUE), 'trace', $this->id);
		//Yii::trace(print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		$_ktvid = isset($post_array['ktvid']) ? $post_array['ktvid'] : '';
		$_roomtype = isset($post_array['roomtype']) ? $post_array['roomtype'] : '';
		$_starttime = isset($post_array['starttime']) ? $post_array['starttime'] : '';
		$_endtime = isset($post_array['endtime']) ? $post_array['endtime'] : '';
		$_members = isset($post_array['members']) ? intval($post_array['members']) : 1;
		$_couponid = isset($post_array['couponid']) ? intval($post_array['couponid']) : 0;

		if (empty($_ktvid)) {
			$result_array['msg'] = 'Must provide parameter of ktvid!';
			$this->sendResults($result_array, self::BadRequest);
		}
		if (empty($_roomtype)) {
			$result_array['msg'] = 'Must provide parameter of room type!';
			$this->sendResults($result_array, self::BadRequest);
		}
		if (empty($_starttime)) {
			$result_array['msg'] = 'Must provide parameter of start time!';
			$this->sendResults($result_array, self::BadRequest);
		}
		$_temptime = strtotime($_starttime);
		if (FALSE === $_temptime || $_temptime < (time() - 1800)) {
			$result_array['msg'] = 'The format of starttime error!';
			$this->sendResults($result_array, self::BadRequest);
		}
		$_booking_time_start = $_temptime;

		if (empty($_endtime)) {
			$result_array['msg'] = 'Must provide parameter of end time!';
			$this->sendResults($result_array, self::BadRequest);
		}
		$_temptime = strtotime($_endtime);
		if (FALSE === $_temptime || $_temptime < $_booking_time_start) {
			$result_array['msg'] = 'The format of endtime error!';
			$this->sendResults($result_array, self::BadRequest);
		}
		$_booking_time_end = $_temptime;

		$xktv = Xktv::model()->findByAttributes(array('xktvid' => $_ktvid));
		if (!is_null($xktv) && !empty($xktv)) {

			$description = $xktv->description;
			$smallpicurl = $this->getRoomPicUrl($xktv->smallpicurl, 0);
			$bigpicurl = $this->getRoomPicUrl($xktv->bigpicurl);

			$booking = new RoomBooking;
			$booking->status = self::ORDER_PENDING_STATUS;
			$booking->invoice = uniqid('RO-');
			$booking->code = uniqid('RBC');
			// $booking->amount = floatval($_price * ($_booking_time_end - $_booking_time_start) / 3600);
			$booking->time = time();
			$booking->roomtype = $_roomtype;
			$booking->ktvid = $_ktvid;
			$booking->userid = $this->user_id;
			$booking->starttime = $_booking_time_start;
			$booking->endtime = $_booking_time_end;
			$booking->members = $_members;
			$booking->couponid = $_couponid;

			$roomname = array(1 => '大包', 2 => '中包', 3 => '小包');

			if ($booking->save()) {
				$booking->qrcode = self::makeOrderQrcode($booking->id);
				$booking->save();
				if ($_couponid > 0) {
					if (Coupon::model()->setAvailable($booking->couponid)) {

					}
				}

				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'Room booking success!');

				$result_array['order_invoice'] = $booking->invoice;
				$result_array['order_code'] = $booking->code;
				// $result_array['order_amount'] = floatval($booking->amount);
				$result_array['order_status'] = 'Pending';
				$result_array['order_time'] = intval($booking->time);
				$result_array['room_type'] = $booking->roomtype;
				$result_array['room_name'] = $roomname[intval($_roomtype)];
				$result_array['description'] = $description;
				$result_array['smallpicurl'] = $smallpicurl;
				$result_array['bigpicurl'] = $bigpicurl;
				$result_array['starttime'] = intval($_booking_time_start);
				$result_array['endtime'] = intval($_booking_time_end);
				$result_array['members'] = intval($booking->members);
				$result_array['qrcode'] = $booking->qrcode;
				$result_array['couponid'] = $booking->couponid;

				// TODO add ktv information
				$_xktv_info = Xktv::model()->getKtvInfo($xktv);
				$result_array['ktvinfo'] = array(
					"xktvid" => $_xktv_info['xktvid'],
					"xktvname" => $_xktv_info['xktvname'],
					"area_id" => $_xktv_info['area_id'],
					"telephone" => $_xktv_info['telephone'],
					"openhours" => $_xktv_info['openhours'],
					"piclist" => $_xktv_info['piclist'],
					"lat" => floatval($_xktv_info['lat']),
					"lng" => floatval($_xktv_info['lng']),
					"rate" => intval($_xktv_info['rate']),
					"address" => $_xktv_info['address'],
				);

				if ($xktv['type'] == '2') {
					self::sendNotifyToWechat($xktv['id'], $this->user_id, $booking->id);
				}
			} else {
				$result_array['msg'] = Yii::t('user', 'Room booking failed!');
			}
		} else {
			$result_array['msg'] = Yii::t('user', 'No available room for booking!');
		}

		// Set response information
		$this->sendResults($result_array);

	}

	public function actionSubmitorder_new() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			"order_invoice" => "",
			"order_code" => "",
			// "order_amount" => 0,
			"order_status" => "",
			"order_time" => 0,
			// "room_type" => 0,
			// "room_name" => "",
			"description" => "",
			"smallpicurl" => "",
			"bigpicurl" => "",
			"starttime" => 0,
			"endtime" => 0,
			// "members" => 0,
			"couponid" => 0,
			'course' => 0,
			'taocantype' => 0,
			'taocanID' => 0,
			"roomtypeid" => 0,
			'price' => null,

		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}

		// Get post data
		$post_data = Yii::app()->request->getPost('SubmitOrderRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		self::log('Room booking data: ' . print_r($post_data, TRUE), 'trace', $this->id);
		//Yii::trace(print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		$_ktvid = isset($post_array['ktvid']) ? $post_array['ktvid'] : '';
		$_roomtype = isset($post_array['roomtype']) ? $post_array['roomtype'] : '';
		$_roomtypeid = isset($post_array['roomtypeid']) ? $post_array['roomtypeid'] : 0;
		$_starttime = isset($post_array['starttime']) ? $post_array['starttime'] : '';
		$_endtime = isset($post_array['endtime']) ? $post_array['endtime'] : '';
		$_couponid = isset($post_array['couponid']) ? intval($post_array['couponid']) : 0;
		$_taocantype = isset($post_array['taocantype']) ? intval($post_array['taocantype']) : 0;
		$_taocanid = isset($post_array['taocanid']) ? intval($post_array['taocanid']) : 0;

		if (empty($_ktvid)) {
			$result_array['msg'] = 'Must provide parameter of ktvid!';
			$this->sendResults($result_array, self::BadRequest);
		}

		$xktv = Xktv::model()->findByAttributes(array('xktvid' => $_ktvid));
		if (!is_null($xktv) && !empty($xktv)) {
			$description = $xktv->description;
			$smallpicurl = $this->getRoomPicUrl($xktv->smallpicurl, 0);
			$bigpicurl = $this->getRoomPicUrl($xktv->bigpicurl);
			$booking = new RoomBooking;
			$booking->status = self::ORDER_PENDING_STATUS;
			$booking->invoice = uniqid('RO-');
			$booking->code = uniqid('RBC');
			$booking->ktvid = $_ktvid;
			$booking->userid = $this->user_id;
			$booking->time = time();
			$booking->couponid = $_couponid;

			if ($_taocantype == 0) {
				// 黄金档套餐
				$taocaninfo = TaocanContent::model()->getTaocanInfoById($_taocanid);
				if ($taocaninfo != null) {
					$booking->price = $taocaninfo['price_yd'];
				}
				$booking->taocantype = 0;
				$booking->starttime = strtotime($_starttime);
				// $booking->endtime = $this->getEndTime($_taocanid, $_starttime);
				$booking->endtime = strtotime($_endtime);
				$booking->taocanid = $_taocanid;

			} elseif ($_taocantype == 1) {
				// 欢唱套餐
				$booking->taocantype = 1;
				$booking->starttime = strtotime($_starttime);
				$booking->endtime = strtotime($_endtime);
				$booking->roomtypeid = $_roomtype;
			}

			if ($booking->save()) {
				$booking->qrcode = self::makeOrderQrcode($booking->id);
				$booking->save();
				if ($_couponid > 0) {
					if (Coupon::model()->setAvailable($booking->couponid)) {

					}
				}
				$result_array['result'] = self::Success;
				$result_array['msg'] = Yii::t('user', 'Room booking success!');

				$result_array['order_invoice'] = $booking->invoice;
				$result_array['order_code'] = $booking->code;
				$result_array['order_status'] = 'Pending';
				$result_array['order_time'] = intval($booking->time);
				// $result_array['room_name'] = $roomname[intval($_roomtype)];
				$result_array['description'] = $description;
				$result_array['smallpicurl'] = $smallpicurl;
				$result_array['bigpicurl'] = $bigpicurl;
				$result_array['qrcode'] = $booking->qrcode;
				$result_array['couponid'] = $booking->couponid;
				$result_array['orderid'] = intval($booking->id);
				if ($_taocantype == 0) {
					$result_array['price'] = $booking->price;

				}

				// TODO add ktv information
				$_xktv_info = Xktv::model()->getKtvInfo($xktv);
				$result_array['ktvinfo'] = array(
					"xktvid" => $_xktv_info['xktvid'],
					"xktvname" => $_xktv_info['xktvname'],
					"area_id" => $_xktv_info['area_id'],
					"telephone" => $_xktv_info['telephone'],
					"openhours" => $_xktv_info['openhours'],
					"piclist" => $_xktv_info['piclist'],
					"lat" => floatval($_xktv_info['lat']),
					"lng" => floatval($_xktv_info['lng']),
					"rate" => intval($_xktv_info['rate']),
					"address" => $_xktv_info['address'],
				);

				if ($xktv['type'] == '2') {
					self::sendNotifyToWechat($xktv['id'], $this->user_id, $booking->id);
				}
			}
		} else {
			$result_array['msg'] = Yii::t('user', 'No available room for booking!');
		}

		// Set response information
		$this->sendResults($result_array);
	}

	protected function getEndTime($taocanid = '', $starttime) {
		return strtotime($starttime) + 7200;
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
		$_order_code = Yii::app()->request->getQuery('order_code');
		if ($_order_code == null) {
			$result_array['msg'] = Yii::t('booking', 'No orderid');
			$this->sendResults($result_array);
			die();
		}
		$_userid = Yii::app()->user->getID();
		$orderStatus = RoomBooking::model()->getStatus($_userid, $_order_code);
		if ($orderStatus != null) {
			$result_array['status'] = intval($orderStatus);
			$result_array['msg'] = Yii::t('booking', 'Get Order Status Success');
			$result_array['result'] = self::Success;
		} else {
			$result_array['msg'] = Yii::t('booking', 'No Order Info');
		}
		$this->sendResults($result_array);
	}

	public function actionUpdateorder() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			"order_invoice" => "",
			"order_code" => "",
			"order_amount" => 0,
			"order_status" => "",
			"order_time" => 0,
			"room_type" => 0,
			"room_name" => "",
			"description" => "",
			"smallpicurl" => "",
			"bigpicurl" => "",
			"starttime" => 0,
			"endtime" => 0,
			"members" => 0,
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}

		// Get post data
		$post_data = Yii::app()->request->getPost('UpdateOrderRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents("php://input");
		}
		// log
		self::log('Room booking data: ' . print_r($post_data, TRUE), 'trace', $this->id);
		//Yii::trace(print_r($post_data, TRUE));
		// Decode post data
		$post_array = json_decode($post_data, true);
		$_order_invoice = isset($post_array['order_invoice']) ? $post_array['order_invoice'] : '';
		$_order_status_id = isset($post_array['order_status_id']) ? $post_array['order_status_id'] : '';

		if (empty($_order_invoice)) {
			$result_array['msg'] = 'Must provide order invoice!';
			$this->sendResults($result_array, self::BadRequest);
		}

		if (empty($_order_status_id) || !in_array($_order_status_id, $this->ROOM_ORDER_MODIFIED_STATUS)) {
			$result_array['msg'] = 'Must provide correct parameter of order_status_id!';
			$this->sendResults($result_array, self::BadRequest);
		}

		// get user information
		$_staff_user_name = '';
		$_staff_user_type = 'KTV';

		$_staff = PlatformUser::model()->findByPk($this->user_id);
		if (!is_null($_staff) && !empty($_staff)) {
			$_staff_user_name = ($_staff->username);
			$_staff_user_type = strtoupper($_staff->auth_type);
		} else {
			$result_array['msg'] = Yii::t('user', 'User permission error!');
			$this->sendResults($result_array);
		}

		// update expired order status
		$this->checkExpiredOrders();

		// get available room order
		if (true) {
			if ('KTVSTAFF' == $_staff_user_type) {
				$booking_order = RoomBooking::model()->findByAttributes(array('invoice' => $_order_invoice, 'ktvid' => $_staff_user_name));
			} else {
				if (!in_array($_order_status_id, $this->ROOM_ORDER_USER_MODIFIED_STATUS)) {
					$result_array['msg'] = Yii::t('user', 'User permission error!');
					$this->sendResults($result_array);
				}
				$booking_order = RoomBooking::model()->findByAttributes(array('invoice' => $_order_invoice, 'userid' => $this->user_id));
			}
			if (!is_null($booking_order) && !empty($booking_order)) {
				// check current KTV staff permission
				$_current_ktvid = strtoupper($booking_order->ktvid);
				if ('KTVSTAFF' == $_staff_user_type && $_current_ktvid != $_staff_user_name) {
					$result_array['msg'] = Yii::t('user', 'Not current KTV staff!');
					$this->sendResults($result_array);
				}
				$_cur_status_id = $booking_order->status;
				if (in_array($_cur_status_id, $this->ROOM_INVALID_ORDER_STATUS)) {
					$result_array['msg'] = Yii::t('user', 'Room order status can not be modified anymore!');
				} else if (in_array($_cur_status_id, $this->ROOM_ORDER_CONFIRM_STATUS) && !in_array($_order_status_id, $this->ROOM_ORDER_COMPLETED_STATUS)) {
					$result_array['msg'] = Yii::t('user', 'Room order status can only be set to completed or cancelled!');
				} else if (!in_array($_cur_status_id, $this->ROOM_ORDER_CONFIRM_STATUS) && !in_array($_order_status_id, $this->ROOM_ORDER_STAFF_MODIFIED_STATUS)) {
					$result_array['msg'] = Yii::t('user', 'Room order status can only be set to confirmed or cancelled or rejected!');
				} else {
					// check confirm order permission, cancelled
					if (in_array($_order_status_id, $this->ROOM_ORDER_CONFIRM_STATUS)) {
						if ($_current_ktvid != $_staff_user_name || $_staff_user_type != 'KTVSTAFF') {
							$result_array['msg'] = Yii::t('user', 'No permission to update order!');
							$this->sendResults($result_array);
						}

						// get available room order
						$_day_begin_time = strtotime(date('Y-m-d', $booking_order->starttime));
						$_day_end_time = $_day_begin_time + 3600 * 24;
						// Check KTV order limit, 10 confirmed orders per day
						$criteria_ktv = new CDbCriteria;
						$criteria_ktv->compare('ktvid', $booking_order->ktvid);
						$criteria_ktv->addInCondition('status', $this->ROOM_ORDER_KTVLIMIT_STATUS);
						$criteria_ktv->addBetweenCondition('starttime', $_day_begin_time, $_day_end_time);
						$count_limit = self::ORDER_KTV_AVAILABLE_LIMIT;
						$count = RoomBooking::model()->count($criteria_ktv);
						if ($count >= $count_limit) {
							$result_array['msg'] = Yii::t('user', "KTV confirmed order count > $count_limit !");
							$this->sendResults($result_array, self::BadRequest);
						}
					}

					// check expire status
					//if (((time() - $booking_order->time) > self::ORDER_EXPIRED_TIME) && !in_array($booking_order->status, $this->ROOM_ORDER_STAFF_CONFIRMED_STATUS)) {
					//    $_order_status_id = self::ORDER_EXPIRED_STATUS;
					//}
					// update order status
					$booking_order->status = $_order_status_id;
					if (in_array($_order_status_id, $this->ROOM_ORDER_CONFIRM_STATUS)) {
						$booking_order->confirm_time = time();
					}
					if ($booking_order->save()) {
						$xktv = Xktv::model()->findByAttributes(array('xktvid' => $booking_order->ktvid));
						if (!is_null($xktv) && !empty($xktv)) {
							$description = $xktv->description;
							$smallpicurl = $this->getRoomPicUrl($xktv->smallpicurl, 0);
							$bigpicurl = $this->getRoomPicUrl($xktv->bigpicurl);
							$roomname = array(1 => '大包', 2 => '中包', 3 => '小包');
							$result_array['result'] = self::Success;
							$result_array['msg'] = Yii::t('user', 'Room order status updated success!');
							$result_array['order_invoice'] = $booking_order->invoice;
							$result_array['order_code'] = $booking_order->code;
							$result_array['order_amount'] = floatval($booking_order->amount);
							$result_array['order_status'] = $booking_order->status;
							$result_array['order_time'] = intval($booking_order->time);
							$result_array['room_type'] = $booking_order->roomtype;
							$result_array['room_name'] = $roomname[$booking_order->roomtype];
							$result_array['description'] = $description;
							$result_array['smallpicurl'] = $smallpicurl;
							$result_array['bigpicurl'] = $bigpicurl;
							$result_array['starttime'] = intval($booking_order->starttime);
							$result_array['endtime'] = intval($booking_order->endtime);
							$result_array['members'] = intval($booking_order->members);

							// TODO add ktv information
							$_xktv_info = Xktv::model()->getKtvInfo($xktv);
							$result_array['ktvinfo'] = array(
								"xktvid" => $_xktv_info['xktvid'],
								"xktvname" => $_xktv_info['xktvname'],
								"area_id" => $_xktv_info['area_id'],
								"telephone" => $_xktv_info['telephone'],
								"openhours" => $_xktv_info['openhours'],
								"piclist" => $_xktv_info['piclist'],
								"lat" => floatval($_xktv_info['lat']),
								"lng" => floatval($_xktv_info['lng']),
								"rate" => intval($_xktv_info['rate']),
								"address" => $_xktv_info['address'],
							);

							$user = PlatformUser::model()->findByAttributes(array('id' => $booking_order->userid));
							$cid = $user->cid;
							if ($_order_status_id == 7) {
								$title = "您的订单已被取消";
								$msg = "这家店太火爆了，房间被预定一空，请再找一下吧！";
							} else if ($_order_status_id == 3) {
								$title = "您的订单已确认";
								$msg = "订单已经确认，请准时到店！";
							} else if ($_order_status_id == 4) {
								$title = "您的订单已被拒绝";
								$msg = "订单被拒绝，请重新下单！";
							} else if ($_order_status_id == 5) {
								$title = "您的订单已完成";
								$msg = "订单已经完成，获得积分奖励！";
								self::processPoints($booking_order->userid, '2', '200000055d19acfb6cde');
							} else if ($_order_status_id == 14) {
								$title = "您的订单已过期";
								$msg = "订单已经过期，请重新下单！";
							}

							if (!empty($msg)) {
								self::sendNotifyToUser($cid, $msg, $title);
							}
						} else {
							$result_array['msg'] = Yii::t('user', 'No available room for booking!');
						}
					} else {
						self::log(print_r($booking_order->getErrors(), true), 'error', $this->id);
						$result_array['msg'] = Yii::t('user', 'Room booking failed!');
					}
				}
			} else {
				$result_array['msg'] = Yii::t('user', 'Not found booking order !');
			}
		} else {
			$result_array['msg'] = Yii::t('user', 'order count >10 !');
		}

		// Set response information
		$this->sendResults($result_array);
	}

	public function actionOrderlist() {
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

		// Get query data
		$_userid = Yii::app()->request->getQuery('userid');
		$_offset = Yii::app()->request->getQuery('offset');
		$_limit = Yii::app()->request->getQuery('limit');
		$_status = Yii::app()->request->getQuery('status');
		$_userid = empty($_userid) ? '' : trim($_userid);
		$_offset = empty($_offset) ? 0 : intval($_offset);
		$_limit = empty($_limit) ? 100 : intval($_limit);
		$_status = empty($_status) ? 0 : intval($_status);

		$_userid = Yii::app()->user->getId();
		RoomBooking::model()->checkExpiredOrders($_userid);

		if (!empty($_userid)) {
			$userid = $_userid;
		} else {
			$userid = $this->user_id;
		}
		$where = array('userid' => $userid, 'deleted' => '0');
		if ($_status != 0) {
			$where['status'] = $_status;
		}
		$booking_list = RoomBooking::model()->findAllByAttributes($where, array('offset' => $_offset, 'limit' => $_limit, 'order' => 'update_time desc'));

		if (!is_null($booking_list) && !empty($booking_list)) {
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'Room order list got success!');
			$result_array['total'] = count($booking_list);

			foreach ($booking_list as $key => $_order) {
				// $roomname = array(1 => '大包', 2 => '中包', 3 => '小包');
				$xktv = Xktv::model()->findByAttributes(array('xktvid' => $_order->ktvid));
				if (!is_null($xktv) && !empty($xktv)) {

					$description = $xktv->description;
					$smallpicurl = $this->getRoomPicUrl($xktv->smallpicurl, 0);
					$bigpicurl = $this->getRoomPicUrl($xktv->bigpicurl);

					$orderstatus = $_order->status;

					// TODO add ktv information
					$_xktv_info = Xktv::model()->getKtvInfo($xktv);
					if ($_order->taocantype == 1) {
						if ($_order->roomtypeid > 0) {
							$order_info["roomtypeid"] = intval($_order->roomtypeid);
							$order_info["room_name"] = TaocanRoomtype::model()->getRoomName($_order->roomtypeid);
							$order_info['taocan_info'] = null;
						}
					} else {
						$taocaninfo = TaocanContent::model()->getTaocanInfoById($_order->taocanid);
						// var_dump($taocaninfo);
						if ($taocaninfo != null) {
							$order_info["roomtypeid"] = intval($taocaninfo['roomtype']);
							$order_info["room_name"] = TaocanRoomtype::model()->getRoomName($taocaninfo['roomtype']);
							$order_info['taocan_info'] = $taocaninfo;
						}
					}
					$result_array['list'][] = array(
						"order_invoice" => $_order->invoice,
						"order_code" => $_order->code,
						"order_status" => intval($orderstatus),
						"order_time" => intval($_order->time),
						'roomtypeid' => $order_info["roomtypeid"],
						'room_name' => $order_info["room_name"],
						'taocan_info' => $order_info["taocan_info"],
						"description" => $description,
						"smallpicurl" => $smallpicurl,
						"bigpicurl" => $bigpicurl,
						"starttime" => intval($_order->starttime),
						"endtime" => intval($_order->endtime),
						'ktvinfo' => array(
							"xktvid" => $_xktv_info['xktvid'],
							"xktvname" => $_xktv_info['xktvname'],
							"area_id" => $_xktv_info['area_id'],
							"telephone" => $_xktv_info['telephone'],
							"openhours" => $_xktv_info['openhours'],
							"piclist" => $_xktv_info['piclist'],
							"lat" => floatval($_xktv_info['lat']),
							"lng" => floatval($_xktv_info['lng']),
							"rate" => intval($_xktv_info['rate']),
							"address" => $_xktv_info['address'],
						),
						'rating' => comment::model()->getRatingStatus($_userid, $xktv->xktvid),
						'display_name' => $_order->display_name,
						'mobile' => $_order->mobile,
						// "coupon_info" => array('name' => '百威铝瓶啤酒六罐', 'count' => 6),
						"coupon_info" => $_order->couponid == 0 ? 0 : $this->getConponInfo($_order->couponid, $userid),
					);
				} else {
					$result_array['result'] = self::ListNull;
					$result_array['msg'] = Yii::t('user', 'No available room for booking!');
				}
			}
		} else {
			$result_array['result'] = self::ListNull;
			$result_array['msg'] = Yii::t('user', 'No available room booking orders!');
		}

		// Set response information
		$this->sendResults($result_array);
	}

	protected function getConponInfo($id = '', $userid = '') {
		if ($id != '') {
			$info = Coupon::model()->getInfoByid($id, $userid);
			return array('name' => $info['name'], 'count' => $info['count']);
		} else {
			return 0;
		}
	}

	public function actionOrderdetail() {
		// Response format data
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			"order_invoice" => "",
			"order_code" => "",
			"order_amount" => 0,
			"order_status" => "",
			"order_time" => 0,
			"room_type" => 0,
			"room_name" => "",
			"description" => "",
			"smallpicurl" => "",
			"bigpicurl" => "",
			"starttime" => 0,
			"endtime" => 0,
			"members" => 0,
			'price' => 0,
		);

		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}

		// Get query data
		$_ordercode = Yii::app()->request->getQuery('ordercode');
		$_ordercode = empty($_ordercode) ? '' : trim($_ordercode);

		$_userid = Yii::app()->user->getId();
		$User_info = PlatformUser::model()->findByPk($_userid);
		// $_mobile = Yii::app()->user->getMobile();
		if (!empty($_userid)) {
			$userid = $_userid;
		} else {
			$userid = $this->user_id;
		}
		$order_detail = RoomBooking::model()->findByAttributes(array('userid' => $userid, 'code' => $_ordercode));
		// }

		if (!is_null($order_detail) && !empty($order_detail)) {
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', ' order detail got success!');

			$roomname = array(1 => '大包', 2 => '中包', 3 => '小包');
			$xktv = Xktv::model()->findByAttributes(array('xktvid' => $order_detail->ktvid));
			if (!is_null($xktv) && !empty($xktv)) {
				$description = $xktv->description;
				$smallpicurl = $this->getRoomPicUrl($xktv->smallpicurl, 0);
				$bigpicurl = $this->getRoomPicUrl($xktv->bigpicurl);

				$orderstatus = $order_detail->status;
				$result_array["order_invoice"] = $order_detail->invoice;
				$result_array["order_code"] = $order_detail->code;
				// $result_array["order_amount"] = floatval($order_detail->amount);
				$result_array["order_status"] = intval($orderstatus);
				$result_array["order_time"] = intval($order_detail->time);
				// $result_array["room_type"] = intval($order_detail->roomtype);
				if ($order_detail->taocantype == 1) {
					if ($order_detail->roomtypeid > 0) {
						$result_array["roomtypeid"] = intval($order_detail->roomtypeid);
						$result_array["room_name"] = TaocanRoomtype::model()->getRoomName($order_detail->roomtypeid);
					}
				} else {
					$taocaninfo = TaocanContent::model()->getTaocanInfoById($order_detail->taocanid);
					// var_dump($taocaninfo);
					if ($taocaninfo != null) {
						$result_array["roomtypeid"] = $taocaninfo['roomtype'];
						$result_array["room_name"] = TaocanRoomtype::model()->getRoomName($taocaninfo['roomtype']);
						$result_array['taocan_info'] = $taocaninfo;
					}
				}
				$result_array['rating'] = comment::model()->getRatingStatus($_userid, $order_detail->ktvid);
				$result_array['taocantype'] = $order_detail['taocantype'];
				$result_array["description"] = $description;
				$result_array["smallpicurl"] = $smallpicurl;
				$result_array["bigpicurl"] = $bigpicurl;
				$result_array["starttime"] = intval($order_detail->starttime);
				$result_array["endtime"] = intval($order_detail->endtime);
				// $result_array["members"] = intval($order_detail->members);
				$result_array["qrcode"] = '/wechatshangjia/' . $order_detail->qrcode;
				$result_array["coupon_info"] = $order_detail->couponid == 0 ? 0 : $this->getConponInfo($order_detail->couponid, $userid);
				$result_array["mobile"] = $User_info->mobile;
				$result_array["display_name"] = $User_info->display_name;
				$result_array['price'] = $order_detail->price;

				// TODO add ktv information
				$_xktv_info = Xktv::model()->getKtvInfo($xktv);
				$result_array['ktvinfo'] = array(
					"xktvid" => $_xktv_info['xktvid'],
					"xktvname" => $_xktv_info['xktvname'],
					"area_id" => $_xktv_info['area_id'],
					"telephone" => $_xktv_info['telephone'],
					"openhours" => $_xktv_info['openhours'],
					"piclist" => $_xktv_info['piclist'],
					"lat" => floatval($_xktv_info['lat']),
					"lng" => floatval($_xktv_info['lng']),
					"rate" => intval($_xktv_info['rate']),
					"address" => $_xktv_info['address'],
				);
			} else {
				$result_array['msg'] = Yii::t('user', 'No available room for booking!');
			}
		} else {
			$result_array['msg'] = Yii::t('user', 'No available room booking orders!');
		}

		// Set response information
		$this->sendResults($result_array);
	}

	// public function actionSubmitorder() {
	// // Response format data
	// $result_array = array(
	// 'result' => self::BadRequest,
	// 'msg' => Yii::t('user', 'Request method illegal!'),
	// "order_invoice" => "",
	// "order_code" => "",
	// "order_amount" => 0,
	// "order_status" => "",
	// "order_time" => 0,
	// "room_type" => 0,
	// "room_name" => "",
	// "description" => "",
	// "smallpicurl" => "",
	// "bigpicurl" => "",
	// "starttime" => 0,
	// "endtime" => 0,
	// "members" => 0,
	// );
	// // Check request type
	// $request_type = Yii::app()->request->getRequestType();
	// if ('POST' != $request_type) {
	// $this->sendResults($result_array, self::BadRequest);
	// }
	// // Get post data
	// $post_data = Yii::app()->request->getPost('BookingSubmitRequest');
	// if (empty($post_data)) {
	// $post_data = file_get_contents("php://input");
	// }
	// // log
	// self::log('Room booking data: ' . print_r($post_data, TRUE), 'trace', $this->id);
	// //Yii::trace(print_r($post_data, TRUE));
	// // Decode post data
	// $post_array = json_decode($post_data, true);
	// $_roomid = isset($post_array['roomid']) ? $post_array['roomid'] : '';
	// $_bookingtime = isset($post_array['bookingtime']) ? $post_array['bookingtime'] : '';
	// $_booking_duration = isset($post_array['duration']) ? intval($post_array['duration']) : 2;
	// $_booking_duration = empty($_booking_duration) ? 2 : intval($_booking_duration);
	// $_booking_duration = ($_booking_duration < 1) ? 2 : $_booking_duration;
	// if (empty($_roomid)) {
	// $result_array['msg'] = 'Must provide parameter of room id!';
	// $this->sendResults($result_array, self::BadRequest);
	// }
	// if (empty($_bookingtime)) {
	// $result_array['msg'] = 'Must provide parameter of bookingtime!';
	// $this->sendResults($result_array, self::BadRequest);
	// }
	// $_temptime = strtotime($_bookingtime);
	// if (FALSE === $_temptime) {
	// $result_array['msg'] = 'The format of bookingtime error!';
	// $this->sendResults($result_array, self::BadRequest);
	// }
	// $_booking_time = $_temptime;
	// $_booking_time_start = $_booking_time;
	// $_booking_time_end = $_booking_time + $_booking_duration * 60 * 60 - 1;
	// // TODO:
	// // get booked room
	// // room_booked = (booking_time between ($_booking_time_start, booking_time) or (booking_time + duration between($_booking_time_start, booking_time + duration)
	// $between_criteria = new CDbCriteria();
	// $between_criteria->addBetweenCondition('booking_time', $_booking_time_start, $_booking_time_end);
	// $between_criteria->addBetweenCondition('expire', $_booking_time_start, $_booking_time_end, 'OR');
	// $booked_criteria = new CDbCriteria();
	// $booked_criteria->addNotInCondition('room_status', $this->ROOM_INVALID_ORDER_STATUS);
	// $booked_criteria->mergeWith($between_criteria);
	// $room_booked_list = RoomBooking::model()->findAll($booked_criteria);
	// $room_booked_id_array = array();
	// if (!is_null($room_booked_list) && !empty($room_booked_list)) {
	// foreach ($room_booked_list as $key => $_room) {
	// $room_booked_id_array[] = $_room->room_id;
	// }
	// }
	// // checked in rooms
	// $room_checkin_list = CheckinCode::model()->findAll('expire IS NULL OR expire = 0 OR expire > :starttime', array(':starttime' => $_booking_time_start));
	// if (!is_null($room_checkin_list) && !empty($room_checkin_list)) {
	// foreach ($room_checkin_list as $key => $_room) {
	// $room_booked_id_array[] = $_room->room_id;
	// }
	// }
	// // get available room
	// // room_available = (room_type = $_room_type and roomid not in(room_booked))
	// // $_count = room_available->count;
	// $available_criteria = new CDbCriteria();
	// if (!empty($room_booked_id_array)) {
	// $available_criteria->addNotInCondition('id', $room_booked_id_array);
	// }
	// $available_criteria->compare('roomid', $_roomid);
	// $booking_room = Room::model()->find($available_criteria);
	// if (!is_null($booking_room) && !empty($booking_room)) {
	// $_price = (empty($booking_room->price)) ? 0 : $booking_room->price;
	// $_s_pic_url = (empty($booking_room->smallpic_url)) ? '' : $booking_room->smallpic_url;
	// $_b_pic_url = (empty($booking_room->bigpic_url)) ? '' : $booking_room->bigpic_url;
	// $_room_name = $booking_room->name;
	// $_room_description = $booking_room->description;
	// $_room_id = $booking_room->roomid;
	// $booking = new RoomBooking;
	// $booking->user_id = $this->user_id;
	// $booking->room_id = $booking_room->id;
	// $booking->room_status = 1;
	// $booking->order_invoice = uniqid('RO-');
	// $booking->order_code = uniqid('RBC');
	// $booking->order_amount = floatval($_price * $_booking_duration);
	// $booking->duration = $_booking_duration;
	// $booking->booking_time = $_booking_time;
	// $booking->order_time = time();
	// $booking->expire = $_booking_time + $_booking_duration * 60 * 60 - 1;
	// if ($booking->save()) {
	// $result_array['result'] = self::Success;
	// $result_array['msg'] = Yii::t('user', 'Room booking success!');
	// $result_array['order_invoice'] = $booking->order_invoice;
	// $result_array['order_code'] = $booking->order_code;
	// $result_array['order_amount'] = floatval($booking->order_amount);
	// $result_array['order_status'] = 'Pending';
	// $result_array['order_time'] = intval($booking->order_time);
	// $result_array['room_id'] = $_room_id;
	// $result_array['room_name'] = $_room_name;
	// $result_array['description'] = $_room_description;
	// $result_array['smallpicurl'] = $this->getRoomPicUrl($_s_pic_url, 0);
	// $result_array['bigpicurl'] = $this->getRoomPicUrl($_b_pic_url);
	// $result_array['booking_time'] = intval($booking->booking_time);
	// $result_array['booking_duration'] = intval($booking->duration);
	// } else {
	// $result_array['msg'] = Yii::t('user', 'Room booking failed!');
	// }
	// } else {
	// $result_array['msg'] = Yii::t('user', 'No available room for booking!');
	// }
	// // Set response information
	// $this->sendResults($result_array);
	// }
	// public function actionUpdateorder() {
	// // Response format data
	// $result_array = array(
	// 'result' => self::BadRequest,
	// 'msg' => Yii::t('user', 'Request method illegal!'),
	// "order_invoice" => "",
	// "order_code" => "",
	// "order_amount" => 0,
	// "order_status" => "",
	// "order_time" => 0,
	// "room_id" => "",
	// "room_name" => "",
	// "description" => "",
	// "smallpicurl" => "",
	// "bigpicurl" => "",
	// "booking_time" => 0,
	// "booking_duration" => 0,
	// );
	// // Check request type
	// $request_type = Yii::app()->request->getRequestType();
	// if ('POST' != $request_type) {
	// $this->sendResults($result_array, self::BadRequest);
	// }
	// // Get post data
	// $post_data = Yii::app()->request->getPost('BookingUpdateRequest');
	// if (empty($post_data)) {
	// $post_data = file_get_contents("php://input");
	// }
	// // log
	// self::log('Room booking update data: ' . print_r($post_data, TRUE), 'trace', $this->id);
	// //Yii::trace(print_r($post_data, TRUE));
	// // Decode post data
	// $post_array = json_decode($post_data, true);
	// $_order_invoice = isset($post_array['order_invoice']) ? $post_array['order_invoice'] : '';
	// $_invoice_status = isset($post_array['order_status_id']) ? intval($post_array['order_status_id']) : 0;
	// if (empty($_order_invoice)) {
	// $result_array['msg'] = 'Must provide correct parameter of order invoice!';
	// $this->sendResults($result_array, self::BadRequest);
	// }
	// if (empty($_invoice_status) || !in_array($_invoice_status, $this->ROOM_ORDER_MODIFIED_STATUS)) {
	// $result_array['msg'] = 'Must provide correct parameter of order_status_id!';
	// $this->sendResults($result_array, self::BadRequest);
	// }
	// // get available room order
	// $booking_order = RoomBooking::model()->findByAttributes(array('order_invoice' => $_order_invoice, 'user_id' => $this->user_id));
	// if (!is_null($booking_order) && !empty($booking_order)) {
	// $_cur_status_id = $booking_order->room_status;
	// if (in_array($_cur_status_id, $this->ROOM_INVALID_ORDER_STATUS)) {
	// $result_array['msg'] = Yii::t('user', 'Room order status can not be modified anymore!');
	// } else {
	// $booking_order->room_status = $_invoice_status;
	// if ($booking_order->save()) {
	// $result_array['result'] = self::Success;
	// $result_array['msg'] = Yii::t('user', 'Room order status updated success!');
	// $booking_room = $booking_order->room;
	// $_s_pic_url = (empty($booking_room->smallpic_url)) ? '' : $booking_room->smallpic_url;
	// $_b_pic_url = (empty($booking_room->bigpic_url)) ? '' : $booking_room->bigpic_url;
	// $_room_name = $booking_room->name;
	// $_room_description = $booking_room->description;
	// $_room_id = $booking_room->roomid;
	// $result_array['order_invoice'] = $booking_order->order_invoice;
	// $result_array['order_code'] = $booking_order->order_code;
	// $result_array['order_amount'] = floatval($booking_order->order_amount);
	// $result_array['order_status'] = $booking_order->getOrderStatusName($booking_order->room_status);
	// $result_array['order_time'] = intval($booking_order->order_time);
	// $result_array['room_id'] = $_room_id;
	// $result_array['room_name'] = $_room_name;
	// $result_array['description'] = $_room_description;
	// $result_array['smallpicurl'] = $this->getRoomPicUrl($_s_pic_url, 0);
	// $result_array['bigpicurl'] = $this->getRoomPicUrl($_b_pic_url);
	// $result_array['booking_time'] = intval($booking_order->booking_time);
	// $result_array['booking_duration'] = intval($booking_order->duration);
	// } else {
	// $result_array['msg'] = Yii::t('user', 'Room order status update failed!');
	// }
	// }
	// } else {
	// $result_array['msg'] = Yii::t('user', 'No available room order for update!');
	// }
	// // Set response information
	// $this->sendResults($result_array);
	// }
	// public function actionOrderlist() {
	// // Response format data
	// $result_array = array(
	// 'result' => self::BadRequest,
	// 'msg' => Yii::t('user', 'Request method illegal!'),
	// 'total' => 0,
	// 'list' => array(),
	// );
	// // get available room orders
	// $booking_list = RoomBooking::model()->findAllByAttributes(array('user_id' => $this->user_id));
	// if (!is_null($booking_list) && !empty($booking_list)) {
	// $result_array['result'] = self::Success;
	// $result_array['msg'] = Yii::t('user', 'Room order list got success!');
	// $result_array['total'] = count($booking_list);
	// foreach ($booking_list as $key => $_order) {
	// $booking_room = $_order->room;
	// $_s_pic_url = (empty($booking_room->smallpic_url)) ? '' : $booking_room->smallpic_url;
	// $_b_pic_url = (empty($booking_room->bigpic_url)) ? '' : $booking_room->bigpic_url;
	// $_room_name = $booking_room->name;
	// $_room_description = $booking_room->description;
	// $_room_id = $booking_room->roomid;
	// $result_array['list'][] = array(
	// "order_invoice" => $_order->order_invoice,
	// "order_code" => $_order->order_code,
	// "order_amount" => floatval($_order->order_amount),
	// "order_status" => $_order->getOrderStatusName($_order->room_status),
	// "order_time" => intval($_order->order_time),
	// "room_id" => $_room_id,
	// "room_name" => $_room_name,
	// "description" => $_room_description,
	// "smallpicurl" => $this->getRoomPicUrl($_s_pic_url, 0),
	// "bigpicurl" => $this->getRoomPicUrl($_b_pic_url),
	// "booking_time" => intval($_order->booking_time),
	// "booking_duration" => intval($_order->duration),
	// );
	// }
	// } else {
	// $result_array['msg'] = Yii::t('user', 'No available room booking orders!');
	// }
	// // Set response information
	// $this->sendResults($result_array);
	// }

	/**
	 * Get the media picture url
	 * @param String $filename
	 * @param integer $picsize
	 * @return string
	 */
	public function getRoomPicUrl($filename = '', $picsize = 1) {
		$_baseurl = Yii::app()->createAbsoluteUrl('/');
		$_mediabaseurl = (empty(Yii::app()->params['room_url']) ? $_baseurl . '/uploads' : Yii::app()->params['room_url']);
		$_mediaurl = $_mediabaseurl . '/room/';

		$default_room_setting = empty(Yii::app()->params['room_default_setting']) ? array() : Yii::app()->params['room_default_setting'];
		if (1 == $picsize) {
			$default_room_pic = (isset($default_room_setting['bigpic']) && !empty($default_room_setting['bigpic'])) ? ($_mediabaseurl . '/' . $default_room_setting['bigpic']) : '';
		} else {
			$default_room_pic = (isset($default_room_setting['smallpic']) && !empty($default_room_setting['smallpic'])) ? ($_mediabaseurl . '/' . $default_room_setting['smallpic']) : '';
		}
		$_media_full_url = empty($filename) ? $default_room_pic : ($_mediaurl . $filename);
		return $_media_full_url;
	}

	public function actionXktvdistrict() {
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

		// Get query data
		$_parent = Yii::app()->request->getQuery('parent');
		$_offset = Yii::app()->request->getQuery('offset');
		$_limit = Yii::app()->request->getQuery('limit');
		$_parent = empty($_parent) ? '' : trim($_parent);
		$_offset = empty($_offset) ? 0 : intval($_offset);
		$_limit = empty($_limit) ? 100 : intval($_limit);

		$result_array['msg'] = Yii::t('user', 'No district code data!');
		// Search district list
		// $district_array = require(YII::app()->basePath . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'DistrictCode.php');
		// $search_array = $this->getChildDistrict($district_array, $_parent);
		$search_array = AreaCode::model()->getAreaCode($_parent, $_offset, $_limit);

		if (!empty($search_array)) {
			// get district array
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'Get district list success!');
			$result_array['list'] = $search_array;
			$result_array['total'] = count($search_array);
		} else {
			$result_array['result'] = self::ListNull;
			$result_array['msg'] = 'List is Null';
		}

		// Set response information
		$this->sendResults($result_array);
	}

	protected function getChildDistrict($district_array = array(), $parent = '') {
		//$district_array = require(YII::app()->basePath . DIRECTORY_SEPARATOR . 'DistrictCode.php');
		$result_array = array();
		if (empty($parent)) {
			foreach ($district_array as $code => $district) {
				$childnum = 0;
				if (!empty($district['child']) && is_array($district['child'])) {
					$childnum = count($district['child']);
				}
				$result_array[] = array(
					'code' => $district['code'],
					'name' => $district['name'],
					'childnum' => $childnum,
				);
			}
			return $result_array;
		} else if (!empty($district_array) && is_array($district_array)) {
			foreach ($district_array as $code => $district) {
				if ($code == $parent) {
					if (!empty($district['child'])) {
						foreach ($district['child'] as $_code => $_district) {
							$childnum = 0;
							if (!empty($_district['child']) && is_array($_district['child'])) {
								$childnum = count($_district['child']);
							}
							$result_array[] = array(
								'code' => $_district['code'],
								'name' => $_district['name'],
								'childnum' => $childnum,
							);
						}
					}
					return $result_array;
				} else {
					$child_array = $this->getChildDistrict($district, $parent);
					if (!empty($child_array)) {
						return $child_array;
					}
				}
			}
		}
		return $result_array;
	}

	public function actionXktv() {

		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
			'data' => array(),
		);
		// Check request type
		$request_type = Yii::app()->request->getRequestType();
		if ('GET' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}

		$_xktvid = Yii::app()->request->getQuery('xktvid');
		if ($_xktvid == null) {
			$this->sendResults($result_array);
		}
		$result_array['msg'] = Yii::t('user', 'No XKTV data!');
//		$search_array = Xktv::model()->searchXktvList('', '', '', '', '', 0, 1, $_xktvid);
		$detail = Xktv::model()->getDetail($_xktvid);
		if ($detail != NULL) {
//			$detail->taocaninfo = Taocan::model()->getDetail($_xktvid);
			$taocaninfo = array();
			$taocaninfo['lastofjiedan'] = $detail['lastofjiedan'];
			// if ($detail['type'] == 2) {
			$courses = TaocanShijianduan::model()->getCourses($detail['id']);
			if ($courses != null && $detail['taocan'] == 1) {
				$taocaninfo['course'] = $courses;
			} else {
//				$taocaninfo['course'] = json_decode('[{
				//									"id": 10018,
				//									"name": "全天",
				//									"starttime": {"time":"13:00","ciri":0},
				//									"endtime": {"time":"19:00","ciri":0},
				//									"show": 1,
				//									"is_hjd": 0
				//								}]', true);
				$taocaninfo['course'] = array(array(
					'id' => 0,
					'name' => '全天',
					'starttime' => $this->getFormatTime($detail['openhours_s']),
					'endtime' => $this->getFormatTime($detail['openhours_e']),
					'show' => 1,
					'is_hjd' => 0,
					'is_ymc' => 0,
				));
			}

			$roomtypelist = TaocanRoomtype::model()->getRoomtype($detail['id']);
			if ($roomtypelist != null && $detail['taocan'] == 1) {
				// if ($roomtypelist != null) {
				// 	usort($roomtypelist, function ($a, $b) {
				// 		$room1 = $a['des'];
				// 		$room1_num = explode('-', $room1);
				// 		$room2 = $b['des'];
				// 		$room2_num = explode('-', $room2);
				// 		if ($room1_num[0] > $room2_num[0]) {
				// 			return 1;
				// 		} elseif ($room1_num[0] == $room2_num[0]) {
				// 			return 0;
				// 		} else {
				// 			return -1;
				// 		}

				// 	});
				// }

				$taocaninfo['roomtype'] = $roomtypelist;
			} else {
				$taocaninfo['roomtype'] = json_decode('[{
									"id": 485,
									"name": "小房",
									"desc": "1-5人",
									"des": "1-5",
									"show": 1
								}, {
									"id": 486,
									"name": "中房",
									"desc": "5-10人",
									"des": "5-10",
									"show": 1
								}, {
									"id": 487,
									"name": "大房",
									"desc": "10-20人",
									"des": "10-20",
									"show": 1
								}]', true);
			}

			// } else {

			// }
			$tiaokuanlist = TaocanTiaokuan::model()->getList($detail['id']);
			if ($tiaokuanlist != null) {
				$taocaninfo['tiaokuan'] = $tiaokuanlist;
			} else {
				$taocaninfo['tiaokuan'] = json_decode('
								[{"id": 10015,
									"name": "KTV确认您的订单之后，还请按时到店。"
								}, {
									"id": 10016,
									"name": "如有其它费用，以KTV实际情况为准。"
								}]', true);
			}
			$taocaninfo['days'] = $this->getDays($detail['lastofjiedan']);
			$detail['taocaninfo'] = $taocaninfo;
		}

		if (!empty($detail)) {
			// get XTV array
			$result_array['now'] = time();
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'Get XKTV info success!');
			$result_array['data'] = $detail;

			// $result_array['total'] = count($search_array);
		}

		// Set response information
		$this->sendResults($result_array);
	}

	protected function getFormatTime($time) {
		$is_ciri = date('H', strtotime($time)) < 7;
		return array('time' => $time, 'ciri' => $is_ciri === true ? 1 : 0);
	}

	// public function roomSort($a, $b) {

	// 	$room1 = $a['des'];
	// 	$room1_num = explode('-', $room1);
	// 	$room2 = $b['des'];
	// 	$room2_num = explode('-', $room2);
	// 	var_dump($room1_num);
	// 	var_dump($room2_num);die();
	// 	if ($room1_num[0] > $room2_num[0]) {
	// 		return 1;
	// 	} elseif ($room1_num[0] == $room2_num[0]) {
	// 		return 0;
	// 	} else {
	// 		return -1;
	// 	}

	// }

	protected function getDays($lastofjiedan = '') {
		// var_dump($lastofjiedan);
		if ($lastofjiedan != '') {
			if ($lastofjiedan['ciri'] == 1) {
				// echo time();
				// echo strtotime($lastofjiedan['time']);die();
				if (strtotime($lastofjiedan['time']) > time()) {
					$today = date('Y-m-d', strtotime(date('Y-m-d') . " -1 day"));
				} else {
					$today = date('Y-m-d');
				}
			} else {
				$today = date('Y-m-d');
			}
		} elseif ($lastofjiedan == null) {
			$today = date('Y-m-d');
		}

		$days = array();
		$days[] = $today;
		$days[] = date("Y-m-d", strtotime($today . " +1 day"));
		$days[] = date("Y-m-d", strtotime($today . " +2 day"));
		$days[] = date("Y-m-d", strtotime($today . " +3 day"));
		$days[] = date("Y-m-d", strtotime($today . " +4 day"));
		$days[] = date("Y-m-d", strtotime($today . " +5 day"));
		$days[] = date("Y-m-d", strtotime($today . " +6 day"));
		return $days;
	}

	public function actionxktvcoords() {
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
		$result_array['msg'] = Yii::t('user', 'No XKTV data!');
		$search_array = Xktv::model()->getXktvcoodsList();
		if (!empty($search_array)) {
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'Get XKTVcoods list success!');
			$result_array['list'] = $search_array;
			$result_array['total'] = count($search_array);
		} else {
			$result_array['result'] = self::ListNull;
			$result_array['msg'] = 'List is Null';
		}

		$this->sendResults($result_array);

	}

	public function actionXktvlist() {
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
			if ('POST' == $request_type) {
				$post_data = Yii::app()->request->getPost('XktvlistRequest');
				if (empty($post_data)) {
					$post_data = file_get_contents("php://input");
				}
				$post_array = json_decode($post_data, true);
				$_limit = empty($post_array['limit']) ? 100 : intval($post_array['limit']);
				$_offset = empty($post_array['offset']) ? 0 : intval($post_array['offset']);
				$_code = empty($post_array['code']) ? '' : trim($post_array['code']);
				$_best = empty($post_array['best']) ? 'update_time' : trim($post_array['best']);
				$_ordertype = empty($post_array['ordertype']) ? '0' : trim($post_array['ordertype']);
				$_type = empty($post_array['type']) ? '' : trim($post_array['type']);
				$_sjf = empty($post_array['sjf']) ? '' : trim($post_array['sjf']);
				$_sjq = empty($post_array['sjq']) ? '' : trim($post_array['sjq']);
				$_ydzs = empty($post_array['ydzs']) ? '' : trim($post_array['ydzs']);
				$_taocan = empty($post_array['taocan']) ? '' : trim($post_array['taocan']);
				if ($_ordertype == '0') {
					$_order = $_best . ' desc';
				} else {
					$_order = $_best . ' asc';
				}
				$_xktvs = $post_array['list'];
				if (is_array($_xktvs)) {
					$criteria = new CDbCriteria;
					$criteria->addInCondition('xktvid', $_xktvs);
					$criteria->offset = $_offset;
					$criteria->limit = $_limit;
					if ($_type != '') {
						$criteria->addCondition("`type`='" . $_type . "'");
					}
					if ($_code != '') {
						$criteria->addCondition('area_id=' . $_code);
					}

					if ($_ydzs == '1') {
						$criteria->addCondition('ydzs>0');
					}
					if ($_sjf == '1') {
						$criteria->addCondition('sjf>0');
					}
					if ($_sjq == '1') {
						$criteria->addCondition('sjq>0');
					}
					if ($_taocan == '1') {
						$criteria->addCondition('taocan>0');
					}
//
					if ($_best == 'distance') {
						$criteria->order = 'field(`xktvid`,"' . implode('","', $_xktvs) . '")';
					} else {
						$criteria->order = $_order;
					}
//					echo $criteria->order;die();
					$xktvlist_array = Xktv::model()->getKtvlistByID($criteria);
					if (!empty($xktvlist_array)) {
						$result_array['result'] = self::Success;
						$result_array['msg'] = Yii::t('user', 'Get XKTV list success!');
						$result_array['list'] = $xktvlist_array;
						$result_array['total'] = count($xktvlist_array);
						$result_array['now'] = time();
					} else {
						$result_array['result'] = self::ListNull;
						$result_array['msg'] = 'List is Null';
					}

					$this->sendResults($result_array);
				}

			}
			$this->sendResults($result_array, self::BadRequest);
		}

		// Get query data
		$_parent = Yii::app()->request->getQuery('code');
		$_offset = Yii::app()->request->getQuery('offset');
		$_limit = Yii::app()->request->getQuery('limit');
		$_best = Yii::app()->request->getQuery('best');
		$_ordertype = Yii::app()->request->getQuery('ordertype');
		$_type = Yii::app()->request->getQuery('type');
		$_sjf = Yii::app()->request->getQuery('sjf');
		$_sjq = Yii::app()->request->getQuery('sjq');
		$_ydzs = Yii::app()->request->getQuery('ydzs');
		$_type = empty($_type) ? '' : trim($_type);
		$_parent = empty($_parent) ? '' : trim($_parent);
		$_offset = empty($_offset) ? 0 : intval($_offset);
		$_limit = empty($_limit) ? 100 : intval($_limit);
		$_best = empty($_best) ? 'update_time' : trim($_best);
		$_ordertype = empty($_ordertype) ? '0' : trim($_ordertype);
		$_sjf = empty($_sjf) ? '0' : trim($_sjf);
		$_sjq = empty($_sjq) ? '0' : trim($_sjq);
		$_ydzs = empty($_ydzs) ? '0' : trim($_ydzs);
		$_taocan = empty($_taocan) ? '0' : trim($_taocan);
		if ($_ordertype == '0') {
			$_order = $_best . ' desc';
		} else {
			$_order = $_best . ' asc';
		}

		$result_array['msg'] = Yii::t('user', 'No XKTV data!');
		// Search district list
		//$xktv_array = require(YII::app()->basePath . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'XKTVData.php');
		// $search_array = $this->getXKTVList($xktv_array, $_parent);
		$search_array = Xktv::model()->getXktvList($_parent, $_offset, $_limit, $_order, $_type, $_ydzs, $_sjq, $_sjf, $_taocan);

		// foreach ($search_array as $_k => $_v) {
		// 	// get user information
		// 	$_staff_user_name = '';
		// 	$_staff_user_type = 'KTV';

		// 	// TODO check ktv staff available
		// 	$_status = 1;
		// 	$_staff = PlatformUser::model()->findByAttributes(array('username' => $_v['xktvid']));
		// 	if (!is_null($_staff) && !empty($_staff)) {
		// 		$_staff_user_name = ($_staff->username);
		// 		$_staff_user_type = strtoupper($_staff->auth_type);
		// 		// TODO get staff did not respose time
		// 		$criteria_satff = new CDbCriteria;
		// 		$criteria_satff->compare('uid', $_staff->id);
		// 		$criteria_satff->order = 'expire DESC';
		// 		$_staff_session = YiiSession::model()->find($criteria_satff);
		// 		if (self::CHECK_STAFF_ONDUTY) {
		// 			if (!is_null($_staff_session) && !empty($_staff_session)) {
		// 				$_staff_session_time = ($_staff_session->expire - DBHttpSession::SESSION_TIMEOUT);
		// 				if ($_staff_session_time < (time() - self::ONDUTY_EXPIRED_TIME)) {
		// 					$_status = 0;
		// 				}
		// 			} else {
		// 				$_status = 0;
		// 			}
		// 		}
		// 	} else if (self::CHECK_STAFF_ONDUTY) {
		// 		$_status = 0;
		// 	}
		// 	$search_array[$_k]['status'] = intval($_status);
		// }

		if (!empty($search_array)) {
			// get XTV array
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'Get XKTV list success!');
			$result_array['list'] = $search_array;
			$result_array['total'] = count($search_array);
			$result_array['now'] = time();
		} else {
			$result_array['result'] = self::ListNull;
			$result_array['msg'] = 'List is Null';
		}
		// $this->api_current_call = $this->getId() . '/' . $action->id;
		$_userId = $this->user_id;
		$this->recordStatistics('booking/XKTVList', $_userId);
		$this->sendResults($result_array);
	}

	public function actionXktvsearchlist() {
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

		// Get query data
		$_name = Yii::app()->request->getQuery('name');
		$_address = Yii::app()->request->getQuery('address');
		$_telephone = Yii::app()->request->getQuery('telephone');
		$_offset = Yii::app()->request->getQuery('offset');
		$_limit = Yii::app()->request->getQuery('limit');
		$_responsetime = Yii::app()->request->getQuery('responsetime');
		$_rate = Yii::app()->request->getQuery('rate');
		$_price = Yii::app()->request->getQuery('price');
		$_lat = (float) Yii::app()->request->getQuery('lat');
		$_lng = (float) Yii::app()->request->getQuery('lng');
		$_name = empty($_name) ? '' : trim($_name);
		$_offset = empty($_offset) ? 0 : intval($_offset);
		$_limit = empty($_limit) ? 100 : intval($_limit);
		$_rate = empty($_rate) ? 0 : intval($_rate);
		$_ponsetime = empty($_responsetime) ? 0 : intval($_responsetime);

		$_xktvid = Yii::app()->request->getQuery('xktvid');
		// var_dump($_xktvid);
		// die();
		if ($_xktvid != null) {
			$_xktvid .= '';
		}
		// echo $_xktvid;
		// die();

		$_name = $this->utf8Unescape($_name);
		$_address = $this->utf8Unescape($_address);
		$_telephone = $this->utf8Unescape($_telephone);

		$result_array['msg'] = Yii::t('user', 'No XKTV data!');
		// Search district list
		// $xktv_array = require(YII::app()->basePath . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'XKTVData.php');
		// $search_array = $this->searchXKTVList($xktv_array, $_name, $_address, $_telephone);

		if (!empty($_lat) && !empty($_lng)) {
			$search_array = Xktv::model()->searchXktvListByGPS($_name, $_lat, $_lng, $_offset, $_limit);
		} else {
			$search_array = Xktv::model()->searchXktvList($_name, $_address, $_telephone, $_responsetime, $_rate, $_offset, $_limit, $_xktvid);
		}

		if (!empty($search_array)) {
			// get XTV array
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('user', 'Search XKTV list success!');
			$result_array['list'] = $search_array;
			$result_array['total'] = count($search_array);
		} else {
			$result_array['result'] = self::ListNull;
			$result_array['msg'] = 'List is Null';
		}

		// Set response information
		$this->sendResults($result_array);
	}

	protected function getXKTVList($xktv_array = array(), $parent = '') {
		$result_array = array();
		if (empty($xktv_array) || !is_array($xktv_array)) {
			return $result_array;
		}
		foreach ($xktv_array as $code => $ktvlist) {
			if (empty($parent) || $code == $parent) {
				foreach ($ktvlist as $key => $ktv) {
					if (!empty($ktv) && is_array($ktv)) {
						$result_array[] = array(
							"code" => $ktv['code'],
							"xktvid" => $ktv['xktvid'],
							"xktvname" => $ktv['xktvname'],
							"description" => $ktv['description'],
							"smallpicurl" => $this->getRoomPicUrl($ktv['smallpicurl'], 0),
							"bigpicurl" => $this->getRoomPicUrl($ktv['bigpicurl']),
							"lat" => floatval($ktv['lat']),
							"lng" => floatval($ktv['lng']),
							"rate" => intval($ktv['rate']),
							"address" => $ktv['address'],
							"telephone" => $ktv['telephone'],
							"price" => floatval($ktv['price']),
							"openhours" => $ktv['openhours'],
							"roomtotal" => intval($ktv['roomtotal']),
							"roombig" => intval($ktv['roombig']),
							"roommedium" => intval($ktv['roommedium']),
							"roomsmall" => intval($ktv['roomsmall']),
						);
					}
				}
			}
		}
		return $result_array;
	}

	protected function searchXKTVList($xktv_array = array(), $name = '', $address = '', $telephone = '') {
		$result_array = array();
		if (empty($xktv_array) || !is_array($xktv_array)) {
			return $result_array;
		}
		foreach ($xktv_array as $code => $ktvlist) {
			foreach ($ktvlist as $key => $ktv) {
				// search
				$bMatched = true;
				if ($bMatched && !empty($name)) {
					$result = stripos($ktv['xktvname'], trim($name));
					if ($result === false) {
						$bMatched = false;
					}
				}
				if ($bMatched && !empty($address)) {
					$result = stripos($ktv['address'], trim($address));
					if ($result === false) {
						$bMatched = false;
					}
				}
				if ($bMatched && !empty($telephone)) {
					$result = stripos($ktv['telephone'], trim($telephone));
					if ($result === false) {
						$bMatched = false;
					}
				}

				if ($bMatched) {
					$result_array[] = array(
						"xktvid" => $ktv['xktvid'],
						"xktvname" => $ktv['xktvname'],
						"description" => $ktv['description'],
						"smallpicurl" => $this->getRoomPicUrl($ktv['smallpicurl'], 0),
						"bigpicurl" => $this->getRoomPicUrl($ktv['bigpicurl']),
						"lat" => floatval($ktv['lat']),
						"lng" => floatval($ktv['lng']),
						"rate" => intval($ktv['rate']),
						"address" => $ktv['address'],
						"telephone" => $ktv['telephone'],
						"price" => floatval($ktv['price']),
						"openhours" => $ktv['openhours'],
						"roomtotal" => intval($ktv['roomtotal']),
						"roombig" => intval($ktv['roombig']),
						"roommedium" => intval($ktv['roommedium']),
						"roomsmall" => intval($ktv['roomsmall']),
					);
				}
			}
		}
		return $result_array;
	}

	public function actiongettaocaninfo() {
		$info = json_decode('{"result":0,"msg":"获取套餐信息成功","data":{"ktvid":"XKTV00001","type":2,"course":[{"name":"黄金场","starttime":"20:00","endtime":"02:00","show":0,"id":1},{"name":"黄金场","starttime":"20:00","endtime":"02:00","show":0,"id":2},{"name":"黄金场","starttime":"20:00","endtime":"02:00","show":0,"id":3},{"name":"黄金场","starttime":"20:00","endtime":"02:00","show":1,"id":4},{"name":"黄金场","starttime":"20:00","endtime":"02:00","show":1,"id":5}],"roomtype":[{"id":1,"name":"豪华包","desc":"3-6人","show":1},{"id":1,"name":"豪华包","desc":"3-6人","show":0},{"id":2,"name":"豪华包","desc":"3-6人","show":1},{"id":3,"name":"豪华包","desc":"3-6人","show":1}],"days":["2016-05-16","2016-05-17","2016-05-18","2016-05-19","2016-05-20","2016-05-21","2016-05-22"]}}');
//		die(json_encode($info));
		$this->sendResults($info);
	}

	public function actiongettaocanlist() {
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
		$_course = Yii::app()->request->getQuery('course');
		$_roomtype = Yii::app()->request->getQuery('roomtype');
		$_days = Yii::app()->request->getQuery('days');
		$_offset = Yii::app()->request->getQuery('offset');
		$_limit = Yii::app()->request->getQuery('limit');
		// $info = json_decode('{"result":0,"msg":"获取套餐信息成功","data":{"total":5,"list":[{"name":"5个啤酒+3个果盘","id":0,"price":"300","price_yd":"250","show":1},{"name":"5个啤酒+3个果盘","id":1,"price":"300","price_yd":"250","show":1},{"name":"5个啤酒+3个果盘","id":2,"price":"300","price_yd":"250","show":1},{"name":"5个啤酒+3个果盘","id":3,"price":"300","price_yd":"250","show":1},{"name":"5个啤酒+3个果盘","id":4,"price":"300","price_yd":"250","show":0},{"name":"5个啤酒+3个果盘","id":5,"price":"300","price_yd":"250","show":0}]}}');
		//		die(json_encode($info));
		$taocanlist = array();
		$taocanlist['list'] = TaocanContent::model()->getList($_course, $_roomtype, $_days, $_offset, $_limit);
		$taocanlist['total'] = intval(count($taocanlist['list']));
		$result_array['data'] = $taocanlist;
		$result_array['result'] = self::Success;
		$result_array['msg'] = "获取套餐信息成功";
		$this->sendResults($result_array);
	}

	public function actionmakesure() {

	}

	public function utf8Unescape($str) {
		if (empty($str)) {
			return $str;
		}
		$str = rawurldecode($str);
		preg_match_all("/%u.{4}|&#x.{4};|&#d+;|.+/U", $str, $r);
		$ar = $r[0];
		foreach ($ar as $k => $v) {
			if (substr($v, 0, 2) == "%u") {
				$ar[$k] = mb_convert_encoding(pack("H4", substr($v, -4)), "utf-8", "UCS-2");
			} elseif (substr($v, 0, 3) == "&#x") {
				$ar[$k] = mb_convert_encoding(pack("H4", substr($v, 3, -1)), "utf-8", "UCS-2");
			} elseif (substr($v, 0, 2) == "&#") {
				$ar[$k] = mb_convert_encoding(pack("H4", substr($v, 2, -1)), "utf-8", "UCS-2");
			}
		}
		return join("", $ar);
	}

	/**
	 * 个推测试
	 */
	public function actionNotify($_cid, $_title = '', $_msg = '') {
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

		// $cid = '1234567890';
		$push_array[] = $_cid;

		ob_start();
		$result = PHPGetui::pushToUserListTrans($push_array, $_title, $_msg, 0);
		ob_end_clean();

		$result_array['result'] = self::Success;
		$result_array['msg'] = json_encode($result);

		// Set response information
		$this->sendResults($result_array);
	}

	protected function checkExpiredOrders() {
		/*
			        $cur_time = time();
			        $query_command = Yii::app()->getDb()->createCommand();
			        $update_command = Yii::app()->getDb()->createCommand();
			        $_update_sql = 'UPDATE {{order}} SET `status` = :exp_status WHERE `id` = :cur_id AND `status` = :new_status';
			        $sql = 'SELECT `id`, `userid` FROM {{order}} WHERE `status` = ' . self::ORDER_PENDING_STATUS . ' AND time < ' . ($cur_time - self::ORDER_EXPIRED_TIME);
			        $order_list = $query_command->setText($sql)->queryAll();
			        if (!is_null($order_list) && !empty($order_list)) {
			        foreach ($order_list as $key => $value) {
			        $order_id = $value['id'];
			        $user_id = $value['userid'];
			        // send notify to userid
			        // update order status = expired
			        $update_command->setText($_update_sql)->execute(array(':exp_status' => self::ORDER_EXPIRED_STATUS, ':cur_id' => $order_id, ':new_status' => self::ORDER_PENDING_STATUS));
			        $user = PlatformUser::model()->findByAttributes(array('id' => $user_id));
			        if (!is_null($user) && !empty($user)) {
			        $cid = $user->cid;
			        $title = "您的订单已过期";
			        $msg = "订单已经过期，请重新下单！";
			        self::sendNotifyToUser($cid, $msg, $title);
			        }
			        }
			        }
		*/

		// update expired order status
		//RoomBooking::model()->updateAll(array('status' => self::ORDER_EXPIRED_STATUS), 'status = :cur_status AND time < :exp_end_time', array(':cur_status' => self::ORDER_PENDING_STATUS, ':exp_end_time' => ($cur_time - self::ORDER_EXPIRED_TIME)));
	}

}
