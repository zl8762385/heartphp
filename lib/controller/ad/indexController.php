<?php
/**
 *  index.php 入口文件
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:979314>
 * @lastmodify			2013.03.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class indexController extends helper_baseadminController {
//extends helper_baseadminController 

	public function test() {

		$this->view->assign('adcontent', 'adddddddddddd');
		$this->view->display('zhang');	
	}
}