<?php
/* @var $this MusicofchartsController */
/* @var $model Musicofcharts */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'musicofcharts-form',
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
    <legend><?php echo Yii::t('Musicofcharts', 'Fields with {required} are required to {operate} musicofcharts.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'media_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'media_id'); ?>
		<?php echo $form->error($model,'media_id'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'chart_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'chart_id'); ?>
		<?php echo $form->error($model,'chart_id'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'rank', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'rank'); ?>
		<?php echo $form->error($model,'rank'); ?>
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
	<?php echo $form->labelEx($model,'status', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
        </div>
    </div>


    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

