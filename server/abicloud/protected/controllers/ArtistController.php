<?php

class ArtistController extends Controller
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
                    'filename' => 'artist-' . date('YmdHis', time()) . '.csv',
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
		$model=new Artist;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Artist']))
		{
			$model->attributes=$_POST['Artist'];
                        $model->bpic_file = CUploadedFile::getInstance($model, 'bpic_file');
                        $model->spic_file = CUploadedFile::getInstance($model, 'spic_file');
                        
                        if ($model->validate()) {
                            if ($model->bpic_file !== null) {
                                // Save new file
                                $model->bpic_filename = $model->bpic_file->getName();
                                // 文件类型  
                                $file_type = strtolower($model->bpic_file->getExtensionName());
                                // 存储文件名  
                                $file_name = 'ARTIST_BPIC_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;
                                $model->bpic_file->saveAs($model->attach_path . DIRECTORY_SEPARATOR . $file_name);
                                $model->bpic_url = $file_name;
                            }
                            
                            if ($model->spic_file !== null) {
                                // Save new file
                                $model->spic_filename = $model->spic_file->getName();
                                // 文件类型  
                                $file_type = strtolower($model->spic_file->getExtensionName());
                                // 存储文件名  
                                $file_name = 'ARTIST_SPIC_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;
                                $model->spic_file->saveAs($model->attach_path . DIRECTORY_SEPARATOR . $file_name);
                                $model->spic_url = $file_name;
                            }
                        }
                        
			if($model->save()) {
				$this->redirect(array('view','id'=>$model->id));
                                //$this->redirect(array('index'));
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

		if(isset($_POST['Artist']))
		{
			$model->attributes=$_POST['Artist'];
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
                                $file_name = 'ARTIST_BPIC_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;
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
                                $file_name = 'ARTIST_SPIC_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $file_type;
                                $model->spic_file->saveAs($model->attach_path . DIRECTORY_SEPARATOR . $file_name);
                                $model->spic_url = $file_name;
                            }
                        }
                        
			if($model->save()) {
				$this->redirect(array('view','id'=>$model->id));
                                //$this->redirect(array('index'));
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
		//$dataProvider=new CActiveDataProvider('Artist');
		//$this->render('index',array(
		//	'dataProvider'=>$dataProvider,
		//));
		$model=new Artist('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Artist']))
			$model->attributes=$_GET['Artist'];

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
		$model=new Artist('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Artist']))
			$model->attributes=$_GET['Artist'];

                // Create download file 
                if ($this->isExportRequest()) {
                    set_time_limit(0); //Uncomment to export lage datasets
                    $content = $this->renderPartial('_export', array('model' => $model->search(100000), 'exportMode' => 'export'), true);
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
	 * @return Artist the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Artist::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Artist $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='artist-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionAddcategory($id,$cateid = 0) {
            $model = $this->loadModel($id);
            
            if(!empty($cateid)) {
                // add to category
                $count = Artistofcategory::model()->countByAttributes(array('artist_id'=>$id,'category_id'=>$cateid));
                if($count > 0) {
                    $resp_message = Yii::t('Artist', '{name} is a member of {category} yet.', array('{name}' => $model->name,'{category}'=>$cateid));
                }
                else {
                    $aoc = new Artistofcategory;
                    $aoc->artist_id = $id;
                    $aoc->category_id = $cateid;
                    $aoc->save(FALSE);
                    $resp_message = Yii::t('Artist', '{name} added to category {category} successfully.', array('{name}' => $model->name,'{category}'=>$cateid));
                    
                }
                Yii::app()->user->setFlash('update', $resp_message);
            }
            else {
                $resp_message = Yii::t('Artist', 'Parameter Error！');
            }
            
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_GET['returnUrl']) ? $_GET['returnUrl'] : array('index'));
            else {
                echo CJSON::encode(array('redirect' => isset($_GET['returnUrl']) ? $_GET['returnUrl'] : $this->createUrl('index'),'message'=>$resp_message));
            }
            
        }
        
        public function actionCheckpicture() {
            set_time_limit(0);
            $total = Artist::model()->count();
            for($i = 0; $i < $total; $i += 500) {
                $artis_list = Artist::model()->findAllBySql("select id,name,name_pinyin, name_chinese, bpic_url,spic_url from {{artist}} order by id limit ${i},500");
                if(!empty($artis_list)) {
                    foreach ($artis_list as $key=>$_artist) {
                        $_attach_path = $_artist->attach_path;
                        $_bpic_url = $_artist->bpic_url;
                        $_spic_url = $_artist->spic_url;
                        $_name_pinyin = $_artist->name_pinyin;
                        $_name_chinese = $_artist->name_chinese;
                        $is_changed = false;
                        if(empty($_bpic_url) || !file_exists($_attach_path . DIRECTORY_SEPARATOR . $_bpic_url)) {
                            if(!empty($_name_chinese) && file_exists($_attach_path . DIRECTORY_SEPARATOR . ($this->trimall($_name_chinese) . '.jpg'))) {
                                $_artist->bpic_url = $this->trimall($_name_chinese) . '.jpg';
                            }
                            else {
                                $_artist->bpic_url = '';
                            }
                            $is_changed = true;
                        }
                        if(empty($_spic_url) || !file_exists($_attach_path . DIRECTORY_SEPARATOR . $_spic_url)) {
                            if(!empty($_name_chinese) && file_exists($_attach_path . DIRECTORY_SEPARATOR . ($this->trimall($_name_chinese) . '.jpg'))) {
                                $_artist->spic_url = $this->trimall($_name_chinese) . '.jpg';
                            }
                            else {
                                $_artist->spic_url = '';
                            }
                            $is_changed = true;
                        }
                        
                        if($is_changed) {
                            $result = $_artist->save(false);
                            //if(!$result) {
                            //    Yii::app()->user->setFlash('delete', Yii::t('Artist', 'Check picture error !'));
                            //}
                        }
                        
                    }
                }
            }
            Yii::app()->user->setFlash('update', Yii::t('Artist', 'Please copy artist pictures to AbiKTV uploads/attach/artist directory.'));
            $this->redirect(array('index'));
        }
        
        function trimall($str)
        {
            $qian=array(" ","　","\t","\n","\r");
            $hou=array("","","","","");
            return str_replace($qian,$hou,$str);
        }
}
