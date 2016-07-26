<?php

/**
 * This is the model class for table "{{jaycn_event_order}}".
 *
 * The followings are the available columns in table '{{jaycn_event_order}}':
 * @property string $id
 * @property string $name
 * @property integer $status
 * @property string $mobile
 * @property string $openid
 * @property integer $sex
 * @property integer $roomid
 * @property string $create_time
 * @property string $update_time
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $order_qr_code
 */
class JaycnEventOrder extends PSActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{jaycn_event_order}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('create_time, update_time', 'required'),
            array('status, sex, roomid, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
            array('name, mobile', 'length', 'max'=>20),
            array('openid', 'length', 'max'=>40),
            array('order_qr_code', 'length', 'max'=>100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, status, mobile, openid, sex, roomid, create_time, update_time, create_user_id, update_user_id, order_qr_code', 'safe', 'on'=>'search'),
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
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'mobile' => 'Mobile',
            'openid' => 'Openid',
            'sex' => 'Sex',
            'roomid' => 'Roomid',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'create_user_id' => 'Create User',
            'update_user_id' => 'Update User',
            'order_qr_code' => 'Order Qr Code',
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

        $criteria->compare('id',$this->id,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('mobile',$this->mobile,true);
        $criteria->compare('openid',$this->openid,true);
        $criteria->compare('sex',$this->sex);
        $criteria->compare('roomid',$this->roomid);
        $criteria->compare('create_time',$this->create_time,true);
        $criteria->compare('update_time',$this->update_time,true);
        $criteria->compare('create_user_id',$this->create_user_id);
        $criteria->compare('update_user_id',$this->update_user_id);
        $criteria->compare('order_qr_code',$this->order_qr_code,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return JaycnEventOrder the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getJayOrder($userid){
        $openid = PlatformUser::model()->getUserOpenid($userid);
        $orderinfo = $this->findByAttributes(array('openid'=>$openid));
        if($orderinfo!=null){
            return $orderinfo;
        }else{
            return null;
        }

    }
}
