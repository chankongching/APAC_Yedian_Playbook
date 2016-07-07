<?php
/* @var $this ArtistController */
/* @var $model Artist */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'artist-form',
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
    <legend><?php echo Yii::t('Artist', 'Fields with {required} are required to {operate} artist.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
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
	<?php echo $form->labelEx($model,'birthday', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'birthday'); ?>
		<?php echo $form->error($model,'birthday'); ?>
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

    <div class="control-group">
	<?php echo $form->labelEx($model,'country', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'country',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'country'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'periods', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'periods',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'periods'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'products', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'products',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'products'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'name_chinese', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'name_chinese',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name_chinese'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'name_pinyin', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'name_pinyin',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name_pinyin'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'name_count', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'name_count'); ?>
		<?php echo $form->error($model,'name_count'); ?>
        </div>
    </div>


    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

