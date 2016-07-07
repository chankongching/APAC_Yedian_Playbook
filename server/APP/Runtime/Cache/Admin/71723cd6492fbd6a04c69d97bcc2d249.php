<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Bootstrap Admin App + jQuery">
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
    <title><?php echo ($xktvinfo["name"]); ?>-后台管理系统</title>
    <!-- =============== VENDOR STYLES ===============-->
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="/wechatshangjia/Public/Admin/vendor/fontawesome/css/font-awesome.min.css">
    <!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="/wechatshangjia/Public/Admin/vendor/simple-line-icons/css/simple-line-icons.css">
    <!-- ANIMATE.CSS-->
    <link rel="stylesheet" href="/wechatshangjia/Public/Admin/vendor/animate.css/animate.min.css">
    <!-- WHIRL (spinners)-->
    <link rel="stylesheet" href="/wechatshangjia/Public/Admin/vendor/whirl/dist/whirl.css">
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
        <section>
            <!-- Page content-->
            <div class="content-wrapper">
                <div class="content-heading"><?php echo ($xktvinfo["name"]); ?></div>
                <form class="panel" action="<?php echo U(update);?>" method="post">
                    <input type="hidden" name="id" value="<?php echo ($xktvinfo["id"]); ?>">
                    <div ng-app="taocan" id="edit" role="tabpanel" class="tab-pane" ng-init="kid=<?php echo ($kid); ?>">
                        <div class="panel panel-default" ng-controller="shijianduan">
                            <div class="panel-heading">
                                <button type="button" class="btn btn-primary pull-right" ng-click="add()">
                                    <em class="fa fa-plus-circle fa-fw mr-sm"></em>添加时间段</button>
                                <span ng-bind="title"></span>
                            </div>
                            <div class="panel-body">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <!-- <th><strong>ID</strong></th> -->
                                            <th><strong>名称</strong></th>
                                            <th><strong>时间段</strong></th>
                                            <th><strong>操作</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr role="row" class="odd" ng-repeat="shijianduan in shijianduans">
                                            <!-- <td>
                                                <div ng-bind='shijianduan.sid'></div>
                                            </td> -->
                                            <td>
                                                <div ng-bind='getshijianuanname(shijianduan.id)'></div>
                                            </td>
                                            <td><span ng-bind='shijianduan.starttime'></span>-<span ng-if="shijianduan.ciri==1">次日</span><span ng-bind='shijianduan.endtime'></span></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" ng-click="edit(shijianduan)">查看/修改</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <script type="text/ng-template" id="shijianduan.html">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close" ng-click="cancel()">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 id="myModalLabel" class="modal-title"><span ng-bind="title_action"></span></h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" value="" id='shijianduanid' />
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">请选择场次</label>
                                            <div class="col-md-5">
                                                <select ng-options="item.id as item.name for item in shijianduantypes" ng-model="node.id"></select>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">请选择起始时间</label>
                                            <div class="col-md-6">
                                                <select ng-options="item as item for item in times" ng-model="node.starttime"></select>-
                                                <select ng-options="item as item for item in times" ng-model="node.endtime"></select>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">结束时间为次日</label>
                                            <div class="col-md-6">
                                                <input type="checkbox" ng-model='node.ciri'>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" ng-click="cancel()">关闭</button>
                                    <button type="button" class="btn btn-primary" ng-click="ok()">确定</button>
                                </div>
                            </script>
                        </div>
                        <div class="panel panel-default" ng-controller="roomtype">
                            <div class="panel-heading">
                                <button type="button" class="btn btn-primary pull-right" ng-click="add()">
                                    <em class="fa fa-plus-circle fa-fw mr-sm"></em>添加房型</button>
                                <span ng-bind="title"></span></div>
                            <div class="panel-body">
                                <table id="roomtype" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <!-- <th><strong>ID</strong></th> -->
                                            <th><strong>名称</strong></th>
                                            <th><strong>人数</strong></th>
                                            <th><strong>房间数</strong></th>
                                            <th><strong>首页显示</strong></th>
                                            <th><strong>操作</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr role="row" class="odd" ng-repeat="roominfo in roominfos">
                                            <!-- <td><span ng-bind="roominfo.id"></span></td> -->
                                            <td><span ng-bind="roominfo.name"></span></td>
                                            <td><span ng-bind="roominfo.des"></span>人</td>
                                            <td><span ng-bind="roominfo.count"></span></td>
                                            <td><span ng-bind="getshouyeinfo(roominfo.shouye)"></span></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" ng-click="edit(roominfo)">查看/修改</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <script type="text/ng-template" id="roomtype.html">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close" ng-click="cancel()">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" ng-bind="title_action"></h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" value="" id="roomtypeid">
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">房型名称</label>
                                            <div class="col-md-5">
                                                <input type="text" placeholder="房型名称" ng-model='roominfo.name'>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">容纳人数</label>
                                            <div class="col-md-5 form-inline">
                                                <div class="form-group">
                                                    <input type="text" ng-model='roominfo.des_s'>
                                                </div> -
                                                <div class="form-group">
                                                    <input type="text" ng-model='roominfo.des_e'>人
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">房间数量</label>
                                            <div class="col-md-5">
                                                <input type="text" placeholder="房间数量" ng-model='roominfo.count'>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">首页显示</label>
                                            <div class="col-md-5">
                                                <input type="checkbox" ng-model='roominfo.shouye' />
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" ng-click="cancel()">关闭</button>
                                    <button type="button" class="btn btn-primary" ng-click="ok()">确定</button>
                                </div>
                            </script>
                        </div>
                        <div class="panel panel-default" ng-controller="taocan-content">
                            <div class="panel-heading">
                                <button type="button" class="btn btn-primary pull-right" ng-click="add()">
                                    <em class="fa fa-plus-circle fa-fw mr-sm"></em>添加套餐</button>
                                <span ng-bind="title"></span></div>
                            <div class="panel-body">
                                <table id="roomtype2" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <!-- <th><strong>ID</strong></th> -->
                                            <th><strong>名称</strong></th>
                                            <th><strong>时间段</strong></th>
                                            <th><strong>房型</strong></th>
                                            <!-- <th><strong>说明</strong></th>
                                            <th><strong>原价</strong></th>
                                            <th><strong>优惠价</strong></th>
                                            <th><strong>夜点/会员价</strong></th> -->
                                            <th><strong>适用时间</strong></th>
                                            <th><strong>首页推荐</strong></th>
                                            <th><strong>操作</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr role="row" class="odd" ng-repeat="taocan in taocans">
                                            <!-- <td><span ng-bind="taocan.id"></span></td> -->
                                            <td><span ng-bind="taocan.name"></span></td>
                                            <td><span ng-bind="getshijianuanname(taocan.shijianduanid)"></span></td>
                                            <td><span ng-bind="getroomtypename(taocan.roomtypeid)"></span></td>
                                            <!-- <td><span ng-bind="taocan.desc"></span></td>
                                            <td><span ng-bind="taocan.price"></span></td>
                                            <td><span ng-bind="taocan.member_price"></span></td>
                                            <td><span ng-bind="getshouyeinfo(taocan.is_yd_price)"></span></td> -->
                                            <td><span> 
                                            <button ng-class="taocan.sun=='1'?'btn btn-default btn-info':'btn btn-default'">日</button>
                                            <button ng-class="taocan.mon=='1'?'btn btn-default btn-info':'btn btn-default'">一</button>
                                            <button ng-class="taocan.tue=='1'?'btn btn-default btn-info':'btn btn-default'">二</button>
                                            <button ng-class="taocan.wen=='1'?'btn btn-default btn-info':'btn btn-default'">三</button>
                                            <button ng-class="taocan.thu=='1'?'btn btn-default btn-info':'btn btn-default'">四</button>
                                            <button ng-class="taocan.fri=='1'?'btn btn-default btn-info':'btn btn-default'">五</button>
                                            <button ng-class="taocan.sat=='1'?'btn btn-default btn-info':'btn btn-default'">六</button>
                                            </span>
                                            </td>
                                            <td><span ng-bind="getshouyeinfo(taocan.shouye)"></span></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" ng-click="edit(taocan)">查看/修改</button>
                                                <button type="button" class="btn btn-primary" ng-click="del(taocan)">删除</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <script type="text/ng-template" id="taocan.html">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close" ng-click='cancel()'>
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 id="addroomtype" class="modal-title"><span ng-bind="title_action"></span></h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" value="" id="taocancontentid">
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">套餐名称</label>
                                            <div class="col-md-5">
                                                <input type="text" placeholder="套餐名称" ng-model='taocaninfo.name'>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">选择时段</label>
                                            <div class="col-md-5">
                                            <select ng-options="item.sid as item.name+':'+item.starttime+'-'+item.ciritxt+item.endtime for item in shijianduan_scope" ng-model="taocaninfo.shijianduanid"></select>
                                                
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">选择房型</label>
                                            <div class="col-md-5">
                                            <select ng-options="item.id as item.name for item in roominfos_scope" ng-model="taocaninfo.roomtypeid"></select>
                                            
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">套餐详情</label>
                                            <div class="col-md-5">
                                                <textarea placeholder="套餐详情" id='addtaocandesc' rows="5" class="form-control" ng-model='taocaninfo.desc'></textarea>
                                                <p>不同内容之间请使用全角逗号“，”来分隔</p>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">原价</label>
                                            <div class="col-md-5">
                                                <input type="text" placeholder="原价" ng-model='taocaninfo.price'>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">优惠价</label>
                                            <div class="col-md-5">
                                                <input type="text" placeholder="优惠价" ng-model='taocaninfo.member_price'>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">是否夜点价</label>
                                            <div class="col-md-5">
                                                <input type="checkbox" ng-model='taocaninfo.is_yd_price'>
                                                <p>勾选此项，则在夜点娱乐－KTV详情页中显示夜点专属优惠价格</p>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">适用日期</label>
                                            <div class="col-md-5">
                                                周一：
                                                <input type="checkbox" ng-model='taocaninfo.mon' />
                                                <br> 周二：
                                                <input type="checkbox" ng-model='taocaninfo.tue' />
                                                <br> 周三：
                                                <input type="checkbox" ng-model='taocaninfo.wen' />
                                                <br> 周四：
                                                <input type="checkbox" ng-model='taocaninfo.thu' />
                                                <br> 周五：
                                                <input type="checkbox" ng-model='taocaninfo.fri' />
                                                <br> 周六：
                                                <input type="checkbox" ng-model='taocaninfo.sat' />
                                                <br> 周日：
                                                <input type="checkbox" ng-model='taocaninfo.sun' />
                                                <br>
                                            </div>
                                            周末
                                            <input type="checkbox" ng-click="selweekend()" ng-model='selweekends'/>
                                            <br> 工作日
                                            <input type="checkbox" ng-click="selworkday()" ng-model='selworkdays'/>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">首页显示</label>
                                            <div class="col-md-5">
                                                <input type="checkbox" ng-model='taocaninfo.shouye' />
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" ng-click="cancel()">关闭</button>
                                    <button type="button" class="btn btn-primary" ng-click="ok()">确定</button>
                                </div>
                            </script>
                        </div>
                        <div class="panel panel-default" ng-controller="zonglan">
                            <div class="panel-heading">
                                <span ng-bind="title"></span></div>
                            <div class="panel-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>套餐总览</th>
                                            <th>周日</th>
                                            <th>周一</th>
                                            <th>周二</th>
                                            <th>周三</th>
                                            <th>周四</th>
                                            <th>周五</th>
                                            <th>周六</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="taocan in zonglan">
                                            <th scope="row">{{taocan.shijianduan}}</th>
                                            <td>
                                                <div ng-repeat="node in taocan.sun">
                                                    <button type="button" class="btn btn-info" style="margin:5px auto">{{node}}</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div ng-repeat="node in taocan.mon">
                                                    <button type="button" class="btn btn-info" style="margin:5px auto">{{node}}</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div ng-repeat="node in taocan.tue">
                                                    <button type="button" class="btn btn-info" style="margin:5px auto">{{node}}</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div ng-repeat="node in taocan.wen">
                                                    <button type="button" class="btn btn-info" style="margin:5px auto">{{node}}</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div ng-repeat="node in taocan.thu">
                                                    <button type="button" class="btn btn-info" style="margin:5px auto">{{node}}</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div ng-repeat="node in taocan.fri">
                                                    <button type="button" class="btn btn-info" style="margin:5px auto">{{node}}</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div ng-repeat="node in taocan.sat">
                                                    <button type="button" class="btn btn-info" style="margin:5px auto">{{node}}</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="panel panel-default" ng-controller="tiaokuan">
                            <div class="panel-heading">
                                <button type="button" class="btn btn-primary pull-right" ng-click="add()">
                                    <em class="fa fa-plus-circle fa-fw mr-sm"></em>添加条款</button>
                                <span ng-bind="title"></span></div>
                            <div class="panel-body">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <!-- <th><strong>ID</strong></th> -->
                                            <th><strong>名称</strong></th>
                                            <th><strong>操作</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr role="row" class="odd" ng-repeat="tiaokuan in tiaokuans">
                                            <!-- <td class="sorting_1">{{tiaokuan.id}}</td> -->
                                            <td><span ng-bind="tiaokuan.name"></span></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" ng-click="edit(tiaokuan)">修改</button>
                                                <button type="button" class="btn btn-primary" ng-click="del(tiaokuan)">删除</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <script type="text/ng-template" id="tiaokuan.html">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-label="Close" class="close" ng-click="cancel()">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title"><span ng-bind='title_action'></span></h4>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" value="" id="tiaokuanid">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <label class="col-md-5 control-label">内容</label>
                                                        <div class="col-md-5">
                                                            <input type="text" placeholder="条款内容" ng-model='tiaokuaninfo.name'>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="modal-footer">
                                    <button type="button" class="btn btn-default" ng-click="cancel()">关闭</button>
                                    <button type="button" class="btn btn-primary" ng-click="ok()">确定</button>
                                </div>
                                        
                            </script>
                        </div>
                    </div>
            </div>
            <div class="text-right mt-lg">
                <button type="button" class="btn btn-warning">取消</button>
                <button type="submit" class="btn btn-success">保存</button>
            </div>
            </form>
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
    <!-- =============== APP SCRIPTS ===============-->
    <script src="/wechatshangjia/Public/Admin/js/app.js"></script>
    <script src="/wechatshangjia/Public/Admin/js/angular.min.js"></script>
    <script src="/wechatshangjia/Public/Admin/js/ui-bootstrap-tpls-1.3.2.min.js"></script>
    <script src="/wechatshangjia/Public/Admin/js/adminapp/taocan.js"></script>
    
    
    <script type="text/javascript">
    $('.btn-addtiaokuan').on('click', function() {
        if ($('#tiaokuanname').val() == '') {
            alert('请填写必要的信息');
            return;
        }
        var ktvid = '<?php echo ($kid); ?>';
        var pdata = {
            'name': $('#tiaokuanname').val(),
            'ktvid': ktvid,
            'tiaokuanid': $('#tiaokuanid').val()
        };
        $.ajax({
            'url': '<?php echo U('
            Taocan / addtiaokuan ');?>',
            'data': pdata,
            'type': 'post',
            success: function(data) {
                var msg = JSON.parse(data);
                if (msg.result == 0) {
                    if ($('#tiaokuanid').val() > 0) {
                        alert('修改成功');
                    } else {
                        alert('添加成功');
                    }
                    $('#addtiaokuan').modal('hide');
                    location.replace(location.href);
                }
            }
        });
    })
    </script>
</body>

</html>