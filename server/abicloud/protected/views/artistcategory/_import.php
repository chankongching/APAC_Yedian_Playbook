<?php
/* @var $this ArtistcategoryController */
/* @var $model Artistcategory */

$this->layout = '/layouts/admin';
$this->breadcrumbs=array(
	Yii::t('Artistcategory', 'Artistcategories')=>array('index'),
	Yii::t('site', 'Import'),
);

$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('Artistcategory', 'Artistcategories');

$this->menu=array(
	array('label'=>'List Artistcategory', 'url'=>array('index')),
	array('label'=>'Manage Artistcategory', 'url'=>array('admin')),
);

$error_info = CHtml::errorSummary($model);

?>

<style type="text/css">
    div.errorMessage {
        color: #BC628B;
        margin-top: 5px;
        margin-bottom: 5px;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
    }
    form span.required {
        color: #FF0000;
    }    
</style>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-folder-open"></i> <?php echo Yii::t('Artistcategory', 'Import Artistcategory') ?>
            </h2>
            <div class="box-icon">
                <a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <?php if (!empty($error_info)): ?>
                <div class="flash-error">
                    <?php echo $error_info; ?>
                </div>
            <?php endif; ?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'artistcategory-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
    //'htmlOptions' => array('class' => 'form-horizontal')
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
        ));
?>

<fieldset>
    <legend>
        <label>注意：</label>
        <label>文件为文本文件，以英文逗号作为分隔符，名称中不能包含英文逗号。</label>
        <label>无需标题行，每行一条记录，以回车换行，每行记录格式如下：</label>
        <p>分类名称,分类大图名称</p>
        <label>示例：</label>
        <label>男歌手,artist_category_male.png</label>
        <label>女歌手,artist_category_female.png</label>
        <label>.</label>
        <label>.</label>
        <label>.</label>
        <label>&nbsp;</label>
        <label>注意：图片文件名最好为英文名，图片文件统一复制到AbiKTV系统的 uploads/attach/picture目录下。</label>
    </legend>
    
        <div class="control-group">
            <?php echo $form->labelEx($model,'bpic_file', array('class' => 'control-label', 'label' => Yii::t('Artistcategory','Upload Artistcategory File'))); ?>
            <div class="controls">
                    <input class="input-file uniform_on" id="ytFiles_bpic_file" name="Artistcategory[bpic_file]" type="file">
                    <?php echo $form->error($model,'bpic_file'); ?>
            </div>
        </div>

    <div class="form-actions">
        <button type="submit" name="yt0" class="btn btn-primary"><?php echo Yii::t('site', ($model->isNewRecord ? 'Import' : 'Save Changes')) ?></button>
        <button type="reset" class="btn"><?php echo Yii::t('site', 'Reset') ?></button>
    </div>
</fieldset>
<?php $this->endWidget(); ?>


        </div><!-- form -->
    </div><!--/span-->

</div><!--/row-->
<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery.uniform.options.fileBtnText = "<?php echo Yii::t('site', 'Choose File') ?>";
        jQuery.uniform.options.fileDefaultText = "<?php echo Yii::t('site', 'No file selected') ?>";
    });
    /*]]>*/
</script>
