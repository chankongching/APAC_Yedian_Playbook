<?php
$this->widget('CExGridView', array(
    'id' => 'artistcategory-grid',
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
	'bpic_url',
	'spic_url',
    ),
));
?>
