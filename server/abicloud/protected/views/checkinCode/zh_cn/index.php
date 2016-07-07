<?php
/* @var $this CheckinCodeController */
/* @var $dataProvider CActiveDataProvider */

$this->layout = '/layouts/admin';
$this->breadcrumbs=array(
	Yii::t('site', 'Checkin Codes'),
);
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'Checkin Codes');
$total = $dataProvider->getTotalItemCount();

$this->menu=array(
	array('label'=>'Create CheckinCode', 'url'=>array('create')),
	array('label'=>'Manage CheckinCode', 'url'=>array('admin')),
);
?>


<style type="text/css">
@media (max-width: 480px) {
	.grid-view table td:nth-child(4),
	.grid-view table th:nth-child(4) {display:none;}
}
</style>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-picture"></i> <?php echo Yii::t('site', 'Checkin Codes') ?> ( <?php echo $total; ?> <?php if($total > 1) echo Yii::t('site', 'checkin codes'); else echo Yii::t('CheckinCode', 'checkincode'); ?> )</h2>
            <div class="box-icon">
                <!-- a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a -->
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
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
            <!-- Begin CheckinCode list -->

            <?php
            
                    $_buttons = array();
                    $_template = '';
                    if( Yii::app()->user->checkAccess('CheckinCode.*') || Yii::app()->user->checkAccess('CheckinCode.View') ) { 
                        $_buttons['view'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-search view', 'title' => Yii::t('CheckinCode', 'View this checkincode'))
                            );
                        $_template .= '{view}';
                    }
                    if( Yii::app()->user->checkAccess('CheckinCode.*') || Yii::app()->user->checkAccess('CheckinCode.Update') ) { 
                        $_buttons['update'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-edit update', 'title' => Yii::t('CheckinCode', 'Update this checkincode'))
                            );
                        if(empty($_template)) {
                            $_template .= '{update}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{update}';
                        }
                    }
                    if( Yii::app()->user->checkAccess('CheckinCode.*') || Yii::app()->user->checkAccess('CheckinCode.Delete') ) { 
                        $_buttons['delete'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-close delete', 'title' => Yii::t('CheckinCode', 'Delete this checkincode'))
                            );
                        if(empty($_template)) {
                            $_template .= '{delete}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{delete}';
                        }
                    }
            
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'checkincode-grid',
                'dataProvider' => $dataProvider,
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                //'filter' => $model,
                'columns' => array(
	//'code',
                    array('name' => 'code', 'type' => 'raw', 'value' => 'CHtml::image($data->getCheckinUrl($data->code), Yii::t("files", "Scan to check in"), array("style" => "height:100px;"))'),
	//'room_id',
                    array('name' => 'room_id', 'value' => '$data->room->name'),
	//'name',
	//'description',
	'duration',
	//'expire',
                    array('name' => 'expire', 'value' => 'empty($data->expire)?Yii::t("site","Never"):date("Y-m-d H:i:s", $data->expire)'),
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
        <p><?php echo Yii::t('CheckinCode', 'Are you sure to delete all checkin codes?') ?></p>
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
        <p><?php echo Yii::t('CheckinCode', 'Are you sure to delete this checkincode?') ?></p>
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
            jQuery('#myConfirmAllModal div.modal-body p').html('<?php echo Yii::t("CheckinCode", "Are you sure to delete all checkin codes?") ?>');
            jQuery('#myConfirmAllModal').modal('show');
        });
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
        jQuery('.button-column a.delete').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td:first').html();
            jQuery('#myConfirmModal div.modal-body p').html('<?php echo Yii::t("CheckinCode", "Are you sure to delete checkincode") ?>' + ' ' + name + '?');
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
