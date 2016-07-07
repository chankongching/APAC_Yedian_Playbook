<?php
/* @var $this SiteController */

$this->layout = '/layouts/admin';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'Home');
$this->breadcrumbs = array(
    Yii::t('site', 'Dashboard'),
);

//$category_count = Racer::model()->count();
//$category_new = Racer::model()->countNew();
//$files_count = Score::model()->count();
//$files_new = Score::model()->countNew();
$category_count = 0;
$category_new = 0;
$files_count = 0;
$files_new = 0;

$apicall_count = 0;
$apicall_new = 0;
$user_count = User::model()->count();
$user_new = User::model()->countNew();
?>

<div class="sortable row-fluid">
    <a data-rel="tooltip" title="<?php echo Yii::t('site', '{count} new racer.', array('{count}' => $category_new)) ?>" class="well span3 top-block" href="<?php echo $this->createUrl('racer/index'); ?>">
        <span class="icon32 icon-color icon-folder-open"></span>
        <div><?php echo Yii::t('site', 'Racers') ?></div>
        <div><?php echo $category_count; ?></div>
        <span class="notification green"><?php echo $category_new; ?></span>
    </a>

    <a data-rel="tooltip" title="<?php echo Yii::t('site', '{count} new score.', array('{count}' => $files_new)) ?>" class="well span3 top-block" href="<?php echo $this->createUrl('score/index'); ?>">
        <span class="icon32 icon-color icon-image"></span>
        <div><?php echo Yii::t('site', 'Scores') ?></div>
        <div><?php echo $files_count; ?></div>
        <span class="notification yellow"><?php echo $files_new; ?></span>
    </a>

    <a data-rel="tooltip" title="<?php echo Yii::t('site', '{count} new api calls.', array('{count}' => $apicall_new)) ?>" class="well span3 top-block" href="<?php echo $this->createUrl('racer/index'); ?>">
        <span class="icon32 icon-color icon-transfer-ew"></span>
        <div><?php echo Yii::t('site', 'API calls') ?></div>
        <div><?php echo $apicall_count; ?></div>
        <span class="notification red">0</span>
    </a>

    <a data-rel="tooltip" title="<?php echo Yii::t('site', '{count} new members.', array('{count}' => $user_new)) ?>" class="well span3 top-block" href="<?php echo $this->createUrl('user/index'); ?>">
        <span class="icon32 icon-red icon-user"></span>
        <div><?php echo Yii::t('site', 'Total Members') ?></div>
        <div><?php echo $user_count; ?></div>
        <span class="notification"><?php echo $user_new; ?></span>
    </a>
</div>

<div class="row-fluid">
    <div class="box span12">
        <div class="box-header well">
            <h2><i class="icon-info-sign"></i> <?php echo Yii::t('site','Introduction'); ?></h2>
            <div class="box-icon">
                <a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
            </div>
        </div>
        <div class="box-content">
            <h1><?php echo Yii::t('site',Yii::app()->name); ?> <small></small></h1>
            <p></p>
            <p><b></b></p>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
