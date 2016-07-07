<?php
/* @var $this MediaController */
/* @var $model Media */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'songid'); ?>
		<?php echo $form->textField($model,'songid',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lyrics'); ?>
		<?php echo $form->textArea($model,'lyrics',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'artist_id'); ?>
		<?php echo $form->textField($model,'artist_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'category_id'); ?>
		<?php echo $form->textField($model,'category_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'albumn_id'); ?>
		<?php echo $form->textField($model,'albumn_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'duration'); ?>
		<?php echo $form->textField($model,'duration'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bpic_filename'); ?>
		<?php echo $form->textField($model,'bpic_filename',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bpic_url'); ?>
		<?php echo $form->textField($model,'bpic_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'spic_filename'); ?>
		<?php echo $form->textField($model,'spic_filename',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'spic_url'); ?>
		<?php echo $form->textField($model,'spic_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'light_tag'); ?>
		<?php echo $form->textArea($model,'light_tag',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'led_tag'); ?>
		<?php echo $form->textArea($model,'led_tag',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'video_filename'); ?>
		<?php echo $form->textField($model,'video_filename',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'video_url'); ?>
		<?php echo $form->textField($model,'video_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'audio_filename'); ?>
		<?php echo $form->textField($model,'audio_filename',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'audio_url'); ?>
		<?php echo $form->textField($model,'audio_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'audio_filename1'); ?>
		<?php echo $form->textField($model,'audio_filename1',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'audio_url1'); ?>
		<?php echo $form->textField($model,'audio_url1',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'audio_filename2'); ?>
		<?php echo $form->textField($model,'audio_filename2',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'audio_url2'); ?>
		<?php echo $form->textField($model,'audio_url2',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_time'); ?>
		<?php echo $form->textField($model,'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_user_id'); ?>
		<?php echo $form->textField($model,'create_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_time'); ?>
		<?php echo $form->textField($model,'update_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_user_id'); ?>
		<?php echo $form->textField($model,'update_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name_chinese'); ?>
		<?php echo $form->textField($model,'name_chinese',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name_pinyin'); ?>
		<?php echo $form->textField($model,'name_pinyin',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lyrics_chinese'); ?>
		<?php echo $form->textArea($model,'lyrics_chinese',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lyrics_pinyin'); ?>
		<?php echo $form->textArea($model,'lyrics_pinyin',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'video_dir_name'); ?>
		<?php echo $form->textField($model,'video_dir_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'video_real_url'); ?>
		<?php echo $form->textField($model,'video_real_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'video_codec'); ?>
		<?php echo $form->textField($model,'video_codec',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'video_res'); ?>
		<?php echo $form->textField($model,'video_res',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'video_style'); ?>
		<?php echo $form->textField($model,'video_style',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'song_style'); ?>
		<?php echo $form->textField($model,'song_style',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'song_lang'); ?>
		<?php echo $form->textField($model,'song_lang',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'video_version'); ?>
		<?php echo $form->textField($model,'video_version',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hot_grade'); ?>
		<?php echo $form->textField($model,'hot_grade'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'play_count'); ?>
		<?php echo $form->textField($model,'play_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_new'); ?>
		<?php echo $form->textField($model,'is_new'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'origin_audio'); ?>
		<?php echo $form->textField($model,'origin_audio'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'qc_grade'); ?>
		<?php echo $form->textField($model,'qc_grade'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'video_comment'); ?>
		<?php echo $form->textField($model,'video_comment',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'audio_volume1'); ?>
		<?php echo $form->textField($model,'audio_volume1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'audio_volume2'); ?>
		<?php echo $form->textField($model,'audio_volume2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'audio_count'); ?>
		<?php echo $form->textField($model,'audio_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name_count'); ?>
		<?php echo $form->textField($model,'name_count'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->