<?php

/**
 * This is the model class for table "{{points_history}}".
 *
 * The followings are the available columns in table '{{points_history}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $activity_id
 * @property string $code
 * @property integer $points_before
 * @property integer $points
 * @property integer $points_after
 * @property integer $duetime
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property Room $room
 */
class PointsHistory extends PSActiveRecord {

    public $activity_name = '';
    public $source_name = '';

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{points_history}}';
    }

    protected function afterFind() {
        parent::afterFind();
        if (!empty($this->activity_id) && !empty($this->code)) {
            $qrcode = ActivityQrcode::model()->findByAttributes(array('activity_id' => $this->activity_id, 'code' => $this->code));
            if (!empty($qrcode)) {
                $this->activity_name = $qrcode->getActivityName(intval($this->activity_id));
                $this->source_name = $qrcode->name;
            }
        }
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id', 'required'),
            array('user_id, activity_id, points_before, points, points_after, duetime, create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
            array('code', 'length', 'max' => 255),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, code, user_id, activity_id, points_before, points, points_after, duetime, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
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
            'id' => Yii::t('PointsHistory', 'ID'),
            'user_id' => Yii::t('PointsHistory', 'User ID'),
            'activity_id' => Yii::t('PointsHistory', 'Activity ID'),
            'code' => Yii::t('PointsHistory', 'Source Code'),
            'points_before' => Yii::t('PointsHistory', 'Points Before'),
            'points' => Yii::t('PointsHistory', 'Points Changed'),
            'points_after' => Yii::t('PointsHistory', 'Points After'),
            'duetime' => Yii::t('PointsHistory', 'Date Time'),
            'create_time' => Yii::t('PointsHistory', 'Create Time'),
            'create_user_id' => Yii::t('PointsHistory', 'Create User'),
            'update_time' => Yii::t('PointsHistory', 'Update Time'),
            'update_user_id' => Yii::t('PointsHistory', 'Update User'),
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
        $criteria->compare('code', $this->code, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('activity_id', $this->activity_id);
        $criteria->compare('points_before', $this->points_before);
        $criteria->compare('points', $this->points);
        $criteria->compare('points_after', $this->points_after);
        $criteria->compare('duetime', $this->duetime);
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
     * @return CheckinCode the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
