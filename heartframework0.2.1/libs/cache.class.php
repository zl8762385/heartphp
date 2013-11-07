<?php
/**
 * cache.class.php  缓存类
 * 所有缓存文件都是根据 模块生成
 * cache文件都在 data/cache/下
 * 跟目录：data/cache/cache__index 根目录下在indexController
 * 多目录：data/cache/cache_test_index  test目录下的indexController
 * 
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:979314>  
 * @lastmodify			2013.07.03
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 * 为了方便做了一些简化操作，如果需要设置数据类型 必须把所有参数全部都写全，不然会报错.
 * 使用方法有两种：
 * 第一种：
 * $data = 我是字符串'';
 * cache::set('zhangliang', $data, 'string');//设置缓存
 * cache::get('zhangliang', '', 'index', 'string');//
 *
 * 第二种：如果不是字符串和其他数据 默认就是序列化存储,可以省略最后一个数据类型参数
 * $data = array('aa' => 'a111', 'bb' => 'a222', 'cc' => '张亮哈哈');
 * cache::set('zhangliang', $data);
 * cache::get('zhangliang', '', 'index');//完整参数
 * cache::get('zhangliang', 'index');//如果您控制器在根目录不是多级目录 可以省略目录的参数 直接写控制器名称即可
 */

class cache {
	static public $suffix = '.php';//缓存后缀

	/**
	 * 设置缓存 默认类型 serialize
	 * @param string $filename      缓存文件名
	 * @param string or array $data 数据
	 * @param string $type          string, serialize
	 * @return 
	 */
	static public function set($filename, $data, $type = 'serialize') {
		if(empty($filename)) return false;
		$filename = md5($filename);

		$complete_filename = self::get_cache_filename();//获取完整文件名
		self::mkdir($complete_filename);//创建目录，如果存在则返回FALSE
		$new_filename = $complete_filename.$filename.self::$suffix;

		//写入文件 先做转换
		switch($type) {
			case 'serialize':
				$data = serialize($data);
			break;
		}

		self::write($new_filename, $data);//写入文件
	}

	/**
	 * 获取缓存 
	 * @param  string $filename 文件名
	 * @param  string $d        目录
	 * @param  string $c        控制器
	 * @param  string $type     类型 默认是 序列化serialize 
	 * @return
	 */
	static public function get($filename, $d = '', $c = '', $type = 'serialize'){
		if(empty($filename)) return false;
		//没有多级目录的情况下 默认$d参数变成控制器 省略第三个参数$c
		if(!empty($d) && empty($c)){
			$c = $d;
			$d = '';
		}
		$filename = md5($filename);

		$complete_filename = self::get_cache_filename($d, $c);//获取完整文件名
		$complete_filename = $complete_filename.$filename.self::$suffix;
		//echo $complete_filename;	
		$content = '';
		$content = self::read($complete_filename);

		//对读取文件进行转换
		switch($type) {
			case 'serialize':
				$content = unserialize($content);
			break;
		}
		return $content;
	}


	/**
	 * 读取文件
	 * @param  $filename [文件完全路径名]
	 * @return 
	 */
	static public function read($filename) {
		if(!is_file($filename)) return false;//缓存文件不存在 

		if(($fp = fopen($filename, 'rb')) === false) {
			core::show_error($filename.'<br/>读取文件失败，请检查文件权限.');
		}
		$content = file_get_contents($filename);

		return $content;
	}
	/**
	 * 写入文件
	 * @param  [string] $filename [文件名]
	 * @param  [string] $content  [内容]
	 * @return    
	 */
	static public function write($filename, $content) {
		if(($fp = fopen($filename, 'wb')) === false) {
			core::show_error($filename.'<br/>缓存文件失败，请检查文件权限.');
		}
		//开启文件锁
		if(flock($fp, LOCK_EX + LOCK_NB)) {
			fwrite($fp, $content, strlen($content));
			flock($fp, LOCK_UN + LOCK_NB);
		}
		fclose($fp);
	}

	/**
	 * 获取完整文件名
	 * @return
	 */
	static public function get_cache_filename($d = '', $c = '') {
		global $conf;
		$path_name = self::get_path_name($d, $c);//获取当前文件目录	
		$cache_path = $conf['cache_path'];
		return $cache_path.$path_name;
	}

	/**
	 * 获取cache文件路径
	 * @param  string $d [目录]
	 * @param  string $c [控制器]
	 * @return 路径 
	 */
	static public function get_path_name($d = '', $c = '') {
		if(empty($d) && defined('__D__')) $d = __D__;//目录 
		if(empty($c) && defined('__C__')) $c = __C__;//控制器
		//先转换小写
		$d = strtolower($d);
		$c = strtolower($c);
		$path_name = "cache_{$d}_{$c}/";
		return $path_name;
	}

	/**
	 * 只创建一级目录
	 */
	static public function mkdir($path, $mode = 0777) {
		if(is_dir($path)) return false;//目录存在 不继续创建
		if(!@mkdir($path, $mode)) {
			core::show_error($path.'<br/>创建目录失败，请检查是否有可都写权限！');
		}
	}
}