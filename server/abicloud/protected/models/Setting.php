<?php

/**
 * This is the model class for table "{{setting}}".
 *
 * The followings are the available columns in table '{{setting}}':
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 */
class Setting extends PSActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{setting}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, content', 'required'),
            //array('create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
            array('name, content', 'length', 'max' => 255),
            //array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, content, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
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
            'id' => Yii::t('setting', 'ID'),
            'name' => Yii::t('setting', 'Name'),
            'content' => Yii::t('setting', 'Content'),
            'create_time' => Yii::t('setting', 'Create Time'),
            'create_user_id' => Yii::t('setting', 'Create User'),
            'update_time' => Yii::t('setting', 'Update Time'),
            'update_user_id' => Yii::t('setting', 'Update User'),
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('create_user_id', $this->create_user_id);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('update_user_id', $this->update_user_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Setting the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Login to API server
     * If success, User login token will be stored into app user state 'apitoken'
     * @param string $login_name the login_name to registered, optional, if empty, will read from db
     * @param string $password the password to login, optional, if empty, will read from db
     * @return array API Login success or not, and login message
     */
    public function apiLogin($login_name = '', $password = '') {
        set_time_limit(300);
        $success = FALSE;
        $msg = Yii::t('setting', 'API error!');

        if (empty($login_name)) {
            $usernameobj = $this->findBySql('SELECT content from {{setting}} WHERE name = ?', array('APIUsername'));
            if (!empty($usernameobj)) {
                $login_name = $usernameobj->content;
            }
        }
        if (empty($password)) {
            $passwordobj = $this->findBySql('SELECT content from {{setting}} WHERE name = ?', array('APIPassword'));
            if (!empty($passwordobj)) {
                $password = $passwordobj->content;
            }
        }

        if (!empty($login_name) && !empty($password)) {
            $rest = new RESTClient();
            $rest->initialize(array('server' => Yii::app()->params['API_USER_LOGIN']['url']));
            $json = $rest->post(Yii::app()->params['API_USER_LOGIN']['api'], array('identify' => $login_name, 'password' => $password));

            if (!empty($json)) {
                $result = json_decode($json, TRUE);
                if ($result !== NULL) {
                    if (isset($result['result']) && $result['result'] == 0) {
                        Yii::log($json, CLogger::LEVEL_TRACE);
                        $user = $result['user'];
                        $token = $result['token'];
                        $uuid = $user['uuid'];
                        $msg = $result['msg'] . '( UUID: ' . $uuid . ' , TOKEN: ' . $token . ' )';
                        // Update the token
                        $model = $this->findBySql('SELECT * from {{setting}} WHERE name = ?', array('APIToken'));
                        if (!empty($model)) {
                            $model->content = $token;
                            $model->save();
                        }
                        // Update the UUID
                        $model = $this->findBySql('SELECT * from {{setting}} WHERE name = ?', array('UserUUID'));
                        if (!empty($model)) {
                            $model->content = $uuid;
                            $model->save();
                        }
                        $this->refreshAPIState();
                        $success = TRUE;
                    } else {
                        Yii::log($json, CLogger::LEVEL_WARNING);
                        $msg = $result['msg'];
                    }
                } else {
                    $msg = Yii::t('setting', 'Result decode error!');
                }
            }
        }

        return array('success' => $success, 'msg' => $msg);
    }

    /**
     * Login to API server
     * If success, User login token will be stored into app user state 'apitoken'
     * @param string $login_name the login_name to registered, optional, if empty, will read from db
     * @param string $password the password to login, optional, if empty, will read from db
     * @return array API Login success or not, and login message
     */
    public function apiRegister($login_name = '', $password = '', $nick_name = '') {
        set_time_limit(300);
        $success = FALSE;
        $msg = Yii::t('setting', 'API error!');

        if (empty($login_name)) {
            $usernameobj = $this->findBySql('SELECT content from {{setting}} WHERE name = ?', array('APIUsername'));
            if (!empty($usernameobj)) {
                $login_name = $usernameobj->content;
            }
        }
        if (empty($password)) {
            $passwordobj = $this->findBySql('SELECT content from {{setting}} WHERE name = ?', array('APIPassword'));
            if (!empty($passwordobj)) {
                $password = $passwordobj->content;
            }
        }
        if (empty($nick_name)) {
            $nick_name = $login_name;
        }

        if (!empty($login_name) && !empty($password)) {
            $rest = new RESTClient();
            $rest->initialize(array('server' => Yii::app()->params['API_USER_DEF_REG']['url']));
            $rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            $json = $rest->post(Yii::app()->params['API_USER_DEF_REG']['api'], array('login_name' => $login_name, 'password' => $password, 'nick_name' => $nick_name));

            if (!empty($json)) {
                $result = json_decode($json, TRUE);
                if ($result !== NULL) {
                    if (isset($result['result']) && $result['result'] == 0) {
                        Yii::log($json, CLogger::LEVEL_TRACE);
                        $user = $result['user'];
                        $token = $result['token'];
                        $uuid = $user['uuid'];
                        $msg = $result['msg'] . '( UUID: ' . $uuid . ' , TOKEN: ' . $token . ' )';
                        // Update the token
                        $model = $this->findBySql('SELECT * from {{setting}} WHERE name = ?', array('APIToken'));
                        if (!empty($model)) {
                            $model->content = $token;
                            $model->save();
                        }
                        // Update the UUID
                        $model = $this->findBySql('SELECT * from {{setting}} WHERE name = ?', array('UserUUID'));
                        if (!empty($model)) {
                            $model->content = $uuid;
                            $model->save();
                        }
                        $this->refreshAPIState();
                        $success = TRUE;
                    } else {
                        Yii::log($json, CLogger::LEVEL_WARNING);
                        $msg = $result['msg'];
                    }
                } else {
                    $msg = Yii::t('setting', 'Result decode error!');
                }
            }
        }

        return array('success' => $success, 'msg' => $msg);
    }

    public function apiCategory() {
        set_time_limit(600);
        $success = FALSE;
        $msg = Yii::t('setting', 'API error!');

        $token = Yii::app()->user->getState('apitoken');
        $parent_uuid = Yii::app()->user->getState('apiuuid');
        if (empty($token)) {
            $ret_array = $this->apiLogin();
            if ($ret_array['success'] == FALSE) {
                return array('success' => $success, 'msg' => $msg);
            }
        }
        $token = Yii::app()->user->getState('apitoken');
        $parent_uuid = Yii::app()->user->getState('apiuuid');
        //$parent_uuid = (empty($parent_uuid) ? Yii::app()->params['category_root'] : $parent_uuid);
        if (empty($parent_uuid)) {
            return array('success' => $success, 'msg' => Yii::t('setting', 'Please register API user first!'));
        }

        $b_created_root = false;
        $b_created_ico = false;
        // get current category list first
        $rest = new RESTClient();
        $rest->initialize(array('server' => Yii::app()->params['API_FAMILY_SORT_LIST']['url']));
        $json = $rest->get(Yii::app()->params['API_FAMILY_SORT_LIST']['api'] . $parent_uuid . Yii::app()->params['API_FAMILY_SORT']['type'], array('start' => 0, 'limit' => 200, 'token' => $token, 'app_key' => Yii::app()->params['app_key'], 'merchant_id' => Yii::app()->params['merchant_id']));
        if (!empty($json)) {
            $result = json_decode($json, TRUE);
            if ($result !== NULL) {
                if (isset($result['result']) && $result['result'] == 0) {
                    Yii::log($json, CLogger::LEVEL_TRACE);
                    $list = $result['list'];
                    // Update the downloaded category
                    if (!empty($list)) {
                        foreach ($list as $key => $sort) {
                            if (in_array($sort['name'], array('CategoryRoot'))) {
                                $model = $this->findByAttributes(array('name' => 'CategoryRootUUID'));
                                if (!empty($model)) {
                                    $model->content = $sort['uuid'];
                                    $model->save();
                                } else {
                                    $model = new Setting;
                                    $model->name = 'CategoryRootUUID';
                                    $model->content = $sort['uuid'];
                                    $model->save();
                                }
                                $msg = ' Category ROOT UUID: ' . $sort['uuid'] . ' getted. ';
                                $b_created_root = true;
                            }
                            if (in_array($sort['name'], array('CategoryICORoot'))) {
                                $model = $this->findByAttributes(array('name' => 'CategoryIcoUUID'));
                                if (!empty($model)) {
                                    $model->content = $sort['uuid'];
                                    $model->save();
                                } else {
                                    $model = new Setting;
                                    $model->name = 'CategoryIcoUUID';
                                    $model->content = $sort['uuid'];
                                    $model->save();
                                }
                                $msg .= ' ICO ROOT UUID: ' . $sort['uuid'] . ' getted. ';
                                $b_created_ico = true;
                            }
                        }
                    }
                }
            }
        }

        $api_url = Yii::app()->params['API_FAMILY_SORT']['url'];
        $api_name = Yii::app()->params['API_FAMILY_SORT']['api'];
        $api_type = Yii::app()->params['API_FAMILY_SORT']['type'];

        if (!$b_created_root) {
            // Create category root
            $rest = new RESTClient();
            $rest->initialize(array('server' => $api_url));
            //$rest->language('UTF-8');
            $rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            $json = $rest->post($api_name . $api_type, array('name' => 'CategoryRoot', 'parent_uuid' => $parent_uuid, 'token' => $token, 'app_key' => Yii::app()->params['app_key'], 'merchant_id' => Yii::app()->params['merchant_id']));
            if (!empty($json)) {
                Yii::log($json);
                $result = json_decode($json, TRUE);
                if ($result !== NULL) {
                    if (isset($result['result']) && $result['result'] == 0) {
                        $sort = $result['sort'];
                        $uuid = $sort['uuid'];
                        $msg = ' Category ROOT ' . $result['msg'] . '( UUID: ' . $uuid . ' ) created. ';
                        $model = $this->findByAttributes(array('name' => 'CategoryRootUUID'));
                        if (!empty($model)) {
                            $model->content = $uuid;
                            $model->save();
                        } else {
                            $model = new Setting;
                            $model->name = 'CategoryRootUUID';
                            $model->content = $uuid;
                            $model->save();
                        }

                        $success = TRUE;
                    } else {
                        $msg = Yii::t('setting', 'API Call Failed!');
                    }
                } else {
                    $msg = Yii::t('setting', 'Result decode error!');
                }
            }
        }

        if (!$b_created_ico) {
            // Create category ico file root
            $rest = new RESTClient();
            $rest->initialize(array('server' => $api_url));
            //$rest->language('UTF-8');
            $rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            $json = $rest->post($api_name . $api_type, array('name' => 'CategoryICORoot', 'parent_uuid' => $parent_uuid, 'token' => $token, 'app_key' => Yii::app()->params['app_key'], 'merchant_id' => Yii::app()->params['merchant_id']));
            if (!empty($json)) {
                Yii::log($json);
                $result = json_decode($json, TRUE);
                if ($result !== NULL) {
                    if (isset($result['result']) && $result['result'] == 0) {
                        $sort = $result['sort'];
                        $uuid = $sort['uuid'];
                        $msg .= ' ICO ROOT ' . $result['msg'] . '( UUID: ' . $uuid . ' ) created. ';
                        $model = $this->findByAttributes(array('name' => 'CategoryIcoUUID'));
                        if (!empty($model)) {
                            $model->content = $uuid;
                            $model->save();
                        } else {
                            $model = new Setting;
                            $model->name = 'CategoryIcoUUID';
                            $model->content = $uuid;
                            $model->save();
                        }

                        $success = TRUE;
                    } else {
                        $msg = Yii::t('setting', 'API Call Failed!');
                    }
                } else {
                    $msg = Yii::t('setting', 'Result decode error!');
                }
            }
        }

        $this->refreshAPIState();

        return array('success' => $success, 'msg' => $msg);
    }

    public function refreshAPIState() {
        $model = $this->findByAttributes(array('name' => 'APIToken'));
        if (!empty($model)) {
            $content = $model->content;
            Yii::app()->user->setState('apitoken', $content);
        }
        $model = $this->findByAttributes(array('name' => 'UserUUID'));
        if (!empty($model)) {
            $content = $model->content;
            Yii::app()->user->setState('apiuuid', $content);
        }
        $model = $this->findByAttributes(array('name' => 'CategoryRootUUID'));
        if (!empty($model)) {
            $content = $model->content;
            Yii::app()->user->setState('apicategoryroot', $content);
        }
        $model = $this->findByAttributes(array('name' => 'CategoryIcoUUID'));
        if (!empty($model)) {
            $content = $model->content;
            Yii::app()->user->setState('apicategoryico', $content);
        }
    }

}
