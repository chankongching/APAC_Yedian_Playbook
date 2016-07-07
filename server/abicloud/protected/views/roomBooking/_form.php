<?php
/* @var $this RoomBookingController */
/* @var $model RoomBooking */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'roombooking-form',
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
    <legend><?php echo Yii::t('RoomBooking', 'Fields with {required} are required to {operate} roombooking.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'order_invoice', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'order_invoice',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'order_invoice'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'order_amount', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'order_amount',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'order_amount'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'order_code', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'order_code',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'order_code'); ?>
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
	<?php echo $form->labelEx($model,'user_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'room_status', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'room_status'); ?>
		<?php echo $form->error($model,'room_status'); ?>
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
	<?php echo $form->labelEx($model,'booking_time', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'booking_time'); ?>
		<?php echo $form->error($model,'booking_time'); ?>
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

