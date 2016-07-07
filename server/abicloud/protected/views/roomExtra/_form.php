<?php
/* @var $this RoomExtraController */
/* @var $model RoomExtra */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'roomextra-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form-horizontal')
    //'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
        ));
?>

<fieldset>
    <legend><?php echo Yii::t('RoomExtra', 'Fields with {required} are required to {operate} roomextra.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'room_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'room_id'); ?>
		<?php echo $form->error($model,'room_id'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'room_type', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'room_type'); ?>
		<?php echo $form->error($model,'room_type'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'price', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'price',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'price'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'description', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'smallpic_url', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'smallpic_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'smallpic_url'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'bigpic_url', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'bigpic_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'bigpic_url'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'smallpic_filename', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'smallpic_filename',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'smallpic_filename'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'bigpic_filename', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'bigpic_filename',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'bigpic_filename'); ?>
        </div>
    </div>


    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

