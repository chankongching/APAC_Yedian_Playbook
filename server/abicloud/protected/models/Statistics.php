<?php

/**
 * This is the model class for table "{{statistics}}".
 *
 * The followings are the available columns in table '{{statistics}}':
 * @property integer $id
 * @property integer $calltime
 * @property string $call_api
 * @property integer $call_id
 * @property string $call_ip
 * @property string $req_param
 * @property string $head_param
 */
class Statistics extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{statistics}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('calltime, call_id', 'numerical', 'integerOnly'=>true),
			array('call_api, call_ip', 'length', 'max'=>64),
			array('req_param, head_param', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, calltime, call_api, call_id, call_ip, req_param, head_param', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('setting', 'ID'),
			'calltime' => Yii::t('setting', 'Calltime'),
			'call_api' => Yii::t('setting', 'Call Api'),
			'call_id' => Yii::t('setting', 'Call ID'),
			'call_ip' => Yii::t('setting', 'Call Ip'),
			'req_param' => Yii::t('setting', 'Req Param'),
			'head_param' => Yii::t('setting', 'Head Param'),
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

		$criteria->compare('id',$this->id);
		$criteria->compare('calltime',$this->calltime);
		$criteria->compare('call_api',$this->call_api,true);
		$criteria->compare('call_id',$this->call_id);
		$criteria->compare('call_ip',$this->call_ip,true);
		$criteria->compare('req_param',$this->req_param,true);
		$criteria->compare('head_param',$this->head_param,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Statistics the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
