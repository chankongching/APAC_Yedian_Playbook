<?php
/* @var $this AlbumnController */
/* @var $model Albumn */

$this->breadcrumbs=array(
	'Albumns'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Albumn', 'url'=>array('index')),
	array('label'=>'Create Albumn', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#albumn-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Albumns</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'albumn-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'description',
		'publish_time',
		'bpic_filename',
		'bpic_url',
		/*
		'spic_filename',
		'spic_url',
		'status',
		'create_time',
		'create_user_id',
		'update_time',
		'update_user_id',
		'artists',
		'name_chinese',
		'name_pinyin',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
