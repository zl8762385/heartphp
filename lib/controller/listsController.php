<?php
/**
 *  index.php 首页文件
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:979314>
 * @lastmodify			2013.03.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class listsController extends helper_baseadminController {

	public function content() {
		$lists = array('11','222','333');
		$content = 'fsdfdffsdf';
		$this->view->assign('content', $content);
		$this->view->assign('list', $lists);
		$this->view->display();
	}

	public function lists() {
		$lists = array('333','444','555');
		$content = 'lists';
		$this->view->assign('content', $content);
		$this->view->assign('list', $lists);
		$this->view->display();
	}
}