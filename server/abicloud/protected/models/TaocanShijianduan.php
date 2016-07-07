<?php

/**
 * This is the model class for table "{{taocan_shijianduan}}".
 *
 * The followings are the available columns in table '{{taocan_shijianduan}}':
 * @property integer $id
 * @property integer $ktvid
 * @property integer $shijianduantype
 * @property string $starttime
 * @property string $endtime
 * @property integer $ciri
 */
class TaocanShijianduan extends PSActiveRecord {
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{taocan_shijianduan}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ktvid, shijianduantype, starttime, endtime', 'required'),
			array('ktvid, shijianduantype, ciri', 'numerical', 'integerOnly' => true),
			array('starttime, endtime', 'length', 'max' => 20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ktvid, shijianduantype, starttime, endtime, ciri', 'safe', 'on' => 'search'),
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
			'shijianduantype' => 'Shijianduantype',
			'starttime' => 'Starttime',
			'endtime' => 'Endtime',
			'ciri' => 'Ciri',
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
		$criteria->compare('shijianduantype', $this->shijianduantype);
		$criteria->compare('starttime', $this->starttime, true);
		$criteria->compare('endtime', $this->endtime, true);
		$criteria->compare('ciri', $this->ciri);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TaocanShijianduan the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function getCourses($id = '') {
		if ($id != '') {
			$_list = $this->findAllByAttributes(array('ktvid' => $id, 'status' => 1));
			$list = array();
			foreach ($_list as $key => $value) {
				$list[] = $this->getCourseInfo($value);
			}
			return $list;
		}
	}
	protected function getCourseInfo($value) {
		return array(
			'id' => intval($value['id']),
			'name' => $this->getShijiandanName($value['shijianduantype']),
			'starttime' => array('time' => $value['starttime'], 'ciri' => intval($value['ciri_starttime'])),
			'endtime' => array('time' => $value['endtime'], 'ciri' => intval($value['ciri'])),
			'show' => intval($value['shouye']),
			'is_hjd' => $value['shijianduantype'] == 3 ? 1 : 0,
			'is_ymc' => $value['shijianduantype'] == 4 ? 1 : 0,
		);
		// "name":"黄金场","starttime":"20:00","endtime":"02:00","show":0,"id":1
	}
	protected function getShijiandanName($id = '', $key = 'name') {
		if ($id != '') {
			return TaocanShijianduanType::model()->getNamebyid($id, $key);
		}
	}
}
