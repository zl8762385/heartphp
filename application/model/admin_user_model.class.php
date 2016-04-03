<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');

//管理员数据表
class admin_user_model extends base_model {
	public $table_name = 'admin_user';//表名
	public $db_setting = 'heartphp';//DB设置 读取配置文件的键值
	public $table_prefix = 'heart_';//前缀

	public function __construct() {
		global $conf;
		parent::__construct($conf);
	}

	/**
	 * 获取用户信息
	 * @param $username string 用户名
	 */
	public function get_user($username, $filed = 'username') {
		$userinfo = $this->get_one("{$filed}='{$username}'");
		return $userinfo;
	}

	/**
	 * 检查用户名密码
	 * @param $username string 用户名
	 * @param $pwd string 密码
	 * @return array
	 */
	public function check_adminuser_login($username, $pwd) {
		$userinfo = $this->get_one("username='{$username}'");
		if($userinfo) {
			$password = password($pwd, $userinfo['encrypt']);
			if($password != $userinfo['password']) return 104;

			$this->update_login_message($userinfo);//更新登陆信息
			return $userinfo;
		} else {
			return 103;
		}
	}

	/**
	 * 更新登陆信息
	 * @param $userinfo array 用户信息
	 * @return 
	 */
	public function update_login_message(&$userinfo) {
		$data = array();
		$data['lastlogintime'] = $data['updatetime'] = $data['lifetime'] = time();
		$data['lastloginip'] = GetIP();

		set_cookie('lastlogintime', $data['lastlogintime'], 0, '', 1);
		$this->update($data, "userid='{$userinfo['userid']}'");
	}
}