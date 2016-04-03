<?php
/**
 *  index.php 入口文件
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:3677989>
 * @lastmodify			2013.03.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

define('IS_HEARTPHP', TRUE);
define('SYSTEM_PATH', str_replace('\\', '/', substr(__FILE__, 0, -9)));
define('HEART_FRAMEWORK', SYSTEM_PATH.'./heartframework0.2.1/');//框架的物理路径
define('DEBUG', false);//开启DEBUG模式  模板引擎会一直处于编译状态，建议上线后DEBUG改成0

//加载框架入口文件
include HEART_FRAMEWORK.'heart.php';
