<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  base_model.class.php 数据模型鸡肋 
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

abstract class base_model {
	//数据库配置
	protected $conf = '';
	//表名字
	protected $table_name = '';
	//默认配置
	public $db_setting = 'default';
	//表前缀
	public $table_prefix = 'heart_';

	protected static $db_instance= array();//静态私有变量 用来保存实例以后的对象 避免重复new
	protected static $cache_instance = array();//静态私有变量 用来保存实例以后的对象 避免重复new

	public function __construct(&$conf) {
		$this->conf = C();
		$this->table_name = $this->table_prefix.$this->table_name;//设置默认完整表名

	}

	/**
	 * 手动设置完整表名
	 * @param $table_name 表名
	 * @param $table_prefix 表前缀
	 */
	final public function set_table($table_name, $table_prefix = '') {
		$this->table_prefix = (empty($table_prefix)) ? $this->table_prefix : $table_prefix ;
		$this->table_name = $this->table_prefix.$table_name;
	}

	/**
	 * 魔法方法 如果试图查找不存在的属性 则调用
	 * db 数据库句柄
	 */
	public function __get($var) {
		switch($var) {
			case 'db':
				return $this->instance_db();
			break;
			case 'cache';
				return $this->instance_cache();
			break;
			default:
		}
	}

	/**
	 * 切换数据库句柄 实际操作方法
	 */
	public function instance_db() {
		$type = $this->conf['db']['type'];
		if(empty($type)) core::show_error('请在配置文件中设置数据库类型！');

		$dbname = 'db_'.$type;
		if(isset(self::$db_instance[$dbname][$this->db_setting])) {//区分跨库
			return self::$db_instance[$dbname][$this->db_setting];
		} else {
			self::$db_instance[$dbname][$this->db_setting] = new $dbname($this->conf['db'][$type][$this->db_setting]);
			return self::$db_instance[$dbname][$this->db_setting];
		}
	}

	/**
	 * 切换cache类句柄 实际操作方法
	 */
	public function instance_cache() {
		if(!$this->conf['cache']['cache_switch']) core::show_error('缓存处于关闭中，请打开缓存并配置相关参数！');

		$type = $this->conf['cache']['type'];


		$cachename = 'cache_'.$type;
		if(isset(self::$cache_instance[$cachename])) {
			return self::$cache_instance[$cachename];
		} else {
			$conf = array();
			if(isset($this->conf['cache'][$type])) $conf = $this->conf['cache'][$type];
			$conf['cache_dir'] = $this->conf['cache_path'];

			self::$cache_instance[$cachename] = new $cachename($conf);
			return self::$cache_instance[$cachename];	
		}

	}

    /**
     * $rt = $db->query("select * from heart_model");
     * while($rt= $db->fetch_next()) {
     * $rt_data[] = $rt;
     * }
     * @param $sql 直接执行sql 如果需要多表查询的话可以使用这个
     * @return mixed
     */
    final public function query($sql = '') {
        return $this->db->execute($sql);
    }


	/**
	 * 插入数据
	 */
	final public function insert($data) {
		return $this->db->insert($data, $this->table_name);
	}

	/**
	 * 更新数据
	 * @param $data array
	 * @param $where 可以为数组 也可以是字符串
	 */
	final public function update($data, $where) {
		if(empty($data) || empty($where) || !is_array($data)) return false;
		$where = $this->sqls($where);
		return $this->db->update($data, $where, $this->table_name);
	}

	/**
	 * 删除数据
	 * @param $data array('字段' => '10')
	 * @return number
	 */
	final public function delete($where = '') {
		if(empty($where)) return false;

		return $this->db->delete($where, $this->table_name);
	}

	/**
	 * 查询数据
	 * @param $fileds string 需要显示的字段，例如：username,age,content
	 * @param $where string where查询条件
	 * @param $limit limit 0,10 不解释了
	 * @param $order 排序
	 * @param $gourp 分组
	 * @return array
	 */
	final public function select($fileds = '', $where = '', $limit = '', $order = '', $group = '') {
		if(is_array($fileds)) {//当字段参数为数组的时候 转换成 $where
			$where = $this->sqls($fileds);
			$fileds = '*';
		}

		return $this->db->select($fileds, $this->table_name, $where, $limit, $order, $group);
	}

