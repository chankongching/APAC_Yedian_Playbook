<?php
/* @var $this RoomExtraController */
/* @var $model RoomExtra */
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
		<?php echo $form->label($model,'room_id'); ?>
		<?php echo $form->textField($model,'room_id'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'room_type'); ?>
		<?php echo $form->textField($model,'room_type'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'smallpic_url'); ?>
		<?php echo $form->textField($model,'smallpic_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'bigpic_url'); ?>
		<?php echo $form->textField($model,'bigpic_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'smallpic_filename'); ?>
		<?php echo $form->textField($model,'smallpic_filename',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'bigpic_filename'); ?>
		<?php echo $form->textField($model,'bigpic_filename',array('size'=>60,'maxlength'=>255)); ?>
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