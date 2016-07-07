<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Bootstrap Admin App + jQuery">
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
    <title>夜点管理系统</title>
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
        
        <!-- Main section-->
        <section>
            <!-- Page content-->
            <div class="content-wrapper">
                <div class="content-heading">
                    <a href="<?php echo U('Xktv/update');?>"><button type="button" class="btn btn-primary pull-right" id="importdata">
                        <em class="fa fa-plus-circle fa-fw mr-sm"></em>添加KTV</button></a>KTV管理</div>
                <div class="table-responsive b0">
                    <table id="Xktvlist" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>
                                <strong>KTVID</strong>
                            </th>
                            <th>
                                <strong>名称</strong>
                            </th>
                            <th>
                                <strong>预定电话</strong>
                            </th>
                            <th>
                                <strong>Ktv类型</strong>
                            </th>
                            <th>
                                <strong>营业时间</strong>
                            </th>
                            <th class="text-center">
                                <strong>地址</strong>
                            </th>
                            <th>
                                <strong>经理</strong>
                            </th>
                            <th class="text-center">
                                <strong>经理电话</strong>
                            </th>
                            <th class="text-center">
                                <strong>更新者</strong>
                            </th>
                            <th class="text-center">
                                <strong>更新时间</strong>
                            </th>
                            
                            <th class="text-center">
                                <strong>操作</strong>
                            </th>
                        </tr>
                        </thead>
                        
                    </table>
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
    <!-- DATATABLES-->
    <script src="/wechatshangjia/Public/Admin/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="/wechatshangjia/Public/Admin/vendor/datatables-colvis/js/dataTables.colVis.js"></script>
    <script src="/wechatshangjia/Public/Admin/vendor/datatable-bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="/wechatshangjia/Public/Admin/vendor/datatable-bootstrap/js/dataTables.bootstrapPagination.js"></script>
    <script src="/wechatshangjia/Public/Admin/js/demo/demo-datatable.js"></script>
    <!-- =============== APP SCRIPTS ===============-->
    <script src="/wechatshangjia/Public/Admin/js/app.js"></script>

</body>

</html>