<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  base_model.class.php   database model class
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang
 * @lastmodify			2013.03.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class base_model {
	protected $conf = '';//database config
	protected $table_name = '';

	public function __construct() {
		global $conf;
		$this->conf = $conf['db'];
		//echo $this->table_name;
	}

	public function __call($method, $params){
		core::show_error("base_mode.class.php : {$method}方法不存在");
	}
}