<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');

//menu数据表
class admin_menu_model extends base_model {
	public $table_name = 'menu';//表名
	public $db_setting = 'heartphp';//DB设置 读取配置文件的键值
	public $table_prefix = 'heart_';//前缀

	public function __construct() {
		global $conf;
		parent::__construct($conf);
	}

	/**
	 * 菜单JSON格式
	 */
	public function menu_json() {
		$data = $this->db->select('id, parentid, name', $this->table_name);
		return json_encode($data);
	}

	/**
	 * 获取用户组数据，并以ID为键值
	 * @return array
	 */
	public function menulists() {
		$data = $newdata = array();
		$data = $this->select('*');
		foreach($data as $k => $v) {
			$newdata[$v['id']] = $v;
		}

		return $newdata;
	}
}