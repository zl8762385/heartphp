<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 后台管理系统 model 模型管理
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.22
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class modelController extends helper_baseadminController {

	public function __construct(){
		parent::__construct();
		$this->db = D('admin_model_model');
		$this->db_filed = D('model_filed_model');
		$this->pagesize = 20;
		$filed_type = C('', 'filed_config');//获取字段配置文件
		$this->filed_settings_config = $filed_type['filed_type'];//字段配置文件 类型，大小
		$this->select_type_config = $filed_type['select_type'];//字段选择类型
		$this->check_pattern = $filed_type['check_pattern'];//验证 正则
	}

	/**
	 * 字段列表
	 */
	public function filed() {
		$m_id = gpc('id');
		
		$lists = $this->db_filed->select('*', 'm_id='.$m_id, '', 'orders asc');
		$this->view->assign('filed_type', $this->select_type_config);
	 	$this->view->assign("lists", $lists);
	 	$this->view->assign('m_id', $m_id);
		$this->view->display('filed');
	}

	/**
	 * 添加字段
	 */
	public function add_filed() {
		$id = gpc('id');
		$model_info = $this->db->get_one($id);
	
		if(gpc('dosubmit', 'R')){
			
			
			$data = gpc('data', 'P');
			$settings = gpc('settings', 'R');
			$m_id = gpc('m_id', 'R');
			$m_data = $this->db->get_one($m_id);

			$this->comm_check_data('_empty', $data['title'], '请输入标题.');
			$this->comm_check_data('_empty', $data['name'], '请输入字段名.');
			$this->comm_check_data('_empty', $data['type'], '请选择字段类型.');
			$this->check_filed($data['name'], $m_id);//检查字段是否存在
			//$this->comm_check_data('_empty', $data['verify_column'], '', '0');
			$this->comm_check_data('_empty', $data['description'], '', '0');

			if(!empty($settings)) $data['settings'] = json_encode($settings);
			if(!empty($data['number_range'])) $data['number_range'] = json_encode($data['number_range']);
			
			$data['m_id'] = $m_id;

			//过滤
			if(!preg_match("/^[a-zA-Z0-9_]+$/i", $data['name'])) {
				$this->show_message('字段名只允许数字和英文和下划线.');
			}

			if(!preg_match("/^[\x{4e00}-\x{9fa5}\w]+$/u", $data['description'])) {
				$this->show_message('描述不允许特殊字符,只可以是数字英文或汉字.');
			}
			$data['title'] = htmlspecialchars($data['title']);
			$data['name'] = htmlspecialchars($data['name']);
			$data['description'] = htmlspecialchars($data['description']);
			
			if($this->db_filed->insert($data)) {
				$filed_type = $this->filed_settings_config[$data['type']]['type'];

				//字段长度
				$filed_length = '';
				if(isset($settings['size']) && !empty($settings['size'])) {
					$filed_length = "({$settings['size']})";
				} else {
					if(isset($this->filed_settings_config[$data['type']]['length'])) {
						$filed_length = "({$this->filed_settings_config[$data['type']]['length']})";
					}
				}

				//如果填写了字段类型 则使用填写的数据
				if(isset($settings['filedtype'])) {
					$filed_arr = explode('-', $settings['filedtype']);
					$filed_type = $filed_arr[0];
					$filed_length = "({$filed_arr[1]})";
				}

				//字段默认值
				$field_default = '';
				if(isset($settings['default_value'])) $field_default = " default '{$settings['default_value']}' ";

				$column_info = "{$data['name']} {$filed_type}{$filed_length} not null {$field_default} COMMENT '{$data['description']}'";
				$this->db->column_action($m_data['table_name'], 'add', $column_info);
				$this->show_message('添加成功', '',get_url('admin', 'model', 'filed', 'id='.$m_id));
			}
		}

		$check_pattern = array_flip($this->check_pattern);
		$this->view->assign('pattern', $check_pattern);
		$this->view->assign('m_id', $id);
		$this->view->assign('model_info', $model_info);
		$this->view->assign('filed_type', $this->select_type_config);
		$this->view->display('add_filed');
	}

	/**
	 * 修改字段
	 * @return
	 */
	public function edit_filed() {
		if(gpc('dosubmit', 'R')){
			
			$data = gpc('data', 'P');
			$filed_id = gpc('filed_id', 'P');
			$m_id = gpc('m_id', 'P');
			$settings = gpc('settings', 'P');
			$model_data = $this->db->get_one($m_id);

			$this->comm_check_data('_empty', $data['name'], '请输入标题.');
			$this->comm_check_data('_empty', $data['name'], '请输入字段名.');
			$this->comm_check_data('_empty', $data['type'], '请选择字段类型.');

			if($data['name'] != $data['source_name']) {
				$this->check_filed($data['name'], $m_id);//检查字段是否存在
			}
			
			$this->comm_check_data('_empty', $data['description'], '', '0');

			//过滤
			if(!preg_match("/^[a-zA-Z0-9_]+$/i", $data['name'])) {
				$this->show_message('字段名只允许数字和英文和下划线.');
			}

			if(!preg_match("/^[\x{4e00}-\x{9fa5}\w]+$/u", $data['description'])) {
				$this->show_message('描述不允许特殊字符,只可以是数字英文或汉字.');
			}
			$data['title'] = htmlspecialchars($data['title']);
			$data['name'] = htmlspecialchars($data['name']);
			$data['description'] = htmlspecialchars($data['description']);
			
			
			

			//模型数据
			$model_data = $this->db->get_one($m_id);
	
			//更新数据
			$update = array();
			$update = $data;//将修改数据放入 原键值中.
			unset($update['source_name']);
			if(!empty($settings)) $update['settings'] = json_encode($settings);
			if(!empty($data['number_range'])) $update['number_range'] = json_encode($data['number_range']);
			
			if($this->db_filed->update($update, 'id='.$filed_id)) {
				$filed_type = $this->filed_settings_config[$data['type']]['type'];

				//字段长度
				$filed_length = '';
				if(isset($settings['size']) && !empty($settings['size'])) {
					$filed_length = "({$settings['size']})";
				} else {
					if(isset($this->filed_settings_config[$data['type']]['length'])) {
						$filed_length = "({$this->filed_settings_config[$data['type']]['length']})";
					}
				}

				//如果填写了字段类型 则使用填写的数据
				if(isset($settings['filedtype'])) {
					$filed_arr = explode('-', $settings['filedtype']);
					$filed_type = $filed_arr[0];
					$filed_length = "({$filed_arr[1]})";
				}

				//字段默认值
				$filed_default = '';
				if(isset($settings['default_value'])) {
					$filed_default = " default '{$settings['default_value']}'";
				}

				if($filed_type == 'text') {//某些字段不能做的事情
					$filed_default = '';
				}
				$filed_data = "{$data['source_name']} {$update['name']} {$filed_type}{$filed_length} not null {$filed_default} COMMENT '{$data['description']}'";
				$this->db->column_action($model_data['table_name'], 'change', $filed_data);
				$this->show_message('修改成功', '',get_url('admin', 'model', 'filed', 'id='.$m_id));
			}
		}

		$id = gpc('id');
		$filed_info = $this->db_filed->get_one($id);//获取字段信息
		$model_info = $this->db->get_one($filed_info['m_id']);//获取模型信息

		$check_pattern = array_flip($this->check_pattern);
		$this->view->assign('pattern', $check_pattern);
		$this->view->assign('id', $id);
		$this->view->assign('filed_info', $filed_info);
		$this->view->assign('model_info', $model_info);
		$this->view->assign('filed_type', $this->select_type_config);
		$this->view->display('edit_filed');
	}


	/**
	 * 删除字段
	 */
	public function del_filed() {
		$id = gpc('id');
		$this->comm_check_data('_empty', $id, '错误信息.');

		$filed_info = $this->db_filed->get_one($id);
		$this->comm_check_data('_empty', $filed_info, '错误信息.');

		$model_info = $this->db->get_one($filed_info['m_id']);

		if($this->db_filed->delete("id='{$id}'")) {
			$this->db->column_action($model_info['table_name'], 'drop', $filed_info['name']);
			$this->show_message('删除成功.', '',get_url('admin', 'model', 'filed', 'id='.$filed_info['m_id']));
		}
	}

	/**
	 * 应用操作
	 */
	public function filed_action() {
		$actions_switch = gpc('actions_switch', 'R');
		$m_id = gpc('m_id', 'R');
		switch($actions_switch) {
			case 'orders':
				$orders = gpc('orders', 'P');
				if(empty($orders)) $orders = array();

				foreach($orders as $k => $v) {
					$data['orders'] = $v;
					$this->db_filed->update($data, "id='{$k}'");
				}
				$this->show_message('操作成功!', '', get_url('admin', 'model', 'filed', 'id='.$m_id));
			break;
			case 'content_list':
				$content_list = gpc('content_list', 'P');
				if(empty($content_list)) $content_list = array();

				foreach($content_list as $k => $v) {
					$data['content_list'] = $v;
					$this->db_filed->update($data, "id='{$k}'");
				}
				$this->show_message('操作成功!', '', get_url('admin', 'model', 'filed', 'id='.$m_id));
			break;
			case 0:
				$this->show_message('请选择!', '', get_url('admin', 'model', 'filed', 'id='.$m_id));
			break;
		}
	}


	/**
	 * 检查字段 是否存在
	 * @param $filed_name 字段数据
	 * @param $model_data 模型数据
	 * @return
 	 */
	public function check_filed($filed_name, $m_id) {

		if(empty($filed_name)) return false;
		$data = $this->db_filed->get_one(array('m_id' => $m_id, 'name' => $filed_name));
		if(!empty($data)) $this->show_message("[{$data['name']}]字段已存在.");
	}
	/**
	 * 模型列表
	 */
	public function index() {
		$page = gpc('p');
		list($count, $lists) = $this->db->select_all('*', 'status=1', '', '', $page, $this->pagesize);

		//print_r($lists);
	 	$this->view->assign("pages", $this->page($count, $this->pagesize));
	 	$this->view->assign("lists", $lists);

		$this->view->display();
	}

	/**
	 * add model
	 */
	public function add () {
		if(gpc('dosubmit', 'P')) {
			$data = gpc('data', 'P');

			$this->comm_check_data('_empty', $data['name'], '请输入模型名称.');
			$this->comm_check_data('_empty', $data['table_name'], '请输入数据表名.');
			$data['createtime'] = time();
			//check data
			$this->check_data($data['table_name']);		

			//insert
			$rt = $this->db->insert($data);
			if($rt) {
				//create table
			 	$rt_table = $this->db->create_table($data['table_name']);
			 	$rt_table && $this->show_message('模型创建成功.', '',get_url('admin', 'model', 'index'));
			}
		}
		$this->view->display('add');
	}

	/**
	 * edit model
	 */
	public function edit() {
		if(gpc('dosubmit', 'P')) {
			$id = gpc('modelid', 'R');
			$data = gpc('data', 'P');

			$this->comm_check_data('_empty', $data['name'], '请输入模型名称.');
			$this->comm_check_data('_empty', $data['table_name'], '请输入数据表名.');
			

			$data['createtime'] = time();

			//check data
			//$this->check_data($data['table_name']);
				
			//获取模型信息
			$rtdata = $this->db->get_one($id);
			//insert
			$rt = $this->db->update($data, 'id='.$id);

			if($rt) {
				//create table
				
			 	$rt_table = $this->db->change_table($rtdata['table_name'], $data['table_name']);
			 	$rt_table && $this->show_message('模型修改成功.', '',get_url('admin', 'model', 'index'));
			}
		}

		$id = gpc('id');
		$data = $this->db->get_one($id);

		$this->view->assign('data', $data);
		$this->view->display('edit');
	}
	/**
	 * delete model
	 */
	public function delete() {
		$id = intval(gpc('id', 'R'));
		$rt = $this->db->update(array('status' => '-1'),'id='.$id);
		if($rt) {
			//删除模型只是修改状态，并且不删除表，以免误操作 数据丢失，请见谅
			$rt && $this->show_message('数据删除成功！', '', get_url('admin', 'model', 'index'));
		}
	}

	/**
	 * 检查数据
	 * @param $table_name 表名
	 */
	public function check_data($table_name) {
		$get_modelinfo = $this->db->get_one("table_name='{$table_name}' AND status=1", 'name,id');
		//检查模型是否存在
		if(!empty($get_modelinfo)) $this->show_message($table_name.' 模型已存在，请重新输入.');
		//检查数据表之前是否有遗留  是否存在
		$check_tablename = $this->db->check_table_exists($table_name);
		if($check_tablename) $this->show_message('数据表已存在，请检查.');

	}
}
