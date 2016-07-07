<?php 
$this->layout = '/layouts/admin';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Rights::t('core', 'Generate items');
$this->breadcrumbs = array(
	Rights::t('core','Rights')=>Rights::getBaseUrl(),
	Rights::t('core', 'Generate items'),
); 
?>

<div id="generator" class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-picture"></i> <?php echo Rights::t('core', 'Generate items'); ?></h2>
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

	<h4><?php echo Rights::t('core', 'Please select which items you wish to generate.'); ?></h4>

	<div class="form">

		<?php $form=$this->beginWidget('CActiveForm'); ?>

			<div class="control-group">

				<table class="table table-striped table-bordered bootstrap-datatable items generate-item-table" border="0" cellpadding="0" cellspacing="0">

					<tbody>

						<tr class="application-heading-row">
							<th colspan="3"><?php echo Rights::t('core', 'Application'); ?></th>
						</tr>

						<?php $this->renderPartial('_generateItems', array(
							'model'=>$model,
							'form'=>$form,
							'items'=>$items,
							'existingItems'=>$existingItems,
							'displayModuleHeadingRow'=>true,
							'basePathLength'=>strlen(Yii::app()->basePath),
						)); ?>

					</tbody>

				</table>

			</div>

			<div class="control-group">

   				<?php echo CHtml::link(Rights::t('core', 'Select all'), '#', array(
   					'onclick'=>"jQuery('.generate-item-table').find(':checkbox').attr('checked', 'checked'); jQuery('.generate-item-table').find(':checkbox').parent().attr('class','checked');return false;",
   					'class'=>'selectAllLink')); ?>
   				/
				<?php echo CHtml::link(Rights::t('core', 'Select none'), '#', array(
					'onclick'=>"jQuery('.generate-item-table').find(':checkbox').removeAttr('checked'); jQuery('.generate-item-table').find(':checkbox').parent().removeAttr('class');return false;",
					'class'=>'selectNoneLink')); ?>

			</div>

   			<div class="control-group">

				<?php echo CHtml::submitButton(Rights::t('core', 'Generate')); ?>

			</div>

		<?php $this->endWidget(); ?>

	</div>
            
        </div>
    </div><!--/span-->

</div><!--/row-->
