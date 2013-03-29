<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');

class mypost_model extends base_model {
	public $table_name = 'mypost';//表名
	public $db_setting = 'www';//DB设置 读取配置文件的键值

	public function __construct() {
		parent::__construct();
	}
}