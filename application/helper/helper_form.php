<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  助手工具包 form转换 input html
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:979314>  
 * @lastmodify			2013.05.28
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class helper_form extends base_Controller {
	public $html = '';//文本框HTML
	public $update_infos = '';//显示更新数据

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 设置信息 
	 */
	public function set_data($data, $update_infos = '') {
		if(empty($data)) return false; 
		$this->update_infos = (isset($update_infos[$data['name']]) && !empty($update_infos[$data['name']])) ? $update_infos[$data['name']] : '' ;

		$this->html = $this->$data['type']($data);//自动获取form类型
	}

	/**
	 * 获取信息
	 */
	public function get_data() {
		return (empty($this->html)) ? '' : $this->html ;
	}

	/**
	 * 单行文本
	 * @param $filed_data 字段数据
	 * @return array
	 */
	public function text($filed_data) {
		$input_arr = array();
		$settings = string2array($filed_data['settings']);
		
		$settings['text_style'] = (isset($settings['text_style'])) ? $settings['text_style'] : '' ;
		$input['input'] = form::input("data[{$filed_data['name']}]", $this->update_infos, 'text', 'text w_15', '', "{$settings['text_style']}");
		$input['name'] = $filed_data['title'];
		return $input;
	}

	/**
	 * 多行文本
	 * @param $filed_data 字段数据
	 * @return array
	 */
	public function textarea($filed_data) {
		$input_arr = array();
		$settings = (empty($filed_data['settings'])) ? array() : string2array($filed_data['settings']) ;

		$width = (isset($settings['width'])) ? $settings['width'] : '' ;
		$height = (isset($settings['height'])) ? $settings['height'] : '' ;

		$input['input'] = form::textarea("data[{$filed_data['name']}]", $this->update_infos, '', '', '', "width:{$width};height:{$height}");
		$input['name'] = $filed_data['title'];
		return $input;
	}

	/**
	 * 编辑器
	 * @param $filed_data 字段数据
	 * @return array
	 */
	public function editor($filed_data) {
		$input_arr = array();
		$settings = (empty($filed_data['settings'])) ? array() : string2array($filed_data['settings']) ;

		$width = (isset($settings['width'])) ? $settings['width'] : '' ;
		$height = (isset($settings['height'])) ? $settings['height'] : '' ;

		$input['input'] = form::editer("data[{$filed_data['name']}]", $this->update_infos, $width, $height, $filed_data['name']);
		$input['name'] = $filed_data['title'];
		return $input;
	}

	/**
	 * 图片上传
	 * @param $filed_data 字段数据
	 * @return array
	 */
	public function image($filed_data) {
		$input_arr = array();
		$settings = (empty($filed_data['settings'])) ? array() : string2array($filed_data['settings']) ;

		$update_infos = array();
		if(strpos($this->update_infos, ',') !== false) {
			$update_infos = explode(',', $this->update_infos);
			$update_infos = array_filter($update_infos);
		}

		$input['input'] = form::uploadfile($filed_data['name'], 'images', "data[{$filed_data['name']}][]", $update_infos);
		$input['name'] = $filed_data['title'];
		return $input;
	}

	/**
	 * 数字
	 * @param $filed_data 字段数据
	 * @return array
	 */
	public function number($filed_data) {
		$input_arr = array();
		$input['input'] = form::input("data[{$filed_data['name']}]", $this->update_infos);
		$input['name'] = $filed_data['title'];
		return $input;
	}

	/**
	 * 时间日期
	 * @param $filed_data 字段数据
	 * @return array
	 */
	public function datetime($filed_data) {
		$input_arr = array();
		$settings = (empty($filed_data['settings'])) ? array() : string2array($filed_data['settings']) ;
		$date_format = (isset($settings['date_format'])) ? $settings['date_format'] : 'yyyy-MM-dd' ;

		$date = '';
		switch($settings['date_format']) {
			case 'yyyy-MM':
				$date = (!empty($this->update_infos)) ? date('Y-m', $this->update_infos) : '' ;
			break;
			case 'yyyy-MM-dd';
				$date = (!empty($this->update_infos)) ? date('Y-m-d', $this->update_infos) : '' ;
			break;
			case 'yyyy-MM-dd HH:mm:ss';
				$date = (!empty($this->update_infos)) ? date('Y-m-d H:i:s', $this->update_infos) : '' ;
			break;
		}

		$input['input'] = form::datetime("data[{$filed_data['name']}]", $date, $date_format);
		$input['name'] = $filed_data['title'];
		return $input;
	}

	/**
	 * 文件上传
	 * @param $filed_data 字段数据
	 * @return array
	 */
	public function uploadfile($filed_data) {
		$input_arr = array();
		$settings = (empty($filed_data['settings'])) ? array() : string2array($filed_data['settings']) ;

		$update_infos = array();
		if(strpos($this->update_infos, ',') !== false) {
			$update_infos = explode(',', $this->update_infos);
			$update_infos = array_filter($update_infos);
		}

		$input['input'] = form::uploadfile($filed_data['name'], 'files', "data[{$filed_data['name']}][]", $update_infos);
		$input['name'] = $filed_data['title'];
		return $input;
	}

	/**
	 * 选项
	 * @param $filed_data 字段数据
	 * @return array
	 */
	public function box($filed_data) {
		$input_arr = array();
		$settings = (empty($filed_data['settings'])) ? array() : string2array($filed_data['settings']) ;

		if(isset($settings['boxtype'])) {
			$boxtype = $settings['boxtype'];
		} else {
			return false;
		}


		$filed_default_value = (isset($settings['filed_default_value'])) ? $settings['filed_default_value'] : '' ;
		//如果有修改数据 那么默认就为数组或者单字符串
		if(!empty($this->update_infos)) {
			$filed_default_value = $this->update_infos;
			//select下拉列表 去掉逗号
			if($boxtype == 'select') $filed_default_value = str_replace(',', '', $this->update_infos);
		}

		$box_data = (isset($settings['box'])) ? explode("\n", $settings['box']) : array() ;

		$box_data  = $this->box_explode($box_data);

		$input['input'] = form::$boxtype("data[{$filed_data['name']}][]", $box_data, $filed_default_value);
		$input['name'] = $filed_data['title'];
		return $input;
	}

	/**
	 * 拆分选项
	 * @param $data 拆分数据 数组
	 * @param $exp 拆分符号
	 * @return array
	 */
	public function box_explode($data, $exp = '|') {
		if(empty($data) || !is_array($data)) return array();
		$newdata = array();
		foreach($data as $k => $v) {
			$arr = explode($exp, $v);
			if(empty($arr[1])) continue;
			$newdata[trim($arr[1])] = $arr[0];
		}

		return $newdata;
	}
}
