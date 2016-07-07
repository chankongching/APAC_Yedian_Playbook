<?php
/* @var $this ArtistController */
/* @var $model Artist */

$this->layout = '/layouts/admin_search';
$this->breadcrumbs=array(
	Yii::t('site', 'Artists'),
);
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'Artists');
$total = $model->count();

$this->menu=array(
	array('label'=>'List Artist', 'url'=>array('index')),
	array('label'=>'Create Artist', 'url'=>array('create')),
);

?>

<style type="text/css">
@media (max-width: 480px) {
	.grid-view table td:nth-child(4),
	.grid-view table th:nth-child(4),
	.grid-view table td:nth-child(5),
	.grid-view table th:nth-child(5),
	.grid-view table td:nth-child(6),
	.grid-view table th:nth-child(6),
	.grid-view table td:nth-child(7),
	.grid-view table th:nth-child(7),
	.grid-view table td:nth-child(8),
	.grid-view table th:nth-child(8),
	.grid-view table td:nth-child(9),
	.grid-view table th:nth-child(9) {display:none;}
}
@media (max-width: 767px) {
	.grid-view table td:nth-child(6),
	.grid-view table th:nth-child(6),
	.grid-view table td:nth-child(7),
	.grid-view table th:nth-child(7),
	.grid-view table td:nth-child(8),
	.grid-view table th:nth-child(8),
	.grid-view table td:nth-child(9),
	.grid-view table th:nth-child(9) {display:none;}
}
@media (min-width: 768px) and (max-width: 979px) {
	.grid-view table td:nth-child(8),
	.grid-view table th:nth-child(8),
		.grid-view table td:nth-child(9),
		.grid-view table th:nth-child(9) {display:none;}
}
.grid-view .filters input, .grid-view .filters select {
    width: 94%;
}
</style>

<p style="display:none;">
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-picture"></i> <?php echo Yii::t('site', 'Artists') ?> ( <?php echo $total; ?> <?php echo ($total > 1) ? Yii::t('site', 'artists') : Yii::t('Artist', 'artist'); ?> )</h2>
            <div class="box-icon">
                <!-- a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a -->
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
            <!-- div style="float:right;"><a href="#" class="btn search-button"><?php echo Yii::t('Artist', 'Advanced Search') ?></a></div -->
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <?php if( Yii::app()->user->checkAccess('Artist.*') || Yii::app()->user->checkAccess('Artist.create') ) { ?>
                <div class="btn-group">
                    <a class="btn btn-success btn-round btn-add-file" href="<?php echo $this->createUrl('create'); ?>">
                        <i class="icon-plus icon-white"></i>  
                        <?php echo Yii::t('Artist', 'Add Artist') ?>                                            
                    </a>
                </div>
                <div class="btn-group">
                    <?php $this->renderExportGridButton('artist-grid', '导出艺术家', 'btn-export-csv-file', array('class' => 'btn btn-info btn-round')); ?>
                </div>
                <div class="btn-group">
                    <a class="btn btn-info btn-round loading-link btn-add-file" href="<?php echo $this->createUrl('checkpicture'); ?>">
                        <i class="icon-check icon-white"></i>  
                        <?php echo Yii::t('Artist', 'Check Artist Picture') ?>                                            
                    </a>
                </div>
                <?php } ?>
            </div><!-- Toolbar -->
            <?php if (Yii::app()->user->hasFlash('update')): ?>
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('update'); ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::app()->user->hasFlash('create')): ?>
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('create'); ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::app()->user->hasFlash('delete')): ?>
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('delete'); ?>
                </div>
            <?php endif; ?>
            
