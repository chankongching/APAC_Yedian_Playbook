<?php
/* @var $this SiteController */
/* @var $error array */

$this->layout = '/layouts/simple';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - Error';
$this->breadcrumbs = array(
    'Error',
);
?>
<style type="text/css">
    div.errorMessage {
        color: #BC628B;
        margin-bottom: 10px;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
    }
</style>

<div class="row-fluid">
    <div class="span12 center login-header">
        <h2><?php echo CHtml::encode(Yii::t('site', Yii::app()->name)); ?></h2>
    </div><!--/span-->
</div><!--/row-->

<div class="row-fluid">
    <div class="well span5 center login-box">
        <div class="alert alert-info">
            <h2 style="color:#BC628B">Error <?php echo $code; ?></h2>
            <h3 style="color:#BC628B"><?php echo CHtml::encode($message); ?></h3>
        </div>
        <a href="<?php echo Yii::app()->homeUrl ?>"><?php echo Yii::t('site', 'Return Home') ?></>
    </div><!--/span-->
</div><!--/row-->
