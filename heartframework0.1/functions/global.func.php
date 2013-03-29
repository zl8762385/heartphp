<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');

/**
 * 只获取数据库MODEL
 * 
 */
function D($model) {
	if(empty($model)) core::show_error($model.'不存在');
	$model_path = get_conf('model_path');
	if(empty($model_path)) core::show_error('数据模块路径不正确，请检查配置文件！');

	$model_filepath = $model_path.$model.'.class.php';
	if(is_file($model_filepath)) {
		include $model_filepath;
		return new $model();
	}

}

/**
 * 获取conf配置信息
 * @param [string] [$k] [key]
 */
function get_conf($k) {
	global $conf;
	if(isset($conf[$k])) {
		return $conf[$k];
	} else {
		return NULL;
	}	
}