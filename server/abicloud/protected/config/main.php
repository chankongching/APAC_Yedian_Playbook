<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => "Let's KTV System",
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.extensions.debugtoolbar.*',
        'application.extensions.qrcode.*',
        // rights
        'application.modules.rights.*',
        'application.modules.rights.components.*', //这一行，在官方文档里面没有，不写的话，会导致RWebUser找不到
    // giix components
    //'application.extensions.giix-components.*',
    ),
    'modules' => array(
        // rights module
        'rights' => array(
            'superuserName' => 'Super', //超级用户角色，这个作为超级用户角色
            'userClass' => 'User', //自己用户表对应的用户模型类
            'authenticatedName' => 'Member', //认证用户角色，自己起个喜欢的名字
            'userIdColumn' => 'id', //自己用户表对应的id栏
            'userNameColumn' => 'username', //自己用户表对应的栏
            'enableBizRule' => true,
            'enableBizRuleData' => false,
            'displayDescription' => true,
            'flashSuccessKey' => 'RightsSuccess',
            'flashErrorKey' => 'RightsError',
            'baseUrl' => '/rights',
            'layout' => 'rights.views.layouts.main',
            //'appLayout' => 'application.views.layouts.main',
            'cssFile' => 'rights.css',
            'install' => false, //第一次安装需要为true，安装成功以后记得改成false
            'debug' => false,
        ),
        // uncomment the following to enable the Gii tool
        ///*
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'wingsun',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            // giix generations
            'generatorPaths' => array('application.vendor.giix-core'),
        ),
    //*/
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            // use rights module
            'class' => 'RWebUser',
        ),
        'session' => array(
            'class' => 'DBHttpSession',
            'connectionID' => 'db',
            'autoCreateSessionTable' => true,
            'sessionTableName' => '{{YiiSession}}',
        ),
        'authManager' => array(
            //'class' => 'CDbAuthManager',
            //'connectionID' => 'db',
            'itemTable' => '{{auth_item}}',
            'itemChildTable' => '{{auth_item_child}}',
            'assignmentTable' => '{{auth_assignment}}',
            'class' => 'RDbAuthManager',
            //'assignmentTable' => '{{authassignment}}',
            //'itemTable' => '{{authitem}}',
            //'itemChildTable' => '{{authitemchild}}',
            'rightsTable' => '{{rights}}',
            //'defaultRoles' => array('Admin','Member','Guest'),// 不能随便设置
            'defaultRoles' => array('Member', 'Guest'), // 不能随便设置，用户创建时默认的两种角色，根据是否登录来判断
        ),
        // uncomment the following to enable URLs in path-format
        ///*
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'urlSuffix' => '.html',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        //*/
        /*
          'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ),
         */
        // uncomment the following to use a MySQL database
        ///*
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=abicloud',
            'emulatePrepare' => true,
            'username' => 'website',
            'password' => 'WebSite456',
            'charset' => 'utf8',
            'tablePrefix' => 'ac_',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
        //*/
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error',
                    'logFile' => 'error.log',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'trace, info, profile',
                    'logFile' => 'trace.log',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, trace, info, profile',
                    'categories' => 'ApiController.stb.*',
                    'logFile' => 'STB-trace.log',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, trace, info, profile',
                    'categories' => 'ApiController.order.*,ApiController.shopcart.*',
                    'logFile' => 'STORE-trace.log',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, trace, info, profile',
                    'categories' => 'ApiController.player.*,ApiController.user.*',
                    'logFile' => 'APP-trace.log',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, trace, info, profile',
                    'categories' => 'ApiController.booking.*',
                    'logFile' => 'BOOKING-trace.log',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, trace, info, profile',
                    'categories' => 'ApiController.notify.*',
                    'logFile' => 'notify-trace.log',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, trace, info, profile',
                    'categories' => 'ApiController.points.*',
                    'logFile' => 'points-trace.log',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, trace, info, profile',
                    'categories' => 'ApiController.admin.*',
                    'logFile' => 'admin-trace.log',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, trace, profile',
                    'categories' => 'system.db.*',
                    'logFile' => 'DB-trace.log',
                ),
            // debug toolbar configuration
            //array(
            //    'class' => 'XWebDebugRouter',
            //    'config' => 'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
            //    'levels' => 'error, warning, trace, profile, info',
            //    'allowedIPs' => array('127.0.0.1'),
            //),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // Wingsun: requires you to copy the theme directory to your themes directory
    'theme' => 'smart',
    'language' => 'zh_cn',
    'timeZone' => 'Asia/Shanghai',
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'viewstyle' => 'list',
        'upload_folder' => '',
        'room_user_list' => array(
        //array('nickname' => '风高云淡', 'avatarurl' => 'default_user_portrait.gif'),
        //array('nickname' => 'Mountain King', 'avatarurl' => 'pxb00000148.jpg'),
        //array('nickname' => 'Smile Sky', 'avatarurl' => 'pxb00000149.jpg'),
        //array('nickname' => '那山那水那人', 'avatarurl' => 'pxb00000154.jpg'),
        //array('nickname' => '跨越巅峰', 'avatarurl' => 'pxb00000157.jpg'),
        ),
        'user_default_setting' => array('nickname' => 'AbiKTV', 'avatarurl' => 'default_user_portrait.gif'),
        'song_default_setting' => array('bigpic' => '0002big.png', 'smallpic' => '0002small.png', 'duration' => 0, 'video' => '0002.mp4'),
        'picture_default_setting' => array('bigpic' => '0002big.png', 'smallpic' => '0002small.png'),
        'erp_default_setting' => array('erp_picbaseurl' => 'http://192.168.2.253/xktv', 'category_bigpic' => '0002big.png', 'category_smallpic' => '0002small.png', 'product_bigpic' => '0002big.png', 'product_smallpic' => '0002small.png'),
        'erp_api_url' => 'http://192.168.2.253/V8WebService.dll/wsdl/IMain',
        'erp_api_userid' => 'dba',
        'test_room_id' => 1,
        'user_valid_time' => 10,
        'user_idle_time' => 600,
        'merge_room_order' => true,
        'app_key' => '82f9KD6TAMb2waoMR9en5B',
        'vendor_key' => 'Y2ZqvVcQtKdUWpED6rR5JN',
    ),
);
