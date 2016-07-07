<?php
/* @var $this SiteController */

$this->layout = '/layouts/admin';
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Dashboard',
);

//$category_count = Category::model()->count();
//$category_new = Category::model()->countNew();
//$files_count = Files::model()->count();
//$files_new = Files::model()->countNew();
$user_count = User::model()->count();
$user_new = User::model()->countNew();
?>

			<div class="sortable row-fluid">
				<a data-rel="tooltip" title="<?php echo $category_new; ?> new category." class="well span3 top-block" href="<?php echo $this->createUrl('category/index'); ?>">
					<span class="icon32 icon-color icon-folder-open"></span>
					<div>Categories</div>
					<div><?php echo $category_count; ?></div>
                                        <span class="notification green"><?php echo $category_new; ?></span>
				</a>

				<a data-rel="tooltip" title="<?php echo $files_new; ?> new files." class="well span3 top-block" href="<?php echo $this->createUrl('files/index'); ?>">
					<span class="icon32 icon-color icon-image"></span>
					<div>Files</div>
					<div><?php echo $files_count; ?></div>
					<span class="notification yellow"><?php echo $files_new; ?></span>
				</a>
                            
				<a data-rel="tooltip" title="0 new api calls." class="well span3 top-block" href="<?php echo $this->createUrl('category/index'); ?>">
					<span class="icon32 icon-color icon-transfer-ew"></span>
					<div>API calls</div>
					<div>0</div>
					<span class="notification red">0</span>
				</a>
                            
				<a data-rel="tooltip" title="<?php echo $user_new; ?> new members." class="well span3 top-block" href="<?php echo $this->createUrl('user/index'); ?>">
					<span class="icon32 icon-red icon-user"></span>
					<div>Total Members</div>
					<div><?php echo $user_count; ?></div>
					<span class="notification"><?php echo $user_new; ?></span>
				</a>
			</div>

			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> Introduction</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<h1><?php echo Yii::app()->name; ?> <small></small></h1>
						<p></p>
						<p><b>All pages in the menu are functional, take a look at all, please give us your comments.</b></p>
						
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
