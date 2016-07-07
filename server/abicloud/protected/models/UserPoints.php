<?php

/**
 * This is the model class for table "{{user_points}}".
 *
 * The followings are the available columns in table '{{user_points}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property integer $points
 * @property integer $rewards
 * @property integer $redeems
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property Room $room
 */
class UserPoints extends PSActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_points}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id', 'required'),
            array('user_id, status, points, rewards, redeems, create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, status, points, rewards, redeems, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
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
            'id' => Yii::t('UserPoints', 'ID'),
            'user_id' => Yii::t('UserPoints', 'User ID'),
            'status' => Yii::t('UserPoints', 'Status'),
            'points' => Yii::t('UserPoints', 'Total Points'),
            'rewards' => Yii::t('UserPoints', 'Rewarded Points'),
            'redeems' => Yii::t('UserPoints', 'Redeemed Points'),
            'create_time' => Yii::t('UserPoints', 'Create Time'),
            'create_user_id' => Yii::t('UserPoints', 'Create User'),
            'update_time' => Yii::t('UserPoints', 'Update Time'),
            'update_user_id' => Yii::t('UserPoints', 'Update User'),
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
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('points', $this->points);
        $criteria->compare('rewards', $this->rewards);
        $criteria->compare('redeems', $this->redeems);
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
