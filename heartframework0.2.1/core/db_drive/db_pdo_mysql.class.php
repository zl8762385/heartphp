<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * db_mysql.class.php    MYSQL 鸡肋 
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class db_pdo_mysql implements db_interface  {
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

		$pdo_dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['db_name']}";
		$link = new PDO($pdo_dsn, $config['user'], $config['password']);

		if(!$link) {
			die('Error:Can\'t connect to database ' . $config ['db_name'] . ' of pd_mysql server:' . $config ['host'] );
		}

		$link->query("SET NAMES {$config['charset']}");
		return $link;
	}

	/**
	 * 
	 * 执行SQL 用户处理数据库修复等操作 一般不直接使用, 正常操作数据库请使用execute
	 * @param $sql  sql
	 * @param $link mysql object
	 * @return object last_query 
	 */
	final public function query($sql, $link = NULL) {
		$this->last_query = $link->query($sql);
		$this->last_query_sql = $sql;


		if(!$this->last_query) {
			$error_info = $link->errorInfo();
			core::show_error('MySQL Query Error: <br/>'.$sql.' <br/>'. $error_info[2]);
		}

		return $this->last_query;
	}

	/**
	 * 执行SQL语句 所有MYSQL_QUERY全部走这个方法
	 * @param $sql sql
	 * @return 
	 */
	final public function execute($sql) {

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


	/**
	 * 获取最新的ID主键
	 * @return number 
	 */
	final public function insert_id() {
		return $this->mlink->lastInsertId();
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
		// echo "$sql \n";
		$this->execute($sql);
		$this->last_query->setFetchMode(PDO::FETCH_ASSOC);
		$datalist = $this->last_query->fetchAll();

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

		return $rd;
	}

	final public function check_table_exists($table_name) {
		$sql = "SHOW TABLES LIKE '%{$table_name}%'";
		$this->execute($sql);
		$data = $this->fetch_next();

		
		$rt = (!empty($data)) ? '1' : '0' ;
		return $rt;
	}
	/**
	 * 返回结果集
	 * @param $type
	 */
	final public function fetch_next($type = MYSQL_ASSOC) {
		$this->last_query->setFetchMode(PDO::FETCH_ASSOC);
		$rd = $this->last_query->fetch();

		return $rd;
	}


	public function version() {
		return 'pdo_mysql';
	}


	public function __destruct(){
		if(!empty($this->mlink)) {
			$this->mlink = NULL;
		}
	}
}
