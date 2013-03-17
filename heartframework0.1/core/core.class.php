<?php
/**
 *  core.class.php   基础核心类【用来提供各种静态方法】
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang
 * @lastmodify			2013.03.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class core {
	/**
	 * 获取CONF指定KEY
	 */
	static public function get_conf($appname) {
		global $conf;
		if(isset($conf[$appname])) {
			return $conf[$appname];
		} else {
			return NULL;
		}
	}

	/**
	 * 获取类名
	 */
	static public function get_model_name() {
		$get['m'] = $_GET['m'];
		return $get['m'];
	}

	/**
	 * 获取实际操作方法名
	 */
	static public function get_action_name() {
		$get['c'] = $_GET['c'];
		return $get['c'];
	}
}