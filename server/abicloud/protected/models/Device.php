<?php

/**
 * This is the model class for table "{{device}}".
 *
 * The followings are the available columns in table '{{device}}':
 * @property integer $id
 * @property string $name
 * @property string $imei
 * @property string $ip
 * @property string $description
 * @property integer $type
 * @property integer $status
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property DevicePlaylist[] $devicePlaylists
 * @property DeviceState[] $deviceStates
 */
class Device extends PSActiveRecord
{
    public $room_id;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{device}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, imei', 'required'),
			array('type, status, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('name, description', 'length', 'max'=>255),
			array('imei', 'length', 'max'=>32),
			array('ip', 'length', 'max'=>64),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, imei, ip, description, type, status, create_time, create_user_id, update_time, update_user_id, room_id', 'safe', 'on'=>'search'),
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
			'devicePlaylists' => array(self::HAS_MANY, 'DevicePlaylist', 'device_id'),
			'deviceStates' => array(self::HAS_MANY, 'DeviceState', 'device_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('Device','ID'),
			'name' => Yii::t('Device','Name'),
			'imei' => Yii::t('Device','Imei'),
			'ip' => Yii::t('Device','Ip'),
			'description' => Yii::t('Device','Description'),
			'type' => Yii::t('Device','Type'),
			'status' => Yii::t('Device','Status'),
                        'room_id' => Yii::t('RoomBooking','Room'),
			'create_time' => Yii::t('Device','Create Time'),
			'create_user_id' => Yii::t('Device','Create User'),
			'update_time' => Yii::t('Device','Update Time'),
			'update_user_id' => Yii::t('Device','Update User'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('imei',$this->imei,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('status',$this->status);
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
	 * @return Device the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getTypeName($type) {
            if($type == 1) {
                return Yii::t('site', 'Set Top Box');
            }
            if($type == 2) {
                return Yii::t('site', 'Table Touchpad');
            }
            return Yii::t('site', 'Unknown');
        }
        
        public function getTypeOptions() {
            $device_type = array(1=>Yii::t('site', 'Set Top Box'),2=>Yii::t('site', 'Table Touchpad'));
            return $device_type;
        }
        
        public function getStatusName() {
            if(!empty($this->deviceStates)) {
                return Yii::t('site', 'Ready');
            }
            else {
                return Yii::t('site', 'Not Binded to Room');
            }
        }
        
        public function getRoomName() {
            if(!empty($this->deviceStates) && is_array($this->deviceStates)) {
                $_devicestate = $this->deviceStates[0];
                $_room = $_devicestate->room;
                if(!empty($_room)) {
                    return $_room->name;
                }
            }
            return '';
        }
}
