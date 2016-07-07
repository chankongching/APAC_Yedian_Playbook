<?php

/**
 * This is the model class for table "{{xktv}}".
 *
 * The followings are the available columns in table '{{xktv}}':
 * @property integer $id
 * @property integer $area_id
 * @property string $code
 * @property string $xktvid
 * @property string $name
 * @property string $description
 * @property string $content
 * @property string $smallpicurl
 * @property string $bigpicurl
 * @property string $address
 * @property string $telephone
 * @property string $openhours
 * @property float $lat
 * @property float $lng
 * @property float $price
 * @property float $rate
 * @property integer $roomtotal
 * @property integer $roombig
 * @property integer $roommedium
 * @property integer $roomsmall
 * @property float $responsetime
 * @property float $distance
 * @property integer $roombigprice
 * @property integer $roommediumprice
 * @property integer $roomsmallprice
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property CheckinCode[] $checkinCodes
 * @property CheckinState[] $checkinStates
 * @property CheckinUser[] $checkinUsers
 * @property DeviceState[] $deviceStates
 * @property Vendor $vendor
 * @property RoomBooking $roomBooking
 * @property RoomExtra $roomExtra */
class Xktv extends PSActiveRecord {

	const EARTH_RADIUS = 6378.137; //地球半径

	protected $juli = 0;

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{xktv}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('xktvid, name', 'required'),
			array('area_id, roomtotal, roombig, roommedium, roomsmall, roombigprice, roommediumprice, roomsmallprice', 'numerical', 'integerOnly' => true),
			//array('lat, lng, price, distance', 'numerical'),
			array('code', 'length', 'max' => 20),
			array('xktvid, name, description, content, smallpicurl, bigpicurl, address, telephone, openhours', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, area_id, code, xktvid, name, description, content, smallpicurl, bigpicurl, address, telephone, openhours, lat, lng, price, rate, roomtotal, roombig, roommedium, roomsmall, responsetime, distance, roombigprice, roommediumprice, roomsmallprice, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//'checkinCodes' => array(self::HAS_MANY, 'CheckinCode', 'id'),
			//'checkinStates' => array(self::HAS_MANY, 'CheckinState', 'id'),
			//'checkinUsers' => array(self::HAS_MANY, 'CheckinUser', 'id'),
			//'deviceStates' => array(self::HAS_MANY, 'DeviceState', 'id'),
			//'vendor' => array(self::BELONGS_TO, 'Vendor', 'vendor_id'),
			//'roomBooking' => array(self::HAS_ONE, 'RoomBooking', 'id'),
			//'roomExtra' => array(self::HAS_ONE, 'RoomExtra', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => Yii::t('Xktv', 'ID'),
			'area_id' => Yii::t('Xktv', 'Area id'),
			'code' => Yii::t('Xktv', 'Code'),
			'xktvid' => Yii::t('Xktv', 'Xktvid'),
			'name' => Yii::t('Xktv', 'Name'),
			'description' => Yii::t('Xktv', 'Description'),
			'content' => Yii::t('Xktv', 'Content'),
			'smallpicurl' => Yii::t('Xktv', 'Smallpic Url'),
			'bigpicurl' => Yii::t('Xktv', 'Bigpic Url'),
			'address' => Yii::t('Xktv', 'Address'),
			'telephone' => Yii::t('Xktv', 'Telephone'),
			'openhours' => Yii::t('Xktv', 'Open Hours'),
			'lat' => Yii::t('Xktv', 'GPS Lat'),
			'lng' => Yii::t('Xktv', 'GPS Lng'),
			'price' => Yii::t('Xktv', 'Price/Hour'),
			'rate' => Yii::t('Xktv', 'Rate'),
			'roomtotal' => Yii::t('Xktv', 'Total Room Number'),
			'roombig' => Yii::t('Xktv', 'Big Room Number'),
			'roommedium' => Yii::t('Xktv', 'Medium Room Number'),
			'roomsmall' => Yii::t('Xktv', 'Small Room Number'),
			'responsetime' => Yii::t('Xktv', 'Response Time'),
			'distance' => Yii::t('Xktv', 'Distance'),
			'roombigprice' => Yii::t('Xktv', 'Big Room Price'),
			'roommediumprice' => Yii::t('Xktv', 'Medium Room Price'),
			'roomsmallprice' => Yii::t('Xktv', 'Small Room Price'),
			'create_time' => Yii::t('Xktv', 'Create Time'),
			'create_user_id' => Yii::t('Xktv', 'Create User'),
			'update_time' => Yii::t('Xktv', 'Update Time'),
			'update_user_id' => Yii::t('Xktv', 'Update User'),
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

		$criteria->compare('id', $this->id);
		$criteria->compare('area_id', $this->area_id);
		$criteria->compare('code', $this->code, true);
		$criteria->compare('xktvid', $this->xktvid, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('content', $this->content, true);
		$criteria->compare('smallpicurl', $this->smallpicurl, true);
		$criteria->compare('bigpicurl', $this->bigpicurl, true);
		$criteria->compare('address', $this->address, true);
		$criteria->compare('telephone', $this->telephone, true);
		$criteria->compare('openhours', $this->openhours, true);
		$criteria->compare('lat', $this->lat);
		$criteria->compare('lng', $this->lng);
		$criteria->compare('price', $this->price);
		$criteria->compare('rate', $this->rate);
		$criteria->compare('roomtotal', $this->roomtotal);
		$criteria->compare('roombig', $this->roombig);
		$criteria->compare('roommedium', $this->roommedium);
		$criteria->compare('roomsmall', $this->roomsmall);
		$criteria->compare('responsetime', $this->responsetime);
		$criteria->compare('distance', $this->distance);
		$criteria->compare('roombigprice', $this->roombigprice);
		$criteria->compare('roommediumprice', $this->roommediumprice);
		$criteria->compare('roomsmallprice', $this->roomsmallprice);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('create_user_id', $this->create_user_id);
		$criteria->compare('update_time', $this->update_time, true);
		$criteria->compare('update_user_id', $this->update_user_id);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Xktv the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function importXktv($xktv_array = array(), $check_exists = false) {
		$ktv_area_id = isset($xktv_array['area_id']) ? $xktv_array['area_id'] : 0;
		$ktv_code = isset($xktv_array['code']) ? $xktv_array['code'] : '';
		$ktv_xktvid = isset($xktv_array['xktvid']) ? $xktv_array['xktvid'] : '';
		$ktv_name = isset($xktv_array['xktvname']) ? $xktv_array['xktvname'] : '';
		$ktv_description = isset($xktv_array['description']) ? $xktv_array['description'] : '';
		$ktv_content = isset($xktv_array['content']) ? $xktv_array['content'] : '';
		$ktv_smallpicurl = isset($xktv_array['smallpicurl']) ? $xktv_array['smallpicurl'] : '';
		$ktv_bigpicurl = isset($xktv_array['bigpicurl']) ? $xktv_array['bigpicurl'] : '';
		$ktv_address = isset($xktv_array['address']) ? $xktv_array['address'] : '';
		$ktv_telephone = isset($xktv_array['telephone']) ? $xktv_array['telephone'] : '';
		$ktv_openhours = isset($xktv_array['openhours']) ? $xktv_array['openhours'] : '';
		$ktv_lat = isset($xktv_array['lat']) ? $xktv_array['lat'] : 0;
		$ktv_lng = isset($xktv_array['lng']) ? $xktv_array['lng'] : 0;
		$ktv_price = isset($xktv_array['price']) ? $xktv_array['price'] : 0;
		$ktv_rate = isset($xktv_array['rate']) ? $xktv_array['rate'] : 0;
		$ktv_roomtotal = isset($xktv_array['roomtotal']) ? $xktv_array['roomtotal'] : 0;
		$ktv_roombig = isset($xktv_array['roombig']) ? $xktv_array['roombig'] : '';
		$ktv_roommedium = isset($xktv_array['roommedium']) ? $xktv_array['roommedium'] : 0;
		$ktv_roomsmall = isset($xktv_array['roomsmall']) ? $xktv_array['roomsmall'] : 0;
		$ktv_responsetime = isset($xktv_array['responsetime']) ? $xktv_array['responsetime'] : 0;
		$ktv_distance = isset($xktv_array['distance']) ? $xktv_array['distance'] : 0;
		$ktv_roombigprice = isset($xktv_array['roombigprice']) ? $xktv_array['roombigprice'] : 100;
		$ktv_roommediumprice = isset($xktv_array['roommediumprice']) ? $xktv_array['roommediumprice'] : 80;
		$ktv_roomsmallprice = isset($xktv_array['roomsmallprice']) ? $xktv_array['roomsmallprice'] : 50;

		if (empty($ktv_name)) {
			return null;
		}

		$model = $this->findByAttributes(array('name' => $ktv_name, 'xktvid' => $ktv_xktvid));
		if (!is_null($model) && !empty($model)) {
			if ($check_exists) {
				throw new Exception("ktv '$ktv_name' already exists.");
			}
		} else {
			$model = new Xktv;
		}

		$model->area_id = $ktv_area_id;
		$model->code = $ktv_code;
		$model->xktvid = $ktv_xktvid;
		$model->name = $ktv_name;
		$model->description = $ktv_description;
		$model->content = $ktv_content;
		$model->smallpicurl = $ktv_smallpicurl;
		$model->bigpicurl = $ktv_bigpicurl;
		$model->address = $ktv_address;
		$model->telephone = $ktv_telephone;
		$model->openhours = $ktv_openhours;
		$model->lat = $ktv_lat;
		$model->lng = $ktv_lng;
		$model->price = $ktv_price;
		$model->rate = $ktv_rate;
		$model->roomtotal = $ktv_roomtotal;
		$model->roombig = $ktv_roombig;
		$model->roommedium = $ktv_roommedium;
		$model->roomsmall = $ktv_roomsmall;
		$model->responsetime = $ktv_responsetime;
		$model->distance = $ktv_distance;
		$model->roombigprice = $ktv_roombigprice;
		$model->roommediumprice = $ktv_roommediumprice;
		$model->roomsmallprice = $ktv_roomsmallprice;
		if ($model->save()) {
			return $model;
		} else {
			return NULL;
		}
	}

	protected function foreachXktvList($_list = array(), $_lat = 0, $_lng = 0) {
		$_xktv_list = array();
		foreach ($_list as $key => $ktv) {
			$_xktv_list[] = $this->getKtvInfo($ktv, $_lat, $_lng);
		}
		return $_xktv_list;
	}
	protected function foreachXktvcoodList($_list = array()) {
		$_xktv_list = array();
		foreach ($_list as $key => $ktv) {
			if ($ktv['status'] == 1) {
				$_xktv_list[] = $this->getKtvCood($ktv);
			}

		}
		return $_xktv_list;
	}

	protected function getKtvCood($ktv) {
		$_xktv_info = array(
			"xktvid" => $ktv['xktvid'],
			"lat" => floatval($ktv['lat']),
			"lng" => floatval($ktv['lng']),
			"sjq" => $ktv['sjq'] > 0 && $ktv['type'] == 2 ? 1 : 0,
			"taocan" => $ktv['taocan'] > 0 ? 1 : 0,
			"rate" => floatval($ktv['DecorationRating']) * 0.3 + floatval($ktv['SoundRating']) * 0.15 + floatval($ktv['ServiceRating']) * 0.15 + floatval($ktv['ConsumerRating']) * 0.2 + floatval($ktv['FoodRating']) * 0.2,

		);
		return $_xktv_info;
	}

	public function getKtvInfo($ktv, $_lat = 0, $_lng = 0) {
		if (!empty($ktv['spic1']) && !empty($ktv['bpic1'])) {
			$_piclist[] = array(
				"smallpicurl" => $this->getRoomPicUrl($ktv['spic1'], 0),
				"bigpicurl" => $this->getRoomPicUrl($ktv['bpic1']),
			);
		}
		if (!empty($ktv['spic2']) && !empty($ktv['bpic2'])) {
			$_piclist[] = array(
				"smallpicurl" => $this->getRoomPicUrl($ktv['spic2'], 0),
				"bigpicurl" => $this->getRoomPicUrl($ktv['bpic2']),
			);
		}
		if (!empty($ktv['spic3']) && !empty($ktv['bpic3'])) {
			$_piclist[] = array(
				"smallpicurl" => $this->getRoomPicUrl($ktv['spic3'], 0),
				"bigpicurl" => $this->getRoomPicUrl($ktv['bpic3']),
			);
		}
		if (!empty($ktv['spic4']) && !empty($ktv['bpic4'])) {
			$_piclist[] = array(
				"smallpicurl" => $this->getRoomPicUrl($ktv['spic4'], 0),
				"bigpicurl" => $this->getRoomPicUrl($ktv['bpic4']),
			);
		}
		if (!empty($ktv['spic5']) && !empty($ktv['bpic5'])) {
			$_piclist[] = array(
				"smallpicurl" => $this->getRoomPicUrl($ktv['spic5'], 0),
				"bigpicurl" => $this->getRoomPicUrl($ktv['bpic5']),
			);
		}

		if (empty($_piclist)) {
			$_piclist[] = array(
				"smallpicurl" => $this->getRoomPicUrl($ktv['smallpicurl'], 0),
				"bigpicurl" => $this->getRoomPicUrl($ktv['bigpicurl']),
			);
		}

		// distance
		$_xktv_list_distance = 0.00;
		$_xktv_list_distance_1 = 0.00;
		if ($_lat && $_lng) {
			//$_xktv_list[$key]["distance"] = ACOS(SIN(($_lat * 3.1415) / 180 ) *SIN(($ktv['lat'] * 3.1415) / 180 ) +COS(($_lat * 3.1415) / 180 ) * COS(($ktv['lat'] * 3.1415) / 180 ) *COS(($_lng * 3.1415) / 180 - ($ktv['lng'] * 3.1415) / 180 ) ) * 6380;
			//$_xktv_list_distance = 2 * 6378.137* ASIN(SQRT(POW(SIN(PI()*($_lat-$ktv['lat'])/360),2)+COS(PI()*$_lng/180)* COS($ktv['lat'] * PI()/180)*POW(SIN(PI()*($_lng-$ktv['lng'])/360),2)));
			$_xktv_list_distance = floatval($ktv['juli'] / 1000);

			// test distance
			//$_xktv_list_distance_1 = $this->getDistance(floatval($_lat), floatval($_lng), floatval($ktv['lat']), floatval($ktv['lng']));
		}

		$_xktv_info = array(
			'id' => intval($ktv['id']),
			"area_id" => $ktv['area_id'],
			"xktvid" => $ktv['xktvid'],
			"xktvname" => $ktv['name'],
			"description" => $ktv['description'],
			"piclist" => $_piclist,
			"lat" => floatval($ktv['lat']),
			"lng" => floatval($ktv['lng']),
			"district" => $ktv['district'],
			"address" => $ktv['district'] . $ktv['address'],
			"telephone" => $ktv['pretelephone'] . '-' . $ktv['telephone'],
			"price" => floatval($ktv['price']),
			"openhours" => $ktv['openhours'],
			"roomtotal" => intval($ktv['roomtotal']),
			"roombig" => intval($ktv['roombig']),
			"roommedium" => intval($ktv['roommedium']),
			"roomsmall" => intval($ktv['roomsmall']),
			"roombigprice" => intval($ktv['roombigprice']),
			"roommediumprice" => intval($ktv['roommediumprice']),
			"roomsmallprice" => intval($ktv['roomsmallprice']),
			"responsetime" => floatval($ktv['responsetime']),
			'Circle' => $ktv['Circle'],
			'DecorationRating' => floatval($ktv['DecorationRating']),
			'SoundRating' => floatval($ktv['SoundRating']),
			'ServiceRating' => floatval($ktv['ServiceRating']),
			'ConsumerRating' => floatval($ktv['ConsumerRating']),
			'FoodRating' => floatval($ktv['FoodRating']),
			'distance' => $_xktv_list_distance,
			'status' => intval($this->getStatus($ktv['xktvid'])),
			"devices" => $this->getDevices($ktv['xktvid']),
			"special" => intval($ktv['special']),
			"rate" => floatval($ktv['DecorationRating']) * 0.3 + floatval($ktv['SoundRating']) * 0.15 + floatval($ktv['ServiceRating']) * 0.15 + floatval($ktv['ConsumerRating']) * 0.2 + floatval($ktv['FoodRating']) * 0.2,
			"type" => intval($ktv['type']),
			"ydzs" => intval($ktv['ydzs']) > 0 ? 1 : 0,
			"sjf" => intval($ktv['sjf']) > 0 ? 1 : 0,
			"sjq" => intval($ktv['sjq']) > 0 && $ktv['type'] == 2 ? 1 : 0,
			"zqss" => intval($ktv['zqss']) > 0 ? 1 : 0,
			"zxgy" => intval($ktv['zxgy']) > 0 ? 1 : 0,
			"tm" => intval($ktv['tm']) > 0 ? 1 : 0,
			"sjdg" => intval($ktv['sjdg']) > 0 ? 1 : 0,
			"openhours_s" => $this->getOpenHours($ktv['openhours'], 's'),
			"openhours_e" => $this->getOpenHours($ktv['openhours'], 'e'),
			"jqname" => $this->getjqname($ktv['sjq']),
			'lastofjiedan' => $this->getLastJiedan($ktv['lasttimeofjiedan']),
			'taocan' => intval($ktv['taocan']),
		);

		return $_xktv_info;
	}
	protected function getLastJiedan($value = '') {
		if ($value != '') {
			$Hour = date('H', strtotime($value));
			if ($Hour > 12) {
				return array('time' => $value, 'ciri' => 0);
			} else {
				return array('time' => $value, 'ciri' => 1);
			}
		} else {
			return null;
		}
	}
	protected function getjqname($sjq = '') {
		return CouponType::model()->getbyID($sjq, 'name');
	}
	protected function getOpenHours($openhours, $type = '') {
		$openhour = explode('-', $openhours);
		if (count($openhour) != '2') {
			return '0';
		}
		if ($type == '') {
			return null;
		} elseif ($type == 's') {
			return $openhour[0];
			// return array('time' => $openhour[0], 'ciri' => 0);
		} elseif ($type == 'e') {
			// return array('time' => $openhour[1], 'ciri' => 0);
			return $openhour[1];
		}
	}

// "bathroom,WIFI,YeDianPad,WirelessMicrophones,Themerooms,parking,XKTV"

	protected function getDevices($xktvid) {
		if ($xktvid == '' || empty($xktvid)) {
			return "bathroom,WIFI,YeDianPad,WirelessMicrophones,Themerooms,parking,XKTV";
		} else {
			$result = self::model()->findByAttributes(array('xktvid' => $xktvid));
			// var_dump($result);die();
			$res_array = array();
			// $res_array[] = $result['bathroom']?'bathroom':'';
			if ($result['bathroom']) {
				$res_array[] = Yii::t('Xktv', 'bathroom');
			}
			if ($result['WIFI']) {
				$res_array[] = Yii::t('Xktv', 'WIFI');
			}
			// if ($result['YeDianPad']) {
			// 	$res_array[] = Yii::t('Xktv', 'YeDianPad');
			// }
			if ($result['WirelessMicrophones']) {
				$res_array[] = Yii::t('Xktv', 'WirelessMicrophones');
			}
			if ($result['Themerooms']) {
				$res_array[] = Yii::t('Xktv', 'Themerooms');
			}
			if ($result['parking']) {
				$res_array[] = Yii::t('Xktv', 'parking');
			}
			if ($result['XKTV']) {
				$res_array[] = Yii::t('Xktv', 'XKTV');
			}
			if ($result['buffet']) {
				$res_array[] = Yii::t('Xktv', 'buffet');
			}
			if ($result['zqss']) {
				$res_array[] = Yii::t('Xktv', 'zqss');
			}
			if ($result['zxgy']) {
				$res_array[] = Yii::t('Xktv', 'zxgy');
			}
			if ($result['sjdg']) {
				$res_array[] = Yii::t('Xktv', 'sjdg');
			}
			if ($result['tm']) {
				$res_array[] = Yii::t('Xktv', 'tm');
			}
			// return implode($res_array, ',');
			return $res_array;
		}
	}

	public function getStatus($xktvid) {
		if ($xktvid == 'XKTV01158') {
			return 1;
		} else {
			return 0;
		}
	}

//	public function getXktvList($_code, $_offset = 0, $_limit = 100) {
	//		$_list = $this->findAllByAttributes(empty($_code) ? array() : array('area_id' => $_code), array('offset' => $_offset, 'limit' => $_limit, 'order' => 'update_time desc'));
	//		if (!empty($_list) && is_array($_list)) {
	//			return $this->foreachXktvList($_list);
	//		}
	//		return array();
	//	}

	public function getCollection($criteria) {
		$_list = $this->findAll($criteria);
		if (!empty($_list) && is_array($_list)) {
			return $this->foreachXktvList($_list);
		}
		return array();
	}

	public function getKtvlistByID($criteria) {
		$_list = $this->findAll($criteria);
		if (!empty($_list) && is_array($_list)) {
			return $this->foreachXktvList($_list);
		}
		return array();
	}

	public function getXktvList($_code, $_offset = 0, $_limit = 100, $_best = 'CONVERT(name USING GBK) asc', $type = '', $_ydzs = '0', $_sjq = '0', $_sjf = '0', $_taocan = '0') {
		$criteria = new CDbCriteria();
		if ($_best == 'rate asc') {
			$criteria->select = '*,`DecorationRating`*0.3+`SoundRating`*0.15+`ServiceRating`*0.15+`ConsumerRating`*0.2+`FoodRating`*0.2 as arate';
			$criteria->order = 'arate asc,CONVERT(name USING GBK) asc';
		} elseif ($_best == 'rate desc') {
			$criteria->select = '*,`DecorationRating`*0.3+`SoundRating`*0.15+`ServiceRating`*0.15+`ConsumerRating`*0.2+`FoodRating`*0.2 as arate';
			$criteria->order = 'arate desc,CONVERT(name USING GBK) asc';
		} else {
			$criteria->order = $_best . ',CONVERT(name USING GBK) asc';
		}
		$criteria->limit = $_limit;
		$criteria->offset = $_offset;
		$criteria->addCondition("`status`=1");
		if ($type != '') {
			$criteria->addCondition("`type`='" . $type . "'");
		}
		if ($_ydzs == '1') {
			$criteria->addCondition("`ydzs`>'0'");
		}
		if ($_sjf == '1') {
			$criteria->addCondition("`sjf`>'0'");
		}
		if ($_sjq == '1') {
			$criteria->addCondition("`sjq`>'0'");
			$criteria->addCondition("`type`='2'");
		}
		if ($_taocan == '1') {
			$criteria->addCondition("`taocan`>'0'");
		}

		if (!empty($_code)) {
			$criteria->addCondition("`area_id`='$_code'");
		}

//		$_list = $this->findAllByAttributes(empty($_code) ? array() : array('area_id' => $_code), array('offset' => $_offset, 'limit' => $_limit, 'order' => $_best));
		$_list = $this->findAll($criteria);
		if (!empty($_list) && is_array($_list)) {
			return $this->foreachXktvList($_list);
		}
		return array();
	}

	public function getXktvcoodsList() {
		$_list = $this->findAll();
		if (!empty($_list) && is_array($_list)) {
			return $this->foreachXktvcoodList($_list);
		}
		return array();
	}

	public function searchXktvList($_name = '', $_address = '', $_telephone = '', $_responsetime = 0, $_rate = 0, $_offset = 0, $_limit = 100, $_xktvid = '') {
		$criteria = new CDbCriteria;
		$criteria->compare('name', $_name, TRUE);
		$criteria->compare('address', $_address, TRUE);
		$criteria->compare('telephone', $_telephone, TRUE);
		$criteria->offset = $_offset;
		$criteria->limit = $_limit;
		if ($_xktvid != null && !empty($_xktvid)) {
			$_xktvid .= ',cdata';
			$_xktvids = explode(',', $_xktvid);
			$criteria->addInCondition('xktvid', $_xktvids);
		}
		if ($_responsetime) {
			$criteria->order = 'responsetime DESC';
		}

		if ($_rate) {
			$criteria->order = 'rate DESC';
		}
		$criteria->addCondition("`status`=1");

		$_list = $this->findAll($criteria);
		if (!empty($_list) && is_array($_list)) {
			return $this->foreachXktvList($_list);
		}
	}

	public function searchXktvListByGPS($_name = '', $_lat = 0, $_lng = 0, $_offset = 0, $_limit = 100) {
		$range = 5000; // m
		// caculate distance of two gps point with mysql in (M)
		// m =   round(6378.138*2*asin(sqrt(pow(sin( ($_lat*pi()/180-lat*pi()/180)/2),2)+cos($_lat*pi()/180)*cos(lat*pi()/180)* pow(sin( ($_lng*pi()/180-lng*pi()/180)/2),2)))*1000)
		if (!empty($_name)) {
			$sql = "select *, round(6378.138*2*asin(sqrt(pow(sin( ($_lat*pi()/180-lat*pi()/180)/2),2)+cos($_lat*pi()/180)*cos(lat*pi()/180)* pow(sin( ($_lng*pi()/180-lng*pi()/180)/2),2)))*1000) AS juli from {{xktv}} where name like '%$_name%' AND round(6378.138*2*asin(sqrt(pow(sin( ($_lat*pi()/180-lat*pi()/180)/2),2)+cos($_lat*pi()/180)*cos(lat*pi()/180)* pow(sin( ($_lng*pi()/180-lng*pi()/180)/2),2)))*1000) <= $range order by juli ASC limit $_offset,$_limit";
		} else {
			$sql = "select *, round(6378.138*2*asin(sqrt(pow(sin( ($_lat*pi()/180-lat*pi()/180)/2),2)+cos($_lat*pi()/180)*cos(lat*pi()/180)* pow(sin( ($_lng*pi()/180-lng*pi()/180)/2),2)))*1000) AS juli from {{xktv}} where round(6378.138*2*asin(sqrt(pow(sin( ($_lat*pi()/180-lat*pi()/180)/2),2)+cos($_lat*pi()/180)*cos(lat*pi()/180)* pow(sin( ($_lng*pi()/180-lng*pi()/180)/2),2)))*1000) <= $range order by juli ASC limit $_offset,$_limit";
		}
		self::log($sql, 'trace');
		$_list = $this->findAllBySql($sql);
		if (!empty($_list) && is_array($_list)) {
			return $this->foreachXktvList($_list, $_lat, $_lng);
		}
	}

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

	/**
	 * get rad
	 * @param type $d
	 * @return type
	 */
	public function rad($d) {
		return $d * pi() / 180.0;
	}

	/**
	 * caculate the distance between two point
	 * @param type $lat1
	 * @param type $lng1
	 * @param type $lat2
	 * @param type $lng2
	 * @return type
	 */
	public function getDistance($lat1, $lng1, $lat2, $lng2) {
		$radLat1 = $this->rad($lat1);
		$radLat2 = $this->rad($lat2);
		$a = $radLat1 - $radLat2;
		$b = $this->rad($lng1) - $this->rad($lng2);

		$s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
		$s = $s * self::EARTH_RADIUS;
		//$s = round($s * 10000) / 10000;
		return $s;
	}

	public function getKtvidByid($id) {
		$info = $this->findByPk($id);
		// var_dump($info);die();
		return $info['xktvid'];
	}

	public function getDetail($xktvid) {
		$info = $this->findByAttributes(array('xktvid' => $xktvid));
		if ($info != null) {
			return $this->getKtvInfo($info);
		} else {
			return null;
		}

	}

}
