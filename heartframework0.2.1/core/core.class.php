<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  core.class.php   基础核心类【用来提供各种静态方法】
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class core {
	static public $_instance = array();//防止重复加载

	/**
	 * @param [string] [$info] [调试信息]
	 * @param [string] [$type] [type]
	 * @return
	 */
	static public function debug($type, $info = '') {
		if(DEBUG) {
			switch ($type) {
				case 'sqls':
					array_push($_SERVER['sqls'], $info);//sql
				break;
				case 'show':
					echo '<pre>';
					print_r($_SERVER['class_model']);
					echo '</pre>';
				break;
				default:
					# code...
					break;
			}
		}
	}

	/**
	 * hooks 静态方法
	 * @param  string $hooks_name 钩子名
	 * @param  string $args 参数
	 * @param  string $type hooks类型 默认插件模式
	 * @return 
	 */
	static public function hooks($hooks_name, $args = '',$type="plugin") {

		$hooks = new base_hooks();
		$hooks->load_hooks($type, $hooks_name, $args);
	}

	/**
	 * 核心部分错误提示 HTML，这个方法不做美化了。
	 * 给用户重新美化一个方法
	 * @param $content 信息
	 * @param $title 标题
	 * @return 
	 */	
	static function show_error($content, $title="错误提示"){
		include HEART_FRAMEWORK_TPL.'error.tpl.php';
		exit;
	}
}
?>
