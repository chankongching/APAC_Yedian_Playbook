<?php
$this->widget('CExGridView', array(
    'id' => 'artistofcategory-grid',
    'dataProvider' => $model,
    'exportMode' => $exportMode,
    'template' => '{items}',
    'hideHeader' => false,
    'hideFooter' => true,
    'ajaxUpdate' => false,
    'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
    'columns' => array(
	'category_id',
	'artist_id',
	'category_name',
	'artist_name',
    ),
));
?>
