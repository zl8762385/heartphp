<?php
/**
 *  form.class.php  表单
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class form {

	static public function static_func($type) {
		switch($type) {
			case 'js':
				return C('static_path_js', 'config');
			break;
			case 'css':
				return C('static_path_css', 'config');
			break;
			case 'images':
				return C('static_path_images', 'config');
			break;
			case 'domain':
				return C('domain', 'config');
			break;
		}

	}
	/**
	 * jquery.uploadify 上传图片
	 * @param $id ID
	 * @param $upload_type 上传类型 images
	 * @param $form_name 表单name
	 * @param $default_value 默认值
	 * @return html
	 */
	static public function uploadfile($id = 'uploadify', $upload_type = 'images', $form_name = '', $default_value = array()) {
		$path_js = self::static_func('js');

		$path_images = self::static_func('images');
		$domain = self::static_func('domain');
		$ref_url = urlencode($_SERVER['REQUEST_URI']);

		$swfupload_config = C($upload_type, 'swfupload');

		$swfupload_type = (isset($swfupload_config['type'])) ? $swfupload_config['type'] : '' ;

		//button images and files
		$buttom_class =  ($swfupload_type == 'images')? 'IMGButtonUploadText1_61x22.png' : 'FileButtonUploadText1_61x22.png' ;

		//拆分 后缀
		$file_types = '*.*';
		if($swfupload_config) {
			$new_ext = $swf_ext = array();
			isset($swfupload_config['ext']) && $swf_ext = explode('|', $swfupload_config['ext']);

			foreach($swf_ext as $k => $v) {
				$new_ext[$k] = '*.'.$v;
			}

			$file_types = implode(';', $new_ext);
		}

		//是否多图
		$allow_multi = (isset($swfupload_config['allow_multi'])) ? $swfupload_config['allow_multi'] : 1 ;//默认不允许多图,多图默认最多10个


		$upload_url = ($swfupload_type == 'files') ? 'uploader_files' : 'uploader_images' ;

		//修改数据 默认图片
		$update_html = $update_input_html = '';

		if(is_array($default_value)) {

			foreach($default_value as $k_img => $v) {

				$update_input_html .= '<input type="hidden" name="data['.$form_name.'][]" node-type="input_'.$id.'_'.$k_img.'" value="'. $v['filename'] .'" />';
				$update_html .= '<div class="uploadfile_images_td" node-type="div_'.$id.'_'.$k_img.'">';
					
						if($swfupload_type == 'images'){
							$update_html .= '<a href="'. $v['filename'] .'" target="_blank">';
								$update_html .= '<img src="'. $v['filename'].'"/>';
							$update_html .= '</a>';
						} else {
							$update_html .= '<input type="text" value="'. $v['filename'] .'"/>';
						}
						$update_html .= '<br/><input name="data['.$form_name.'][pic_title]['.$v['filename'].']" class="text w_15" type="text" value="'.$v['title'].'"/>';
					$update_html .= '<div><input type="button" value="删除" onclick="del_uploadfiles'.$id.'('.$k_img.')"/></div>';
				$update_html .= '</div>';	
			}
		}

		$html =<<<EOF
		<script type="text/javascript" src="{$path_js}/swfupload/swfupload.js"></script>
		<script type="text/javascript" src="{$path_js}/swfupload/jquery.swfupload.js"></script>
		<script type="text/javascript" src="{$path_js}/swfupload/swfupload.cookies.js"></script>
		<script type="text/javascript">
		function del_uploadfiles{$id}(id_numbers) {
			$('[node-type=input_{$id}_'+id_numbers+']').remove();
			$('[node-type=div_{$id}_'+id_numbers+']').remove();
			$('[node-type=input__{$id}_'+id_numbers+']').remove();
			$('[node-type=div__{$id}_'+id_numbers+']').remove();
		}

		$(function(){
			$('#{$id}_id').swfupload({
				upload_url: "{$domain}index.php?d=admin&c=upload&a={$upload_url}&ref={$ref_url}",
				file_types : "{$file_types}",
				file_types_description : "All Files",
				//file_upload_limit : {$allow_multi},
				file_queue_limit: {$allow_multi},
				flash_url : "{$path_js}/swfupload/swfupload.swf",
				file_post_name : 'uploaddata',
				button_image_url : '{$path_js}/swfupload/{$buttom_class}',
				button_width : 61,
				button_height : 22,
				button_placeholder : $('#button{$id}')[0],
				debug: true,
				custom_settings : {something : "here"},
				debug_handler : function (d) {
					// console.info(d);
				}
			})
				.bind('swfuploadLoaded', function(event){
					$('#log').append('<li>Loaded</li>');
				})
				.bind('fileQueued', function(event, file){
					//console.log(file+'===');
					$('#log').append('<li>File queued - '+file.name+'</li>');
					// start the upload since it's queued
					$(this).swfupload('startUpload');
				})
				.bind('fileQueueError', function(event, file, errorCode, message){
					$('#log').append('<li>File queue error - '+message+'</li>');
				})
				.bind('fileDialogStart', function(event){
					//console.log('<li>File dialog start</li>')
					$('#log').append('<li>File dialog start</li>');
				})
				.bind('fileDialogComplete', function(event, numFilesSelected, numFilesQueued){
					if(numFilesSelected > numFilesQueued) {
						alert('超出数量');
					}
					//console.log(numFilesSelected+'=='+numFilesQueued)
					$('#log').append('<li>File dialog complete</li>');
				})
				.bind('uploadStart', function(event, file){
					$('#log').append('<li>Upload start - '+file.name+'</li>');
				})
				.bind('uploadProgress', function(event, file, bytesLoaded){
					$('#log').append('<li>Upload progress - '+bytesLoaded+'</li>');
				})
				.bind('uploadSuccess', function(event, file, serverData){
					var serverData = $.parseJSON(serverData);
					if(!serverData) {
						alert('上传文件出错，请联系管理员！.');
						return false;
					}
					var files_name = serverData.filepath+serverData.filename;
					var length = $('[id={$id}]').length;
					var upload_type = '{$swfupload_type}';
					//console.info(serverData);
					//console.log(serverData)

					switch(serverData.flag) {
						case '1':
						var html = '';
						html += '<div class="uploadfile_images_td" node-type="div__{$id}_'+length+'">';
					        
					        	if(upload_type == 'images') {
					        		html += '<a href="'+ files_name +'" target="_blank">';
					        			html += '<img src="'+ files_name +'"/>';
					        		html += '</a>';
					        	} else {
					        		html += '<input type="text" value="'+ files_name +'"/>';
					        	}
				        		html += '<br/><input name="data[{$form_name}][pic_title]['+files_name+']" class="text w_15" type="text" value=""/>';
					            
					        
					       html += '<div><input type="button" value="删除" onclick="del_uploadfiles{$id}('+length+')"/></div>';

					    html += '</div>';

					    //$('#{$id}').
						$('#uploadfiles_{$id}').append(html).prepend('<input node-type="input__{$id}_'+length+'" type="hidden" name="data[{$form_name}][]" id="{$id}" value="'+ files_name +'" />');
						break;
						case '-1':
							alert('文件类型不允许.');
						break;
						case '-2':
							alert('文件大小超出限制.');
						break;
					}
					//$('#log').append('<li>Upload success - '+file.name+'</li>');
				})
				.bind('uploadComplete', function(event, file){
					//console.info(file);
					$('#log').append('<li>Upload complete - '+file.name+'</li>');
					// upload has completed, lets try the next one in the queue
					$(this).swfupload('startUpload');
				})
				.bind('uploadError', function(event, file, errorCode, message){
					//console.info(event);
					$('#log').append('<li>Upload error - '+message+'</li>');
				});
		});	
		</script>
		<div id="{$id}_id">
		      <input type="button" id="button{$id}"/>
		      <input type="hidden" name="data[{$form_name}][]" value=''/>
		      <div class="uploadfile_images_table" id="uploadfiles_{$id}">
		      		{$update_input_html}
			    	{$update_html}
			  </div>
	    </div>
EOF;
		return $html;
	}
	/**
	 * 在线编辑器
	 * @param $name 编辑器名称
	 * @param $default_value 编辑默认信息 例如修改
	 * @param $width width
	 * @param $height height
	 * @param $id id
	 * @return
	 */
	static public function editer($name, $defult_value='', $width="600", $height="300", $id = '') {
		$width = intval($width);
		$height = intval($height);
		$domain = self::static_func('domain');
		$ref_url = urlencode($_SERVER['REQUEST_URI']);

		$path_js = self::static_func('js');
		$path_css = self::static_func('css');
		$id = (empty($id)) ? $name : $id ;
		$html =<<<EOF
		<link href="{$path_css}/kindeditor/themes/default/default.css" type="text/css"/>
		<script type="text/javascript" src="{$path_js}/kindeditor/kindeditor.js"></script>
		<script>
			KindEditor.ready(function(K) {
				var editor_{$id} = K.create('textarea[id="{$id}"]', {
					uploadJson :'{$domain}index.php?d=admin&c=upload&a=uploader_images&type=editer&ref={$ref_url}',
					
					items : ['source', '|', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', '|', 'selectall', '-', 'title', 'fontname', 'fontsize', '|', 'textcolor', 'bgcolor', 'bold','italic', 'underline', 'strikethrough', 'removeformat', '|', 'image', 'flash', 'media', 'advtable', 'hr', 'emoticons', 'link', 'unlink']
				});
			});

		</script>
		<textarea name="{$name}" id="{$id}" style="width:{$width}px;height:{$height}px;visibility:hidden;">{$defult_value}</textarea>
EOF;

		return $html;
	}


	/**
	 * 时间日期 年月日 时分秒
	 * 日历 访问地址：http://www.my97.net/dp/demo/index.htm 方便日后修改查找，哈哈哈哈哈
	 * @param $name
	 * @param $def_value 输入框默认值
	 * @param $dateFmt 格式化日期
	 * @param $js_event JS事件
	 * @return 
	 */
	static public function datetime($name, $def_value = '', $dateFmt = 'yyyy-MM-dd', $js_event = 'onfocus', $class='text w_15') {
		$path_js = self::static_func('js');

		$html =<<<EOF
		<script charset="utf-8" src="{$path_js}/datapicker/WdatePicker.js"></script>
		<input id="{$name}" name="{$name}" value="{$def_value}" type="text" class="{$class}" onfocus="WdatePicker({dateFmt:'{$dateFmt}'})"/>
EOF;
		return $html;
	}

	/**
	 * input
	 * @param $name
	 * @param $value 文本框value
	 * @param $type  文本框type
	 * @param $class input class
	 * @param $id id
	 * @param $style style
	 * @return text html
	 */
	static public function input($name, $value = '', $type = 'text', $class = 'dfinput', $id = '', $style='') {
		$id = (empty($id)) ? $name : $id ;
		$style = (empty($style)) ? '' : 'style="'.$style.'"' ;
		return '<input '.$style.' class="'.$class.'" value="'.$value.'" type="'.$type.'" id="'.$id.'" name="'.$name.'" />';
	}

	/**
	 *  <{echo form::radio('is_display', '1', '1')}>
	 * @param $name
	 * @param $value value 传递数组 array('选项值' => '选项名称')
	 * @param $checked_value checked key
	 * @return radio html
	 */
	static public function radio($name, $value = '', $checked_value = '') {
		
		if(!is_array($value)) {
			$checked = ($value == $checked_value) ? 'checked' : '' ;
			return '<input type="radio" value="'.$value.'" id="'.$name.'" name="'.$name.'" '.$checked.'/>';
		} else {
			$value_checked = array();
			if(strpos($checked_value, ',') !== false) {
				$value_checked = explode(',', $checked_value);
			} else {
				$value_checked = array($checked_value);
			}

			$html = '';
			foreach($value as $k => $v) {
				$checked = '';
				$checked = (in_array($k, $value_checked)) ? 'checked' : '' ;
				$html .= '<input type="radio" value="'.$k.'" id="'.$name.'" name="'.$name.'" '.$checked.'/> '.$v;
			}

			return $html;
		}
		
	}

	/**
	 *  <{echo form::checkbox('is_display', '1', '1')}> 
	 * @param $name
	 * @param $value value 传递数组过来 array('选项值' => '选项名称')
	 * @param $checked_value 可以为数组或字符串
	 * @return radio html
	 */
	static public function checkbox($name, $value = '', $checked_value = '') {

		if(!is_array($value)) {//字符串执行
			$checked = ($value == $checked_value) ? 'checked' : '' ;
			return '<input type="checkbox" value="'.$value.'" id="'.$name.'" name="'.$name.'" '.$checked.'/>';
		} else {//数组执行
			$value_checked = array();
			if(strpos($checked_value, ',') !== false) {
				$value_checked = explode(',', $checked_value);
			} else {
				$value_checked = array($checked_value);
			}

			$html = '';
			foreach($value as $k => $v) {
				$checked = '';
				$checked = (in_array($k, $value_checked)) ? 'checked' : '' ;
				$html .= '<input type="checkbox" value="'.$k.'" id="'.$name.'" name="'.$name.'" '.$checked.'/> '.$v;
			}

			return $html;
		}
		
	}

	/**
	 * textarea
	 * @param $name 名称
	 * @param $value value
	 * @param $rows rows
	 * @param $cols cols
	 * @param $class classname
	 * @param $style style
	 */
	static public function textarea($name, $value='', $rows=10, $cols=50, $class = '', $style='') {
		$html = '<textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'" class="'.$class.'" style="'.$style.'">'.$value.'</textarea>';
		return $html;
	}

	/**
	 *  <{echo form::select('select123', $select, '1')}>
	 * @param $name
	 * @param $data array
	 * @param $selected_key 默认选中KEY
	 * @param $first_option 第一个OPTIONS
	 * @param $class css classname
	 * @param $onevent js event[change, click, mouseover, mouseout ...]
	 * @param $style style
	 * @return select html
	 */
	static public function select($name, $data,$selected_key = '', $first_option = '', $class = 'select2', $onevent = '', $style = '', $id = '') {
	
		$style = (empty($style)) ? '' : 'style="'.$style.'"' ;
		$id = (empty($id)) ? $name : $id ; 
		$html = '<select name="'.$name.'" id="'.$id.'" class="system_select '.$class.'" '.$onevent.' '.$style.'>';
			$html.= $first_option;
			if(is_array($data)) {
			 	foreach($data as $k => $v) {
				 	$selected = '';
			 		if($k == $selected_key) $selected = 'selected="selected"';
		            $html .= '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
			 	}
			}
        $html .= '</select>';

        return $html;
	}

}