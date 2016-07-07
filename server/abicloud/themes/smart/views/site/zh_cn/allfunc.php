<?php
/* @var $this SiteController */

$this->layout = '/layouts/admin';
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'Home');
$this->breadcrumbs = array(
    Yii::t('site', 'All Functions'),
);
?>

<div class="row-fluid">
    <ul class="thumbnails category">
        <?php
        foreach ($allcontrollers as $key => $obj) {
            $controller_name = ($obj['name']);
            ?>
            <li id="category-<?php echo $controller_name; ?>" class="thumbnail">
                <a data-rel="tooltip" style="text-align:center;background:url(<?php echo Yii::app()->theme->baseUrl; ?>/img/bg-folder.png) no-repeat;background-size:50% 50%;background-position:25px 50px;" title="View <?php echo $obj['name']; ?>" href="<?php echo $this->createUrl($controller_name . '/index'); ?>">
                    <span class="icon32 icon-color icon-folder-open"></span>
                    <div><?php echo $obj['name']; ?></div>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
</div>
