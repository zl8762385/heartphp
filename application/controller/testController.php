<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:3677989>
 * @lastmodify			2013.04.07
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class testController extends base_controller {

    public function gpc() {
        $db = D('admin_user_model');
        $rt = $db->query("select * from heart_model");

        while($rt= $db->fetch_next()) {
           $rt_data[] = $rt;
        }

        $data = gpc(array('id', 'name'), 'G');
    }
	public function index(){
		$this->view->display();
	}

	public function test() {
		$h_test = new helper_test();
		$h_test->test();
		$array = array('aaa', 'bbb', 'ccc');

		$this->view->assign('array', $array);
		$this->view->display();
	}

	public function name($str) {

		echo 'zhangliang 123'.$str;
	}
}
