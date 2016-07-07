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
   <style tyle="text/css">
       #datatable1 .btn-default {padding: 0;}
       #datatable1 .btn-default a {padding: 5px 10px;}
       #datatable1 .btn-default a:hover {text-decoration: none;}
   </style>
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
                     <a href="index.php" title="Dashboard" data-toggle="">
                        <em class="icon-speedometer"></em>
                        <span data-localize="sidebar.nav.DASHBOARD">总览</span>
                     </a>
                  </li>
<?php }?>
                  <li class=" ">
                     <a href="index.php?m=orders&a=<?php if(ROLE == 'operator') {echo 'today';} else {echo 'today';}?>&type=<?php if(ROLE == 'operator') {echo 'todo';} else {echo 'all';}?>" title="Layouts" data-toggle="">
                        <em class="icon-layers"></em>
                        <span><?php if(ROLE == 'operator') {echo 'Call Center ';}?>订单</span>
                     </a>
                  </li>
<?php if(ROLE == 'operator') {
?>
                  <li class=" ">
                     <a href="index.php?m=orders&a=sjb" title="Layouts" data-toggle="">
                        <em class="icon-layers"></em>
                        <span>商户版订单</span>
                     </a>
                  </li>
<?php
}?>

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
<?php
if($a == 'dashbord') {
?>
            <div class="content-heading">总览</div>
            <div class="row">
               <div class="col-lg-12">
                  <!-- START panel-->
                  <div class="panel panel-default">
                     <div class="panel-body">
                        <!-- START table-responsive-->
                        <div class="table-responsive">
                           <table class="table table-striped table-bordered table-hover">
                              <thead>
                                 <tr>
                                     <th colspan="10">
                                         当前共有 <?php if(isset($_online_users) && is_array($_online_users)) {echo count($_online_users);} else {echo '0';}?> 位员工登录(非实时信息)
                                         <span style="float: right;"><a class="btn btn-info" href="<?php echo URL;?>?m=orders&a=today&type=all">今日订单</a></span>&nbsp; &nbsp;
                                         <span style="float: right;margin-right: 2em;"><a class="btn btn-info" href="<?php echo URL;?>?m=orders&a=history&type=all">历史订单</a></span>
                                        </th>
                                 </tr>
                                 <tr>
                                    <th>用户</th>
                                    <th>今日总订单</th>
                                    <th>今日待处理订单</th>
                                    <th>今日已确定订单</th>
                                    <th>今日无房间订单</th>
                                    <th>今日用户取消订单</th>
                                    <th>今日超时订单</th>
                                    <th>总计确定订单</th>
                                    <th>总计取消订单</th>
                                    <th>总计处理订单</th>
                                 </tr>
                              </thead>
                              <tbody>
<?php
foreach($users as $user) {
?>
                                 <tr>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php if(isset($count_today[$user['id']]) && is_array($count_today[$user['id']]) && isset($count_today[$user['id']]['all']) && intval($count_today[$user['id']]['all']) > 0){echo $count_today[$user['id']]['all'];} else {echo '0';} ?></td>
                                    <td><?php if(isset($count_today[$user['id']]) && is_array($count_today[$user['id']]) && isset($count_today[$user['id']]['1']) && intval($count_today[$user['id']]['1']) > 0){echo $count_today[$user['id']]['1'];} else {echo '0';} ?></td>
                                    <td><?php if(isset($count_today[$user['id']]) && is_array($count_today[$user['id']]) && isset($count_today[$user['id']]['3']) && intval($count_today[$user['id']]['3']) > 0){echo $count_today[$user['id']]['3'];} else {echo '0';} ?></td>
                                    <td><?php if(isset($count_today[$user['id']]) && is_array($count_today[$user['id']]) && isset($count_today[$user['id']]['4']) && intval($count_today[$user['id']]['4']) > 0){echo $count_today[$user['id']]['4'];} else {echo '0';} ?></td>
                                    <td><?php if(isset($count_today[$user['id']]) && is_array($count_today[$user['id']]) && isset($count_today[$user['id']]['7']) && intval($count_today[$user['id']]['7']) > 0){echo $count_today[$user['id']]['7'];} else {echo '0';} ?></td>
                                    <td><?php if(isset($count_today[$user['id']]) && is_array($count_today[$user['id']]) && isset($count_today[$user['id']]['14']) && intval($count_today[$user['id']]['14']) > 0){echo $count_today[$user['id']]['14'];} else {echo '0';} ?></td>
                                    <td><?php if(isset($count_all[$user['id']]) && is_array($count_all[$user['id']]) && isset($count_all[$user['id']]['3']) && intval($count_all[$user['id']]['7']) > 0){echo $count_all[$user['id']]['3'];} else {echo '0';} ?></td>
                                    <td><?php if(isset($count_all[$user['id']]) && is_array($count_all[$user['id']]) && isset($count_all[$user['id']]['4']) && intval($count_all[$user['id']]['4']) > 0){echo $count_all[$user['id']]['4'];} else {echo '0';} ?></td>
                                    <td><?php if(isset($count_all[$user['id']]) && is_array($count_all[$user['id']]) && isset($count_all[$user['id']]['all']) && intval($count_all[$user['id']]['all']) > 0){echo $count_all[$user['id']]['all'];} else {echo '0';} ?></td>
                                 </tr>
<?php
}
?>
                              </tbody>
                           </table>
                        </div>
                        <!-- END table-responsive-->
                     </div>
                  </div>
                  <!-- END panel-->
               </div>
            </div>
<?php
} else {
?>
            <div class="content-heading">订单</div>
            <div class="table-responsive b0">
               <table id="datatable1" class="table table-striped table-hover">
                  <thead>
                     <tr>
                         <th colspan="10">
<?php
if($a !== 'sjb') {
if(isset($history) && intval($history) > 0) {
?>
                            <span style="float: right;"><a class="btn btn-info" href="<?php echo URL;?>?m=orders&a=today&type=all<?php if(isset($id)){echo '&id='.$id;}?>">今日订单</a></span>
                            历史订单：<a class="label label-default" href="<?php echo URL;?>?m=orders&a=history&type=all<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today;?>
                            </a>&nbsp; &nbsp;
<?php
// if(ROLE != 'admin') {
?>
                            待处理：<a class="label label-primary" href="<?php echo URL;?>?m=orders&a=history&type=todo<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today_todo;?>
                            </a>&nbsp; &nbsp;
<?php
// }
?>
                            已确定：<a class="label label-success" href="<?php echo URL;?>?m=orders&a=history&type=done<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today_done;?>
                            </a>&nbsp; &nbsp;
                            已到店：<a class="label label-success" href="<?php echo URL;?>?m=orders&a=history&type=confirmed<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today_confirmed;?>
                            </a>&nbsp; &nbsp;
                            无房间：<a class="label label-danger" href="<?php echo URL;?>?m=orders&a=history&type=rejected<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today_rejected;?>
                            </a>&nbsp; &nbsp;
                            用户取消：<a class="label label-warning" href="<?php echo URL;?>?m=orders&a=history&type=canceled<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today_canceled;?>
                            </a>&nbsp; &nbsp;
                            超时未处理：<a class="label label-info" href="<?php echo URL;?>?m=orders&a=history&type=expired<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today_expired;?>
                            </a>&nbsp; &nbsp;
<?php
} else {
?>
                            <span style="float: right;"><a class="btn btn-info" href="<?php echo URL;?>?m=orders&a=history&type=all<?php if(isset($id)){echo '&id='.$id;}?>">历史订单</a></span>
                            今日订单：<a class="label label-default" href="<?php echo URL;?>?m=orders&a=today&type=all<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today;?>
                            </a>&nbsp; &nbsp;
<?php
// if(ROLE != 'admin') {
?>
                            待处理：<a class="label label-primary" href="<?php echo URL;?>?m=orders&a=today&type=todo<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today_todo;?>
                            </a>&nbsp; &nbsp;
<?php
// }
?>
                            已确定：<a class="label label-success" href="<?php echo URL;?>?m=orders&a=today&type=done<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today_done;?>
                            </a>&nbsp; &nbsp;
                            已到店：<a class="label label-success" href="<?php echo URL;?>?m=orders&a=today&type=confirmed<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today_confirmed;?>
                            </a>&nbsp; &nbsp;
                            无房间：<a class="label label-danger" href="<?php echo URL;?>?m=orders&a=today&type=rejected<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today_rejected;?>
                            </a>&nbsp; &nbsp;
                            用户取消：<a class="label label-warning" href="<?php echo URL;?>?m=orders&a=today&type=canceled<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today_canceled;?>
                            </a>&nbsp; &nbsp;
                            超时未处理：<a class="label label-info" href="<?php echo URL;?>?m=orders&a=today&type=expired<?php if(isset($id)){echo '&id='.$id;}?>">
                                <?php echo $order_today_expired;?>
                            </a>&nbsp; &nbsp;
<?php
}
if(ROLE == 'admin') {
?>
                            <select class="form-control m-b" id="jumptouser">
                                <option value="<?php echo URL;?>?m=orders&a=<?php echo $a;?>&type=<?php echo $type;?>">全部用户</option>
<?php
    foreach($users as $user) {
?>
                                <option <?php if($id == $user['id']){echo 'selected';}?> value="<?php echo URL;?>?m=orders&a=<?php echo $a;?>&type=<?php echo $type;?>&id=<?php echo $user['id'];?>"><?php echo $user['username'];?></option>
<?php
    }
?>
                            </select>
<?php
}
}
?>
                        </th>
                     </tr>
                     <tr>
                        <th>
                           <strong>订单ID</strong>
                        </th>
                        <th>
                           <strong>下单时间</strong>
                        </th>
<?php 
if($a == 'sjb') { ?>
                        <th>
                           <strong>等待时长</strong>
                        </th>
<?php
}
?>
                        <th>
                           <strong>KTV名</strong>
                        </th>
                        <th>
                           <strong>预定人手机</strong>
                        </th>
                        <th>
                           <strong>开始时间</strong>
                        </th>
                        <th>
                           <strong>持续时间</strong>
                        </th>
                        <th>
                           <strong>状态</strong>
                        </th>
<?php
if(ROLE == 'admin') {
?>
                        <th>
                           <strong>操作员</strong>
                        </th>
                        <th>
                           <strong>处理时间</strong>
                        </th>
<?php
}
?>
                        <th>
                           <strong>操作</strong>
                        </th>
                     </tr>
                  </thead>
                  <tbody>
<?php
if(!empty($orders)) {
    foreach($orders as $order) {
//         if($order['status'] !== 7) {
?>
                     <tr>
                        <td><?php echo $order['id']?></td>
                        <td><?php echo $order['time']?></td>
<?php 
if($a == 'sjb') { ?>
                        <td><?php echo intval((TIME - strtotime($order['time'])) / 60).'分钟'?></td>
<?php
}
?>
                        <td><?php echo $order['name']?></td>
                        <td><?php echo $order['mobile']?></td>
                        <td><?php echo $order['starttime']?></td>
                        <td><?php echo $order['lasts']?> 小时</td>
                        <td>
                           <span class="<?php if(array(array_key_exists($order['status'], $orderstatusstyle))) {echo $orderstatusstyle[$order['status']];}?>">
                            <?php if(array(array_key_exists($order['status'], $orderstatus))) {echo $orderstatus[$order['status']];}?>
                           </span>
                        </td>
<?php
if(ROLE == 'admin') {
?>
                        <td><?php if(isset($users_by_id[$order['cc_user']])) {echo $users_by_id[$order['cc_user']];} else {echo '-';}?></td>
                        <td><?php if($order['update_time'] !== $order['time']) {echo $order['update_time'];}?></td>
<?php
}
?>
                        <td class="text-center">
                           <button type="button" <?php if($a !== 'sjb') {echo 'class="btn btn-xs btn-default"';} else {$_bnt = $order['cc_detail'] ? 'btn-success' : 'btn-danger';echo 'class="btn btn-xs '.$_bnt.' fa-pencil-square-ooo" data-toggle="modal" data-target="#myModal"';}?>>
<?php 
if($a !== 'sjb') { ?>
                              <a class="fa <?php if($a !== 'sjb') {echo 'fa-search';} else {echo 'fa-pencil-square-o';}?>" href="<?php echo URL.'?m=order&id='.$order['id']?>"></a>
<?php 
} else {
?>
                              <?php if($order['cc_detail']){echo '已';} else {echo '待';} ?>通知
<?php
}
?>
                           </button>
                        </td>
                     </tr>
<?php
//         }
    }
} else {
?>
<?php }?>
                  </tbody>
               </table>
            </div>
<?php
}
?>
         </div>
      </section>
