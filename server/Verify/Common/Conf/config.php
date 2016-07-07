<?php
return array(
    //'配置项'=>'配置值'
    'MODULE_ALLOW_LIST' => array('Home', 'Admin'),
    'DEFAULT_MODULE' => 'Admin',
    'URL_MODEL' => 2,
    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => 'Common@Public/error',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Common@Public/success',
);
