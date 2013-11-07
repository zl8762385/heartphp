<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');

//管理员数据表
class attachment_model extends base_model {
	public $table_name = 'attachment';//表名
	public $db_setting = 'heartphp';//DB设置 读取配置文件的键值
	public $table_prefix = 'heart_';//前缀

	public function __construct() {
		global $conf;
		parent::__construct($conf);
	}
}