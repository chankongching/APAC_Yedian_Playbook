<?php
/* @var $this MediaController */
/* @var $model Media */

$this->breadcrumbs=array(
	'Medias'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Media', 'url'=>array('index')),
	array('label'=>'Create Media', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#media-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Medias</h1>

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
	'id'=>'media-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'songid',
		'name',
		'description',
		'lyrics',
		'artist_id',
		/*
		'category_id',
		'albumn_id',
		'duration',
		'bpic_filename',
		'bpic_url',
		'spic_filename',
		'spic_url',
		'light_tag',
		'led_tag',
		'video_filename',
		'video_url',
		'audio_filename',
		'audio_url',
		'audio_filename1',
		'audio_url1',
		'audio_filename2',
		'audio_url2',
		'status',
		'create_time',
		'create_user_id',
		'update_time',
		'update_user_id',
		'name_chinese',
		'name_pinyin',
		'lyrics_chinese',
		'lyrics_pinyin',
		'video_dir_name',
		'video_real_url',
		'video_codec',
		'video_res',
		'video_style',
		'song_style',
		'song_lang',
		'video_version',
		'hot_grade',
		'play_count',
		'is_new',
		'origin_audio',
		'qc_grade',
		'video_comment',
		'audio_volume1',
		'audio_volume2',
		'audio_count',
		'name_count',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
