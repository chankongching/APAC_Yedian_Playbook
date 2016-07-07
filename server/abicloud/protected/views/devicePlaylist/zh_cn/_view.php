<?php
/* @var $this DevicePlaylistController */
/* @var $data DevicePlaylist */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('device_id')); ?>:</b>
	<?php echo CHtml::encode($data->device_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('media_id')); ?>:</b>
	<?php echo CHtml::encode($data->media_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('index_num')); ?>:</b>
	<?php echo CHtml::encode($data->index_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->create_user_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->update_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('play_status')); ?>:</b>
	<?php echo CHtml::encode($data->play_status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('play_timestamp')); ?>:</b>
	<?php echo CHtml::encode($data->play_timestamp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('play_posttime')); ?>:</b>
	<?php echo CHtml::encode($data->play_posttime); ?>
	<br />

	*/ ?>

</div>