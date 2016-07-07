<?php
/* @var $this VersionController */
/* @var $dataProvider CActiveDataProvider */

$this->layout = '/layouts/admin';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('files', 'Versions');
$this->breadcrumbs = array(
    Yii::t('files', 'Versions'),
);

$total = $dataProvider->getTotalItemCount();

$this->menu = array(
    array('label' => 'Create Version', 'url' => array('create')),
    array('label' => 'Manage Version', 'url' => array('admin')),
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
    @media (min-width: 768px) and (max-width: 979px) {
        .grid-view table td:nth-child(5),
        .grid-view table th:nth-child(5),
        .grid-view table td:nth-child(7),
        .grid-view table th:nth-child(7),
        .grid-view table td:nth-child(8),
        .grid-view table th:nth-child(8),
        .grid-view table td:nth-child(9),
        .grid-view table th:nth-child(9) {display:none;}
    }
</style>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-picture"></i> <?php echo Yii::t('files', 'All Versions') ?> ( <?php echo $total; ?> <?php echo Yii::t('files', 'Versions') ?> )</h2>
            <div class="box-icon">
                <!-- a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a -->
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <div class="btn-group">
                    <a class="btn btn-success btn-round btn-add-file" href="<?php echo $this->createUrl('version/create'); ?>">
                        <i class="icon-plus icon-white"></i>  
                        <?php echo Yii::t('files', 'Add Version') ?>                                            
                    </a>
                </div>
            </div><!-- Toolbar -->
                <div class="flash-notice">
                    <?php echo Yii::t('files', '注意：每种终端类型最多只能保留一个更新版本。非下发版本请删除。'); ?>
                </div>
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
            <!-- Begin racer list -->

            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'files-grid',
                'dataProvider' => $dataProvider,
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                //'filter' => $model,
                'columns' => array(
                    array('name' => 'application_id', 'value' => '$data->application->name'),
                    'name',
                    'version',
                    'version_code',
                    array('name' => 'app_type', 'type' => 'raw', 'value' => '$data->getTypeOption($data->app_type)'),
                    'os',
                    'content',
                    'force_update',
                    array('name' => 'download_url', 'type' => 'raw', 'value' => '(empty($data->download_url) ? "" : CHtml::link(Yii::t("racers", "Download from server"), Yii::app()->baseUrl . "/site/appfile/" . $data->id))'),
                    array('name' => 'id','header'=>'MD5', 'type' => 'raw', 'value' => '$data->getMD5ofVersion($data->id)'),
                    //'create_time',
                    'update_time',
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
                                'options' => array('class' => 'icon icon-color icon-search view', 'title' => Yii::t('racers', 'View this version'))
                            ),
                            'update' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-edit update', 'title' => Yii::t('racers', 'Update this version'))
                            ),
                            'delete' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-close delete', 'title' => Yii::t('files', 'Delete this version'))
                            ),
                            'push' => array(
                                'label' => '',
                                'url' => 'Yii::app()->createUrl("version/push", array("id"=>$data->id, "returnUrl"=>"' . Yii::app()->getRequest()->getUrl() . '", "ajax"=>1))',
                                'options' => array('class' => 'icon icon-color icon-archive push', 'title' => Yii::t('Version', '推送升级信息'))
                            ),
                        ),
                        'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}&nbsp;&nbsp;{push}',
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
        <h3><?php echo Yii::t('files', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('files', 'Are you sure to delete all versions?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo $this->createUrl('version/deleteall'); ?>" class="btn btn-confirm-all-delete"><?php echo Yii::t('files', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('files', 'Cancel') ?></a>
    </div>
</div>
<div class="modal hide fade" id="myConfirmModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('files', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('files', 'Are you sure to delete all versions?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo $this->createUrl('version/deleteall'); ?>" class="btn btn-confirm-delete"><?php echo Yii::t('files', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('files', 'Cancel') ?></a>
    </div>
</div>

<div class="modal hide fade" id="myPushConfirmModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('files', 'Push Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('files', 'Are you sure to push upgrade message to all clients ?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-confirm-push" data-dismiss="modal"><?php echo Yii::t('files', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('files', 'Cancel') ?></a>
    </div>
</div>

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery('.btn-delete-allfile').click(function(e) {
            e.preventDefault();
            jQuery('#myConfirmAllModal div.modal-body p').html('<?php echo Yii::t('files', 'Are you sure to delete all versions?') ?>');
            jQuery('#myConfirmAllModal').modal('show');
        });
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
        jQuery('.button-column a.delete').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td:first').html();
            jQuery('#myConfirmModal div.modal-body p').html('<?php echo Yii::t('files', 'Are you sure to ') ?>' + jQuery(this).attr('title') + ' ' + name + '?');
            jQuery('#myConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmModal').modal('show');
            return false;
        });
        
        jQuery('.button-column a.push').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td').eq(1).html();
            var version = jQuery(this).closest('tr').find('td').eq(2).html();
            jQuery('#myPushConfirmModal div.modal-body p').html('<?php echo Yii::t('files', 'Are you sure to ') ?>' + jQuery(this).attr('title') + ' with ' + name + ' ' + version +  ' to clients ?');
            jQuery('#myPushConfirmModal div.modal-footer a.btn-confirm-push').attr('href', jQuery(this).attr('href'));
            jQuery('#myPushConfirmModal').modal('show');
            return false;
        });
    });

    jQuery(document).on('click', '#myConfirmAllModal a.btn-confirm-all-delete', function() {
        jQuery.ajax({
            url: jQuery(this).attr('href'),
            type: 'POST',
            success: function(result) {
                window.location.href = '<?php echo $this->createUrl('index'); ?>';
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
                window.location.href = '<?php echo $this->createUrl('index'); ?>';
            },
            error: function(result) {
                alert(result.responseText);
            },
            cache: false,
        });
        return false;
    });

    jQuery(document).on('click', '#myPushConfirmModal a.btn-confirm-push', function() {
        jQuery.ajax({
            url: jQuery(this).attr('href'),
            type: 'POST',
            success: function(result) {
                var jsonParsedObject = JSON.parse(result);
                alert(jsonParsedObject.message);
                //window.location.href = '<?php echo $this->createUrl('index'); ?>';
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

