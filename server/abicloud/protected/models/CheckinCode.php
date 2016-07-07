<?php

/**
 * This is the model class for table "{{checkin_code}}".
 *
 * The followings are the available columns in table '{{checkin_code}}':
 * @property integer $id
 * @property string $code
 * @property integer $room_id
 * @property string $name
 * @property string $description
 * @property integer $duration
 * @property integer $expire
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property Room $room
 */
class CheckinCode extends PSActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{checkin_code}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, name', 'required'),
			array('room_id, duration, expire, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>32),
			array('name, description', 'length', 'max'=>255),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, room_id, name, description, duration, expire, create_time, create_user_id, update_time, update_user_id', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('CheckinCode','ID'),
			'code' => Yii::t('CheckinCode','Code'),
			'room_id' => Yii::t('CheckinCode','Room'),
			'name' => Yii::t('CheckinCode','Name'),
			'description' => Yii::t('CheckinCode','Description'),
			'duration' => Yii::t('CheckinCode','Duration'),
			'expire' => Yii::t('CheckinCode','Expire'),
			'create_time' => Yii::t('CheckinCode','Create Time'),
			'create_user_id' => Yii::t('CheckinCode','Create User'),
			'update_time' => Yii::t('CheckinCode','Update Time'),
			'update_user_id' => Yii::t('CheckinCode','Update User'),
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('room_id',$this->room_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('expire',$this->expire);
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
	 * @return CheckinCode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getCheckinUrl($qrcode) {
            $siteurl = Yii::app()->createAbsoluteUrl('/');
            $downloadurl = Yii::app()->createAbsoluteUrl('/') . '/site/download/';
            $checkinurl = Yii::app()->createAbsoluteUrl('/') . '/user/checkin/?cid=' . $qrcode;
            self::log('Site Url: ' . $siteurl);
            self::log('Checkin Url: ' . $checkinurl);
            $qrcodeinfo = $downloadurl . 'SITEURL-' . base64url_encode($siteurl) . '/CHECKINURL-' . base64url_encode($checkinurl);
            self::log('Encoded Checkin Url: ' . base64url_encode($checkinurl));
            self::log('Decoded Checkin Url: ' . base64url_decode(base64url_encode($checkinurl)));
            
            return($this->getQRCodeUrl($qrcodeinfo));
        }
        
    /**
     * get the qr code url
     * @param string $qrdata
     * @return string
     */
    public function getQRCodeUrl($qrdata) {
        $_baseurl = Yii::app()->createAbsoluteUrl('/');
        $qr_filename = 'QR_' . md5($qrdata) . '.png';
        $qr_filePath = (empty(Yii::app()->params['qrcode_folder']) ? (Yii::getPathOfAlias('webroot.uploads') . DIRECTORY_SEPARATOR . 'qr') : Yii::app()->params['qrcode_folder']);
        $qr_fileUrl = (empty(Yii::app()->params['qrcode_url']) ? $_baseurl . '/uploads/qr' : Yii::app()->params['qrcode_url']);
        $qr_fullFilePath = $qr_filePath . DIRECTORY_SEPARATOR . $qr_filename;
        $qr_fullUrl = $qr_fileUrl . '/' . $qr_filename;

        // check to create the qr code now
        if (!is_file($qr_fullFilePath)) {
            $code = new QRCode($qrdata);
            $code->create($qr_fullFilePath);
        }
        return $qr_fullUrl;
    }
    
}
