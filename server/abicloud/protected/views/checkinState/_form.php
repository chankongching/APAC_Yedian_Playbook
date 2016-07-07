<?php
/* @var $this CheckinStateController */
/* @var $model CheckinState */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'checkinstate-form',
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
    <legend><?php echo Yii::t('CheckinState', 'Fields with {required} are required to {operate} checkinstate.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'room_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php //echo $form->textField($model,'room_id'); ?>
            <?php
            $_htmlOptions = $model->isNewRecord ? '' : array('disabled' => true);
            try {
                $this->widget('RelationWidget', array(
                    'model' => $model,
                    'relation' => 'room',
                    'fields' => array('roomid', 'name'),
                    'htmlOptions' => $_htmlOptions,
                    'showAddButton' => false,
                    'allowEmpty' => Yii::t('user', 'Root level'),
                ));
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
            ?>
            <?php echo $form->error($model, 'room_id'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'code', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'code', array('size' => 32, 'maxlength' => 32)); ?>
            <?php echo $form->error($model, 'code'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'start_time', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'start_time'); ?>
            <?php echo $form->error($model, 'start_time'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'expire', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'expire'); ?>
            <?php echo $form->error($model, 'expire'); ?>
        </div>
    </div>



    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

