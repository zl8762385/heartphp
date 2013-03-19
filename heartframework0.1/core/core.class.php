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
	static public function get_directory_name() {
		$get['d'] = (!empty($_GET['d']) && isset($_GET['d'])) ? $_GET['d'] : '' ;
		return $get['d'];
	}

	/**
	 * 获取类名
	 */
	static public function get_model_name() {
		$get['c'] = $_GET['c'];
		return $get['c'];
	}

	/**
	 * 获取实际操作方法名
	 */
	static public function get_action_name() {
		$get['m'] = $_GET['m'];
		return $get['m'];
	}

	/**
	 * 递归创建目录
	 */	
	static public function mkdirs($path, $mode = 0777) {
		$dir_arr = explode('/', $path);
		$dir_arr = array_filter($dir_arr);
		print_r($dir_arr);
	}
	/**
	 * 错误
	 */	
	static function show_error($content){
		$stringHtml = '<html xmlns="http://www.w3.org/1999/xhtml">';
		$stringHtml .= '<head>';
		$stringHtml .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		$stringHtml .= '<title>错误信息</title>';
		$stringHtml .= '</head><body>';
	    $stringHtml .= '<div style="width:780px;height:auto;padding:10px;border:1px solid #CCC;margin:0 auto;">';
	    $stringHtml .= 'Error information:<br />';
	    $stringHtml .= '<font color="red">';
	    $stringHtml .= $content;
	    $stringHtml .= '</font>';
	    $stringHtml .= '</div></body></html>';
	    exit($stringHtml);
	}
}