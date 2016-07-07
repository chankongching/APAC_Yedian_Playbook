<?php 
$this->layout = '/layouts/admin';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Rights::t('core', 'Permissions');
$total = $dataProvider->getTotalItemCount();
$this->breadcrumbs = array(
	Rights::t('core', 'Rights')=>Rights::getBaseUrl(),
	Rights::t('core', 'Permissions'),
); 
?>

<div id="permissions" class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-picture"></i> <?php echo Rights::t('core', 'Permissions'); ?></h2>
            <div class="box-icon">
                <!-- a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a -->
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <div class="btn-group">
                    <?php echo CHtml::link('<i class="icon icon-users icon-white"></i>' . Rights::t('core', 'Roles'), array('authItem/roles'), array('class'=>'btn btn-success btn-round',)) ?>
                </div>
                <div class="btn-group">
                    <?php echo CHtml::link('<i class="icon icon-users icon-white"></i>' . Rights::t('core', 'Tasks'), array('authItem/tasks'), array('class'=>'btn btn-success btn-round',)) ?>
                </div>
                <div class="btn-group">
                    <?php echo CHtml::link('<i class="icon icon-users icon-white"></i>' . Rights::t('core', 'Operations'), array('authItem/operations'), array('class'=>'btn btn-success btn-round',)) ?>
                </div>

                <div class="btn-group">
                    <?php echo CHtml::link('<i class="icon icon-plus icon-white"></i>' . Rights::t('core', 'Generate items for controller actions'), array('authItem/generate'), array('class'=>'btn btn-info btn-round generator-link',)) ?>
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
                'id' => 'permission-grid',
                'dataProvider' => $dataProvider,
		'template'=>'{items}',
		'emptyText'=>Rights::t('core', 'No authorization items found.'),
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                //'filter' => $model,
                'ajaxUpdate' => false,
                'columns'=>$columns,
            ));
            ?>

            <!-- End file list -->
            <p class="info">*) <?php echo Rights::t('core', 'Hover to see from where the permission is inherited.'); ?></p>

	<script type="text/javascript">

		/**
		* Attach the tooltip to the inherited items.
		*/
		jQuery('.inherited-item').rightsTooltip({
			title:'<?php echo Rights::t('core', 'Source'); ?>: '
		});

		/**
		* Hover functionality for rights' tables.
		*/
		$('#content tbody tr').hover(function() {
			$(this).addClass('hover'); // On mouse over
		}, function() {
			$(this).removeClass('hover'); // On mouse out
		});

	</script>
            
        </div>
    </div><!--/span-->

</div><!--/row-->

