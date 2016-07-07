<?php

/**
 * This is the model class for table "{{gifts}}".
 *
 * The followings are the available columns in table '{{gifts}}':
 * @property integer $id
 * @property string $product_id
 * @property string $product_mainpic
 * @property integer $product_issell
 * @property string $product_cata5
 * @property string $productsale_name
 * @property string $productsale_spec
 * @property string $productsale_subname
 * @property string $productsale_goodsno
 * @property string $productsale_partnum
 * @property string $productsale_barcode
 * @property string $productsale_abstr
 * @property string $productsale_brand
 * @property string $productsale_producing
 * @property string $productsale_size
 * @property string $productsale_supplyprice
 * @property integer $productsale_webprice
 * @property integer $productsale_bussprice
 * @property integer $productsale_insideprice
 * @property integer $productsale_custmprice
 * @property integer $productsale_discprice
 * @property integer $productsale_points
 * @property integer $productsale_respoints
 * @property integer $productsale_discount
 * @property string $productsale_unitweight
 * @property string $productsale_unitvolume
 * @property string $productsale_declaredvalue
 * @property string $productsale_cata1
 * @property string $productsale_cata2
 * @property string $productsale_cata3
 * @property string $productsale_cata4
 * @property string $productsale_cata5
 * @property string $productsale_cid
 * @property string $productsale_cidout
 * @property string $productsale_cont1
 * @property string $productsale_cont2
 * @property string $productsale_cont3
 * @property string $productsale_cont4
 * @property string $productsale_cont5
 * @property string $productsale_recommend
 * @property integer $productsale_issell
 * @property integer $goods_sum
 * @property integer $goods_score
 * @property integer $waresum
 * @property string $supl_id
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 * @property integer $create_user_id
 */
