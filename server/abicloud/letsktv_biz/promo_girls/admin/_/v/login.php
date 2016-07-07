<!DOCTYPE html>
<html lang="zhCN">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <title>登录</title>
   <!-- =============== VENDOR STYLES ===============-->
   <!-- SIMPLE LINE ICONS-->
   <link rel="stylesheet" href="public/vendor/simple-line-icons/css/simple-line-icons.css">
   <!-- =============== BOOTSTRAP STYLES ===============-->
   <link rel="stylesheet" href="public/app/css/bootstrap.css" id="bscss">
   <!-- =============== APP STYLES ===============-->
   <link rel="stylesheet" href="public/app/css/app.css" id="maincss">
   <style type="text/css">
       body {
           background-image: url('public/app/img/login_background.jpg');
           background-repeat: no-repeat;
           background-position: center center;
           background-size: cover;
       }
       .main {
           position: absolute;
           top:50%;
           left:50%;
           width: 320px;
           height: 356px;
           margin-top: -190px !important;
           margin-left: -160px;
       }
       .panel {
           background-color: transparent;
       }
       .panel-dark > .panel-heading {
           background-color: transparent;
       }
       .panel-body {
           background-color: #fff;
           border-radius: 5px;
       }
       .btn {
           background-color: #c9161e;
       }
       .btn-primary:hover {
           background-color: #c9161e;
       }
   </style>
</head>

<body>
   <div class="wrapper">
      <div class="block-center mt-xl wd-xl main">
         <!-- START panel-->
         <div class="panel panel-dark panel-flat">
            <div class="panel-heading text-center">
               <a href="#">
                  <img src="public/app/img/login_logo.png" alt="Image" class="block-center img-rounded">
               </a>
            </div>
            <div class="panel-body">
               <p class="text-center pv">请登录</p>
               <p class="parsley-error" style="color: red;"><?php if(isset($error)){echo $error;}?></p>
               <form role="form" data-parsley-validate="" novalidate="" class="mb-lg" method="post">
                  <div class="form-group has-feedback">
                     <input id="exampleInputEmail1" name="username" type="text" placeholder="请输入用户名" autocomplete="off" required class="form-control">
                     <span class="fa fa-envelope form-control-feedback text-muted"></span>
                  </div>
                  <div class="form-group has-feedback">
                     <input id="exampleInputPassword1" name="password" type="password" placeholder="请输入密码" required class="form-control">
                     <span class="fa fa-lock form-control-feedback text-muted"></span>
                  </div>
                  <button type="submit" class="btn btn-block btn-primary mt-lg">登录</button>
               </form>
            </div>
         </div>
         <!-- END panel-->
      </div>
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
   <!-- PARSLEY-->
   <script src="public/vendor/parsleyjs/dist/parsley.min.js"></script>
</body>

</html>