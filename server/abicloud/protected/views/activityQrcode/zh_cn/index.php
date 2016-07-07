<?php
/* @var $this QrcodeController */
/* @var $dataProvider CActiveDataProvider */

$this->layout = '/layouts/admin';
$this->breadcrumbs=array(
	Yii::t('site', 'QR code'),
);
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'QR code');
$total = $dataProvider->getTotalItemCount();

$this->menu=array(
	array('label'=>'Create QR code', 'url'=>array('create')),
	array('label'=>'Manage QR code', 'url'=>array('admin')),
);
?>


<style type="text/css">
</style>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-picture"></i> <?php echo Yii::t('site', 'QR code') ?> ( <?php echo $total; ?> <?php if($total > 1) echo Yii::t('Qrcode', 'codes'); else echo Yii::t('Qrcode', 'code'); ?> )</h2>
            <div class="box-icon">
                <!-- a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a -->
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <?php if( Yii::app()->user->checkAccess('ActivityQrcode.*') || Yii::app()->user->checkAccess('ActivityQrcode.create') ) { ?>
                <div class="btn-group">
                    <a class="btn btn-success btn-round btn-add-file" href="<?php echo $this->createUrl('create'); ?>">
                        <i class="icon-plus icon-white"></i>  
                        <?php echo Yii::t('Qrcode', 'Add QR code') ?>                                            
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
            <!-- Begin Room list -->

            <?php
                    $_buttons = array();
                    $_template = '';
                    if( Yii::app()->user->checkAccess('ActivityQrcode.*') || Yii::app()->user->checkAccess('ActivityQrcode.View') ) { 
                        $_buttons['view'] = array(
                                        'label' => '',
                                        'url' => 'Yii::app()->createUrl("activityQrcode/view", array("id"=>$data->id))',
                                        'options' => array('class' => 'icon icon-color icon-search view', 'title' => Yii::t('Qrcode', 'View this code'))
                                    );
                        $_template .= '{view}';
                    }
                    if( Yii::app()->user->checkAccess('ActivityQrcode.*') || Yii::app()->user->checkAccess('ActivityQrcode.Update') ) { 
                        $_buttons['update'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-edit update', 'title' => Yii::t('Qrcode', 'Update this code'))
                            );
                        if(empty($_template)) {
                            $_template .= '{update}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{update}';
                        }
                    }
                    if( Yii::app()->user->checkAccess('ActivityQrcode.*') || Yii::app()->user->checkAccess('ActivityQrcode.Delete') ) { 
                        $_buttons['delete'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-close delete', 'title' => Yii::t('Qrcode', 'Delete this code'))
                            );
                        if(empty($_template)) {
                            $_template .= '{delete}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{delete}';
                        }
                    }
            
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'qrcode-grid',
                'dataProvider' => $dataProvider,
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                //'filter' => $model,
                'columns' => array(
	'id',
        array('name' => 'activity_id', 'value' => '$data->getActivityName($data->activity_id)'),
	//'vendor_id',
	'name',
	//'description',
        array('name' => 'code', 'type' => 'raw', 'value' => 'CHtml::image($data->getQRCodeUrl($data->code), Yii::t("Qrcode", "QR code"), array("style" => "height:100px;"))'),
	//'status',
        array('name' => 'status', 'value' => '$data->getStatusName($data->status)'),
	'points',
	'usages',
	'userlimit',
	'usagetotal',
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
            jQuery('#myConfirmAllModal div.modal-body p').html('<?php echo Yii::t("Room", "Are you sure to delete all QR codes?") ?>');
            jQuery('#myConfirmAllModal').modal('show');
        });
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
        jQuery('.button-column a.delete').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td').eq(2).html();
            jQuery('#myConfirmModal div.modal-header h3').html('<?php echo Yii::t('site', 'Delete Confirm Window') ?>');
            jQuery('#myConfirmModal div.modal-body p').html('<?php echo Yii::t("Qrcode", "Are you sure to delete QR code") ?>' + ' ' + name + '?');
            jQuery('#myConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmModal').modal('show');
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

    /*]]>*/
</script>
