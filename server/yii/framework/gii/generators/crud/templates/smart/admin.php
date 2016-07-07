<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

$this->layout = '/layouts/admin';
<?php
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	Yii::t('site', '$label'),
);\n";
?>
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', '<?php echo $label; ?>');
$total = $model->count();

$this->menu=array(
	array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
);

?>

<?php
$count=0;
$total_allow = 9;
foreach($this->tableSchema->columns as $column)
{
	if($column->isPrimaryKey)
		continue;
        if(++$count>=$total_allow) {
            break;
        }
}
?>
<style type="text/css">
<?php
echo "@media (max-width: 480px) {\n";
for($i=4;$i<$count;$i++) {
    echo "\t.grid-view table td:nth-child(${i}),\n";
    echo "\t.grid-view table th:nth-child(${i}),\n";
}
if($i == $count) {
    echo "\t.grid-view table td:nth-child(${i}),\n";
    echo "\t.grid-view table th:nth-child(${i}) {display:none;}\n";
}
echo "}\n";
echo "@media (max-width: 767px) {\n";
for($i=6;$i<$count;$i++) {
    echo "\t.grid-view table td:nth-child(${i}),\n";
    echo "\t.grid-view table th:nth-child(${i}),\n";
}
if($i == $count) {
    echo "\t.grid-view table td:nth-child(${i}),\n";
    echo "\t.grid-view table th:nth-child(${i}) {display:none;}\n";
}
echo "}\n";
echo "@media (min-width: 768px) and (max-width: 979px) {\n";
for($i=8;$i<$count;$i++) {
    echo "\t.grid-view table td:nth-child(${i}),\n";
    echo "\t.grid-view table th:nth-child(${i}),\n";
}
if($i == $count) {
    echo "\t\t.grid-view table td:nth-child(${i}),\n";
    echo "\t\t.grid-view table th:nth-child(${i}) {display:none;}\n";
}
echo "}\n";
?>
.grid-view .filters input, .grid-view .filters select {
    width: 94%;
}
</style>

<p style="display:none;">
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-picture"></i> <?php echo "<?php"; ?> echo Yii::t('site', '<?php echo $label; ?>') ?> ( <?php echo "<?php"; ?> echo $total; ?> <?php echo "<?php"; ?> echo ($total > 1) ? Yii::t('site', '<?php  echo strtolower ($label); ?>') : Yii::t('<?php echo $this->modelClass; ?>', '<?php  echo strtolower ($this->modelClass); ?>'); ?> )</h2>
            <div class="box-icon">
                <a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
            <div style="float:right;"><a href="#" class="btn search-button"><?php echo "<?php"; ?> echo Yii::t('<?php echo $this->modelClass; ?>', 'Advanced Search') ?></a></div>
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <div class="btn-group">
                    <a class="btn btn-success btn-round btn-add-file" href="<?php echo "<?php"; ?> echo $this->createUrl('create'); ?>">
                        <i class="icon-plus icon-white"></i>  
                        <?php echo "<?php"; ?> echo Yii::t('<?php echo $this->modelClass; ?>', 'Add <?php echo $this->modelClass; ?>') ?>                                            
                    </a>
                </div>
            </div><!-- Toolbar -->
            <?php echo "<?php"; ?> if (Yii::app()->user->hasFlash('update')): ?>
                <div class="flash-success">
                    <?php echo "<?php"; ?> echo Yii::app()->user->getFlash('update'); ?>
                </div>
            <?php echo "<?php"; ?> endif; ?>
            <?php echo "<?php"; ?> if (Yii::app()->user->hasFlash('create')): ?>
                <div class="flash-success">
                    <?php echo "<?php"; ?> echo Yii::app()->user->getFlash('create'); ?>
                </div>
            <?php echo "<?php"; ?> endif; ?>
            <?php echo "<?php"; ?> if (Yii::app()->user->hasFlash('delete')): ?>
                <div class="flash-success">
                    <?php echo "<?php"; ?> echo Yii::app()->user->getFlash('delete'); ?>
                </div>
            <?php echo "<?php"; ?> endif; ?>
            
<div class="modal hide fade" id="mySearchFormModal" dailogtype="search">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo "<?php"; ?> echo Yii::t('site', 'Advanced Search Window') ?></h3>
    </div>
    <div class="modal-body">
