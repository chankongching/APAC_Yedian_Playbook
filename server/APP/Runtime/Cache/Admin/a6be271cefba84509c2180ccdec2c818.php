<?php if (!defined('THINK_PATH')) exit(); if(C('LAYOUT_ON')) { echo ''; } ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>跳转</title>
        <!-- Bootstrap -->
        <link href="/wechatshangjia/Public/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/wechatshangjia/Public/css/style.css">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    </head>

    <body>
        <div class="container main-box">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                    <div class="system-message">
                        <?php if(isset($message)) {?>
                            <p class="success">
                                <button type="button" class="btn btn-danger btn-block"><?php echo($message); ?></button>
                            </p>
                            <?php }else{?>
                                <p class="error">
                                    <button type="button" class="btn btn-danger btn-block"><?php echo($error); ?></button>
                                </p>
                                <?php }?>
                                    <p class="detail"></p>
                                    <p class="jump">
                                        <button type="button" class="btn btn-danger btn-block">页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b></button>
                                    </p>
                    </div>
                </div>
            </div>
        </div>
        <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
        <script src="/wechatshangjia/Public/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        (function() {
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function() {
                var time = --wait.innerHTML;
                if (time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
        </script>
    </body>

    </html>