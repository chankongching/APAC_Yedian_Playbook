<?php

/**
 * This is the model class for table "{{temp_import}}".
 *
 * The followings are the available columns in table '{{temp_import}}':
 * @property string $col1
 * @property string $col2
 * @property string $col3
 * @property string $col4
 * @property string $col5
 * @property string $col6
 * @property string $col7
 * @property string $col8
 * @property string $col9
 * @property string $col10
 * @property string $col11
 * @property string $col12
 * @property string $col13
 * @property string $col14
 * @property string $col15
 * @property string $col16
 * @property string $col17
 * @property string $col18
 * @property string $col19
 * @property string $col20
 * @property string $col21
 * @property string $col22
 * @property string $col23
 * @property string $col24
 * @property string $col25
 * @property string $col26
 * @property string $col27
 * @property string $col28
 * @property string $col29
 * @property string $col30
 * @property string $col31
 * @property string $col32
 * @property string $col33
 * @property string $col34
 * @property string $col35
 */
class TempImport extends PSActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{temp_import}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('col1, col2, col3, col4, col5, col6, col7, col8, col9, col10, col11, col12, col13, col14, col15, col16, col17, col18, col19, col20, col21, col22, col23, col24, col25, col26, col27, col28, col29, col30, col31, col32, col33, col34, col35', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('col1, col2, col3, col4, col5, col6, col7, col8, col9, col10, col11, col12, col13, col14, col15, col16, col17, col18, col19, col20, col21, col22, col23, col24, col25, col26, col27, col28, col29, col30, col31, col32, col33, col34, col35', 'safe', 'on'=>'search'),
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
			'col1' => 'Col1',
			'col2' => 'Col2',
			'col3' => 'Col3',
			'col4' => 'Col4',
			'col5' => 'Col5',
			'col6' => 'Col6',
			'col7' => 'Col7',
			'col8' => 'Col8',
			'col9' => 'Col9',
			'col10' => 'Col10',
			'col11' => 'Col11',
			'col12' => 'Col12',
			'col13' => 'Col13',
			'col14' => 'Col14',
			'col15' => 'Col15',
			'col16' => 'Col16',
			'col17' => 'Col17',
			'col18' => 'Col18',
			'col19' => 'Col19',
			'col20' => 'Col20',
			'col21' => 'Col21',
			'col22' => 'Col22',
			'col23' => 'Col23',
			'col24' => 'Col24',
			'col25' => 'Col25',
			'col26' => 'Col26',
			'col27' => 'Col27',
			'col28' => 'Col28',
			'col29' => 'Col29',
			'col30' => 'Col30',
			'col31' => 'Col31',
			'col32' => 'Col32',
			'col33' => 'Col33',
			'col34' => 'Col34',
			'col35' => 'Col35',
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

		$criteria->compare('col1',$this->col1,true);
		$criteria->compare('col2',$this->col2,true);
		$criteria->compare('col3',$this->col3,true);
		$criteria->compare('col4',$this->col4,true);
		$criteria->compare('col5',$this->col5,true);
		$criteria->compare('col6',$this->col6,true);
		$criteria->compare('col7',$this->col7,true);
		$criteria->compare('col8',$this->col8,true);
		$criteria->compare('col9',$this->col9,true);
		$criteria->compare('col10',$this->col10,true);
		$criteria->compare('col11',$this->col11,true);
		$criteria->compare('col12',$this->col12,true);
		$criteria->compare('col13',$this->col13,true);
		$criteria->compare('col14',$this->col14,true);
		$criteria->compare('col15',$this->col15,true);
		$criteria->compare('col16',$this->col16,true);
		$criteria->compare('col17',$this->col17,true);
		$criteria->compare('col18',$this->col18,true);
		$criteria->compare('col19',$this->col19,true);
		$criteria->compare('col20',$this->col20,true);
		$criteria->compare('col21',$this->col21,true);
		$criteria->compare('col22',$this->col22,true);
		$criteria->compare('col23',$this->col23,true);
		$criteria->compare('col24',$this->col24,true);
		$criteria->compare('col25',$this->col25,true);
		$criteria->compare('col26',$this->col26,true);
		$criteria->compare('col27',$this->col27,true);
		$criteria->compare('col28',$this->col28,true);
		$criteria->compare('col29',$this->col29,true);
		$criteria->compare('col30',$this->col30,true);
		$criteria->compare('col31',$this->col31,true);
		$criteria->compare('col32',$this->col32,true);
		$criteria->compare('col33',$this->col33,true);
		$criteria->compare('col34',$this->col34,true);
		$criteria->compare('col35',$this->col35,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TempImport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
