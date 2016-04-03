<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 数据级缓存类 redis
 * redis
 * redis方法太多，不具体扩展了，等用到在继续...
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class cache_redis implements cache_interface {

	public function __construct(&$conf) {
		$this->conf = $conf;
	}

	public function __get($vars) {
		if($vars == 'redis') {
			if(extension_loaded('redis')) {
				$this->redis = new Redis;
			} else {
				core::show_error('未加载redis扩展');
			}

			if($this->redis->connect($this->conf['host'], $this->conf['port'], $this->conf['timeout'])) {
				return $this->redis;	
			} else {
				core::show_error('不能正常连接redis');
			}
		}
	}

	public function set_cache($key, $data, $life = 0) {
		return $this->redis->set($key, $data, $life);
	}

	public function get_cache($key) {
		return $this->redis->get($key);
	}

	public function delete_cache($key) {
		return $this->redis->delete($key);
	}

	public function ttl_cache($key) {
		return $this->redis->ttl($key);
	}
}