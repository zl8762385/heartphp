<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  app.class.php  应用类 框架入口
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang
 * @lastmodify			2013.03.27
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class app {

	static public function init() {
		
		app::init_set();
		app::init_vars();
		app::init_handle();
		dispatcher::dispatch();//执行调度
	}

	/**
	 * app 初始化设置
	 */
	static public function init_set() {
		if(DEBUG) {
			error_reporting(E_ALL | E_STRICT);

			ini_set('display_error', 'ON');
		} else {
			error_reporting(E_ALL & ~E_NOTICE);
		}

		// 最低版本需求判断
		PHP_VERSION < '5.0' && exit('Required PHP version 5.0.* or later.');

		//关闭运行期间的自动反斜杠
		@set_magic_quotes_runtime(0);

	}

	/**
	 * 变量设置
	 */
	static public function init_vars() {
		$_SERVER['starttime'] = microtime(1);
		$_SERVER['sqls'] = array(); //debug
		$_SERVER['class_model']  = array();
	}

	/**
	 * 初始化处理方法
	 */
	static public function init_handle() {
		spl_autoload_register(array('app', 'autoload'));//autoload 自动include
		
	}

	/**
	 * new class不存在 自动加载
	 * @return bool
	 */
	static public function autoload($classname) {
		global $conf;
		$libsclass = ' template, cookie, log';
		$core_path = HEART_FRAMEWORK.'core/';
		if(substr($classname, 0,7) == 'helper_') {//助手
			$helper_path = $conf['helper_path'].$classname.'.php';
			if(is_file($helper_path)) {
				app::_require($helper_path);
				return class_exists($classname,false);
			} else {
				core::show_error("URL错误，或文件不存在");
			}
		} elseif(substr($classname, 0,5) == 'base_') {//核心基础base
			if(is_file($core_path.$classname.'.class.php')) {
				app::_require($core_path.$classname.'.class.php');
				return class_exists($classname,false);
			}
		}

		if($s = strpos($libsclass, $classname)) {//核心库libs 需要在上面定义 满足条件才可以加载
			$libs_path = HEART_FRAMEWORK.'libs/'.$classname.'.class.php';
			if(is_file($libs_path)) {
				app::_require($libs_path);
				return class_exists($classname, false);
			}
		}

		return true;
		
	}

	/**
	 * 封装include 对导入类文件进行统一管理
	 */
	static public function _require($file) {
		require $file;
		core::debug('class_name', $file);
	}

	/**
	 * 运行实例 controller
	 * @return 
	 */
	static public function run() {
		global $conf;

		$directory = (core::get_directory_name()) ? core::get_directory_name().'/' : '';
		$control = core::get_model_name();
		$action  = core::get_action_name();

		$control_name = $control.'Controller';
		if(!empty($conf['controller_path']) && isset($conf['controller_path'])) {
			$paths = $conf['controller_path'];

			foreach($paths as $path) {
				$control_file = $path.$directory.$control_name.'.php';

				if(is_file($control_file)) {
					break;
				} else {
					$control_file = '';
				}
			}

			if(!empty($control_file)) {
				app::_require($control_file);
				$newcontrol = new $control_name();
		
				(method_exists($newcontrol, $action)) ? $newcontrol->$action() : core::show_error("方法未实现 $action.");
			} else {
				core::show_error("您当前URL不正确，请检查！");
			}
		}

		core::debug('show');
		unset($control, $action, $newcontrol, $control_file);
	}

}