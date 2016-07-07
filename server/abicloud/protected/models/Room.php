<?php

/**
 * This is the model class for table "{{room}}".
 *
 * The followings are the available columns in table '{{room}}':
 * @property integer $id
 * @property string $roomid
 * @property integer $vendor_id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property integer $room_type
 * @property string $price
 * @property string $content
 * @property string $smallpic_url
 * @property string $bigpic_url
 * @property string $smallpic_filename
 * @property string $bigpic_filename
 * 
 * The followings are the available model relations:
 * @property CheckinCode[] $checkinCodes
 * @property CheckinState[] $checkinStates
 * @property CheckinUser[] $checkinUsers
 * @property DeviceState[] $deviceStates
 * @property Vendor $vendor
 * @property RoomBooking $roomBooking
 * @property RoomExtra $roomExtra */
class Room extends PSActiveRecord
{
	public $bigfile;
	public $smallfile;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{room}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('roomid', 'required'),
			array('vendor_id, status, create_user_id, update_user_id, room_type', 'numerical', 'integerOnly'=>true),
			array('roomid', 'length', 'max'=>32),
			array('name, description, smallpic_url, bigpic_url, smallpic_filename, bigpic_filename', 'length', 'max'=>255),
			array('create_time, update_time, content', 'safe'),
			array('price', 'length', 'max'=>10),                    
                        array('bigfile', 'file', 'types' => 'jpg,png,gif,jpeg,bmp', 'maxSize' => 1048576 * 10, 'safe' => true, 'allowEmpty' => true),
                        array('smallfile', 'file', 'types' => 'jpg,png,gif,jpeg,bmp', 'maxSize' => 1048576 * 10, 'safe' => true, 'allowEmpty' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, roomid, vendor_id, name, description, status, create_time, create_user_id, update_time, update_user_id, room_type, price, content, smallpic_url, bigpic_url, smallpic_filename, bigpic_filename', 'safe', 'on'=>'search'),
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
			'checkinCodes' => array(self::HAS_MANY, 'CheckinCode', 'room_id'),
			'checkinStates' => array(self::HAS_MANY, 'CheckinState', 'room_id'),
			'checkinUsers' => array(self::HAS_MANY, 'CheckinUser', 'room_id'),
			'deviceStates' => array(self::HAS_MANY, 'DeviceState', 'room_id'),
			'vendor' => array(self::BELONGS_TO, 'Vendor', 'vendor_id'),
			'roomBooking' => array(self::HAS_ONE, 'RoomBooking', 'room_id'),
			'roomExtra' => array(self::HAS_ONE, 'RoomExtra', 'room_id'),                    
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('Room','ID'),
			'roomid' => Yii::t('Room','Roomid'),
			'vendor_id' => Yii::t('Room','Vendor'),
			'name' => Yii::t('Room','Name'),
			'description' => Yii::t('Room','Description'),
			'status' => Yii::t('Room','Status'),
			'create_time' => Yii::t('Room','Create Time'),
			'create_user_id' => Yii::t('Room','Create User'),
			'update_time' => Yii::t('Room','Update Time'),
			'update_user_id' => Yii::t('Room','Update User'),
			'room_type' => Yii::t('Room','Room Type'),
			'price' => Yii::t('Room','Price/Hour'),
			'content' => Yii::t('Room','Content'),
			'smallpic_url' => Yii::t('Room','Smallpic Url'),
			'bigpic_url' => Yii::t('Room','Bigpic Url'),
			'smallpic_filename' => Yii::t('Room','Smallpic Filename'),
			'bigpic_filename' => Yii::t('Room','Bigpic Filename'),                    
                        'bigfile' => Yii::t('files', 'Select Big Picture'),
                        'smallfile' => Yii::t('files', 'Select Small Picture'),
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
		$criteria->compare('roomid',$this->roomid,true);
		$criteria->compare('vendor_id',$this->vendor_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('room_type',$this->room_type);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('smallpic_url',$this->smallpic_url,true);
		$criteria->compare('bigpic_url',$this->bigpic_url,true);
		$criteria->compare('smallpic_filename',$this->smallpic_filename,true);
		$criteria->compare('bigpic_filename',$this->bigpic_filename,true);
                

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Room the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
    public function countNew() {
        $pre3days = time() - 3600 * 24 * 7;
        $count = Room::model()->count('create_time > :pre3days', array(':pre3days' => date('Y-m-d H:i:s', $pre3days)));
        return $count;
    }
    
    public function getStatusName() {
        if(!empty($this->checkinStates)) {
            return Yii::t('site','In Using');
        }
        elseif(!empty($this->checkinCodes)) {
            $checkincode = $this->checkinCodes[0];
            if(!empty($checkincode) && (empty($checkincode->expire) || $checkincode->expire > time())) {
                return Yii::t('site','Waiting');
            }
            else {
                return Yii::t('site','Expired');
            }
        }
        
        return Yii::t('site','Closed');
    }
    
    public function getRoomOptions() {
        $listData = array();
        $rooms = $this->findAll();
        foreach ($rooms as $key => $room) {
            $listData[$room->id] = $room->name;
        }
        return $listData;
    }
    
    public function getRoomTypeName($type) {
        $room_type = array(1=>Yii::t('Room','Large Room'),2=>Yii::t('Room','Medium Room'),3=>Yii::t('Room','Normal Room'));
        if(isset($room_type[$type])) {
            return $room_type[$type];
        }
        return '';
    }
    public function getRoomTypeOptions() {
        $room_type = array(1=>Yii::t('Room','Large Room'),2=>Yii::t('Room','Medium Room'),3=>Yii::t('Room','Normal Room'));
        return $room_type;
    }
}
