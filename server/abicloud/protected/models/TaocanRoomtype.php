<?php

/**
 * This is the model class for table "{{taocan_roomtype}}".
 *
 * The followings are the available columns in table '{{taocan_roomtype}}':
 * @property integer $id
 * @property integer $ktvid
 * @property string $name
 * @property integer $count
 * @property string $des
 * @property integer $shouye
 */
class TaocanRoomtype extends PSActiveRecord {
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{taocan_roomtype}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ktvid, name, count, des', 'required'),
			array('ktvid, count, shouye', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 50),
			array('des', 'length', 'max' => 100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ktvid, name, count, des, shouye', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'ktvid' => 'Ktvid',
			'name' => 'Name',
			'count' => 'Count',
			'des' => 'Des',
			'shouye' => 'Shouye',
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
		$criteria->compare('ktvid', $this->ktvid);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('count', $this->count);
		$criteria->compare('des', $this->des, true);
		$criteria->compare('shouye', $this->shouye);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TaocanRoomtype the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function getRoomtype($id = '') {
		if ($id != '') {
			$_list = $this->findAllByAttributes(array('ktvid' => $id, 'status' => 1));
			$list = array();
			foreach ($_list as $key => $value) {
				$list[] = $this->getRoomtypeInfo($value);
			}
			return $list;
		}
	}
	protected function getRoomtypeInfo($value) {
		return array(
			'id' => intval($value['id']),
			'name' => $value['name'],
			'desc' => $value['des'] . '人',
			'des' => $value['des'],
			'show' => intval($value['shouye']),
		);
		// "name":"黄金场","starttime":"20:00","endtime":"02:00","show":0,"id":1
	}

	public function getRoomName($value = '') {
		if ($value != '') {
			$info = $this->findByPk($value);
			return $info->name;
		}
	}
}
