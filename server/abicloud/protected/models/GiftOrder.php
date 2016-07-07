<?php

/**
 * This is the model class for table "{{gift_order}}".
 *
 * The followings are the available columns in table '{{gift_order}}':
 * @property string $id
 * @property string $order_no
 * @property string $order_status
 * @property integer $userid
 * @property string $sellorder_belong
 * @property string $sellorder_datetime
 * @property string $sellorder_id
 * @property string $sellorder_receivecell
 * @property string $sellorder_remarks
 * @property string $sellordergoods_goodsid
 * @property string $sellordergoods_id
 * @property string $sellordergoods_mainpic
 * @property string $sellordergoods_name
 * @property integer $sellordergoods_num
 * @property string $sellordergoods_orderid
 * @property integer $is_tuikuan
 * @property integer $is_check
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property string $sellorder_belong_user
 * @property string $sellorder_custmodno
 * @property string $sellorder_custmdate
 * @property integer $sellorder_cost
 * @property string $sellorder_amount
 * @property string $sellorder_syscost
 * @property string $sellorder_sysamount
 * @property string $sellorder_actax
 * @property string $sellorder_freight
 * @property string $sellorder_insurance
 * @property string $sellorder_othercosts
 * @property string $sellorder_discount
 * @property string $sellorder_adjamount
 * @property string $sellorder_total
 * @property string $sellorder_cashdeduction
 * @property string $sellorder_pointdeduction
 * @property string $sellorder_masadeduction
 * @property string $sellorder_payamount
 * @property string $sellorder_receiver
 * @property string $sellorder_receivetel
 * @property string $sellorder_receiveprov
 * @property string $sellorder_receivecity
 * @property string $sellorder_receivecounty
 * @property string $sellorder_receiveaddr
 * @property string $sellorder_receivepost
 * @property string $sellorder_receiveemail
 * @property string $sellorder_deliverytime
 * @property string $sellorder_remindtime
 * @property string $sellorder_project
 * @property string $sellorder_itemdesc
 * @property string $sellorder_owner
 * @property string $sellorder_warename
 * @property integer $transorder_id
 */
