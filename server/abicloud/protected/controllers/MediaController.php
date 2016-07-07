<?php

class MediaController extends Controller
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
                    'filename' => 'media-' . date('YmdHis', time()) . '.csv',
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
		$model=new Media;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Media']))
		{
			$model->attributes=$_POST['Media'];
                        $model->bpic_file = CUploadedFile::getInstance($model, 'bpic_file');
                        $model->spic_file = CUploadedFile::getInstance($model, 'spic_file');
                        if ($model->validate()) {
                            if ($model->bpic_file !== null) {
                                // Save new file
                                $model->bpic_filename = $model->bpic_file->getName();
                                // 文件类型  
                                $file_type = strtolower($model->bpic_file->getExtensionName());
                                // 存储文件名  
                                $file_name = 'MEDIA_BPIC_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;
                                if (!empty($model->video_dir_name)) {
                                    if ("demo" == $model->video_dir_name) {
                                        $_mediapath = $model->video_dir_name . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                                    } else {
                                        $_mediapath = $model->video_dir_name . DIRECTORY_SEPARATOR;
                                    }
                                } else {
                                    $_cate_id = empty($model->category_id) ? '0' : $model->category_id;
                                    $_mediapath = $_cate_id . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                                }
                                $model->bpic_file->saveAs($model->attach_path . DIRECTORY_SEPARATOR . $_mediapath . $file_name);
                                $model->bpic_url = $file_name;
                            }
                            
                            if ($model->spic_file !== null) {
                                // Save new file
                                $model->spic_filename = $model->spic_file->getName();
                                // 文件类型  
                                $file_type = strtolower($model->spic_file->getExtensionName());
                                // 存储文件名  
                                $file_name = 'MEDIA_SPIC_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;
                                if (!empty($model->video_dir_name)) {
                                    if ("demo" == $model->video_dir_name) {
                                        $_mediapath = $model->video_dir_name . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                                    } else {
                                        $_mediapath = $model->video_dir_name . DIRECTORY_SEPARATOR;
                                    }
                                } else {
                                    $_cate_id = empty($model->category_id) ? '0' : $model->category_id;
                                    $_mediapath = $_cate_id . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                                }
                                $model->spic_file->saveAs($model->attach_path . DIRECTORY_SEPARATOR . $_mediapath . $file_name);
                                $model->spic_url = $file_name;
                            }
                        }
                        
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

		if(isset($_POST['Media']))
		{
			$model->attributes=$_POST['Media'];
                        $model->bpic_file = CUploadedFile::getInstance($model, 'bpic_file');
                        $model->spic_file = CUploadedFile::getInstance($model, 'spic_file');
                        if ($model->validate()) {
                            if ($model->bpic_file !== null) {
                                // Save new file
                                $model->bpic_filename = $model->bpic_file->getName();
                                // 文件类型  
                                $file_type = strtolower($model->bpic_file->getExtensionName());
                                // 存储文件名  
                                $file_name = 'MEDIA_BPIC_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;
                                if (!empty($model->video_dir_name)) {
                                    if ("demo" == $model->video_dir_name) {
                                        $_mediapath = $model->video_dir_name . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                                    } else {
                                        $_mediapath = $model->video_dir_name . DIRECTORY_SEPARATOR;
                                    }
                                } else {
                                    $_cate_id = empty($model->category_id) ? '0' : $model->category_id;
                                    $_mediapath = $_cate_id . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                                }
                                $result = $model->bpic_file->saveAs($model->attach_path . DIRECTORY_SEPARATOR . $_mediapath . $file_name);
                                if($result) {
                                    $model->bpic_url = $file_name;
                                }
                                else {
                                    Yii::app()->user->setFlash('update', Yii::t('Media', '{name} uploaded error.', array('{name}' => $model->bpic_filename)));
                                }
                            }
                            
                            if ($model->spic_file !== null) {
                                // Save new file
                                $model->spic_filename = $model->spic_file->getName();
                                // 文件类型  
                                $file_type = strtolower($model->spic_file->getExtensionName());
                                // 存储文件名  
                                $file_name = 'MEDIA_SPIC_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;
                                if (!empty($model->video_dir_name)) {
                                    if ("demo" == $model->video_dir_name) {
                                        $_mediapath = $model->video_dir_name . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                                    } else {
                                        $_mediapath = $model->video_dir_name . DIRECTORY_SEPARATOR;
                                    }
                                } else {
                                    $_cate_id = empty($model->category_id) ? '0' : $model->category_id;
                                    $_mediapath = $_cate_id . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                                }
                                $result = $model->spic_file->saveAs($model->attach_path . DIRECTORY_SEPARATOR . $_mediapath . $file_name);
                                if($result) {
                                    $model->spic_url = $file_name;
                                }
                                else {
                                    Yii::app()->user->setFlash('update', Yii::t('Media', '{name} uploaded error.', array('{name}' => $model->spic_filename)));
                                }
                            }
                        }
                        
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
		//$dataProvider=new CActiveDataProvider('Media');
		//$this->render('index',array(
		//	'dataProvider'=>$dataProvider,
		//));
		$model=new Media('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Media']))
			$model->attributes=$_GET['Media'];

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
		$model=new Media('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Media']))
			$model->attributes=$_GET['Media'];

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
	 * @return Media the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Media::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Media $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='media-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionAddcategory($id,$cateid = 0) {
            $model = $this->loadModel($id);
            
            if(!empty($cateid)) {
                // add to category
                $count = Musicofcharts::model()->countByAttributes(array('media_id'=>$id,'chart_id'=>$cateid));
                if($count > 0) {
                    $resp_message = Yii::t('Media', '{name} is a member of {category} yet.', array('{name}' => $model->name,'{category}'=>$cateid));
                }
                else {
                    $aoc = new Musicofcharts;
                    $aoc->media_id = $id;
                    $aoc->chart_id = $cateid;
                    $aoc->save(FALSE);
                    $resp_message = Yii::t('Media', '{name} added to chart {category} successfully.', array('{name}' => $model->name,'{category}'=>$cateid));
                }
                Yii::app()->user->setFlash('update', $resp_message);
            }
            else {
                $resp_message = Yii::t('Media', 'Parameter Error！');
            }
            
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_GET['returnUrl']) ? $_GET['returnUrl'] : array('index'));
            else {
                echo CJSON::encode(array('redirect' => isset($_GET['returnUrl']) ? $_GET['returnUrl'] : $this->createUrl('index'),'message'=>$resp_message));
            }
            
        }
}
