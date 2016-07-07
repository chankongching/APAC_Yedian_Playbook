<?php 
$this->layout = '/layouts/admin';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Rights::t('core', 'Assignments');
$total = $dataProvider->getTotalItemCount();
$this->breadcrumbs = array(
	Rights::t('core', 'Rights')=>Rights::getBaseUrl(),
	Rights::t('core', 'Assignments'),
); 
?>
<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-picture"></i> <?php echo Rights::t('core', 'Assignments') ?></h2>
            <div class="box-icon">
                <!-- a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a -->
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <?php if (Yii::app()->user->hasFlash('RightsSuccess')): ?>
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('RightsSuccess'); ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::app()->user->hasFlash('RightsError')): ?>
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('RightsError'); ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::app()->user->hasFlash('delete')): ?>
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('delete'); ?>
                </div>
            <?php endif; ?>
            <!-- Begin list -->

            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'assignment-grid',
                'dataProvider' => $dataProvider,
                'template'=>"{items}\n{pager}",
                'emptyText'=>Rights::t('core', 'No users found.'),
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                //'filter' => $model,
                'ajaxUpdate' => false,
                'columns' => array(
                    array(
                            'name'=>'name',
                            'header'=>Rights::t('core', 'Name'),
                            'type'=>'raw',
                            'htmlOptions'=>array('class'=>'name-column'),
                            'value'=>'$data->getAssignmentNameLink()',
                    ),
                    array(
                            'name'=>'assignments',
                            'header'=>Rights::t('core', 'Roles'),
                            'type'=>'raw',
                            'htmlOptions'=>array('class'=>'role-column'),
                            'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_ROLE)',
                    ),
                            array(
                            'name'=>'assignments',
                            'header'=>Rights::t('core', 'Tasks'),
                            'type'=>'raw',
                            'htmlOptions'=>array('class'=>'task-column'),
                            'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_TASK)',
                    ),
                            array(
                            'name'=>'assignments',
                            'header'=>Rights::t('core', 'Operations'),
                            'type'=>'raw',
                            'htmlOptions'=>array('class'=>'operation-column'),
                            'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_OPERATION)',
                    ),
                ),
            ));
            ?>

            <!-- End file list -->

        </div>
    </div><!--/span-->

</div><!--/row-->
