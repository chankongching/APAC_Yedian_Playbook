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
		<?php echo CHtml::label('选择要加入的热歌榜', 'select-category-id'); ?>
		<?php 
                 $artist_category_list = Musiccharts::model()->findAll();
                 $artist_category_array = array();
                 foreach($artist_category_list as $key=>$obj) {
                     $artist_category_array[$obj->id] = $obj->id . ' ' . $obj->name;
                 }
                echo CHtml::dropDownList('select-category-id','',$artist_category_array); 
                ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->