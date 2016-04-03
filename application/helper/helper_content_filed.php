<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  助手工具包  内容管理[字段操作]
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.05.28
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class helper_content_filed extends base_Controller {
	public $m_id = ''; //模型ID
	public $update_infos = '';//显示更新数据
		
	public function __construct() {
		parent::__construct();
		$this->db_filed = D('model_filed_model');
		$this->db_model = D('admin_model_model');
	}

	/**
	 * 设置数据
	 * @param $m_id 模型ID
	 */
	public function set_data($m_id, $update_infos = '') {
		$this->m_id = (empty($m_id)) ? '0' : intval($m_id);
		$this->update_infos = $update_infos;
	}

	/**
	 * 获取数据
	 */
	public function get_data() {
		$filed_data = $this->filed_html($this->get_field());
		return $filed_data;
	}
	/**
	 * 将字段转成HTML
	 * @param $fileds_data 字段数据
	 */
	public function filed_html($fileds_data) {
		if(empty($fileds_data)) return false;
		$form = H('helper_form');
		$html = array();
		foreach($fileds_data as $k => $v) {
			$form->set_data($v, $this->update_infos);
			$html[$k] = $form->get_data();
		}

		return $html;
	}


	/**
	 * 获取字段
	 */
	public function get_field() {
		$data = $this->db_filed->select('*', "m_id = $this->m_id", '', 'orders asc');
		return $data;
	}

}
