<?php

class AdminUserController extends Controller {

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
        $model = new User;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save()) {
                ApiController::log('Admin ID: ' . Yii::app()->user->getId() . ', Name: ' . Yii::app()->user->getName() . ' created a new user: ' . $model->username, 'info', 'admin');
                // Assign role to user
                $auth = Yii::app()->authManager;
                $auth->assign($model->role, $model->id);

                Yii::app()->user->setFlash('create', Yii::t('user', '{user} created successfully.', array('{user}' => $model->username)));
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
        $model = $this->loadModel($id);
        $model->password = '';

        if (('SUPER' != strtoupper(Yii::app()->user->getState('role'))) && (Yii::app()->user->id != $model->id)) {
            //throw new CHttpException(403, Yii::t('user', 'You have no permission to perform this action!'));
            throw new CHttpException(403, Yii::t('user', 'You have no permission to perform this action!'));
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->validate()) {
                if (isset($_POST['User']['password']) && !empty($_POST['User']['password'])) {
                    $model->password = $_POST['User']['password'];
                } else {
                    $model->password_changed = false;
                }

                if ($model->save()) {
                    ApiController::log('Admin ID: ' . Yii::app()->user->getId() . ', Name: ' . Yii::app()->user->getName() . ' updated a user: ' . $model->username, 'info', 'admin');
                    // Assign role to user
                    User::assignUserRole($model->role, $model->id);

                    Yii::app()->user->setFlash('update', Yii::t('user', '{user} updated successfully.', array('{user}' => $model->username)));
                    $this->redirect(array('view', 'id' => $model->id));
                }
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
        $model = $this->loadModel($id);
        if (1 == $model->id) {
            Yii::app()->user->setFlash('delete', Yii::t('user', '{user} can not be deleted.', array('{user}' => $model->username)));
            Yii::app()->end(CJSON::encode(array('redirect' => isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->createUrl('admin'))));
        }
        if ($model->delete()) {
            ApiController::log('Admin ID: ' . Yii::app()->user->getId() . ', Name: ' . Yii::app()->user->getName() . ' deleted a user ID: ' . $id, 'info', 'admin');
            Yii::app()->user->setFlash('delete', Yii::t('user', '{user} deleted successfully.', array('{user}' => $model->username)));
        } else {
            Yii::app()->user->setFlash('delete', Yii::t('user', '{user} deleted failed.', array('{user}' => $model->username)));
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (isset($_GET['ajax']) || Yii::app()->request->isAjaxRequest) {
            echo CJSON::encode(array('redirect' => isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->createUrl('admin')));
        } else {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('User');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('files', 'The requested page does not exist.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
