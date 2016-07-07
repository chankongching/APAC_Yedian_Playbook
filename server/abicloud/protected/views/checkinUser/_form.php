<?php
/* @var $this CheckinUserController */
/* @var $model CheckinUser */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'checkinuser-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form-horizontal')
    //'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
        ));
?>

<fieldset>
    <legend><?php echo Yii::t('CheckinUser', 'Fields with {required} are required to {operate} checkinuser.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'room_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'room_id'); ?>
		<?php echo $form->error($model,'room_id'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'uid', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'uid'); ?>
		<?php echo $form->error($model,'uid'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'checkin_time', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'checkin_time'); ?>
		<?php echo $form->error($model,'checkin_time'); ?>
        </div>
    </div>


    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

