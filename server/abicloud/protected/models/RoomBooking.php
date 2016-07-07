<?php

/**
 * This is the model class for table "{{room_booking}}".
 *
 * The followings are the available columns in table '{{room_booking}}':
 * @property integer $id
 * @property integer $status
 * @property string $invoice
 * @property string $code
 * @property float $amount
 * @property long $time
 * @property long $confirm_time
 * @property integer $roomtype
 * @property integer $roomid
 * @property string $ktvid
 * @property integer $userid
 * @property string $name
 * @property string $description
 * @property string $smallpicurl
 * @property string $bigpicurl
 * @property integer $starttime
 * @property integer $endtime
 * @property integer $members
 * @property string $reason
 * @property string $content
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property PlatformUser $user
 * @property Room $room
 */
class RoomBooking extends PSActiveRecord {

	public $display_name = '';
	public $mobile = '';

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{order}}';
	}

	public function afterFind() {
		parent::afterFind();
		$user = PlatformUser::model()->findByPk($this->userid);
		if (!is_null($user) && !empty($user)) {
			$this->display_name = $user->display_name;
			$this->mobile = $user->mobile;
		}
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invoice, code', 'required'),
			array('roomid, userid, status, starttime,endtime, members,  time, confirm_time, create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
			array('invoice, code, ktvid', 'length', 'max' => 32),
			array('amount', 'length', 'max' => 10),
			array('description, reason, content, bigpicurl, smallpicurl, name', 'length', 'max' => 255),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, status, invoice, code, amount, time, confirm_time, roomtype, roomid, ktvid, userid, name, description, smallpicurl, bigpicurl, starttime, endtime, members, reason, content, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'PlatformUser', 'user_id'),
			'room' => array(self::BELONGS_TO, 'Room', 'room_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => Yii::t('RoomBooking', 'ID'),
			'status' => Yii::t('RoomBooking', 'Status'),
			'invoice' => Yii::t('RoomBooking', 'Order Invoice'),
			'code' => Yii::t('RoomBooking', 'Order Code'),
			'amount' => Yii::t('RoomBooking', 'Order Amount'),
			'time' => Yii::t('RoomBooking', 'Booking Time'),
			'confirm_time' => Yii::t('RoomBooking', 'Confirm Time'),
			'roomtype' => Yii::t('RoomBooking', 'Room Type'),
			'roomid' => Yii::t('RoomBooking', 'Room'),
			'ktvid' => Yii::t('RoomBooking', 'Xktv'),
			'userid' => Yii::t('RoomBooking', 'User'),
			'name' => Yii::t('RoomBooking', 'Room Name'),
			'description' => Yii::t('RoomBooking', 'Description'),
			'smallpicurl' => Yii::t('RoomBooking', 'Small Pic Url'),
			'bigpicurl' => Yii::t('RoomBooking', 'Big Pic Url'),
			'starttime' => Yii::t('RoomBooking', 'Booking Start TIme'),
			'endtime' => Yii::t('RoomBooking', 'Booking End TIme'),
			'members' => Yii::t('RoomBooking', 'Room members'),
			'reason' => Yii::t('RoomBooking', 'Reject Reason'),
			'content' => Yii::t('RoomBooking', 'Detail Content'),
			'create_time' => Yii::t('RoomBooking', 'Create Time'),
			'create_user_id' => Yii::t('RoomBooking', 'Create User'),
			'update_time' => Yii::t('RoomBooking', 'Update Time'),
			'update_user_id' => Yii::t('RoomBooking', 'Update User'),
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
		$criteria->compare('status', $this->status);
		$criteria->compare('invoice', $this->invoice, true);
		$criteria->compare('code', $this->code, true);
		$criteria->compare('amount', $this->amount);
		$criteria->compare('time', $this->time);
		$criteria->compare('confirm_time', $this->confirm_time);
		$criteria->compare('roomtype', $this->roomtype);
		$criteria->compare('roomid', $this->roomid);
		$criteria->compare('ktvid', $this->ktvid);
		$criteria->compare('userid', $this->userid);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('smallpicurl', $this->smallpicurl, true);
		$criteria->compare('bigpicurl', $this->bigpicurl, true);
		$criteria->compare('starttime', $this->starttime);
		$criteria->compare('endtime', $this->endtime);
		$criteria->compare('members', $this->members);
		$criteria->compare('reason', $this->reason, true);
		$criteria->compare('content', $this->content, true);
		$criteria->compare('create_time', $this->create_time);
		$criteria->compare('create_user_id', $this->create_user_id);
		$criteria->compare('update_time', $this->update_time);
		$criteria->compare('update_user_id', $this->update_user_id);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RoomBooking the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function getOrderStatusName($status) {
		$status_array = array(
			1 => Yii::t('RoomBooking', 'Pending'),
			17 => Yii::t('RoomBooking', 'Waiting Cash'),
			18 => Yii::t('RoomBooking', 'Paid by Wechat'),
			19 => Yii::t('RoomBooking', 'Paid by Alipay'),
			20 => Yii::t('RoomBooking', 'Paid by Cash'),
			4 => Yii::t('RoomBooking', 'Rejected'),
			5 => Yii::t('RoomBooking', 'Complete'),
			7 => Yii::t('RoomBooking', 'Canceled'),
			3 => Yii::t('RoomBooking', 'Confirmed'),
			14 => Yii::t('RoomBooking', 'Expired'),
		);
		return (isset($status_array[$status]) ? $status_array[$status] : Yii::t('RoomBooking', 'unknown'));
	}

	public function getUserOrderCount($user_id) {
		if (empty($user_id)) {
			return 0;
		}

		$criteria = new CDbCriteria;
		$criteria->compare('userid', $user_id);
		$criteria->compare('deleted', 0);
		//$criteria->addInCondition('status', array(1, 17, 18, 19, 20, 5, 3));

		return $this->count($criteria);
	}

	public function checkExpire($userid) {
		$criteria = new CDbCriteria;
		$criteria->addInCondition('status', array(3, 1));
		$criteria->addCondition('starttime<' . (time() - 3600));
		$criteria->addCondition('userid=' . $userid);
		$_list = $this->findAll($criteria);
		foreach ($_list as $key => $value) {
			$order = $this->findByPk($value['id']);
			$order->status = 7;
			$order->save();
		}
	}
	public function getOrdersbyUid($userid, $ktvid) {
		$criteria = new CDbCriteria;
		$criteria->addCondition('userid=' . $userid);
		$criteria->addCondition('ktvid="' . $this->getKtvidByid($ktvid) . '"');
		$_list = $this->findAll($criteria);
		$_new_list = array();
		foreach ($_list as $key => $value) {
			$_new_list[] = $value['id'];
		}
		return $_new_list;
	}

	public function getStatus($_userid, $order_code) {
		$orderinfo = $this->findByAttributes(array('userid' => $_userid, 'code' => $order_code));
		if ($orderinfo != null) {
			return $orderinfo['status'];
		} else {
			return null;
		}
	}

	protected function getKtvidByid($ktvid) {
		return Xktv::model()->getKtvidByid($ktvid);
	}

	public function checkExpiredOrders($userid) {
		$criteria = new CDbCriteria;
		$criteria->addInCondition('status', array(3, 1));
		$criteria->addCondition('starttime<' . (time() - 3600));
		$criteria->addCondition('userid=' . $userid);
		$_list = $this->findAll($criteria);
		foreach ($_list as $key => $value) {
			$order = $this->findByPk($value['id']);
			if ($order->couponid > 0) {
				if (Coupon::model()->tuihuan($order->couponid)) {

				}
			}
			$order->status = 14;
			$order->save();
		}

	}

}
