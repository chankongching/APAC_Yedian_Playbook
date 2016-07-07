<?php

class RoomController extends Controller
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
		$model=new Room;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Room']))
		{
			$model->attributes=$_POST['Room'];
                        
                        $model->bigfile = CUploadedFile::getInstance($model, 'bigfile');
                        $model->smallfile = CUploadedFile::getInstance($model, 'smallfile');
                        if ($model->bigfile !== null) {
                            $dir = Yii::getPathOfAlias('webroot.uploads');
                            // 文件类型  
                            $file_type = strtolower($model->bigfile->getExtensionName());
                            // 存储文件名  
                            $file_origin_name = $model->bigfile->getName();
                            $file_random = date('YmdHis') . '_' . rand(1000, 9999);
                            $file_name = 'img_' . $file_random . '.' . $file_type;
                            $file_path = $dir . DIRECTORY_SEPARATOR . 'room' . DIRECTORY_SEPARATOR;
                            $file_full_path = $file_path . $file_name;

                            $model->bigfile->saveAs($file_full_path);
                            $model->bigpic_url = $file_name;
                            $model->bigpic_filename = $file_origin_name;
                        }
                        if ($model->smallfile !== null) {
                            $dir = Yii::getPathOfAlias('webroot.uploads');
                            // 文件类型  
                            $file_type = strtolower($model->smallfile->getExtensionName());
                            // 存储文件名  
                            $file_origin_name = $model->smallfile->getName();
                            $file_random = date('YmdHis') . '_' . rand(1000, 9999);
                            $file_name = 'img_' . $file_random . '.' . $file_type;
                            $file_path = $dir . DIRECTORY_SEPARATOR . 'room' . DIRECTORY_SEPARATOR;
                            $file_full_path = $file_path . $file_name;

                            $model->smallfile->saveAs($file_full_path);
                            $model->smallpic_url = $file_name;
                            $model->smallpic_filename = $file_origin_name;
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

		if(isset($_POST['Room']))
		{
			$model->attributes=$_POST['Room'];
                        $model->bigfile = CUploadedFile::getInstance($model, 'bigfile');
                        $model->smallfile = CUploadedFile::getInstance($model, 'smallfile');
                        if ($model->bigfile !== null) {
                            $dir = Yii::getPathOfAlias('webroot.uploads');
                            // 文件类型  
                            $file_type = strtolower($model->bigfile->getExtensionName());
                            // 存储文件名  
                            $file_origin_name = $model->bigfile->getName();
                            $file_random = date('YmdHis') . '_' . rand(1000, 9999);
                            $file_name = 'img_' . $file_random . '.' . $file_type;
                            $file_path = $dir . DIRECTORY_SEPARATOR . 'room' . DIRECTORY_SEPARATOR;
                            $file_full_path = $file_path . $file_name;

                            // delete old file
                            $old_file_name = $model->bigpic_url;
                            if(is_file($file_path . $old_file_name)) {
                                @unlink($file_path . $old_file_name);
                            }
                            
                            $model->bigfile->saveAs($file_full_path);
                            $model->bigpic_url = $file_name;
                            $model->bigpic_filename = $file_origin_name;
                        }
                        if ($model->smallfile !== null) {
                            $dir = Yii::getPathOfAlias('webroot.uploads');
                            // 文件类型  
                            $file_type = strtolower($model->smallfile->getExtensionName());
                            // 存储文件名  
                            $file_origin_name = $model->smallfile->getName();
                            $file_random = date('YmdHis') . '_' . rand(1000, 9999);
                            $file_name = 'img_' . $file_random . '.' . $file_type;
                            $file_path = $dir . DIRECTORY_SEPARATOR . 'room' . DIRECTORY_SEPARATOR;
                            $file_full_path = $file_path . $file_name;

                            // delete old file
                            $old_file_name = $model->smallpic_url;
                            if(is_file($file_path . $old_file_name)) {
                                @unlink($file_path . $old_file_name);
                            }
                            
                            $model->smallfile->saveAs($file_full_path);
                            $model->smallpic_url = $file_name;
                            $model->smallpic_filename = $file_origin_name;
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
		//$dataProvider=new CActiveDataProvider('Room');
		//$this->render('index',array(
		//	'dataProvider'=>$dataProvider,
		//));
		$model=new Room('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Room']))
			$model->attributes=$_GET['Room'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Room('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Room']))
			$model->attributes=$_GET['Room'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Room the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Room::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Room $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='room-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
	/**
	 * Reset the specifized room.
         * Expire the room device check in QR code
         * Reset CheckinUser list
         * Reset CheckinState list
         * 
	 * If reset is successful, the all status will be set to false.
	 * @param integer $id the ID of the model to be reset
	 */
	public function actionReset($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
                // Reset QR check in code of this room
                CheckinCode::model()->updateAll(array('expire' => time()), 'room_id = :roomid', array(':roomid' => $id));
                //foreach($model->checkinCodes as $key => $obj) {
                //    $obj->setAttribute('expire', time());
                //    $obj->save();
                //}
                // Clear room user check in list
                CheckinUser::model()->deleteAllByAttributes(array('room_id' => $id));
                //foreach($model->checkinUsers as $key => $obj) {
                //    $obj->deleteAll();
                //}
                // Clear room check in state
                CheckinState::model()->deleteAllByAttributes(array('room_id' => $id));
                //$model->checkinStates->deleteAll();

                // get room device and clear room device playlist
                $device_list = DeviceState::model()->findAllByAttributes(array('room_id' => $id));
                if(!is_null($device_list) && !empty($device_list)) {
                    foreach ($device_list as $key => $value) {
                        $device_id = $value->device_id;
                        DevicePlaylist::model()->deleteAllByAttributes(array('device_id' => $device_id));
                    }
                }
                
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		//if(!isset($_GET['ajax']))
		//	$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}
        
	/**
	 * initliaze the specifized room.
         * Create the room device check in QR code
         * Reset CheckinUser list
         * Reset CheckinState list
         * 
	 * If check in is successful, the new QR code will be generated.
	 * @param integer $id the ID of the model to be reset
	 */
	public function actionCheckin($id, $hours = 0)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
                // create new  QR check in code of this room
                //$checkin_codes = $model->checkinCodes;
                $cur_checkin_qr_code = uniqid();
                if($hours > 0 || $hours < 0) {
                    $cur_duration = abs($hours);
                    $cur_expire_time = time() + $cur_duration * 3600;
                }
                else {
                    $cur_duration = 0;
                    $cur_expire_time = 0;
                }
                
                // initliaze check in qr code
                $checkin_codes = CheckinCode::model()->findByAttributes(array('room_id'=>$id));
                if(is_null($checkin_codes) || empty($checkin_codes)) {
                    $cur_checkin_code = new CheckinCode;
                    $cur_checkin_code->code = $cur_checkin_qr_code;
                    $cur_checkin_code->room_id = $id;
                    $cur_checkin_code->name = 'QR code for room ' . $id;
                    $cur_checkin_code->expire = $cur_expire_time;
                    $cur_checkin_code->duration = $cur_duration;
                    $cur_checkin_code->save();
                }
                else {
                    $checkin_codes->setAttribute('code', $cur_checkin_qr_code);
                    $checkin_codes->setAttribute('expire', $cur_expire_time);
                    $checkin_codes->setAttribute('duration', $cur_duration);
                    $checkin_codes->save();
                }
                // Clear room user check in list
                CheckinUser::model()->deleteAllByAttributes(array('room_id' => $id));
                // Clear room check in state
                CheckinState::model()->deleteAllByAttributes(array('room_id' => $id));

                // get room device and clear room device playlist
                $device_list = DeviceState::model()->findAllByAttributes(array('room_id' => $id));
                if(!is_null($device_list) && !empty($device_list)) {
                    foreach ($device_list as $key => $value) {
                        $device_id = $value->device_id;
                        DevicePlaylist::model()->deleteAllByAttributes(array('device_id' => $device_id));
                    }
                }
                
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_GET['returnUrl']) ? $_GET['returnUrl'] : array('index'));
                else {
                    echo CJSON::encode(array('redirect' => isset($_GET['returnUrl']) ? $_GET['returnUrl'] : $this->createUrl('index')));
                }
	}
}
