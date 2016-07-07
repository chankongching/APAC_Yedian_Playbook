<?php
/* @var $this RoomController */
/* @var $model Room */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'room-form',
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
    <legend><?php echo Yii::t('Room', 'Fields with {required} are required to {operate} room.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'roomid', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'roomid',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'roomid'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'vendor_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'vendor_id', Vendor::model()->getVendorOptions(), array('data-rel' => 'chosen')); ?>
		<?php echo $form->error($model,'vendor_id'); ?>
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

    <!--
    <div class="control-group">
	<?php echo $form->labelEx($model,'status', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
        </div>
    </div>
    -->

    <div class="control-group">
	<?php echo $form->labelEx($model,'room_type', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'room_type', $model->getRoomTypeOptions(), array('data-rel' => 'chosen')); ?>
		<?php echo $form->error($model,'room_type'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'price', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'price',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'price'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'content', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'content'); ?>
        </div>
    </div>

    <?php if ($model->isNewRecord) { ?>
        <div class="control-group">
            <?php echo $form->labelEx($model, 'smallfile', array('class' => 'control-label')); ?>
            <div class="controls">
                <input class="input-file uniform_on" id="ytFiles_file" name="Hdpic[smallfile]" type="file">
                <?php echo $form->error($model, 'smallfile'); ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="control-group">
            <?php echo $form->labelEx($model, 'smallpic_url', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo CHtml::link(CHtml::image(Yii::app()->createUrl('//') . '/uploads/room/' . $model->smallpic_url, '', array('style'=>'width:210px;')),Yii::app()->createUrl('//') . '/uploads/room/' . $model->smallpic_url); ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo $form->labelEx($model, 'smallfile', array('class' => 'control-label')); ?>
            <div class="controls">
                <input class="input-file uniform_on" id="ytFiles_updatefile" name="Room[smallfile]" type="file">
                <?php echo $form->error($model, 'smallfile'); ?>
            </div>
        </div>
    <?php } ?>
    
    <?php if ($model->isNewRecord) { ?>
        <div class="control-group">
            <?php echo $form->labelEx($model, 'bigfile', array('class' => 'control-label')); ?>
            <div class="controls">
                <input class="input-file uniform_on" id="ytFiles_file" name="Hdpic[bigfile]" type="file">
                <?php echo $form->error($model, 'bigfile'); ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="control-group">
            <?php echo $form->labelEx($model, 'bigpic_url', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo CHtml::link(CHtml::image(Yii::app()->createUrl('//') . '/uploads/room/' . $model->bigpic_url, '', array('style'=>'width:210px;')),Yii::app()->createUrl('//') . '/uploads/room/' . $model->bigpic_url); ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo $form->labelEx($model, 'bigfile', array('class' => 'control-label')); ?>
            <div class="controls">
                <input class="input-file uniform_on" id="ytFiles_updatefile" name="Room[bigfile]" type="file">
                <?php echo $form->error($model, 'bigfile'); ?>
            </div>
        </div>
    <?php } ?>
    
    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

