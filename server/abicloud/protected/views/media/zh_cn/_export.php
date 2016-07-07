<?php
$this->widget('CExGridView', array(
    'id' => 'media-grid',
    'dataProvider' => $model,
    'exportMode' => $exportMode,
    'template' => '{items}',
    'hideHeader' => false,
    'hideFooter' => true,
    'ajaxUpdate' => false,
    'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
    'columns' => array(
	'id',
	'name',
	'name_pinyin',
	'artist_id',
	'artist_name',
	'artist_pinyin',
    ),
));
?>
