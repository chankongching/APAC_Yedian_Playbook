<?php

/**
 * This is the model class for table "{{platform_user}}".
 *
 * The followings are the available columns in table '{{platform_user}}':
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $mobile
 * @property string $password
 * @property string $role
 * @property string $openid
 * @property string $token
 * @property integer $type
 * @property string $profile_data
 * @property string $display_name
 * @property string $avatar_url
 * @property string $last_login_time
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property string $auth_type
 *
 * The followings are the available model relations:
 * @property CheckinUser[] $checkinUsers
 */
class PlatformUser extends PSActiveRecord
{

    public $password_repeat;
    public $password_changed = true;

    protected $authtype_options;
    public $cid = '';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{platform_user}}';
    }

    public function init()
    {
        parent::init();
        $this->authtype_options = array(
            'KTV' => Yii::t('PlatformUser', 'KTV User'),
            //'KTVTABLE' => Yii::t('PlatformUser', 'KTV TABLE'),
            //'KTVPAD' => Yii::t('PlatformUser', 'KTV PAD'),
            'KTVSTAFF' => Yii::t('PlatformUser', 'KTV STAFF'),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        if (Yii::app()->user->id == $this->id) {
            return array(
                array('email', 'safe'),
                array('email', 'unique'),
                array('email', 'email'),
                //array('username, password', 'required'),
                //array('type, create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
                array('username, email, mobile, password, openid, token, display_name, avatar_url', 'length', 'max' => 255),
                array('role', 'length', 'max' => 64),
                array('auth_type', 'length', 'max' => 32),
                array('profile_data, last_login_time, create_time, update_time', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, email, mobile, password, role, openid, token, type, profile_data, display_name, avatar_url, last_login_time, create_time, create_user_id, update_time, update_user_id, auth_type', 'safe', 'on' => 'search'),
            );
        }
        if ($this->isNewRecord) {
            return array(
                array('username', 'required'),
                array('password, password_repeat, role', 'required'),
                //array('create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
                array('username, email, password, mobile, openid, token, display_name, avatar_url', 'length', 'max' => 255),
                array('role', 'length', 'max' => 64),
                array('auth_type', 'length', 'max' => 32),
                //array('last_login_time, create_time, update_time', 'safe'),
                array('email, username', 'unique'),
                array('email', 'email'),
                array('password', 'compare'),
                array('password_repeat', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, email, password, role, last_login_time, create_time, create_user_id, update_time, update_user_id, auth_type', 'safe', 'on' => 'search'),
            );
        } else {
            return array(
                array('username', 'required'),
                array('role', 'required'),
                //array('create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
                array('username, email, mobile, openid, token, display_name, avatar_url', 'length', 'max' => 255),
                array('role', 'length', 'max' => 64),
                array('auth_type', 'length', 'max' => 32),
                //array('last_login_time, create_time, update_time', 'safe'),
                //array('email, username', 'unique'),
                array('username', 'unique'),
                //array('email', 'email'),
                array('email', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, email, password, role, last_login_time, create_time, create_user_id, update_time, update_user_id, auth_type', 'safe', 'on' => 'search'),
            );
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'checkinUsers' => array(self::HAS_MANY, 'CheckinUser', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('PlatformUser', 'ID'),
            'username' => Yii::t('PlatformUser', 'Username'),
            'email' => Yii::t('PlatformUser', 'Email'),
            'mobile' => Yii::t('PlatformUser', 'Mobile'),
            'password' => Yii::t('PlatformUser', 'Password'),
            'role' => Yii::t('PlatformUser', 'Role'),
            'openid' => Yii::t('PlatformUser', 'Openid'),
            'token' => Yii::t('PlatformUser', 'Token'),
            'type' => Yii::t('PlatformUser', 'Type'),
            'profile_data' => Yii::t('PlatformUser', 'Profile Data'),
            'display_name' => Yii::t('PlatformUser', 'Display Name'),
            'avatar_url' => Yii::t('PlatformUser', 'Avatar Url'),
            'last_login_time' => Yii::t('PlatformUser', 'Last Login Time'),
            'create_time' => Yii::t('PlatformUser', 'Create Time'),
            'create_user_id' => Yii::t('PlatformUser', 'Create User'),
            'update_time' => Yii::t('PlatformUser', 'Update Time'),
            'update_user_id' => Yii::t('PlatformUser', 'Update User'),
            'auth_type' => Yii::t('PlatformUser', 'Auth Type'),
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('role', $this->role, true);
        $criteria->compare('openid', $this->openid, true);
        $criteria->compare('token', $this->token, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('profile_data', $this->profile_data, true);
        $criteria->compare('display_name', $this->display_name, true);
        $criteria->compare('avatar_url', $this->avatar_url, true);
        $criteria->compare('last_login_time', $this->last_login_time, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('create_user_id', $this->create_user_id);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('update_user_id', $this->update_user_id);
        $criteria->compare('auth_type', $this->auth_type, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PlatformUser the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * apply a hash on the password before we store it in the database
     */
    protected function afterValidate()
    {
        parent::afterValidate();
        if (!$this->hasErrors()) {
            if ($this->password_changed) {
                $this->password = $this->hashPassword($this->password);
            } else {
                //$old_password = User::model()->findByPk($this->id);
                $old_password = $this->findByPk($this->id);
                $this->password = $old_password->password;
            }
        }
    }

    protected function afterFind()
    {
        parent::afterFind();
        if (!empty($this->profile_data)) {
            $_profile = unserialize($this->profile_data);
            if (!empty($_profile) && is_array($_profile) && isset($_profile['cid'])) {
                $this->cid = $_profile['cid'];
            }
        }
    }

    /**
     * Generates the password hash.
     * @param string password
     * @return string hash
     */
    public function hashPassword($password)
    {
        if (empty($password)) {
            return $password;
        }

        //return md5($password);
        return PasswordHash::create_hash($password);
    }

    /**
     * Checks if the given password is correct.
     * @param string the password to be validated
     * @return boolean whether the password is valid
     */
    public function validatePassword($password)
    {
        //die($this->hashPassword($password));
        //return $this->hashPassword($password) === $this->password;
        return PasswordHash::validate_password($password, $this->password);
    }

    /**
     * Returns an array of available roles in which a user can be placed when being added to a project
     */
    public static function getUserRoleOptions()
    {
        /* @var $roles array */
        $roles = Yii::app()->authManager->getRoles();
        $listData = array();
        if (!empty($roles)) {
            foreach ($roles as $role) {
                $value = CHtml::value($role, 'name');
                $text = CHtml::value($role, 'name');
                $listData[$value] = Yii::t('user', $text);
            }
        }
        return $listData;
        //return CHtml::listData($roles, 'name', 'name');
    }

    public static function assignUserRole($role, $userId)
    {
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

    public function countNew()
    {
        $pre3days = time() - 3600 * 24 * 7;
        $count = User::model()->count('create_time > :pre3days', array(':pre3days' => date('Y-m-d H:i:s', $pre3days)));
        return $count;
    }

    /**
     * @param string $userName
     * @param string $password
     *
     * @return PlatformUser
     */
    public function authenticate($userName, $password)
    {
        /** @var User $_user */
        $_user = $this->findByAttributes(array('username' => $userName));

        if (empty($_user)) {
            self::log('Platform login fail: ' . $userName . ' NOT FOUND', CLogger::LEVEL_ERROR);

            return null;
        }

        if (!$this->validatePassword($password)) {
            self::log('Platform password verify fail: ' . $userName, CLogger::LEVEL_ERROR);

            return null;
        }

        self::log('Platform local user auth (via password): ' . $userName);

        return $_user;
    }

    /**
     * @param string $userName
     * @param string $auth_type default KTV
     *
     * @return PlatformUser
     */
    public function validateUserByName($userName, $auth_type = ApiController::DEFAULT_AUTH_TYPE)
    {
        /** @var PlatformUser $_user */
        $_user = $this->findByAttributes(array('username' => $userName, 'auth_type' => $auth_type));

        if (empty($_user)) {
            self::log('Platform login fail: ' . $userName . ' NOT FOUND', CLogger::LEVEL_ERROR);

            return null;
        }

        self::log('Platform local user exists: ' . $userName);

		return $_user;
	}

	/**
	 * @param string  $userName
	 * @param string  $password
	 * @param integer $duration
	 * @param string  $auth_type
	 *
	 * @throws Exception
	 * @return boolean | PlatformUser
	 */
	public function loginRequest($userName, $password, $duration = 0, $auth_type = ApiController::DEFAULT_AUTH_TYPE) {
		if (empty($userName)) {
			throw new Exception("Login request is missing required username.");
		}

		if (empty($password)) {
			throw new Exception("Login request is missing required password.");
		}

		$_identity = new PlatformUserIdentity($userName, $password);
		$_identity->_auth_type = $auth_type;

		if (!$_identity->authenticate()) {
			throw new Exception("Invalid user name and password combination.");
		}

		if (CUserIdentity::ERROR_NONE != $_identity->errorCode) {
			throw new Exception("Failed to authenticate user. code = " . $_identity->errorCode);
		}

		if (!Yii::app()->user->login($_identity, $duration)) {
			throw new Exception('Failed to login user.');
		}

		/** @var PlatformUser $_user */
		if (null === ($_user = $_identity->getUser())) {
			// bad user object
			throw new Exception('The user session contains no data.');
		}

		return $_user;
	}

	public function getAuthTypeOptions() {
		return $this->authtype_options;
	}

    public function findUserByOpenid($openid)
    {
        if ($openid != null) {
            $userinfo = $this->findByAttributes(array('openid' => $openid));
            if ($userinfo != null) {
                return $userinfo['id'];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getUserOpenid($userid){
        $userinfo = $this->findByPk($userid);
        if($userinfo!=null){
            return $userinfo['openid'];
        }else{
            return null;
        }
    }

}
