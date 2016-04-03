<?php
/**
 *  system.php 系统配置文件
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

return array(
	'db' => array(//数据库配置
		'type' => 'mysql',//数据库类别 默认选中MYSQL 如果想切换其他数据库 请更改TYPE
		'mysql' => array(
			'heartphp' => array(
				'host' => 'localhost',
				'user' => 'zl8762385_heart',
				'password' => 'v90v70v00',
				'db_name' => 'zl8762385_cms',
				'charset' => 'utf8',
				'table_prefix' => 'heart_',
				'engine' => 'MyISAM'
			)
		),
		'mssql' => array(
			'heartphp' => array(
				'host' => 'localhost',
				'user' => 'sa',
				'password' => 'v90v70v00',
				'db_name' => 'test',
				'table_prefix' => 'heart_',
			)
		)
	),
	'cache' => array(//缓存配置
		'cache_switch'=> true,//false=关 true=开
		'type'=> 'redis',//默认采用file [file, memcache, redis, xcache]
		'memcache'=> array (//memcache
			'host'=>'127.0.0.1',
			'port'=>'11211',
		),
		'redis' => array(//redis
			'host' => '127.0.0.1',
			'port' => '6379',
			'timeout' => '200'
		)

	),
	//模板
	'view_path' => array(SYSTEM_PATH.'tpl/'),
	//数据模型
	'model_path' => SYSTEM_PATH.'application/model/',
	//application
	'user_libs_path' => SYSTEM_PATH.'application/',
	//控制器
	'controller_path' => array(SYSTEM_PATH.'application/controller/'),
	//缓存路径
	'cache_path' => SYSTEM_PATH.'data/cache/',
	//助手
	'helper_path' => SYSTEM_PATH.'application/helper/',
	//日志
	'log_path' => SYSTEM_PATH.'data/log/',

	//开启pathinfo  {true:开启, false:关闭}
	'path_info' => true,

	//hooks插件机制配置
	'hooks' => array(
		'enable' => false,//是否开启hooks true or false
		'path' => SYSTEM_PATH.'application/hooks/',//hooks放置路径
		'config' => SYSTEM_PATH.'application/hooks/config/',//hooks挂载配置文件
		'class_postfix' => 'Hooks',//hooks后缀，例如：你的钩子名是 users,那么完整的就是 usersHooks.php
	),
	
	//配置模板信息
	'template_config' => array(
		'template_suffix'    => '.html',
		'template_path'      => SYSTEM_PATH.'tpl/',
		'template_c'         => SYSTEM_PATH.'data/template_c/',
		'template_label'     => SYSTEM_PATH.'functions/template_label/',//模板标签函数 路径
	)

);