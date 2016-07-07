<?php
/* @var $this RoomController */
/* @var $dataProvider CActiveDataProvider */

$this->layout = '/layouts/admin';
$this->breadcrumbs=array(
	Yii::t('site', 'Rooms'),
);
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'Rooms');
$total = $dataProvider->getTotalItemCount();

$this->menu=array(
	array('label'=>'Create Room', 'url'=>array('create')),
	array('label'=>'Manage Room', 'url'=>array('admin')),
);
?>


<style type="text/css">
</style>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-picture"></i> <?php echo Yii::t('site', 'Rooms') ?> ( <?php echo $total; ?> <?php if($total > 1) echo Yii::t('site', 'rooms'); else echo Yii::t('Room', 'room'); ?> )</h2>
            <div class="box-icon">
                <a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <div class="btn-group">
                    <a class="btn btn-success btn-round btn-add-file" href="<?php echo $this->createUrl('create'); ?>">
                        <i class="icon-plus icon-white"></i>  
                        <?php echo Yii::t('Room', 'Add Room') ?>                                            
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
            <!-- Begin Room list -->

            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'room-grid',
                'dataProvider' => $dataProvider,
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                //'filter' => $model,
                'columns' => array(
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
                        'buttons' => array(
                            //'view', 'update', //'delete',
                            'view' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-search view', 'title' => Yii::t('Room', 'View this room'))
                            ),
                            'update' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-edit update', 'title' => Yii::t('Room', 'Update this room'))
                            ),
                            'delete' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-close delete', 'title' => Yii::t('Room', 'Delete this room'))
                            ),
                            'reset' => array(
                                'label' => '',
                                'url' => 'Yii::app()->createUrl("room/reset", array("id"=>$data->id))',
                                'options' => array('class' => 'icon icon-color icon-locked reset', 'title' => Yii::t('Room', 'Reset this room'))
                            ),
                            'checkin' => array(
                                'label' => '',
                                'url' => 'Yii::app()->createUrl("room/checkin", array("id"=>$data->id, "returnUrl"=>"' . Yii::app()->getRequest()->getUrl() . '", "ajax"=>1))',
                                'options' => array('class' => 'icon icon-color icon-unlocked checkin', 'title' => Yii::t('Room', 'Open this room'))
                            ),
                        ),
                        'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}&nbsp;&nbsp;{reset}&nbsp;&nbsp;{checkin}',
                        'deleteConfirmation' => false,
                    ),
                ),
            ));
            ?>

            <!-- End file list -->

        </div>
    </div><!--/span-->

</div><!--/row-->

<div class="modal hide fade" id="myConfirmAllModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('Room', 'Are you sure to delete all rooms?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo $this->createUrl('deleteall'); ?>" class="btn btn-confirm-all-delete"><?php echo Yii::t('site', 'Confirm') ?></a>
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
        jQuery('.btn-delete-allfile').click(function(e) {
            e.preventDefault();
            jQuery('#myConfirmAllModal div.modal-body p').html('<?php echo Yii::t("Room", "Are you sure to delete all rooms?") ?>');
            jQuery('#myConfirmAllModal').modal('show');
        });
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
        jQuery('.button-column a.delete').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td:first').html();
            jQuery('#myConfirmModal div.modal-header h3').html('<?php echo Yii::t('site', 'Delete Confirm Window') ?>');
            jQuery('#myConfirmModal div.modal-body p').html('<?php echo Yii::t("Room", "Are you sure to delete room") ?>' + ' ' + name + '?');
            jQuery('#myConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmModal').modal('show');
            return false;
        });
        jQuery('.button-column a.reset').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td:first').html();
            jQuery('#myResetConfirmModal div.modal-header h3').html('<?php echo Yii::t('site', 'Reset Room Confirm Window') ?>');
            jQuery('#myResetConfirmModal div.modal-body p').html('<?php echo Yii::t("Room", "Are you sure to reset room") ?>' + ' ' + name + '?');
            jQuery('#myResetConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myResetConfirmModal').modal('show');
            return false;
        });
        jQuery('.button-column a.checkin').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td:first').html();
            jQuery('#myCheckinConfirmModal div.modal-header h3').html('<?php echo Yii::t('site', 'Open Room Confirm Window') ?>');
            jQuery('#myCheckinConfirmModal div.modal-body p').html('<?php echo Yii::t("Room", "Are you sure to open room") ?>' + ' ' + name + '?');
            jQuery('#myCheckinConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myCheckinConfirmModal').modal('show');
            return false;
        });
    });

    jQuery(document).on('click', '#myConfirmAllModal a.btn-confirm-all-delete', function() {
        jQuery.ajax({
            url: jQuery(this).attr('href'),
            type: 'POST',
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
