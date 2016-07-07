<?php
/* @var $this RoomBookingController */
/* @var $model RoomBooking */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="control-group">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'order_invoice'); ?>
		<?php echo $form->textField($model,'order_invoice',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'order_amount'); ?>
		<?php echo $form->textField($model,'order_amount',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'order_code'); ?>
		<?php echo $form->textField($model,'order_code',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'room_id'); ?>
		<?php echo $form->textField($model,'room_id'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'room_status'); ?>
		<?php echo $form->textField($model,'room_status'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'duration'); ?>
		<?php echo $form->textField($model,'duration'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'booking_time'); ?>
		<?php echo $form->textField($model,'booking_time'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'expire'); ?>
		<?php echo $form->textField($model,'expire'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'create_time'); ?>
		<?php echo $form->textField($model,'create_time'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'create_user_id'); ?>
		<?php echo $form->textField($model,'create_user_id'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'update_time'); ?>
		<?php echo $form->textField($model,'update_time'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'update_user_id'); ?>
		<?php echo $form->textField($model,'update_user_id'); ?>
	</div>

	<div class="control-group buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->