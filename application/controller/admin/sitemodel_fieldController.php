<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 后台管理系统 网站模型_字段s
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang <zl8762385@163.com> <qq:3677989>
 * @lastmodify			2013.04.07
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class sitemodel_fieldController extends helper_baseadminController {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 字段设置
	 */
	public function field_settings() {
		$filedtype = gpc('filedtype');
		$filedtype_func = (empty($filedtype)) ? $this->show_message('请求错误!') : $filedtype.'_settings' ;

		$this->db_filed = D('model_filed_model');
		$this->filed_id = gpc('id');//模型ID
		$this->read_fieldinfo = $this->get_field_settings();//更新字段设置，从数据库模型中读取.
	
		$this->$filedtype_func();
	}

	/**
	 * 获取字段类型 配置相关信息(settings)
	 */
	public function get_field_settings() {
		if(empty($this->filed_id)) return false;
		$filed_info = $this->db_filed->get_one($this->filed_id);

		if(isset($filed_info['settings'])) $filed_info['settings'] = json_decode($filed_info['settings'],1);
		return $filed_info;
	}

	public function datetime_settings() {
		$data = array();
		$data['filetype'] = 'text';

		//读取模型相关配置
		$r_date_format = (isset($this->read_fieldinfo['settings']['date_format'])) ? $this->read_fieldinfo['settings']['date_format'] : 50 ;

		//生成文本框
		$select = array('yyyy-MM' => 'yyyy-MM', 'yyyy-MM-dd' => 'yyyy-MM-dd', 'yyyy-MM-dd HH:mm:ss' => 'yyyy-MM-dd HH:mm:ss');
		$date_format = form::select('settings[date_format]', $select, $r_date_format);
		//html
		$data['settings'] =<<<EOF
			<p>{$date_format}</p>
EOF;
		
		$data['settings'] = str_replace('#filed_html#', $data['settings'], $this->template_html());
		echo json_encode($data);
	}
	/**
	 * 单行文本
	 */
	public function text_settings() {
		$data = array();
		$data['filetype'] = 'text';

		//读取模型相关配置
		$r_size = (isset($this->read_fieldinfo['settings']['size'])) ? $this->read_fieldinfo['settings']['size'] : 50 ;
		$r_text_style = (isset($this->read_fieldinfo['settings']['text_style'])) ? $this->read_fieldinfo['settings']['text_style'] : 50 ;
		$r_is_pwd = (isset($this->read_fieldinfo['settings']['is_pwd'])) ? $this->read_fieldinfo['settings']['is_pwd'] : 0 ;
		$r_default_value = (isset($this->read_fieldinfo['settings']['default_value'])) ? $this->read_fieldinfo['settings']['default_value'] : '' ;

		//生成文本框
		$size = form::input('settings[size]', $r_size);
		$text_style = form::input('settings[text_style]', $r_text_style);
		$default_value = form::input('settings[default_value]', $r_default_value);
		$is_pwd_yes = form::radio('settings[is_pwd]', '1', $r_is_pwd);
		$is_pwd_no = form::radio('settings[is_pwd]', '0', $r_is_pwd);

		//html
		$data['settings'] =<<<EOF
			<p><span>字段长度：</span>{$size}</p>
			<p><span>样式：</span>{$text_style}</p>
			<p><span>默认值：</span>{$default_value}</p>
			<p><span>是否为密码框：</span>{$is_pwd_yes} &nbsp;是 {$is_pwd_no} &nbsp;否</p> 
EOF;
		
		$data['settings'] = str_replace('#filed_html#', $data['settings'], $this->template_html());
		echo json_encode($data);
	}

	/**
	 * 多行文本
	 */
	public function textarea_settings() {
		$data = array();
		$data['filetype'] = 'textarea';

		//读取模型相关配置
		$r_default_value = (isset($this->read_fieldinfo['settings']['default_value'])) ? $this->read_fieldinfo['settings']['default_value'] : '' ;
		$r_width = (isset($this->read_fieldinfo['settings']['width'])) ? $this->read_fieldinfo['settings']['width'] : '200px' ;
		$r_height = (isset($this->read_fieldinfo['settings']['height'])) ? $this->read_fieldinfo['settings']['height'] : '50px' ;

		//生成文本框
		$default_value = form::input('settings[default_value]', $r_default_value);
		$width = form::input('settings[width]', $r_width);
		$height = form::input('settings[height]', $r_height);
		//html
		$data['settings'] =<<<EOF
			<p><span>高度：</span>{$height}</p>
			<p><span>宽度：</span>{$width}</p>
			<p><span>默认值：</span>{$default_value}</p>
EOF;
		
		$data['settings'] = str_replace('#filed_html#', $data['settings'], $this->template_html());
		echo json_encode($data);
	}

	/**
	 * 编辑器
	 */
	public function editor_settings() {
		$data = array();
		$data['filetype'] = 'editor';

		//读取模型相关配置
		$r_width = (isset($this->read_fieldinfo['settings']['width'])) ? $this->read_fieldinfo['settings']['width'] : '670px' ;
		$r_height = (isset($this->read_fieldinfo['settings']['height'])) ? $this->read_fieldinfo['settings']['height'] : '350px' ;


		//生成文本框
		$width = form::input('settings[width]', $r_width);
		$height = form::input('settings[height]', $r_height);
		//html
		$data['settings'] =<<<EOF
			<p><span>高度：</span>{$height}</p>
			<p><span>宽度：</span>{$width}</p>
EOF;
		
		$data['settings'] = str_replace('#filed_html#', $data['settings'], $this->template_html());
		echo json_encode($data);
	}

	/**
	 * 选项
	 */
	public function box_settings() {
		$data = array();
		$data['filetype'] = 'box';

		//读取模型相关配置
		$r_filed_default_value = (isset($this->read_fieldinfo['settings']['filed_default_value'])) ? $this->read_fieldinfo['settings']['filed_default_value'] : '' ;
		$r_box = (isset($this->read_fieldinfo['settings']['box'])) ? $this->read_fieldinfo['settings']['box'] : '选项名称1|选项值1' ;
		$r_boxtype = (isset($this->read_fieldinfo['settings']['boxtype'])) ? $this->read_fieldinfo['settings']['boxtype'] : '' ;
		$r_filedtype = (isset($this->read_fieldinfo['settings']['filedtype'])) ? $this->read_fieldinfo['settings']['filedtype'] : '' ;

		//生成文本框
		$filed_default_value = form::input('settings[filed_default_value]', $r_filed_default_value);
		$box = form::textarea('settings[box]', $r_box, '', '', 'textinput', 'width:150px;height:150px;');
		$radio = form::radio('settings[boxtype]', 'radio', $r_boxtype);
		$checkbox = form::radio('settings[boxtype]', 'checkbox', $r_boxtype);
		$select = form::radio('settings[boxtype]', 'select', $r_boxtype);
		$multiple = form::radio('settings[boxtype]', 'multiple', $r_boxtype);

		$filedtype = array(
			'varchar-100' => '字符 VARCHAR',
			'tinyint-3' => '整数 TINYINT(3',
			'smallint-5' => '整数 SMALLINT(5)',
			'mediumint-8' => '整数 MEDIUMINT(8)',
			'int-10' => '整数 INT(10)'
		);
		$filedtype = form::select('settings[filedtype]', $filedtype, $r_filedtype);
		//html
		$data['settings'] =<<<EOF
		<table class="system_param_select">
			<tr>
				<td style="vertical-align:middle;width:100px;text-align:right">选项列表：</td>
				<td style="width:560px;">{$box}</td>
			</tr>
			<tr style="height:55px;">
				<td></td>
				<td>
				 {$radio}单选按钮 
				 {$checkbox}复选框 
				 {$select}下拉框 
				 <!--{$multiple}多选列表框-->
				</td>
			</tr>
			<tr style="height:55px;">
				<td style="text-align:right;">字段类型：</td>
				<td>{$filedtype}</td>
			</tr>
			<tr style="height:55px;">
				<td style="text-align:right;">默认值：</td>
				<td>{$filed_default_value}</td>
			</tr>
		</table>
EOF;
		
		$data['settings'] = str_replace('#filed_html#', $data['settings'], $this->template_html());
		echo json_encode($data);
	}
	/**
	 * 相关参数模板
	 */
	public function template_html() {
		$html =<<<EOF
		<p>
            <label class="colorred">相关参数设置</label>
            <div class="param_settings">
              #filed_html#
            </div>
        </p>
EOF;
		return $html;
	}

	public function __call($name, $arg) {
		echo $name.'不存在!';
	}
}