<?php

/**
 * This is the model class for table "{{comment}}".
 *
 * The followings are the available columns in table '{{comment}}':
 * @property integer $id
 * @property string $ktvid
 * @property string $openid
 * @property integer $DecorationRating
 * @property integer $SoundRating
 * @property integer $ServiceRating
 * @property integer $ConsumerRating
 * @property integer $FoodRating
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 */
class Comment extends PSActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{comment}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ktvid, openid, DecorationRating, SoundRating, ServiceRating, ConsumerRating, FoodRating', 'required'),
            array('DecorationRating, SoundRating, ServiceRating, ConsumerRating, FoodRating, create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
            array('ktvid', 'length', 'max' => 20),
            array('openid', 'length', 'max' => 50),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, ktvid, openid, DecorationRating, SoundRating, ServiceRating, ConsumerRating, FoodRating, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'ktvid' => 'Ktvid',
            'openid' => 'Openid',
            'orderid' => 'orderid',
            'DecorationRating' => 'Decoration Rating',
            'SoundRating' => 'Sound Rating',
            'ServiceRating' => 'Service Rating',
            'ConsumerRating' => 'Consumer Rating',
            'FoodRating' => 'Food Rating',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('ktvid', $this->ktvid, true);
        $criteria->compare('openid', $this->openid, true);
        $criteria->compare('orderid', $this->orderid, true);
        $criteria->compare('DecorationRating', $this->DecorationRating);
        $criteria->compare('SoundRating', $this->SoundRating);
        $criteria->compare('ServiceRating', $this->ServiceRating);
        $criteria->compare('ConsumerRating', $this->ConsumerRating);
        $criteria->compare('FoodRating', $this->FoodRating);
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
     * @return Comment the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getRatingStatus($userid = '', $ktvid = '')
    {
        if ($userid != '' && $ktvid != '') {
            $comm = $this->findByAttributes(array('userid' => $userid, 'ktvid' => $ktvid));
            if ($comm != null) {
                return array('status' => 1, 'rating' => array(
                    'DecorationRating' => intval($comm->DecorationRating),
                    'SoundRating' => intval($comm->SoundRating),
                    'ServiceRating' => intval($comm->ServiceRating),
                    'ConsumerRating' => intval($comm->ConsumerRating),
                    'FoodRating' => intval($comm->FoodRating),
                    'appRating' => intval($comm->appRating),
                    // 'uid' => $userid,
                    // 'ktv' => $ktvid,
                ));
            } else {
                return array('status' => 0,
                    // 'uid' => $userid, 'ktv' => $ktvid,
                );
            }

        }
    }

    public function getAppRating($orderid)
    {
        $commentinfo = $this->findByAttributes(array('orderid' => $orderid));
        if ($commentinfo != null) {
            return intval($commentinfo->appRating);
        }
    }
}
