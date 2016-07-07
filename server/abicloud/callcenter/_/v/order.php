<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <title>管理中心</title>
   <!-- =============== VENDOR STYLES ===============-->
   <!-- FONT AWESOME-->
   <link rel="stylesheet" href="public/vendor/fontawesome/css/font-awesome.min.css">
   <!-- SIMPLE LINE ICONS-->
   <link rel="stylesheet" href="public/vendor/simple-line-icons/css/simple-line-icons.css">
   <!-- ANIMATE.CSS-->
   <link rel="stylesheet" href="public/vendor/animate.css/animate.min.css">
   <!-- WHIRL (spinners)-->
   <link rel="stylesheet" href="public/vendor/whirl/dist/whirl.css">
   <!-- =============== PAGE VENDOR STYLES ===============-->
   <!-- DATATABLES-->
   <link rel="stylesheet" href="public/vendor/datatables-colvis/css/dataTables.colVis.css">
   <link rel="stylesheet" href="public/app/vendor/datatable-bootstrap/css/dataTables.bootstrap.css">
   <!-- =============== BOOTSTRAP STYLES ===============-->
   <link rel="stylesheet" href="public/app/css/bootstrap.css" id="bscss">
   <!-- =============== APP STYLES ===============-->
   <link rel="stylesheet" href="public/app/css/app.css" id="maincss">
   <link id="autoloaded-stylesheet" rel="stylesheet" href="public/app/css/theme-h.css">
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
                     <img src="public/app/img/page_logo.png" alt="" class="img-responsive">
                  </div>
               </a>
            </div>
            <!-- END navbar header-->
            <!-- START Nav wrapper-->
            <div class="nav-wrapper">
               <span style="display: block;float: left;line-height: 55px;font-size: 16px;color: #fff;">KTV订单处理系统</span>
               <!-- START Right Navbar-->
               <ul class="nav navbar-nav navbar-right">
                  <!-- START Offsidebar button-->
                  <li>
                     <a href="index.php?m=logout" data-toggle-state="offsidebar-open" data-no-persist="true">
                        <em class="icon-logout"></em>
                     </a>
                  </li>
                  <!-- END Offsidebar menu-->
               </ul>
               <!-- END Right Navbar-->
            </div>
            <!-- END Nav wrapper-->
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
<?php if(ROLE == 'admin') {?>
                  <li class=" ">
                     <a href="index.php" title="Dashboard" data-toggle="collapse">
                        <em class="icon-speedometer"></em>
                        <span data-localize="sidebar.nav.DASHBOARD">总览</span>
                     </a>
                  </li>
<?php }?>
                  <li class=" ">
                     <a href="index.php?m=orders&a=today&type=todo" title="Layouts" data-toggle="">
                        <em class="icon-layers"></em>
                        <span>订单</span>
                     </a>
                  </li>
               </ul>
               <!-- END sidebar nav-->
            </nav>
         </div>
         <!-- END Sidebar (left)-->
      </aside>
      <!-- Main section-->
      <section>
         <!-- Page content-->
         <div class="content-wrapper">
            <div class="panel panel-default">
               <div class="panel-heading">订单信息
                   <span style="margin-left: 2em;" class="<?php if(array(array_key_exists($order['status'], $orderstatusstyle))) {echo $orderstatusstyle[$order['status']];}?>">
                    <?php if(array(array_key_exists($order['status'], $orderstatus))) {echo $orderstatus[$order['status']];}?>
                   </span>
                   <span style="float: right;">
                    <?php if(isset($referer)){echo '<a id="back" class="btn btn-info" onclick="javascript:window.location.href=\''.$referer.'\';return false;">返回</a></span>';}?>
                   </div>
               <div class="panel-body">
