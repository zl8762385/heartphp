<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 后台管理系统 菜单
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:3677989>
 * @lastmodify			2013.04.09
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class menuController extends helper_baseadminController {

	public function __construct() {
		parent::__construct();
		$this->db = D('admin_menu_model');
		$this->pagesize = 10;
	}

	//lists
	public function index() {
		$page = gpc('p');
		$tree = new tree();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

		$data = $this->db->select('*', '', '', 'id desc');
		$array = array();

		foreach($data as $r) {
			$r['create_date'] = date('Y-m-d H:i:s', $r['createtime']);
			$r['str_manage'] = '<a href="'.get_url('admin', 'menu', 'add', 'id='.$r['id'].'').'" title="">'.icons('add', '添加子菜单').'</a>&nbsp;&nbsp;<a href="'.get_url('admin', 'menu', 'edit', 'id='.$r['id'].'').'" title="">'.icons('edit', '修改').'</a>&nbsp;&nbsp;<a href="javascript:_confirm(\''.get_url('admin', 'menu', 'delete', 'id='.$r['id'].'').'\', \'您确认要删除该信息吗?\')" title="">'.icons('delete', '删除').'</a>';
			$r['parentid_node'] = ($r['parentid'])? ' class="child-of-node-'.$r['parentid'].'"' : '';
			$array[] = $r;
		}

		$str  = "<tr id='node-\$id' \$parentid_node>
		<td align='center'>\$id</td>
		<td align='left'>\$spacer\$name</td>
		<td >\$create_date</td>
		<td align='center'>\$str_manage</td>
		</tr>";

		$tree->init($array);
		$categorys = $tree->get_tree(0, $str);
		$this->view->assign('categorys', $categorys);
	
		$this->view->display();
	}

	//add
	public function add() {
		$id = gpc('id');
		$data = $this->db->get_one($id);

		if(gpc('dosubmit_add', 'R')) {//add menu
			$data = gpc('data', 'R');
			empty($data['name']) && $this->show_message('请输入名称');

			empty($data['controller']) && $this->show_message('请输入文件名');
			empty($data['methed']) && $this->show_message('请输入方法');

			$data['createtime'] = time();
			$rt = $this->db->insert($data);
			$rt && $this->show_message('数据添加成功.', '', get_url('admin', 'menu', 'index'));
		}

		$parentid = (empty($id)) ? 0 : $id ;

		if(!empty($id)) {
			$this->view->assign('parentname', $data['name']);
		}
		
		$this->view->assign('parentid', $parentid);
		$this->view->display('add');
	}

	//edit
	public function edit() {
		$id = gpc('id', 'R');
		empty($id) && $this->show_message('请输入ID.', '', get_url('admin', 'menu', 'index'));
		if(gpc('dosubmit', 'P')){
			$data =  gpc('data', 'R');

			$rt = $this->db->update($data, 'id='.$id);
			$rt && $this->show_message('数据修改成功.', '', get_url('admin', 'menu', 'index'));
		}

		$data = $this->db->get_one($id);

		$this->view->assign('edit_data', $data);
		$this->view->display('edit');
	}

	//delete
	public function delete() {
		$id = intval(gpc('id'));
		$rt = $this->db->delete('id='.$id);
		if($rt) {
			$rts = $this->db->delete('parentid='.$id);//删除子栏目
			$rts && $this->show_message('数据删除成功！', '', get_url('admin', 'menu', 'index'));
		}
	}

	public function __destruct() {
		$this->db = NULL;
	}
}