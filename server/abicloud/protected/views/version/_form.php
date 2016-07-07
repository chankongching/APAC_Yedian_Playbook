<?php
/* @var $this VersionController */
/* @var $model Version */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'version-form',
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
    <legend><?php echo Yii::t('Version', 'Fields with {required} are required to {operate} version.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'name', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'content', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'content',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'content'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'os', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'os',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'os'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'file_name', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'file_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'file_name'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'version', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'version',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'version'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'force_update', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'force_update'); ?>
		<?php echo $form->error($model,'force_update'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'download_url', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'download_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'download_url'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'application_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'application_id'); ?>
		<?php echo $form->error($model,'application_id'); ?>
        </div>
    </div>


    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

