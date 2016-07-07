<?php
$this->widget('CExGridView', array(
    'id' => 'artist-grid',
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
	'name_chinese',
	'bpic_url',
    ),
));
?>
