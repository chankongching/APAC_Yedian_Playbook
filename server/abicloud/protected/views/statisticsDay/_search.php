<?php
/* @var $this StatisticsController */
/* @var $model Statistics */
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
		<?php echo $form->label($model,'calltime'); ?>
		<?php echo $form->textField($model,'calltime'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'call_api'); ?>
		<?php echo $form->textField($model,'call_api',array('size'=>60,'maxlength'=>64)); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'call_count'); ?>
		<?php echo $form->textField($model,'call_count'); ?>
	</div>

	<div class="control-group">
		<?php echo $form->label($model,'call_type'); ?>
		<?php echo $form->textField($model,'call_type',array('size'=>60,'maxlength'=>64)); ?>
	</div>

	<div class="control-group buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->