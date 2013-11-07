<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  base_controller.class.php   控制器基础类
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:979314>  
 * @lastmodify			2013.07.13
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class base_hooks {
	//是否启动
	private $enable = false;

	//hooks 路径
	private $hooks_path;

	//hooks 配置文件路径
	private $hooks_config_path;

	//hooks 配置文件数据
	private $hooks_config_data = array();

	//hooks 后缀
	public $class_postfix = 'Hooks';

	//hook 静态变量
	private static $hook_instance  = array();

	//位置偏移，设置核心框架中在什么地方加载hooks 
	private $offset = array(
						'controller_top', //系统中执行，控制器执行前
						'controller_bottom',//系统中执行，控制器执行后
						'plugin'//插件模式 不在系统中运行，可以在任何地方调用
					);

	public function __construct() {
		global $conf;
		$_conf =& $conf['hooks'];
		$this->enabled = $_conf['enable'];
		$this->hooks_path = $_conf['path'];
		$this->hooks_config_path = $_conf['config'];
		$this->class_postfix = (isset($_conf['class_postfix']) && !empty($_conf['class_postfix'])) ? $_conf['class_postfix'] : $this->class_postfix ;
		$this->load_cfg();
	}

	/**
	 * 加载hooks config
	 * @return 
	 */
	public function load_cfg() {
		if(is_file($this->hooks_config_path.'hook.config.php')) {
			@include $this->hooks_config_path.'hook.config.php';
			$this->hooks_config_data = $hook;
		}
	}

	/**
	 * 加载hooks 根据位置来定位hook在啥地方,默认开启插件模式
	 * @param  string $offset 位置偏移 与本类$offset做验证 
	 * @param  string $hook 钩子名
	 * @param  string $args 参数
	 * @return 
	 */
	public function load_hooks($offset = 'plugin', $hooks = '', $args = '') {
		//检查加载位置是否在本类配置中
		if(!in_array($offset, $this->offset)) return false;

		//检查是否开启插件模式
		if(!$this->enabled) return false;
		
		//查看用户配置文件中是否定义了 需要执行钩子的位置
		if(isset($this->hooks_config_data[$offset])) {

			//如果没有配置钩子相关二维数据 return false;
			if(empty($this->hooks_config_data[$offset]) || count($this->hooks_config_data[$offset]) <= 0) return false;

			$args || $args = '';

			//找指定钩子去.
			$this->find_hook($offset, $this->hooks_config_data[$offset], $hooks, $args);
			//print_r($this->hooks_config_data[$offset]);
			//echo " $hooks $offset <br/>";
		}

	}

	/**
	 * 找指定位置的钩子去
	 * @param  string $offset 位置
	 * @param  array $offset_data 指定位置的钩子数据
	 * @param  string $hooks 钩子名
	 * @param  string $args 参数
	 * @return 
	 */
	public function find_hook($offset, $offset_data, $hooks, $args = '') {
		//找到钩子
		foreach($offset_data as $hooks_name => $v) {
			
				//遍历钩子集合
				foreach($offset_data[$hooks_name] as $hk => $hv) {
					if($offset == 'plugin' && $hooks_name == $hooks) {
						//找到要执行的钩子
						$this->run_hook($hv, $args);
					} else {
						$this->run_hook($hv, $args);
					}
				}
		}		
	}

	/**
	 * 运行HOOKS
	 * @param  array $data hooks类名和函数名 
	 * @param  string $args 参数 
	 * @return
	 */
	public function run_hook($data, $args = '') {
		if(!is_array($data)) return false;
		list($classname, $func) = $data;
		$classname = strtolower($classname);

		$hooks_path = $this->hooks_path.$classname.$this->class_postfix.'.php';
		if(is_file($hooks_path)) {//hooks类文件存在 执行

			$_class = $classname.$this->class_postfix;
			if(isset(self::$hook_instance[$_class])) {//重复的类名 第二次就会执行这里
				if(method_exists(self::$hook_instance[$_class], $func)) {
					call_user_func(array(self::$hook_instance[$_class],$func), $args);
				}
			} else {//避免重复new相同的类   这里使用的单例
				if(!class_exists($_class, false)) core::_require($hooks_path);

				self::$hook_instance[$_class] = new $_class();
				if(method_exists(self::$hook_instance[$_class], $func)) {
					call_user_func(array(self::$hook_instance[$_class],$func),$args);
				}
			}
		}
	}

	/**
	 * 结束 注销相关变量
	 */
	public function __destruct() {
		//这里因为结束注销时 导致hook_instance 重新注册了  悲催小问题，暂时用class_exists解决了
		self::$hook_instance = null;

		//注销其他数据
		$this->class_postfix = $this->hooks_config_path = $this->hooks_path = $this->enabled = $this->hooks_path = null;
	}
}