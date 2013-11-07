<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  baseadmin 后台管理系统 助手文件 controller引用中可以extends这个助手
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:979314>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class helper_baseadminController extends base_controller {
	//通行证，如果没设置后台通行证，那么就不能公开现实您想要显示的页面
	public $pass_action = array('login', 'check_login', 'logout');

	public function __construct() {
		parent::__construct();

		if(!in_array(core::get_action_name(), $this->pass_action)) {
			$this->check_user();//检查用户登录
			$this->menu();//后台左侧菜单
		}
	}

	/**
	 * 检查用户是否登陆
	 */
	public function check_user() {
		$uname = get_cookie('uname', '', 2);
		$uid   = get_cookie('uid');
		if(!empty($uname) && !empty($uid)) {
			$db = D('admin_user_model');
			$data = $db->get_user($uname, 'username');
			if(!$data) $this->redirect(get_url('admin', 'index', 'login'));

			$data['groupinfo'] = $this->get_group($data['groupid']);//获取用户组信息
			$this->userinfo = $data;

			$this->action_log();//操作日志
			$this->view->assign('userinfo', $this->userinfo);
		} else {
			$this->redirect(get_url('admin', 'index', 'login'));
		}
	}

	final public function action_log() {
		$log_db = D('action_log_model');

		$data = array();
		if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {//POST记录
			$op = array(//保存 操作做基本信息
							'userid' => $this->userinfo['userid'],
							'username' => $this->userinfo['username'],
							'truename' => $this->userinfo['truename']
						); 
			$data['ip'] = GetIP();
			$data['d'] = $_GET['d'];
			$data['c'] = $_GET['c'];
			$data['a'] = $_GET['a'];
			$data['method'] = 'POST';
			$data['referer'] = HTTP_REFERER;
			$data['createtime'] = time();
			$data['op'] = array2string($op);
			$data['submit_data'] = array2string($_POST);
			$log_db->insert($data);
		}

	}
	/**
	 * 获取当前用户组信息
	 */
	public function get_group($groupid) {
		if(empty($groupid)) return false;
		$db_group = D('admin_group_model');

		$data = $db_group->get_one($groupid);
		
		return $data;
	}


	/**
	 * 左侧菜单
	 */
	final public function menu($level_id = 0) {
		$db = D('admin_menu_model');
		$db_group = D('admin_group_model');

		$tree = new tree();
		$current_url = $this->get_currenturl();//当前URL
		$config = core::load_config('config');

		$where = array();
		if($this->userinfo['userid'] == 1) {//创始人读取全部菜单	
			$where[0] = 'is_display=1';
		} else {
			$data = $db_group->get_one('id='.$this->userinfo['groupid']);//获取当前用户组菜单
			$group_value = (!empty($data['group_value'])) ? $data['group_value'] : '' ;
			if($group_value != '1') {
				$this->look_user_power($db, $group_value);//检查URL是否在当前用户组中
				$where[1] = 'id in('.$group_value.')';
			}
			
			$where[2] = 'is_display = 1';
		}

		$_where = implode(' AND ', $where);

		$data = $db->select('*', $_where, '', 'menuorder asc, id asc');
		empty($data) && $this->show_message('无菜单获取,请检查!');

		$newarr = array();
		foreach($data as $k => $v) {
			$url = get_url($v['directory'], $v['controller'], $v['methed']);
			$v['current'] = ($current_url == $url) ? 'current' : '' ;
			$v['url'] = $url;
			$v['icon_type'] = 'file';
			$newarr[$k] = $v;
		}

		if(!empty($newarr)) {
			$tree->init($newarr);
			$strs = "<span class='\$icon_type'><a href='\$url' target='right'>\$name</a></span>";
			$strs2 = "<span class='folder'>\$name</span>";

			$categorys = array();
			//$categorys = $tree->get_treeview_admin(0,'category_tree', $strs, $strs2);

			//获取一级菜单
			$menu_master = array();
			foreach($newarr as $k => $v) {
				if($v['parentid'] == '0') {
					$menu_master[] = $v;
				}
			}

			if(!empty($menu_master)) {

				foreach($menu_master as $k => $v) {
					$category_html = '';
					$category_html = $tree->get_treeview_admin($v['id'],'category_tree', $strs, $strs2);
					$categorys[$v['id']] = str_replace("'", '"', trim($category_html));
				}

				$this->view->assign('menu_mater', $menu_master);
			}

			//其他二级菜单
			$this->view->assign('menu_data', $categorys);
		}

	}

	/**
	 * 遍历目录 查看用户是否有查看当前URL的权限
	 * @param $db object
	 * @param $grou_value 34,12,43
	 */
	public function look_user_power($db, $group_value) {
		if(empty($group_value)) $this->show_message('没有权限!');

		$where = array();
		if(core::get_directory_name()) $where[0] = 'directory="'.core::get_directory_name().'"';
		if(core::get_controller_name()) $where[1] = 'controller="'.core::get_controller_name().'"';
		if(core::get_action_name()) $where[2] = 'methed="'.core::get_action_name().'"';

		$_where = implode(' AND ', $where);
		$_where = 'id in('.$group_value.') AND '.$_where;
		$data = $db->get_one($_where);
		if(empty($data)) $this->show_message('抱歉,您没有访问当前页面的权限,请联系管理员.');
	}

	/**
	 * 获取当前URL
	 * @return  url
	 */
	public function get_currenturl () {
		$current_url = get_url(core::get_directory_name(), core::get_controller_name(), core::get_action_name());
		return $current_url;
	}

	/**
	 * 封装 公用检查数据方法
	 * @param $type 类型
	 * @param $data 引用数据
	 * @param $tipmessage 提示信息
	 * @param $default_data 默认数据
	 * @param $jump_url 跳转url
	 * @return 
	 */
	public function comm_check_data($type, &$data, $tipmessage = '请输入信息!', $default_data = '', $jump_url = '') {
		$message_status = false;
	
		switch($type) {
			case '_empty'://判断是否为空 等于空等于0
				if(empty($data)) $message_status = true;
			break;
			case '_isset_empty'://判断是否为空 等于空等于0
				if(!isset($data) || empty($data)) $message_status = true;
			break;
			case '_not_empty'://判断是否为空 不等于空等于0
				if(!empty($data)) $message_status = true;
			break;
			case '_number';//判断数字类型
				echo '1234';
			break;
			case '_string';//判断字符串
				echo '1235';
			break;
			default:
				$this->show_message('没找到相关类型.');
		}
		if($message_status) {
			if($default_data == '') {
				$this->show_message($tipmessage, '', $jump_url);
			} else {
				$data = $default_data;//提示状态默认值.
			}
		}
	}
}