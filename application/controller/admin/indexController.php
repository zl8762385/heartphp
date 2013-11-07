<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 后台管理系统
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:979314>
 * @lastmodify			2013.04.07
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class indexController extends helper_baseadminController {

	public $login_error_code = array(
		'100' => '请输入用户名',
		'101' => '请输入密码',
	//	'102' => '请输入用户名和密码',
		'103' => '用户名不存在',
		'104' => '密码错误'
	);

	/**
	 * login
	 */
	public function login() {
		$uname = get_cookie('uname', '', 2);
		$uid = get_cookie('uid');
		if(!empty($uname) && !empty($uid)) $this->redirect(get_url('admin', 'index', 'main'));

		$this->view->display('login');
	}

	/**
	 * login user and password
	 */
	public function check_login() {
		$post_data['username'] = core::gpc('username', 'R');
		$post_data['pwd']      = core::gpc('pwd', 'R');

		empty($post_data['username']) && exit($this->login_error_code['100']);
		empty($post_data['pwd']) && exit($this->login_error_code['101']);

		$db_admin_user = D('admin_user_model');
		$u_data = '';
		$u_data = $db_admin_user->check_adminuser_login($post_data['username'], $post_data['pwd']);		
		!is_array($u_data) && exit($this->login_error_code[$u_data]);


		set_cookie('uname', $u_data['username'], 0, '', 1);
		set_cookie('uid', $u_data['userid'], 0, '', 1);
		echo '1';
		exit();
	}


	/**
	 * 登陆后主页面
	 */
	public function main() {
		$this->view->display('index');
	}

	/**
	 * 登陆后主页面
	 */
	public function home() {

		$this->view->display('home');
	}

	/**
	 * 退出
	 */
	public function logout() {
		set_cookie('uname', '', -3600);	
		set_cookie('uid', '', -3600);	
		$this->redirect(get_url('admin', 'index', 'login'));
	}


}