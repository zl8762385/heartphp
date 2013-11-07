<?php
/**
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:979314>
 * @lastmodify			2013.03.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
// define('IS_HEARTPHP', TRUE);
// define('SYSTEM_PATH', str_replace('\\', '/', substr(__FILE__, 0, -7)).DIRECTORY_SEPARATOR);
// define('HEART_FRAMEWORK', SYSTEM_PATH.'../heartframework0.2/');//框架的物理路径

// if(!($conf = include(SYSTEM_PATH.'conf/system.php'))){
// 	echo '全局配置文件不存在，请仔细检查.';
// 	exit;
// }

// include HEART_FRAMEWORK.'base.php';//加载框架入口文件
// app::run();

// $op = isset($_GET['op']) && trim($_GET['op']) ? trim($_GET['op']) : exit('Operation can not be empty.');
// if (!preg_match('/([^a-z_]+)/i',$op) && file_exists(SYSTEM_PATH.'api/'.$op.'.php')) {
// 	include SYSTEM_PATH.'api/'.$op.'.php';
// } else {
// 	exit('API handler does not exist~');
// }
$str = "/您在芭乐([a-z0-9xa1-xff]+)的注册验证码为：([0-9]+)，验证码有效期为5分钟。/i";
preg_match($str, '您在芭乐艺人红人的注册验证码为：897654，验证码有效期为5分钟。', $m);
print_r($m);