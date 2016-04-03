<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  base.php 框架入口文件
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2014.05.30
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('PRC');//清除时间差

define('PHPFILE_EXT', '.php');//php文件后缀
define('PHPCLASS_EXT', '.class.php');//php 类文件后缀
define('PHPFUNC_EXT', '.func.php');//php 函数文件后缀

defined('DEBUG') || define('DEBUG', TRUE);
defined('HEART_FRAMEWORK_TPL') || define('HEART_FRAMEWORK_TPL', HEART_FRAMEWORK.'tpl/');//框架中的核心模板文件路径  只放框架中的模板文件
defined('HEART_FRAMEWORK_CORE') || define('HEART_FRAMEWORK_CORE', HEART_FRAMEWORK.'core/');//框架中的核心文件夹路径
define('HTTP_REFERER', (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '' );//referer
define('HEARTPHP_KEY', '!@#%&GGRE$%^&*()OLKJHGT');//默认密钥 用于加密

if(DEBUG) {
	include HEART_FRAMEWORK_CORE.'core'.PHPCLASS_EXT;//一些核心的不太常用的静态方法
	if(file_exists(SYSTEM_PATH.'functions/global'.PHPFUNC_EXT)) {
		include SYSTEM_PATH.'functions/global'.PHPFUNC_EXT;//每个项目都有一个公用函数库
	}

	include HEART_FRAMEWORK.'functions/global'.PHPFUNC_EXT;//加载核心的函数库
	include HEART_FRAMEWORK_CORE.'dispatcher'.PHPCLASS_EXT;//调度
	include HEART_FRAMEWORK_CORE.'app'.PHPCLASS_EXT;//应用入口 控制各种入口分发

} else {
	$content = '';
	$runtimefile = SYSTEM_PATH.'data/_runtime'.PHPFILE_EXT;
	if (!is_file($runtimefile)) {

		$content .= php_strip_whitespace(HEART_FRAMEWORK_CORE.'core'.PHPCLASS_EXT);
		$content .= php_strip_whitespace(HEART_FRAMEWORK.'functions/global'.PHPFUNC_EXT);
		$content .= php_strip_whitespace(HEART_FRAMEWORK_CORE.'dispatcher'.PHPCLASS_EXT);
		$content .= php_strip_whitespace(HEART_FRAMEWORK_CORE.'app'.PHPCLASS_EXT);
		file_put_contents($runtimefile, $content);
		unset($content);		
	}

	//function 不写入runtime里
	if(file_exists(SYSTEM_PATH.'functions/global'.PHPFUNC_EXT)) {
		include SYSTEM_PATH.'functions/global'.PHPFUNC_EXT;//每个项目都有一个公用函数库
	}

	include $runtimefile;
}

//应用到项目
app::run();
