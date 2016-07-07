<?php
/* @var $this DeviceController */
/* @var $model Device */

$this->layout = '/layouts/admin_search';
$this->breadcrumbs=array(
	Yii::t('site', 'Devices'),
);
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'Devices');
$total = $model->count();

$this->menu=array(
	array('label'=>'List Device', 'url'=>array('index')),
	array('label'=>'Create Device', 'url'=>array('create')),
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
            <h2><i class="icon-picture"></i> <?php echo Yii::t('site', 'Devices') ?> ( <?php echo $total; ?> <?php echo ($total > 1) ? Yii::t('site', 'devices') : Yii::t('Device', 'device'); ?> )</h2>
            <div class="box-icon">
                <!-- a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a -->
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
            <!-- div style="float:right;"><a href="#" class="btn search-button"><?php echo Yii::t('Device', 'Advanced Search') ?></a></div -->
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <?php if( Yii::app()->user->checkAccess('Device.*') || Yii::app()->user->checkAccess('Device.create') ) { ?>
                <div class="btn-group">
                    <a class="btn btn-success btn-round btn-add-file" href="<?php echo $this->createUrl('create'); ?>">
                        <i class="icon-plus icon-white"></i>  
                        <?php echo Yii::t('Device', 'Add Device') ?>                                            
                    </a>
                </div>
                <?php } ?>
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
            
<div class="modal hide fade" id="mySearchFormModal" dailogtype="search">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('site', 'Advanced Search Window') ?></h3>
    </div>
    <div class="modal-body">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
    </div>
    <div class="modal-footer">
        <a href="<?php echo '#'; ?>" class="btn btn-dialog-search" data-dismiss="modal"><?php echo Yii::t('site', 'Search') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div><!-- search-form -->    

<div class="modal hide fade" id="myBindFormModal" dailogtype="bind">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('site', 'Bind Device To Room Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('Device', 'Are you sure to bind this device?') ?></p>
<?php $this->renderPartial('_bind'); ?>
    </div>
    <div class="modal-footer">
        <a href="<?php echo '#'; ?>" class="btn btn-confirm-bind"><?php echo Yii::t('site', 'Bind') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div><!-- search-form -->    
            
            <!-- Begin Device list -->

            <?php
                    $_buttons = array();
                    $_template = '';
                    if( Yii::app()->user->checkAccess('Device.*') || Yii::app()->user->checkAccess('Device.View') ) { 
                        $_buttons['view'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-search view', 'title' => Yii::t('Device', 'View this device'))
                            );
                        $_template .= '{view}';
                    }
                    if( Yii::app()->user->checkAccess('Device.*') || Yii::app()->user->checkAccess('Device.Update') ) { 
                        $_buttons['update'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-edit update', 'title' => Yii::t('Device', 'Update this device'))
                            );
                        if(empty($_template)) {
                            $_template .= '{update}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{update}';
                        }
                    }
                    if( Yii::app()->user->checkAccess('Device.*') || Yii::app()->user->checkAccess('Device.Delete') ) { 
                        $_buttons['delete'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-close delete', 'title' => Yii::t('Device', 'Delete this device'))
                            );
                        if(empty($_template)) {
                            $_template .= '{delete}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{delete}';
                        }
                    }
            
                    if( Yii::app()->user->checkAccess('Device.*') || Yii::app()->user->checkAccess('Device.Bind') ) { 
                        $_buttons['bind'] = array(
                                'label' => '',
                                'url' => 'Yii::app()->createUrl("device/bind", array("id"=>$data->id, "returnUrl"=>"' . Yii::app()->getRequest()->getUrl() . '", "ajax"=>1))',
                                'options' => array('class' => 'icon icon-color icon-link bind', 'title' => Yii::t('Device', 'bind this device to room'))
                            );
                        if(empty($_template)) {
                            $_template .= '{bind}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{bind}';
                        }
                    }
                    if( Yii::app()->user->checkAccess('Device.*') || Yii::app()->user->checkAccess('Device.Unbind') ) { 
                        $_buttons['unbind'] = array(
                                'label' => '',
                                'url' => 'Yii::app()->createUrl("device/bind", array("id"=>$data->id, "returnUrl"=>"' . Yii::app()->getRequest()->getUrl() . '", "type"=>2))',
                                'options' => array('class' => 'icon icon-color icon-unlink unbind', 'title' => Yii::t('Device', 'unbind this device to any room'))
                            );
                        if(empty($_template)) {
                            $_template .= '{unbind}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{unbind}';
                        }
                    }
                    
            $this->widget('CExGridView', array(
                'id' => 'device-grid',
                'dataProvider' => $model->search(),
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                'ajaxUpdate' => false,
                'filter' => $model,
                'columns' => array(
	'id',
	'name',
	'imei',
	'ip',
	//'description',
	//'type',
                    array('name' => 'type', 'value' => '$data->getTypeName($data->type)'),
	//'status',
                    array('name' => 'status', 'value' => '$data->getStatusName()'),
                    array('name' => 'room_id', 'value' => '$data->getRoomName()'),
	//'create_time',
	//'create_user_id',
	//'update_time',
	//'update_user_id',
                
                    array(
                        'class' => 'CButtonColumn',
                        'htmlOptions' => array('class' => 'button-column', 'style' => 'width:150px;'),
                        'viewButtonImageUrl' => false,
                        'updateButtonImageUrl' => false,
                        'deleteButtonImageUrl' => false,
                        'buttons' => $_buttons,
                        'template' => $_template,
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
        <p><?php echo Yii::t('Device', 'Are you sure to delete all devices?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo $this->createUrl('deleteall'); ?>" class="btn btn-dialog-confirm-all"><?php echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div>
<div class="modal hide fade" id="myConfirmModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('Device', 'Are you sure to delete this device?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo '#'; ?>" class="btn btn-confirm-delete"><?php echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div>

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
        jQuery('#mySearchFormModal form').submit(function(){
            jQuery('#device-grid').yiiGridView('update', {
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
            jQuery('#myConfirmAllModal div.modal-header h3').html('<?php echo Yii::t("Device", "Delete All devices Confirm Window") ?>');
            jQuery('#myConfirmAllModal div.modal-body p').html('<?php echo Yii::t("Device", "Are you sure to delete all devices?") ?>');
            jQuery('#myConfirmAllModal div.modal-footer a.btn-dialog-confirm-all').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmAllModal').modal('show');
            return false;
        });
        jQuery('.button-column a.delete').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td').eq(1).html();
            jQuery('#myConfirmModal div.modal-body p').html('<?php echo Yii::t("Device", "Are you sure to delete device") ?>' + ' ' + name + '?');
            jQuery('#myConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmModal').modal('show');
            return false;
        });
        jQuery('.button-column a.unbind').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td').eq(1).html();
            jQuery('#myConfirmModal div.modal-body p').html('<?php echo Yii::t("Device", "Are you sure to unbind device") ?>' + ' ' + name + '?');
            jQuery('#myConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmModal').modal('show');
            return false;
        });
        jQuery('.button-column a.bind').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td').eq(1).html();
            jQuery('#myBindFormModal div.modal-body p').html('<?php echo Yii::t("Device", "Are you sure to bind device") ?>' + ' ' + name + '?');
            jQuery('#myBindFormModal div.modal-footer a.btn-confirm-bind').attr('href', jQuery(this).attr('href'));
            jQuery('#myBindFormModal').modal('show');
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
                window.location.href = '<?php echo $this->createUrl("index"); ?>';
            },
            error: function(result) {
                alert(result.responseText);
            },
            cache: false,
        });
        return false;
    });
    jQuery(document).on('click', '#myConfirmModal a.btn-confirm-delete', function() {
        jQuery.ajax({
            url: jQuery(this).attr('href'),
            type: 'POST',
            success: function(result) {
                //var jsonParsedObject = JSON.parse(result);
                //alert(jsonParsedObject.redirect);
                window.location.href = '<?php echo $this->createUrl("index"); ?>';
            },
            error: function(result) {
                alert(result.responseText);
            },
            cache: false,
        });
        return false;
    });
    jQuery(document).on('click', '#myBindFormModal a.btn-confirm-bind', function() {
        var selected_room_id = jQuery('#myBindFormModal #select-room-id').val();
        if(typeof(selected_room_id) == "undefined") {
            alert('选择错误，请重试。');
            return false;
        }
        
        jQuery.ajax({
            url: jQuery(this).attr('href') + '&room_id=' + selected_room_id,
            type: 'GET',
            success: function(result) {
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