class Gifts extends PSActiveRecord {
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{gifts}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_issell, productsale_webprice, productsale_bussprice, productsale_insideprice, productsale_custmprice, productsale_discprice, productsale_points, productsale_respoints, productsale_discount, productsale_issell, goods_sum, goods_score, waresum, status, create_user_id', 'numerical', 'integerOnly' => true),
			array('product_id', 'length', 'max' => 15),
			array('product_mainpic, product_cata5, productsale_name, productsale_spec, productsale_subname, productsale_partnum, productsale_supplyprice, productsale_cata3, productsale_cata4, productsale_cata5, productsale_cid, productsale_cidout, productsale_recommend, supl_id', 'length', 'max' => 100),
			array('productsale_goodsno, productsale_barcode, productsale_abstr, productsale_brand, productsale_producing, productsale_size, productsale_unitweight, productsale_unitvolume, productsale_declaredvalue, productsale_cata1, productsale_cata2', 'length', 'max' => 20),
			array('productsale_cont1, productsale_cont2, productsale_cont3, productsale_cont4, productsale_cont5, create_time, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, product_id, product_mainpic, product_issell, product_cata5, productsale_name, productsale_spec, productsale_subname, productsale_goodsno, productsale_partnum, productsale_barcode, productsale_abstr, productsale_brand, productsale_producing, productsale_size, productsale_supplyprice, productsale_webprice, productsale_bussprice, productsale_insideprice, productsale_custmprice, productsale_discprice, productsale_points, productsale_respoints, productsale_discount, productsale_unitweight, productsale_unitvolume, productsale_declaredvalue, productsale_cata1, productsale_cata2, productsale_cata3, productsale_cata4, productsale_cata5, productsale_cid, productsale_cidout, productsale_cont1, productsale_cont2, productsale_cont3, productsale_cont4, productsale_cont5, productsale_recommend, productsale_issell, goods_sum, goods_score, waresum, supl_id, status, create_time, update_time, create_user_id', 'safe', 'on' => 'search'),
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
			'product_id' => 'Product',
			'product_mainpic' => 'Product Mainpic',
			'product_issell' => 'Product Issell',
			'product_cata5' => 'Product Cata5',
			'productsale_name' => 'Productsale Name',
			'productsale_spec' => 'Productsale Spec',
			'productsale_subname' => 'Productsale Subname',
			'productsale_goodsno' => 'Productsale Goodsno',
			'productsale_partnum' => 'Productsale Partnum',
			'productsale_barcode' => 'Productsale Barcode',
			'productsale_abstr' => 'Productsale Abstr',
			'productsale_brand' => 'Productsale Brand',
			'productsale_producing' => 'Productsale Producing',
			'productsale_size' => 'Productsale Size',
			'productsale_supplyprice' => 'Productsale Supplyprice',
			'productsale_webprice' => 'Productsale Webprice',
			'productsale_bussprice' => 'Productsale Bussprice',
			'productsale_insideprice' => 'Productsale Insideprice',
			'productsale_custmprice' => 'Productsale Custmprice',
			'productsale_discprice' => 'Productsale Discprice',
			'productsale_points' => 'Productsale Points',
			'productsale_respoints' => 'Productsale Respoints',
			'productsale_discount' => 'Productsale Discount',
			'productsale_unitweight' => 'Productsale Unitweight',
			'productsale_unitvolume' => 'Productsale Unitvolume',
			'productsale_declaredvalue' => 'Productsale Declaredvalue',
			'productsale_cata1' => 'Productsale Cata1',
			'productsale_cata2' => 'Productsale Cata2',
			'productsale_cata3' => 'Productsale Cata3',
			'productsale_cata4' => 'Productsale Cata4',
			'productsale_cata5' => 'Productsale Cata5',
			'productsale_cid' => 'Productsale Cid',
			'productsale_cidout' => 'Productsale Cidout',
			'productsale_cont1' => 'Productsale Cont1',
			'productsale_cont2' => 'Productsale Cont2',
			'productsale_cont3' => 'Productsale Cont3',
			'productsale_cont4' => 'Productsale Cont4',
			'productsale_cont5' => 'Productsale Cont5',
			'productsale_recommend' => 'Productsale Recommend',
			'productsale_issell' => 'Productsale Issell',
			'goods_sum' => 'Goods Sum',
			'goods_score' => 'Goods Score',
			'waresum' => 'Waresum',
			'supl_id' => 'Supl',
			'status' => 'Status',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'create_user_id' => 'Create User',
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
		$criteria->compare('product_id', $this->product_id, true);
		$criteria->compare('product_mainpic', $this->product_mainpic, true);
		$criteria->compare('product_issell', $this->product_issell);
		$criteria->compare('product_cata5', $this->product_cata5, true);
		$criteria->compare('productsale_name', $this->productsale_name, true);
		$criteria->compare('productsale_spec', $this->productsale_spec, true);
		$criteria->compare('productsale_subname', $this->productsale_subname, true);
		$criteria->compare('productsale_goodsno', $this->productsale_goodsno, true);
		$criteria->compare('productsale_partnum', $this->productsale_partnum, true);
		$criteria->compare('productsale_barcode', $this->productsale_barcode, true);
		$criteria->compare('productsale_abstr', $this->productsale_abstr, true);
		$criteria->compare('productsale_brand', $this->productsale_brand, true);
		$criteria->compare('productsale_producing', $this->productsale_producing, true);
		$criteria->compare('productsale_size', $this->productsale_size, true);
		$criteria->compare('productsale_supplyprice', $this->productsale_supplyprice, true);
		$criteria->compare('productsale_webprice', $this->productsale_webprice);
		$criteria->compare('productsale_bussprice', $this->productsale_bussprice);
		$criteria->compare('productsale_insideprice', $this->productsale_insideprice);
		$criteria->compare('productsale_custmprice', $this->productsale_custmprice);
		$criteria->compare('productsale_discprice', $this->productsale_discprice);
		$criteria->compare('productsale_points', $this->productsale_points);
		$criteria->compare('productsale_respoints', $this->productsale_respoints);
		$criteria->compare('productsale_discount', $this->productsale_discount);
		$criteria->compare('productsale_unitweight', $this->productsale_unitweight, true);
		$criteria->compare('productsale_unitvolume', $this->productsale_unitvolume, true);
		$criteria->compare('productsale_declaredvalue', $this->productsale_declaredvalue, true);
		$criteria->compare('productsale_cata1', $this->productsale_cata1, true);
		$criteria->compare('productsale_cata2', $this->productsale_cata2, true);
		$criteria->compare('productsale_cata3', $this->productsale_cata3, true);
		$criteria->compare('productsale_cata4', $this->productsale_cata4, true);
		$criteria->compare('productsale_cata5', $this->productsale_cata5, true);
		$criteria->compare('productsale_cid', $this->productsale_cid, true);
		$criteria->compare('productsale_cidout', $this->productsale_cidout, true);
		$criteria->compare('productsale_cont1', $this->productsale_cont1, true);
		$criteria->compare('productsale_cont2', $this->productsale_cont2, true);
		$criteria->compare('productsale_cont3', $this->productsale_cont3, true);
		$criteria->compare('productsale_cont4', $this->productsale_cont4, true);
		$criteria->compare('productsale_cont5', $this->productsale_cont5, true);
		$criteria->compare('productsale_recommend', $this->productsale_recommend, true);
		$criteria->compare('productsale_issell', $this->productsale_issell);
		$criteria->compare('goods_sum', $this->goods_sum);
		$criteria->compare('goods_score', $this->goods_score);
		$criteria->compare('waresum', $this->waresum);
		$criteria->compare('supl_id', $this->supl_id, true);
		$criteria->compare('status', $this->status);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('update_time', $this->update_time, true);
		$criteria->compare('create_user_id', $this->create_user_id);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Gifts the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function giftlist($type = 1, $_offset = 0, $_limit = 100, $_order = 'productsale_points asc') {

		$_list = $this->findAllByAttributes(array('type' => $type, 'product_issell' => 0), array('offset' => $_offset, 'limit' => $_limit, 'order' => $_order . ',update_time desc'));

		if (!empty($_list) && is_array($_list)) {
			return $this->foreachList($_list);
		}
		return array();
	}
	public function giftdetail($id) {
		$val = $this->findByAttributes(array('product_id' => $id));
		if ($val == NULL) {
			return null;
		}
		return $this->getGiftInfo($val);

	}
	protected function foreachList($_list = array()) {
		$_gift_list = array();
		foreach ($_list as $key => $val) {
			$giftinfo = $this->getGiftInfo($val);
			if ($giftinfo != null) {
				$_gift_list[] = $this->getGiftInfo($val);
			}

		}
		return $_gift_list;
	}
	protected function getGiftInfo($val) {
		$_gift_info = array(
			'product_id' => $val->product_id,
			'product_mainpic' => $val->product_mainpic,
			'product_issell' => $val->product_issell,
			'product_cata5' => $val->product_cata5,
			'productsale_name' => $val->productsale_name,
			'productsale_spec' => $val->productsale_spec,
			'productsale_subname' => $val->productsale_subname,
			'productsale_goodsno' => $val->productsale_goodsno,
			'productsale_partnum' => $val->productsale_partnum,
			'productsale_barcode' => $val->productsale_barcode,
			'productsale_abstr' => $val->productsale_abstr,
			'productsale_brand' => $val->productsale_brand,
			'productsale_producing' => $val->productsale_producing,
			'productsale_size' => $val->productsale_size,
			'productsale_supplyprice' => $val->productsale_supplyprice,
			'productsale_webprice' => $val->productsale_webprice,
			'productsale_bussprice' => $val->productsale_bussprice,
			'productsale_insideprice' => $val->productsale_insideprice,
			'productsale_custmprice' => $val->productsale_custmprice,
			'productsale_discprice' => $val->productsale_discprice,
			'productsale_points' => $val->productsale_points,
			'productsale_respoints' => $val->productsale_respoints,
			'productsale_discount' => $val->productsale_discount,
			'productsale_unitweight' => $val->productsale_unitweight,
			'productsale_unitvolume' => $val->productsale_unitvolume,
			'productsale_declaredvalue' => $val->productsale_declaredvalue,
			'productsale_cata1' => $val->productsale_cata1,
			'productsale_cata2' => $val->productsale_cata2,
			'productsale_cata3' => $val->productsale_cata3,
			'productsale_cata4' => $val->productsale_cata4,
			'productsale_cata5' => $val->productsale_cata5,
			'productsale_cid' => $val->productsale_cid,
			'productsale_cidout' => $val->productsale_cidout,
			'productsale_cont1' => $val->productsale_cont1,
			'productsale_cont2' => $val->productsale_cont2,
			'productsale_cont3' => $val->productsale_cont3,
			'productsale_cont4' => $val->productsale_cont4,
			'productsale_cont5' => $val->productsale_cont5,
			'productsale_recommend' => $val->productsale_recommend,
			'productsale_issell' => $val->productsale_issell,
			'goods_sum' => $val->goods_sum,
			'goods_score' => $val->goods_score,
		);
		if ($val->is_shuliangxianzhi == '0' && $val->is_shijianxianzhi == '0') {
			return $_gift_info;
		} elseif ($val->is_shuliangxianzhi != '0' && $val->product_count < 0) {
			return null;
		} elseif ($val->is_shijianxianzhi != '0' && $val->xiajiashijiandian < time()) {
			return null;
		} else {
			return $_gift_info;
		}

	}

