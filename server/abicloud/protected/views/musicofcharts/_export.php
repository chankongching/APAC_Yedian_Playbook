<?php
$this->widget('CExGridView', array(
    'id' => 'musicofcharts-grid',
    'dataProvider' => $model,
    'exportMode' => $exportMode,
    'template' => '{items}',
    'hideHeader' => false,
    'hideFooter' => true,
    'ajaxUpdate' => false,
    'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
    'columns' => array(
	'chart_id',
	'media_id',
	'rank',
	'category_name',
	'song_name',
    ),
));
?>
