<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */

$this->layout = '/layouts/admin';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('user', 'Users');
$this->breadcrumbs = array(
    Yii::t('user', 'Users'),
);

$this->menu = array(
    array('label' => 'Create User', 'url' => array('create')),
    array('label' => 'Manage User', 'url' => array('admin')),
);
?>
<style type="text/css">
    @media (max-width: 480px) {
        .grid-view table td:nth-child(2),
        .grid-view table th:nth-child(2),
        .grid-view table td:nth-child(3),
        .grid-view table th:nth-child(3) {display:none;}
    }
    @media (max-width: 767px) {
    }
</style>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-user"></i> <?php echo Yii::t('user', 'Users') ?> </h2>
            <div class="box-icon">
                <!-- a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a -->
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <div class="btn-group">
                    <a class="btn btn-success btn-round btn-add-user" href="<?php echo $this->createUrl('user/create'); ?>">
                        <i class="icon-plus icon-white"></i>  
                        <?php echo Yii::t('user', 'Create user') ?>                                            
                    </a>
                </div>

                <div class="btn-group">
                    <div id="api-result-info"></div>
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
            <!-- Begin file list -->

            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'users-grid',
                'dataProvider' => $dataProvider,
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                //'filter' => $model,
                'columns' => array(
                    'username',
                    'email',
                    //'password',
                    'last_login_time',
                    array(
                        'class' => 'CButtonColumn',
                        'htmlOptions' => array('class' => 'button-column', 'style' => 'width:80px;'),
                        'viewButtonImageUrl' => false,
                        'updateButtonImageUrl' => false,
                        'deleteButtonImageUrl' => false,
                        'buttons' => array(
                            'view' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-search view', 'title' => Yii::t('user', 'View uer profile'))
                            ),
                            'update' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-edit update', 'title' => Yii::t('user', 'Edit user profile'))
                            ),
                            'delete' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-close delete', 'title' => Yii::t('user', 'Delete user'))
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

<div class="modal hide fade" id="myConfirmModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('user', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p>Are you sure?</p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo $this->createUrl('files/deleteall'); ?>" class="btn btn-confirm-delete"><?php echo Yii::t('user', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('user', 'Cancel') ?></a>
    </div>
</div>

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
        jQuery('.button-column a.delete').click(function(e) {
            e.preventDefault();
            jQuery('#myConfirmModal div.modal-body p').html('<?php echo Yii::t('user', 'Are you sure to ') ?>' + jQuery(this).attr('title') + '?');
            jQuery('#myConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmModal').modal('show');
            return false;
        });
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

    /*]]>*/
</script>
