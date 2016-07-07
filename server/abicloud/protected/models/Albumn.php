<?php

/**
 * This is the model class for table "{{albumn}}".
 *
 * The followings are the available columns in table '{{albumn}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $publish_time
 * @property string $bpic_filename
 * @property string $bpic_url
 * @property string $spic_filename
 * @property string $spic_url
 * @property integer $status
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property string $artists
 * @property string $name_chinese
 * @property string $name_pinyin
 *
 * The followings are the available model relations:
 * @property Media[] $medias
 */
class Albumn extends PSActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{albumn}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('status, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('name, description, bpic_filename, bpic_url, spic_filename, spic_url, artists, name_chinese, name_pinyin', 'length', 'max'=>255),
			array('publish_time, create_time, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, publish_time, bpic_filename, bpic_url, spic_filename, spic_url, status, create_time, create_user_id, update_time, update_user_id, artists, name_chinese, name_pinyin', 'safe', 'on'=>'search'),
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
			'medias' => array(self::HAS_MANY, 'Media', 'albumn_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('Albumn','ID'),
			'name' => Yii::t('Albumn','Name'),
			'description' => Yii::t('Albumn','Description'),
			'publish_time' => Yii::t('Albumn','Publish Time'),
			'bpic_filename' => Yii::t('Albumn','Bpic Filename'),
			'bpic_url' => Yii::t('Albumn','Bpic Url'),
			'spic_filename' => Yii::t('Albumn','Spic Filename'),
			'spic_url' => Yii::t('Albumn','Spic Url'),
			'status' => Yii::t('Albumn','Status'),
			'create_time' => Yii::t('Albumn','Create Time'),
			'create_user_id' => Yii::t('Albumn','Create User'),
			'update_time' => Yii::t('Albumn','Update Time'),
			'update_user_id' => Yii::t('Albumn','Update User'),
			'artists' => Yii::t('Albumn','Artists'),
			'name_chinese' => Yii::t('Albumn','Name Chinese'),
			'name_pinyin' => Yii::t('Albumn','Name Pinyin'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('publish_time',$this->publish_time,true);
		$criteria->compare('bpic_filename',$this->bpic_filename,true);
		$criteria->compare('bpic_url',$this->bpic_url,true);
		$criteria->compare('spic_filename',$this->spic_filename,true);
		$criteria->compare('spic_url',$this->spic_url,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('artists',$this->artists,true);
		$criteria->compare('name_chinese',$this->name_chinese,true);
		$criteria->compare('name_pinyin',$this->name_pinyin,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Albumn the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
