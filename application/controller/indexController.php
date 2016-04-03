<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * index.php
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:3677989>
 * @lastmodify			2013.04.07
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
//require_once('/Users/zhangliang/workspace/webapps/heartphp/functions/template_label/template_func_videolist.php'); 
class indexController extends base_controller {
	public function __construct() {
		parent::__construct();
		//A('test')->name('indexConstroller');


		$this->category_db = D('admin_category_model');
		$this->pagesize = 10;
		$this->content_db = D('content_model');//内容模型
		$this->content_db->set_model(15);
	}
	public function index() {
		$this->view->display();
	}

	public function test1() {
		print_r( $_SERVER );
		echo '1231';
	}
}
