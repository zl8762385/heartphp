<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * index.php
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:979314>
 * @lastmodify			2013.04.07
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class indexController extends base_controller {
	public function __construct() {
		parent::__construct();

		$this->pagesize = 10;
		$this->content_db = D('content_model');//内容模型
		$this->content_db->set_model(15);
	}
	public function index() {
		$this->view->display();
	}

	public function news() {
		$page = core::gpc('p');
		
		list($count, $lists) = $this->content_db->select_all('*', '', '', 'id desc', $page, $this->pagesize);

	 	$this->view->assign("pages", $this->page($count, $this->pagesize));
	 	$this->view->assign("lists", $lists);
		$this->view->display();
	}

	public function show() {
		$id = intval(core::gpc('id'));
		!$id && $this->show_message('请求错误');

		$infos = $this->content_db->get_one($id);

		$this->view->assign('infos', $infos);
		$this->view->display();
	}

	public function test() {
		echo 'test a';
		print_r(debug_backtrace());
		$str = 'my name is param';
		core::hooks('arge_users', $str);
		//core::hooks('yes_admin', $str);
		//$this->view->display();
	}

	public function read_test() {
		$data = cache::get('zhangliang', 'index');
		print_r($data);
	}
}