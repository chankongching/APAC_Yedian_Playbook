<?php
/* @var $this DevicePlaylistController */
/* @var $model DevicePlaylist */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'deviceplaylist-form',
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
    <legend><?php echo Yii::t('DevicePlaylist', 'Fields with {required} are required to {operate} deviceplaylist.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'device_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'device_id'); ?>
		<?php echo $form->error($model,'device_id'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'media_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'media_id'); ?>
		<?php echo $form->error($model,'media_id'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'index_num', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'index_num'); ?>
		<?php echo $form->error($model,'index_num'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'status', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'play_status', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'play_status',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'play_status'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'play_timestamp', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'play_timestamp'); ?>
		<?php echo $form->error($model,'play_timestamp'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'play_posttime', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'play_posttime'); ?>
		<?php echo $form->error($model,'play_posttime'); ?>
        </div>
    </div>


    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

