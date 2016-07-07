<?php
/* @var $this VersionController */
/* @var $model Version */

$this->layout = '/layouts/admin';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('files', 'Versions');
$this->breadcrumbs = array(
    Yii::t('files', 'Versions') => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => 'List Version', 'url' => array('index')),
    array('label' => 'Create Version', 'url' => array('create')),
    array('label' => 'Update Version', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete Version', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage Version', 'url' => array('admin')),
);
?>

<style type="text/css">
    .table-bordered tbody:first-child tr:first-child th:first-child {
        border-top-left-radius: 4px;
    }    
    .table-bordered tbody:last-child tr:last-child th:first-child {
        border-radius: 0 0 0 4px;
    }    
</style>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-film"></i> <?php echo Yii::t('files', 'Version') ?> ( <?php echo $model->name; ?> )
            </h2>
            <div class="box-icon">
                <!-- a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a -->
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <div class="btn-group">
                    <a class="btn btn-primary btn-round btn-edit-file" href="<?php echo $this->createUrl('version/update', array('id' => $model->id)); ?>">
                        <i class="icon-edit icon-white"></i>  
                        <?php echo Yii::t('files', 'Update') ?>                                            
                    </a>
                    <a class="btn btn-danger btn-round btn-delete-file" href="#">
                        <i class="icon-remove icon-white"></i>  
                        <?php echo Yii::t('files', 'Delete') ?>                                            
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
            <div>
                <h2><?php echo $model->name; ?></h2>
                <?php
                $create_user = User::model()->findByPk($model->create_user_id);
                $create_user_name = empty($create_user) ? '' : $create_user->username;
                $update_user = User::model()->findByPk($model->update_user_id);
                $update_user_name = empty($update_user) ? '' : $update_user->username;
                $this->widget('zii.widgets.CDetailView', array(
                    'data' => $model,
                    'attributes' => array(
                        'id' => array('visible' => FALSE),
                        'application_id' => array('name' => Yii::t('files', 'Application'), 'value' => $model->application->name),
                        'name',
                        'version',
                    'version_code',
                    array('name' => 'app_type', 'type' => 'raw', 'value' => $model->getTypeOption($model->app_type)),
                        'content',
                        'force_update' => array('name' => Yii::t('files', 'Force Update'), 'value' => empty($model->force_update) ? 'FALSE' : 'TRUE'),
                        'create_time',
                        'create_user_id' => array('name' => Yii::t('files', 'Create User'), 'value' => $create_user_name),
                        'update_time',
                        'update_user_id' => array('name' => Yii::t('files', 'Update User'), 'value' => $update_user_name),
                        'file_name',
                        'download_url' => array('name' => Yii::t('files', 'Server Url'), 'type' => 'raw', 'value' => (empty($model->download_url) ? '' : CHtml::link(Yii::t('files', 'Download from server'), Yii::app()->baseUrl . '/site/appfile/' . $model->id))),
                    ),
                    'htmlOptions' => array('class' => 'table table-striped table-bordered bootstrap-datatable')
                ));
                ?>
            </div>
        </div>
    </div><!--/span-->

</div><!--/row-->

<div class="modal hide fade" id="myConfirmModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('files', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('files', 'Are you sure to delete version {file}?', array('{file}' => $model->name)) ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo $this->createUrl('version/delete', array('id' => $model->id)); ?>" class="btn btn-confirm-delete"><?php echo Yii::t('files', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('files', 'Cancel') ?></a>
    </div>
</div>

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery('.btn-delete-file').click(function(e) {
            e.preventDefault();
            jQuery('#myConfirmModal').modal('show');
        });
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
    });

    jQuery(document).on('click', '#myConfirmModal a.btn-confirm-delete', function() {
        jQuery.ajax({
            url: jQuery(this).attr('href'),
            type: 'POST',
            success: function(result) {
                window.location.href = '<?php echo $this->createUrl('version/index'); ?>';
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