	public function count_jian($product_id) {
		$prod = self::model()->findByAttributes(array('product_id' => $product_id));
		// var_dump($prod);
		if ($prod != NULL) {
			if ($prod->is_shuliangxianzhi == '1') {
				$prod->product_count = $prod->product_count - 1;
				if ($prod->save()) {
					return true;
				}
			} else {
				return false;
			}
		}

	}

	public function getPointsbyid($giftid = '') {
		$gift = self::model()->findByAttributes(array('product_id' => $giftid));
		return $gift->productsale_points;
	}
}

// CREATE TABLE `ac_gifts` (
//   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品ID',
//   `product_id` varchar(15) NOT NULL COMMENT '产品ID',
//   `productsale_name` varchar(100) NOT NULL COMMENT '产品名称',
//   `product_mainpic` varchar(100) NOT NULL COMMENT '产品主图',
//   `product_issell` tinyint(1) NOT NULL COMMENT '销售状态',
//   `product_cata5` varchar(100) NOT NULL COMMENT '产品属性，0单品，1套装',
//   `productsale_spec` varchar(100) NOT NULL COMMENT '产品规格',
//   `productsale_partnum` varchar(100) NOT NULL COMMENT '编码',
//   `productsale_subname` varchar(100) NOT NULL COMMENT '副名称',
//   `productsale_supplyprice` varchar(100) NOT NULL COMMENT '成本价',
//   `productsale_webprice` int(11) NOT NULL COMMENT '网站价',
//   `productsale_bussprice` int(11) NOT NULL COMMENT '市场价',
//   `productsale_insideprice` int(11) NOT NULL COMMENT '内部价',
//   `productsale_custmprice` int(11) NOT NULL COMMENT '报客户价',
//   `productsale_discprice` int(11) NOT NULL COMMENT '折扣金额',
//   `productsale_points` int(11) NOT NULL COMMENT '积分值',
//   `productsale_respoints` int(11) NOT NULL COMMENT '返积分',
//   `productsale_discount` int(11) NOT NULL COMMENT '折扣率',
//   `productsale_issell` tinyint(1) NOT NULL COMMENT '销售状态',
//   `productsale_cata3` varchar(100) NOT NULL COMMENT '虚拟，实物',
//   `goods_sum` int(11) NOT NULL COMMENT '销售数量',
//   `goods_score` int(11) NOT NULL COMMENT '评分值',
//   `waresum` int(11) NOT NULL COMMENT '库存总数量',
//   `supl_id` varchar(100) NOT NULL COMMENT '供应商ID',
//   `status` tinyint(1) NOT NULL COMMENT '产品状态',
//   `create_time` datetime NOT NULL COMMENT '新建时间',
//   `update_time` datetime NOT NULL COMMENT '更新时间',
//   `create_user_id` int(11) DEFAULT NULL,
//   `update_user_id` int(11) DEFAULT NULL,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;