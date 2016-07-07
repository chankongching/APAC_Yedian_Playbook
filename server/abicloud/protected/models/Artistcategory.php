<?php

/**
 * This is the model class for table "{{artistcategory}}".
 *
 * The followings are the available columns in table '{{artistcategory}}':
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $description
 * @property string $bpic_filename
 * @property string $bpic_url
 * @property string $spic_filename
 * @property string $spic_url
 * @property integer $status
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property Artistofcategory[] $artistofcategories
 */
class Artistcategory extends PSActiveRecord
{
    public $bpic_file;
    public $spic_file;
    public $attach_path = '';
    public $attach_url = '';
    
    public function init() {
        parent::init();
        $this->attach_path = (empty(Yii::app()->params['upload_folder']) ? Yii::getPathOfAlias('webroot.uploads') : Yii::app()->params['upload_folder']) . DIRECTORY_SEPARATOR . 'attach' . DIRECTORY_SEPARATOR . 'picture';
        if (!file_exists($this->attach_path)) {
            $this->_createFolder($this->attach_path);
        }
        $this->attach_url = (empty(Yii::app()->params['upload_url']) ? (Yii::app()->createAbsoluteUrl('/') . '/uploads') : (Yii::app()->params['upload_url'])) . '/attach/picture';
    }
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{artistcategory}}';
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
			array('parent_id, status, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('name, description, bpic_filename, bpic_url, spic_filename, spic_url', 'length', 'max'=>255),
			array('create_time, update_time', 'safe'),
                        array('bpic_file', 'file', 'types' => 'jpg,jpeg,png,bmp,gif', 'maxSize' => 1048576 * 10, 'safe' => true, 'allowEmpty' => true),
                        array('spic_file', 'file', 'types' => 'jpg,jpeg,png,bmp,gif', 'maxSize' => 1048576 * 10, 'safe' => true, 'allowEmpty' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parent_id, name, description, bpic_filename, bpic_url, spic_filename, spic_url, status, create_time, create_user_id, update_time, update_user_id', 'safe', 'on'=>'search'),
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
			'artistofcategories' => array(self::HAS_MANY, 'Artistofcategory', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_id' => 'Parent',
			'name' => Yii::t('Artistcategory', 'Name'),
			'description' => Yii::t('Artistcategory', 'Description'),
			'bpic_filename' => Yii::t('Artistcategory', 'Bpic Filename'),
			'bpic_url' => Yii::t('Artistcategory', 'Bpic Url'),
			'spic_filename' => Yii::t('Artistcategory', 'Spic Filename'),
			'spic_url' => Yii::t('Artistcategory', 'Spic Url'),
			'status' => Yii::t('site', 'Status'),
			'create_time' => Yii::t('site', 'Create Time'),
			'create_user_id' => Yii::t('site', 'Create User'),
			'update_time' => Yii::t('site', 'Update Time'),
			'update_user_id' => Yii::t('site', 'Update User'),
                        'bpic_file' => Yii::t('Artistcategory', 'Select File'),
                        'spic_file' => Yii::t('Artistcategory', 'Select File'),
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
	public function search($pagesize = 10)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('bpic_filename',$this->bpic_filename,true);
		$criteria->compare('bpic_url',$this->bpic_url,true);
		$criteria->compare('spic_filename',$this->spic_filename,true);
		$criteria->compare('spic_url',$this->spic_url,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination' => array('pageSize' => $pagesize),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Artistcategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
