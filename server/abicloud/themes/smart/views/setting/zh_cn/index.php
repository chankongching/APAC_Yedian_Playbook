<?php
/* @var $this SettingController */
/* @var $dataProvider CActiveDataProvider */

$this->layout = '/layouts/admin';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('setting', 'Settings');
$this->breadcrumbs = array(
    Yii::t('setting', 'Settings'),
);

$this->menu = array(
    array('label' => 'Create Setting', 'url' => array('create')),
    array('label' => 'Manage Setting', 'url' => array('admin')),
);
?>

<style type="text/css">
    @media (max-width: 480px) {
        .grid-view table td:nth-child(2),
        .grid-view table th:nth-child(2) {display:none;}
    }
</style>
<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-cog"></i> <?php echo Yii::t('setting', 'API Settings') ?> </h2>
            <div class="box-icon">
                <a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <div class="btn-group">
                    <?php
                    echo CHtml::ajaxLink(
                            $text = Yii::t('setting', 'Refresh Token'), $url = $this->createUrl('apilogin', array('login_name' => '', 'password' => '')), $ajaxOptions = array(
                        'type' => 'POST',
                        //'dataType'=>'json',
                        'success' => 'function(html){ window.location.reload(); }'
                            ), $htmlOptions = array('class' => 'btn btn-success btn-round loading-link btn-api-login')
                    );
                    ?>
                </div>
                <div class="btn-group">
                    <?php
                    echo CHtml::ajaxLink(
                            $label = Yii::t('setting', 'Register User'), $url = $this->createUrl('apiregister', array('login_name' => '', 'password' => '', 'nick_name' => '')), $ajaxOptions = array(
                        'type' => 'POST',
                        //'dataType'=>'json',
                        'success' => 'function(html){ window.location.reload(); }'
                            ), $htmlOptions = array('class' => 'btn btn-primary btn-round loading-link btn-api-register')
                    );
                    ?>
                    <?php
                    echo CHtml::ajaxLink(
                            $label = Yii::t('setting', 'Create Category Root'), $url = $this->createUrl('apicategory'), $ajaxOptions = array(
                        'type' => 'POST',
                        //'dataType'=>'json',
                        'success' => 'function(html){ window.location.reload(); }'
                            ), $htmlOptions = array('class' => 'btn btn-info btn-round loading-link btn-api-category')
                    );
                    ?>
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
                'id' => 'files-grid',
                'dataProvider' => $dataProvider,
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                //'filter' => $model,
                'columns' => array(
                    'name',
                    'content',
                    array(
                        'class' => 'CButtonColumn',
                        'htmlOptions' => array('class' => 'button-column', 'style' => 'width:60px;'),
                        'viewButtonImageUrl' => false,
                        'updateButtonImageUrl' => false,
                        'buttons' => array(
                            'view' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-search view', 'title' => Yii::t('setting', 'View this setting'))
                            ),
                            'update' => array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-edit update', 'title' => Yii::t('setting', 'Update this setting'))
                            ),
                        ),
                        'template' => '{view}&nbsp;&nbsp;{update}',
                        'deleteConfirmation' => false,
                    ),
                ),
            ));
            ?>

            <!-- End file list -->

        </div>
    </div><!--/span-->

</div><!--/row-->

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
    });
</script>