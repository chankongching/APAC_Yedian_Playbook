<?php

class ArtistcategoryController extends Controller
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
                    'filename' => 'Category-' . date('YmdHis', time()) . '.csv',
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
		$model=new Artistcategory;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Artistcategory']))
		{
			$model->attributes=$_POST['Artistcategory'];
                        $model->bpic_file = CUploadedFile::getInstance($model, 'bpic_file');
                        $model->spic_file = CUploadedFile::getInstance($model, 'spic_file');
                        
                        if ($model->validate()) {
                            if ($model->bpic_file !== null) {
                                // Save new file
                                $model->bpic_filename = $model->bpic_file->getName();
                                // 文件类型  
                                $file_type = strtolower($model->bpic_file->getExtensionName());
                                // 存储文件名  
                                $file_name = 'CATE_BPIC_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;
                                $model->bpic_file->saveAs($model->attach_path . DIRECTORY_SEPARATOR . $file_name);
                                $model->bpic_url = $file_name;
                            }
                            
                            if ($model->spic_file !== null) {
                                // Save new file
                                $model->spic_filename = $model->spic_file->getName();
                                // 文件类型  
                                $file_type = strtolower($model->spic_file->getExtensionName());
                                // 存储文件名  
                                $file_name = 'CATE_SPIC_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;
                                $model->spic_file->saveAs($model->attach_path . DIRECTORY_SEPARATOR . $file_name);
                                $model->spic_url = $file_name;
                            }
                        }
			if($model->save()) {
                                Yii::app()->user->setFlash('create', Yii::t('Artistcategory', '{name} created successfully.', array('{name}' => $model->name)));
				//$this->redirect(array('view','id'=>$model->id));
                                $this->redirect(array('index'));
                        }
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

		if(isset($_POST['Artistcategory']))
		{
			$model->attributes=$_POST['Artistcategory'];
                        
                        $model->bpic_file = CUploadedFile::getInstance($model, 'bpic_file');
                        $model->spic_file = CUploadedFile::getInstance($model, 'spic_file');
                        
                        if ($model->validate()) {
                            if ($model->bpic_file !== null) {
                                // Delete old file?
                                $old_file_name = $model->attach_path . DIRECTORY_SEPARATOR . $model->bpic_url;
                                if (file_exists($old_file_name)) {
                                    @unlink($old_file_name);
                                }
                                // Save new file
                                $model->bpic_filename = $model->bpic_file->getName();
                                // 文件类型  
                                $file_type = strtolower($model->bpic_file->getExtensionName());
                                // 存储文件名  
                                $file_name = 'CATE_BPIC_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;
                                $model->bpic_file->saveAs($model->attach_path . DIRECTORY_SEPARATOR . $file_name);
                                $model->bpic_url = $file_name;
                            }
                            
                            if ($model->spic_file !== null) {
                                // Delete old file?
                                $old_file_name = $model->attach_path . DIRECTORY_SEPARATOR . $model->spic_url;
                                if (file_exists($old_file_name)) {
                                    @unlink($old_file_name);
                                }
                                // Save new file
                                $model->spic_filename = $model->spic_file->getName();
                                // 文件类型  
                                $file_type = strtolower($model->spic_file->getExtensionName());
                                // 存储文件名  
                                $file_name = 'CATE_SPIC_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;
                                $model->spic_file->saveAs($model->attach_path . DIRECTORY_SEPARATOR . $file_name);
                                $model->spic_url = $file_name;
                            }
                        }
                        
			if($model->save()) {
                                Yii::app()->user->setFlash('update', Yii::t('Artistcategory', '{name} updated successfully.', array('{name}' => $model->name)));
				//$this->redirect(array('view','id'=>$model->id));
                                $this->redirect(array('index'));
                        }
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
		$model=new Artistcategory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Artistcategory']))
			$model->attributes=$_GET['Artistcategory'];

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
		$model=new Artistcategory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Artistcategory']))
			$model->attributes=$_GET['Artistcategory'];

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
	 * @return Artistcategory the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Artistcategory::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Artistcategory $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='artistcategory-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionImport() {
            $model=new Artistcategory;
            if(isset($_FILES['Artistcategory'])) {
                        // read from file
                        $txtFile = CUploadedFile::getInstance($model, 'bpic_file');
                
                        if ($txtFile !== null) {
                            $filetype = $txtFile->getType();
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
                                            //$_cate_id = $line_list[0];
                                            $_cate_name = $line_list[1];
                                            $_bpic_url = $line_list[2];
                                            //$_spic_url = $line_list[3];
                                            $cate_array = array(
                                                //'id' => $_cate_id,
                                                'name' => $_cate_name,
                                                'bpic_url' => $_bpic_url,
                                                //'spic_url' => $_spic_url,
                                            );
                                            
                                            // TODO: update or add record
                                            $cate_model = Artistcategory::model()->findByAttributes($cate_array);
                                            if(empty($cate_model)) {
                                                $cate_model = new Artistcategory;
                                                $cate_model->name = $cate_array['name'];
                                                $cate_model->bpic_url = $cate_array['bpic_url'];
                                                $cate_model->save(false);
                                                //$cate_model->saveAttributes($cate_array);
                                            }
                                            else {
                                                $cate_model->update($cate_array);
                                            }
                                            
                                        }
                                    }
                                }
                            }
                            // rediret to index page
                            $this->redirect(array('index'));
                        }
            }
            $this->render('_import',array(
                'model'=>$model,
                ));
        }
}