<?php echo "<?php"; ?> $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
    </div>
    <div class="modal-footer">
        <a href="<?php echo "<?php"; ?> echo '#'; ?>" class="btn btn-dialog-search" data-dismiss="modal"><?php echo "<?php"; ?> echo Yii::t('site', 'Search') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo "<?php"; ?> echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div><!-- search-form -->    
            
            <!-- Begin <?php echo $this->modelClass; ?> list -->

            <?php echo "<?php\r\n"; ?>
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => '<?php echo strtolower($this->modelClass); ?>-grid',
                'dataProvider' => $model->search(),
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                'ajaxUpdate' => false,
                'filter' => $model,
                'columns' => array(
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if($column->isPrimaryKey)
		continue;
        if(++$count>$total_allow) {
            echo "\t//'{$column->name}',\n";
        }
        else {
            echo "\t'{$column->name}',\n";
        }
}
?>
                
                    array(
                        'class' => 'CButtonColumn',
                        'htmlOptions' => array('class' => 'button-column', 'style' => 'width:120px;'),
                        'viewButtonImageUrl' => false,
                        'updateButtonImageUrl' => false,
                        'deleteButtonImageUrl' => false,
                        'buttons' => array(
                            //'view', 'update', //'delete',
                            'view' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-search view', 'title' => Yii::t('<?php echo $this->modelClass; ?>', 'View this <?php echo strtolower($this->modelClass); ?>'))
                            ),
                            'update' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-edit update', 'title' => Yii::t('<?php echo $this->modelClass; ?>', 'Update this <?php echo strtolower($this->modelClass); ?>'))
                            ),
                            'delete' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-close delete', 'title' => Yii::t('<?php echo $this->modelClass; ?>', 'Delete this <?php echo strtolower($this->modelClass); ?>'))
                            ),
                        ),
                        'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}',
                        'deleteConfirmation' => false,
                    ),
                ),
            ));
            ?>

            <!-- End file list -->

        </div>
    </div><!--/span-->

</div><!--/row-->

<div class="modal hide fade" id="myConfirmAllModal" dialogtype="confirm">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo "<?php"; ?> echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo "<?php"; ?> echo Yii::t('<?php echo $this->modelClass; ?>', 'Are you sure to delete all <?php echo strtolower($label); ?>?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo "<?php"; ?> echo $this->createUrl('deleteall'); ?>" class="btn btn-dialog-confirm-all"><?php echo "<?php"; ?> echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo "<?php"; ?> echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div>
<div class="modal hide fade" id="myConfirmModal" dialogtype="confirm">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo "<?php"; ?> echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo "<?php"; ?> echo Yii::t('<?php echo $this->modelClass; ?>', 'Are you sure to delete this <?php echo strtolower($this->modelClass); ?>?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo "<?php"; ?> echo '#'; ?>" class="btn btn-dialog-confirm"><?php echo "<?php"; ?> echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo "<?php"; ?> echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div>

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
        jQuery('#mySearchFormModal form').submit(function(){
            jQuery('#<?php echo strtolower($this->modelClass); ?>-grid').yiiGridView('update', {
                data: $(this).serialize()
            });
            return false;
        });
        jQuery('a.search-button').click(function(e) {
            e.preventDefault();
            jQuery('#mySearchFormModal').modal('show');
            return false;
        });
        
        jQuery('.btn-delete-allfile').click(function(e) {
            e.preventDefault();
            jQuery('#myConfirmAllModal').attr('dialogtype','deleteall');
            jQuery('#myConfirmAllModal div.modal-header h3').html('<?php echo "<?php"; ?> echo Yii::t("<?php echo $this->modelClass; ?>", "Delete All <?php echo strtolower($label); ?> Confirm Window") ?>');
            jQuery('#myConfirmAllModal div.modal-body p').html('<?php echo "<?php"; ?> echo Yii::t("<?php echo $this->modelClass; ?>", "Are you sure to delete all <?php echo strtolower($label); ?>?") ?>');
            jQuery('#myConfirmAllModal div.modal-footer a.btn-dialog-confirm-all').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmAllModal').modal('show');
            return false;
        });
        jQuery('.button-column a.delete').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td:first').html();
            jQuery('#myConfirmModal').attr('dialogtype','delete');
            jQuery('#myConfirmModal div.modal-header h3').html('<?php echo "<?php"; ?> echo Yii::t("<?php echo $this->modelClass; ?>", "Delete <?php echo strtolower($this->modelClass); ?> Confirm Window") ?>');
            jQuery('#myConfirmModal div.modal-body p').html('<?php echo "<?php"; ?> echo Yii::t("<?php echo $this->modelClass; ?>", "Are you sure to delete <?php echo strtolower($this->modelClass); ?>") ?>' + ' ' + name + '?');
            jQuery('#myConfirmModal div.modal-footer a.btn-dialog-confirm').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmModal').modal('show');
            return false;
        });
    });

    jQuery(document).on('click', '#mySearchFormModal a.btn-dialog-search', function() {
        jQuery('#mySearchFormModal form').submit();
        return false;
    });
    
    jQuery(document).on('click', '#myConfirmAllModal a.btn-dialog-confirm-all', function() {
        jQuery.ajax({
            url: jQuery(this).attr('href'),
            type: 'POST',
            success: function(result) {
                //var jsonParsedObject = JSON.parse(result);
                //window.location.href = jsonParsedObject.redirect;
                window.location.href = '<?php echo "<?php"; ?> echo $this->createUrl("index"); ?>';
            },
            error: function(result) {
                alert(result.responseText);
            },
            cache: false,
        });
        return false;
    });
    jQuery(document).on('click', '#myConfirmModal a.btn-dialog-confirm', function() {
        jQuery.ajax({
            url: jQuery(this).attr('href'),
            type: 'POST',
            success: function(result) {
                //var jsonParsedObject = JSON.parse(result);
                //alert(jsonParsedObject.redirect);
                //window.location.href = jsonParsedObject.redirect;
                window.location.href = '<?php echo "<?php"; ?> echo $this->createUrl("index"); ?>';
            },
            error: function(result) {
                alert(result.responseText);
            },
            cache: false,
        });
        return false;
    });

    /*]]>*/
</script>
