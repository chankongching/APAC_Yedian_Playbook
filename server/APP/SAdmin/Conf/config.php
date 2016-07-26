<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE' => 'mysql', // 数据库类型
	'DB_HOST' => 'localhost', // 服务器地址
	'DB_NAME' => 'abicloud', // 数据库名
	'DB_USER' => 'website', // 用户名
	'DB_PWD' => 'WebSite456', // 密码
	'DB_PORT' => '3306', // 端口
	'DB_PREFIX' => 'ac_', // 数据库表前缀

	//    RBAC配置
	'USER_AUTH_ON' => true, // USER_AUTH_ON 是否需要认证
	'USER_AUTH_TYPE' => 1, // USER_AUTH_TYPE 认证类型
	'USER_AUTH_KEY' => 'rbacid', // USER_AUTH_KEY 认证识别号
	'USER_AUTH_MODEL' => 'sjb_user',
	// REQUIRE_AUTH_MODULE  需要认证模块
	'NOT_AUTH_MODULE' => 'Public', // NOT_AUTH_MODULE 无需认证模块
	'USER_AUTH_GATEWAY' => 'Public/login', // USER_AUTH_GATEWAY 认证网关
	// RBAC_DB_DSN  数据库连接DSN
	'RBAC_ROLE_TABLE' => 'ac_sjb_role', // RBAC_ROLE_TABLE 角色表名称
	'RBAC_USER_TABLE' => 'ac_sjb_role_user', // RBAC_USER_TABLE 用户表名称
	'RBAC_ACCESS_TABLE' => 'ac_sjb_access', // RBAC_ACCESS_TABLE 权限表名称
	'RBAC_NODE_TABLE' => 'ac_sjb_node', // RBAC_NODE_TABLE 节点表名称
	'RBAC_SUPERADMIN' => 'admin',
	'ADMIN_AUTH_KEY' => 'adminkey',
	'RBAC_ERROR_PAGE' => 'Public/Login_error',
);