<div class="modal hide fade" id="mySearchFormModal" dailogtype="search">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('site', 'Advanced Search Window') ?></h3>
    </div>
    <div class="modal-body">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
    </div>
    <div class="modal-footer">
        <a href="<?php echo '#'; ?>" class="btn btn-dialog-search" data-dismiss="modal"><?php echo Yii::t('site', 'Search') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div><!-- search-form -->    
            
            <!-- Begin Artist list -->

            <?php
                    $_buttons = array();
                    $_template = '';
                    if( Yii::app()->user->checkAccess('Artist.*') || Yii::app()->user->checkAccess('Artist.View') ) { 
                        $_buttons['view'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-search view', 'title' => Yii::t('Artist', 'View this artist'))
                            );
                        $_template .= '{view}';
                    }
                    if( Yii::app()->user->checkAccess('Artist.*') || Yii::app()->user->checkAccess('Artist.Update') ) { 
                        $_buttons['update'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-edit update', 'title' => Yii::t('Artist', 'Update this artist'))
                            );
                        $_buttons['category'] = array(
                                'label' => '',
                                'url' => 'Yii::app()->createUrl("artist/addcategory", array("id"=>$data->id, "returnUrl"=>"' . Yii::app()->getRequest()->getUrl() . '", "ajax"=>1))',
                                'options' => array('class' => 'icon icon-color icon-add category', 'title' => Yii::t('Artist', 'Add this artist to category'))
                            );
                        if(empty($_template)) {
                            $_template .= '{category}&nbsp;&nbsp;{update}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{category}&nbsp;&nbsp;{update}';
                        }
                    }
                    if( Yii::app()->user->checkAccess('Artist.*') || Yii::app()->user->checkAccess('Artist.Delete') ) { 
                        $_buttons['delete'] = array(
                                'label' => '',
                                'options' => array('class' => 'icon icon-color icon-close delete', 'title' => Yii::t('Artist', 'Delete this artist'))
                            );
                        if(empty($_template)) {
                            $_template .= '{delete}';
                        }
                        else {
                            $_template .= '&nbsp;&nbsp;{delete}';
                        }
                    }
            
            $this->widget('CExGridView', array(
                'id' => 'artist-grid',
                'dataProvider' => $model->search(),
                'itemsCssClass' => 'table table-striped table-bordered bootstrap-datatable',
                'ajaxUpdate' => false,
                'filter' => $model,
                'columns' => array(
	'id',
	'name',
	//'description',
	//'birthday',
	//'bpic_filename',
	//'bpic_url',
                    array('filter'=>false,'name' => 'bpic_url', 'type' => 'raw', 'value' => '(empty($data->bpic_url) ? "" : CHtml::image($data->attach_url . "/" . $data->bpic_url, Yii::t("files", "View big picture"), array("style" => "height:50px;")))'),
	//'spic_filename',
	//'spic_url',
	//'status',
	//'create_time',
	//'create_user_id',
	//'update_time',
	//'update_user_id',
	//'country',
	//'periods',
	//'products',
	'name_chinese',
	'name_pinyin',
	'name_count',
                
                    array(
                        'class' => 'CButtonColumn',
                        'htmlOptions' => array('class' => 'button-column', 'style' => 'width:120px;'),
                        'viewButtonImageUrl' => false,
                        'updateButtonImageUrl' => false,
                        'deleteButtonImageUrl' => false,
                        'buttons' => $_buttons,
                        'template' => $_template,
                        'deleteConfirmation' => false,
                    ),
                ),
            ));
            ?>

            <!-- End file list -->

        </div>
    </div><!--/span-->

</div><!--/row-->

