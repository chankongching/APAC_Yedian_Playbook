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
                         <a href="?m=conversion&a=<?php echo $a;?>&t=reservation&range=<?php echo $conversion->start;?>/<?php echo $conversion->end;?>" class="mb-sm btn btn-<?php if($t === 'reservation') { ?>primary<?php } else { ?>default<?php } ?>">KTV预订率</a>
                         <a href="?m=conversion&a=<?php echo $a;?>&t=channel&range=<?php echo $conversion->start;?>/<?php echo $conversion->end;?>" class="mb-sm btn btn-<?php if($t === 'channel') { ?>primary<?php } else { ?>default<?php } ?>">预订渠道分析</a>
                         <a href="?m=conversion&a=<?php echo $a;?>&t=visit&range=<?php echo $conversion->start;?>/<?php echo $conversion->end;?>" class="mb-sm btn btn-<?php if($t === 'visit') { ?>primary<?php } else { ?>default<?php } ?>">KTV页面访问率</a>
                         <a href="?m=conversion&a=<?php echo $a;?>&t=user&range=<?php echo $conversion->start;?>/<?php echo $conversion->end;?>" class="mb-sm btn btn-<?php if($t === 'user') { ?>primary<?php } else { ?>default<?php } ?>">预定用户</a>
                    </div>
                    <div>
                         <a href="?m=conversion&a=pastWeek&t=<?php echo $t;?>" class="btn btn-<?php if($a === 'pastWeek') { ?>primary<?php } else { ?>default<?php } ?>">最近 7 天</a>
                         <a href="?m=conversion&a=pastMonth&t=<?php echo $t;?>" class="btn btn-<?php if($a === 'pastMonth') { ?>primary<?php } else { ?>default<?php } ?>">最近30天</a>
                         <input id="dateRange" class="btn btn-<?php if($a === 'byRange') { ?>primary<?php } else { ?>default<?php } ?>" readonly="" value="<?php echo $conversion->start;?>/<?php echo $conversion->end;?>" data-url="?m=conversion&a=byRange&t=<?php echo $t;?>&range=" />
                    </div>
                </div>
                <div class="container-fluid">
<?php
if(in_array($t, array('reservation', 'channel'))) {
?>
                    <!-- START row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div id="panelChart5" class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title text-center"><?php echo $conversion->ChartTitle;?></div>
                                    <div class="chart-line-legend" style="float: right;padding: 0 15px;"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="chart-line flot-chart"></div>
                                    <div style="text-align: center; font-size: 12px;">
<?php
foreach($conversion->data['reservationbyRange'] as $desc) {
    echo '<span style="color: '.$desc['color'].'">'.$desc['desc'].'</span><br />';
}
?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END row-->
<?php
} elseif($t === 'visit') {
?>
                    <!-- START row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title text-center">KTV Visit Rate (Total Fans)</div>
                                    <div class="chart-line1-legend" style="float: right;padding: 0 15px;"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="chart-line1 flot-chart"></div>
                                    <div style="text-align: center; font-size: 12px;">
<?php
echo '<span style="color: '.$conversion->data[0][0]['color'].'">'.$conversion->data[0][0]['desc'].'</span><br />';
?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END row-->
                    <!-- START row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title text-center">Reservation User Rate (Total UV)</div>
                                    <div class="chart-line2-legend" style="float: right;padding: 0 15px;"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="chart-line2 flot-chart"></div>
                                    <div style="text-align: center; font-size: 12px;">
<?php
echo '<span style="color: '.$conversion->data[1][0]['color'].'">'.$conversion->data[1][0]['desc'].'</span><br />';
?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END row-->
<?php
} elseif($t === 'user') {
?>
                    <!-- START row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title text-center">Reservation User Rate (Total Fans)</div>
                                    <div class="chart-line3-legend" style="float: right;padding: 0 15px;"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="chart-line3 flot-chart"></div>
                                    <div style="text-align: center; font-size: 12px;">
<?php
echo '<span style="color: '.$conversion->data[0][0]['color'].'">'.$conversion->data[0][0]['desc'].'</span><br />';
?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END row-->
                    <!-- START row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title text-center">Reservation User Rate (Total UV)</div>
                                    <div class="chart-line4-legend" style="float: right;padding: 0 15px;"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="chart-line4 flot-chart"></div>
                                    <div style="text-align: center; font-size: 12px;">
<?php
echo '<span style="color: '.$conversion->data[1][0]['color'].'">'.$conversion->data[1][0]['desc'].'</span><br />';
?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END row-->
<?php
}
?>
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
<?php
if(in_array($t, array('reservation', 'channel'))) {
?>
        // CHART LINE
        (function(window, document, $, undefined) {
            $(function() {
                var data = <?php echo json_encode($conversion->data['reservationbyRange']); ?>;
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
                        backgroundColor: '#fcfcfc'
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function(label, x, y) {
                            return /* x + ': ' +  */y + '%';
                        }
                    },
                    xaxis: {
                        tickColor: '#eee',
                        mode: 'categories'
                    },
                    yaxis: {
                        tickColor: '#eee',
                        tickFormatter: function (v) {
                            return v + '%';
                        }
                    },
                    shadowSize: 0,
                    legend: {
                        show: data.length > 1 ? true : false,
                        container:$(".chart-line-legend"),
                        noColumns: data.length
                    }
                };
                var chart = $('.chart-line');
                if (chart.length) $.plot(chart, data, options);
            });
        })(window, document, window.jQuery);
