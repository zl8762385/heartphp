<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 后台管理系统
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:3677989>
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

	public function __construct() {
		parent::__construct();
		$this->db_admin_user = D('admin_user_model');
		$this->db_menu = D('admin_menu_model');
	}
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
		$post_data['username'] = gpc('username', 'R');
		$post_data['pwd']      = gpc('pwd', 'R');

		empty($post_data['username']) && exit($this->login_error_code['100']);
		empty($post_data['pwd']) && exit($this->login_error_code['101']);

		$db_admin_user = $this->db_admin_user;
		$u_data = '';
		$u_data = $db_admin_user->check_adminuser_login($post_data['username'], $post_data['pwd']);
		$error_message = (!is_array($u_data)) ? $this->login_error_code[$u_data] : '成功登陆' ;
		$this->action_log(array(
			        	"login_username" => $post_data['username'],
			        	'login_passwd' => substr($post_data['pwd'], 0,3).'***',
			        	'error_message' => $error_message
						));//操作日志
		!is_array($u_data) && exit($this->login_error_code[$u_data]);

		set_cookie('uname', $u_data['username'], 0, '', 1);
		set_cookie('ept', $u_data['encrypt'], 0, '', 1);
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
	 * TOP
	 */
	public function top() {

		$this->view->display('top');
	}

	/**
	 * LEFT
	 */
	public function left() {
		$menuid = gpc('menuid');
		//获取当前栏目信息
		$current_menu = $this->db_menu->get_one("id='{$menuid}'");

		//这里为了效率不做递归，之找到两级菜单
		$menudata = $this->db_menu->select("*","parentid='{$menuid}' AND is_display=1", '', 'menuorder DESC');

		$menu_html = '';
		if(!empty($menudata)) {

			foreach($menudata as $k => &$v) {
				$v['submenu'] = $this->db_menu->select("*","parentid='{$v['id']}' AND is_display=1", '', 'menuorder DESC');
			}

			foreach($menudata as $k => $vv){
				$menu_html .= '<dd>';
			    $menu_html .= '<div class="title">';
			    $menu_html .= '<span></span>'.$vv['name'];
			    $menu_html .= '</div>';
			    	$menu_html .= '<ul class="menuson">';
			    		if(!empty($vv['submenu'])) {
			    			foreach($vv['submenu'] as $sk => $sv) {
								$url = "index.php?d={$sv['directory']}&c={$sv['controller']}&a={$sv['methed']}";
								$menu_html .= '<li><cite></cite><a href="'.$url.'" target="rightFrame">'.$sv['name'].'</a><i></i></li>'."\n";

			    			}
			    		}

			        $menu_html .= '</ul>';
			    $menu_html .= '</dd>';
			}
		}

		//如果没有选择一级分类，默认显示快捷方式
		if(empty($menuid)) {
			$menu_html = '';
		}
		//$name = (!empty($current_menu['name'])) ? $current_menu['name'] : '' ;



		$this->view->assign('menu_html', $menu_html);
		$this->view->display('left');
	}

	/**
	 * 生存周期
	 * @return [type] [description]
	 */
	public function user_life() {
		//更新用户活动时间，来监测用户在线人数
		$uid = get_cookie('uid', '', 2);
		if($uid && !empty($uid)) {
			$this->db_admin_user->update(array(
				'lifetime' => time()
			), "userid='$uid'");
		}

		return true;
		//$this->db_admin_user
	}

	/**
	 * 用于框架内部的一些统计
	 * @return [type] [description]
	 */
	public function stat_infos() {
		$time = time()-60;
		$statinfo = array();
		$statinfo['users_total'] = $this->db_admin_user->get_one('','count(userid) as total');
		$statinfo['users_online']= $this->db_admin_user->select('username, lifetime', "lifetime > '{$time}'");

		echo json_encode($statinfo);
	}
	/**
	 * 退出
	 */
	public function logout() {
		set_cookie('uname', '', -3600);
		set_cookie('ept', '', -3600);
		set_cookie('uid', '', -3600);
		set_cookie('lastlogintime', '', -3600);

		setcookie('f_token', '',-3600);
		$this->redirect(get_url('admin', 'index', 'login'));
	}


}