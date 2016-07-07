<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <title>管理中心</title>
   <link rel="stylesheet" href="public/vendor/fontawesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="public/vendor/simple-line-icons/css/simple-line-icons.css">
   <link rel="stylesheet" href="public/vendor/animate.css/animate.min.css">
   <link rel="stylesheet" href="public/vendor/whirl/dist/whirl.css">
   <link rel="stylesheet" href="public/vendor/datatables-colvis/css/dataTables.colVis.css">
   <link rel="stylesheet" href="public/app/vendor/datatable-bootstrap/css/dataTables.bootstrap.css">
   <link rel="stylesheet" href="public/app/css/bootstrap.css" id="bscss">
   <link rel="stylesheet" href="public/app/css/app.css" id="maincss">
   <link id="autoloaded-stylesheet" rel="stylesheet" href="public/app/css/theme-h.css">
</head>

<body>
   <div class="wrapper">
      <header class="topnavbar-wrapper">
         <nav role="navigation" class="navbar topnavbar">
            <div class="navbar-header">
               <a href="#/" class="navbar-brand">
                  <div class="brand-logo">
                     <img src="public/app/img/page_logo.png" alt="" class="img-responsive">
                  </div>
               </a>
            </div>
            <div class="nav-wrapper">
               <span style="display: block;float: left;line-height: 55px;font-size: 16px;color: #fff;">SPR信息管理系统</span>
               <ul class="nav navbar-nav navbar-right">
                  <li>
                     <a href="index.php?m=logout" data-toggle-state="offsidebar-open" data-no-persist="true">
                        <em class="icon-logout"></em>
                     </a>
                  </li>
               </ul>
            </div>
         </nav>
      </header>
      <aside class="aside">
         <div class="aside-inner">
            <nav data-sidebar-anyclick-close="" class="sidebar">
               <ul class="nav">
                  <li class=" ">
                     <a href="index.php" title="Layouts" data-toggle="">
                        <em class="icon-layers"></em>
                        <span>SPR</span>
                     </a>
                  </li>
               </ul>
            </nav>
         </div>
      </aside>
      <section>
         <div class="content-wrapper">
            <div class="table-responsive b0">
               <table id="datatable1" class="table table-striped table-hover">
                  <thead>
                     <tr>
                         <th colspan="6">
                        </th>
                     </tr>
                     <tr>
                        <th>
                           <strong>姓名</strong>
                        </th>
                        <th>
                           <strong>手机号码</strong>
                        </th>
                        <th>
                           <strong>绑定状态</strong>
                        </th>
                        <th>
                           <strong>角色</strong>
                        </th>
                        <th>
                           <strong>用户状态</strong>
                        </th>
                        <th>
                           <strong>操作</strong>
                        </th>
                     </tr>
                  </thead>
                  <tbody>
<?php
if(is_array($sprs) && !empty($sprs)) {
    foreach($sprs as $spr) {
?>
                     <tr>
                        <td><?php echo $spr['name'];?></td>
                        <td><?php echo $spr['phone'];?></td>
                        <td><button type="button" class="mb-xs btn btn-<?php echo $C['d_status_style'][$spr['d_status']];?>"><?php echo $C['d_status'][$spr['d_status']];?></button></td>
                        <td><?php echo $C['role'][$spr['role']];?></td>
                        <td><button type="button" class="m_style mb-xs btn btn-<?php if(isset($spr['status'])) {echo $C['status_style'][(string) $spr['status']];} else {echo $C['status_style']['-1'];}?>"><?php if(isset($spr['status'])) {echo $C['status'][(string) $spr['status']];} else {echo '未绑定';}?></button></td>
                        <td class="text-center">
                           <button type="button" class="btn btn-xs btn-default">
                              <a class="m_ajax" href="javascript:void(0);" data-status="<?php echo $spr['status'];?>" data-url="index.php?ajax&m=spr&id=<?php echo $spr['id'];?>&status="><?php if($spr['status'] == '1') {echo '禁用';}elseif($spr['status'] =='0') {echo '启用';} ?></a>
                           </button>
                        </td>
                     </tr>
<?php
    }
}
?>
                  </tbody>
               </table>
            </div>
         </div>
      </section>
   </div>
   <script src="public/vendor/modernizr/modernizr.js"></script>
   <script src="public/vendor/jquery/dist/jquery.js"></script>
   <script src="public/vendor/bootstrap/dist/js/bootstrap.js"></script>
   <script src="public/vendor/jQuery-Storage-API/jquery.storageapi.js"></script>
   <script src="public/vendor/jquery.easing/js/jquery.easing.js"></script>
   <script src="public/vendor/animo.js/animo.js"></script>
   <script src="public/vendor/slimScroll/jquery.slimscroll.min.js"></script>
   <script src="public/vendor/screenfull/dist/screenfull.js"></script>
   <script src="public/vendor/jquery-localize-i18n/dist/jquery.localize.js"></script>
   <script src="public/app/js/demo/demo-rtl.js"></script>
   <script src="public/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
   <script src="public/vendor/datatables-colvis/js/dataTables.colVis.js"></script>
   <script src="public/app/vendor/datatable-bootstrap/js/dataTables.bootstrap.js"></script>
   <script src="public/app/vendor/datatable-bootstrap/js/dataTables.bootstrapPagination.js"></script>
   <script src="public/app.js"></script>
</body>

</html>