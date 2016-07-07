<?php

/**
 * This is the model class for table "{{device_playlist}}".
 *
 * The followings are the available columns in table '{{device_playlist}}':
 * @property integer $id
 * @property integer $device_id
 * @property integer $media_id
 * @property integer $index_num
 * @property integer $status
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property string $play_status
 * @property integer $play_timestamp
 * @property integer $play_posttime
 *
 * The followings are the available model relations:
 * @property Device $device
 * @property Media $media
 */
class DevicePlaylist extends PSActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{device_playlist}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('device_id, media_id, index_num, status, create_user_id, update_user_id, play_timestamp, play_posttime', 'numerical', 'integerOnly' => true),
            array('play_status', 'length', 'max' => 32),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, device_id, media_id, index_num, status, create_time, create_user_id, update_time, update_user_id, play_status, play_timestamp, play_posttime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'device' => array(self::BELONGS_TO, 'Device', 'device_id'),
            'media' => array(self::BELONGS_TO, 'Media', 'media_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('DevicePlaylist','ID'),
            'device_id' => Yii::t('DevicePlaylist','Device'),
            'media_id' => Yii::t('DevicePlaylist','Media'),
            'index_num' => Yii::t('DevicePlaylist','Index Num'),
            'status' => Yii::t('DevicePlaylist','Status'),
            'create_time' => Yii::t('DevicePlaylist','Create Time'),
            'create_user_id' => Yii::t('DevicePlaylist','Create User'),
            'update_time' => Yii::t('DevicePlaylist','Update Time'),
            'update_user_id' => Yii::t('DevicePlaylist','Update User'),
            'play_status' => Yii::t('DevicePlaylist','Play Status'),
            'play_timestamp' => Yii::t('DevicePlaylist','Play Timestamp'),
            'play_posttime' => Yii::t('DevicePlaylist','Play Posttime'),
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
        $criteria->compare('device_id', $this->device_id);
        $criteria->compare('media_id', $this->media_id);
        $criteria->compare('index_num', $this->index_num);
        $criteria->compare('status', $this->status);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('create_user_id', $this->create_user_id);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('update_user_id', $this->update_user_id);
        $criteria->compare('play_status', $this->play_status, true);
        $criteria->compare('play_timestamp', $this->play_timestamp);
        $criteria->compare('play_posttime', $this->play_posttime);
        
        //$criteria->addInCondition('status', array(0, 1));

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DevicePlaylist the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getStatus($status) {
        $status_array = array(
            0=>Yii::t('DevicePlaylist','Waiting'),
            1=>Yii::t('DevicePlaylist','Playing'),
            2=>Yii::t('DevicePlaylist','Played')
        );
        
        return isset($status_array[$status])?$status_array[$status]:'';
    }
}
