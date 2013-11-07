<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 后台管理系统 attachment附件管理
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:979314>
 * @lastmodify			2013.04.12
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class attachmentController extends helper_baseadminController {

	public function __construct() {
		parent::__construct();

		$this->db = D('attachment_model');
		$this->category_db = D('admin_category_model');
		$this->pagesize = 20;
	}

	//list
	public function index () {
		$page = core::gpc('p');
		list($count, $lists) = $this->db->select_all('*', '', '', '', $page, $this->pagesize);
		
	 	$this->view->assign("pages", $this->page($count, $this->pagesize));
	 	$this->view->assign("lists", $lists);
		$this->view->display();
	}

	//delete
	public function delete() {
		$id = intval(core::gpc('id', 'R'));
		$data = $this->db->get_one($id);

		if($this->db->delete('id='.$id)) {
			$file = SYSTEM_PATH.$data['filepath'].$data['filename'];
			if(file_exists($file)) {
				unlink($file);
			}

			$this->show_message('数据删除成功！', '', get_url('admin', 'attachment', 'index'));
		}
	}

	public function __destruct() {
		$this->db = NULL;
		$this->category_db = NULL;
	}
}
