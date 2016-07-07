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
?>

<div class="sortable row-fluid">
</div>
