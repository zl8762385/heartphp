<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 路由分发 
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class base_router {
	private $config = array();//路由配置
	private $d = '';
	private $c = '';
	private $a = '';

	public function __construct() {
		$this->config = C('', 'route');
		//print_r($this->config);

		//设置路由分发
		$this->d = (defined('__D__')) ? __D__.'/' : '' ;
		$this->c = (defined('__C__')) ? __C__ : '' ;
		$this->a = (defined('__A__')) ? __A__ : '' ;

		$this->handle();
	}

	/**
	 * 处理路由分发
	 */
	public function handle() {

	}

	/**
	 * 目录
	 */
	public function get_directory(){
		return $this->d;
	}

	/**
	 * 控制器路由
	 */
	public function get_control(){
		return $this->c;
	}

	/**
	 * 方法路由
	 */
	public function get_action(){
		return $this->a;
	}
}
