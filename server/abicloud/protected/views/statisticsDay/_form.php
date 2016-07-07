<?php
/* @var $this StatisticsController */
/* @var $model Statistics */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'statistics-form',
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
    <legend><?php echo Yii::t('Statistics', 'Fields with {required} are required to {operate} statistics.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'calltime', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'calltime'); ?>
		<?php echo $form->error($model,'calltime'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'call_api', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'call_api',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'call_api'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'call_count', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'call_count'); ?>
		<?php echo $form->error($model,'call_count'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'call_type', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'call_type',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'call_type'); ?>
        </div>
    </div>


    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

