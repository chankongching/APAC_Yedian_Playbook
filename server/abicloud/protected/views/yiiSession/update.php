<?php
/* @var $this YiiSessionController */
/* @var $model YiiSession */

$this->layout = '/layouts/admin';
$this->breadcrumbs=array(
	'Yii Sessions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('site', 'Update'),
);
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'Yii Sessions')  . ' - ' . $model->id;

$this->menu=array(
	array('label'=>'List YiiSession', 'url'=>array('index')),
	array('label'=>'Create YiiSession', 'url'=>array('create')),
	array('label'=>'View YiiSession', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage YiiSession', 'url'=>array('admin')),
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
            <h2><i class="icon-folder-open"></i> <?php echo Yii::t('YiiSession', 'Update YiiSession') ?> ( <?php echo $model->id; ?> )
            </h2>
            <div class="box-icon">
                <a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <?php if (!empty($error_info)): ?>
                <div class="flash-error">
                    <?php echo $error_info; ?>
                </div>
            <?php endif; ?>

            <?php $this->renderPartial('_form', array('model' => $model)); ?>

        </div><!-- form -->
    </div><!--/span-->

</div><!--/row-->

