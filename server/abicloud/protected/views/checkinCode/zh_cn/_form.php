<?php
/* @var $this CheckinCodeController */
/* @var $model CheckinCode */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'checkincode-form',
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
    <legend><?php echo Yii::t('CheckinCode', 'Fields with {required} are required to {operate} checkincode.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'code', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'code',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'code'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'room_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'room_id'); ?>
		<?php echo $form->error($model,'room_id'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'name', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'description', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'duration', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'duration'); ?>
		<?php echo $form->error($model,'duration'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'expire', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'expire'); ?>
		<?php echo $form->error($model,'expire'); ?>
        </div>
    </div>


    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

