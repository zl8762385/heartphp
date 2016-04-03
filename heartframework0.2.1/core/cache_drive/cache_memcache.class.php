<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 数据级缓存类 memcache
 * memcache
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class cache_memcache implements cache_interface {

	public function __construct(&$conf){
		$this->conf = $conf;

	}

	public function __get($vars) {
		if($vars == 'memcache') {
			if(extension_loaded('memcache')) {
				$this->memcache = new memcache();
			} else {
				core::show_error('未加载Memcached扩展');
			}

			if($this->memcache->connect($this->conf['host'], $this->conf['port'])) {
				return $this->memcache;	
			} else {
				core::show_error('不能正常连接memcache');
			}

		}
	}

	/**
	 * 设置缓存
	 * @param $key key
	 * @param $data array data
	 * @param $life 生存周期
	 */
	public function set_cache($key, $data, $life = 0) {
		return $this->memcache->set($key, $data, MEMCACHE_COMPRESSED, $life);	
	}

	/**
	 * 设置缓存  如果这个值已存在 返回FALSE
	 * @param $key key
	 * @param $data array data
	 * @param $life 生存周期
	 */
	public function add_cache($key, $data, $life = 0) {
		return $this->memcache->add($key, $data, MEMCACHE_COMPRESSED, $life);	
	}

	/**
	 * 获取缓存
	 * @param $key key
	 */
	public function get_cache($key) {
		return $this->memcache->get($key);
	}

	/**
	 * 删除缓存
	 * @param $key key 
	 */
	public function delete_cache($key) {
		return $this->memcache->delete($key);
	}

	/**
	 * 清空所有缓存
	 */
	public function flush() {
		return $this->memcache->flush();
	}

	public function __destruct() {
		$this->memcache->close();
	}

}