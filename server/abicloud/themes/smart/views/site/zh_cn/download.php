<?php
/* @var $this SiteController */

$this->layout = '/layouts/empty';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>BudKTV<?php echo Yii::t('site','Download'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
    </head>

    <body>
        <div class="container-fluid" style="text-align:center;">
            <?php if($is_ios) { ?>
            <a href="http://fir.im/zaex"><img style="width:98%;" src="<?php echo Yii::app()->createAbsoluteUrl('//') . '/uploads/download.png'; ?>"></a>
            <!-- a href="itms-services://?action=download-manifest&url=<?php echo Yii::app()->createAbsoluteUrl('//') . '/uploads/AbiKtv-Info.plist'; ?>"><img style="width:98%;" src="<?php echo Yii::app()->createAbsoluteUrl('//') . '/uploads/download.png'; ?>"></a -->
            <!-- a href="<?php echo Yii::app()->createAbsoluteUrl('//') . '/uploads/AbiKtv.ipa'; ?>"><img style="width:98%;" src="<?php echo Yii::app()->createAbsoluteUrl('//') . '/uploads/download.png'; ?>"></a -->
            <?php } else { ?>
            <a href="<?php echo Yii::app()->createAbsoluteUrl('//') . '/site/appfile'; ?>"><img style="width:98%;" src="<?php echo Yii::app()->createAbsoluteUrl('//') . '/uploads/download.png'; ?>"></a>
            <?php } ?>
        </div>
    </body>
</html>
