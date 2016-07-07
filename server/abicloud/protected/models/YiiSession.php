<?php

/**
 * This is the model class for table "{{YiiSession}}".
 *
 * The followings are the available columns in table '{{YiiSession}}':
 * @property string $id
 * @property integer $uid
 * @property string $ip
 * @property integer $expire
 * @property string $data
 */
class YiiSession extends PSActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{YiiSession}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('uid, expire', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>32),
			array('ip', 'length', 'max'=>64),
			array('data', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, ip, expire, data', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'uid' => 'Uid',
			'ip' => 'Ip',
			'expire' => 'Expire',
			'data' => 'Data',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('expire',$this->expire);
		$criteria->compare('data',$this->data,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return YiiSession the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getExpiredUids($delay_sec = 3) {
            $criteria =new CDbCriteria;
            $criteria->select = 'uid';
            $criteria->distinct = true;
            $criteria->addCondition('uid > 0 and expire < ' . (time() - $delay_sec));

            $uids = array();
            $expireds = $this->findAll($criteria);
            if(!is_null($expireds) && !empty($expireds)) {
                foreach ($expireds as $key => $user) {
                    $uids[] = $user->uid;
                }
            }
            return $uids;
        }
        
        public function getValidUids($delay_sec = 3) {
            $criteria =new CDbCriteria;
            $criteria->select = 'uid';
            $criteria->distinct = true;
            $criteria->addCondition('uid > 0 and expire > ' . (time() - $delay_sec));

            $uids = array();
            $expireds = $this->findAll($criteria);
            if(!is_null($expireds) && !empty($expireds)) {
                foreach ($expireds as $key => $user) {
                    $uids[] = $user->uid;
                }
            }
            return $uids;
        }
        
        public function getIdleUids($idle_sec = 300) {
            $criteria =new CDbCriteria;
            $criteria->select = 'uid';
            $criteria->distinct = true;
            $criteria->addCondition('uid > 0 and expire < ' . (time() + DBHttpSession::SESSION_TIMEOUT - $idle_sec));

            $uids = array();
            try {
                $expireds = $this->findAll($criteria);
                if(!is_null($expireds) && !empty($expireds)) {
                    foreach ($expireds as $key => $user) {
                        $uids[] = $user->uid;
                    }
                }
            } catch (Exception $ex) {

            }
            return $uids;
        }
}
