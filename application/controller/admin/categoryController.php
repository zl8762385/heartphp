<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 后台管理系统
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:3677989>
 * @lastmodify			2013.04.09
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class categoryController extends helper_baseadminController {
	public $catids = array();//栏目集合

	public function __construct() {
		parent::__construct();
		$this->db = D('admin_category_model');
		$this->model_db = D('admin_model_model');//模型表
		$this->pagesize = 10;
	}

	//lists
	public function index() {
		$page = gpc('p');
		$tree = new tree();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

		$data = $this->db->select('*', '', '', 'orders desc');
		$array = array();

		foreach($data as $r) {
			$r['is_display'] = (empty($r['is_display'])) ? '隐藏' : '显示' ;
			$r['create_date'] = date('Y-m-d H:i:s', $r['createtime']);
			$r['str_manage'] = '<a href="'.get_url('admin', 'category', 'add', 'id='.$r['id'].'').'" title="">'.icons('add', '添加子栏目').'</a>&nbsp;&nbsp;<a href="'.get_url('admin', 'category', 'edit', 'id='.$r['id'].'').'" title="">'.icons('edit', '修改').'</a>&nbsp;&nbsp;<a href="javascript:_confirm(\''.get_url('admin', 'category', 'delete', 'id='.$r['id'].'').'\', \'您确认要删除该信息吗?\')" title="">'.icons('delete', '删除').'</a>';
			$r['parentid_node'] = ($r['parentid'])? ' class="child-of-node-'.$r['parentid'].'"' : '';
			$array[] = $r;
		}

		$str  = "<tr id='node-\$id' \$parentid_node>
		<td align='center'><input class='dfinput' style='text-align:center;width:60px;height:20px;margin:0 10px 0 0;' type='text' name='orders[\$id]' value='\$orders'/></td>
		<td align='center'>\$id</td>
		<td align='left'>\$spacer\$name</td>
		<td align=''>\$is_display</td>
		<td >\$create_date</td>
		<td align=''>\$str_manage</td>
		</tr>";

		$tree->init($array);
		$categorys = $tree->get_tree(0, $str);
		$this->view->assign('categorys', $categorys);
	
		$this->view->display();
	}

	//add
	public function add() {

		if(gpc('dosubmit', 'R')) {//add
			$data = gpc('data', 'R');

			$this->comm_check_data('_isset_empty', $data['modelid'], '请选择模型');
			$this->comm_check_data('_empty', $data['name'], '请输入名称');
			

			$check_name = $this->db->get_one("name='".$data['name']."'");
			$this->comm_check_data('_not_empty', $check_name, "[{$data['name']}] 已存在!");

			//$images = (isset($data['images'])) ?  : ;
			$catetory_images = '';
			if(isset($data['images']['pic_title']) && is_array($data['images']['pic_title'])) {//如果有上传图片 and file，带有标题的，单独处理
				$pic_value = array();
				foreach($data['images']['pic_title'] as $pic_k => $pic_v) {
					$pic_value[] = array('title' => $pic_v, 'filename' => $pic_k);
				}

				$catetory_images = json_encode($pic_value);
			}
		
			$insertdata = array();
			$insertdata['name'] = $data['name'];
			$insertdata['parentid'] = $data['parentid'];
			$insertdata['description'] = $data['description'];
			$insertdata['images'] = $catetory_images;
			$insertdata['modelid'] = $data['modelid'];
			$insertdata['is_display'] = $data['is_display'];
			$insertdata['status'] = 1;//状态，暂时为通过 后续增加功能使用
			$insertdata['operate'] = $this->userinfo['userid'];
			$insertdata['createtime'] = time();
			
			$rt = $this->db->insert($insertdata);

			if(!empty($data['parentid'])) {//插入父数据 节点
				$parent_data = $this->db->get_one($data['parentid']);

				$nodes_id['attach_id'] = $parent_data['attach_id'].','.$this->db->insert_id();
				$this->db->update($nodes_id, "id='".$data['parentid']."'");
			}
			
			$rt && $this->show_message('数据添加成功.', '', get_url('admin', 'category', 'index'));
		}

		$id = gpc('id');
		$data = $this->db->get_one($id);
		
		$parentid = (empty($id)) ? 0 : $id ;

		if(!empty($id)) $this->view->assign('parentname', $data['name']);
		$this->view->assign('parentid', $parentid);
		$this->view->assign('model_data', $this->model_data());
		$this->view->display('add');
	}

	//edit
	public function edit() {
		$id = gpc('id', 'R');
		$this->comm_check_data('_empty', $id, '请输入ID.', '', get_url('admin', 'category', 'index'));

		if(gpc('dosubmit', 'P')){
			$data =  gpc('data', 'R');

			$this->comm_check_data('_empty', $data['name'], '请输入名称');

			$catetory_images = '';
			if(isset($data['images']['pic_title']) && is_array($data['images']['pic_title'])) {//如果有上传图片 and file，带有标题的，单独处理
				$pic_value = array();
				foreach($data['images']['pic_title'] as $pic_k => $pic_v) {
					$pic_value[] = array('title' => $pic_v, 'filename' => $pic_k);
				}

				$data['images'] = json_encode($pic_value);
			}
		
			$this->db->update($data, 'id='.$id) && $this->show_message('数据修改成功.', '', get_url('admin', 'category', 'index'));
		}

		$data = $this->db->get_one($id);

		$this->view->assign('model_data', $this->model_data());
		$this->view->assign('edit_data', $data);
		$this->view->display('edit');
	}


	//delete
	public function delete() {
		$id = intval(gpc('id'));

		//入桟 当前catid
		array_push($this->catids, $id);

		if($this->recursion_delete($id) && !empty($this->catids)) {
			$catids = implode(',', $this->catids);
		
			$this->db->delete("id in({$catids})") && $this->show_message('数据删除成功！', '', get_url('admin', 'category', 'index'));
		}
	}

	/**
	 * 应用操作
	 */
	public function action() {
		$actions_switch = gpc('actions_switch', 'R');
		switch($actions_switch) {
			case 'orders':
				$orders = gpc('orders', 'P');
				if(empty($orders)) $orders = array();
				
				foreach($orders as $k => $v) {
					$data['orders'] = $v;
					$this->db->update($data, "id='{$k}'");
				}
				
				$this->show_message('操作成功!', '', get_url('admin', 'category', 'index'));
			break;
			case 0:
				$this->show_message('请选择!', '', get_url('admin', 'category', 'index'));
			break;
		}
	}
	/**
	 * 递归删除
	 * @param $id catid
	 * @return
	 */
	public function recursion_delete($id) {
		if(empty($id)) return false;

		$id_data = $this->db->get_one($id);

		$ids = explode(',', $id_data['attach_id']);

		//递归 遍历
		foreach($ids as $k => $v) {
			if(empty($v)) continue;
			$ids_data = $this->db->get_one($v);

			array_push($this->catids, $v);//入桟 末尾
			if($ids_data['attach_id']) {
				$this->recursion_delete($v);
			} else {
				continue;
			}

		}

		return true;
	}
	/**
	 * 获取模型数据 并且重构数据
	 */
	public function model_data() {
		$data = $this->model_db->select('id, name', 'status=1');
		if(empty($data)) return false;

		$newarr = array();
		foreach($data as $k => $v) {
			$newarr[$v['id']] = $v['name'];
		}

		return $newarr;
	}

	
	public function __destruct() {
		$this->db = NULL;
	}
}