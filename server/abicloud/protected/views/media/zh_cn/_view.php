<?php
/* @var $this MediaController */
/* @var $data Media */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('songid')); ?>:</b>
	<?php echo CHtml::encode($data->songid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lyrics')); ?>:</b>
	<?php echo CHtml::encode($data->lyrics); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('artist_id')); ?>:</b>
	<?php echo CHtml::encode($data->artist->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category_id')); ?>:</b>
	<?php echo CHtml::encode($data->category->name); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('albumn_id')); ?>:</b>
	<?php echo CHtml::encode($data->albumn_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('duration')); ?>:</b>
	<?php echo CHtml::encode($data->duration); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bpic_filename')); ?>:</b>
	<?php echo CHtml::encode($data->bpic_filename); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bpic_url')); ?>:</b>
	<?php echo CHtml::encode($data->bpic_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('spic_filename')); ?>:</b>
	<?php echo CHtml::encode($data->spic_filename); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('spic_url')); ?>:</b>
	<?php echo CHtml::encode($data->spic_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('light_tag')); ?>:</b>
	<?php echo CHtml::encode($data->light_tag); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('led_tag')); ?>:</b>
	<?php echo CHtml::encode($data->led_tag); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('video_filename')); ?>:</b>
	<?php echo CHtml::encode($data->video_filename); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('video_url')); ?>:</b>
	<?php echo CHtml::encode($data->video_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('audio_filename')); ?>:</b>
	<?php echo CHtml::encode($data->audio_filename); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('audio_url')); ?>:</b>
	<?php echo CHtml::encode($data->audio_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('audio_filename1')); ?>:</b>
	<?php echo CHtml::encode($data->audio_filename1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('audio_url1')); ?>:</b>
	<?php echo CHtml::encode($data->audio_url1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('audio_filename2')); ?>:</b>
	<?php echo CHtml::encode($data->audio_filename2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('audio_url2')); ?>:</b>
	<?php echo CHtml::encode($data->audio_url2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->create_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->update_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name_chinese')); ?>:</b>
	<?php echo CHtml::encode($data->name_chinese); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name_pinyin')); ?>:</b>
	<?php echo CHtml::encode($data->name_pinyin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lyrics_chinese')); ?>:</b>
	<?php echo CHtml::encode($data->lyrics_chinese); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lyrics_pinyin')); ?>:</b>
	<?php echo CHtml::encode($data->lyrics_pinyin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('video_dir_name')); ?>:</b>
	<?php echo CHtml::encode($data->video_dir_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('video_real_url')); ?>:</b>
	<?php echo CHtml::encode($data->video_real_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('video_codec')); ?>:</b>
	<?php echo CHtml::encode($data->video_codec); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('video_res')); ?>:</b>
	<?php echo CHtml::encode($data->video_res); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('video_style')); ?>:</b>
	<?php echo CHtml::encode($data->video_style); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('song_style')); ?>:</b>
	<?php echo CHtml::encode($data->song_style); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('song_lang')); ?>:</b>
	<?php echo CHtml::encode($data->song_lang); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('video_version')); ?>:</b>
	<?php echo CHtml::encode($data->video_version); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hot_grade')); ?>:</b>
	<?php echo CHtml::encode($data->hot_grade); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('play_count')); ?>:</b>
	<?php echo CHtml::encode($data->play_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_new')); ?>:</b>
	<?php echo CHtml::encode($data->is_new); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('origin_audio')); ?>:</b>
	<?php echo CHtml::encode($data->origin_audio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qc_grade')); ?>:</b>
	<?php echo CHtml::encode($data->qc_grade); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('video_comment')); ?>:</b>
	<?php echo CHtml::encode($data->video_comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('audio_volume1')); ?>:</b>
	<?php echo CHtml::encode($data->audio_volume1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('audio_volume2')); ?>:</b>
	<?php echo CHtml::encode($data->audio_volume2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('audio_count')); ?>:</b>
	<?php echo CHtml::encode($data->audio_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name_count')); ?>:</b>
	<?php echo CHtml::encode($data->name_count); ?>
	<br />

	*/ ?>

</div>