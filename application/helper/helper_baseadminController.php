<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  baseadmin 后台管理系统 助手文件 controller引用中可以extends这个助手
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class helper_baseadminController extends base_controller {
	//通行证，如果没设置后台通行证，那么就不能公开现实您想要显示的页面
	public $pass_action = array('login', 'check_login', 'logout');

	public function __construct() {
		parent::__construct();

		if(!in_array(__A__, $this->pass_action)) {
			$this->check_user();//检查用户登录
			$this->menu();//后台左侧菜单
		}
	}

	/**
	 * 检查用户是否登陆
	 */
	public function check_user() {
		$uname = get_cookie('uname', '', 2);
		$encrypt = get_cookie('ept', '', 2);
		$uid   = get_cookie('uid', '', 2);
		$lastlogintime = get_cookie('lastlogintime','', 2);

		if(!empty($uname) && !empty($uid)) {
			$db = D('admin_user_model');
			$data = $db->get_one("username='{$uname}' AND userid='{$uid}'");
			if(!$data) $this->redirect(get_url('admin', 'index', 'logout'));
			//如果找到了该用户，还需要进行密码 加密的检测，一致才可通过
			if(!isset($data['encrypt']) || $data['encrypt'] != $encrypt) $this->redirect(get_url('admin', 'index', 'logout'));


			$data['groupinfo'] = $this->get_group($data['groupid']);//获取用户组信息
		
			//通过了，但是这里需要检查用户last登陆时间，每人只能登陆一次
			if($lastlogintime != $data['lastlogintime']) {
				$this->redirect(get_url('admin', 'index', 'logout'));
			}
			
			$this->userinfo = $data;

			$this->action_log(array(//保存 操作做基本信息
				'userid' => $this->userinfo['userid'],
				'username' => $this->userinfo['username'],
				'truename' => $this->userinfo['truename']
			));
			
			$this->is_token();//对令牌进行验证

			$this->view->assign('userinfo', $this->userinfo);
		} else {
			$this->redirect(get_url('admin', 'index', 'logout'));
		}
	}

	public function is_token(){
		//登陆成功，检查form提交是否执行了crsf  对POST
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(!isset($_POST['is_token']) || empty($_POST['is_token'])) $this->show_message('非法提交！');
			if($this->get_token() !== $_POST['is_token']) $this->show_message('非法提交！');

		} else {//post请求时 不用生成token
			if(!isset($_COOKIE['f_token']) && empty($_COOKIE['f_token'])) {
				$this->set_token();//登陆成功 生成form_token
			}
		}
	}

	/**
	 * 设置TOTKEN
	 */
	private function set_token() {
		$token = $this->gen_token();;
		setcookie('f_token', $token);
		$_COOKIE['f_token'] = $token;
	}

	/**
	 * 获取token
	 * @return [type] [description]
	 */
	public function get_token() {
		return $_COOKIE['f_token'];
	}

	final public function action_log($op = array()) {
		$log_db = D('action_log_model');

		$data = array();
		if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {//POST记录

			$data['ip'] = GetIP();
			$data['d'] = __D__;
			$data['c'] = __C__;
			$data['a'] = __A__;
			$data['method'] = 'POST';
			$data['referer'] = HTTP_REFERER;
			$data['createtime'] = time();
			$data['op'] = json_encode($op);
			$data['submit_data'] = json_encode($_POST);
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
		$config = C('', 'config');

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
		//empty($data) && $this->show_message('无菜单获取,请检查!');

		$newarr = array();
		foreach($data as $k => $v) {
			$url = get_url($v['directory'], $v['controller'], $v['methed']);
			$v['current'] = ($current_url == $url) ? 'current' : '' ;
			$v['url'] = $url;
			$v['icon_type'] = 'file';
			$newarr[$k] = $v;
		}

		if(!empty($newarr)) {

			//获取一级菜单
			$menu_master = array();
			foreach($newarr as $k => $v) {
				if($v['parentid'] == '0') {
					$menu_master[] = $v;
				}
			}

			$this->view->assign('menu_mater', $menu_master);
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
		if(defined('__D__')) $where[0] = 'directory="'.__D__.'"';
		if(defined('__C__')) $where[1] = 'controller="'.__C__.'"';
		if(defined('__A__')) $where[2] = 'methed="'.__A__.'"';

		$_where = implode(' AND ', $where);
		$_where = 'id in('.$group_value.') AND '.$_where;

		$allow_url = array('admin_index_main', 'admin_index_top', 'admin_index_left', 'admin_index_home');
		$allow_str = __D__.'_'.__C__.'_'.__A__;
		if(in_array($allow_str, $allow_url)) {
			return true;
		}

		$data = $db->get_one($_where);
		if(empty($data)) $this->show_message('抱歉,您没有访问当前页面的权限,请联系管理员.');
	}

	/**
	 * 获取当前URL
	 * @return  url
	 */
	public function get_currenturl () {
		return get_url(__D__, __C__, __A__);
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
