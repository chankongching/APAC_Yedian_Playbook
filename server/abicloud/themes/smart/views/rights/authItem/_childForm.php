<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>
	
	<div class="control-group">
		<?php echo $form->dropDownList($model, 'itemname', $itemnameSelectOptions); ?>
		<?php echo $form->error($model, 'itemname'); ?>
	</div>
	
	<div class="control-group buttons">
		<?php echo CHtml::submitButton(Rights::t('core', 'Add')); ?>
	</div>

<?php $this->endWidget(); ?>

</div>