<?php
if(ROLE == 'operator' && $a == 'today' && $type == 'todo') {
?>
      <span id="operator_ajax" style="display: none;"></span>
<?php
}
?>
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
<!--    <script src="public/app/js/demo/demo-datatable.js"></script> -->
   <!-- =============== APP SCRIPTS ===============-->
   <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                  <span aria-hidden="true">&times;</span>
               </button>
               <h4 id="myModalLabel" class="modal-title">备注</h4>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <div class="col-sm-6">
                     <strong>用户手机号码</strong><input type="text" id="d_mobile" class="form-control input-sm" />
                  </div>
                  <div class="col-sm-6">
                     <strong>KTV电话号码</strong><input type="text" id="d_phone" class="form-control input-sm" />
                  </div>
               </div>
               <div class="form-group">
                  <div class="col-sm-6">
                     <strong>开始时间</strong><input type="text" id="d_starttime" class="form-control input-sm" />
                  </div>
                  <div class="col-sm-6">
                     <strong>结束时间</strong><input type="text" id="d_endtime" class="form-control input-sm" />
                  </div>
               </div>
               <div class="form-group">
                   <div class="col-sm-6">
                   <strong>备注</strong>
                   </div>
                <input type="hidden" id="order_id" value="0" />
                <textarea id="detail" class="form-control editor" style="margin:0 auto;width:95%;height:100%;" placeholder="请输入备注..."></textarea>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" data-dismiss="modal" class="btn btn-default">关闭</button>
               <button type="button" id="detail_submit" class="btn btn-primary">保存</button>
            </div>
         </div>
      </div>
   </div>
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