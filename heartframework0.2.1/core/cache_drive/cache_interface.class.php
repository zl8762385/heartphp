<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * cache_interface 缓存接口，每个CLASS实现必须实现下面方法
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

interface cache_interface {
	public function __construct(&$conf);

	/**
	 * 设置缓存
	 * @param $key key
	 * @param $data array data
	 * @param $life 生存周期
	 */
	public function set_cache($key, $data, $life = 0);

	/**
	 * 获取缓存
	 * @param $key key
	 */
	public function get_cache($key);

	/**
	 * 删除缓存
	 * @param $key key 
	 */
	public function delete_cache($key);
}