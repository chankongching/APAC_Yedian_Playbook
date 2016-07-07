<?php

/**
 * This is the model class for table "{{artistofcategory}}".
 *
 * The followings are the available columns in table '{{artistofcategory}}':
 * @property integer $id
 * @property integer $artist_id
 * @property integer $category_id
 * @property string $description
 * @property integer $status
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property Artist $artist
 * @property Artistcategory $category
 */
class Artistofcategory extends PSActiveRecord
{
    // TODO: Step 1, for relation field filter and sort
    public $artist_name;
    public $category_name;
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{artistofcategory}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('artist_id, category_id, status, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			// TODO: Step 1, for relation field filter
			array('id, artist_id, category_id, description, status, create_time, create_user_id, update_time, update_user_id, artist_name, category_name', 'safe', 'on'=>'search'),
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
			'artist' => array(self::BELONGS_TO, 'Artist', 'artist_id'),
			'category' => array(self::BELONGS_TO, 'Artistcategory', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'artist_id' => Yii::t('Artistofcategory', 'Artist'),
			'category_id' => Yii::t('Artistofcategory', 'Category'),
			'description' => Yii::t('Artistofcategory', 'Description'),
			'status' => Yii::t('site', 'Status'),
			'create_time' => Yii::t('site', 'Create Time'),
			'create_user_id' => Yii::t('site', 'Create User'),
			'update_time' => Yii::t('site', 'Update Time'),
			'update_user_id' => Yii::t('site', 'Update User'),
			'artist_name' => Yii::t('Artistofcategory', 'Artist Name'),
			'category_name' => Yii::t('Artistofcategory', 'Category Name'),
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
                
                // TODO: Step 2, relation filter
                $criteria->with = array('artist'=>array('select'=>'name'),'category'=>array('select'=>'name'));
		$criteria->compare('artist.name',$this->artist_name,true);
		$criteria->compare('category.name',$this->category_name,true);

		$criteria->compare('id',$this->id);
		$criteria->compare('artist_id',$this->artist_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('t.description',$this->description,true);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);

                // TODO: Step 2, relation sort
		$sort = new CSort();
		$sort->attributes = array(
		    //'defaultOrder'=>'t.create_time',
		    'artist_name'=>array(
		        'asc'=>'artist.name',
		        'desc'=>'artist.name desc',
		    ),
		    'category_name'=>array(
		        'asc'=>'category.name',
		        'desc'=>'category.name desc',
		    ),
		    'description'=>array(
		        'asc'=>'t.description',
		        'desc'=>'t.description desc',
		    ),
		    'status'=>array(
		        'asc'=>'t.status',
		        'desc'=>'t.status desc',
		    ),
		);        
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination' => array('pageSize' => $pagesize),
                        'sort'=>$sort,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Artistofcategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        /**
         * After find process
         * TODO: Step 3 for relation field filter and sort
         */
        public function afterFind() {
            parent::afterFind();
            $this->artist_name = (isset($this->artist) && isset($this->artist->name))?$this->artist->name:'';
            $this->category_name = (isset($this->category) && isset($this->category->name))?$this->category->name:'';
        }
}
