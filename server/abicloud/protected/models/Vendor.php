<?php

/**
 * This is the model class for table "{{vendor}}".
 *
 * The followings are the available columns in table '{{vendor}}':
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property Application[] $applications
 * @property Room[] $rooms
 */
class Vendor extends PSActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{vendor}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, content', 'required'),
            array('create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
            array('name, content', 'length', 'max' => 255),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, content, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'applications' => array(self::HAS_MANY, 'Application', 'vendor_id'),
            'rooms' => array(self::HAS_MANY, 'Room', 'vendor_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'content' => 'Content',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('content', $this->content, true);
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
     * @return Vendor the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getVendor($vendorKey) {
        $model = $this->findByAttributes(array('content' => $vendorKey));
        return $model;
    }

    public function getVendorID($vendorKey) {
        $model = $this->findByAttributes(array('content' => $vendorKey));
        if (is_null($model)) {
            return 0;
        } else {
            return $model->getAttribute('id');
        }
    }

    public function getVendorOptions() {
        $vendor_list = array();
        $vendors = $this->findAll();
        if (!empty($vendors)) {
            foreach ($vendors as $key => $vendor) {
                $vendor_list[$vendor->id] = $vendor->name;
            }
        }
        return $vendor_list;
    }
}
