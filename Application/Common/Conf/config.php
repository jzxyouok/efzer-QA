<?php
return array(
	// 活动名称
	'ACTIVITY_NAME' => '二附中人问答挑战',
	// 活动主办者
	'ACTIVITY_HOLDER' => '华东师大二附中校友联络会',
	// 管理员密码
	'ADMIN_PASSWORD' => 'admin',
	// Cookie前缀
	'COOKIE_PREFIX'         =>  'efzer_qagame_',

	// 数据库设置
	'DB_TYPE' => 'mysql', //数据库类型
	'DB_HOST' => 'localhost', //数据库地址
	'DB_NAME' => '', // 数据库名
	'DB_USER' => '', // 用户名
	'DB_PWD' => '', // 密码
	'DB_PORT' => 3306, // 端口
	'DB_PREFIX' => '', // 数据库表前缀
	'DB_CHARSET' => 'utf8',

	/*
	 下列设置请勿随意调节
	 配置参考：http://document.thinkphp.cn/manual_3_2.html#config_reference
	*/
	'URL_MODEL'             =>  2,
	'URL_HTML_SUFFIX'       =>  '',
	'TMPL_TEMPLATE_SUFFIX'  =>  '.html',
	'DB_TYPE'               =>  'mysql',
	'SHOW_ERROR_MSG'        =>  true,
	'MODULE_DENY_LIST'=>  array('Common','Runtime'),
	'MULTI_MODULE'          =>  true,
	'DEFAULT_MODULE'        =>  'Player',
);
