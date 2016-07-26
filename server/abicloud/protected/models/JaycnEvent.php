<?php

/**
 * This is the model class for table "{{jaycn_event}}".
 *
 * The followings are the available columns in table '{{jaycn_event}}':
 * @property string $id
 * @property string $name
 * @property integer $total
 * @property integer $count
 * @property integer $date
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 */
class JaycnEvent extends PSActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{jaycn_event}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, date, create_time, update_time', 'required'),
            array('total, count, date, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>20),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, total, count, date, create_time, create_user_id, update_time, update_user_id', 'safe', 'on'=>'search'),
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
            'total' => 'Total',
            'count' => 'Count',
            'date' => 'Date',
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

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('total',$this->total);
        $criteria->compare('count',$this->count);
        $criteria->compare('date',$this->date);
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
     * @return JaycnEvent the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
