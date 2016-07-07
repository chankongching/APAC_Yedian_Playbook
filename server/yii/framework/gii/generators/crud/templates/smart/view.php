<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

$this->layout = '/layouts/admin';
<?php
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	Yii::t('site', '$label')=>array('index'),
	\$model->{$nameColumn},
);\n";
?>
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', '<?php echo $label; ?>')  . ' - ' . $model-><?php echo $nameColumn; ?>;

$this->menu=array(
	array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
	array('label'=>'Update <?php echo $this->modelClass; ?>', 'url'=>array('update', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>'Delete <?php echo $this->modelClass; ?>', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<style type="text/css">
    .table-bordered tbody:first-child tr:first-child th:first-child {
        border-top-left-radius: 4px;
    }    
    .table-bordered tbody:last-child tr:last-child th:first-child {
        border-radius: 0 0 0 4px;
    }    
</style>

<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-film"></i> <?php echo "<?php"; ?> echo Yii::t('<?php echo $this->modelClass; ?>', '<?php echo $this->modelClass; ?>') ?> ( <?php echo "<?php"; ?> echo $model-><?php echo $nameColumn; ?>; ?> )
            </h2>
            <div class="box-icon">
                <a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <!-- a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a -->
            </div>
        </div>
        <div class="box-content">
            <div class="btn-toolbar" style="margin-bottom: 15px;margin-top: 0px;border-bottom: 1px solid #EEEEEE;">
                <div class="btn-group">
                    <a class="btn btn-primary btn-round btn-edit-file" href="<?php echo "<?php"; ?> echo $this->createUrl('update', array('id' => $model->id)); ?>">
                        <i class="icon-edit icon-white"></i>  
                        <?php echo "<?php"; ?> echo Yii::t('site', 'Update') ?>                                            
                    </a>
                    <a class="btn btn-danger btn-round btn-delete-file" href="#">
                        <i class="icon-remove icon-white"></i>  
                        <?php echo "<?php"; ?> echo Yii::t('site', 'Delete') ?>                                            
                    </a>
                </div>
            </div><!-- Toolbar -->
            <?php echo "<?php"; ?> if (Yii::app()->user->hasFlash('update')): ?>
                <div class="flash-success">
                    <?php echo "<?php"; ?> echo Yii::app()->user->getFlash('update'); ?>
                </div>
            <?php echo "<?php"; ?> endif; ?>
            <?php echo "<?php"; ?> if (Yii::app()->user->hasFlash('create')): ?>
                <div class="flash-success">
                    <?php echo "<?php"; ?> echo Yii::app()->user->getFlash('create'); ?>
                </div>
            <?php echo "<?php"; ?> endif; ?>
            <?php echo "<?php"; ?> if (Yii::app()->user->hasFlash('delete')): ?>
                <div class="flash-success">
                    <?php echo "<?php"; ?> echo Yii::app()->user->getFlash('delete'); ?>
                </div>
            <?php echo "<?php"; ?> endif; ?>
            <div>
                <h2><?php echo "<?php"; ?> echo $model-><?php echo $nameColumn; ?>; ?></h2>
                <?php echo "<?php\n"; ?>
                //$create_user = User::model()->findByPk($model->create_user_id);
                //$create_user_name = empty($create_user) ? '' : $create_user->username;
                //$update_user = User::model()->findByPk($model->update_user_id);
                //$update_user_name = empty($update_user) ? '' : $update_user->username;
                $this->widget('zii.widgets.CDetailView', array(
                    'data' => $model,
                    'attributes' => array(
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if($column->isPrimaryKey)
		continue;
        echo "\t'{$column->name}',\n";
}
?>
                    ),
                    'htmlOptions' => array('class' => 'table table-striped table-bordered bootstrap-datatable')
                ));
                ?>
            </div>
        </div>
    </div><!--/span-->

</div><!--/row-->

<div class="modal hide fade" id="myConfirmModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3><?php echo "<?php"; ?> echo Yii::t('site', 'Confirm Window') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo "<?php"; ?> echo Yii::t('<?php echo $this->modelClass; ?>', 'Are you sure to delete <?php echo strtolower($this->modelClass); ?> {name}?', array('{name}' => $model-><?php echo $nameColumn; ?>)) ?></p>
    </div>
    <div class="modal-footer">
        <a href="<?php echo "<?php"; ?> echo $this->createUrl('delete', array('id' => $model->id)); ?>" class="btn btn-confirm-delete"><?php echo "<?php"; ?> echo Yii::t('site', 'Confirm') ?></a>
        <a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo "<?php"; ?> echo Yii::t('site', 'Cancel') ?></a>
    </div>
</div>

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery('.btn-delete-file').click(function(e) {
            e.preventDefault();
            jQuery('#myConfirmModal').modal('show');
        });
        jQuery.noty.consumeAlert({"layout": "topCenter", "type": "alert"});
    });

    jQuery(document).on('click', '#myConfirmModal a.btn-confirm-delete', function() {
        jQuery.ajax({
            url: jQuery(this).attr('href'),
            type: 'POST',
            success: function(result) {
                window.location.href = '<?php echo "<?php"; ?> echo $this->createUrl("index"); ?>';
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