<?php
if(isset($order) && is_array($order) && !empty($order)) {
?>
                  <div class="row">
                     <div class="col-md-6">
                        <form class="form-horizontal p-20">
                           <div class="form-group">
                              <div class="col-sm-3">下单时间:</div>
                              <div class="col-sm-9">
                                 <strong><?php echo $order['time'];?></strong>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-sm-3">KTV名称:</div>
                              <div class="col-sm-9">
                                 <strong><?php echo $order['name'];?></strong>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-sm-3">KTV电话:</div>
                              <div class="col-sm-9">
                                 <strong><?php echo $order['pretelephone'];?> - <?php echo $order['telephone'];?></strong>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-sm-3">KTV地址:</div>
                              <div class="col-sm-9">
                                 <strong><?php echo $order['district'];?> - <?php echo $order['address'];?></strong>
                              </div>
                           </div>
<?php
if(ROLE == 'admin') {
?>
                           <div class="form-group">
                              <div class="col-sm-3">操作员:</div>
                              <div class="col-sm-9">
                                 <strong><?php echo $users_by_id[$order['cc_user']];?></strong>
                              </div>
                           </div>
<?php
}
?>
                        </form>
                     </div>
                     <div class="col-md-6">
                        <form class="form-horizontal p-20">
                           <div class="form-group">
                              <div class="col-sm-3">订单ID:</div>
                              <div class="col-sm-9">
                                 <strong><?php echo date('Ymd', TIME).sprintf("%08d", $order['id']);?></strong>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-sm-3">用户:</div>
                              <div class="col-sm-9">
                                 <strong><?php echo $order['display_name'];?> &nbsp; <?php echo $order['mobile'];?></strong>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-sm-9">
<!--                                  <strong>人数：<?php //echo $order['members'];?>  &nbsp; -->房型：<?php if(array(array_key_exists($order['roomtype'], $roomtypes))) {echo $roomtypes[$order['roomtype']];}?></strong>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-sm-12">
                                 <strong>开始时间：<?php echo $order['starttime'];?> &nbsp; 持续时间：<?php echo $order['last'];?>小时</strong>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-sm-12">
                                 <strong>处理时间：<?php echo $order['update_time'];?></strong>
                              </div>
                           </div>
                        </form>
                     </div>
<?php
if($order['status'] == 1 && ROLE == 'operator') {
?>
                     <div class="col-md-6"></div>
                     <div class="col-md-6">
                         <input id="confirmOrder" data-id="<?php echo $order['id'];?>" class="btn btn-info btn-sm" type="button" value="确认订单" /> &nbsp; 
                         <input id="cancelOrder" data-id="<?php echo $order['id'];?>" class="btn btn-warning btn-sm" type="button" value="没有房间" />
                     </div>
<?php
}
?>
                  </div>
<?php
} else {
?>
                  <div class="alert alert-warning">
                     <em class="fa fa-exclamation-circle fa-lg fa-fw"></em>没有该订单，或该订单没有分配给您。</div>
<?php
}
?>
               </div>
            </div>
         </div>
      </section>
      <!-- Page footer-->
      <footer>
         <span></span>
      </footer>
   </div>
   <!-- =============== VENDOR SCRIPTS ===============-->
   <!-- MODERNIZR-->
   <script src="public/vendor/modernizr/modernizr.js"></script>
   <!-- JQUERY-->
   <script src="public/vendor/jquery/dist/jquery.js"></script>
   <!-- BOOTSTRAP-->
   <script src="public/vendor/bootstrap/dist/js/bootstrap.js"></script>
   <!-- STORAGE API-->
   <script src="public/vendor/jQuery-Storage-API/jquery.storageapi.js"></script>
   <!-- JQUERY EASING-->
   <script src="public/vendor/jquery.easing/js/jquery.easing.js"></script>
   <!-- ANIMO-->
   <script src="public/vendor/animo.js/animo.js"></script>
   <!-- SLIMSCROLL-->
   <script src="public/vendor/slimScroll/jquery.slimscroll.min.js"></script>
   <!-- SCREENFULL-->
   <script src="public/vendor/screenfull/dist/screenfull.js"></script>
   <!-- LOCALIZE-->
   <script src="public/vendor/jquery-localize-i18n/dist/jquery.localize.js"></script>
   <!-- RTL demo-->
   <script src="public/app/js/demo/demo-rtl.js"></script>
   <!-- =============== PAGE VENDOR SCRIPTS ===============-->
   <!-- DATATABLES-->
   <script src="public/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
   <script src="public/vendor/datatables-colvis/js/dataTables.colVis.js"></script>
   <script src="public/app/vendor/datatable-bootstrap/js/dataTables.bootstrap.js"></script>
   <script src="public/app/vendor/datatable-bootstrap/js/dataTables.bootstrapPagination.js"></script>
   <script src="public/app/js/demo/demo-datatable.js"></script>
   <!-- =============== APP SCRIPTS ===============-->
   <script type="text/javascript">
<?php
if($orderby) {
?>
      var orderby = 'asc';
<?php
} else {
?>
      var orderby = 'desc';
<?php
}
?>
   </script>
   <script src="public/app.js"></script>
</body>

</html>