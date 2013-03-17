<?php
/**
 *  app.class.php  应用类
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang
 * @lastmodify			2013.03.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class app {

	static public function init() {
		$_SERVER['starttime'] = microtime(1);
		$_SERVER['sqls'] = array(); //debug

		// 最低版本需求判断
		PHP_VERSION < '5.0' && exit('Required PHP version 5.0.* or later.');

		app::init_handle('index1Controller');
		dispatcher::dispatch();//执行调度
		print_r($_GET);
	}

	static public function init_handle($classname) {
		spl_autoload_register(array('app', 'autoload'));//autoload 自动include
		
	}

	/**
	 * new class不存在 自动加载
	 */
	static public function autoload($classname) {
		global $conf;
		echo $classname;
		
	}

	static public function run() {
		global $conf;
		$control = core::get_model_name();
		$action  = core::get_action_name();
	
		$control_name = $control.'Controller';
		if(!empty($conf['controller_path']) && isset($conf['controller_path'])) {
			$paths = $conf['controller_path'];

			foreach($paths as $path) {
				$control_file = $path.$control_name.'.php';
				if(is_file($control_file)) {
					break;
				} else {
					$control_file = '';
				}
			}

			if(@include $control_file) {
				$newcontrol = new $control_name();
				if(method_exists($newcontrol, $action)) {
					$newcontrol->$action();
				} else {
					throw new Exception('$action 不存在');
				}
			} else {
				throw new Exception("$control 不存在");
			}
		}

		unset($control, $action, $newcontrol, $control_file);

	}

}