<?php
/* @var $this RoomBookingController */
/* @var $data RoomBooking */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('order_invoice')); ?>:</b>
	<?php echo CHtml::encode($data->order_invoice); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('order_amount')); ?>:</b>
	<?php echo CHtml::encode($data->order_amount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('order_code')); ?>:</b>
	<?php echo CHtml::encode($data->order_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('room_id')); ?>:</b>
	<?php echo CHtml::encode($data->room_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('room_status')); ?>:</b>
	<?php echo CHtml::encode($data->room_status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('duration')); ?>:</b>
	<?php echo CHtml::encode($data->duration); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('booking_time')); ?>:</b>
	<?php echo CHtml::encode($data->booking_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('expire')); ?>:</b>
	<?php echo CHtml::encode($data->expire); ?>
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