<?php
} elseif($t === 'visit') {
?>
        (function(window, document, $, undefined) {
            $(function() {
                var data = <?php echo json_encode($conversion->data[0]); ?>;
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
                        backgroundColor: '#fcfcfc'
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function(label, x, y) {
                            return /* x + ': ' +  */y + '‰';
                        }
                    },
                    xaxis: {
                        tickColor: '#eee',
                        mode: 'categories'
                    },
                    yaxis: {
                        tickColor: '#eee',
                        tickFormatter: function (v) {
                            return v + '‰';
                        }
                    },
                    shadowSize: 0,
                    legend: {
                        show: data.length > 1 ? true : false,
                        container:$(".chart-line1-legend"),
                        noColumns: data.length
                    }
                };
                var chart = $('.chart-line1');
                if (chart.length) $.plot(chart, data, options);
            });
        })(window, document, window.jQuery);

        (function(window, document, $, undefined) {
            $(function() {
                var data = <?php echo json_encode($conversion->data[1]); ?>;
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
                        backgroundColor: '#fcfcfc'
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function(label, x, y) {
                            return /* x + ': ' +  */y + '%';
                        }
                    },
                    xaxis: {
                        tickColor: '#eee',
                        mode: 'categories'
                    },
                    yaxis: {
                        tickColor: '#eee',
                        tickFormatter: function (v) {
                            return v + '%';
                        }
                    },
                    shadowSize: 0,
                    legend: {
                        show: data.length > 1 ? true : false,
                        container:$(".chart-line2-legend"),
                        noColumns: data.length
                    }
                };
                var chart = $('.chart-line2');
                if (chart.length) $.plot(chart, data, options);
            });
        })(window, document, window.jQuery);
<?php
} elseif($t === 'user') {
?>
        (function(window, document, $, undefined) {
            $(function() {
                var data = <?php echo json_encode($conversion->data[0]); ?>;
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
                        backgroundColor: '#fcfcfc'
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function(label, x, y) {
                            return /* x + ': ' +  */y + '‱';
                        }
                    },
                    xaxis: {
                        tickColor: '#eee',
                        mode: 'categories'
                    },
                    yaxis: {
                        tickColor: '#eee',
                        tickFormatter: function (v) {
                            return v + '‱';
                        }
                    },
                    shadowSize: 0,
                    legend: {
                        show: data.length > 1 ? true : false,
                        container:$(".chart-line3-legend"),
                        noColumns: data.length
                    }
                };
                var chart = $('.chart-line3');
                if (chart.length) $.plot(chart, data, options);
            });
        })(window, document, window.jQuery);

        (function(window, document, $, undefined) {
            $(function() {
                var data = <?php echo json_encode($conversion->data[1]); ?>;
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
                        backgroundColor: '#fcfcfc'
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function(label, x, y) {
                            return /* x + ': ' +  */y + '%';
                        }
                    },
                    xaxis: {
                        tickColor: '#eee',
                        mode: 'categories'
                    },
                    yaxis: {
                        tickColor: '#eee',
                        tickFormatter: function (v) {
                            return v + '%';
                        }
                    },
                    shadowSize: 0,
                    legend: {
                        show: data.length > 1 ? true : false,
                        container:$(".chart-line4-legend"),
                        noColumns: data.length
                    }
                };
                var chart = $('.chart-line4');
                if (chart.length) $.plot(chart, data, options);
            });
        })(window, document, window.jQuery);
<?php
}
?>
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