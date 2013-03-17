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
	'db' => array(),
	//模板
	'view_path' => array(SYSTEM_PATH.'tpl/'),
	//数据模型
	'model_path' => array(SYSTEM_PATH.'lib/model/'),
	//控制器
	'controller_path' => array(SYSTEM_PATH.'lib/controller/'),
	//日志
	'log_path' => array(SYSTEM_PATH.'data/log/'),
	//插件
	'plugin_path' => array(SYSTEM_PATH.'plugin/'),
	//静态文件
	'statics_path' => array(SYSTEM_PATH.'statics/'),
	//临时目录
	'tmp_path' => array(SYSTEM_PATH.'tmp/'),
	'path_info' => '1' //开启pathinfo  {1:开启, 0:关闭}

);