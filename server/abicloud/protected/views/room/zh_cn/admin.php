<?php
/* @var $this RoomController */
/* @var $model Room */

$this->layout = '/layouts/admin_search';
$this->breadcrumbs=array(
	Yii::t('site', 'Rooms'),
);
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'Rooms');
$total = $model->count();

$this->menu=array(
	array('label'=>'List Room', 'url'=>array('index')),
	array('label'=>'Create Room', 'url'=>array('create')),
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
            <h2><i class="icon-picture"></i> <?php echo Yii::t('site', 'Rooms') ?> ( <?php echo $total; ?> <?php echo ($total > 1) ? Yii::t('site', 'rooms') : Yii::t('Room', 'room'); ?> )</h2>
            <div class="box-icon">
                <!-- a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a -->
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
            <!-- div style="float:right;"><a href="#" class="btn search-button"><?php echo Yii::t('Room', 'Advanced Search') ?></a></div -->
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <?php if( Yii::app()->user->checkAccess('Room.*') || Yii::app()->user->checkAccess('Room.create') ) { ?>
                <div class="btn-group">
                    <a class="btn btn-success btn-round btn-add-file" href="<?php echo $this->createUrl('create'); ?>">
                        <i class="icon-plus icon-white"></i>  
                        <?php echo Yii::t('Room', 'Add Room') ?>                                            
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
            
            <!-- Begin Room list -->
            <?php
                    $_buttons = array();
                    $_template = '';
                    if( Yii::app()->user->checkAccess('Room.*') || Yii::app()->user->checkAccess('Room.View') ) { 
                        $_buttons['view'] = array(
                                        'label' => '',
                                        'url' => 'Yii::app()->createUrl("room/view", array("id"=>$data->id))',
                                        'options' => array('class' => 'icon icon-color icon-search view', 'title' => Yii::t('Room', 'View this room'))
                                    );
                        $_template .= '{view}';
                    }
                    if( Yii::app()->user->checkAccess('Room.*') || Yii::app()->user->checkAccess('Room.Update') ) { 
                        $_buttons['update'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-edit update', 'title' => Yii::t('Room', 'Update this room'))
                            );
                        if(empty($_template)) {
                            $_template .= '{update}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{update}';
                        }
                    }
                    if( Yii::app()->user->checkAccess('Room.*') || Yii::app()->user->checkAccess('Room.Delete') ) { 
                        $_buttons['delete'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-close delete', 'title' => Yii::t('Room', 'Delete this room'))
                            );
                        if(empty($_template)) {
                            $_template .= '{delete}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{delete}';
                        }
                    }
                    if( Yii::app()->user->checkAccess('Room.*') || Yii::app()->user->checkAccess('Room.Reset') ) { 
                        $_buttons['reset'] = array(
                                        'label' => '',
                                        'url' => 'Yii::app()->createUrl("room/reset", array("id"=>$data->id))',
                                        'options' => array('class' => 'icon icon-color icon-locked reset', 'title' => Yii::t('Room', 'Reset this room'))
                                    );
                        if(empty($_template)) {
                            $_template .= '{reset}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{reset}';
                        }
                    }
                    if( Yii::app()->user->checkAccess('Room.*') || Yii::app()->user->checkAccess('Room.Checkin') ) { 
                        $_buttons['checkin'] = array(
                                        'label' => '',
                                        'url' => 'Yii::app()->createUrl("room/checkin", array("id"=>$data->id, "returnUrl"=>"' . Yii::app()->getRequest()->getUrl() . '", "ajax"=>1))',
                                        //'url' => 'Yii::app()->createUrl("room/checkin", array("id"=>$data->id))',
                                        'options' => array('class' => 'icon icon-color icon-unlocked checkin', 'title' => Yii::t('Room', 'Open this room'))
                                    );
                        if(empty($_template)) {
                            $_template .= '{checkin}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{checkin}';
                        }
                    }

            $this->widget('CExGridView', array(
                'id' => 'room-grid',
                'dataProvider' => $model->search(),
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                'ajaxUpdate' => false,
                'filter' => $model,
                'columns' => array(
	'id',
	'roomid',
	//'vendor_id',
	'name',
	//'description',
	//'status',
                    array('name' => 'status', 'value' => '$data->getStatusName()'),
	//'create_time',
	//'create_user_id',
	//'update_time',
	//'update_user_id',
	//'room_type',
                    array('name' => 'room_type', 'value' => '$data->getRoomTypeName($data->room_type)'),
	//'price',
	//'content',
	//'smallpic_url',
	//'bigpic_url',
	//'smallpic_filename',
	//'bigpic_filename',                
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
        <p><?php echo Yii::t('Room', 'Are you sure to delete all rooms?') ?></p>
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
        <p><?php echo Yii::t('Room', 'Are you sure to delete this room?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo '#'; ?>" class="btn btn-confirm-delete"><?php echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div>
<div class="modal hide fade" id="myResetConfirmModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('Room', 'Are you sure to reset this room?') ?></p>
        <span><?php echo Yii::t('Room', 'Notice: reset in will clear all records of this room, <br>customers can not check in anymore.') ?></span>
    </div>
    <div class="modal-footer">
        <a href="<?php echo '#'; ?>" class="btn btn-confirm-delete"><?php echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div>
<div class="modal hide fade" id="myCheckinConfirmModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('Room', 'Are you sure to open this room?') ?></p>
        <span><?php echo Yii::t('Room', 'Notice: open room will reset all records of this room, <br>and will create a new check in QR code for new customers to check in.') ?></span>
        <p>&nbsp;</p>
        <?php $this->renderPartial('_bind'); ?>
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
            jQuery('#room-grid').yiiGridView('update', {
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
            jQuery('#myConfirmAllModal div.modal-header h3').html('<?php echo Yii::t("Room", "Delete All rooms Confirm Window") ?>');
            jQuery('#myConfirmAllModal div.modal-body p').html('<?php echo Yii::t("Room", "Are you sure to delete all rooms?") ?>');
            jQuery('#myConfirmAllModal div.modal-footer a.btn-dialog-confirm-all').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmAllModal').modal('show');
            return false;
        });
        jQuery('.button-column a.delete').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td').eq(2).html();
            jQuery('#myConfirmModal div.modal-header h3').html('<?php echo Yii::t('site', 'Delete Confirm Window') ?>');
            jQuery('#myConfirmModal div.modal-body p').html('<?php echo Yii::t("Room", "Are you sure to delete room") ?>' + ' ' + name + '?');
            jQuery('#myConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmModal').modal('show');
            return false;
        });
        jQuery('.button-column a.reset').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td').eq(2).html();
            jQuery('#myResetConfirmModal div.modal-header h3').html('<?php echo Yii::t('site', 'Reset Room Confirm Window') ?>');
            jQuery('#myResetConfirmModal div.modal-body p').html('<?php echo Yii::t("Room", "Are you sure to reset room") ?>' + ' ' + name + '?');
            jQuery('#myResetConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myResetConfirmModal').modal('show');
            return false;
        });
        jQuery('.button-column a.checkin').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td').eq(2).html();
            jQuery('#myCheckinConfirmModal div.modal-header h3').html('<?php echo Yii::t('site', 'Open Room Confirm Window') ?>');
            jQuery('#myCheckinConfirmModal div.modal-body p').html('<?php echo Yii::t("Room", "Are you sure to open room") ?>' + ' ' + name + '?');
            jQuery('#myCheckinConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myCheckinConfirmModal').modal('show');
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
    jQuery(document).on('click', '#myResetConfirmModal a.btn-confirm-delete', function() {
        jQuery.ajax({
            url: jQuery(this).attr('href'),
            type: 'POST',
            success: function(result) {
                //var jsonParsedObject = JSON.parse(result);
                //alert(jsonParsedObject.redirect);
                //alert('Room reset success!');
                window.location.href = '<?php echo $this->createUrl("checkinCode/index"); ?>';
            },
            error: function(result) {
                alert(result.responseText);
            },
            cache: false,
        });
        return false;
    });
    jQuery(document).on('click', '#myCheckinConfirmModal a.btn-confirm-delete', function() {
        var selected_hour_id = jQuery('#myCheckinConfirmModal #select-hour-id').val();
        if(typeof(selected_hour_id) == "undefined") {
            alert('选择错误，请重试。');
            return false;
        }
        jQuery.ajax({
            url: jQuery(this).attr('href') + '&hours=' + selected_hour_id,
            type: 'GET',
            success: function(result) {
                //var jsonParsedObject = JSON.parse(result);
                //alert(jsonParsedObject.redirect);
                //alert('New check in success!');
                window.location.href = '<?php echo $this->createUrl("checkinCode/index"); ?>';
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
