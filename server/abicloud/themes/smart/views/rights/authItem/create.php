<?php 
$this->layout = '/layouts/admin';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Rights::t('core', 'Create :type', array(':type'=>Rights::getAuthItemTypeName($_GET['type'])));
$this->breadcrumbs = array(
	Rights::t('core','Rights')=>Rights::getBaseUrl(),
	Rights::t('core', 'Create :type', array(':type'=>Rights::getAuthItemTypeName($_GET['type']))),
); 
?>

<div class="createAuthItem row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-picture"></i> <?php echo Rights::t('core', 'Create :type', array(':type'=>Rights::getAuthItemTypeName($_GET['type']),)); ?></h2>
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

            <?php $this->renderPartial('_form', array('model'=>$formModel)); ?>
        </div>
    </div><!--/span-->

</div><!--/row-->
