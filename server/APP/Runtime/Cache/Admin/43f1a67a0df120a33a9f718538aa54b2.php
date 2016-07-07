<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Bootstrap Admin App + jQuery">
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
    <title>修改时间段-套餐系统-夜点管理系统</title>
    <!-- =============== VENDOR STYLES ===============-->
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="/wechatshangjia/Public/Admin/vendor/fontawesome/css/font-awesome.min.css">
    <!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="/wechatshangjia/Public/Admin/vendor/simple-line-icons/css/simple-line-icons.css">
    <!-- ANIMATE.CSS-->
    <link rel="stylesheet" href="/wechatshangjia/Public/Admin/vendor/animate.css/animate.min.css">
    <!-- WHIRL (spinners)-->
    <link rel="stylesheet" href="/wechatshangjia/Public/Admin/vendor/whirl/dist/whirl.css">
    <!-- =============== PAGE VENDOR STYLES ===============-->
    <link rel="stylesheet" href="/wechatshangjia/Public/Admin/vendor/datatables-colvis/css/dataTables.colVis.css">
    <link rel="stylesheet" href="/wechatshangjia/Public/Admin/vendor/datatable-bootstrap/css/dataTables.bootstrap.css">
    <!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="/wechatshangjia/Public/Admin/css/bootstrap.css" id="bscss">
    <!-- =============== APP STYLES ===============-->
    <link rel="stylesheet" href="/wechatshangjia/Public/Admin/css/app.css" id="maincss">
</head>

<body>
<div class="wrapper">
    <!-- top navbar-->
    <header class="topnavbar-wrapper">
        <!-- START Top Navbar-->
        <nav role="navigation" class="navbar topnavbar">
    <!-- START navbar header-->
    <div class="navbar-header">
        <a href="#/" class="navbar-brand">
            <div class="brand-logo">
                <img src="/wechatshangjia/Public/Admin/img/logo.png" alt="App Logo" class="img-responsive">
            </div>
            <div class="brand-logo-collapsed">
                <img src="/wechatshangjia/Public/Admin/img/logo-single.png" alt="App Logo" class="img-responsive">
            </div>
        </a>
    </div>
    <!-- END navbar header-->
    <!-- START Nav wrapper-->
    <div class="nav-wrapper">
        <!-- START Left navbar-->
        <ul class="nav navbar-nav">
            <li>
                <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
                <a href="#" data-toggle-state="aside-collapsed" class="hidden-xs">
                    <em class="fa fa-navicon"></em>
                </a>
                <!-- Button to show/hide the sidebar on mobile. Visible on mobile only.-->
                <a href="#" data-toggle-state="aside-toggled" data-no-persist="true" class="visible-xs sidebar-toggle">
                    <em class="fa fa-navicon"></em>
                </a>
            </li>
            
        </ul>
        <!-- END Left navbar-->
        <!-- START Right Navbar-->
        <ul class="nav navbar-nav navbar-right">
            <!-- Search icon-->
            
            
            <!-- START Alert menu-->
            
            <!-- END Alert menu-->
            
            <!-- START LOGOUT Button -->
            <li class="visible-lg">
                <a href="<?php echo U('Admin/Public/logout');?>">
                    <em class="icon-logout"></em>
                </a>
            </li>
            <!-- END LOGOUT Button -->
        </ul>
        <!-- END Right Navbar-->
    </div>
    <!-- END Nav wrapper-->
    <!-- START Search form-->
    <form role="search" action="search.html" class="navbar-form">
        <div class="form-group has-feedback">
            <input type="text" placeholder="Type and hit enter ..." class="form-control">
            <div data-search-dismiss="" class="fa fa-times form-control-feedback"></div>
        </div>
        <button type="submit" class="hidden btn btn-default">Submit</button>
    </form>
    <!-- END Search form-->
</nav>

        <!-- END Top Navbar-->
    </header>
    <!-- sidebar-->
    <aside class="aside">
    <!-- START Sidebar (left)-->
    <div class="aside-inner">
        <nav data-sidebar-anyclick-close="" class="sidebar">
            <!-- START sidebar nav-->
            <ul class="nav">
                <!-- Iterates over all sidebar items-->
                <li class="nav-heading ">
                    <span data-localize="sidebar.heading.HEADER">夜点管理系统</span>
                </li>
                <li class=" ">
                    <a href="#xktv" title="XKTV管理" data-toggle="collapse">
                        <!-- <div class="pull-right label label-info">0</div> -->
                        <em class="icon-wrench"></em>
                        <span data-localize="sidebar.nav.MXktv">KTV管理</span>
                    </a>
                    <ul id="xktv" class="nav sidebar-subnav collapse">
                        <li class="sidebar-subnav-header">KTV列表</li>
                        <li class=" ">
                            <a href="<?php echo U('Xktv/lists');?>" title="XKTV列表">
                                <span>KTV列表</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class=" ">
                    <a href="#taocanM" title="套餐管理" data-toggle="collapse">
                        <!-- <div class="pull-right label label-info">0</div> -->
                        <em class="icon-wrench"></em>
                        <span data-localize="sidebar.nav.MXktv">套餐管理</span>
                    </a>
                    <ul id="taocanM" class="nav sidebar-subnav collapse">
                        <li class="sidebar-subnav-header">套餐管理</li>
                        <li class=" ">
                            <a href="<?php echo U('Taocan/datelists');?>" title="时间段管理">
                                <span>时间段管理</span>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
            <!-- END sidebar nav-->
        </nav>
    </div>
    <!-- END Sidebar (left)-->
