<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'ABI Cloud System',
    // preloading 'log' component
    'preload' => array('log'),
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    // application components
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=abicloud',
            'emulatePrepare' => true,
            'username' => 'website',
            'password' => 'WebSite456',
            'charset' => 'utf8',
            'tablePrefix' => 'ac_',
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'itemTable' => '{{auth_item}}',
            'itemChildTable' => '{{auth_item_child}}',
            'assignmentTable' => '{{auth_assignment}}',
        ),
        // uncomment the following to use a MySQL database
        /*
          'db'=>array(
          'connectionString' => 'mysql:host=localhost;dbname=testdrive',
          'emulatePrepare' => true,
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
          ),
         */
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
    // Wingsun: requires you to copy the theme directory to your themes directory
    //'language' => 'zh_cn',
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'language' => 'zh_cn',
    'timeZone' => 'Asia/Shanghai',
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
    ),
);
