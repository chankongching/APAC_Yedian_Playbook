<?php
/* @var $this PlatformUserController */
/* @var $model PlatformUser */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'platformuser-form',
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
    <legend><?php echo Yii::t('PlatformUser', 'Fields with {required} are required to {operate} platformuser.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'username', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'username'); ?>
        </div>
    </div>

    <!--
    <div class="control-group">
	<?php echo $form->labelEx($model,'email', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'mobile', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'mobile',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'mobile'); ?>
        </div>
    </div>
    -->

    <div class="control-group">
	<?php echo $form->labelEx($model,'password', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'password'); ?>
        </div>
    </div>

    <!--
    <div class="control-group">
	<?php echo $form->labelEx($model,'role', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'role',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'role'); ?>
        </div>
    </div>
    -->

    <div class="control-group">
	<?php echo $form->labelEx($model,'openid', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'openid',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'openid'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'token', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'token',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'token'); ?>
        </div>
    </div>

    <!--
    <div class="control-group">
	<?php echo $form->labelEx($model,'type', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'type'); ?>
		<?php echo $form->error($model,'type'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'profile_data', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textArea($model,'profile_data',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'profile_data'); ?>
        </div>
    </div>
    -->

    <div class="control-group">
	<?php echo $form->labelEx($model,'display_name', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'display_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'display_name'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'avatar_url', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'avatar_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'avatar_url'); ?>
        </div>
    </div>

    <!--
    <div class="control-group">
	<?php echo $form->labelEx($model,'last_login_time', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'last_login_time'); ?>
		<?php echo $form->error($model,'last_login_time'); ?>
        </div>
    </div>
    -->

    <div class="control-group">
	<?php echo $form->labelEx($model,'auth_type', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'auth_type',$model->getAuthTypeOptions(), array('data-rel' => 'chosen')); ?>
		<?php echo $form->error($model,'auth_type'); ?>
        </div>
    </div>

    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

