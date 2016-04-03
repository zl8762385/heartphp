<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 后台管理系统 attachment附件管理
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:3677989>
 * @lastmodify			2013.04.12
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class logController extends helper_baseadminController {

	public function __construct() {
		parent::__construct();

		$this->db = D('action_log_model');
		$this->pagesize = 20;
	}

	//list
	public function index () {
		$page = gpc('p');
		list($count, $lists) = $this->db->select_all('*', '', 'id desc', '', $page, $this->pagesize);
		
	 	$this->view->assign("pages", $this->page($count, $this->pagesize));
	 	$this->view->assign("lists", $lists);
		$this->view->display();
	}

	//details
	public function details() {
		$id = gpc('id', 'R');
		if(empty($id)) $this->show_message('请求错误.');
		$infos = $this->db->get_one($id);

		$this->view->assign('infos', $infos);
		$this->view->display('details');
	}
	public function __destruct() {
		$this->db = NULL;
	}
}
