<?php
/* @var $this ArtistcategoryController */
/* @var $model Artistcategory */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'artistcategory-form',
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
    <legend><?php echo Yii::t('Artistcategory', 'Fields with {required} are required to {operate} artistcategory.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <!-- div class="control-group">
	<?php echo $form->labelEx($model,'parent_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'parent_id'); ?>
		<?php echo $form->error($model,'parent_id'); ?>
        </div>
    </div -->

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

    <?php if ($model->isNewRecord) { ?>
        <div class="control-group">
            <?php echo $form->labelEx($model,'bpic_file', array('class' => 'control-label', 'label' => Yii::t('Artistcategory','Upload Bpic Url'))); ?>
            <div class="controls">
                    <input class="input-file uniform_on" id="ytFiles_bpic_file" name="Artistcategory[bpic_file]" type="file">
                    <?php echo $form->error($model,'bpic_file'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->labelEx($model,'spic_file', array('class' => 'control-label', 'label' => Yii::t('Artistcategory','Upload Spic Url'))); ?>
            <div class="controls">
                    <input class="input-file uniform_on" id="ytFiles_spic_file" name="Artistcategory[spic_file]" type="file">
                    <?php echo $form->error($model,'spic_file'); ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="control-group">
            <?php echo $form->labelEx($model,'bpic_filename', array('class' => 'control-label', 'label' => Yii::t('Artistcategory','Exists Bpic Url'))); ?>
            <div class="controls">
                    <?php 
                    //echo $form->textField($model,'bpic_filename',array('size'=>60,'maxlength'=>255)); 
                    echo (empty($model->bpic_url) ? "" : CHtml::image($model->attach_url . "/" . $model->bpic_url, Yii::t("files", "View big picture"), array("style" => "height:50px;")));
                    ?>
                    <?php echo $form->error($model,'bpic_filename'); ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo $form->labelEx($model,'bpic_file', array('class' => 'control-label', 'label' => Yii::t('Artistcategory','Update Bpic Url'))); ?>
            <div class="controls">
                    <input class="input-file uniform_on" id="ytFiles_bpic_file" name="Artistcategory[bpic_file]" type="file">
                    <?php //echo $form->textField($model,'bpic_url',array('size'=>60,'maxlength'=>255)); ?>
                    <?php echo $form->error($model,'bpic_file'); ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo $form->labelEx($model,'spic_filename', array('class' => 'control-label', 'label' => Yii::t('Artistcategory','Exists Spic Url'))); ?>
            <div class="controls">
                    <?php 
                    //echo $form->textField($model,'spic_filename',array('size'=>60,'maxlength'=>255)); 
                    echo (empty($model->spic_url) ? "" : CHtml::image($model->attach_url . "/" . $model->spic_url, Yii::t("files", "View small picture"), array("style" => "height:50px;")));
                    ?>
                    <?php echo $form->error($model,'spic_filename'); ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo $form->labelEx($model,'spic_file', array('class' => 'control-label', 'label' => Yii::t('Artistcategory','Update Spic Url'))); ?>
            <div class="controls">
                    <input class="input-file uniform_on" id="ytFiles_spic_file" name="Artistcategory[spic_file]" type="file">
                    <?php //echo $form->textField($model,'spic_url',array('size'=>60,'maxlength'=>255)); ?>
                    <?php echo $form->error($model,'spic_file'); ?>
            </div>
        </div>
    <?php } ?>

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

