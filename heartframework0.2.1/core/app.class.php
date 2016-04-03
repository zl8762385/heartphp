<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  app.class.php  应用类 框架入口
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class app {

	/**
	 * 运行application
	 * @return
	 */
	static public function run() {
		//初始化设置
		self::init_set();

		//变量设置
		self::init_vars();

		//绑定初始化处理方法
		self::init_handle();

		//执行调度
		dispatcher::dispatch();

		//new base_hooks
		$Hooks = new base_hooks();

		//hooks 在controller运行前执行 
		$Hooks->load_hooks('controller_top');

		//过滤一些
		self::filter_file();

		//hooks 在controller运行后执行
		$Hooks->load_hooks('controller_bottom');

        //trace debug
        //output_trace();
	}

	static public function filter_file() {
		//过滤根目录一些文件，因为如果应用到框架就会影响路由正确读取
		if(substr($_SERVER['PHP_SELF'], -7) == 'api.php') return false;

		//应用到项目
		if(php_sapi_name() != 'cli') {//命令行模式不直接应用到项目，也就是不执行controller files
			self::run_application();
		}
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
	}

	/**
	 * 初始化处理方法
	 */
	static public function init_handle() {
		spl_autoload_register(array('app', 'autoload_handle'));//autoload 自动include
	}

	/**
	 * 自动加载
	 * @return bool
	 */
	static public function autoload_handle($classname) {
		$conf = C();//获取配置文件
		$libsclass = ' template, cookie, log, form, page, tree, upload, cache';

		if(substr($classname, 0,7) == 'helper_') {//助手
			$helper_path = $conf['helper_path'].$classname.PHPFILE_EXT;
			
			if(is_file($helper_path)) {
				require $helper_path; 
				return class_exists($classname,false);
			} else {
				core::show_error("<span style='color:red;'>{$classname}.php</span> 没找到.");
			}
		} elseif(substr($classname, 0,5) == 'base_') {//核心基础base
			if(is_file(HEART_FRAMEWORK_CORE.$classname.PHPCLASS_EXT)) {
				require HEART_FRAMEWORK_CORE.$classname.PHPCLASS_EXT;
				return class_exists($classname,false);
			}
		} elseif(substr($classname, 0,3) == 'db_'){//database
			if(is_file(HEART_FRAMEWORK_CORE.'db_drive/'.$classname.PHPCLASS_EXT)) {
				require HEART_FRAMEWORK_CORE.'db_drive/'.$classname.PHPCLASS_EXT;
				return class_exists($classname,false);
			}
		} elseif(substr($classname, 0,6) == 'cache_'){//缓存
			if(is_file(HEART_FRAMEWORK_CORE.'cache_drive/'.$classname.PHPCLASS_EXT)) {
				require HEART_FRAMEWORK_CORE.'cache_drive/'.$classname.PHPCLASS_EXT;
				return class_exists($classname,false);
			}
		} elseif(substr($classname, -10, strlen($classname)) === 'Controller') {//跨目录访问 结尾必须大写Controller
			if(is_file(SYSTEM_PATH.'application/controller/'.$classname.PHPFILE_EXT)) {
				return A($classname);
			}
		}

		if($s = strpos($libsclass, $classname)) {//核心库libs 需要在上面定义 满足条件才可以加载
			$libs_path = HEART_FRAMEWORK.'libs/'.$classname.PHPCLASS_EXT;
			if(is_file($libs_path)) {
				require $libs_path;
				return class_exists($classname, false);
			}
		}

		return true;
		
	}


	/**
	 * 运行实例 controller
	 * @return 
	 */
	static public function run_application() {
		$conf = C();//获取配置文件

		//设置路由
		$router = new base_router();
		$directory = $router->get_directory();
		$control = $router->get_control();
		$action = $router->get_action();

		$control_name = $control.'Controller';
		if(!empty($conf['controller_path']) && isset($conf['controller_path'])) {
			$paths = $conf['controller_path'];

			foreach($paths as $path) {
				$control_file = $path.$directory.$control_name.PHPFILE_EXT;

				if(is_file($control_file)) {
					break;
				} else {
					$control_file = '';
				}
			}
			if(!empty($control_file)) {
				require $control_file;
				$newcontrol = new $control_name();
				//method_exists($newcontrol, $action) && $newcontrol->$action();
				
				if(method_exists($newcontrol, $action)) {
					call_user_func(array($newcontrol, $action));
				} else {
					core::show_error("{$control_name}->{$action}(); 您访问的方法不存在.");
				}
			} else {
				core::show_error("您当前URL不正确或文件不存在，请检查！");
			}
		}

		unset($control, $action, $newcontrol, $control_file);
	}

}
?>
