<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * db_interface 数据库接口，每个CLASS实现必须实现下面方法
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
interface db_interface {
	public function __construct(&$conf);

	public function query($sql, $link = NULL);

	public function update($data, $where, $tblname);

	public function delete($where, $tblname);

	public function insert($data, $tblname);

	public function select($fileds, $tblname, $where = '', $limit = '', $order = '', $group = '');

	public function get_one($fileds, $tblname, $where = '');

	public function version();
}