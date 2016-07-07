<?php

/**
 * This is the model class for table "{{qrcode}}".
 *
 * The followings are the available columns in table '{{qrcode}}':
 * @property integer $id
 * @property integer $activity_id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property integer $points
 * @property integer $usages
 * @property integer $userlimit
 * @property integer $userdaylimit
 * @property integer $usagelimit
 * @property integer $daylimit
 * @property integer $usedtotal
 * @property integer $usagetotal
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * 
 * The followings are the available model relations:
 */
class ActivityQrcode extends PSActiveRecord {

    protected $activity_options;
    protected $status_options;

    public function init() {
        parent::init();
        $this->activity_options = array(
            1 => Yii::t('Qrcode', 'Promotion Code'),
            2 => Yii::t('Qrcode', 'Reward Code'),
            3 => Yii::t('Qrcode', 'Redeem Code'),
        );
        $this->status_options = array(0 => Yii::t('Qrcode', 'Disabled'), 1 => Yii::t('Qrcode', 'Enabled'));
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{qrcode}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('activity_id', 'required'),
            array('activity_id, status, points, usages, usedtotal, usagetotal, daylimit, userlimit, userdaylimit, usagelimit, create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
            array('name, description, code', 'length', 'max' => 255),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, activity_id, code, name, description, status, points, usages, usedtotal, usagetotal, daylimit, userlimit, userdaylimit, usagelimit, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
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
            'id' => Yii::t('Qrcode', 'ID'),
            'activity_id' => Yii::t('Qrcode', 'Activity'),
            'name' => Yii::t('Qrcode', 'Name'),
            'code' => Yii::t('Qrcode', 'Code'),
            'description' => Yii::t('Qrcode', 'Description'),
            'status' => Yii::t('Qrcode', 'Status'),
            'points' => Yii::t('Qrcode', 'Points'),
            'usages' => Yii::t('Qrcode', 'Used Times'),
            'daylimit' => Yii::t('Qrcode', 'Day Limit'),
            'userlimit' => Yii::t('Qrcode', 'User Limit Times'),
            'userdaylimit' => Yii::t('Qrcode', 'User Day Limit'),
            'usagelimit' => Yii::t('Qrcode', 'Total Limit Times'),
            'usedtotal' => Yii::t('Qrcode', 'Total Used Points'),
            'usagetotal' => Yii::t('Qrcode', 'Total Points'),
            'create_time' => Yii::t('Qrcode', 'Create Time'),
            'create_user_id' => Yii::t('Qrcode', 'Create User'),
            'update_time' => Yii::t('Qrcode', 'Update Time'),
            'update_user_id' => Yii::t('Qrcode', 'Update User'),
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
        $criteria->compare('activity_id', $this->activity_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('points', $this->points);
        $criteria->compare('usages', $this->usages);
        $criteria->compare('daylimit', $this->daylimit);
        $criteria->compare('userlimit', $this->userlimit);
        $criteria->compare('userdaylimit', $this->userlimit);
        $criteria->compare('usagelimit', $this->usagelimit);
        $criteria->compare('usedtotal', $this->usedtotal);
        $criteria->compare('usagetotal', $this->usagetotal);
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
     * @return Room the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getActivityName($type) {
        if (isset($this->activity_options[$type])) {
            return $this->activity_options[$type];
        }
        return '';
    }

    public function getActivityOptions() {

        return $this->activity_options;
    }

    public function getStatusName($type) {
        if (isset($this->status_options[$type])) {
            return $this->status_options[$type];
        }
        return '';
    }

    public function getStatusOptions() {

        return $this->status_options;
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
