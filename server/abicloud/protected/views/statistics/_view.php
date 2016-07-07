<?php
/* @var $this StatisticsController */
/* @var $data Statistics */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('calltime')); ?>:</b>
	<?php echo CHtml::encode($data->calltime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('call_api')); ?>:</b>
	<?php echo CHtml::encode($data->call_api); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('call_id')); ?>:</b>
	<?php echo CHtml::encode($data->call_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('call_ip')); ?>:</b>
	<?php echo CHtml::encode($data->call_ip); ?>
	<br />


</div>