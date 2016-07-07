<?php
/* @var $this QrcodeController */
/* @var $model Qrcode */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'qrcode-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
    //'htmlOptions' => array('class' => 'form-horizontal')
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
        ));
?>

<fieldset>
    <legend><?php echo Yii::t('Qrcode', 'Fields with {required} are required to {operate} QR code.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'activity_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'activity_id',$model->getActivityOptions(), array('data-rel' => 'chosen')); ?>
		<?php echo $form->error($model,'activity_id'); ?>
        </div>
    </div>

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
	<?php echo $form->labelEx($model,'status', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'status',$model->getStatusOptions(), array('data-rel' => 'chosen')); ?>
		<?php echo $form->error($model,'status'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'points', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'points',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'points'); ?>
        </div>
    </div>
    <div class="control-group">
	<?php echo $form->labelEx($model,'userlimit', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'userlimit',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'userlimit'); ?>
        </div>
    </div>
    <!-- div class="control-group">
	<?php echo $form->labelEx($model,'userdaylimit', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'userdaylimit',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'userdaylimit'); ?>
        </div>
    </div>
    <div class="control-group">
	<?php echo $form->labelEx($model,'usagelimit', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'usagelimit',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'usagelimit'); ?>
        </div>
    </div-->
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'usagetotal', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'usagetotal',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'usagetotal'); ?>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

