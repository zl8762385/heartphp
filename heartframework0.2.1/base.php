<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  base.php 框架入口文件
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:979314>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
date_default_timezone_set('PRC');//清除时间差

define('HEARTPHP_KEY', '!@#%&GGRE$%^&*()OLKJHGT');//默认密钥 用于加密


//公用常量
if(isset($_SERVER['HTTP_REFERER'])) define('HTTP_REFERER', $_SERVER['HTTP_REFERER']);//referer
if(!defined('DEBUG')) define('DEBUG', TRUE);

if(DEBUG) {
	include HEART_FRAMEWORK.'core/core.class.php';//一些核心的不太常用的静态方法
	if(file_exists(SYSTEM_PATH.'functions/global.func.php')) {
		include SYSTEM_PATH.'functions/global.func.php';//每个项目都有一个公用函数库
	}
	
	include HEART_FRAMEWORK.'functions/global.func.php';//加载核心的函数库
	include HEART_FRAMEWORK.'core/dispatcher.class.php';//路由
	include HEART_FRAMEWORK.'core/app.class.php';//应用入口 控制各种入口分发

} else {
	$content = '';
	$runtimefile = SYSTEM_PATH.'data/_runtime.php';
	if (!is_file($runtimefile)) {
		
		$content .= php_strip_whitespace(HEART_FRAMEWORK.'core/core.class.php');
		$content .= php_strip_whitespace(HEART_FRAMEWORK.'functions/global.func.php');
		$content .= php_strip_whitespace(HEART_FRAMEWORK.'core/dispatcher.class.php');
		$content .= php_strip_whitespace(HEART_FRAMEWORK.'core/app.class.php');
		file_put_contents($runtimefile, $content);
		unset($content);		
	}

	//function 不写入runtime里
	if(file_exists(SYSTEM_PATH.'functions/global.func.php')) {
		include SYSTEM_PATH.'functions/global.func.php';//每个项目都有一个公用函数库
	}
	
	include $runtimefile;
}
