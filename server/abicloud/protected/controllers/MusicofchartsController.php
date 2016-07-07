<?php

class MusicofchartsController extends Controller
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
                    'filename' => 'MusicOfCharts-' . date('YmdHis', time()) . '.csv',
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
		$model=new Musicofcharts;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Musicofcharts']))
		{
			$model->attributes=$_POST['Musicofcharts'];
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

		if(isset($_POST['Musicofcharts']))
		{
			$model->attributes=$_POST['Musicofcharts'];
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
		$model=new Musicofcharts('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Musicofcharts']))
			$model->attributes=$_GET['Musicofcharts'];

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
		$model=new Musicofcharts('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Musicofcharts']))
			$model->attributes=$_GET['Musicofcharts'];

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
	 * @return Musicofcharts the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Musicofcharts::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Musicofcharts $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='musicofcharts-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionImport() {
            $model=new Musicofcharts;
            if(isset($_FILES['Musicofcharts'])) {
                $do_not_clear_flag = false;
                if(isset($_POST['do_not_clear_flag']) && $_POST['do_not_clear_flag'] == 1) {
                    $do_not_clear_flag = true;
                }
                        // read from file
                        $txtFile = CUploadedFile::getInstance($model, 'bpic_file');
                        if ($txtFile !== null) {
                            set_time_limit(600);
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
                                    $_charts_clear_flag = array();
                                    $_rank_array = array();
                                    foreach ($line_array as $key => $value) {
                                        $line_list = explode(",", $value);
                                        if (!empty($line_list) && is_array($line_list)) {
                                            $_charts_id = isset($line_list[0])?trim($line_list[0]):'';
                                            $_media_id = isset($line_list[1])?trim($line_list[1]):'';
                                            $_rank = isset($line_list[2])?intval(trim($line_list[2])):0;
                                            
                                            if(empty($_charts_id) || empty($_media_id)) {
                                                continue;
                                            }
                                            // first clear charts
                                            if(!$do_not_clear_flag && !isset($_charts_clear_flag[$_charts_id])) {
                                                Musicofcharts::model()->deleteAllByAttributes(array('chart_id'=>$_charts_id));
                                                $_charts_clear_flag[$_charts_id] = 1;
                                            }
                                            
                                            if(Musiccharts::model()->countByAttributes(array('id'=>$_charts_id)) < 1) {
                                                continue;
                                            }
                                            if(Media::model()->countByAttributes(array('id'=>$_media_id)) < 1) {
                                                continue;
                                            }
                                            
                                            // record rank 
                                            if(!isset($_rank_array[$_charts_id])) {
                                                $max_rank = 1;
                                                try {
                                                    $sql = 'select max(rank) as rank from {{musicofcharts}} where chart_id = ' . "'$_charts_id'" .' limit 1';
                                                    $cmd = Yii::app()->db->createCommand($sql);
                                                    $result = $cmd->queryRow();
                                                    if($result !== false) {
                                                        $max_rank = intval($result['rank']) + 1;
                                                    }
                                                } catch (Exception $ex) {

                                                }
                                                
                                                $_rank_array[$_charts_id] = $max_rank;
                                            }
                                            else {
                                                $_rank_array[$_charts_id] += 1;
                                            }
                                            if($_rank > $_rank_array[$_charts_id]) {
                                                $_rank_array[$_charts_id] = $_rank;
                                            }
                                            
                                            $cate_array = array(
                                                'chart_id' => $_charts_id,
                                                'media_id' => $_media_id,
                                            );
                                            
                                            // TODO: update or add record
                                            $cate_model = Musicofcharts::model()->findByAttributes($cate_array);
                                            if(empty($cate_model)) {
                                                $cate_model = new Musicofcharts;
                                                $cate_model->chart_id = $cate_array['chart_id'];
                                                $cate_model->media_id = $cate_array['media_id'];
                                                $cate_model->rank = $_rank_array[$_charts_id];
                                                $cate_model->save(false);
                                            }
                                            else {
                                                $cate_model->rank = $_rank_array[$_charts_id];
                                                $cate_model->update();
                                            }
                                            $_total ++;
                                        }
                                    }
                                }
                            }
                            Yii::app()->user->setFlash('update', Yii::t('Musicofcharts', 'Import total ' . $_total . ' songs.'));
                            // rediret to index page
                            $this->redirect(array('index'));
                        }
            }
            $this->render('_import',array(
                'model'=>$model,
                ));
        }
}
