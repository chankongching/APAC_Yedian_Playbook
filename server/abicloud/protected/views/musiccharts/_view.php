<?php
/* @var $this MusicchartsController */
/* @var $data Musiccharts */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bpic_filename')); ?>:</b>
	<?php echo CHtml::encode($data->bpic_filename); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bpic_url')); ?>:</b>
	<?php echo CHtml::encode($data->bpic_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('spic_filename')); ?>:</b>
	<?php echo CHtml::encode($data->spic_filename); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('spic_url')); ?>:</b>
	<?php echo CHtml::encode($data->spic_url); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->create_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->update_user_id); ?>
	<br />

	*/ ?>

</div>