<?php
/* @var $this DeviceController */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>'#',
	'method'=>'get',
)); ?>

	<div class="control-group">
		<?php echo CHtml::label('选择Room', 'select-room-id'); ?>
		<?php echo CHtml::dropDownList('select-room-id','', Room::model()->getRoomOptions()); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->