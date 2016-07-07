<?php
$this->widget('CExGridView', array(
    'id' => 'statisticsday-grid',
    'dataProvider' => $model,
    'exportMode' => $exportMode,
    'template' => '{items}',
    'hideHeader' => false,
    'hideFooter' => true,
    'ajaxUpdate' => false,
    'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
    'columns' => array(
        array('name'=>'calltime','value'=>'date("Y-m-d H:i:s",$data->calltime)'),
	'call_api',
	'call_type',
	'call_count',
    ),
));
?>
