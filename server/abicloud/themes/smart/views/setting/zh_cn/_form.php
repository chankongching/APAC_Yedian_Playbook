<?php
/* @var $this SettingController */
/* @var $model Setting */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'setting-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form-horizontal')
        ));
?>

<fieldset>
    <legend><?php echo Yii::t('setting', 'Fields with {required} are required to {operate} setting.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('setting', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'name', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'name', array('maxlength' => 255, 'class' => 'span6')); ?>
            <?php echo $form->error($model, 'name'); ?>
        </div>
    </div>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'content', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->textArea($model, 'content', array('rows' => 6, 'class' => 'span6')); ?>
            <?php echo $form->error($model, 'content'); ?>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('setting', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('setting', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

