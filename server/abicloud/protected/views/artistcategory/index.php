<?php
/* @var $this ArtistcategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->layout = '/layouts/admin';
$this->breadcrumbs=array(
	Yii::t('site', 'Artistcategories'),
);
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'Artistcategories');
$total = $dataProvider->getTotalItemCount();

$this->menu=array(
	array('label'=>'Create Artistcategory', 'url'=>array('create')),
	array('label'=>'Manage Artistcategory', 'url'=>array('admin')),
);
?>


<style type="text/css">
@media (max-width: 480px) {
	.grid-view table td:nth-child(4),
	.grid-view table th:nth-child(4),
	.grid-view table td:nth-child(5),
	.grid-view table th:nth-child(5),
	.grid-view table td:nth-child(6),
	.grid-view table th:nth-child(6),
	.grid-view table td:nth-child(7),
	.grid-view table th:nth-child(7),
	.grid-view table td:nth-child(8),
	.grid-view table th:nth-child(8),
	.grid-view table td:nth-child(9),
	.grid-view table th:nth-child(9) {display:none;}
}
@media (max-width: 767px) {
	.grid-view table td:nth-child(6),
	.grid-view table th:nth-child(6),
	.grid-view table td:nth-child(7),
	.grid-view table th:nth-child(7),
	.grid-view table td:nth-child(8),
	.grid-view table th:nth-child(8),
	.grid-view table td:nth-child(9),
	.grid-view table th:nth-child(9) {display:none;}
}
@media (min-width: 768px) and (max-width: 979px) {
	.grid-view table td:nth-child(8),
	.grid-view table th:nth-child(8),
		.grid-view table td:nth-child(9),
		.grid-view table th:nth-child(9) {display:none;}
}
</style>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-picture"></i> <?php echo Yii::t('site', 'Artistcategories') ?> ( <?php echo $total; ?> <?php echo ($total > 1) ? Yii::t('site', 'artistcategories') : Yii::t('Artistcategory', 'artistcategory'); ?> )</h2>
            <div class="box-icon">
                <!-- a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a -->
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <div class="btn-group">
                    <a class="btn btn-success btn-round btn-add-file" href="<?php echo $this->createUrl('create'); ?>">
                        <i class="icon-plus icon-white"></i>  
                        <?php echo Yii::t('Artistcategory', 'Add Artistcategory') ?>                                            
                    </a>
                </div>
            </div><!-- Toolbar -->
            <?php if (Yii::app()->user->hasFlash('update')): ?>
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('update'); ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::app()->user->hasFlash('create')): ?>
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('create'); ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::app()->user->hasFlash('delete')): ?>
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('delete'); ?>
                </div>
            <?php endif; ?>
            <!-- Begin Artistcategory list -->

            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'artistcategory-grid',
                'dataProvider' => $dataProvider,
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                //'filter' => $model,
                'ajaxUpdate' => false,
                'columns' => array(
	//'parent_id',
	'name',
	'description',
	'bpic_filename',
	'bpic_url',
	'spic_filename',
	'spic_url',
	'status',
	'create_time',
	//'create_user_id',
	//'update_time',
	//'update_user_id',
                
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
                                'options' => array('class' => 'icon icon-color icon-search view', 'title' => Yii::t('Artistcategory', 'View this artistcategory'))
                            ),
                            'update' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-edit update', 'title' => Yii::t('Artistcategory', 'Update this artistcategory'))
                            ),
                            'delete' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-close delete', 'title' => Yii::t('Artistcategory', 'Delete this artistcategory'))
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
        <h3><?php echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('Artistcategory', 'Are you sure to delete all artistcategories?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo $this->createUrl('deleteall'); ?>" class="btn btn-dialog-confirm-all"><?php echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div>
<div class="modal hide fade" id="myConfirmModal" dialogtype="confirm">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('Artistcategory', 'Are you sure to delete this artistcategory?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo '#'; ?>" class="btn btn-dialog-confirm"><?php echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div>

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
        jQuery('.btn-delete-allfile').click(function(e) {
            e.preventDefault();
            jQuery('#myConfirmAllModal').attr('dialogtype','deleteall');
            jQuery('#myConfirmAllModal div.modal-header h3').html('<?php echo Yii::t("Artistcategory", "Delete All artistcategories Confirm Window") ?>');
            jQuery('#myConfirmAllModal div.modal-body p').html('<?php echo Yii::t("Artistcategory", "Are you sure to delete all artistcategories?") ?>');
            jQuery('#myConfirmAllModal div.modal-footer a.btn-dialog-confirm-all').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmAllModal').modal('show');
            return false;
        });
        jQuery('.button-column a.delete').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td:first').html();
            jQuery('#myConfirmModal').attr('dialogtype','delete');
            jQuery('#myConfirmModal div.modal-header h3').html('<?php echo Yii::t("Artistcategory", "Delete artistcategory Confirm Window") ?>');
            jQuery('#myConfirmModal div.modal-body p').html('<?php echo Yii::t("Artistcategory", "Are you sure to delete artistcategory") ?>' + ' ' + name + '?');
            jQuery('#myConfirmModal div.modal-footer a.btn-dialog-confirm').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmModal').modal('show');
            return false;
        });
    });

    jQuery(document).on('click', '#myConfirmAllModal a.btn-dialog-confirm-all', function() {
        jQuery.ajax({
            url: jQuery(this).attr('href'),
            type: 'POST',
            success: function(result) {
                //var jsonParsedObject = JSON.parse(result);
                //window.location.href = jsonParsedObject.redirect;
                window.location.href = '<?php echo $this->createUrl("index"); ?>';
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
                window.location.href = '<?php echo $this->createUrl("index"); ?>';
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
