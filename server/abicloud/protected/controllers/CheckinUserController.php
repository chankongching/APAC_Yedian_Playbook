<?php

class CheckinUserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	//public function filters()
	//{
	//	return array(
	//		'accessControl', // perform access control for CRUD operations
	//		'postOnly + delete', // we only allow deletion via POST request
	//	);
	//}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new CheckinUser;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CheckinUser']))
		{
			$model->attributes=$_POST['CheckinUser'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CheckinUser']))
		{
			$model->attributes=$_POST['CheckinUser'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
            $model = $this->loadModel($id);
            if(!is_null($model)) {
                // TODO should logout user
                $uid = $model->uid;
                YiiSession::model()->deleteAllByAttributes(array('uid'=>$uid));
                        
            }
		$model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		//$dataProvider=new CActiveDataProvider('CheckinUser');
		//$this->render('index',array(
		//	'dataProvider'=>$dataProvider,
		//));
		$model=new CheckinUser('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CheckinUser']))
			$model->attributes=$_GET['CheckinUser'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CheckinUser('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CheckinUser']))
			$model->attributes=$_GET['CheckinUser'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CheckinUser the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CheckinUser::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CheckinUser $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='checkin-user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
