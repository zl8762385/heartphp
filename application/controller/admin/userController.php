<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 后台管理系统 admin user
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:979314>
 * @lastmodify			2013.04.12
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class userController extends helper_baseadminController {

	public function __construct() {
		parent::__construct();

		$this->db = D('admin_user_model');
		$this->db_group = D('admin_group_model');
		$this->pagesize = 20;
	}

	//list
	public function index () {
		$page = core::gpc('p');
		list($count, $lists) = $this->db->select_all('*', '', '', '', $page, $this->pagesize);
		
		$this->view->assign('groupdata', $this->db_group->grouplists());
	 	$this->view->assign("pages", $this->page($count, $this->pagesize));
	 	$this->view->assign("lists", $lists);

		$this->view->display();
	}

	//add
	public function add() {
		if(core::gpc('dosubmit', 'R')) {
			$data = core::gpc('data', 'P');

			$this->comm_check_data('_empty', $data['username'], '请输入用户名！', '', get_url('admin', 'user', 'add'));
			$this->comm_check_data('_empty', $data['password'], '请输入密码！', '', get_url('admin', 'user', 'add'));
			$this->comm_check_data('_empty', $data['confirmpassword'], '请输入确认密码！', '', get_url('admin', 'user', 'add'));
			if($data['password'] != $data['confirmpassword']) $this->show_message('两次密码不一致！', '', get_url('admin', 'user', 'add'));

			$this->comm_check_data('_empty', $data['email'], '请输入邮箱！', '', get_url('admin', 'user', 'add'));
			$this->comm_check_data('_empty', $data['truename'], '请输入真实姓名！', '', get_url('admin', 'user', 'add'));

			$password = password($data['password']);
			$data['encrypt'] = $password['encrypt'];
			$data['password'] = $password['password'];
			unset($data['confirmpassword']);

			$this->db->insert($data) && $this->show_message('数据添加成功！', '', get_url('admin', 'user', 'index'));
		}
		$this->re_group_arr();
		$this->view->assign('groupdata', $this->re_group_arr());
		$this->view->display('add');
	}

	//edit
	public function edit(){
		$id = core::gpc('id');
		if(core::gpc('dosubmit', 'P')) {
			$data = core::gpc('data', 'P');
			$userid = core::gpc('userid', 'P');

			$this->comm_check_data('_empty', $data['username'], '请输入用户名！', '', get_url('admin', 'user', 'edit', 'id='.$userid));
			$this->comm_check_data('_empty', $data['email'], '请输入邮箱！', '', get_url('admin', 'user', 'edit', 'id='.$userid));
			$this->comm_check_data('_empty', $data['truename'], '请输入真实姓名！', '', get_url('admin', 'user', 'edit', 'id='.$userid));
		

			if(!empty($data['password']) && !empty($data['confirmpassword'])) {
				if($data['password'] != $data['confirmpassword']) $this->show_message('两次密码不一致！', '', get_url('admin', 'user', 'edit', 'id='.$userid));
				$password = password($data['password']);
				$data['encrypt'] = $password['encrypt'];
				$data['password'] = $password['password'];
				unset($data['confirmpassword']);
			} else {
				unset($data['password'], $data['confirmpassword']);
			}	

			$rt = $this->db->update($data, 'userid='.$userid);
			$rt && $this->show_message('数据修改成功.', '', get_url('admin', 'user', 'index'));
		}
		empty($id) && $this->show_message('请输入ID！');
		$data = $this->db->get_one('userid='.$id);
		$this->re_group_arr();

		$this->view->assign('userdata', $data);
		$this->view->assign('groupdata', $this->re_group_arr());
		$this->view->display('edit');
	}
	//重构group数组
	public function re_group_arr() {
		$groupdata = $this->db_group->select('id,name');
		$newarr = array();
		foreach($groupdata as $k => $v) {
			$newarr[$v['id']] = $v['name'];
		}

		return $newarr;
	}

	//delete
	public function delete() {
		$id = intval(core::gpc('id', 'R'));
		$rt = $this->db->delete('userid='.$id);
		if($rt) {
			$rt && $this->show_message('数据删除成功！', '', get_url('admin', 'user', 'index'));
		}
	}

	public function __destruct() {
		$this->db = NULL;
		$this->db_group = NULL;
	}
}