</aside>

    <!-- offsidebar-->
    <aside class="offsidebar hide">
            <!-- START Off Sidebar (right)-->
            <nav>
                <div role="tabpanel">
                    <!-- Nav tabs-->
                    <ul role="tablist" class="nav nav-tabs nav-justified">
                        <li role="presentation" class="active">
                            <a href="#app-settings" aria-controls="app-settings" role="tab" data-toggle="tab">
                                <em class="icon-equalizer fa-lg"></em>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#app-chat" aria-controls="app-chat" role="tab" data-toggle="tab">
                                <em class="icon-users fa-lg"></em>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes-->
                    <div class="tab-content">
                        <div id="app-settings" role="tabpanel" class="tab-pane fade in active">
                            <h3 class="text-center text-thin">Settings</h3>
                            <div class="p">
                                <h4 class="text-muted text-thin">Themes</h4>
                                <div class="table-grid mb">
                                    <div class="col mb">
                                        <div class="setting-color">
                                            <label data-load-css="css/theme-a.css">
                                                <input type="radio" name="setting-theme" checked="checked">
                                                <span class="icon-check"></span>
                                                <span class="split">
                                       <span class="color bg-info"></span>
                                                <span class="color bg-info-light"></span>
                                                </span>
                                                <span class="color bg-white"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col mb">
                                        <div class="setting-color">
                                            <label data-load-css="css/theme-b.css">
                                                <input type="radio" name="setting-theme">
                                                <span class="icon-check"></span>
                                                <span class="split">
                                       <span class="color bg-green"></span>
                                                <span class="color bg-green-light"></span>
                                                </span>
                                                <span class="color bg-white"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col mb">
                                        <div class="setting-color">
                                            <label data-load-css="css/theme-c.css">
                                                <input type="radio" name="setting-theme">
                                                <span class="icon-check"></span>
                                                <span class="split">
                                       <span class="color bg-purple"></span>
                                                <span class="color bg-purple-light"></span>
                                                </span>
                                                <span class="color bg-white"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col mb">
                                        <div class="setting-color">
                                            <label data-load-css="css/theme-d.css">
                                                <input type="radio" name="setting-theme">
                                                <span class="icon-check"></span>
                                                <span class="split">
                                       <span class="color bg-danger"></span>
                                                <span class="color bg-danger-light"></span>
                                                </span>
                                                <span class="color bg-white"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-grid mb">
                                    <div class="col mb">
                                        <div class="setting-color">
                                            <label data-load-css="css/theme-e.css">
                                                <input type="radio" name="setting-theme">
                                                <span class="icon-check"></span>
                                                <span class="split">
                                       <span class="color bg-info-dark"></span>
                                                <span class="color bg-info"></span>
                                                </span>
                                                <span class="color bg-gray-dark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col mb">
                                        <div class="setting-color">
                                            <label data-load-css="css/theme-f.css">
                                                <input type="radio" name="setting-theme">
                                                <span class="icon-check"></span>
                                                <span class="split">
                                       <span class="color bg-green-dark"></span>
                                                <span class="color bg-green"></span>
                                                </span>
                                                <span class="color bg-gray-dark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col mb">
                                        <div class="setting-color">
                                            <label data-load-css="css/theme-g.css">
                                                <input type="radio" name="setting-theme">
                                                <span class="icon-check"></span>
                                                <span class="split">
                                       <span class="color bg-purple-dark"></span>
                                                <span class="color bg-purple"></span>
                                                </span>
                                                <span class="color bg-gray-dark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col mb">
                                        <div class="setting-color">
                                            <label data-load-css="css/theme-h.css">
                                                <input type="radio" name="setting-theme">
                                                <span class="icon-check"></span>
                                                <span class="split">
                                       <span class="color bg-danger-dark"></span>
                                                <span class="color bg-danger"></span>
                                                </span>
                                                <span class="color bg-gray-dark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p">
                                <h4 class="text-muted text-thin">Layout</h4>
                                <div class="clearfix">
                                    <p class="pull-left">Fixed</p>
                                    <div class="pull-right">
                                        <label class="switch">
                                            <input id="chk-fixed" type="checkbox" data-toggle-state="layout-fixed">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <p class="pull-left">Boxed</p>
                                    <div class="pull-right">
                                        <label class="switch">
                                            <input id="chk-boxed" type="checkbox" data-toggle-state="layout-boxed">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <p class="pull-left">RTL</p>
                                    <div class="pull-right">
                                        <label class="switch">
                                            <input id="chk-rtl" type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="p">
                                <h4 class="text-muted text-thin">Aside</h4>
                                <div class="clearfix">
                                    <p class="pull-left">Collapsed</p>
                                    <div class="pull-right">
                                        <label class="switch">
                                            <input id="chk-collapsed" type="checkbox" data-toggle-state="aside-collapsed">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <p class="pull-left">Float</p>
                                    <div class="pull-right">
                                        <label class="switch">
                                            <input id="chk-float" type="checkbox" data-toggle-state="aside-float">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <p class="pull-left">Hover</p>
                                    <div class="pull-right">
                                        <label class="switch">
                                            <input id="chk-hover" type="checkbox" data-toggle-state="aside-hover">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="app-chat" role="tabpanel" class="tab-pane fade">
                            <h3 class="text-center text-thin">Connections</h3>
                            <ul class="nav">
                                <!-- START list title-->
                                <li class="p">
                                    <small class="text-muted">ONLINE</small>
                                </li>
                                <!-- END list title-->
                                <li>
                                    <!-- START User status-->
                                    <a href="#" class="media-box p mt0">
                                        <span class="pull-right">
                                 <span class="circle circle-success circle-lg"></span>
                                        </span>
                                        <span class="pull-left">
                                 <!-- Contact avatar-->
                                 <img src="/wechatshangjia/Public/Admin/img/user/05.jpg" alt="Image" class="media-box-object img-circle thumb48">
                              </span>
                                        <!-- Contact info-->
                                        <span class="media-box-body">
                                 <span class="media-box-heading">
                                    <strong>Juan Sims</strong>
                                    <br>
                                    <small class="text-muted">Designeer</small>
                                 </span>
                                        </span>
                                    </a>
                                    <!-- END User status-->
                                    <!-- START User status-->
                                    <a href="#" class="media-box p mt0">
                                        <span class="pull-right">
                                 <span class="circle circle-success circle-lg"></span>
                                        </span>
                                        <span class="pull-left">
                                 <!-- Contact avatar-->
                                 <img src="/wechatshangjia/Public/Admin/img/user/06.jpg" alt="Image" class="media-box-object img-circle thumb48">
                              </span>
                                        <!-- Contact info-->
                                        <span class="media-box-body">
                                 <span class="media-box-heading">
                                    <strong>Maureen Jenkins</strong>
                                    <br>
                                    <small class="text-muted">Designeer</small>
                                 </span>
                                        </span>
                                    </a>
                                    <!-- END User status-->
                                    <!-- START User status-->
                                    <a href="#" class="media-box p mt0">
                                        <span class="pull-right">
                                 <span class="circle circle-danger circle-lg"></span>
                                        </span>
                                        <span class="pull-left">
                                 <!-- Contact avatar-->
                                 <img src="/wechatshangjia/Public/Admin/img/user/07.jpg" alt="Image" class="media-box-object img-circle thumb48">
                              </span>
                                        <!-- Contact info-->
                                        <span class="media-box-body">
                                 <span class="media-box-heading">
                                    <strong>Billie Dunn</strong>
                                    <br>
                                    <small class="text-muted">Designeer</small>
                                 </span>
                                        </span>
                                    </a>
                                    <!-- END User status-->
                                    <!-- START User status-->
                                    <a href="#" class="media-box p mt0">
                                        <span class="pull-right">
                                 <span class="circle circle-warning circle-lg"></span>
                                        </span>
                                        <span class="pull-left">
                                 <!-- Contact avatar-->
                                 <img src="/wechatshangjia/Public/Admin/img/user/08.jpg" alt="Image" class="media-box-object img-circle thumb48">
                              </span>
                                        <!-- Contact info-->
                                        <span class="media-box-body">
                                 <span class="media-box-heading">
                                    <strong>Tomothy Roberts</strong>
                                    <br>
                                    <small class="text-muted">Designer</small>
                                 </span>
                                        </span>
                                    </a>
                                    <!-- END User status-->
                                </li>
                                <!-- START list title-->
                                <li class="p">
                                    <small class="text-muted">OFFLINE</small>
                                </li>
                                <!-- END list title-->
                                <li>
                                    <!-- START User status-->
                                    <a href="#" class="media-box p mt0">
                                        <span class="pull-right">
                                 <span class="circle circle-lg"></span>
                                        </span>
                                        <span class="pull-left">
                                 <!-- Contact avatar-->
                                 <img src="/wechatshangjia/Public/Admin/img/user/09.jpg" alt="Image" class="media-box-object img-circle thumb48">
                              </span>
                                        <!-- Contact info-->
                                        <span class="media-box-body">
                                 <span class="media-box-heading">
                                    <strong>Lawrence Robinson</strong>
                                    <br>
                                    <small class="text-muted">Developer</small>
                                 </span>
                                        </span>
                                    </a>
                                    <!-- END User status-->
                                    <!-- START User status-->
                                    <a href="#" class="media-box p mt0">
                                        <span class="pull-right">
                                 <span class="circle circle-lg"></span>
                                        </span>
                                        <span class="pull-left">
                                 <!-- Contact avatar-->
                                 <img src="/wechatshangjia/Public/Admin/img/user/10.jpg" alt="Image" class="media-box-object img-circle thumb48">
                              </span>
                                        <!-- Contact info-->
                                        <span class="media-box-body">
                                 <span class="media-box-heading">
                                    <strong>Tyrone Owens</strong>
                                    <br>
                                    <small class="text-muted">Designer</small>
                                 </span>
                                        </span>
                                    </a>
                                    <!-- END User status-->
                                </li>
                                <li>
                                    <div class="p-lg text-center">
                                        <!-- Optional link to list more users-->
                                        <a href="#" title="See more contacts" class="btn btn-purple btn-sm">
                                            <strong>Load more..</strong>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                            <!-- Extra items-->
                            <div class="p">
                                <p>
                                    <small class="text-muted">Tasks completion</small>
                                </p>
                                <div class="progress progress-xs m0">
                                    <div role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-success progress-80">
                                        <span class="sr-only">80% Complete</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p">
                                <p>
                                    <small class="text-muted">Upload quota</small>
                                </p>
                                <div class="progress progress-xs m0">
                                    <div role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-warning progress-40">
                                        <span class="sr-only">40% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- END Off Sidebar (right)-->
        </aside>
    <!-- Main section-->
    <!-- Main section-->
    <section>
        <!-- Page content-->
        <div class="content-wrapper">
            <div class="content-heading">添加时间段</div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    添加时间段
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal p-20" action="<?php echo U();?>" method="post">
                                <input type="hidden" value="<?php echo ($info["id"]); ?>" name="id">
                                <div class="form-group">
                                    <div class="col-sm-4">时段名称:</div>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="请填写时段名称" name='name' value="<?php echo ($info["name"]); ?>" />
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-block alert alert-success">确定修改</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Main section-->
    <!-- Page footer-->
    <footer>
            <div class="p-lg text-center">
                <span>&copy;</span>
                <span>2016</span>
                <span></span>
                <span>夜点管理平台</span>
                <br>
                <span></span>
            </div>
        </footer>
