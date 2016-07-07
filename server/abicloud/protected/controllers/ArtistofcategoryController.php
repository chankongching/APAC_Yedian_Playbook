<?php

class ArtistofcategoryController extends Controller
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

        public function behaviors() {
            return array(
                'exportableGrid' => array(
                    'class' => 'application.behaviors.CExGridExportBehavior',
                    'filename' => 'ArtistOfCategory-' . date('YmdHis', time()) . '.csv',
            ));
        }
        
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
		$model=new Artistofcategory;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Artistofcategory']))
		{
			$model->attributes=$_POST['Artistofcategory'];
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

		if(isset($_POST['Artistofcategory']))
		{
			$model->attributes=$_POST['Artistofcategory'];
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Artistofcategory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Artistofcategory']))
			$model->attributes=$_GET['Artistofcategory'];

                // Create download file 
                if ($this->isExportRequest()) {
                    set_time_limit(0); //Uncomment to export lage datasets
                    $content = $this->renderPartial('_export', array('model' => $model->search(1000), 'exportMode' => 'export'), true);
                    //echo strip_tags($content,'<td>');
                    $this->sendExportHeaders();
                    echo $content;

                    Yii::app()->end(0, false);
                    exit(0);
                }
        
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Artistofcategory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Artistofcategory']))
			$model->attributes=$_GET['Artistofcategory'];

                // Create download file 
                if ($this->isExportRequest()) {
                    set_time_limit(0); //Uncomment to export lage datasets
                    $content = $this->renderPartial('_export', array('model' => $model->search(1000), 'exportMode' => 'export'), true);
                    //echo strip_tags($content,'<td>');
                    $this->sendExportHeaders();
                    echo $content;

                    Yii::app()->end(0, false);
                    exit(0);
                }
        
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Artistofcategory the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Artistofcategory::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Artistofcategory $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='artistofcategory-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionImport() {
            $model=new Artistofcategory;
            if(isset($_FILES['Artistofcategory'])) {
                        // read from file
                        $txtFile = CUploadedFile::getInstance($model, 'bpic_file');
                        if ($txtFile !== null) {
                            $_total = 0;
                            $filetype = $txtFile->getType();
                        //print_r($filetype);die('test');
                            if(strtolower($filetype) != 'text/plain') {
                                //Yii::app()->user->setFlash('update', Yii::t('Artistcategory', 'File {name} type error !', array('{name}' => $txtFile->getName())));
                                Yii::app()->user->setFlash('delete', Yii::t('Artistcategory', 'File type error !'));
                                $this->redirect(array('index'));
                            }
                            $file_content = file_get_contents($txtFile->getTempName());
                            $file_content = str_replace("\r\n", "\n", $file_content);
                            if (!empty($file_content)) {
                                $line_array = explode("\n", $file_content);
                                if (!empty($line_array) && is_array($line_array)) {
                                    foreach ($line_array as $key => $value) {
                                        $line_list = explode(",", $value);
                                        if (!empty($line_list) && is_array($line_list)) {
                                            $_artist_id = isset($line_list[0])?trim($line_list[0]):'';
                                            $_artist_name = isset($line_list[1])?trim($line_list[1]):'';
                                            $_cate_id = isset($line_list[2])?trim($line_list[2]):'';
                                            
                                            if(empty($_artist_id) || empty($_cate_id)) {
                                                continue;
                                            }
                                            if(Artistcategory::model()->countByAttributes(array('id'=>$_cate_id)) < 1) {
                                                continue;
                                            }
                                            if(Artist::model()->countByAttributes(array('id'=>$_artist_id)) < 1) {
                                                continue;
                                            }
                                            
                                            $cate_array = array(
                                                'artist_id' => $_artist_id,
                                                'category_id' => $_cate_id,
                                            );
                                            
                                            // TODO: update or add record
                                            $cate_model = Artistofcategory::model()->findByAttributes($cate_array);
                                            if(empty($cate_model)) {
                                                $cate_model = new Artistofcategory;
                                                $cate_model->artist_id = $cate_array['artist_id'];
                                                $cate_model->category_id = $cate_array['category_id'];
                                                $cate_model->save(false);
                                                //$cate_model->saveAttributes($cate_array);
                                            }
                                            $_total ++;
                                        }
                                    }
                                }
                            }
                            Yii::app()->user->setFlash('update', Yii::t('Artistofcategory', 'Import total ' . $_total . ' artists.'));
                            // rediret to index page
                            $this->redirect(array('index'));
                        }
            }
            $this->render('_import',array(
                'model'=>$model,
                ));
        }
}
