<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');

//内容数据表
class content_model extends base_model {
	public $table_name = 'content';//表名
	public $db_setting = 'heartphp';//DB设置 读取配置文件的键值
	public $table_prefix = 'heart_';//前缀

	public function __construct() {
		global $conf;
		parent::__construct($conf);
	}

	/**
	 * 设置模型ID 切换表
	 * @param $mid 模型ID
	 */
	public function set_model($m_id){
		if(empty($m_id)) return false;

		$model_db = D('admin_model_model');
		$data = $model_db->get_one($m_id);
		if(empty($data)) $this->table_name = '';

		$this->set_table($data['table_name']);
	}
	/**
	 * 创建数据表
	 * @param name tablename
	 * @param $engine 存储类型
	 * @param $charset 编码
	 * @return create table
	 */
	public function create_table($name, $engine = 'myisam', $charset = 'utf8') {
		if(empty($name)) return false;

		$sql="create table ".$this->table_prefix.$name." (
				id int not null auto_increment primary key,
				status tinyint(4) not null default '0' comment '[-1=>删除,0=>禁止, 1=>通过]',
				createtime int not null default '0',
				updatetime int not null default '0',
				operate varchar (50) not null default '0' comment '操作者'
			) engine={$engine} charset={$charset};";

		$rt = $this->db->execute($sql);
		if($rt) {
			/* 暂时不添加 副表 _data,后续开发
			$sql ="create table ".$this->table_prefix.$name."_data (
				id int not null auto_increment primary key,
				gid int not null default '0' comment '关联ID'
			) engine={$engine} charset={$charset}";
			
			return $this->db->execute($sql);
			*/
			return true;
		}

		return false;
	}

	/**
	 * 修改表
	 * @return [type] [description]
	 */
	public function change_table($table, $target_table) {
		$sql = "alter table {$this->table_prefix}{$table} rename {$this->table_prefix}{$target_table}";
		$rt = $this->db->execute($sql);
		if($rt) {
			/* 暂时不添加 副表 _data,后续开发
			$sql = "alter table {$this->table_prefix}{$table}_data rename {$this->table_prefix}{$target_table}_data";
			return $this->db->execute($sql);
			*/
			return true;
		}

		return false;
	}

	/**
	 * 操作
	 * @param $table 表名
	 * @param $type 类型
	 */
	public function column_action($table, $type, $column_info) {
		if(empty($table) || empty($type)) return false;
		$tablename = $this->table_prefix.$table;
		switch($type) {
			case 'add':
				$sql = "alter table {$tablename} add column {$column_info}";
			break;
			case 'change':
				$sql = "alter table {$tablename} change {$column_info}";
			break;
			case 'drop':
				$sql = "alter table {$tablename} drop column {$column_info}";
			break;
		}
		
		return $this->db->execute($sql);
	}
}