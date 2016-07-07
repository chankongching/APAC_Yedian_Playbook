<?php
/* @var $this Controller */
$no_visible_elements = false;
//$racer_total = Racer::model()->count();
//$score_total = Score::model()->count();
//$contact_total = Contact::model()->count();
$racer_total = 0;
$score_total = 0;
$contact_total = 0;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!--
                Charisma v1.0.0

                Copyright 2012 Muhammad Usman
                Licensed under the Apache License v2.0
                http://www.apache.org/licenses/LICENSE-2.0

                http://usman.it
                http://twitter.com/halalit_usman
        -->
        <meta charset="utf-8">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- The styles -->
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" rel="stylesheet">
        <link id="bs-css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-classic.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;
            }
        </style>
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/charisma-app.css" rel="stylesheet">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery-ui-1.8.21.custom.css" rel="stylesheet">
        <!-- link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/fullcalendar.css' rel='stylesheet' -->
        <!-- link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/fullcalendar.print.css' rel='stylesheet'  media='print' -->
        <link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/chosen.css' rel='stylesheet'>
        <link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/uniform.default.css' rel='stylesheet'>
        <link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/colorbox.css' rel='stylesheet'>
        <!-- link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery.cleditor.css' rel='stylesheet' -->
        <link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery.noty.css' rel='stylesheet'>
        <link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/noty_theme_default.css' rel='stylesheet'>
        <!-- link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/elfinder.min.css' rel='stylesheet' -->
        <!-- link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/elfinder.theme.css' rel='stylesheet' -->
        <link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery.iphone.toggle.css' rel='stylesheet'>
        <link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/opa-icons.css' rel='stylesheet'>
        <!-- link href='<?php echo Yii::app()->theme->baseUrl; ?>/css/uploadify.css' rel='stylesheet' -->

        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/html5.js"></script>
        <![endif]-->

        <!-- jQuery -->
        <!-- script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-1.7.2.min.js"></script -->
        <?php Yii::app()->getClientScript()->registerCoreScript('jquery');?>
        <!-- jQuery UI -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-ui-1.8.21.custom.min.js"></script>
        <?php Yii::app()->getClientScript()->registerCoreScript('jquery.ui');?>

        <!-- The fav icon -->
        <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/favicon.ico">

        <style type="text/css">
            .mynotification {
                border-radius: 10px;
                border-style: solid;
                border-width: 1px;
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.08), 0 1px rgba(255, 255, 255, 0.3) inset;
                color: #FFFFFF !important;
                font-family: Arial,sans-serif;
                height: 16px;
                line-height: 16px;
                padding: 0 5px;
                float: right;
                text-shadow: 0 1px rgba(0, 0, 0, 0.25);
                color: #458746;
                background-color: #78CD51;
                background-image: -moz-linear-gradient(center top , #A6DC8D, #78CC51);
                border-color: #5AAD34;
                font-size: 14px;
                font-weight: bold;
            }
            .mynotification.yellow {
                color: #F99406;
                background-color: #FABB3D;
                background-image: -moz-linear-gradient(center top , #FBD587, #FABB3D);
                border-color: #F4A506;
            }
            .mynotification.green {
                color: #458746;
                background-color: #78CD51;
                background-image: -moz-linear-gradient(center top , #A6DC8D, #78CC51);
                border-color: #5AAD34;
            }
        </style>


    </head>

    <body>
        <?php if (!isset($no_visible_elements) || !$no_visible_elements) { ?>
            <!-- topbar starts -->
            <div class="navbar">
                <div class="navbar-inner">
                    <div class="container-fluid">
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <a class="brand" href="<?php echo Yii::app()->homeUrl; ?>"> <img alt="Logo" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo20.png" /> <span><?php echo Yii::t('site', Yii::app()->name); ?></span></a>

                        <!-- theme selector starts -->
                        <!-- theme selector ends -->

                        <!-- user dropdown starts -->
                        <div class="btn-group pull-right" >
                            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="icon-user"></i><span class="hidden-phone"><?php echo Yii::app()->user->name; ?></span>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo $this->createUrl('/adminUser/view', array('id' => Yii::app()->user->id)); ?>"><?php echo Yii::t('site', 'Profile') ?></a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo $this->createUrl('/site/logout'); ?>"><?php echo Yii::t('site', 'Logout') ?></a></li>
                            </ul>
                        </div>
                        <!-- user dropdown ends -->

                        <div class="top-nav nav-collapse">
                        </div><!--/.nav-collapse -->
                    </div>
                </div>
            </div>
            <!-- topbar ends -->
        <?php } ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <?php if (!isset($no_visible_elements) || !$no_visible_elements) { ?>
                    <!-- left menu starts -->
                    <div class="span2 main-menu-span">
                        <div class="well nav-collapse sidebar-nav">
                            <ul class="nav nav-tabs nav-stacked main-menu">
                                <li class="nav-header hidden-tablet"><?php echo Yii::t('site', '管理平台主菜单') ?></li>
                                <li><a class="ajax-link" href="<?php echo Yii::app()->homeUrl; ?>" title="Home"><i class="icon-home"></i><span class="hidden-tablet"> <?php echo Yii::t('site', 'Home') ?></span></a></li>
                                <li class="nav-header hidden-tablet"><?php echo Yii::t('site', '权限设置') ?></li>
                                <li><a class="ajax-link" href="<?php echo $this->createUrl('assignment/view'); ?>" title="<?php echo Rights::t('core', 'Assignments'); ?>"><i class="icon icon-user"></i><span class="hidden-tablet"> <?php echo Rights::t('core', 'Assignments'); ?></span>
            <?php echo '<span class="mynotification green hidden-tablet" style="">' . '' . '</span>'; ?>
                                    </a></li>
                                <li><a class="ajax-link" href="<?php echo $this->createUrl('authItem/roles'); ?>" title="<?php echo Rights::t('core', 'Roles'); ?>"><i class="icon icon-users"></i><span class="hidden-tablet"> <?php echo Rights::t('core', 'Roles'); ?></span>
            <?php echo '<span class="mynotification green hidden-tablet" style="">' . '' . '</span>'; ?>
                                    </a></li>
                                <li><a class="ajax-link" href="<?php echo $this->createUrl('authItem/permissions'); ?>" title="<?php echo Rights::t('core', 'Permissions'); ?>"><i class="icon icon-users"></i><span class="hidden-tablet"> <?php echo Rights::t('core', 'Permissions'); ?></span>
            <?php echo '<span class="mynotification green hidden-tablet" style="">' . '' . '</span>'; ?>
                                    </a></li>
                                <li><a class="ajax-link" href="<?php echo $this->createUrl('authItem/tasks'); ?>" title="<?php echo Rights::t('core', 'Tasks'); ?>"><i class="icon icon-document"></i><span class="hidden-tablet"> <?php echo Rights::t('core', 'Tasks'); ?></span>
            <?php echo '<span class="mynotification green hidden-tablet" style="">' . '' . '</span>'; ?>
                                    </a></li>
                                <li><a class="ajax-link" href="<?php echo $this->createUrl('authItem/operations'); ?>" title="<?php echo Rights::t('core', 'Operations'); ?>"><i class="icon icon-comment-text"></i><span class="hidden-tablet"> <?php echo Rights::t('core', 'Operations'); ?></span>
            <?php echo '<span class="mynotification green hidden-tablet" style="">' . '' . '</span>'; ?>
                                    </a></li>
                                <li class="nav-header hidden-tablet"><?php echo Yii::t('site', 'Setting') ?></li>
                                <li><a href="<?php echo $this->createUrl('//rights'); ?>" title="<?php echo Yii::t('site', '权限管理') ?>"><i class="icon icon-users"></i><span class="hidden-tablet"> <?php echo Yii::t('site', '权限管理') ?></span></a></li>
                                <li><a class="ajax-link" href="<?php echo $this->createUrl('//adminUser/index'); ?>" title="<?php echo Yii::t('site', 'User Admin') ?>"><i class="icon icon-contacts"></i><span class="hidden-tablet"> <?php echo Yii::t('site', 'User Admin') ?></span></a></li>
                                <li><a href="<?php echo $this->createUrl('//version/index'); ?>" title="<?php echo Yii::t('site', 'APP Version') ?>"><i class="icon icon-globe"></i><span class="hidden-tablet"> <?php echo Yii::t('site', '应用版本') ?></span></a></li>
                            </ul>
                        </div><!--/.well -->
                    </div><!--/span-->
                    <!-- left menu ends -->

                    <noscript>
                    <div class="alert alert-block span10">
                        <h4 class="alert-heading">Warning!</h4>
                        <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
                    </div>
                    </noscript>

                    <div id="content" class="span10">
                        <!-- content starts -->
                    <?php } ?>

                    <?php if (isset($this->breadcrumbs)): ?>
                        <?php
                        $this->widget('zii.widgets.CBreadcrumbs', array(
                            'homeLink' => CHtml::link(Yii::t('zii','Home'),$this->createUrl('//site/index',array('Svideolist_sort'=>'ctime.desc'))),
                            'links' => $this->breadcrumbs,
                            'htmlOptions' => array('class' => 'breadcrumb'),
                        ));
                        ?><!-- breadcrumbs -->
                    <?php endif ?>

                    <?php echo $content; ?>

                    <?php if (!isset($no_visible_elements) || !$no_visible_elements) { ?>
                        <!-- content ends -->
                    </div><!--/#content.span10-->
                <?php } ?>
            </div><!--/fluid-row-->
            <?php if (!isset($no_visible_elements) || !$no_visible_elements) { ?>

                <hr>

                <div class="modal hide fade" id="myModal">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h3>Settings</h3>
                    </div>
                    <div class="modal-body">
                        <p>Here settings can be configured...</p>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn" data-dismiss="modal">Close</a>
                        <a href="#" class="btn btn-primary">Save changes</a>
                    </div>
                </div>

                <footer>
                </footer>
            <?php } ?>

        </div><!--/.fluid-container-->

        <!-- external javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->

        <!-- transition / effect library -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-transition.js"></script>
        <!-- alert enhancer library -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-alert.js"></script>
        <!-- modal / dialog library -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-modal.js"></script>
        <!-- custom dropdown library -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-dropdown.js"></script>
        <!-- scrolspy library -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-scrollspy.js"></script>
        <!-- library for creating tabs -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-tab.js"></script>
        <!-- library for advanced tooltip -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-tooltip.js"></script>
        <!-- popover effect library -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-popover.js"></script>
        <!-- button enhancer library -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-button.js"></script>
        <!-- accordion library (optional, not used in demo) -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-collapse.js"></script>
        <!-- carousel slideshow library (optional, not used in demo) -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-carousel.js"></script>
        <!-- autocomplete library -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-typeahead.js"></script>
        <!-- tour library -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-tour.js"></script>
        <!-- library for cookie management -->
        <?php Yii::app()->getClientScript()->registerCoreScript('cookie');?>
        <!-- script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.cookie.js"></script -->
        <!-- calander plugin -->
        <!-- script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/fullcalendar.min.js'></script -->
        <!-- data table plugin -->
        <!-- script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.dataTables.min.js'></script -->

        <!-- chart libraries start -->
        <!--
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/excanvas.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.flot.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.flot.pie.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.flot.stack.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.flot.resize.min.js"></script>
        -->
        <!-- chart libraries end -->

        <!-- select or dropdown enhancer -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.chosen.min.js"></script>
        <!-- checkbox, radio, and file input styler -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.uniform.min.js"></script>
        <!-- plugin for gallery image view -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.colorbox.min.js"></script>
        <!-- rich text editor library -->
        <!-- script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.cleditor.min.js"></script -->
        <!-- notification plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.noty.js"></script>
        <!-- file manager library -->
        <!-- script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.elfinder.min.js"></script -->
        <!-- star rating plugin -->
        <!-- script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.raty.min.js"></script -->
        <!-- for iOS style toggle switch -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.iphone.toggle.js"></script>
        <!-- autogrowing textarea plugin -->
        <!-- script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.autogrow-textarea.js"></script -->
        <!-- multiple file upload plugin -->
        <!-- script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.uploadify-3.1.min.js"></script -->
        <!-- history.js for cross-browser state change on ajax -->
        <?php Yii::app()->getClientScript()->registerCoreScript('history');?>
        <!-- script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.history.js"></script -->
        <!-- application script for Charisma demo -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/charisma.js"></script>

    </body>
</html>
