<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 数据级缓存类 cache目录在每个项目的跟目录下
 * filecache
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:979314>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class cache_file implements cache_interface {
	public $cache_dir = '';//缓存目录
	public $cache_filename = '';//缓存文件名
	public $cache_suffix = '.cache.php';

	public function __construct(&$conf){
		$this->cache_dir = ($conf['cache_dir']) ? $conf['cache_dir'] : core::show_error('请设置缓存目录!') ;
	}

	
	/**
	 * 设置缓存
	 * @param $key key
	 * @param $data array data
	 * @param $life 生存周期
	 */
	public function set_cache($key, $data, $life = 0) {
		$this->get_filename($key);
		//如果存在则覆盖
		if(($fp = fopen($this->cache_dir.$this->cache_filename, 'wb')) === false) {
			core::show_error('cache创建失败，请检查文件权限！');
		}

		//缓存数据格式
		$_data = '<?php
			$rt_data["life"] = '.$life.';
			$rt_data["data"] = '.var_export($data,true).';
		?>';
		flock($fp, LOCK_EX + LOCK_NB);//独占上锁
		fwrite($fp, $_data);
		flock($fp, LOCK_UN + LOCK_NB);//解锁
		fclose($fp);	
	}

	/**
	 * 获取缓存
	 * @param $key key
	 */
	public function get_cache($key) {
		$this->get_filename($key);
		$filepath = $this->cache_dir.$this->cache_filename;
		if(is_file($filepath)) {
			include $filepath;
			if($this->is_active($filepath, $rt_data['life'])) {
				return $rt_data['data'];
			} else {
				$this->delete_cache($key);//过期删除文件
				return false;
			}
		}


	}

	/**
	 * 删除单条缓存
	 * @param $key key 
	 */
	public function delete_cache($key) {
		$this->get_filename($key);
		if(is_file($this->cache_dir.$this->cache_filename)) {
			@unlink($this->cache_dir.$this->cache_filename);
		}
	}

	/**
	 * 遍历删除所有缓存
	 * @return 
	 */
	public function delete_all_cache() {
		$path = dir($this->cache_dir);
		while(($v = $path->read()) !== false) {
			if($v == '..' || $v == '.') continue;
			if(is_file($this->cache_dir.$v)) {
				@unlink($this->cache_dir.$v);
			} else {
				return false;
			}
		}
		return true;

	}

	public function __call($method, $params) {
		core::show_error("cache_file.class.php : {$method}方法不存在");
	}




	/**
	 * 获取文件名
	 * @param $key key
	 * @return $this->cache_filename
	 */
	public function get_filename($key) {
		$this->cache_filename = md5($key).$this->cache_suffix;
	}

	/**
	 * 检测文件是否过期
	 * @param $filepath 文件完整路径
	 * @param $life 过期时间
	 * @return bool
	 */
	public function is_active($filepath, $life = 0) {
		//echo date('Y-m-d H:i:s', filemtime($filepath)+$life);
		if(time() > (filemtime($filepath)+$life)) {
			return false;//过期
		} else {
			return true;;//no 过期
		}
	}
}