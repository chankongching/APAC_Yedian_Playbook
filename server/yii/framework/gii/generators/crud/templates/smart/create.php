<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

$this->layout = '/layouts/admin';
<?php
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	Yii::t('site', '$label')=>array('index'),
	Yii::t('site', 'Create'),
);\n";
?>

$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', '<?php echo $label; ?>');

$this->menu=array(
	array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);

$error_info = CHtml::errorSummary($model);

?>

<style type="text/css">
    div.errorMessage {
        color: #BC628B;
        margin-top: 5px;
        margin-bottom: 5px;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
    }
    form span.required {
        color: #FF0000;
    }    
</style>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-folder-open"></i> <?php echo "<?php"; ?> echo Yii::t('<?php echo $this->modelClass; ?>', 'Create <?php echo $this->modelClass; ?>') ?>
            </h2>
            <div class="box-icon">
                <a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <?php echo "<?php"; ?> if (!empty($error_info)): ?>
                <div class="flash-error">
                    <?php echo "<?php"; ?> echo $error_info; ?>
                </div>
            <?php echo "<?php"; ?> endif; ?>

            <?php echo "<?php"; ?> $this->renderPartial('_form', array('model' => $model)); ?>

        </div><!-- form -->
    </div><!--/span-->

</div><!--/row-->
