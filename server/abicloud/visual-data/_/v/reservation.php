<?php (INAPP !== true) && die('Error !'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Bootstrap Admin App + jQuery">
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
    <title>夜点数据统计分析系统</title>
    <!-- =============== VENDOR STYLES ===============-->
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="public/vendor/Angle-3.0-backend-jquery/vendor/fontawesome/css/font-awesome.min.css">
    <!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="public/vendor/Angle-3.0-backend-jquery/vendor/simple-line-icons/css/simple-line-icons.css">
    <!-- ANIMATE.CSS-->
    <link rel="stylesheet" href="public/vendor/Angle-3.0-backend-jquery/vendor/animate.css/animate.min.css">
    <!-- WHIRL (spinners)-->
    <link rel="stylesheet" href="public/vendor/Angle-3.0-backend-jquery/vendor/whirl/dist/whirl.css">
    <!-- =============== PAGE VENDOR STYLES ===============-->
    <!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="public/vendor/Angle-3.0-backend-jquery/app/css/bootstrap.css" id="bscss">
    <!-- =============== APP STYLES ===============-->
    <link rel="stylesheet" href="public/vendor/Angle-3.0-backend-jquery/app/css/app.css" id="maincss">
    
    <link rel="stylesheet" href="public/vendor/daterangepicker/daterangepicker.min.css" />
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
                            <img src="public/vendor/Angle-3.0-backend-jquery/app/img/logo.png" alt="App Logo" class="img-responsive">
                        </div>
                    </a>
                </div>
                <span style="display: block;float: left;line-height: 55px;font-size: 16px;color: #fff;">夜点数据可视化平台</span>
                <!-- END navbar header-->
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
                            <span data-localize="sidebar.heading.HEADER">菜单</span>
                        </li>
                        <li class=" ">
                            <a href="./">
                                <em class="icon-grid"></em>
                                <span data-localize="sidebar.nav.RESERVSTION">预订信息</span>
                            </a>
                            <a href="./?m=conversion">
                                <em class="icon-grid"></em>
                                <span data-localize="sidebar.nav.RESERVSTION">转化率</span>
                            </a>
                            <a href="./?m=click">
                                <em class="icon-grid"></em>
                                <span data-localize="sidebar.nav.RESERVSTION">点击率</span>
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
                <div class="content-heading">
                    <div>
                         <a href="?m=reservation&a=<?php echo $a;?>&t=all&range=<?php echo $reservation->start;?>/<?php echo $reservation->end;?>" class="mb-sm btn btn-<?php if($t === 'all') { ?>primary<?php } else { ?>default<?php } ?>">总订单数</a>
                         <a href="?m=reservation&a=<?php echo $a;?>&t=callcenter&range=<?php echo $reservation->start;?>/<?php echo $reservation->end;?>" class="mb-sm btn btn-<?php if($t === 'callcenter') { ?>primary<?php } else { ?>default<?php } ?>">电话中心</a>
                         <a href="?m=reservation&a=<?php echo $a;?>&t=biz&range=<?php echo $reservation->start;?>/<?php echo $reservation->end;?>" class="mb-sm btn btn-<?php if($t === 'biz') { ?>primary<?php } else { ?>default<?php } ?>">KTV商户版</a>
                    </div>
                    <div>
                         <a href="?m=reservation&a=pastWeek&t=<?php echo $t;?>" class="btn btn-<?php if($a === 'pastWeek') { ?>primary<?php } else { ?>default<?php } ?>">最近 7 天</a>
                         <a href="?m=reservation&a=pastMonth&t=<?php echo $t;?>" class="btn btn-<?php if($a === 'pastMonth') { ?>primary<?php } else { ?>default<?php } ?>">最近30天</a>
                         <input id="dateRange" class="btn btn-<?php if($a === 'byRange') { ?>primary<?php } else { ?>default<?php } ?>" readonly="" value="<?php echo $reservation->start;?>/<?php echo $reservation->end;?>" data-url="?m=reservation&a=byRange&t=<?php echo $t;?>&range=" />
                    </div>
                </div>
                <div class="container-fluid">
                    <!-- START row-->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="table-responsive" style="padding: 5px 0 4px;}">
                                        <table class="table table-hover table-bordered text-center">
                                            <tbody>
                                                <tr>
                                                    <td><?php if($t === 'all') { ?>历史<?php } elseif($t === 'callcenter') { ?>电话中心<?php } elseif($t === 'biz') { ?>商务版<?php } ?>订单总数<br /><?php echo max(0, $reservation->data['all']);?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php if($t === 'all') { ?>历史<?php } elseif($t === 'callcenter') { ?>电话中心<?php } elseif($t === 'biz') { ?>商务版<?php } ?>昨日订单总数<br /><?php echo max(0, $reservation->data['pastDay']);?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="panel panel-default">
                                <div class="panel-heading text-center">时间区间内数量</div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover text-center">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">等待处理</th>
                                                    <th class="text-center">预订成功</th>
                                                    <th class="text-center">无房</th>
                                                    <th class="text-center">用户取消</th>
                                                    <th class="text-center">超时</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo max(0, $reservation->data['summary']['0']['data']);?></td>
                                                    <td><?php echo max(0, $reservation->data['summary']['1']['data']);?></td>
                                                    <td><?php echo max(0, $reservation->data['summary']['2']['data']);?></td>
                                                    <td><?php echo max(0, $reservation->data['summary']['3']['data']);?></td>
                                                    <td><?php echo max(0, $reservation->data['summary']['4']['data']);?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END row-->
                    <!-- START row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div id="panelChart5" class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title text-center">时间区间内比例</div>
                                    <div class="panelChart5-legend" style="float: right;padding: 0 15px;"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="chart-pie flot-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END row-->
                    <!-- START row-->
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="panelChart4" class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title text-center">每日订单数量</div>
                                    <div class="panelChart4-legend" style="float: right;padding: 0 15px;"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="chart-line flot-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END row-->
                </div>
            </div>
        </section>
    </div>
    <!-- =============== VENDOR SCRIPTS ===============-->
    <!-- MODERNIZR-->
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/modernizr/modernizr.js"></script>
    <!-- JQUERY-->
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/jquery/dist/jquery.js"></script>
    <!-- BOOTSTRAP-->
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/bootstrap/dist/js/bootstrap.js"></script>
    <!-- STORAGE API-->
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/jQuery-Storage-API/jquery.storageapi.js"></script>
    <!-- JQUERY EASING-->
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/jquery.easing/js/jquery.easing.js"></script>
    <!-- ANIMO-->
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/animo.js/animo.js"></script>
    <!-- SLIMSCROLL-->
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- SCREENFULL-->
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/screenfull/dist/screenfull.js"></script>
    <!-- LOCALIZE-->
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/jquery-localize-i18n/dist/jquery.localize.js"></script>
    <!-- RTL demo-->
    <script src="public/vendor/Angle-3.0-backend-jquery/app/js/demo/demo-rtl.js"></script>
    <!-- =============== PAGE VENDOR SCRIPTS ===============-->
    <!-- SPARKLINE-->
    <script src="public/vendor/Angle-3.0-backend-jquery/app/vendor/sparklines/jquery.sparkline.min.js"></script>
    <!-- FLOT CHART-->
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/Flot/jquery.flot.js"></script>
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/Flot/jquery.flot.resize.js"></script>
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/Flot/jquery.flot.pie.js"></script>
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/Flot/jquery.flot.time.js"></script>
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/Flot/jquery.flot.categories.js"></script>
    <script src="public/vendor/Angle-3.0-backend-jquery/vendor/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="public/vendor/Angle-3.0-backend-jquery/app/js/demo/demo-flot.js"></script>
    <!-- =============== APP SCRIPTS ===============-->
    <script src="public/vendor/momentjs/moment-with-locales.min.js"></script>
    <script src="public/vendor/daterangepicker/jquery.daterangepicker.min.js"></script>
    <script>
        // CHART LINE
        (function(window, document, $, undefined) {
            $(function() {
                var data = <?php echo json_encode($reservation->data['chart']); ?>;
                var total = <?php echo json_encode($reservation->data['chart_total_perday']); ?>;
                var options = {
                    series: {
                        lines: {
                            show: true,
                            fill: 0.01
                        },
                        points: {
                            show: true,
                            radius: 4
                        }
                    },
                    grid: {
                        borderColor: '#eee',
                        borderWidth: 1,
                        hoverable: true,
                        backgroundColor: '#fcfcfc',
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function(label, x, y) {
                            return y + ' : ' + GetPercent(y, total[x]);
                        }
                    },
                    xaxis: {
                        tickColor: '#eee',
                        mode: 'categories'
                    },
                    yaxis: {
                        tickColor: '#eee'
                    },
                    shadowSize: 0,
                    legend: {
                        show: data.length > 1 ? true : false,
                        container:$(".panelChart5-legend"),
                        noColumns: data.length
                    }
                };
                var chart = $('.chart-line');
                if (chart.length) $.plot(chart, data, options);
            });
        })(window, document, window.jQuery);
        (function(window, document, $, undefined) {
            $(function() {
                var data = <?php echo json_encode($reservation->data['summary']); ?>;
                var options = {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 0,
                            label: {
                                show: true,
                                radius: 0.8,
                                formatter: function(label, series) {
                                    return '<div class="flot-pie-label">' + 
                                    // label + ' : ' + 
                                    Math.round(series.percent) + '%</div>'
                                },
                                background: {
                                    opacity: 0.8,
                                    color: '#222'
                                }
                            }
                        }
                    },
                    legend: {
                        show: data.length > 1 ? true : false,
                        container:$(".panelChart4-legend"),
                        noColumns: data.length
                    }
                };
                var chart = $('.chart-pie');
                if (chart.length) $.plot(chart, data, options)
            })
        })(window, document, window.jQuery);
        $(document).ready(function(){
            $('#dateRange').dateRangePicker({separator: '/', language:'cn', startDate: '2016-01-27', endDate: '<?php echo YESTERDAY; ?>'}).bind('datepicker-apply', function(event, obj) {
                window.location.href = $(this).attr('data-url') + obj.value;
            });
        });
        function GetPercent(num, total) { 
            num = parseFloat(num); 
            total = parseFloat(total); 
            if (isNaN(num) || isNaN(total)) { 
                return "-"; 
            } 
            return total <= 0 ? "0%" : (Math.round(num / total * 10000) / 100.00 + "%"); 
        }
    </script>
</body>

</html>