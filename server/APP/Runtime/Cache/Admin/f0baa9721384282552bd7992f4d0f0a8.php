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
                <div class="content-heading"><?php echo ($xktvinfo["name"]); ?></div>
                <form class="panel" action="<?php echo U(update);?>" method="post">
                    <input type="hidden" name="id" value="<?php echo ($xktvinfo["id"]); ?>">
                    <div role="tabpanel">
                        <ul class="nav nav-tabs nav-justified">
                            <li role="presentation" class="active"><a href="#edit" aria-controls="edit" role="tab" data-toggle="tab">KTV基本信息</a>
                            </li>
                            <li role="presentation"><a href="#seo" aria-controls="seo" role="tab" data-toggle="tab">商务版信息</a>
                            </li>
                            <li role="presentation"><a href="#picture" aria-controls="picture" role="tab" data-toggle="tab">KTV详细信息</a>
                            </li>
                            <!-- <li role="presentation"><a href="#pic_mange" aria-controls="pic_mange" role="tab" data-toggle="tab">图片管理</a>
                            </li> -->
                            <li role="presentation"><a href="#taocan" aria-controls="picture" role="tab" data-toggle="tab">KTV套餐信息</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="edit" role="tabpanel" class="tab-pane active">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">名称:</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="XKTV名称" class="form-control" value="<?php echo ($xktvinfo["name"]); ?>" name="name">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">描述:</label>
                                        <div class="col-md-10">
                                            <textarea cols="5" placeholder="描述" class="form-control" name="description"><?php echo ($xktvinfo["description"]); ?></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">类型:</label>
                                        <div class="col-sm-10">
                                            <span class="mb-sm btn btn-primary"><?php if($xktvinfo["type"] == 0): ?>CallCenter<?php elseif($xktvinfo["type"] == 2): ?>商户版<?php endif; ?></span>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">状态:</label>
                                        <div class="col-sm-10">
                                            <span class="mb-sm btn btn-primary"><?php if($xktvinfo["status"] == 1): ?>正常<?php elseif($xktvinfo["status"] == 0): ?>禁用<?php endif; ?></span>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">综合评分:</label>
                                        <div class="col-sm-10">
                                            <span class="mb-sm btn btn-primary"><?php echo ($xktvinfo["totalrate"]); ?></span>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div id="seo" role="tabpanel" class="tab-pane">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">经理姓名</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="经理姓名" class="form-control" value="<?php echo ($shangjia["name"]); ?>" name="managername">
                                            <?php if($bind == 1): ?><a href="#" class="btn btn-primary btn-sm">已经绑定</a>
                                                <?php elseif($bind == 0): ?>
                                                <a href="#" class="btn btn-primary btn-sm">未绑定</a><?php endif; ?>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">经理电话</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="经理电话" class="form-control" value="<?php echo ($shangjia["phone"]); ?>" name="managertelphone">
                                        </div>
                                    </div>
                                </fieldset>
                                <?php if($bind == 1): ?><fieldset>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">重新绑定</label>
                                            <div class="col-md-10">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="rebind">如果需要重新绑定经理手机号，请勾选</label>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset><?php endif; ?>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">服务员列表</label>
                                        <div class="col-md-10">
                                            <ul>
                                                <?php if(is_array($ktvemps)): $i = 0; $__LIST__ = $ktvemps;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>用户名：<?php echo ($vo["name"]); ?> 电话：<?php echo ($vo["phone"]); ?> 状态
                                                        <?php if($vo["status"] == 1): ?>正常
                                                            <?php elseif($vo["status"] == 0): ?>已经离职<?php endif; ?>
                                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div id="picture" role="tabpanel" class="tab-pane">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">地址:</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="XKTV地址" class="form-control" value="<?php echo ($xktvinfo["address"]); ?>" name="address">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">地区:</label>
                                        <div class="col-md-10">
                                            <select class="form-control" name='area_id' value="<?php echo ($xktvinfo["area_id"]); ?>">
                                                <?php if(is_array($areas)): $i = 0; $__LIST__ = $areas;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><option value="<?php echo ($data["code"]); ?>" <?php if($data["code"] == $xktvinfo['area_id']): ?>selected="selected"<?php endif; ?> ><?php echo ($data["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">区号:</label>
                                        <div class="col-md-10">
                                            <select class="form-control" name='pretelephone' value="<?php echo ($xktvinfo["pretelephone"]); ?>">
                                                <option <?php if($xktvinfo["pretelephone"] == '020' ): ?>selected="selected"<?php endif; ?> value='020' >(广州)020</option>
                                                <!-- <option>手机</option> -->
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">电话:</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="XKTV电话" class="form-control" value="<?php echo ($xktvinfo["telephone"]); ?>" name="telephone">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">营业时间:</label>
                                        <div class="col-md-10 form-inline">
                                            <!-- <input type="text" placeholder="XKTV营业时间" class="form-control" value="<?php echo ($xktvinfo["openhours"]); ?>" name="openhours"> -->
                                            <select name="openhours_s" class="form-control">
                                                <option>开始时间</option>
                                                <option value='01:00' <?php if($openhours["openhours_s"] == '01:00' ): ?>selected="selected"<?php endif; ?> >01:00</option>
                                                <option value='01:30' <?php if($openhours["openhours_s"] == '01:30' ): ?>selected="selected"<?php endif; ?> >01:30</option>
                                                <option value='02:00' <?php if($openhours["openhours_s"] == '02:00' ): ?>selected="selected"<?php endif; ?> >02:00</option>
                                                <option value='02:30' <?php if($openhours["openhours_s"] == '02:30' ): ?>selected="selected"<?php endif; ?> >02:30</option>
                                                <option value='03:00' <?php if($openhours["openhours_s"] == '03:00' ): ?>selected="selected"<?php endif; ?> >03:00</option>
                                                <option value='03:30' <?php if($openhours["openhours_s"] == '03:30' ): ?>selected="selected"<?php endif; ?> >03:30</option>
                                                <option value='04:00' <?php if($openhours["openhours_s"] == '04:00' ): ?>selected="selected"<?php endif; ?> >04:00</option>
                                                <option value='04:30' <?php if($openhours["openhours_s"] == '04:30' ): ?>selected="selected"<?php endif; ?> >04:30</option>
                                                <option value='05:00' <?php if($openhours["openhours_s"] == '05:00' ): ?>selected="selected"<?php endif; ?> >05:00</option>
                                                <option value='05:30' <?php if($openhours["openhours_s"] == '05:30' ): ?>selected="selected"<?php endif; ?> >05:30</option>
                                                <option value='06:00' <?php if($openhours["openhours_s"] == '06:00' ): ?>selected="selected"<?php endif; ?> >06:00</option>
                                                <option value='06:30' <?php if($openhours["openhours_s"] == '06:30' ): ?>selected="selected"<?php endif; ?> >06:30</option>
                                                <option value='07:00' <?php if($openhours["openhours_s"] == '07:00' ): ?>selected="selected"<?php endif; ?> >07:00</option>
                                                <option value='07:30' <?php if($openhours["openhours_s"] == '07:30' ): ?>selected="selected"<?php endif; ?> >07:30</option>
                                                <option value='08:00' <?php if($openhours["openhours_s"] == '08:00' ): ?>selected="selected"<?php endif; ?> >08:00</option>
                                                <option value='08:30' <?php if($openhours["openhours_s"] == '08:30' ): ?>selected="selected"<?php endif; ?> >08:30</option>
                                                <option value='09:00' <?php if($openhours["openhours_s"] == '09:00' ): ?>selected="selected"<?php endif; ?> >09:00</option>
                                                <option value='09:30' <?php if($openhours["openhours_s"] == '09:30' ): ?>selected="selected"<?php endif; ?> >09:30</option>
                                                <option value='10:00' <?php if($openhours["openhours_s"] == '10:00' ): ?>selected="selected"<?php endif; ?> >10:00</option>
                                                <option value='10:30' <?php if($openhours["openhours_s"] == '10:30' ): ?>selected="selected"<?php endif; ?> >10:30</option>
                                                <option value='11:00' <?php if($openhours["openhours_s"] == '11:00' ): ?>selected="selected"<?php endif; ?> >11:00</option>
                                                <option value='11:30' <?php if($openhours["openhours_s"] == '11:30' ): ?>selected="selected"<?php endif; ?> >11:30</option>
                                                <option value='12:00' <?php if($openhours["openhours_s"] == '12:00' ): ?>selected="selected"<?php endif; ?> >12:00</option>
                                                <option value='12:30' <?php if($openhours["openhours_s"] == '12:30' ): ?>selected="selected"<?php endif; ?> >12:30</option>
                                                <option value='13:00' <?php if($openhours["openhours_s"] == '13:00' ): ?>selected="selected"<?php endif; ?> >13:00</option>
                                                <option value='13:30' <?php if($openhours["openhours_s"] == '13:30' ): ?>selected="selected"<?php endif; ?> >13:30</option>
                                                <option value='14:00' <?php if($openhours["openhours_s"] == '14:00' ): ?>selected="selected"<?php endif; ?> >14:00</option>
                                                <option value='14:30' <?php if($openhours["openhours_s"] == '14:30' ): ?>selected="selected"<?php endif; ?> >14:30</option>
                                                <option value='15:00' <?php if($openhours["openhours_s"] == '15:00' ): ?>selected="selected"<?php endif; ?> >15:00</option>
                                                <option value='15:30' <?php if($openhours["openhours_s"] == '15:30' ): ?>selected="selected"<?php endif; ?> >15:30</option>
                                                <option value='16:00' <?php if($openhours["openhours_s"] == '16:00' ): ?>selected="selected"<?php endif; ?> >16:00</option>
                                                <option value='16:30' <?php if($openhours["openhours_s"] == '16:30' ): ?>selected="selected"<?php endif; ?> >16:30</option>
                                                <option value='17:00' <?php if($openhours["openhours_s"] == '17:00' ): ?>selected="selected"<?php endif; ?> >17:00</option>
                                                <option value='17:30' <?php if($openhours["openhours_s"] == '17:30' ): ?>selected="selected"<?php endif; ?> >17:30</option>
                                                <option value='18:00' <?php if($openhours["openhours_s"] == '18:00' ): ?>selected="selected"<?php endif; ?> >18:00</option>
                                                <option value='18:30' <?php if($openhours["openhours_s"] == '18:30' ): ?>selected="selected"<?php endif; ?> >18:30</option>
                                                <option value='19:00' <?php if($openhours["openhours_s"] == '19:00' ): ?>selected="selected"<?php endif; ?> >19:00</option>
                                                <option value='19:30' <?php if($openhours["openhours_s"] == '19:30' ): ?>selected="selected"<?php endif; ?> >19:30</option>
                                                <option value='20:00' <?php if($openhours["openhours_s"] == '20:00' ): ?>selected="selected"<?php endif; ?> >20:00</option>
                                                <option value='20:30' <?php if($openhours["openhours_s"] == '20:30' ): ?>selected="selected"<?php endif; ?> >20:30</option>
                                                <option value='21:00' <?php if($openhours["openhours_s"] == '21:00' ): ?>selected="selected"<?php endif; ?> >21:00</option>
                                                <option value='21:30' <?php if($openhours["openhours_s"] == '21:30' ): ?>selected="selected"<?php endif; ?> >21:30</option>
                                                <option value='22:00' <?php if($openhours["openhours_s"] == '22:00' ): ?>selected="selected"<?php endif; ?> >22:00</option>
                                                <option value='22:30' <?php if($openhours["openhours_s"] == '22:30' ): ?>selected="selected"<?php endif; ?> >22:30</option>
                                                <option value='23:00' <?php if($openhours["openhours_s"] == '23:00' ): ?>selected="selected"<?php endif; ?> >23:00</option>
                                                <option value='23:30' <?php if($openhours["openhours_s"] == '23:30' ): ?>selected="selected"<?php endif; ?> >23:30</option>
                                                <option value='00:00' <?php if($openhours["openhours_s"] == '00:00' ): ?>selected="selected"<?php endif; ?> >00:00</option>
                                                <option value='00:30' <?php if($openhours["openhours_s"] == '00:30' ): ?>selected="selected"<?php endif; ?> >00:30</option>
                                            </select>
                                            <span>--</span>
                                            <select name="openhours_e" class="form-control">
                                                <option>结束时间</option>
                                                <option value='01:00' <?php if($openhours["openhours_e"] == '01:00' ): ?>selected="selected"<?php endif; ?> >01:00</option>
                                                <option value='01:30' <?php if($openhours["openhours_e"] == '01:30' ): ?>selected="selected"<?php endif; ?> >01:30</option>
                                                <option value='02:00' <?php if($openhours["openhours_e"] == '02:00' ): ?>selected="selected"<?php endif; ?> >02:00</option>
                                                <option value='02:30' <?php if($openhours["openhours_e"] == '02:30' ): ?>selected="selected"<?php endif; ?> >02:30</option>
                                                <option value='03:00' <?php if($openhours["openhours_e"] == '03:00' ): ?>selected="selected"<?php endif; ?> >03:00</option>
                                                <option value='03:30' <?php if($openhours["openhours_e"] == '03:30' ): ?>selected="selected"<?php endif; ?> >03:30</option>
                                                <option value='04:00' <?php if($openhours["openhours_e"] == '04:00' ): ?>selected="selected"<?php endif; ?> >04:00</option>
                                                <option value='04:30' <?php if($openhours["openhours_e"] == '04:30' ): ?>selected="selected"<?php endif; ?> >04:30</option>
                                                <option value='05:00' <?php if($openhours["openhours_e"] == '05:00' ): ?>selected="selected"<?php endif; ?> >05:00</option>
                                                <option value='05:30' <?php if($openhours["openhours_e"] == '05:30' ): ?>selected="selected"<?php endif; ?> >05:30</option>
                                                <option value='06:00' <?php if($openhours["openhours_e"] == '06:00' ): ?>selected="selected"<?php endif; ?> >06:00</option>
                                                <option value='06:30' <?php if($openhours["openhours_e"] == '06:30' ): ?>selected="selected"<?php endif; ?> >06:30</option>
                                                <option value='07:00' <?php if($openhours["openhours_e"] == '07:00' ): ?>selected="selected"<?php endif; ?> >07:00</option>
                                                <option value='07:30' <?php if($openhours["openhours_e"] == '07:30' ): ?>selected="selected"<?php endif; ?> >07:30</option>
                                                <option value='08:00' <?php if($openhours["openhours_e"] == '08:00' ): ?>selected="selected"<?php endif; ?> >08:00</option>
                                                <option value='08:30' <?php if($openhours["openhours_e"] == '08:30' ): ?>selected="selected"<?php endif; ?> >08:30</option>
                                                <option value='09:00' <?php if($openhours["openhours_e"] == '09:00' ): ?>selected="selected"<?php endif; ?> >09:00</option>
                                                <option value='09:30' <?php if($openhours["openhours_e"] == '09:30' ): ?>selected="selected"<?php endif; ?> >09:30</option>
                                                <option value='10:00' <?php if($openhours["openhours_e"] == '10:00' ): ?>selected="selected"<?php endif; ?> >10:00</option>
                                                <option value='10:30' <?php if($openhours["openhours_e"] == '10:30' ): ?>selected="selected"<?php endif; ?> >10:30</option>
                                                <option value='11:00' <?php if($openhours["openhours_e"] == '11:00' ): ?>selected="selected"<?php endif; ?> >11:00</option>
                                                <option value='11:30' <?php if($openhours["openhours_e"] == '11:30' ): ?>selected="selected"<?php endif; ?> >11:30</option>
                                                <option value='12:00' <?php if($openhours["openhours_e"] == '12:00' ): ?>selected="selected"<?php endif; ?> >12:00</option>
                                                <option value='12:30' <?php if($openhours["openhours_e"] == '12:30' ): ?>selected="selected"<?php endif; ?> >12:30</option>
                                                <option value='13:00' <?php if($openhours["openhours_e"] == '13:00' ): ?>selected="selected"<?php endif; ?> >13:00</option>
                                                <option value='13:30' <?php if($openhours["openhours_e"] == '13:30' ): ?>selected="selected"<?php endif; ?> >13:30</option>
                                                <option value='14:00' <?php if($openhours["openhours_e"] == '14:00' ): ?>selected="selected"<?php endif; ?> >14:00</option>
                                                <option value='14:30' <?php if($openhours["openhours_e"] == '14:30' ): ?>selected="selected"<?php endif; ?> >14:30</option>
                                                <option value='15:00' <?php if($openhours["openhours_e"] == '15:00' ): ?>selected="selected"<?php endif; ?> >15:00</option>
                                                <option value='15:30' <?php if($openhours["openhours_e"] == '15:30' ): ?>selected="selected"<?php endif; ?> >15:30</option>
                                                <option value='16:00' <?php if($openhours["openhours_e"] == '16:00' ): ?>selected="selected"<?php endif; ?> >16:00</option>
                                                <option value='16:30' <?php if($openhours["openhours_e"] == '16:30' ): ?>selected="selected"<?php endif; ?> >16:30</option>
                                                <option value='17:00' <?php if($openhours["openhours_e"] == '17:00' ): ?>selected="selected"<?php endif; ?> >17:00</option>
                                                <option value='17:30' <?php if($openhours["openhours_e"] == '17:30' ): ?>selected="selected"<?php endif; ?> >17:30</option>
                                                <option value='18:00' <?php if($openhours["openhours_e"] == '18:00' ): ?>selected="selected"<?php endif; ?> >18:00</option>
                                                <option value='18:30' <?php if($openhours["openhours_e"] == '18:30' ): ?>selected="selected"<?php endif; ?> >18:30</option>
                                                <option value='19:00' <?php if($openhours["openhours_e"] == '19:00' ): ?>selected="selected"<?php endif; ?> >19:00</option>
                                                <option value='19:30' <?php if($openhours["openhours_e"] == '19:30' ): ?>selected="selected"<?php endif; ?> >19:30</option>
                                                <option value='20:00' <?php if($openhours["openhours_e"] == '20:00' ): ?>selected="selected"<?php endif; ?> >20:00</option>
                                                <option value='20:30' <?php if($openhours["openhours_e"] == '20:30' ): ?>selected="selected"<?php endif; ?> >20:30</option>
                                                <option value='21:00' <?php if($openhours["openhours_e"] == '21:00' ): ?>selected="selected"<?php endif; ?> >21:00</option>
                                                <option value='21:30' <?php if($openhours["openhours_e"] == '21:30' ): ?>selected="selected"<?php endif; ?> >21:30</option>
                                                <option value='22:00' <?php if($openhours["openhours_e"] == '22:00' ): ?>selected="selected"<?php endif; ?> >22:00</option>
                                                <option value='22:30' <?php if($openhours["openhours_e"] == '22:30' ): ?>selected="selected"<?php endif; ?> >22:30</option>
                                                <option value='23:00' <?php if($openhours["openhours_e"] == '23:00' ): ?>selected="selected"<?php endif; ?> >23:00</option>
                                                <option value='23:30' <?php if($openhours["openhours_e"] == '23:30' ): ?>selected="selected"<?php endif; ?> >23:30</option>
                                                <option value='00:00' <?php if($openhours["openhours_e"] == '00:00' ): ?>selected="selected"<?php endif; ?> >00:00</option>
                                                <option value='00:30' <?php if($openhours["openhours_e"] == '00:30' ): ?>selected="selected"<?php endif; ?> >00:30</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">经度:</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="XKTV经度" class="form-control" value="<?php echo ($xktvinfo["lat"]); ?>" name="lat">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">纬度:</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="XKTV纬度" class="form-control" value="<?php echo ($xktvinfo["lng"]); ?>" name="lng">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <label class="col-md-2 control-label">选点:</label>
                                    <div class="col-md-10">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" id="latlng"></div>
                                            <div class="panel-body">
                                                <div id="qqmap"></div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">房间数:</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="房间数" class="form-control" value="<?php echo ($xktvinfo["roomtotal"]); ?>" name="roomtotal">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">环境评分:</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="环境评分" class="form-control" value="<?php echo ($xktvinfo["decorationrating"]); ?>" name="decorationrating">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">音效评分:</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="音效评分" class="form-control" value="<?php echo ($xktvinfo["soundrating"]); ?>" name="soundrating">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">服务评分:</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="服务评分" class="form-control" value="<?php echo ($xktvinfo["servicerating"]); ?>" name="servicerating">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">美食评分:</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="美食评分" class="form-control" value="<?php echo ($xktvinfo["foodrating"]); ?>" name="foodrating">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">平均等待时间:</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="平均等待时间" class="form-control" value="<?php echo ($xktvinfo["responsetime"]); ?>" name="responsetime">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">设备:</label>
                                        <div class="col-md-10">
                                            <label class="checkbox-inline c-checkbox">
                                                <input type="hidden" name="WIFI" value="0">
                                                <input type="checkbox" name="WIFI" id="WIFI" value="1" <?php if($xktvinfo["wifi"] == 1): ?>checked<?php endif; ?> >
                                                <span class="fa fa-check"></span>免费WIFI</label>
                                            <label class="checkbox-inline c-checkbox">
                                                <input type="hidden" name="parking" value="0">
                                                <input type="checkbox" name="parking" id="parking" value="1" <?php if($xktvinfo["parking"] == 1): ?>checked<?php endif; ?> >
                                                <span class="fa fa-check"></span>停车场</label>
                                            <label class="checkbox-inline c-checkbox">
                                                <input type="hidden" name="Themerooms" value="0">
                                                <input type="checkbox" name="Themerooms" id="Themerooms" value="1" <?php if($xktvinfo["themerooms"] == 1): ?>checked<?php endif; ?> >
                                                <span class="fa fa-check"></span>主题包房</label>
                                            <label class="checkbox-inline c-checkbox">
                                                <input type="hidden" name="WirelessMicrophones" value="0">
                                                <input type="checkbox" name="WirelessMicrophones" id="WirelessMicrophones" value="1" <?php if($xktvinfo["wirelessmicrophones"] == 1): ?>checked<?php endif; ?> >
                                                <span class="fa fa-check"></span>无线麦克风</label>
                                            <label class="checkbox-inline c-checkbox">
                                                <input type="hidden" name="XKTV" value="0">
                                                <input type="checkbox" name="XKTV" id="XKTV" value="1" <?php if($xktvinfo["xktv"] == 1): ?>checked<?php endif; ?> >
                                                <span class="fa fa-check"></span>X-Table</label>
                                            <label class="checkbox-inline c-checkbox">
                                                <input type="hidden" name="YeDianPad" value="0">
                                                <input type="checkbox" name="YeDianPad" id="YeDianPad" value="1" <?php if($xktvinfo["yedianpad"] == 1): ?>checked<?php endif; ?> >
                                                <span class="fa fa-check"></span>接入收银台</label>
                                            <label class="checkbox-inline c-checkbox">
                                                <input type="hidden" name="buffet" value="0">
                                                <input type="checkbox" name="buffet" id="buffet" value="1" <?php if($xktvinfo["buffet"] == 1): ?>checked<?php endif; ?> >
                                                <span class="fa fa-check"></span>自助餐</label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">增值服务:</label>
                                        <div class="col-md-10">
                                            <label class="checkbox-inline c-checkbox">
                                                <input type="hidden" name="zqss" value="0">
                                                <input type="checkbox" name="zqss" id="zqss" value="1" <?php if($xktvinfo["zqss"] == 1): ?>checked<?php endif; ?> >
                                                <span class="fa fa-check"></span>足球赛事</label>
                                            <label class="checkbox-inline c-checkbox">
                                                <input type="hidden" name="zxgy" value="0">
                                                <input type="checkbox" name="zxgy" id="zxgy" value="1" <?php if($xktvinfo["zxgy"] == 1): ?>checked<?php endif; ?> >
                                                <span class="fa fa-check"></span>在线观影</label>
                                            <label class="checkbox-inline c-checkbox">
                                                <input type="hidden" name="tm" value="0">
                                                <input type="checkbox" name="tm" id="tm" value="1" <?php if($xktvinfo["tm"] == 1): ?>checked<?php endif; ?> >
                                                <span class="fa fa-check"></span>弹幕</label>
                                            <label class="checkbox-inline c-checkbox">
                                                <input type="hidden" name="sjdg" value="0">
                                                <input type="checkbox" name="sjdg" id="sjdg" value="1" <?php if($xktvinfo["sjdg"] == 1): ?>checked<?php endif; ?> >
                                                <span class="fa fa-check"></span>手机点歌</label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">活动:</label>
                                        <div class="col-md-10">
                                            <select class="form-control" name='sjq' value="<?php echo ($xktvinfo["sjq"]); ?>">
                                                <option value="0">未参加送酒</option>
                                                <?php if(is_array($typeids)): $i = 0; $__LIST__ = $typeids;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><option value="<?php echo ($data["id"]); ?>" <?php if($data["id"] == $xktvinfo['sjq']): ?>selected="selected"<?php endif; ?> ><?php echo ($data["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">最后接单时间:</label>
                                        <div class="col-md-10">
                                            <select name="lasttimeofjiedan" class="form-control">
                                                <option>最后接单时间</option>
                                                <option value='01:00' <?php if($xktvinfo["lasttimeofjiedan"] == '01:00' ): ?>selected="selected"<?php endif; ?> >01:00</option>
                                                <option value='01:30' <?php if($xktvinfo["lasttimeofjiedan"] == '01:30' ): ?>selected="selected"<?php endif; ?> >01:30</option>
                                                <option value='02:00' <?php if($xktvinfo["lasttimeofjiedan"] == '02:00' ): ?>selected="selected"<?php endif; ?> >02:00</option>
                                                <option value='02:30' <?php if($xktvinfo["lasttimeofjiedan"] == '02:30' ): ?>selected="selected"<?php endif; ?> >02:30</option>
                                                <option value='03:00' <?php if($xktvinfo["lasttimeofjiedan"] == '03:00' ): ?>selected="selected"<?php endif; ?> >03:00</option>
                                                <option value='03:30' <?php if($xktvinfo["lasttimeofjiedan"] == '03:30' ): ?>selected="selected"<?php endif; ?> >03:30</option>
                                                <option value='04:00' <?php if($xktvinfo["lasttimeofjiedan"] == '04:00' ): ?>selected="selected"<?php endif; ?> >04:00</option>
                                                <option value='04:30' <?php if($xktvinfo["lasttimeofjiedan"] == '04:30' ): ?>selected="selected"<?php endif; ?> >04:30</option>
                                                <option value='05:00' <?php if($xktvinfo["lasttimeofjiedan"] == '05:00' ): ?>selected="selected"<?php endif; ?> >05:00</option>
                                                <option value='05:30' <?php if($xktvinfo["lasttimeofjiedan"] == '05:30' ): ?>selected="selected"<?php endif; ?> >05:30</option>
                                                <option value='06:00' <?php if($xktvinfo["lasttimeofjiedan"] == '06:00' ): ?>selected="selected"<?php endif; ?> >06:00</option>
                                                <option value='06:30' <?php if($xktvinfo["lasttimeofjiedan"] == '06:30' ): ?>selected="selected"<?php endif; ?> >06:30</option>
                                                <option value='07:00' <?php if($xktvinfo["lasttimeofjiedan"] == '07:00' ): ?>selected="selected"<?php endif; ?> >07:00</option>
                                                <option value='07:30' <?php if($xktvinfo["lasttimeofjiedan"] == '07:30' ): ?>selected="selected"<?php endif; ?> >07:30</option>
                                                <option value='08:00' <?php if($xktvinfo["lasttimeofjiedan"] == '08:00' ): ?>selected="selected"<?php endif; ?> >08:00</option>
                                                <option value='08:30' <?php if($xktvinfo["lasttimeofjiedan"] == '08:30' ): ?>selected="selected"<?php endif; ?> >08:30</option>
                                                <option value='09:00' <?php if($xktvinfo["lasttimeofjiedan"] == '09:00' ): ?>selected="selected"<?php endif; ?> >09:00</option>
                                                <option value='09:30' <?php if($xktvinfo["lasttimeofjiedan"] == '09:30' ): ?>selected="selected"<?php endif; ?> >09:30</option>
                                                <option value='10:00' <?php if($xktvinfo["lasttimeofjiedan"] == '10:00' ): ?>selected="selected"<?php endif; ?> >10:00</option>
                                                <option value='10:30' <?php if($xktvinfo["lasttimeofjiedan"] == '10:30' ): ?>selected="selected"<?php endif; ?> >10:30</option>
                                                <option value='11:00' <?php if($xktvinfo["lasttimeofjiedan"] == '11:00' ): ?>selected="selected"<?php endif; ?> >11:00</option>
                                                <option value='11:30' <?php if($xktvinfo["lasttimeofjiedan"] == '11:30' ): ?>selected="selected"<?php endif; ?> >11:30</option>
                                                <option value='12:00' <?php if($xktvinfo["lasttimeofjiedan"] == '12:00' ): ?>selected="selected"<?php endif; ?> >12:00</option>
                                                <option value='12:30' <?php if($xktvinfo["lasttimeofjiedan"] == '12:30' ): ?>selected="selected"<?php endif; ?> >12:30</option>
                                                <option value='13:00' <?php if($xktvinfo["lasttimeofjiedan"] == '13:00' ): ?>selected="selected"<?php endif; ?> >13:00</option>
                                                <option value='13:30' <?php if($xktvinfo["lasttimeofjiedan"] == '13:30' ): ?>selected="selected"<?php endif; ?> >13:30</option>
                                                <option value='14:00' <?php if($xktvinfo["lasttimeofjiedan"] == '14:00' ): ?>selected="selected"<?php endif; ?> >14:00</option>
                                                <option value='14:30' <?php if($xktvinfo["lasttimeofjiedan"] == '14:30' ): ?>selected="selected"<?php endif; ?> >14:30</option>
                                                <option value='15:00' <?php if($xktvinfo["lasttimeofjiedan"] == '15:00' ): ?>selected="selected"<?php endif; ?> >15:00</option>
                                                <option value='15:30' <?php if($xktvinfo["lasttimeofjiedan"] == '15:30' ): ?>selected="selected"<?php endif; ?> >15:30</option>
                                                <option value='16:00' <?php if($xktvinfo["lasttimeofjiedan"] == '16:00' ): ?>selected="selected"<?php endif; ?> >16:00</option>
                                                <option value='16:30' <?php if($xktvinfo["lasttimeofjiedan"] == '16:30' ): ?>selected="selected"<?php endif; ?> >16:30</option>
                                                <option value='17:00' <?php if($xktvinfo["lasttimeofjiedan"] == '17:00' ): ?>selected="selected"<?php endif; ?> >17:00</option>
                                                <option value='17:30' <?php if($xktvinfo["lasttimeofjiedan"] == '17:30' ): ?>selected="selected"<?php endif; ?> >17:30</option>
                                                <option value='18:00' <?php if($xktvinfo["lasttimeofjiedan"] == '18:00' ): ?>selected="selected"<?php endif; ?> >18:00</option>
                                                <option value='18:30' <?php if($xktvinfo["lasttimeofjiedan"] == '18:30' ): ?>selected="selected"<?php endif; ?> >18:30</option>
                                                <option value='19:00' <?php if($xktvinfo["lasttimeofjiedan"] == '19:00' ): ?>selected="selected"<?php endif; ?> >19:00</option>
                                                <option value='19:30' <?php if($xktvinfo["lasttimeofjiedan"] == '19:30' ): ?>selected="selected"<?php endif; ?> >19:30</option>
                                                <option value='20:00' <?php if($xktvinfo["lasttimeofjiedan"] == '20:00' ): ?>selected="selected"<?php endif; ?> >20:00</option>
                                                <option value='20:30' <?php if($xktvinfo["lasttimeofjiedan"] == '20:30' ): ?>selected="selected"<?php endif; ?> >20:30</option>
                                                <option value='21:00' <?php if($xktvinfo["lasttimeofjiedan"] == '21:00' ): ?>selected="selected"<?php endif; ?> >21:00</option>
                                                <option value='21:30' <?php if($xktvinfo["lasttimeofjiedan"] == '21:30' ): ?>selected="selected"<?php endif; ?> >21:30</option>
                                                <option value='22:00' <?php if($xktvinfo["lasttimeofjiedan"] == '22:00' ): ?>selected="selected"<?php endif; ?> >22:00</option>
                                                <option value='22:30' <?php if($xktvinfo["lasttimeofjiedan"] == '22:30' ): ?>selected="selected"<?php endif; ?> >22:30</option>
                                                <option value='23:00' <?php if($xktvinfo["lasttimeofjiedan"] == '23:00' ): ?>selected="selected"<?php endif; ?> >23:00</option>
                                                <option value='23:30' <?php if($xktvinfo["lasttimeofjiedan"] == '23:30' ): ?>selected="selected"<?php endif; ?> >23:30</option>
                                                <option value='00:00' <?php if($xktvinfo["lasttimeofjiedan"] == '00:00' ): ?>selected="selected"<?php endif; ?> >00:00</option>
                                                <option value='00:30' <?php if($xktvinfo["lasttimeofjiedan"] == '00:30' ): ?>selected="selected"<?php endif; ?> >00:30</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div ng-app="taocan" id="taocan" role="tabpanel" class="tab-pane" ng-init="kid=<?php echo ($kid); ?>">
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
                                                        <input type="text" placeholder="房型名称" ng-model='roominfo.name' required>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label">容纳人数</label>
                                                    <div class="col-md-5 form-inline">
                                                        <div class="form-group">
                                                            <input type="number" ng-model='roominfo.des_s' required>
                                                        </div> -
                                                        <div class="form-group">
                                                            <input type="number" ng-model='roominfo.des_e' required>人
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label">房间数量</label>
                                                    <div class="col-md-5">
                                                        <input type="number" placeholder="房间数量" ng-model='roominfo.count' required>
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
                                            <button ng-class="taocan.sun=='1'?'btn btn-default btn-info':'btn btn-default'" disabled="disabled" style="cursor: default;">日</button>
                                            <button ng-class="taocan.mon=='1'?'btn btn-default btn-info':'btn btn-default'" disabled="disabled" style="cursor: default;">一</button>
                                            <button ng-class="taocan.tue=='1'?'btn btn-default btn-info':'btn btn-default'" disabled="disabled" style="cursor: default;">二</button>
                                            <button ng-class="taocan.wen=='1'?'btn btn-default btn-info':'btn btn-default'" disabled="disabled" style="cursor: default;">三</button>
                                            <button ng-class="taocan.thu=='1'?'btn btn-default btn-info':'btn btn-default'" disabled="disabled" style="cursor: default;">四</button>
                                            <button ng-class="taocan.fri=='1'?'btn btn-default btn-info':'btn btn-default'" disabled="disabled" style="cursor: default;">五</button>
                                            <button ng-class="taocan.sat=='1'?'btn btn-default btn-info':'btn btn-default'" disabled="disabled" style="cursor: default;">六</button>
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
                                                        <textarea placeholder="套餐详情" id='addtaocandesc' rows="5" class="form-control" ng-model='taocaninfo.desc' required></textarea>
                                                        <p>不同内容之间请使用全角逗号“，”来分隔</p>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label">时长</label>
                                                    <div class="col-md-5">
                                                        <textarea placeholder="套餐详情" id='addtaocandesc' rows="5" class="form-control" ></textarea>
                                                        
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label">原价</label>
                                                    <div class="col-md-5">
                                                        <input type="text" placeholder="原价" ng-model='taocaninfo.price' required>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label">优惠价</label>
                                                    <div class="col-md-5">
                                                        <input type="text" placeholder="优惠价" ng-model='taocaninfo.member_price' required>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label">夜点价</label>
                                                    <div class="col-md-5">
                                                        <input type="text" placeholder="夜点价" ng-model='taocaninfo.yd_price' required>
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
                                                    <input type="checkbox" ng-click="selweekend()" ng-model='selweekends' />
                                                    <br> 工作日
                                                    <input type="checkbox" ng-click="selworkday()" ng-model='selworkdays' />
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
                                                            <div class="taocan_box">
                                                                <button type="button" class="btn btn-info" style="margin:5px auto">{{node.name}} </button>
                                                                <div class="taocan_detail">
                                                                    <p>描述:{{node.content.desc}}</p>
                                                                    <p>房型:{{node.content.roomtype}}</p>
                                                                    <p>价钱:{{node.content.price}}</p>
                                                                    <p><span ng-if="node.content.is_yd_price==1">夜点专属价</span>
                                                                        <span ng-if="node.content.is_yd_price==0">会员价</span>:{{node.content.member_price}}</p>
                                                                    <p>时段:{{node.content.shijianduan}}</p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div ng-repeat="node in taocan.mon">
                                                            <div class="taocan_box">
                                                                <button type="button" class="btn btn-info" style="margin:5px auto">{{node.name}} </button>
                                                                <div class="taocan_detail">
                                                                    <p>描述:{{node.content.desc}}</p>
                                                                    <p>房型:{{node.content.roomtype}}</p>
                                                                    <p>价钱:{{node.content.price}}</p>
                                                                    <p><span ng-if="node.content.is_yd_price==1">夜点专属价</span>
                                                                        <span ng-if="node.content.is_yd_price==0">会员价</span>:{{node.content.member_price}}</p>
                                                                    <p>时段:{{node.content.shijianduan}}</p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div ng-repeat="node in taocan.tue">
                                                            <div class="taocan_box">
                                                                <button type="button" class="btn btn-info" style="margin:5px auto">{{node.name}} </button>
                                                                <div class="taocan_detail">
                                                                    <p>描述:{{node.content.desc}}</p>
                                                                    <p>房型:{{node.content.roomtype}}</p>
                                                                    <p>价钱:{{node.content.price}}</p>
                                                                    <p><span ng-if="node.content.is_yd_price==1">夜点专属价</span>
                                                                        <span ng-if="node.content.is_yd_price==0">会员价</span>:{{node.content.member_price}}</p>
                                                                    <p>时段:{{node.content.shijianduan}}</p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div ng-repeat="node in taocan.wen">
                                                            <div class="taocan_box">
                                                                <button type="button" class="btn btn-info" style="margin:5px auto">{{node.name}} </button>
                                                                <div class="taocan_detail">
                                                                    <p>描述:{{node.content.desc}}</p>
                                                                    <p>房型:{{node.content.roomtype}}</p>
                                                                    <p>价钱:{{node.content.price}}</p>
                                                                    <p><span ng-if="node.content.is_yd_price==1">夜点专属价</span>
                                                                        <span ng-if="node.content.is_yd_price==0">会员价</span>:{{node.content.member_price}}</p>
                                                                    <p>时段:{{node.content.shijianduan}}</p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div ng-repeat="node in taocan.thu">
                                                            <div class="taocan_box">
                                                                <button type="button" class="btn btn-info" style="margin:5px auto">{{node.name}} </button>
                                                                <div class="taocan_detail">
                                                                    <p>描述:{{node.content.desc}}</p>
                                                                    <p>房型:{{node.content.roomtype}}</p>
                                                                    <p>价钱:{{node.content.price}}</p>
                                                                    <p><span ng-if="node.content.is_yd_price==1">夜点专属价</span>
                                                                        <span ng-if="node.content.is_yd_price==0">会员价</span>:{{node.content.member_price}}</p>
                                                                    <p>时段:{{node.content.shijianduan}}</p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div ng-repeat="node in taocan.fri">
                                                            <div class="taocan_box">
                                                                <button type="button" class="btn btn-info" style="margin:5px auto">{{node.name}} </button>
                                                                <div class="taocan_detail">
                                                                    <p>描述:{{node.content.desc}}</p>
                                                                    <p>房型:{{node.content.roomtype}}</p>
                                                                    <p>价钱:{{node.content.price}}</p>
                                                                    <p><span ng-if="node.content.is_yd_price==1">夜点专属价</span>
                                                                        <span ng-if="node.content.is_yd_price==0">会员价</span>:{{node.content.member_price}}</p>
                                                                    <p>时段:{{node.content.shijianduan}}</p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div ng-repeat="node in taocan.sat">
                                                            <div class="taocan_box">
                                                                <button type="button" class="btn btn-info" style="margin:5px auto">{{node.name}} </button>
                                                                <div class="taocan_detail">
                                                                    <p>描述:{{node.content.desc}}</p>
                                                                    <p>房型:{{node.content.roomtype}}</p>
                                                                    <p>价钱:{{node.content.price}}</p>
                                                                    <p><span ng-if="node.content.is_yd_price==1">夜点专属价</span>
                                                                        <span ng-if="node.content.is_yd_price==0">会员价</span>:{{node.content.member_price}}</p>
                                                                    <p>时段:{{node.content.shijianduan}}</p>

                                                                </div>
                                                            </div>
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
                            <div id="pic_mange" role="tabpanel" class="tab-pane">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">图片1</label>
                                        <div class="col-sm-10">
                                            <input type="file" data-classbutton="btn btn-default" data-classinput="form-control inline" class="form-control filestyle">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">图片2</label>
                                        <div class="col-sm-10">
                                            <input type="file" data-classbutton="btn btn-default" data-classinput="form-control inline" class="form-control filestyle">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">图片3</label>
                                        <div class="col-sm-10">
                                            <input type="file" data-classbutton="btn btn-default" data-classinput="form-control inline" class="form-control filestyle">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">图片4</label>
                                        <div class="col-sm-10">
                                            <input type="file" data-classbutton="btn btn-default" data-classinput="form-control inline" class="form-control filestyle">
                                        </div>
                                    </div>
                                </fieldset>
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
    <script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp&key=4QMBZ-YW5AR-AAZWF-WXXP2-QWJ3V-3DFGN"></script>
    <script>
    var lat = <?php echo ($xktvinfo["lat"]); ?>;
    var lng = <?php echo ($xktvinfo["lng"]); ?>;
    $('#qqmap').css('height', '400px');

    var init = function() {
        var map = new qq.maps.Map(document.getElementById("qqmap"), {
            center: new qq.maps.LatLng(lat, lng),
            zoom: 21
        });
        var marker1 = new qq.maps.Marker({
            position: new qq.maps.LatLng(lat, lng),
            map: map
        });
        qq.maps.event.addListener(map, 'click', function(event) {
            marker1.setMap(null);
            var latLng = event.latLng,
                lat = latLng.getLat().toFixed(5),
                lng = latLng.getLng().toFixed(5);
            // document.getElementById("latlng").innerHTML = lat+','+lng;
            $("input[name='lat']").val(lat);
            $("input[name='lng']").val(lng);
            var marker = new qq.maps.Marker({
                position: event.latLng,
                map: map
            });
            qq.maps.event.addListener(map, 'click', function(event) {
                marker.setMap(null);
            });
        });
    }
    init();
    </script>
</body>

</html>