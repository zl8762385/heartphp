<?php
//if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 后台管理系统 上传文件
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:3677989>
 * @lastmodify			2013.04.12
 * extends helper_baseadminController
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class uploadController{//  extends helper_baseadminController

	public function __construct() {
		//parent::__construct();
		$this->upload_files = SYSTEM_PATH.'/data/uploadfiles/';//上传文件路径
		$this->swfupload_config = C('','swfupload');//swfupload 配置信息
		//$this->allow_images = 'gif|png|bmp|jpeg|jpg|psd';//允许的图片类型
		$this->db_attachment = D('attachment_model');
		$this->domain = C('domain', 'config');

	}

	/**
	 * 上传图片
	 * @return json
	 */
	public function uploader_images() {
		if(empty($_FILES)) return false;
		$type = gpc('type');
		$ref_url = urldecode(gpc('ref'));
		$swfupload = $this->swfupload_config['images'];

		$upload = new upload($swfupload['ext'], $swfupload['filesize'], 'uploaddata');//20971520 = 20M

		$upload->set_dir($this->upload_files.$swfupload['dir'],'{y}/{m}/{d}');
		$fs = $upload->execute();
	
		if($fs[0]['flag'] == '1') {//上传成功以后才记录附件操作
			$return_fs = $this->attachment($ref_url, $fs);//附件管理
			!$return_fs && $return_fs = array();
		}

		switch($type) {
			case 'editer'://编辑器上传文件啊
				$error_data = array();
				$fs = $fs[0];
				if($fs['flag'] != '1') {
					if($fs['flag'] == '-1') $error_data = array('error' => 1, 'message' => '文件类型不允许.');
					if($fs['flag'] == '-2') $error_data = array('error' => 1, 'message' => '文件大小超出限制.');
					echo json_encode($error_data);
				} else {
					$filename = $this->domain.$return_fs['filepath'].$return_fs['filename'];
					echo json_encode(array('error' => 0, 'url' => $filename));
				}
			break;
			default:
				$fs = $fs[0];
				if($fs['flag'] != '1') {
					if($fs['flag'] == '-1') $error_data = array('error' => 1, 'message' => '文件类型不允许.');
					if($fs['flag'] == '-2') $error_data = array('error' => 1, 'message' => '文件大小超出限制.');
					echo json_encode($error_data);
				} else {
					echo json_encode($return_fs);
				}
		}
		
	}

	/**
	 * 上传文件
	 * @return json
	 */
	public function uploader_files() {

		if(empty($_FILES)) return false;
		$ref_url = urldecode(gpc('ref'));
		$swfupload = $this->swfupload_config['files'];

		$upload = new upload($swfupload['ext'], $swfupload['filesize'], 'uploaddata');//20971520 = 20M

		$upload->set_dir($this->upload_files.$swfupload['dir'],'{y}/{m}/{d}');
		$fs = $upload->execute();
		$return_fs = $this->attachment($ref_url, $fs);//附件管理
		!$return_fs && $return_fs = array();
		echo json_encode($return_fs);
	}
	/**
	 * 附件管理
	 */
	public function attachment($ref, $upload_data) {
		$newdata = $url_query = array();

		foreach($upload_data as $v) {
			
			$filepath = str_replace(SYSTEM_PATH, '', $v['dir']);
			$ref && $url_query = parse_url($ref);
			$url_query && parse_str($url_query['query']);
			$newdata['ref_url'] = $ref;
			$newdata['filename'] = $v['name'];
			$newdata['filepath'] = $filepath;
			$newdata['fileext'] = $v['fileext'];
			$newdata['catid'] = (isset($catid) && !empty($catid)) ? $catid : 0 ;

			if($this->db_attachment->insert($newdata)) {
				$newdata['flag'] = $v['flag'];
				return $newdata;
			}
		}
		
	}

}
