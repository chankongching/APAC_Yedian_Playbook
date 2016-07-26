<?php

/**
 * This is the model class for table "{{coupon_share}}".
 *
 * The followings are the available columns in table '{{coupon_share}}':
 * @property string $id
 * @property integer $userid
 * @property integer $orderid
 * @property string $hash_url
 * @property integer $share_count
 * @property string $create_time
 * @property string $update_time
 * @property string $create_user_id
 * @property string $update_user_id
 */
class CouponShare extends PSActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{coupon_share}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userid, orderid, hash_url, share_count, create_time, update_time', 'required'),
            array('userid, orderid, share_count', 'numerical', 'integerOnly' => true),
            array('hash_url', 'length', 'max' => 32),
            array('create_user_id, update_user_id', 'length', 'max' => 50),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, userid, orderid, hash_url, share_count, create_time, update_time, create_user_id, update_user_id', 'safe', 'on' => 'search'),
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
            'userid' => 'Userid',
            'orderid' => 'Orderid',
            'hash_url' => 'Hash Url',
            'share_count' => 'Share Count',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'create_user_id' => 'Create User',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('userid', $this->userid);
        $criteria->compare('orderid', $this->orderid);
        $criteria->compare('hash_url', $this->hash_url, true);
        $criteria->compare('share_count', $this->share_count);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('create_user_id', $this->create_user_id, true);
        $criteria->compare('update_user_id', $this->update_user_id, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CouponShare the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getInfoByOrderid($orderid)
    {
        $info = $this->findByAttributes(array('orderid' => $orderid));
        $sharecouponinfo = $this->getInfo($info);
        return $sharecouponinfo;
    }

    protected function getInfo($info)
    {
        if ($info != null) {
            $sharecouponinfo = array(
                'id' => intval($info['id']),
                'userid' => intval($info['userid']),
                'orderid' => $info['orderid'],
                'hash_url' => $info['hash_url'],
                'share_count' => intval($info['share_count']),
            );
            return $sharecouponinfo;
        }
    }

    public function getInfoByid($id)
    {
        if ($id != null) {
            return $this->findByPk($id);
        }
    }

    public function getcouponstatus($id)
    {
        $sharecoupon = $this->findByPk($id);
        if ($sharecoupon != null) {
            return array('total' => 10, 'count' => 10-$sharecoupon['share_count']);
        } else {
            return null;
        }

    }

    public function sharecountadd($id)
    {
        $coupon = $this->findByPk($id);
        if ($coupon != null) {
            $coupon->share_count = $coupon->share_count + 1;
            if ($coupon->save()) {
                return array('status' => 0);
            } else {
                return array('status' => 1);
            }
        }
    }
}
