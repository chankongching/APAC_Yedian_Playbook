<?php
/* @var $this MediaController */
/* @var $model Media */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'media-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form-horizontal')
    //'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
        ));
?>

<fieldset>
    <legend><?php echo Yii::t('Media', 'Fields with {required} are required to {operate} media.', array('{required}' => '<span class="required">*</span>', '{operate}' => Yii::t('site', ($model->isNewRecord ? 'create' : 'update')))) ?></legend>
    
    <div class="control-group">
	<?php echo $form->labelEx($model,'songid', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'songid',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'songid'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'name', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'description', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'lyrics', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textArea($model,'lyrics',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'lyrics'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'artist_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'artist_id'); ?>
		<?php echo $form->error($model,'artist_id'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'category_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'category_id'); ?>
		<?php echo $form->error($model,'category_id'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'albumn_id', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'albumn_id'); ?>
		<?php echo $form->error($model,'albumn_id'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'duration', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'duration'); ?>
		<?php echo $form->error($model,'duration'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'bpic_filename', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'bpic_filename',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'bpic_filename'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'bpic_url', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'bpic_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'bpic_url'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'spic_filename', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'spic_filename',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'spic_filename'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'spic_url', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'spic_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'spic_url'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'light_tag', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textArea($model,'light_tag',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'light_tag'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'led_tag', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textArea($model,'led_tag',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'led_tag'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'video_filename', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'video_filename',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'video_filename'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'video_url', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'video_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'video_url'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'audio_filename', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'audio_filename',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'audio_filename'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'audio_url', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'audio_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'audio_url'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'audio_filename1', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'audio_filename1',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'audio_filename1'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'audio_url1', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'audio_url1',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'audio_url1'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'audio_filename2', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'audio_filename2',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'audio_filename2'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'audio_url2', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'audio_url2',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'audio_url2'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'status', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'name_chinese', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'name_chinese',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name_chinese'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'name_pinyin', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'name_pinyin',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name_pinyin'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'lyrics_chinese', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textArea($model,'lyrics_chinese',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'lyrics_chinese'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'lyrics_pinyin', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textArea($model,'lyrics_pinyin',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'lyrics_pinyin'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'video_dir_name', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'video_dir_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'video_dir_name'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'video_real_url', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'video_real_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'video_real_url'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'video_codec', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'video_codec',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'video_codec'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'video_res', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'video_res',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'video_res'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'video_style', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'video_style',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'video_style'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'song_style', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'song_style',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'song_style'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'song_lang', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'song_lang',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'song_lang'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'video_version', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'video_version',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'video_version'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'hot_grade', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'hot_grade'); ?>
		<?php echo $form->error($model,'hot_grade'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'play_count', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'play_count'); ?>
		<?php echo $form->error($model,'play_count'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'is_new', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'is_new'); ?>
		<?php echo $form->error($model,'is_new'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'origin_audio', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'origin_audio'); ?>
		<?php echo $form->error($model,'origin_audio'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'qc_grade', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'qc_grade'); ?>
		<?php echo $form->error($model,'qc_grade'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'video_comment', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'video_comment',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'video_comment'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'audio_volume1', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'audio_volume1'); ?>
		<?php echo $form->error($model,'audio_volume1'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'audio_volume2', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'audio_volume2'); ?>
		<?php echo $form->error($model,'audio_volume2'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'audio_count', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'audio_count'); ?>
		<?php echo $form->error($model,'audio_count'); ?>
        </div>
    </div>

    <div class="control-group">
	<?php echo $form->labelEx($model,'name_count', array('class' => 'control-label')); ?>
        <div class="controls">
		<?php echo $form->textField($model,'name_count'); ?>
		<?php echo $form->error($model,'name_count'); ?>
        </div>
    </div>


    
    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Create' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

