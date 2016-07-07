<?php
/* @var $this CategoryController */
/* @var $model Category */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'category-form',
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
    <legend><?php echo Yii::t('Category', 'Fields with {required} are required to {operate} category.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'name', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
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
	<?php echo $form->labelEx($model,'bpic_filename', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'bpic_filename',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'bpic_filename'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'bpic_url', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'bpic_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'bpic_url'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'spic_filename', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'spic_filename',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'spic_filename'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'spic_url', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'spic_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'spic_url'); ?>
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

