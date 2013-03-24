<?php
/**
 *  base.php 框架入口文件
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang
 * @lastmodify			2013.03.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
date_default_timezone_set('PRC');//清除时间差

if(!defined('DEBUG')) {
	define('DEBUG', 1);
}


if(DEBUG) {
	
	include HEART_FRAMEWORK.'core/dispatcher.class.php';
	include HEART_FRAMEWORK.'core/core.class.php';
	include HEART_FRAMEWORK.'core/app.class.php';
	


} else {
	include HEART_FRAMEWORK.'core/dispatcher.class.php';
	include HEART_FRAMEWORK.'core/core.class.php';
	include HEART_FRAMEWORK.'core/app.class.php';
	//echo '非调试状态，留位置，以后把所有类继承到一个文件里';
}