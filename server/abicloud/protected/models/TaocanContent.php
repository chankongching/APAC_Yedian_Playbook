<?php

/**
 * This is the model class for table "{{taocan_content}}".
 *
 * The followings are the available columns in table '{{taocan_content}}':
 * @property integer $id
 * @property integer $ktvid
 * @property integer $shijianduan
 * @property integer $roomtype
 * @property string $name
 * @property integer $dayofweek
 * @property integer $price
 * @property integer $member_price
 * @property integer $is_yd_price
 * @property string $desc
 * @property string $tiaokuan
 * @property integer $shouye
 * @property integer $mon
 * @property integer $tue
 * @property integer $wen
 * @property integer $thu
 * @property integer $fri
 * @property integer $sat
 * @property integer $sun
 */
class TaocanContent extends PSActiveRecord {
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{taocan_content}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ktvid, shijianduan, roomtype, name, dayofweek, price, member_price, desc, tiaokuan', 'required'),
			array('ktvid, shijianduan, roomtype, dayofweek, price, member_price, is_yd_price, shouye, mon, tue, wen, thu, fri, sat, sun', 'numerical', 'integerOnly' => true),
			array('name, tiaokuan', 'length', 'max' => 20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ktvid, shijianduan, roomtype, name, dayofweek, price, member_price, is_yd_price, desc, tiaokuan, shouye, mon, tue, wen, thu, fri, sat, sun', 'safe', 'on' => 'search'),
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
			'shijianduan' => 'Shijianduan',
			'roomtype' => 'Roomtype',
			'name' => 'Name',
			'dayofweek' => 'Dayofweek',
			'price' => 'Price',
			'member_price' => 'Member Price',
			'is_yd_price' => 'Is Yd Price',
			'desc' => 'Desc',
			'tiaokuan' => 'Tiaokuan',
			'shouye' => 'Shouye',
			'mon' => 'Mon',
			'tue' => 'Tue',
			'wen' => 'Wen',
			'thu' => 'Thu',
			'fri' => 'Fri',
			'sat' => 'Sat',
			'sun' => 'Sun',
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
		$criteria->compare('shijianduan', $this->shijianduan);
		$criteria->compare('roomtype', $this->roomtype);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('dayofweek', $this->dayofweek);
		$criteria->compare('price', $this->price);
		$criteria->compare('member_price', $this->member_price);
		$criteria->compare('is_yd_price', $this->is_yd_price);
		$criteria->compare('desc', $this->desc, true);
		$criteria->compare('tiaokuan', $this->tiaokuan, true);
		$criteria->compare('shouye', $this->shouye);
		$criteria->compare('mon', $this->mon);
		$criteria->compare('tue', $this->tue);
		$criteria->compare('wen', $this->wen);
		$criteria->compare('thu', $this->thu);
		$criteria->compare('fri', $this->fri);
		$criteria->compare('sat', $this->sat);
		$criteria->compare('sun', $this->sun);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TaocanContent the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function getList($_course, $_roomtype, $_days, $_limit = 100, $_offset = 0) {
		$criteria = new CDbCriteria();
		// $criteria->limit = $_limit;
		// $criteria->offset = $_offset;
		$criteria->addCondition("`shijianduan`=" . $_course);
		$criteria->addCondition("`roomtype`=" . $_roomtype);
		$criteria->addCondition($this->getDaysConditon($_days));
		// $criteria->addCondition("`shijianduan`=".$_course);
		$_list = $this->findAll($criteria);
		// $_list = $this->findAllByAttributes(array('ktvid' => 1));
		// var_dump($_list);die();
		$list = array();
		foreach ($_list as $key => $value) {
			$list[] = $this->getTaocanInfo($value);
		}
		return $list;
	}
	// public function getListCount($value = '') {
	// 	return 5;
	// }

	protected function getDaysConditon($day = '') {
		if ($day != '') {
			$daystr = strtolower(date('D', time() + 3600 * 24 * $day));
			$daystr = $daystr == 'wed' ? 'wen' : $daystr;
			return '`' . $daystr . '`' . '=1';
			// switch ($day) {
			// case 0:

			// 	break;
			// case 1:
			// 	# code...
			// 	break;
			// case 2:
			// 	# code...
			// 	break;
			// case 3:
			// 	# code...
			// 	break;
			// case 4:
			// 	# code...
			// 	break;
			// case 5:
			// 	# code...
			// 	break;
			// case 6:
			// 	# code...
			// 	break;
			// default:
			// 	# code...
			// 	break;
			// }
		}
	}

	public function getTaocanInfoById($value = '') {
		if ($value != '') {
			$info = $this->findByPk($value);
			$taocaninfo = array(
				'name' => $info->desc,
				'roomtype' => $info->roomtype,
				'price_yd' => $info->yd_price,
			);
			return $taocaninfo;
		}
	}
	protected function getTaocanInfo($value = '') {
		if ($value != '') {
			$typeinfo = $this->getTypeinfo($value['id']);
			return array(
				// 'name' => $value['name'],
				'name' => $value['desc'] . '（可使用兑酒券）',
				'id' => intval($value['id']),
				'price' => intval($value['price']),
				'price_yd' => intval($value['yd_price']),
				'show' => intval($value['shouye']),
				'type' => $typeinfo['type'],
				'longtime' => $typeinfo['longtime'],
				'pre_txt' => $typeinfo['type'] > 0 ? '欢唱' . $typeinfo['longtime'] . '小时，' : '全时段欢唱，',
				// 'roomtype' => $typeinfo['roomtype'],
			);
		}
	}

	protected function getTypeinfo($value = '') {
		if ($value != '') {
			$info = $this->findByPk($value);
			if ($info->shichang == 0) {
				return array('type' => 0, 'longtime' => 0);
			} else {
				return array('type' => 1, 'longtime' => intval($info->shichang));
			}
		}
	}

}
