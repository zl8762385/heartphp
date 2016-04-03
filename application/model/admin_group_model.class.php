<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');

//group数据表
class admin_group_model extends base_model {
	public $table_name = 'admin_group';//表名
	public $db_setting = 'heartphp';//DB设置 读取配置文件的键值
	public $table_prefix = 'heart_';//前缀

	public function __construct() {
		global $conf;
		parent::__construct($conf);
	}

	/**
	 * 获取用户组数据，并以ID为键值
	 * @return array
	 */
	public function grouplists() {
		$data = $newdata = array();
		$data = $this->select('*');
		foreach($data as $k => $v) {
			$newdata[$v['id']] = $v;
		}

		return $newdata;
	}
}