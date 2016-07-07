<?php
/* @var $this DevicePlaylistController */
/* @var $model DevicePlaylist */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'device_id'); ?>
		<?php echo $form->textField($model,'device_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'media_id'); ?>
		<?php echo $form->textField($model,'media_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'index_num'); ?>
		<?php echo $form->textField($model,'index_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_time'); ?>
		<?php echo $form->textField($model,'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_user_id'); ?>
		<?php echo $form->textField($model,'create_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_time'); ?>
		<?php echo $form->textField($model,'update_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_user_id'); ?>
		<?php echo $form->textField($model,'update_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'play_status'); ?>
		<?php echo $form->textField($model,'play_status',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'play_timestamp'); ?>
		<?php echo $form->textField($model,'play_timestamp'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'play_posttime'); ?>
		<?php echo $form->textField($model,'play_posttime'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->