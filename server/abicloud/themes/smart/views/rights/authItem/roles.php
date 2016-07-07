<?php 
$this->layout = '/layouts/admin';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Rights::t('core', 'Roles');
$total = $dataProvider->getTotalItemCount();
$this->breadcrumbs = array(
	Rights::t('core', 'Rights')=>Rights::getBaseUrl(),
	Rights::t('core', 'Roles'),
); 
?>
<div id="roles" class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-picture"></i> <?php echo Rights::t('core', 'Roles'); ?></h2>
            <div class="box-icon">
                <!-- a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a -->
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <div class="btn-group">
                    <?php echo CHtml::link('<i class="icon-plus icon-white"></i>' . Rights::t('core', 'Create a new role'), array('authItem/create', 'type'=>CAuthItem::TYPE_ROLE), array('class'=>'btn btn-success btn-round add-role-link',)) ?>
                </div>

                <div class="btn-group">
                    <div id="api-result-info"></div>
                </div>
            </div><!-- Toolbar -->
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
                'id' => 'role-grid',
                'dataProvider' => $dataProvider,
	    'template'=>'{items}',
	    'emptyText'=>Rights::t('core', 'No roles found.'),
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                //'filter' => $model,
                'ajaxUpdate' => false,
                'columns' => array(
    		array(
    			'name'=>'name',
    			'header'=>Rights::t('core', 'Name'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'name-column'),
    			'value'=>'$data->getGridNameLink()',
    		),
    		array(
    			'name'=>'description',
    			'header'=>Rights::t('core', 'Description'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'description-column'),
    		),
    		array(
    			'name'=>'bizRule',
    			'header'=>Rights::t('core', 'Business rule'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'bizrule-column'),
    			'visible'=>Rights::module()->enableBizRule===true,
    		),
    		array(
    			'name'=>'data',
    			'header'=>Rights::t('core', 'Data'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'data-column'),
    			'visible'=>Rights::module()->enableBizRuleData===true,
    		),
    		array(
    			'header'=>'&nbsp;',
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'actions-column'),
    			'value'=>'$data->getDeleteRoleLink()',
    		),
                ),
            ));
            ?>

            <!-- End file list -->
            <p class="info"><?php echo Rights::t('core', 'Values within square brackets tell how many children each item has.'); ?></p>
	<p>
		<?php echo Rights::t('core', 'A role is group of permissions to perform a variety of tasks and operations, for example the authenticated user.'); ?><br />
		<?php echo Rights::t('core', 'Roles exist at the top of the authorization hierarchy and can therefore inherit from other roles, tasks and/or operations.'); ?>
	</p>

        </div>
    </div><!--/span-->

</div><!--/row-->
