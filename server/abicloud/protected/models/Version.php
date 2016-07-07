<?php

/**
 * This is the model class for table "{{version}}".
 *
 * The followings are the available columns in table '{{version}}':
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property string $version
 * @property string $os
 * @property string $file_name
 * @property integer $force_update
 * @property string $download_url
 * @property integer $application_id
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property string $version_code
 * @property integer $app_type
 *
 * The followings are the available model relations:
 * @property Application $application
 */
class Version extends PSActiveRecord {

    public $file;
    public $updatefile;

    public function getUpdateOptions() {
        return array(
            1 => Yii::t('files', 'Force Update'),
            0 => Yii::t('files', 'Optional'),
        );
    }

    public function getOsOptions() {
        return array(
            'ANDROID' => Yii::t('files', 'Android'),
            'WINDOWSPHONE' => Yii::t('files', 'Windows Phone'),
            'WINDOWS' => Yii::t('files', 'Windows'),
            'IOS' => Yii::t('files', 'Apple IOS'),
        );
    }

    public function getTypeOptions() {
        return array(
            0 => Yii::t('files', 'Unknown'),
            1 => Yii::t('files', 'STB'),
            2 => Yii::t('files', 'KTVTABLE'),
            3 => Yii::t('files', 'KTVPAD'),
            4 => Yii::t('files', 'Android APP'),
            5 => Yii::t('files', 'IOS APP'),
        );
    }
    
    public function getTypeOption($index) {
        $options = array(
            0 => Yii::t('files', 'Unknown'),
            1 => Yii::t('files', 'STB'),
            2 => Yii::t('files', 'KTVTABLE'),
            3 => Yii::t('files', 'KTVPAD'),
            4 => Yii::t('files', 'Android APP'),
            5 => Yii::t('files', 'IOS APP'),
        );
        if(isset($options[$index])) {
            return $options[$index];
        }
        else {
            return Yii::t('files', 'Unknown');
        }
    }
    
    public function getApplicationOptions() {
        $app_list = array();
        $apps = Application::model()->findAll();
        if (!empty($apps)) {
            foreach ($apps as $key => $app) {
                $app_list[$app->id] = $app->name;
            }
        }
        return $app_list;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{version}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        if ($this->isNewRecord) {
            return array(
                array('name, version', 'required'),
                array('force_update, application_id, create_user_id, update_user_id, app_type', 'numerical', 'integerOnly' => true),
                array('name, os, file_name, content, version, download_url, version_code', 'length', 'max' => 255),
                array('create_time, update_time', 'safe'),
                array('file', 'file', 'types' => 'jpg,png,gif,jpeg,apk,zip,exe,7z,tgz,gz,rar', 'maxSize' => 2048576 * 10 * 5, 'safe' => true, 'allowEmpty' => true),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, content, version, os, file_name, force_update, download_url, application_id, create_time, create_user_id, update_time, update_user_id, version_code, app_type', 'safe', 'on' => 'search'),
            );
        } else {
            return array(
                array('name, version', 'required'),
                array('force_update, application_id, create_user_id, update_user_id, app_type', 'numerical', 'integerOnly' => true),
                array('name, os, file_name, content, version, download_url, version_code', 'length', 'max' => 255),
                array('create_time, update_time', 'safe'),
                array('updatefile', 'file', 'types' => 'jpg,png,gif,jpeg,apk,zip,exe,7z,tgz,gz,rar', 'maxSize' => 2048576 * 10 * 5, 'safe' => true, 'allowEmpty' => true),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, content, version, os, file_name, force_update, download_url, application_id, create_time, create_user_id, update_time, update_user_id, version_code, app_type', 'safe', 'on' => 'search'),
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
            'application' => array(self::BELONGS_TO, 'Application', 'application_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'content' => 'Content',
            'version' => 'Version',
            'version_code' => 'Version Number',
            'os' => 'OS',
            'app_type' => 'APP Type',
            'file_name' => Yii::t('files', 'File Name'),
            'force_update' => 'Force Update',
            'download_url' => 'Download Url',
            'application_id' => 'Application',
            'create_time' => 'Create Time',
            'create_user_id' => 'Create User',
            'update_time' => 'Update Time',
            'update_user_id' => 'Update User',
            'file' => Yii::t('files', 'Select File'),
            'updatefile' => Yii::t('files', 'Update File'),
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
        $criteria->compare('version', $this->version, true);
        $criteria->compare('version_code', $this->version_code, true);
        $criteria->compare('os', $this->os, true);
        $criteria->compare('app_type', $this->app_type);
        $criteria->compare('file_name', $this->file_name, true);
        $criteria->compare('force_update', $this->force_update);
        $criteria->compare('download_url', $this->download_url, true);
        $criteria->compare('application_id', $this->application_id);
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
     * @return Version the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getNewVersion($appID, $old_version = '', $os = '', $old_versionCode = 0, $app_type = 0, $need_check = false) {
        $cond_plus = '';
        if(empty($appID)) {
            return null;
        }
        else {
            $cond_plus .= " WHERE t1.application_id = '$appID' ";
        }
        if(!empty($os)) {
            $cond_plus .= " AND t1.os like '$os' ";
        }
        if($need_check && !empty($old_version)) {
            $cond_plus .= " AND t1.version > '$old_version' ";
        }
        if($need_check && !empty($old_versionCode)) {
            $cond_plus .= " AND t1.version_code > '$old_versionCode' ";
        }
        if(!empty($app_type)) {
            $cond_plus .= " AND t1.app_type = '$app_type' ";
        }
        
        $sql = 'select t1.id, t1.name,t1.content,t1.version,t1.version_code,t1.force_update, t1.download_url from {{version}} t1 ' . $cond_plus . ' ORDER BY t1.update_time DESC';
            
        Yii::trace($sql);
        $result = Yii::app()->db->createCommand($sql);
        $query = $result->queryAll();
        if (!empty($query) && !empty($query[0])) {
            return $query[0];
        } else {
            return null;
        }
    }

    public function getMD5ofVersion($id = 0) {
        $dir = Yii::getPathOfAlias('webroot.uploads');
        if($id > 0) {
            $model = $this->findByPk($id);
            if (!is_null($model)) {
                $app_real_name = $model->download_url;
                $app_full_path = $dir . DIRECTORY_SEPARATOR . $app_real_name;
            }
            else {
                $app_file_name = 'BudKTV.apk';
                $app_full_path = $dir . DIRECTORY_SEPARATOR . $app_file_name;
            }
        }
        else {
            $app_file_name = 'BudKTV.apk';
            $app_full_path = $dir . DIRECTORY_SEPARATOR . $app_file_name;
        }
        if(file_exists($app_full_path)) {
            return md5_file($app_full_path);
        }
        return '';
    }
}
