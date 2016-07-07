<?php
/* @var $this VersionController */
/* @var $model Version */

$this->breadcrumbs=array(
	'Versions'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Version', 'url'=>array('index')),
	array('label'=>'Create Version', 'url'=>array('create')),
	array('label'=>'Update Version', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Version', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Version', 'url'=>array('admin')),
);
?>

<h1>View Version #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'content',
		'version',
		'force_update',
		'download_url',
		'application_id',
		'create_time',
		'create_user_id',
		'update_time',
		'update_user_id',
	),
)); ?>
