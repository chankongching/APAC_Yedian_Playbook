<?php

/**
 * This is the model class for table "{{wxpaynotify}}".
 *
 * The followings are the available columns in table '{{wxpaynotify}}':
 * @property string $id
 * @property string $appid
 * @property string $attach
 * @property string $bank_type
 * @property integer $cash_fee
 * @property string $cash_fee_type
 * @property integer $coupon_fee
 * @property integer $coupon_count
 * @property string $fee_type
 * @property string $is_subscribe
 * @property string $mch_id
 * @property string $nonce_str
 * @property string $openid
 * @property string $out_trade_no
 * @property string $result_code
 * @property string $device_info
 * @property string $return_code
 * @property string $err_code
 * @property string $err_code_des
 * @property string $return_msg
 * @property string $sign
 * @property integer $settlement_total_fee
 * @property string $time_end
 * @property integer $total_fee
 * @property string $trade_state
 * @property string $trade_type
 * @property string $transaction_id
 * @property integer $create_user_id
 * @property string $create_time
 * @property integer $update_user_id
 * @property string $update_time
 */
class Wxpaynotify extends PSActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{wxpaynotify}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cash_fee, coupon_fee, coupon_count, settlement_total_fee, total_fee, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('appid, mch_id, nonce_str, out_trade_no, device_info, err_code, sign, transaction_id', 'length', 'max'=>32),
			array('attach, openid, err_code_des, return_msg', 'length', 'max'=>128),
			array('bank_type, cash_fee_type, return_code, trade_type', 'length', 'max'=>16),
			array('fee_type', 'length', 'max'=>8),
			array('is_subscribe', 'length', 'max'=>1),
			array('result_code', 'length', 'max'=>50),
			array('time_end', 'length', 'max'=>14),
			array('trade_state', 'length', 'max'=>30),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, appid, attach, bank_type, cash_fee, cash_fee_type, coupon_fee, coupon_count, fee_type, is_subscribe, mch_id, nonce_str, openid, out_trade_no, result_code, device_info, return_code, err_code, err_code_des, return_msg, sign, settlement_total_fee, time_end, total_fee, trade_state, trade_type, transaction_id, create_user_id, create_time, update_user_id, update_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'appid' => 'Appid',
			'attach' => 'Attach',
			'bank_type' => 'Bank Type',
			'cash_fee' => 'Cash Fee',
			'cash_fee_type' => 'Cash Fee Type',
			'coupon_fee' => 'Coupon Fee',
			'coupon_count' => 'Coupon Count',
			'fee_type' => 'Fee Type',
			'is_subscribe' => 'Is Subscribe',
			'mch_id' => 'Mch',
			'nonce_str' => 'Nonce Str',
			'openid' => 'Openid',
			'out_trade_no' => 'Out Trade No',
			'result_code' => 'Result Code',
			'device_info' => 'Device Info',
			'return_code' => 'Return Code',
			'err_code' => 'Err Code',
			'err_code_des' => 'Err Code Des',
			'return_msg' => 'Return Msg',
			'sign' => 'Sign',
			'settlement_total_fee' => 'Settlement Total Fee',
			'time_end' => 'Time End',
			'total_fee' => 'Total Fee',
			'trade_state' => 'Trade State',
			'trade_type' => 'Trade Type',
			'transaction_id' => 'Transaction',
			'create_user_id' => 'Create User',
			'create_time' => 'Create Time',
			'update_user_id' => 'Update User',
			'update_time' => 'Update Time',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('appid',$this->appid,true);
		$criteria->compare('attach',$this->attach,true);
		$criteria->compare('bank_type',$this->bank_type,true);
		$criteria->compare('cash_fee',$this->cash_fee);
		$criteria->compare('cash_fee_type',$this->cash_fee_type,true);
		$criteria->compare('coupon_fee',$this->coupon_fee);
		$criteria->compare('coupon_count',$this->coupon_count);
		$criteria->compare('fee_type',$this->fee_type,true);
		$criteria->compare('is_subscribe',$this->is_subscribe,true);
		$criteria->compare('mch_id',$this->mch_id,true);
		$criteria->compare('nonce_str',$this->nonce_str,true);
		$criteria->compare('openid',$this->openid,true);
		$criteria->compare('out_trade_no',$this->out_trade_no,true);
		$criteria->compare('result_code',$this->result_code,true);
		$criteria->compare('device_info',$this->device_info,true);
		$criteria->compare('return_code',$this->return_code,true);
		$criteria->compare('err_code',$this->err_code,true);
		$criteria->compare('err_code_des',$this->err_code_des,true);
		$criteria->compare('return_msg',$this->return_msg,true);
		$criteria->compare('sign',$this->sign,true);
		$criteria->compare('settlement_total_fee',$this->settlement_total_fee);
		$criteria->compare('time_end',$this->time_end,true);
		$criteria->compare('total_fee',$this->total_fee);
		$criteria->compare('trade_state',$this->trade_state,true);
		$criteria->compare('trade_type',$this->trade_type,true);
		$criteria->compare('transaction_id',$this->transaction_id,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Wxpaynotify the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
