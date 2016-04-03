<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 用户公用函数库
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

/**
 * 不带分页读取 栏目数据
 * @param  [type] $param [description]
 * @return [type]        [description]
 */
function template_func_category_data($param) {
	if(!is_array($param)) return false;
	$limit = (isset($param['limit'])) ? $param['limit'] : 0 ;
	$order = (isset($param['order'])) ? $param['order'] : 'id desc' ;
	$catid = (isset($param['catid'])) ? $param['catid'] : 0 ;

	//构造数据对象
	$category_db = D('admin_category_model');
	$content_db = D('content_model');//内容模型

	$category_infos = $category_db->get_one("id='$catid'");
	if(empty($category_infos)) return false;

	$modelid = (isset($category_infos['modelid']))? $category_infos['modelid']: 0 ;
	$content_db->set_model($modelid);

	$_where = array();
	$where = '';
	if(!empty($catid)) $_where[] = "catid='$catid'";
	if(is_array($_where) && !empty($_where)) {
		$where = implode(' AND ', $_where);
	}


	if($limit <= 1) {
		$data = $content_db->get_one($where);
	} else {
		$data = $content_db->select('*', $where, $limit, $order);
	}
	return $data;
}