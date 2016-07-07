<?php

/**
 * This is the model class for table "{{room_extra}}".
 *
 * The followings are the available columns in table '{{room_extra}}':
 * @property integer $id
 * @property integer $pid
 * @property string $code
 * @property string $name
 * @property integer $sort
 *
 * The followings are the available model relations:
 * @property Room $room
 */
class AreaCode extends PSActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{area}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pid, sort', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>20),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pid, name, code, sort', 'safe', 'on'=>'search'),
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
			'pid' => 'Parent ID',
			'code' => 'Area code',
			'name' => 'Area name',
			'sort' => 'Sort',
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
		$criteria->compare('pid',$this->pid);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sort',$this->sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RoomExtra the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getAreaCode($_code, $_offset, $_limit){
        $_area_list = array(); 
        if(!empty($_code)){
            $_list = $this->findAllByAttributes(array('pid' => $_code), array('offset' => $_offset, 'limit' => $_limit));
        }else{
            $_list = $this->findAllByAttributes(array('pid' => 0), array('offset' => $_offset, 'limit' => $_limit));
        }
        if(!empty($_list) && is_array($_list)){
            foreach($_list as $key=>$area){
                $_area_list[] = array(
                    "code" => $area['code'],
                    "name" => $area['name'],
                    "childnum" => $this->count("pid =:id",array(':id'=>$area['code']))
                );

            }
        }
        return $_area_list;
    }

}

