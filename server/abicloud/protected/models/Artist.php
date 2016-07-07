<?php

/**
 * This is the model class for table "{{artist}}".
 *
 * The followings are the available columns in table '{{artist}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $birthday
 * @property string $bpic_filename
 * @property string $bpic_url
 * @property string $spic_filename
 * @property string $spic_url
 * @property integer $status
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property string $country
 * @property string $periods
 * @property string $products
 * @property string $name_chinese
 * @property string $name_pinyin
 * @property integer $name_count
 *
 * The followings are the available model relations:
 * @property Media[] $medias
 */
class Artist extends PSActiveRecord
{
    public $bpic_file;
    public $spic_file;
    public $attach_path = '';
    public $attach_url = '';
    
    public function init() {
        parent::init();
        $this->attach_path = (empty(Yii::app()->params['upload_folder']) ? Yii::getPathOfAlias('webroot.uploads') : Yii::app()->params['upload_folder']) . DIRECTORY_SEPARATOR . 'attach' . DIRECTORY_SEPARATOR . 'artist';
        if (!file_exists($this->attach_path)) {
            $this->_createFolder($this->attach_path);
        }
        $this->attach_url = (empty(Yii::app()->params['upload_url']) ? (Yii::app()->createAbsoluteUrl('/') . '/uploads') : (Yii::app()->params['upload_url'])) . '/attach/artist';
    }
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{artist}}';
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
			array('status, create_user_id, update_user_id, name_count', 'numerical', 'integerOnly'=>true),
			array('name, description, bpic_filename, bpic_url, spic_filename, spic_url, country, periods, products, name_chinese, name_pinyin', 'length', 'max'=>255),
			array('birthday, create_time, update_time', 'safe'),
                        array('bpic_file', 'file', 'types' => 'jpg,jpeg,png,bmp,gif', 'maxSize' => 1048576 * 10, 'safe' => true, 'allowEmpty' => true),
                        array('spic_file', 'file', 'types' => 'jpg,jpeg,png,bmp,gif', 'maxSize' => 1048576 * 10, 'safe' => true, 'allowEmpty' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, birthday, bpic_filename, bpic_url, spic_filename, spic_url, status, create_time, create_user_id, update_time, update_user_id, country, periods, products, name_chinese, name_pinyin, name_count', 'safe', 'on'=>'search'),
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
			'medias' => array(self::HAS_MANY, 'Media', 'artist_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('Artist','ID'),
			'name' => Yii::t('Artist','Name'),
			'description' => Yii::t('Artist','Description'),
			'birthday' => Yii::t('Artist','Birthday'),
			'bpic_filename' => Yii::t('Artist','Bpic Filename'),
			'bpic_url' => Yii::t('Artist','Bpic Url'),
			'spic_filename' => Yii::t('Artist','Spic Filename'),
			'spic_url' => Yii::t('Artist','Spic Url'),
			'status' => Yii::t('Artist','Status'),
			'create_time' => Yii::t('Artist','Create Time'),
			'create_user_id' => Yii::t('Artist','Create User'),
			'update_time' => Yii::t('Artist','Update Time'),
			'update_user_id' => Yii::t('Artist','Update User'),
			'country' => Yii::t('Artist','Country'),
			'periods' => Yii::t('Artist','Periods'),
			'products' => Yii::t('Artist','Products'),
			'name_chinese' => Yii::t('Artist','Name Chinese'),
			'name_pinyin' => Yii::t('Artist','Name Pinyin'),
			'name_count' => Yii::t('Artist','Name Count'),
                        'bpic_file' => Yii::t('Artist','Bpic Url'),
                        'spic_file' => Yii::t('Artist','Spic Url'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('bpic_filename',$this->bpic_filename,true);
		$criteria->compare('bpic_url',$this->bpic_url,true);
		$criteria->compare('spic_filename',$this->spic_filename,true);
		$criteria->compare('spic_url',$this->spic_url,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('periods',$this->periods,true);
		$criteria->compare('products',$this->products,true);
		$criteria->compare('name_chinese',$this->name_chinese,true);
		$criteria->compare('name_pinyin',$this->name_pinyin,true);
		$criteria->compare('name_count',$this->name_count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination' => array('pageSize' => $pagesize),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Artist the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