</div>
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="/wechatshangjia/Public/Admin/vendor/modernizr/modernizr.js"></script>
<!-- JQUERY-->
<script src="/wechatshangjia/Public/Admin/vendor/jquery/dist/jquery.js"></script>
<!-- BOOTSTRAP-->
<script src="/wechatshangjia/Public/Admin/vendor/bootstrap/dist/js/bootstrap.js"></script>
<!-- STORAGE API-->
<script src="/wechatshangjia/Public/Admin/vendor/jQuery-Storage-API/jquery.storageapi.js"></script>
<!-- JQUERY EASING-->
<script src="/wechatshangjia/Public/Admin/vendor/jquery.easing/js/jquery.easing.js"></script>
<!-- ANIMO-->
<script src="/wechatshangjia/Public/Admin/vendor/animo.js/animo.js"></script>
<!-- SLIMSCROLL-->
<script src="/wechatshangjia/Public/Admin/vendor/slimScroll/jquery.slimscroll.min.js"></script>
<!-- SCREENFULL-->
<script src="/wechatshangjia/Public/Admin/vendor/screenfull/dist/screenfull.js"></script>
<!-- LOCALIZE-->
<script src="/wechatshangjia/Public/Admin/vendor/jquery-localize-i18n/dist/jquery.localize.js"></script>
<!-- RTL demo-->
<script src="/wechatshangjia/Public/Admin/js/demo/demo-rtl.js"></script>
<!-- =============== PAGE VENDOR SCRIPTS ===============-->
<!-- =============== APP SCRIPTS ===============-->
<script src="/wechatshangjia/Public/Admin/js/app.js"></script>
</body>

</html>