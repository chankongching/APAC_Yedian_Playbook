<?php
/* @var $this YiiSessionController */
/* @var $model YiiSession */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'yiisession-form',
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
    <legend><?php echo Yii::t('YiiSession', 'Fields with {required} are required to {operate} yiisession.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'id',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'id'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'uid', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'uid'); ?>
		<?php echo $form->error($model,'uid'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'ip', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'ip',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'ip'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'expire', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'expire'); ?>
		<?php echo $form->error($model,'expire'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'data', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textArea($model,'data',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'data'); ?>
        </div>
    </div>


    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

