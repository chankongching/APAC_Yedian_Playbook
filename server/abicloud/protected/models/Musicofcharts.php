<?php

/**
 * This is the model class for table "{{musicofcharts}}".
 *
 * The followings are the available columns in table '{{musicofcharts}}':
 * @property integer $id
 * @property integer $media_id
 * @property integer $chart_id
 * @property string $description
 * @property integer $status
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property integer $rank
 *
 * The followings are the available model relations:
 * @property Media $media
 * @property Musiccharts $chart
 */
class Musicofcharts extends PSActiveRecord
{
    // TODO: Step 1, for relation field filter and sort
    public $song_name;
    public $category_name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{musicofcharts}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('media_id, chart_id, status, create_user_id, update_user_id, rank', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			// TODO: Step 1, for relation field filter
			array('id, media_id, chart_id, description, status, create_time, create_user_id, update_time, update_user_id, rank, song_name, category_name', 'safe', 'on'=>'search'),
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
			'media' => array(self::BELONGS_TO, 'Media', 'media_id'),
			'chart' => array(self::BELONGS_TO, 'Musiccharts', 'chart_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'media_id' => Yii::t('Musicofcharts', 'Media'),
			'chart_id' => Yii::t('Musicofcharts', 'Chart'),
			'description' => Yii::t('Musicofcharts', 'Description'),
			'rank' => Yii::t('Musicofcharts', 'Rank'),
			'status' => Yii::t('site', 'Status'),
			'create_time' => Yii::t('site', 'Create Time'),
			'create_user_id' => Yii::t('site', 'Create User'),
			'update_time' => Yii::t('site', 'Update Time'),
			'update_user_id' => Yii::t('site', 'Update User'),
			'song_name' => Yii::t('Musicofcharts', 'Song Name'),
			'category_name' => Yii::t('Musicofcharts', 'Category Name'),
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
                $criteria->with = array('media'=>array('select'=>'name'),'chart'=>array('select'=>'name'));
		$criteria->compare('media.name',$this->song_name,true);
		$criteria->compare('chart.name',$this->category_name,true);

		$criteria->compare('id',$this->id);
		$criteria->compare('media_id',$this->media_id);
		$criteria->compare('chart_id',$this->chart_id);
		$criteria->compare('rank',$this->rank);
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
		    'song_name'=>array(
		        'asc'=>'media.name',
		        'desc'=>'media.name desc',
		    ),
		    'category_name'=>array(
		        'asc'=>'chart.name',
		        'desc'=>'chart.name desc',
		    ),
		    'description'=>array(
		        'asc'=>'t.description',
		        'desc'=>'t.description desc',
		    ),
		    'status'=>array(
		        'asc'=>'t.status',
		        'desc'=>'t.status desc',
		    ),
		    'rank',
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
	 * @return Musicofcharts the static model class
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
            $this->song_name = (isset($this->media) && isset($this->media->name))?$this->media->name:'';
            $this->category_name = (isset($this->chart) && isset($this->chart->name))?$this->chart->name:'';
        }
}
