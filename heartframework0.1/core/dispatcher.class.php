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
	 * http://heartphp.com/index/index?id=12   PATH_INFO【正常模式】
	 * http://heartphp.com/admin/index/test?id=12 PATH_INFO【在controller下的目录admin】
	 * http://heartphp.com/index.php?c=index&m=test&id=12   GET
	 * http://heartphp.com/index.php?d=admin&c=index&m=test&id=12 GET
	 * 调度处理
	 */
	static public function dispatch() {
		global $conf;
		$get = &$_GET;

		$_conf = $conf['path_info'];
		if($_conf) {//处理PATH_INFO

			dispatcher::pathinfo_handle($get);
		}
		$get['c'] = isset($get['c']) && preg_match("/^\w+$/", $get['c']) ? strtolower($get['c']) : 'index' ;
		$get['m'] = isset($get['m']) && preg_match("/^\w+$/", $get['m']) ? strtolower($get['m']) : 'index' ;
	}

	/**
	 * @param [array] [$get] [$_GET]
	 * @return 
	 */
	static public function pathinfo_handle(&$get){
		!isset($get['c']) && $get['c'] = 'index';
		!isset($get['m']) && $get['m'] = 'index';	
		$pathinfo_uri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : "/{$get['c']}/{$get['m']}" ;

		$pathinfo = explode('/', $pathinfo_uri);
		unset($pathinfo[0]);
		
		if(count($pathinfo) == 3) {//用来支持模块目录
			$get['d'] = $pathinfo[1];//模块目录
			$get['c'] = $pathinfo[2];//controller 控制器
			$get['m'] = $pathinfo[3];//action 实际操作方法
		} else {
			$get['c'] = $pathinfo[1];//controller 控制器
			$get['m'] = $pathinfo[2];//action 实际操作方法
		}
		
	}

}