<?php

class VersionController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    //public function filters() {
    //    return array(
    //        'accessControl', // perform access control for CRUD operations
    //        'postOnly + delete', // we only allow deletion via POST request
    //    );
    //}

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $dir = Yii::getPathOfAlias('webroot.uploads');
        $model = new Version;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Version'])) {
            $model->attributes = $_POST['Version'];

            $model->file = CUploadedFile::getInstance($model, 'file');
            if ($model->file === null) {
                throw new CHttpException(403, Yii::t('files', 'Must upload a file to performing this action.'));
            }

            if ($model->validate()) {
                if ($model->file !== null) {
                    $model->file_name = $model->file->getName();
                    // 文件类型  
                    $file_type = strtolower($model->file->getExtensionName());
                    // 存储文件名  
                    $file_name = 'APP_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;

                    $model->file->saveAs($dir . DIRECTORY_SEPARATOR . $file_name);
                    $model->download_url = $file_name;
                }

                if ($model->save())
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $dir = Yii::getPathOfAlias('webroot.uploads');
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Version'])) {
            $model->attributes = $_POST['Version'];

            $model->updatefile = CUploadedFile::getInstance($model, 'updatefile');

            if ($model->validate()) {
                if ($model->updatefile !== null) {
                    // Delete old file?
                    $old_file_name = $dir . DIRECTORY_SEPARATOR . $model->download_url;
                    if (file_exists($old_file_name)) {
                        @unlink($old_file_name);
                    }
                    $model->file_name = $model->updatefile->getName();
                    // 文件类型  
                    $file_type = strtolower($model->updatefile->getExtensionName());
                    // 存储文件名  
                    $file_name = 'APP_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;

                    $model->updatefile->saveAs($dir . DIRECTORY_SEPARATOR . $file_name);
                    $model->download_url = $file_name;
                }

                if ($model->save())
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Version');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Version('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Version']))
            $model->attributes = $_GET['Version'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Version the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Version::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Version $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'version-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionPush($id) {
        $version = $this->loadModel($id);
        // TODO to push to push server
        $rest = new RESTClient();
        $rest->initialize(array('server' => 'http://127.0.0.1:8000/'));

        $cname = 'UPGRADE_NOTIFY';
        $msg = array(
            "category" => "UPGRADE_NOTIFY",
            "message" => array(
                "result" => 0,
                "msg" => Yii::t('user', 'Get new version!'),
                "need_update" => true,
                "force_update" => (1 == $version['force_update']) ? true : false,
                "new_version" => $version['version'],
                "new_versionCode" => intval($version['version_code']),
                "new_name" => $version['name'],
                "download_url" => Yii::app()->createAbsoluteUrl('//') . '/site/appfile/' . $version['id'],
                "md5_file" => $version->getMD5ofVersion($version['id'])
            )
        );

        $content = json_encode($msg);
        //$cname = urlencode($cname);
        //$content = urlencode($content);

        $sended_message = Yii::t('version', 'push sent failed!');
        $json = $rest->get('push', array('cname' => $cname, 'content' => $content));
        $result = json_decode($json, true);
        if (!is_null($result)) {
            if (isset($result['type']) && $result['type'] == 'ok') {
                $sended_message = Yii::t('version', 'push sent successful!');
                ApiController::recordStatistics('version/push', $version['id']);
            }
        }

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_GET['returnUrl']) ? $_GET['returnUrl'] : array('index'));
        else {
            echo CJSON::encode(array('redirect' => isset($_GET['returnUrl']) ? $_GET['returnUrl'] : $this->createUrl('index'), 'message' => $sended_message));
        }
    }

}
