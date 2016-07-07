<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE' => 'mysql', // 数据库类型
	'DB_HOST' => 'localhost', // 服务器地址
	'DB_NAME' => 'abicloud', // 数据库名
	'DB_USER' => 'website', // 用户名
	'DB_PWD' => 'WebSite456', // 密码
	'DB_PORT' => '3306', // 端口
	'DB_PREFIX' => 'ydsjb_', // 数据库表前缀
	// 'DB_FIELDTYPE_CHECK'    =>  false,       // 是否进行字段类型检查 3.2.3版本废弃
	// 'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
	// 'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
	// 'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
	// 'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
	// 'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
	// 'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
	// 'DB_SQL_BUILD_CACHE'    =>  false, // 数据库查询的SQL创建缓存 3.2.3版本废弃
	// 'DB_SQL_BUILD_QUEUE'    =>  'file',   // SQL缓存队列的缓存方式 支持 file xcache和apc 3.2.3版本废弃
	// 'DB_SQL_BUILD_LENGTH'   =>  20, // SQL缓存的队列长度 3.2.3版本废弃
	// 'DB_SQL_LOG'            =>  false, // SQL执行日志记录 3.2.3版本废弃
	// 'DB_BIND_PARAM'         =>  false, // 数据库写入数据自动参数绑定
	// 'DB_DEBUG'              =>  false,  // 数据库调试模式 3.2.3新增
	// 'DB_LITE'               =>  false,  // 数据库Lite模式 3.2.3新增
	'TOKEN_ON' => true, // 是否开启令牌验证 默认关闭
	'TOKEN_NAME' => '__hash__', // 令牌验证的表单隐藏字段名称，默认为__hash__
	'TOKEN_TYPE' => 'md5', //令牌哈希验证规则 默认为MD5
	'TOKEN_RESET' => true, //令牌验证出错后是否重置令牌 默认为true
	'wxoptions' => array(
		'token' => '3c3435ccd5cf23a3aaee891c8982814b', //填写你设定的key
		'encodingaeskey' => 'Yw0E5h5CIiceQJekjTiB3ob8jQrRiSn53q3H6U2AOz6', //填写加密用的EncodingAESKey
		'appid' => 'wx1a8fbf2b1083d924', //填写高级调用功能的app id
		'appsecret' => 'de9e90bc2b77719a7bf42df108b8a090', //填写高级调用功能的密钥
	),

	'server_host' => 'http://letsktv.chinacloudapp.cn', //服务器的网址
);