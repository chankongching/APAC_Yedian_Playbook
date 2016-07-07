<?php
/* @var $this DeviceController */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>'#',
	'method'=>'get',
)); ?>

	<div class="control-group">
		<?php echo CHtml::label('选择热歌榜', 'select-category-id'); ?>
		<?php 
                echo CHtml::dropDownList('select-category-id','',array(
                    '0' => '不限时间',
                    '1' => '1小时',
                    '2' => '2小时',
                    '3' => '3小时',
                    '4' => '4小时',
                    '5' => '5小时',
                    '6' => '6小时',
                    '7' => '7小时',
                    '8' => '8小时',
                    '9' => '9小时',
                    '10' => '10小时',
                    '11' => '11小时',
                    '12' => '12小时',
                    '24' => '1天',
                    '48' => '2天',
                    '72' => '3天',
                )); 
                ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->