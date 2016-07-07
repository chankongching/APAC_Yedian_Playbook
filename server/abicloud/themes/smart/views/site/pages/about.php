<?php
/* @var $this SiteController */

$this->layout = '/layouts/simple';

//$cs=Yii::app()->clientScript;
//$cs->registerCoreScript('yiiactiveform');

$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'About');
$this->breadcrumbs = array(
    'About',
);
?>
<style type="text/css">
    div.errorMessage {
        color: #BC628B;
        margin-bottom: 10px;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
    }
</style>

<?php if (isset($this->breadcrumbs)): ?>
    <?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => $this->breadcrumbs,
        'htmlOptions' => array('class' => 'breadcrumb'),
    ));
    ?><!-- breadcrumbs -->
<?php endif ?>

<div class="row-fluid">
    <div class="span12 center login-header">
        <h2><?php echo CHtml::encode(Yii::t('site', Yii::app()->name)); ?></h2>
        <h3><?php echo Yii::t('site', 'About'); ?></h3>
    </div><!--/span-->
</div><!--/row-->

<div class="row-fluid">
    <div class="well span5 center login-box">
        <p>This is a "static" page. You may change the content of this page.</p>
    </div>
</div>
