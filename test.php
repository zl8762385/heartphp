<?php
/**
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:979314>
 * @lastmodify			2013.03.17
 *
 * ����������ʹ�ø�Դ�룬������ʹ�ù����У��뱣��������Ϣ�����������Ͷ��ɹ����������Լ�
 */
// define('IS_HEARTPHP', TRUE);
// define('SYSTEM_PATH', str_replace('\\', '/', substr(__FILE__, 0, -7)).DIRECTORY_SEPARATOR);
// define('HEART_FRAMEWORK', SYSTEM_PATH.'../heartframework0.2/');//��ܵ�����·��

// if(!($conf = include(SYSTEM_PATH.'conf/system.php'))){
// 	echo 'ȫ�������ļ������ڣ�����ϸ���.';
// 	exit;
// }

// include HEART_FRAMEWORK.'base.php';//���ؿ������ļ�
// app::run();

// $op = isset($_GET['op']) && trim($_GET['op']) ? trim($_GET['op']) : exit('Operation can not be empty.');
// if (!preg_match('/([^a-z_]+)/i',$op) && file_exists(SYSTEM_PATH.'api/'.$op.'.php')) {
// 	include SYSTEM_PATH.'api/'.$op.'.php';
// } else {
// 	exit('API handler does not exist~');
// }
$str = "/���ڰ���([a-z0-9xa1-xff]+)��ע����֤��Ϊ��([0-9]+)����֤����Ч��Ϊ5���ӡ�/i";
preg_match($str, '���ڰ������˺��˵�ע����֤��Ϊ��897654����֤����Ч��Ϊ5���ӡ�', $m);
print_r($m);