<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $mobile
 * @property string $password
 * @property string $last_login_time
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property string $role
 * @property integer $status
 */
class User extends PSActiveRecord {

    public $password_repeat;
    public $password_changed = true;
    protected $status_options;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user}}';
    }

    public function init() {
        parent::init();
        $this->status_options = array(0 => Yii::t('user', 'Disabled'), 1 => Yii::t('user', 'Enabled'));
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        if (Yii::app()->user->id == $this->id) {
            return array(
                array('email', 'required'),
                //array('password, password_repeat, role', 'required'),
                //array('create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
                array('status', 'numerical', 'integerOnly' => true),
                array('email, mobile', 'length', 'max' => 255),
                //array('last_login_time, create_time, update_time', 'safe'),
                array('email', 'unique'),
                array('email', 'email'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, email, password, role, status, last_login_time, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
            );
        }

        if ($this->isNewRecord) {
            return array(
                array('username, email', 'required'),
                array('password, password_repeat, role', 'required'),
                //array('create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
                array('status', 'numerical', 'integerOnly' => true),
                array('username, email, password, mobile', 'length', 'max' => 255),
                array('role', 'length', 'max' => 64),
                //array('last_login_time, create_time, update_time', 'safe'),
                array('email, username', 'unique'),
                array('email', 'email'),
                array('password', 'compare'),
                array('password_repeat', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, email, password, role, status, last_login_time, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
            );
        } else {
            return array(
                array('username, email', 'required'),
                array('role', 'required'),
                //array('create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
                array('status', 'numerical', 'integerOnly' => true),
                array('username, email, mobile', 'length', 'max' => 255),
                array('role', 'length', 'max' => 64),
                //array('last_login_time, create_time, update_time', 'safe'),
                array('email, username', 'unique'),
                array('email', 'email'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, email, password, role, status, last_login_time, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
            );
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
                //'authItem' => array(self::BELONGS_TO, 'AuthItem', 'role'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('user', 'ID'),
            'username' => Yii::t('user', 'Username'),
            'email' => Yii::t('user', 'Email'),
            'mobile' => Yii::t('user', 'Mobile'),
            'password' => Yii::t('user', 'Password'),
            'status' => Yii::t('user', 'Status'),
            'last_login_time' => Yii::t('user', 'Last Login Time'),
            'create_time' => Yii::t('user', 'Create Time'),
            'create_user_id' => Yii::t('user', 'Create User'),
            'update_time' => Yii::t('user', 'Update Time'),
            'update_user_id' => Yii::t('user', 'Update User'),
            'role' => Yii::t('user', 'Role'),
            'password_repeat' => Yii::t('user', 'Password Repeat'),
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
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('last_login_time', $this->last_login_time, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('create_user_id', $this->create_user_id);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('update_user_id', $this->update_user_id);
        $criteria->compare('role', $this->role, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * apply a hash on the password before we store it in the database
     */
    protected function afterValidate() {
        parent::afterValidate();
        if (!$this->hasErrors()) {
            if ($this->password_changed) {
                $this->password = $this->hashPassword($this->password);
            } else {
                $old_password = User::model()->findByPk($this->id);
                $this->password = $old_password->password;
            }
        }
    }

    /**
     * Generates the password hash.
     * @param string password
     * @return string hash
     */
    public function hashPassword($password) {
        if (empty($password))
            return $password;
        //return md5($password);
        return PasswordHash::create_hash($password);
    }

    /**
     * Checks if the given password is correct.
     * @param string the password to be validated
     * @return boolean whether the password is valid
     */
    public function validatePassword($password) {
        //die($this->hashPassword($password));
        //return $this->hashPassword($password) === $this->password;
        return PasswordHash::validate_password($password, $this->password);
    }

    /**
     * Returns an array of available roles in which a user can be placed when being added to a project
     */
    public static function getUserRoleOptions() {
        /* @var $roles array */
        $roles = Yii::app()->authManager->getRoles();
        $listData = array();
        if (!empty($roles)) {
            foreach ($roles as $role) {
                $value = CHtml::value($role, 'name');
                //$text = CHtml::value($role, 'name');
                $text = CHtml::value($role, 'description');
                $listData[$value] = Yii::t('user', $text);
            }
        }
        return $listData;
        //return CHtml::listData($roles, 'name', 'name');
    }

    public static function assignUserRole($role, $userId) {
        // Assign role to user
        $auth = Yii::app()->authManager;
        if (!$auth->isAssigned($role, $userId)) {
            $auth_array = $auth->getAuthAssignments($userId);
            if (!empty($auth_array)) {
                foreach ($auth_array as $key => $obj) {
                    $auth->revoke($key, $userId);
                }
            }
            $auth->assign($role, $userId);
        }
    }

    public function countNew() {
        $pre3days = time() - 3600 * 24 * 7;
        $count = User::model()->count('create_time > :pre3days', array(':pre3days' => date('Y-m-d H:i:s', $pre3days)));
        return $count;
    }

    public function getStatusName($type) {
        if (isset($this->status_options[$type])) {
            return $this->status_options[$type];
        }
        return '';
    }

    public function getStatusOptions() {

        return $this->status_options;
    }
    
    public function isEnabled() {
        return ($this->status == 1);
    }
}
