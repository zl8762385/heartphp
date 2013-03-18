<?php
/**
 *  baseControl.php   控制器基础类
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang
 * @lastmodify			2013.03.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class template {
	private $view = array();

	public function __construct($conf) {
	
	}

	public function assign($key, $value) {
		$this->view[$key] = $value;
	}

	public function display() {
		echo core::get_model_name();
		print_r($this->view);
	}
}