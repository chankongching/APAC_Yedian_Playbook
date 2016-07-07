<?php

/**
 * This is the model class for table "{{room_extra}}".
 *
 * The followings are the available columns in table '{{room_extra}}':
 * @property integer $id
 * @property integer $room_id
 * @property integer $room_type
 * @property string $price
 * @property string $description
 * @property string $smallpic_url
 * @property string $bigpic_url
 * @property string $smallpic_filename
 * @property string $bigpic_filename
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property Room $room
 */
class RoomExtra extends PSActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{room_extra}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('room_id, room_type, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('price', 'length', 'max'=>10),
			array('description, smallpic_url, bigpic_url, smallpic_filename, bigpic_filename', 'length', 'max'=>255),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, room_id, room_type, price, description, smallpic_url, bigpic_url, smallpic_filename, bigpic_filename, create_time, create_user_id, update_time, update_user_id', 'safe', 'on'=>'search'),
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
			'room' => array(self::BELONGS_TO, 'Room', 'room_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'room_id' => 'Room',
			'room_type' => 'Room Type',
			'price' => 'Price',
			'description' => 'Description',
			'smallpic_url' => 'Smallpic Url',
			'bigpic_url' => 'Bigpic Url',
			'smallpic_filename' => 'Smallpic Filename',
			'bigpic_filename' => 'Bigpic Filename',
			'create_time' => 'Create Time',
			'create_user_id' => 'Create User',
			'update_time' => 'Update Time',
			'update_user_id' => 'Update User',
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
		$criteria->compare('room_id',$this->room_id);
		$criteria->compare('room_type',$this->room_type);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('smallpic_url',$this->smallpic_url,true);
		$criteria->compare('bigpic_url',$this->bigpic_url,true);
		$criteria->compare('smallpic_filename',$this->smallpic_filename,true);
		$criteria->compare('bigpic_filename',$this->bigpic_filename,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RoomExtra the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
