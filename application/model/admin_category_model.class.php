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

	public function category_all_json() {
		$data = $this->db->select('id, parentid, name', $this->table_name);
		$newdata = array();
		foreach($data as $k => $v) {
			$url = get_url('admin', 'content', 'content_list', "catid={$v['id']}");
			$newdata[$k] = $v;
			$newdata[$k]['open'] = 'true';
			$newdata[$k]['url'] = $url;
			$newdata[$k]['target'] = 'right';
		}
	
		return json_encode($newdata);
	}
}