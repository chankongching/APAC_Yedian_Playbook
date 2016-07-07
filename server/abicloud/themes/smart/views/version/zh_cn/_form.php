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
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
        ));
?>

<fieldset>
    <legend><?php echo Yii::t('files', 'Fields with {required} are required to {operate} version.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('files', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'application_id', array('class' => 'control-label')); ?>
        <label class="controls">
            <?php echo $form->dropDownList($model, 'application_id', $model->getApplicationOptions(), array('data-rel' => 'chosen')); ?>
        </label>
    </div>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'name', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'name', array('maxlength' => 255, 'class' => 'span6')); ?>
            <?php echo $form->error($model, 'name'); ?>
        </div>
    </div>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'version', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'version', array('maxlength' => 255, 'class' => 'span6')); ?>
            <?php echo $form->error($model, 'version'); ?>
        </div>
    </div>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'version_code', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'version_code', array('maxlength' => 255, 'class' => 'span6')); ?>
            <?php echo $form->error($model, 'version_code'); ?>
        </div>
    </div>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'app_type', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'app_type', $model->getTypeOptions(), array('data-rel' => 'chosen')); ?>
        </div>
    </div>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'os', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'os', $model->getOsOptions(), array('data-rel' => 'chosen')); ?>
        </div>
    </div>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'content', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->textArea($model, 'content', array('rows' => 6, 'class' => 'span6')); ?>
            <?php echo $form->error($model, 'content'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'force_update', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'force_update', $model->getUpdateOptions(), array('data-rel' => 'chosen')); ?>
        </div>
    </div>

    <?php if ($model->isNewRecord) { ?>
        <div class="control-group">
            <?php echo $form->labelEx($model, 'file', array('class' => 'control-label')); ?>
            <div class="controls">
                <input class="input-file uniform_on" id="ytVersion_file" name="Version[file]" type="file">
                <?php echo $form->error($model, 'file'); ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="control-group">
            <?php echo $form->labelEx($model, 'file_name', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo $model->file_name; ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo $form->labelEx($model, 'updatefile', array('class' => 'control-label')); ?>
            <div class="controls">
                <input class="input-file uniform_on" id="ytVersion_updatefile" name="Version[updatefile]" type="file">
                <?php echo $form->error($model, 'updatefile'); ?>
            </div>
        </div>
    
    <?php } ?>



    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('files', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('files', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

