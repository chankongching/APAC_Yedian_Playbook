<?php
/* @var $this PlatformUserController */
/* @var $model PlatformUser */

$this->layout = '/layouts/admin';
$this->breadcrumbs=array(
	Yii::t('site', 'Platform Users')=>array('index'),
	$model->id,
);
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'Platform Users')  . ' - ' . $model->id;

$this->menu=array(
	array('label'=>'List PlatformUser', 'url'=>array('index')),
	array('label'=>'Create PlatformUser', 'url'=>array('create')),
	array('label'=>'Update PlatformUser', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete PlatformUser', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PlatformUser', 'url'=>array('admin')),
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
            <h2><i class="icon-film"></i> <?php echo Yii::t('PlatformUser', 'PlatformUser') ?> ( <?php echo $model->id; ?> )
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
                    <a class="btn btn-primary btn-round btn-edit-file" href="<?php echo $this->createUrl('update', array('id' => $model->id)); ?>">
                        <i class="icon-edit icon-white"></i>  
                        <?php echo Yii::t('site', 'Update') ?>                                            
                    </a>
                    <a class="btn btn-danger btn-round btn-delete-file" href="#">
                        <i class="icon-remove icon-white"></i>  
                        <?php echo Yii::t('site', 'Delete') ?>                                            
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
                <h2><?php echo $model->id; ?></h2>
                <?php
                $create_user = User::model()->findByPk($model->create_user_id);
                $create_user_name = empty($create_user) ? '' : $create_user->username;
                $update_user = User::model()->findByPk($model->update_user_id);
                $update_user_name = empty($update_user) ? '' : $update_user->username;
                $this->widget('zii.widgets.CDetailView', array(
                    'data' => $model,
                    'attributes' => array(
	'username',
	'email',
	'mobile',
	//'password',
	//'role',
	'openid',
	'token',
	//'type',
	'auth_type',
	//'profile_data',
	'display_name',
	'avatar_url',
	'last_login_time',
	'create_time',
	//'create_user_id',
	//'update_time',
	//'update_user_id',
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
        <h3><?php echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('PlatformUser', 'Are you sure to delete platformuser {name}?', array('{name}' => $model->id)) ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo $this->createUrl('delete', array('id' => $model->id)); ?>" class="btn btn-confirm-delete"><?php echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
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
