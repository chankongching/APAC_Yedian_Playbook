<?php

/**
 * This is the model class for table "{{coupon}}".
 *
 * The followings are the available columns in table '{{coupon}}':
 * @property string $id
 * @property integer $type
 * @property integer $userid
 * @property integer $expire_time
 * @property integer $orderid
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property integer $status
 */
class Coupon extends PSActiveRecord {
	private $couponTypes;
	private $typeidnum;
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{coupon}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, userid, expire_time, orderid, create_user_id, update_user_id, status', 'numerical', 'integerOnly' => true),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, userid, expire_time, orderid, create_time, create_user_id, update_time, update_user_id, status', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'userid' => 'Userid',
			'expire_time' => 'Expire Time',
			'orderid' => 'Orderid',
			'create_time' => 'Create Time',
			'create_user_id' => 'Create User',
			'update_time' => 'Update Time',
			'update_user_id' => 'Update User',
			'status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('type', $this->type);
		$criteria->compare('userid', $this->userid);
		$criteria->compare('expire_time', $this->expire_time);
		$criteria->compare('orderid', $this->orderid);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('create_user_id', $this->create_user_id);
		$criteria->compare('update_time', $this->update_time, true);
		$criteria->compare('update_user_id', $this->update_user_id);
		$criteria->compare('status', $this->status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Coupon the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function getCouponList($_userid, $_offset, $_limit) {
		$criteria = new CDbCriteria;
		$criteria->addInCondition('status', array(0, 1, 2, 4));
		$criteria->addCondition('userid=' . $_userid);
		$criteria->addCondition('available=1');
		$criteria->limit = $_limit; //取1条数据，如果小于0，则不作处理
		$criteria->offset = $_offset; //两条合并起来，则表示 limit 10 offset 1,或者代表了。limit 1,10
		$criteria->order = 'status asc,update_time asc';
		$_list = $this->findAll($criteria);
		if (!empty($_list) && is_array($_list)) {
			return $this->foreachCouponList($_list);
		}
		return array();
	}

//	public function getAvailableCouponList($_userid, $_offset, $_limit) {
	//		$criteria = new CDbCriteria;
	//		$criteria->addCondition('userid=' . $_userid);
	//		$criteria->addInCondition('status', array(0));
	//		$criteria->addCondition('available=1'); //判断是否在优惠券中心显示
	//		$criteria->addCondition('is_available=1');
	//		$criteria->limit = $_limit; //取1条数据，如果小于0，则不作处理
	//		$criteria->offset = $_offset; //两条合并起来，则表示 limit 10 offset 1,或者代表了。limit 1,10
	//		$criteria->order = 'update_time desc';
	//		$_list = $this->findAll($criteria);
	//		if (!empty($_list) && is_array($_list)) {
	//			return $this->foreachAvailableCouponList($_list);
	//		}
	//		return array();
	//	}
	public function getAvailableCouponList($_userid = 0, $_offset = 0, $_limit = 10) {
		$criteria = new CDbCriteria;
		$criteria->addCondition('userid=' . $_userid);
		$criteria->addCondition('status=0 or status=1');
		$criteria->limit = $_limit; //取1条数据，如果小于0，则不作处理
		$criteria->offset = $_offset; //两条合并起来，则表示 limit 10 offset 1,或者代表了。limit 1,10
		$criteria->order = 'update_time desc';
		$_list = $this->findAll($criteria);
		if ($_list == null) {
			return array('msg' => '暂无可用兑酒券', 'result' => 1);
		} else {
			return $this->getTodayAvailablelist($_list);
		}
	}

	protected function getTodayAvailablelist($list) {
		$_list = array();
		$_list_not_is_available = array();
		$today = strtotime(date('Y-m-d', time()));
		$tomorrow = $today + 24 * 60 * 60;
		foreach ($list as $key => $value) {
			$update_time = strtotime($value['update_time']);
			// if ($value['is_available'] == 0) {
			if ($update_time > $today && $update_time < $tomorrow && $value['is_available'] == 0 && $value['update_time'] > $value['create_time']) {
				$_list_not_is_available[] = $value;
				// }
			} else {
				$_list[] = $value;
			}
		}
		if (count($_list_not_is_available) > 0) {
			return array('msg' => '超过今日使用上限', 'result' => 1, 'today' => $today, 'tomorrow' => $tomorrow, 'list_not_is_available' => $_list_not_is_available);
		} else {
			return array('msg' => '获取可用酒券列表成功', 'result' => 0, 'list' => $this->foreachAvailableCouponList($_list), 'today' => $today, 'tomorrow' => $tomorrow, 'list_not_is_available' => $_list_not_is_available);
		}
	}

	public function getCouponCount($_userid) {
		$criteria = new CDbCriteria;
		$criteria->addInCondition('status', array(0));
		$criteria->addCondition('userid=' . $_userid);
		$criteria->addCondition('available=1');
		$criteria->addCondition('is_available=1');
		$_list = $this->findAll($criteria);
		if (!empty($_list) && is_array($_list)) {
			return count($_list);
		}
		return 0;
	}

	public function getOrderList($_userid, $_offset, $_limit) {
		$_list = $this->findAllByAttributes(array('userid' => $_userid), array('offset' => $_offset, 'limit' => $_limit, 'order' => 'update_time desc'));
		if (!empty($_list) && is_array($_list)) {
			return $this->foreachOrderList($_list);
		}
		return array();
	}

	public function foreachCouponList($_list = array()) {
		$_coupon_list = array();
		foreach ($_list as $key => $coupon) {
			$_coupon_list[] = $this->getCouponInfo($coupon);
		}
		return $_coupon_list;
	}
	public function foreachAvailableCouponList($_list = array()) {
		$_coupon_list = array();
		foreach ($_list as $key => $coupon) {
			$ttime = mktime(23, 59, 59, date('m', strtotime($coupon['create_time'])), date('d', strtotime($coupon['create_time'])), date('Y', strtotime($coupon['create_time'])));
			if ($coupon['type'] == 5 || $coupon['type'] == 31) {
				if (time() >= $ttime) {
					$_coupon_list[] = $this->getCouponInfo($coupon);
				}
			} else {
				$_coupon_list[] = $this->getCouponInfo($coupon);
			}
		}
		return $_coupon_list;
	}

	public function getInfoByid($couponid, $_userid) {
		$coupon = $this->findByAttributes(array('id' => $couponid, 'userid' => $_userid));
		if ($coupon == null) {
			return null;
		}
		return $this->getCouponInfo($coupon);
	}

	public function getCouponInfo($coupon) {
		$_coupon_info = array(
			'id' => intval($coupon["id"]),
			'type' => intval($coupon["type"]),
			'expire_time' => intval($coupon["expire_time"]),
			'start_time' => $this->getstarttime($coupon["type"], $coupon['create_time']),
			'orderid' => intval($coupon["orderid"]),
			'status' => intval($this->getCouponStatus($coupon["status"], $coupon["is_available"])),
			'qrcode_img' => '/wechatshangjia/' . $coupon['qrcode_img'],
			'name' => $this->getCouponTypeInfo($coupon["type"], 'name'),
			// 'desc' => $this->getCouponTypeInfo($coupon["type"], 'desc'),
			'img' => $this->getCouponTypeInfo($coupon["type"], 'img'),
			'count' => intval($this->getCouponTypeInfo($coupon["type"], 'count')),
			'img_disable' => $this->getCouponTypeInfo($coupon["type"], 'img_disable'),
			'update_time' => $coupon['update_time'],
			//            'is_available' => $coupon['is_available'],
		);
		// echo $_coupon_info['status'];
		return $_coupon_info;
	}

	protected function getstarttime($type = '', $create_time) {
		if ($type == 5) {
			$tt = mktime(23, 59, 59, date('m', strtotime($create_time)), date('d', strtotime($create_time)), date('Y', strtotime($create_time)));
			$tt1 = $tt + 1;
			return $tt1;
		} else {
			return strtotime($create_time);
		}
	}

	protected function getCouponStatus($status = '', $is_available = '') {
		$st = 0;
		if ($status !== '' && $is_available !== '') {
			if ($status == 0 && $is_available == 1) {
				// 未使用
				$st = 0;
			} elseif ($status == 0 && $is_available == 0) {
				// 使用中
				$st = 5;
			} elseif ($status == 1 && $is_available == 0) {
				// 已使用
				$st = 1;
			} elseif ($status == 2 && $is_available == 0) {
				// 已过期
				$st = 2;
			} elseif ($status == 3 && $is_available == 0) {
				// 未确认
				$st = 3;
			} elseif ($status == 4 && $is_available == 0) {
				// 已失效
				$st = 4;
			} elseif ($status == 1 && $is_available == 1) {
				// 已使用
				$st = 1;
			} elseif ($status == 2 && $is_available == 1) {
				// 已过期
				$st = 2;
			} elseif ($status == 3 && $is_available == 1) {
				// 未确认
				$st = 3;
			} elseif ($status == 4 && $is_available == 1) {
				// 已失效
				$st = 4;
			}
			// echo $status;
			return $st;
		} else {
			return $st;
		}
	}

	// protected function getCouponBeerType($typeid) {
	// 	$couponType = $this->getCouponTypeInfo($typeid, 'beer_type');
	// 	if ($couponType != null) {
	// 		$beerType = BeerType::model()->getNamebyId();
	// 		if($beerType!=null){
	// 			return $beerType;
	// 		}
	// 	}

	// }
	protected function getCouponTypeInfo($typeid, $field = 'name') {
		return CouponType::model()->getbyID($typeid, $field);
	}

	public function getCouponCountById($userid) {
		return $this->countByAttributes(array('userid' => $userid));
	}

	public function getStatus($_userid, $couponid) {
		$couponinfo = $this->findByAttributes(array('userid' => $_userid, 'id' => $couponid));
		if ($couponinfo != null) {
			return $couponinfo['status'];
		} else {
			return null;
		}
	}

	public function ExpireByUserCancelOrder($userid, $orderid) {
		$coupon = $this->findByAttributes(array('orderid' => $orderid, 'userid' => $userid));
		if ($coupon != null) {
			if ($coupon->status == 0 || $coupon->status == 3) {
				$coupon->status = 4;
				if ($coupon->save()) {
					return true;
				} else {
					return false;
				}
			}

		}
	}

	public function checkExpire($userid) {
		$criteria = new CDbCriteria;
		$criteria->addInCondition('status', array(0));
		$criteria->addCondition('expire_time<' . time());
		$criteria->addCondition('userid=' . $userid);
		$_list = $this->findAll($criteria);
		foreach ($_list as $key => $value) {
			$coupon = $this->findByPk($value['id']);
			$coupon->status = 2;
			$coupon->is_available = 0;
			$coupon->save();
		}
	}

	public function checkOneDay() {

	}

	public function setAvailable($id = '') {
		$coupon = $this->findByPk($id);
		$coupon->is_available = 0;
		if ($coupon->save()) {
			return true;
		} else {
			return false;
		}
	}

	public function getCouponByEvents($userid) {
		$this->SetcouponTypes();
		$couponTypes = $this->couponTypes;
		$criteria = new CDbCriteria;
		$criteria->addInCondition('type', $couponTypes);
		$criteria->addCondition('userid=' . $userid);
		$user_coupons = $this->find($criteria);

		if (count($user_coupons) > 0) {

			return array('msg' => 'get Coupon Failed', 'result' => 1, 'coupon' => array('beer_type' => intval($this->getCouponTypeInfo($user_coupons->type, 'beer_type')), 'name' => $this->getCouponTypeInfo($user_coupons->type, 'name'), 'count' => intval($this->getCouponTypeInfo($user_coupons->type, 'count')), 'expire_time' => intval($user_coupons->expire_time)));
		} else {

			$typeid = $this->typeidnum;
			$coupon_info_name = $this->getCouponTypeInfo($couponTypes[$typeid], 'name');
			$coupon_info_count = $this->getCouponTypeInfo($couponTypes[$typeid], 'count');
			$coupon_new = new Coupon();
			$coupon_new->userid = $userid;
			$coupon_new->type = $couponTypes[$typeid];
			$coupon_new->create_user_id = $userid;
			$coupon_new->expire_time = strtotime(date('Y-m-d')) + 2 * 7 * 24 * 60 * 60;
			$coupon_new->status = 0;
			$coupon_new->available = 1;
			$coupon_new->is_available = 1;
			$coupon_new->channel = 1; //线下渠道
			if ($coupon_new->save()) {
				return array('msg' => 'get Coupon Success', 'count' => intval($coupon_info_count), 'type' => intval($couponTypes[$typeid]), 'result' => 0, 'name' => $coupon_info_name, 'beer_type' => intval($this->getCouponTypeInfo($coupon_new->type, 'beer_type')), 'expire_time' => intval($coupon_new->expire_time));
			}

		}
	}
	public function getCouponStatusByEvents($userid = 0) {
		$this->SetcouponTypes();
		$couponTypes = $this->couponTypes;
		$criteria = new CDbCriteria;
		$criteria->addInCondition('type', $couponTypes);
		if ($userid != 0) {
			$criteria->addCondition('userid=' . $userid);

		}
		$user_coupons = $this->find($criteria);
		if ($user_coupons != null) {
			// var_dump($user_coupons);die();

			$coupon_info_name = $this->getCouponTypeInfo($user_coupons['type'], 'name');
			$coupon_info_count = intval($this->getCouponTypeInfo($user_coupons['type'], 'count'));
			if ($user_coupons->available == 0) {
				return array('result' => 0, 'coupon' => array('beer_type' => intval($this->getCouponTypeInfo($user_coupons->type, 'beer_type')), 'name' => $coupon_info_name, 'count' => $coupon_info_count, 'expire_time' => intval($user_coupons['expire_time'])));
			}
			return array('result' => 1, 'coupon' => array('beer_type' => intval($this->getCouponTypeInfo($user_coupons->type, 'beer_type')), 'name' => $this->getCouponTypeInfo($user_coupons->type, 'name'), 'count' => intval($this->getCouponTypeInfo($user_coupons->type, 'count')), 'expire_time' => intval($user_coupons->expire_time)));
		} else {

			$typeid = $this->typeidnum;
			$coupon_info_name = $this->getCouponTypeInfo($couponTypes[$typeid], 'name');
			$coupon_info_count = $this->getCouponTypeInfo($couponTypes[$typeid], 'count');
			$coupon_new = new Coupon();
			$coupon_new->userid = $userid;
			$coupon_new->type = $couponTypes[$typeid];
			$coupon_new->create_user_id = $userid;
			$coupon_new->expire_time = strtotime(date('Y-m-d')) + 2 * 7 * 24 * 60 * 60;
			$coupon_new->channel = 0; //线上渠道
			$userinfo = PlatformUser::model()->findByPk($userid);
			if ($userinfo != null) {
				// echo $userinfo['openid'];
				$ch = curl_init('http://letsktv.chinacloudapp.cn/wechat_ktv/home/Event/is_subcribe?openid=' . $userinfo['openid']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				// echo $result;die();
				$info = json_decode($result, true);
				if ($info['result'] == 0) {
					$coupon_new->status = 0;
					$coupon_new->available = 1;
				} else {
					$coupon_new->status = 3;
					$coupon_new->available = 0;
				}
			}

			$coupon_new->is_available = 1;
			if ($coupon_new->save()) {
				return array('result' => 0, 'coupon' => array('beer_type' => intval($this->getCouponTypeInfo($coupon_new->type, 'beer_type')), 'name' => $coupon_info_name, 'count' => intval($coupon_info_count), 'expire_time' => intval($coupon_new->expire_time)));
			}

		}
	}

	protected function SetcouponTypes() {
		// $this->couponTypes = array(13, 14, 14, 14, 14, 15, 15, 15, 15, 15);
		// $this->couponTypes = array(19, 20, 20, 20, 20, 21, 21, 21, 21, 21);
		//		$this->couponTypes = array(19, 19, 19, 19, 20, 20, 20, 21, 21, 21);
		$this->couponTypes = array(44, 44, 44, 44, 45, 45, 45, 46, 46, 46);
		$a_length = count($this->couponTypes) - 1;
		$this->typeidnum = rand(0, $a_length);
	}

	public function tuihuan($id = '') {
		if ($id != '') {
			$coupon = $this->findByPk($id);
			if (time() < $coupon->expire_time) {
				$coupon->is_available = 1;
				$coupon->status = 0;
				if ($coupon->save()) {
					return true;
				} else {
					return false;
				}
			} else {
				$coupon->status = 2;
				$coupon->is_available = 0;
				if ($coupon->save()) {
					return true;
				} else {
					return false;
				}
			}
		}
	}

	public function hasusedtwocoupon($userid = '') {
		if ($userid != '') {
			$criteria = new CDbCriteria;
			$criteria->addCondition('userid=' . $userid);
			$criteria->addCondition('is_available=0');
			$criteria->addCondition("DATEDIFF(update_time,NOW())=0");
			$coupons = $this->findAll($criteria);
			// return $coupons;
			if (count($coupons) >= 2) {
				return true;
			} else {
				return false;
			}

		}

	}

	public function findbyuidandsource($uid, $source) {
		$coupon = $this->findByAttributes(array('userid' => $uid, 'source' => $source));
		if ($coupon != null) {
			$coupon_type = $coupon['type'];
			$coupon_info = array(
				'name' => $this->getCouponTypeInfo($coupon_type, 'name'),
				'count' => $this->getCouponTypeInfo($coupon_type, 'count'),
				'beer_type' => $this->getCouponTypeInfo($coupon_type, 'beer_type'));
			return array('status' => 1, 'coupon' => $coupon_info);
		}
		return array('status' => 0);
	}

	public function getNewCouponByShareCoupon($userid, $sharecouponid) {
		$couponTypes = array(32, 32, 32, 32, 33, 33, 33, 34, 34, 34, 35, 35, 35, 35, 36, 36, 36, 37, 37, 37);
		$coupon = new Coupon();
		$coupon->userid = $userid;
		$coupon->expire_time = time() + 24 * 60 * 60 * 14;
		$coupon->source = $sharecouponid;
		$coupon->status = 0;
		$coupon->available = 1;
		$coupon->is_available = 1;
		$coupon->type = $couponTypes[rand(0, 19)];
		if ($coupon->save()) {
			$add_status = CouponShare::model()->sharecountadd($sharecouponid);
			if ($add_status['status'] == 0) {
				$coupon_info = array(
					'name' => $this->getCouponTypeInfo($coupon->type, 'name'),
					'count' => $this->getCouponTypeInfo($coupon->type, 'count'),
					'beer_type' => $this->getCouponTypeInfo($coupon->type, 'beer_type'));
				return array('result' => 0, 'coupon' => $coupon_info);
			}

		}

	}

}
