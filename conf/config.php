<?php
/**
 *  index.php 配置文件
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang
 * @lastmodify			2013.03.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

return array(
	'db' => array(
		'type' => 'mysql',//数据库类别 默认选中MYSQL 如果想切换其他数据库 请更改TYPE
		'mysql' => array(
			'www' => array(
				'host' => 'localhost',
				'user' => 'root',
				'password' => 'v90v70v00',
				'databse_name' => 'test',
				'tablepre' => 'heart_',
				'engine' => 'MyISAM'
			),
			'new_database' => array(
				'host' => 'localhost',
				'user' => 'root',
				'password' => 'v90v70v00',
				'databse_name' => 'test',
				'tablepre' => 'heart_',
				'engine' => 'MyISAM'
			)
		)
	),
	//模板
	'view_path' => array(SYSTEM_PATH.'tpl/'),
	//数据模型
	'model_path' => SYSTEM_PATH.'lib/model/',
	//控制器
	'controller_path' => array(SYSTEM_PATH.'lib/controller/'),
	//助手
	'helper_path' => SYSTEM_PATH.'lib/helper/',
	//日志
	'log_path' => array(SYSTEM_PATH.'data/log/'),
	//插件
	'plugin_path' => array(SYSTEM_PATH.'plugin/'),
	//静态文件
	'statics_path' => array(SYSTEM_PATH.'statics/'),
	'path_info' => true, //开启pathinfo  {true:开启, false:关闭}

	//配置模板信息
	'template_config' => array(
		'template_suffix'    => '.html',
		'template_path'      => SYSTEM_PATH.'tpl/',
		'template_c'         => SYSTEM_PATH.'data/template_c/'
	),

	//
	'debug' => array(),

);