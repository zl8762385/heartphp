<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * db_mssql.class.php   没经过测试
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class db_mssql implements db_interface  {
	private $conf;

	protected $last_query_sql = null;//最后一次执行的SQL语句
	protected $last_query = null;//最后一次请求句柄

	public function __construct(&$conf) {
		$this->conf = &$conf;
	}	

	public function __get($var) {
		//mlink = master_link主库，先留着 等以后版本支持主从
		//slink  = slave_link从
		switch($var) {
			case 'mlink':
				$this->mlink = $this->connect();
				return $this->mlink;
			break;
			default:
		}
	}

	/**
	 * 链接数据库
	 */
	private function connect() {
		if(!is_array($this->conf)) return false;
		$config = $this->conf;
		$link = mssql_connect($config['host'], $config['user'], $config['password'], $config['db_name']);
		if(!$link) {
			die('Error:Can\'t connect to database ' . $config ['db_name'] . ' of mssql server:' . $config ['host'] );
		}

		$db_selected = mssql_select_db($config['db_name'], $link);
		if(!$db_selected) {
			die ("Can\'t use {$config['db_name']}: " . mssql_error());
		}

		if(!empty($engine) && $engine == 'InnoDB') {
			$this->query("SET innodb_flush_log_at_trx_commit=no", $link);
		}

		mssql_query('SET NAMES \'' . $config['charset'] . '\'', $link);
		return $link;
	}

	/**
	 * 
	 * 执行SQL 用户处理数据库修复等操作 一般不直接使用, 正常操作数据库请使用execute
	 * @param $sql  sql
	 * @param $link mssql object
	 * @return 
	 */
	final public function query($sql, $link = NULL) {
		$this->last_query = mssql_query($sql, $link);
		$this->last_query_sql = $sql;

		if(!$this->last_query) {
			core::show_error('mssql Query Error: <br/>'.$sql.' <br/>'. mssql_error());
		}

		return $this->last_query;
	}

	/**
	 * 执行SQL语句 所有mssql_QUERY全部走这个方法
	 * @param $sql sql
	 * @return 
	 */
	final public function execute($sql) {
		//不是资源类型，或者非正常断开，重新连接
		if(!is_resource($this->mlink) || !mssql_ping($this->mlink)) {
			$this->connect();
		}
		//DEBUG模式 记录执行sql
		if(DEBUG && isset($_SERVER['sqls'])) {
			array_push($_SERVER['sqls'], $sql);
		}

		return $this->query($sql, $this->mlink);
	} 

	/**
	 * 更新数据
	 * @param $data array array('view' => 10, 'age' => 15)
	 * @param $where string id=15 
	 * @param $tblname string table_name
	 * @return $number 
	 */
	final public function update($data, $where, $tblname) {
		if(empty($data) || empty($where) || !is_array($data)) return false;

		$filed = array();
		foreach($data as $k => $v) {
			$this->add_special_char($k);
			$this->filter_filed_value($v);
			$filed[] = $k.'='.$v; 
		}

		$filed = implode(',', $filed);

		$sql = 'UPDATE '.$tblname. ' SET '.$filed.' WHERE '.$where; 
		$this->execute($sql);
		return $this->last_query;
	}

	/**
	 * 删除数据
	 * @param $where 删除条件
	 * @param $tblname table_name
	 * @return number
	 */
	final public function delete($where, $tblname) {
		if(empty($where) || empty($tblname)){
			return false;
		}

		$_where = ' WHERE '.$where;
		$sql = 'DELETE FROM '.$tblname.$_where;
		$this->execute($sql);
		return $this->last_query;
	}

	/**
	 * 插入数据
	 * @param $data 插入数据  键值是字段 array('filed' => 'data')
	 * @param $tblname table_name
	 * @return bool
	 */
	final public function insert($data, $tblname) {
		if(!is_array($data) || empty($data)) return false;
		
		$filed = array_keys($data);
		$value = array_values($data);

		$this->add_special_char($filed);
		$this->filter_filed_value($value);

		$sql = 'INSERT INTO '. $tblname .'('. $filed.') VALUES '.'('. $value .')';
		$this->execute($sql);
		return $this->last_query;

	}

	final public function check_table_exists($table_name) {
		$sql = "SHOW TABLES LIKE '%{$table_name}%'";
		$this->execute($sql);
		$data = $this->fetch_next();
		$rt = (!empty($data)) ? '1' : '0' ;
		return $rt;
	}

	/**
	 * 获取最新的ID主键
	 * @return number 
	 */
	final public function insert_id() {
		return mssql_insert_id($this->mlink);
	}

	/**
	 * 获取最后一次执行的SQL语句
	 */
	final public function last_query_sql() {
		return $this->last_query_sql;
	}

	/**
	 * 给字段值两边加引号，保证数据安全，并对特有内容进行转义
	 * @param $value array
	 * @return 
	 */
	final public function filter_filed_value(&$value) {
		if(is_array($value)) {
			$new_value = array();
			foreach($value as $k => $v) {
				$new_value[$k] = '\''.$v.'\'';
			}

			$value = implode(',', $new_value);
		} else {
			$value = '\''.$value.'\'';
		} 
	}
	/**
	 * 给字段增加``，为了保证数据库安全
	 * @param $filed 字段 
	 * @return string 进行替换反引号之后 最后返回implode之后的数据，例如: a,b,c,d
	 */
	final public function add_special_char(&$filed) {
		if(is_array($filed)) { //进行数组过滤
			$new_filed = array();
			foreach($filed as $k => $v) {
				$v = trim($v);

				if(strpos($v, '(') && strpos($v, ')')) {
					$v = str_replace('(', '(`', $v);
					$v = str_replace(')', '`)', $v);
				} else {
					if(strpos($v, ' as ')) {//处理 as
						$s = explode('as', $v);
						if(is_array($s)) {
							$v = '`'. trim($s[0]) .'` as '. $s[1];	
						}
					} else {
						$v = '`'. trim($v) .'`';
					}
					
				}

				$new_filed[$k] = $v; 
			}

			$filed = implode(',', $new_filed);
		} elseif(strpos($filed,',')) {//查找 如果找到逗号，就是字符串，则开始替换过滤
			$filed = explode(',', $filed);
			$this->add_special_char($filed);
		} else{//很可惜不是数字 也没有逗号  这里就不替换了，去掉两边空格，等以后遇到了问题在加反引号把

			$filed = trim($filed);
		}
	}

	/**
	 * @param $fileds string 需要显示的字段，例如：username,age,content
	 * @param $tblname string 数据表名称
	 * @param $where string where查询条件
	 * @param $limit limit 0,10 不解释了
	 * @param $order 排序
	 * @param $gourp 分组
	 * @return array
	 */
	final public function select($fileds, $tblname, $where = '', $limit = '', $order = '', $group = '') {
		if(empty($tblname)) return false;

		$where = (!empty($where)) ? ' WHERE '.$where : '' ;
		$limit = (!empty($limit)) ? ' LIMIT '.$limit : '' ;
		$order = (!empty($order)) ? ' ORDER BY '.$order : '' ;
		$group = (!empty($group)) ? ' GROUP BY '.$group : '' ;

		empty($fileds) && $fileds = '*';

		$this->add_special_char($fileds);

		$sql = 'SELECT '.$fileds.' FROM '.$tblname.''.$where.$group.$order.$limit;
		$this->execute($sql);

		$datalist = array();
		while (($rd = $this->fetch_next()) != false) {
			$datalist[] = $rd;
		}

		$this->free_result();
		return $datalist;
	}

	/**
	 * 获取一条数据
	 * @param $fileds 字段名 例如：username,passwd,age
	 * @param $tblname table_name
	 * @param $where 查询条件 
	 * @return array;
	 */
	final public function get_one($fileds, $tblname, $where = '') {
		if(empty($tblname)) return false;

		empty($fileds) && $fileds = '*';

		$this->add_special_char($fileds);

		$where = (!empty($where)) ? ' WHERE '.$where : '' ;

		$sql = 'SELECT '.$fileds.' FROM '.$tblname.$where.' LIMIT 1';
		$this->execute($sql);
		$rd  = $this->fetch_next();
		$this->free_result();
		return $rd;
	}

	/**
	 * 返回结果集
	 * @param $type
	 */
	final public function fetch_next($type = MSSQL_ASSOC) {
		if(!is_resource($this->last_query)) {
			return false;
		}
		
		$rd = mssql_fetch_array($this->last_query, $type);
		if(!$rd) {
			$this->free_result();
		}
		return $rd;
	}

	/**
	 * 释放资源
	 * @return 
	 */
	public function free_result() {
		if(is_resource($this->last_query)) {
			mssql_free_result($this->last_query);
			$this->last_query = NULL;
		}
	}
	/**
	 * 关闭数据库 
	 * 如果是资源类型 就关闭 默认先关闭主库
	 */
	final public function close() {
		if(is_resource($this->mlink)) {
			mssql_close($this->mlink);
		}
	}

	public function version() {
		return mssql_get_server_info($this->mlink);
	}

	/**
	 * 获取出错信息
	 * @return string
	 */
	public function error() {
		return @mssql_error ( $this->mlink );
	}

	public function __destruct(){
		//print_r($_SERVER['sqls']);
		if(!empty($this->mlink)) {
			$this->close();
		}
	}
}