class GiftOrder extends PSActiveRecord {
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{gift_order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid', 'required'),
			array('userid, sellordergoods_num, is_tuikuan, is_check, create_user_id, update_user_id, sellorder_cost, transorder_id', 'numerical', 'integerOnly' => true),
			array('order_no, sellorder_belong, sellorder_datetime, sellordergoods_name', 'length', 'max' => 30),
			array('order_status', 'length', 'max' => 10),
			array('sellorder_id, sellorder_receivecell, sellorder_remarks, sellordergoods_goodsid, sellordergoods_id, sellordergoods_orderid', 'length', 'max' => 20),
			array('sellordergoods_mainpic', 'length', 'max' => 100),
			array('sellorder_belong_user, sellorder_custmodno, sellorder_custmdate, sellorder_amount, sellorder_syscost, sellorder_sysamount, sellorder_actax, sellorder_freight, sellorder_insurance, sellorder_othercosts, sellorder_discount, sellorder_adjamount, sellorder_total, sellorder_cashdeduction, sellorder_pointdeduction, sellorder_masadeduction, sellorder_payamount, sellorder_receiver, sellorder_receivetel, sellorder_receiveprov, sellorder_receivecity, sellorder_receivecounty, sellorder_receiveaddr, sellorder_receivepost, sellorder_receiveemail, sellorder_deliverytime, sellorder_remindtime, sellorder_project, sellorder_itemdesc, sellorder_owner, sellorder_warename', 'length', 'max' => 255),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_no, order_status, userid, sellorder_belong, sellorder_datetime, sellorder_id, sellorder_receivecell, sellorder_remarks, sellordergoods_goodsid, sellordergoods_id, sellordergoods_mainpic, sellordergoods_name, sellordergoods_num, sellordergoods_orderid, is_tuikuan, is_check, create_time, create_user_id, update_time, update_user_id, sellorder_belong_user, sellorder_custmodno, sellorder_custmdate, sellorder_cost, sellorder_amount, sellorder_syscost, sellorder_sysamount, sellorder_actax, sellorder_freight, sellorder_insurance, sellorder_othercosts, sellorder_discount, sellorder_adjamount, sellorder_total, sellorder_cashdeduction, sellorder_pointdeduction, sellorder_masadeduction, sellorder_payamount, sellorder_receiver, sellorder_receivetel, sellorder_receiveprov, sellorder_receivecity, sellorder_receivecounty, sellorder_receiveaddr, sellorder_receivepost, sellorder_receiveemail, sellorder_deliverytime, sellorder_remindtime, sellorder_project, sellorder_itemdesc, sellorder_owner, sellorder_warename, transorder_id', 'safe', 'on' => 'search'),
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
			'order_no' => 'Order No',
			'order_status' => 'Order Status',
			'userid' => 'Userid',
			'sellorder_belong' => 'Sellorder Belong',
			'sellorder_datetime' => 'Sellorder Datetime',
			'sellorder_id' => 'Sellorder',
			'sellorder_receivecell' => 'Sellorder Receivecell',
			'sellorder_remarks' => 'Sellorder Remarks',
			'sellordergoods_goodsid' => 'Sellordergoods Goodsid',
			'sellordergoods_id' => 'Sellordergoods',
			'sellordergoods_mainpic' => 'Sellordergoods Mainpic',
			'sellordergoods_name' => 'Sellordergoods Name',
			'sellordergoods_num' => 'Sellordergoods Num',
			'sellordergoods_orderid' => 'Sellordergoods Orderid',
			'is_tuikuan' => 'Is Tuikuan',
			'is_check' => 'Is Check',
			'create_time' => 'Create Time',
			'create_user_id' => 'Create User',
			'update_time' => 'Update Time',
			'update_user_id' => 'Update User',
			'sellorder_belong_user' => 'Sellorder Belong User',
			'sellorder_custmodno' => 'Sellorder Custmodno',
			'sellorder_custmdate' => 'Sellorder Custmdate',
			'sellorder_cost' => 'Sellorder Cost',
			'sellorder_amount' => 'Sellorder Amount',
			'sellorder_syscost' => 'Sellorder Syscost',
			'sellorder_sysamount' => 'Sellorder Sysamount',
			'sellorder_actax' => 'Sellorder Actax',
			'sellorder_freight' => 'Sellorder Freight',
			'sellorder_insurance' => 'Sellorder Insurance',
			'sellorder_othercosts' => 'Sellorder Othercosts',
			'sellorder_discount' => 'Sellorder Discount',
			'sellorder_adjamount' => 'Sellorder Adjamount',
			'sellorder_total' => 'Sellorder Total',
			'sellorder_cashdeduction' => 'Sellorder Cashdeduction',
			'sellorder_pointdeduction' => 'Sellorder Pointdeduction',
			'sellorder_masadeduction' => 'Sellorder Masadeduction',
			'sellorder_payamount' => 'Sellorder Payamount',
			'sellorder_receiver' => 'Sellorder Receiver',
			'sellorder_receivetel' => 'Sellorder Receivetel',
			'sellorder_receiveprov' => 'Sellorder Receiveprov',
			'sellorder_receivecity' => 'Sellorder Receivecity',
			'sellorder_receivecounty' => 'Sellorder Receivecounty',
			'sellorder_receiveaddr' => 'Sellorder Receiveaddr',
			'sellorder_receivepost' => 'Sellorder Receivepost',
			'sellorder_receiveemail' => 'Sellorder Receiveemail',
			'sellorder_deliverytime' => 'Sellorder Deliverytime',
			'sellorder_remindtime' => 'Sellorder Remindtime',
			'sellorder_project' => 'Sellorder Project',
			'sellorder_itemdesc' => 'Sellorder Itemdesc',
			'sellorder_owner' => 'Sellorder Owner',
			'sellorder_warename' => 'Sellorder Warename',
			'transorder_id' => 'Transorder',
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
		$criteria->compare('order_no', $this->order_no, true);
		$criteria->compare('order_status', $this->order_status, true);
		$criteria->compare('userid', $this->userid);
		$criteria->compare('sellorder_belong', $this->sellorder_belong, true);
		$criteria->compare('sellorder_datetime', $this->sellorder_datetime, true);
		$criteria->compare('sellorder_id', $this->sellorder_id, true);
		$criteria->compare('sellorder_receivecell', $this->sellorder_receivecell, true);
		$criteria->compare('sellorder_remarks', $this->sellorder_remarks, true);
		$criteria->compare('sellordergoods_goodsid', $this->sellordergoods_goodsid, true);
		$criteria->compare('sellordergoods_id', $this->sellordergoods_id, true);
		$criteria->compare('sellordergoods_mainpic', $this->sellordergoods_mainpic, true);
		$criteria->compare('sellordergoods_name', $this->sellordergoods_name, true);
		$criteria->compare('sellordergoods_num', $this->sellordergoods_num);
		$criteria->compare('sellordergoods_orderid', $this->sellordergoods_orderid, true);
		$criteria->compare('is_tuikuan', $this->is_tuikuan);
		$criteria->compare('is_check', $this->is_check);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('create_user_id', $this->create_user_id);
		$criteria->compare('update_time', $this->update_time, true);
		$criteria->compare('update_user_id', $this->update_user_id);
		$criteria->compare('sellorder_belong_user', $this->sellorder_belong_user, true);
		$criteria->compare('sellorder_custmodno', $this->sellorder_custmodno, true);
		$criteria->compare('sellorder_custmdate', $this->sellorder_custmdate, true);
		$criteria->compare('sellorder_cost', $this->sellorder_cost);
		$criteria->compare('sellorder_amount', $this->sellorder_amount, true);
		$criteria->compare('sellorder_syscost', $this->sellorder_syscost, true);
		$criteria->compare('sellorder_sysamount', $this->sellorder_sysamount, true);
		$criteria->compare('sellorder_actax', $this->sellorder_actax, true);
		$criteria->compare('sellorder_freight', $this->sellorder_freight, true);
		$criteria->compare('sellorder_insurance', $this->sellorder_insurance, true);
		$criteria->compare('sellorder_othercosts', $this->sellorder_othercosts, true);
		$criteria->compare('sellorder_discount', $this->sellorder_discount, true);
		$criteria->compare('sellorder_adjamount', $this->sellorder_adjamount, true);
		$criteria->compare('sellorder_total', $this->sellorder_total, true);
		$criteria->compare('sellorder_cashdeduction', $this->sellorder_cashdeduction, true);
		$criteria->compare('sellorder_pointdeduction', $this->sellorder_pointdeduction, true);
		$criteria->compare('sellorder_masadeduction', $this->sellorder_masadeduction, true);
		$criteria->compare('sellorder_payamount', $this->sellorder_payamount, true);
		$criteria->compare('sellorder_receiver', $this->sellorder_receiver, true);
		$criteria->compare('sellorder_receivetel', $this->sellorder_receivetel, true);
		$criteria->compare('sellorder_receiveprov', $this->sellorder_receiveprov, true);
		$criteria->compare('sellorder_receivecity', $this->sellorder_receivecity, true);
		$criteria->compare('sellorder_receivecounty', $this->sellorder_receivecounty, true);
		$criteria->compare('sellorder_receiveaddr', $this->sellorder_receiveaddr, true);
		$criteria->compare('sellorder_receivepost', $this->sellorder_receivepost, true);
		$criteria->compare('sellorder_receiveemail', $this->sellorder_receiveemail, true);
		$criteria->compare('sellorder_deliverytime', $this->sellorder_deliverytime, true);
		$criteria->compare('sellorder_remindtime', $this->sellorder_remindtime, true);
		$criteria->compare('sellorder_project', $this->sellorder_project, true);
		$criteria->compare('sellorder_itemdesc', $this->sellorder_itemdesc, true);
		$criteria->compare('sellorder_owner', $this->sellorder_owner, true);
		$criteria->compare('sellorder_warename', $this->sellorder_warename, true);
		$criteria->compare('transorder_id', $this->transorder_id);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GiftOrder the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function getOrderList($_userid, $_offset, $_limit) {
		$_list = $this->findAllByAttributes(array('userid' => $_userid), array('offset' => $_offset, 'limit' => $_limit, 'order' => 'update_time desc'));
		if (!empty($_list) && is_array($_list)) {
			return $this->foreachOrderList($_list);
		}
		return array();
	}

	public function foreachOrderList($_list = array()) {
		$_order_list = array();
		foreach ($_list as $key => $order) {
			$_order_list[] = $this->getOrderInfo($order);
		}
		return $_order_list;
	}

	public function getOrderInfo($order) {
		$_order_info = array(
			'order_no' => $order["order_no"],
			'order_status' => $order["order_status"],
			'userid' => $order['userid'],
			'sellorder_belong' => $order['sellorder_belong'],
			'sellorder_datetime' => strtotime($order['sellorder_datetime']),
			'sellorder_id' => $order['sellorder_id'],
			'sellorder_receivecell' => $order['sellorder_receivecell'],
			'sellorder_remarks' => $order['sellorder_remarks'],
//            "sellorder_belong"=>$order["sellorder_belong"],
			//            "sellorder_id"=>$order["sellorder_id"],
			//            "sellorder_no"=>$order["sellorder_no"],
			"sellorder_custmodno" => $order["sellorder_custmodno"],
			"sellorder_custmdate" => $order["sellorder_custmdate"],
//            "sellorder_datetime"=>$order["sellorder_datetime"],
			//            "order_status"=>$order["order_status"],
			"sellorder_cost" => $order["sellorder_cost"],
			"sellorder_amount" => $order["sellorder_amount"],
			"sellorder_syscost" => $order["sellorder_syscost"],
			"sellorder_sysamount" => $order["sellorder_sysamount"],
			"sellorder_actax" => $order["sellorder_actax"],
			"sellorder_freight" => $order["sellorder_freight"],
			"sellorder_insurance" => $order["sellorder_insurance"],
			"sellorder_othercosts" => $order["sellorder_othercosts"],
			"sellorder_discount" => $order["sellorder_discount"],
			"sellorder_adjamount" => $order["sellorder_adjamount"],
			"sellorder_total" => $order["sellorder_total"],
			"sellorder_cashdeduction" => $order["sellorder_cashdeduction"],
			"sellorder_pointdeduction" => $order["sellorder_pointdeduction"],
			"sellorder_masadeduction" => $order["sellorder_masadeduction"],
			"sellorder_payamount" => $order["sellorder_payamount"],
			"sellorder_receiver" => $order["sellorder_receiver"],
			"sellorder_receivetel" => $order["sellorder_receivetel"],
//            "sellorder_receivecell"=>$order["sellorder_receivecell"],
			"sellorder_receiveprov" => $order["sellorder_receiveprov"],
			"sellorder_receivecity" => $order["sellorder_receivecity"],
			"sellorder_receivecounty" => $order["sellorder_receivecounty"],
			"sellorder_receiveaddr" => $order["sellorder_receiveaddr"],
			"sellorder_receivepost" => $order["sellorder_receivepost"],
			"sellorder_receiveemail" => $order["sellorder_receiveemail"],
//            "sellorder_remarks"=>$order["sellorder_remarks"],
			"sellorder_deliverytime" => $order["sellorder_deliverytime"],
			"sellorder_remindtime" => $order["sellorder_remindtime"],
			"sellorder_project" => $order["sellorder_project"],
			"sellorder_itemdesc" => $order["sellorder_itemdesc"],
			"sellorder_owner" => $order["sellorder_owner"],
			"sellorder_warename" => $order["sellorder_warename"],
			"transorder_id" => $order["transorder_id"],
			'sellordergoods_goodsid' => $order['sellordergoods_goodsid'],
			'sellordergoods_id' => $order['sellordergoods_id'],
			'sellordergoods_mainpic' => $order['sellordergoods_mainpic'],
			'sellordergoods_name' => $order['sellordergoods_name'],
			'sellordergoods_num' => $order['sellordergoods_num'],
			'sellordergoods_orderid' => $order['sellordergoods_orderid'],
			'is_tuikuan' => $order['is_tuikuan'],
			'is_check' => $order['is_check'],
		);
		return $_order_info;
	}

	public function getOrderInfoByid($orderid, $_userid) {
		$order = $this->findByAttributes(array('order_no' => $orderid, 'userid' => $_userid));
		if ($order == null) {
			return null;
		}
		return $this->getOrderInfo($order);
	}

	public function getGiftOrderCount($user_id) {
		if (empty($user_id)) {
			return 0;
		}

		$criteria = new CDbCriteria;
		$criteria->compare('userid', $user_id);
//        $criteria->compare('deleted', 0);
		//$criteria->addInCondition('status', array(1, 17, 18, 19, 20, 5, 3));

		return $this->count($criteria);
	}

	public function updateOrder($data) {
		if ($data == null) {
			return false;
		}
		$giftorder = $this->findByAttributes(array('order_no' => $data['order_no']));
		if ($giftorder == null) {
			$giftorder = new GiftOrder();
			$giftorder->model()->setAttributes($data);
			if ($giftorder->save()) {
				return true;
			}
		}
		$giftorder->setAttributes($data);
		if ($giftorder->save()) {
			return true;
		}
	}
}