	/**
	 *分页
	 *	list($count, $lists) = $this->db->select_all('*', 'parent=0', '', '', $page, $this->pagesize);
	 *	$this->view->assign("pages", $this->page($count, $this->pagesize));
	 *	$this->view->assign("lists", $lists);
	 *
	 * 带分页查询所有数据
	 * @param $fileds string 需要显示的字段，例如：username,age,content
	 * @param $where string where查询条件
	 * @param $order 排序
	 * @param $gourp 分组 
	 * @param $page 当前页码
	 * @param $pagesize 每页显示数据
	 * @return array
	 */
	public function select_all($fields, $where='', $order='', $group='', $page=0, $pagesize=10) {
		$page = max(intval($page),1);
		$offset = $pagesize * ($page - 1);
		$limit = $this->build_limit($offset, $pagesize);

		$newdata = array();
		$count = $this->db->get_one('count(*) as count', $this->table_name, $where);

		$newdata[0] = $count['count'];
		$newdata[1] = $this->db->select($fields, $this->table_name, $where, $limit, $order, $group);
		return $newdata;
	}

	/**
	 * 查询一条数据
	 * @param $data string 如果传入参数是数字 默认会查询ID 如果不是数字会默认WHERE条件 
	 * @param $fields string 字段名，例如：username,age,content
	 * @return array
	 */
	final public function get_one($data, $fileds = '*') {
		$where = '';
		$where = $this->sqls($data);
		$where = (is_numeric($data)) ? "id = $where" : $where;	

		return $this->db->get_one($fileds, $this->table_name, $where);
	}

	/**
	 * 检查表是否存在
	 * @param $name
	 */
	public function check_table_exists($name) {
		return $this->db->check_table_exists($this->table_prefix.$name);		
	}


	/**
	 * mysql_fetch_array
	 */
	final public function fetch_next() {
		return $this->db->fetch_next();
	}

	/**
	 * 获取最后一次执行ID主键
	 */
	final public function insert_id(){
		return $this->db->insert_id();
	}

	/**
	 * 获取最后一次执行的SQL语句
	 */
	final public function last_query_sql() {
		return $this->db->last_query_sql();
	}

	public function version() {
		return $this->db->version();
	}

	/**
	 * 创建limit
	 * @param $offset 偏移数字
	 * @param limit number
	 * @return 0,10
	 */
	final public function build_limit($offset, $num = NULL) {
		$offset = (int) $offset;
		$offset = (empty($offset)) ? 0 : $offset ;
		if($num === NULL) {
			return $offset;
		} else {
			return $offset.','. $num;
		}
	}

	/**
	 * 将数组转换成SQL语句
	 * @param $where SQL数组
	 * @param $font 连接字符串
	 * @return
	 */
	final public function sqls($where, $font = ' AND ') {
		if(is_array($where)) {
			$sqls_str = '';
			foreach($where as $k => $v) {
				$sqls_str .= ($sqls_str) ? " $font `$k` = '$v'" : " `$k` = '$v'" ;
			}
			return $sqls_str;
		} else {
			return $where;
		}
	}

	public function __call($method, $params){
		core::show_error("base_model.class.php : {$method}方法不存在");
	}






	///////////////////////////////////////////////////////////////////////////////cache相关
	/**
	 * $this->set_cache('get_user_one1', "'fdsfdsf'", 10);
	 * 设置缓存
	 * @param $key key
	 * @param $data array data
	 * @param $life 生存周期
	 */
	final public function set_cache($key, $data, $life = 0) {
		empty($key) && core::show_error('请输入KEY！');
		empty($data) && $data = array();
		return $this->cache->set_cache($key, $data, $life);
	}

	/**
	 * [memcache]设置缓存  如果这个值已存在 返回FALSE
	 * @param $key key
	 * @param $data array data
	 * @param $life 生存周期
	 */
	final public function add_cache($key, $data, $life = 0) {
		return $this->cache->add_cache($key, $data, $life);
	}

	/**
	 * $this->get_cache('get_user_one')
	 * 获取缓存
	 * @param $key key
	 */
	final public function get_cache($key) {
		return $this->cache->get_cache($key);
	}

	/**
	 * $this->delete_cache('get_user_one');
	 * delete缓存
	 * @param $key key
	 */
	final public function delete_cache($key) {
		return $this->cache->delete_cache($key);
	}

	/**
	 * $this->delete_all_cache();
	 * 遍历删除所有缓存
	 */
	final public function delete_all_cache() {
		return $this->cache->delete_all_cache();	
	}

	/**
	 * redis ttl 得到一个KEY的生存时间
	 */
	final public function ttl_cache($key) {
		return $this->cache->ttl_cache($key);
	}

}
