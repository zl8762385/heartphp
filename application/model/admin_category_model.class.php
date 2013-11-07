<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');

//栏目数据表
class admin_category_model extends base_model {
	public $table_name = 'category';//表名
	public $db_setting = 'heartphp';//DB设置 读取配置文件的键值
	public $table_prefix = 'heart_';//前缀

	public function __construct() {
		global $conf;
		parent::__construct($conf);
	}
}