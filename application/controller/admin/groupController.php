<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 后台管理系统 group
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:979314>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class groupController extends helper_baseadminController {

	public function __construct() {
		parent::__construct();

		$this->db = D('admin_group_model');
		$this->db_menu = D('admin_menu_model');
		$this->pagesize = 10;
	}

	//list
	public function index () {
		$page = core::gpc('p');
		list($count, $lists) = $this->db->select_all('*', '', '', '', $page, $this->pagesize);

		//print_r($lists);
	 	$this->view->assign("pages", $this->page($count, $this->pagesize));
	 	$this->view->assign("lists", $lists);
		$this->view->display();
	}

	//add
	public function add() {
		if(core::gpc('dosubmit','P')) {
			$data = core::gpc('data', 'P');

			$this->comm_check_data('_empty', $data['name'], '请输入用户组名称！', '', get_url('admin', 'group', 'add'));

			if(!empty($data['group_value'])) {
				$data['group_value'] = implode(',', $data['group_value']);
			}

			$data['createtime'] = time();
			$this->db->insert($data) && $this->show_message('数据添加成功！', '', get_url('admin', 'group', 'index'));

		}	
		$this->view->display('add');
	}

	//edit
	public function edit(){
		$id = core::gpc('id', 'R');
		$this->comm_check_data('_empty', $id, '操作错误.');

		if(core::gpc('dosubmit', 'P')) {
			$data = core::gpc('data', 'P');
			$this->comm_check_data('_empty', $data['name'], '请输入用户组名称！', '', get_url('admin', 'group', 'add'));

			if(!empty($data['group_value'])) {
				$data['group_value'] = implode(',', $data['group_value']);
			}

			$this->db->update($data, 'id='.$id) && $this->show_message('数据修改成功！', '', get_url('admin', 'group', 'index'));
		}
		$data = $this->db->get_one($id);
		$this->comm_check_data('_empty', $data, '数据不存在.');

		$this->view->assign('menulists', $this->db_menu->menulists());
		$this->view->assign('data', $data);
		$this->view->display('edit');
	}

	//delete
	public function delete() {
		$id = intval(core::gpc('id'));
		$this->db->delete('id='.$id) && $this->show_message('数据删除成功！', '', get_url('admin', 'group', 'index'));
	}

	//权限列表
	public function power() {
		$this->view->display('power_lists');
	}

	//获取菜单JSON
	public function menu_list() {
		echo $this->db_menu->menu_json();
	}

	public function __destruct() {
		$this->db = NULL;
		$this->db_menu = NULL;
	}
}
