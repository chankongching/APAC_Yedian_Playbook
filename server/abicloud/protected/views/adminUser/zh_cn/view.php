<?php
/* @var $this UserController */
/* @var $model User */

$this->layout = '/layouts/admin';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('user', 'Users');
$this->breadcrumbs = array(
    Yii::t('user', 'Users') => array('index'),
    $model->username,
);

$this->menu = array(
    array('label' => 'List User', 'url' => array('index')),
    array('label' => 'Create User', 'url' => array('create')),
    array('label' => 'Update User', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete User', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage User', 'url' => array('admin')),
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
            <h2><i class="icon-user"></i> <?php echo Yii::t('user', 'User') ?> ( <?php echo $model->username; ?> )
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
                    <a class="btn btn-success btn-round btn-update-user" href="<?php echo $this->createUrl('adminUser/update', array('id' => $model->id)); ?>">
                        <i class="icon-plus icon-white"></i>  
                        <?php echo Yii::t('user', 'Update user') ?>                                            
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
                <h2><?php echo $model->username; ?></h2>
                <?php
                $update_user = User::model()->findByPk($model->update_user_id);
                $update_user_name = empty($update_user) ? '' : $update_user->username;
                $this->widget('zii.widgets.CDetailView', array(
                    'data' => $model,
                    'attributes' => array(
                        'id' => array('visible' => FALSE),
                        'username',
                        'email',
                        'last_login_time',
                        //'create_time',
                        //'create_user_id' => array('name' => 'Create User', 'value' => $create_user_name),
                        'update_time',
                        'update_user_id' => array('name' => Yii::t('user', 'Update User'), 'value' => $update_user_name),
                    ),
                    'htmlOptions' => array('class' => 'table table-striped table-bordered bootstrap-datatable')
                ));
                ?>
            </div>
        </div>
    </div><!--/span-->

</div><!--/row-->

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
    });
</script>