<div class="modal hide fade" id="myConfirmAllModal" dialogtype="confirm">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('Artist', 'Are you sure to delete all artists?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo $this->createUrl('deleteall'); ?>" class="btn btn-dialog-confirm-all"><?php echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div>
<div class="modal hide fade" id="myConfirmModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('Artist', 'Are you sure to delete this artist?') ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo '#'; ?>" class="btn btn-confirm-delete"><?php echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div>
<div class="modal hide fade" id="myAddCategoryConfirmModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo Yii::t('Artist', 'Are you sure to add category?') ?></p>
        <span><?php echo Yii::t('Artist', 'Notice: artist can add to multi-category.') ?></span>
        <div><br></div>
        <?php $this->renderPartial('_category'); ?>
    </div>
    <div class="modal-footer">
        <a href="<?php echo '#'; ?>" class="btn btn-confirm-delete"><?php echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div>

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
        jQuery('#mySearchFormModal form').submit(function(){
            jQuery('#artist-grid').yiiGridView('update', {
                data: $(this).serialize()
            });
            return false;
        });
        jQuery('a.search-button').click(function(e) {
            e.preventDefault();
            jQuery('#mySearchFormModal').modal('show');
            return false;
        });
        
        jQuery('.btn-delete-allfile').click(function(e) {
            e.preventDefault();
            jQuery('#myConfirmAllModal').attr('dialogtype','deleteall');
            jQuery('#myConfirmAllModal div.modal-header h3').html('<?php echo Yii::t("Artist", "Delete All artists Confirm Window") ?>');
            jQuery('#myConfirmAllModal div.modal-body p').html('<?php echo Yii::t("Artist", "Are you sure to delete all artists?") ?>');
            jQuery('#myConfirmAllModal div.modal-footer a.btn-dialog-confirm-all').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmAllModal').modal('show');
            return false;
        });
        jQuery('.button-column a.delete').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td').eq(1).html();
            jQuery('#myConfirmModal div.modal-body p').html('<?php echo Yii::t("Artist", "Are you sure to delete artist") ?>' + ' ' + name + '?');
            jQuery('#myConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myConfirmModal').modal('show');
            return false;
        });
        jQuery('.button-column a.category').click(function(e) {
            e.preventDefault();
            var name = jQuery(this).closest('tr').find('td').eq(1).html();
            jQuery('#myAddCategoryConfirmModal div.modal-header h3').html('<?php echo Yii::t('Artist', 'Category Confirm Window') ?>');
            jQuery('#myAddCategoryConfirmModal div.modal-body p').html('<?php echo Yii::t("Artist", "Are you sure to add artist ") ?>' + ' ' + name + '<?php echo Yii::t("Artist", "to a category?") ?>');
            jQuery('#myAddCategoryConfirmModal div.modal-footer a.btn-confirm-delete').attr('href', jQuery(this).attr('href'));
            jQuery('#myAddCategoryConfirmModal').modal('show');
            return false;
        });
    });

    jQuery(document).on('click', '#mySearchFormModal a.btn-dialog-search', function() {
        jQuery('#mySearchFormModal form').submit();
        return false;
    });
    
    jQuery(document).on('click', '#myConfirmAllModal a.btn-dialog-confirm-all', function() {
        jQuery.ajax({
            url: jQuery(this).attr('href'),
            type: 'POST',
            success: function(result) {
                //var jsonParsedObject = JSON.parse(result);
                //window.location.href = jsonParsedObject.redirect;
                window.location.href = '<?php echo $this->createUrl("index"); ?>';
            },
            error: function(result) {
                alert(result.responseText);
            },
            cache: false,
        });
        return false;
    });
    jQuery(document).on('click', '#myConfirmModal a.btn-confirm-delete', function() {
        jQuery.ajax({
            url: jQuery(this).attr('href'),
            type: 'POST',
            success: function(result) {
                //var jsonParsedObject = JSON.parse(result);
                //alert(jsonParsedObject.redirect);
                window.location.href = '<?php echo $this->createUrl("index"); ?>';
            },
            error: function(result) {
                alert(result.responseText);
            },
            cache: false,
        });
        return false;
    });

    jQuery(document).on('click', '#myAddCategoryConfirmModal a.btn-confirm-delete', function() {
        var selected_category_id = jQuery('#myAddCategoryConfirmModal #select-category-id').val();
        if(typeof(selected_category_id) == "undefined") {
            alert('选择错误，请重试。');
            return false;
        }
        jQuery.ajax({
            url: jQuery(this).attr('href') + '&cateid=' + selected_category_id,
            type: 'POST',
            success: function(result) {
                var jsonParsedObject = JSON.parse(result);
                //alert(jsonParsedObject.redirect);
                //alert('New check in success!');
                //window.location.href = '<?php echo $this->createUrl("checkinCode/index"); ?>';
                alert(jsonParsedObject.message);
                window.location.href = jsonParsedObject.redirect;
            },
            error: function(result) {
                alert(result.responseText);
            },
            cache: false,
        });
        return false;
    });
    
    /*]]>*/
</script>
