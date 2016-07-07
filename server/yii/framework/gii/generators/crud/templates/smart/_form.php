<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>

<?php echo "<?php\n"; ?>
$form = $this->beginWidget('CActiveForm', array(
    'id' => '<?php echo strtolower($this->modelClass); ?>-form',
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
    <legend><?php echo "<?php"; ?> echo Yii::t('<?php echo $this->modelClass; ?>', 'Fields with {required} are required to {operate} <?php echo strtolower($this->modelClass); ?>.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
	if(in_array($column->name, array('create_time','update_time','create_user_id','update_user_id')))
		continue;
?>
    <div class="control-group">
	<?php echo "<?php echo "."\$form->labelEx(\$model,'{$column->name}', array('class' => 'control-label'))" . "; ?>\n"; ?>
        <div class="controls">
		<?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
		<?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
        </div>
    </div>

<?php
}
?>

    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo "<?php"; ?> echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo "<?php"; ?> echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php echo "<?php"; ?> $this->endWidget(); ?>

