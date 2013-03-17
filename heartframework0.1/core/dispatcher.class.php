<?php
/**
 *  dispatcher.class.php   URL映射到控制器【目前仅支持URL？模式和PATHINFO模式】
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:979314>
 * @lastmodify			2013.03.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class dispatcher {

	/**
	 * http://heartphp.com/index/index?id=12   PATH_INFO
	 * http://heartphp.com/index.php?m=index&c=index&id=12   GET
	 * 调度处理
	 */
	static public function dispatch() {
		global $conf;
		$get = &$_GET;

		$_conf = $conf['path_info'];
		if($_conf) {//处理PATH_INFO
			dispatcher::pathinfo_handle($get);
		}
		$get['m'] = isset($get['m']) && preg_match("/^\w+$/", $get['m']) ? strtolower($get['m']) : 'index' ;
		$get['c'] = isset($get['c']) && preg_match("/^\w+$/", $get['c']) ? strtolower($get['c']) : 'index' ;
	}

	/**
	 * 处理pathinfo
	 */
	static public function pathinfo_handle(&$get){
		$pathinfo_uri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : "/{$get['m']}/{$get['c']}" ;
		$pathinfo = explode('/', $pathinfo_uri);
			
		$get['m'] = $pathinfo[1];
		$get['c'] = $pathinfo[2];
	}

}