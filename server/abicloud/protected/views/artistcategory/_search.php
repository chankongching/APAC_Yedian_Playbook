<?php
/* @var $this ArtistcategoryController */
/* @var $model Artistcategory */
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
		<?php echo $form->label($model,'parent_id'); ?>
		<?php echo $form->textField($model,'parent_id'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'bpic_filename'); ?>
		<?php echo $form->textField($model,'bpic_filename',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'bpic_url'); ?>
		<?php echo $form->textField($model,'bpic_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'spic_filename'); ?>
		<?php echo $form->textField($model,'spic_filename',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'spic_url'); ?>
		<?php echo $form->textField($model,'spic_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
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