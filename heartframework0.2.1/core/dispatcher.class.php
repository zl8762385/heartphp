<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  dispatcher.class.php   URL映射到控制器【目前仅支持URL？模式和PATHINFO模式】
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:979314>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class dispatcher {

	/**
	 * http://heartphp.com/index/index?id=12   PATH_INFO【正常模式】
	 * http://heartphp.com/admin/index/test?id=12 PATH_INFO【在controller下的目录admin】
	 * http://heartphp.com/index.php?c=index&a=test&id=12   GET
	 * http://heartphp.com/index.php?d=admin&c=index&a=test&id=12 GET
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
		$get['a'] = isset($get['a']) && preg_match("/^\w+$/", $get['a']) ? strtolower($get['a']) : 'index' ;

		//设置常量 目录__D__   控制器__C__ 方法__A__
		if(isset($_GET['d'])) define('__D__', $_GET['d']);
		if(isset($_GET['c'])) define('__C__', $_GET['c']);
		if(isset($_GET['a'])) define('__A__', $_GET['a']);

		//empty($get) && core::show_error('您没有设置PATHINFO 请在配置文件中设置.');
	}

	/**
	 * @param $get $_GET 
	 * @return 
	 */
	static public function pathinfo_handle(&$get){
		!isset($get['c']) && $get['c'] = 'index';
		!isset($get['a']) && $get['a'] = 'index';

		

		$path_into = self::pathinfo_bug_handle();
		$pathinfo_uri = !empty($path_into) ? $path_into : "/{$get['c']}/{$get['a']}" ;

		$pathinfo = explode('/', $pathinfo_uri);
		unset($pathinfo[0]);
		
		//如果没有匹配到PATHINFO 那默认就是正常GET模式
		if(count($pathinfo) == 3) {//用来支持模块目录
			$get['d'] = $pathinfo[1];//模块目录
			$get['c'] = $pathinfo[2];//controller 控制器
			$get['a'] = $pathinfo[3];//action 实际操作方法
		} else if(count($pathinfo) == 2) {
			$get['c'] = $pathinfo[1];//controller 控制器
			$get['a'] = $pathinfo[2];//action 实际操作方法
		}

	}

	/**
	 * 处理pathinfo模式在其他服务器获取不到值 BUG
	 * @param $get $_GET 
	 * @return 
	 */
	static public function pathinfo_bug_handle() {
		if(isset($_SERVER['PATH_INFO'])) return $_SERVER['PATH_INFO'];
		if(isset($_SERVER['ORIG_PATH_INFO'])) return $_SERVER['ORIG_PATH_INFO'];
		
		return false;
	}

}
?>