<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  template.class.php   模板解析类
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang
 * @lastmodify			2013.03.20
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class template {
	private $vars = array();
	private $conf = '';
	private $tpl_name = 'index';//如果模板不存在 会查找当前 controller默认index模板
	private $tpl_suffix = '.html';//如果CONFIG没配置默认后缀 则显示
	private $tpl_compile_suffix= '.tpl.php';//编译模板路径
	private $template_tag_left = '<{';//模板左标签
	private $template_tag_right = '}>';//模板右标签
	private $template_c = '';//编译目录
	private $template_path = '';//模板完整路径 D:/PHPnow/htdocs/heartphp/tpl/ad/index/
	private $template_name = '';//模板名称 index.html


	//定义每个模板的标签的元素
	private $tag_foreach = array('from', 'item', 'key');
	private $tag_include = array('file');//目前只支持读取模板默认路径

	public function __construct($conf) {
		$this->conf = &$conf;

		$this->template_c = $this->conf['template_config']['template_c'];//编译目录
		$this->_tpl_suffix = $this->tpl_suffix();
	}


	/**
	 * 重构str_replace
	 * @param $search 搜索字符串
	 * @param $replace 替换字符串
	 * @param $content 内容
	 * @return html
	 */
	private function str_replace($search, $replace, $content) {
		if(empty($search) || empty($replace) || empty($content)) return false;
		return str_replace($search, $replace, $content);
	}

	/**
	 * preg_match_all
	 * @param $pattern 正则
	 * @param $content 内容
	 * @return array
	 */

	private function preg_match_all($pattern, $content) {
		if(empty($pattern) || empty($content)) core::show_error('查找模板标签失败!');
		preg_match_all("/".$this->template_tag_left.$pattern.$this->template_tag_right."/is", $content, $match);
		return $match;
	}
	/**
	 * 模板文件后缀 
	 */	
	public function tpl_suffix() {
		$tpl_suffix = empty($this->conf['template_config']['template_suffix']) ? 
							$this->tpl_suffix : 
							$this->conf['template_config']['template_suffix'] ;
		return $tpl_suffix;
	}

	/**
	 *	此处不解释了
	 *  @return 
	 */	
	public function assign($key, $value) {
		$this->vars[$key] = $value;
	}

	/**
	 *	渲染页面
	 * 	@param 
	 *  @return 
	 */	
	public function display($filename = '', $view_path = '') {
		$tpl_path_arr = $this->get_tpl($filename, $view_path);//获取TPL完整路径 并且向指针传送路径以及名称
		if(!$tpl_path_arr) core::show_error($filename.$this->_tpl_suffix.'模板不存在');

		//编译开始
		$this->compile();
	}

	/**
	 *	编译控制器
	 * 	@param 
	 *  @return 
	 */	
	private function compile() {
		$filepath = $this->template_path.$this->template_name;

		$include_file = $this->template_replace($this->read_file($filepath));//解析
		if($include_file) {
			extract($this->vars, EXTR_SKIP);
			@include $include_file;
		}
	}

	/**
	 *	解析模板语法
	 * 	@param  string  $str   模板内容
	 *  @return 编译过的PHP模板文件名
	 */	
	private function template_replace($str) {
		if(empty($str)) core::show_error('模板内容为空！');

		

		$compile_dirpath = $this->check_temp_compile();//检查模板编译鲁姆
		$vars_template_c_name = str_replace($this->_tpl_suffix, '', $this->template_name);

		//处理编译头部
		$compile_path = $compile_dirpath.$vars_template_c_name.$this->tpl_compile_suffix;//编译文件
		if(is_file($compile_path)) {
			$header_content = $this->get_compile_header($compile_path);
			$compile_date = $this->get_compile_header_comment($header_content);

			//如果文件过期编译   当模板标签有include并且有修改时 也重新编译
			if(filemtime($this->template_path.$this->template_name) > $compile_date|| DEBUG) {
				$ret_file = $this->compile_file($vars_template_c_name, $str, $compile_dirpath);
			} else {
				$ret_file = $compile_path;
			}
		} else {//编译文件不存在 创建他
			$ret_file = $this->compile_file($vars_template_c_name, $str, $compile_dirpath);
		}

		return $ret_file;
	}


	/**
	 *	模板文件主体
	 * 	@param  string  $str    内容
	 *  @return html
	 */
	private function body_content($str) {
		//解析
		$str = $this->parse($str);

		$header_comment = "Create On##".time()."|Compiled from##".$this->template_path.$this->template_name;
		$content = "<? if(!defined('IS_HEARTPHP')) exit('Access Denied');/*{$header_comment}*/?>\r\n$str";

		return $content;
	}

	/**
	 *  开始解析相关模板标签
	 * @param $content 模板内容
	 */
	private function parse($content) {
		echo 'content';
		//foreach
		$content = $this->parse_foreach($content);

		//include
		$content = $this->parse_include($content);

		//if
		$content = $this->parse_if($content);

		//elseif
		$content = $this->parse_elseif($content);

		//模板标签公用部分
		$content = $this->parse_comm($content);	

		//转为PHP代码
		$content = $this->parse_php($content);
		return $content;
	}

	/**
	 * 转换为PHP
	 * @param $content html 模板内容
	 * @return html 替换好的HTML
	 */
	private function parse_php($content){
		if(empty($content)) return false;
		$content = preg_replace("/".$this->template_tag_left."(.+?)".$this->template_tag_right."/is", "<?php $1 ?>", $content);

		return $content;
	}
	/**
	 * if判断语句
	 * <{if empty($zhang)}>
	 * zhang
	 * <{elseif empty($liang)}>
	 * 	liang
	 * <{else}>
	 * 	zhangliang
	 * <{/if}>
	 */
	private function parse_if($content) {
		if(empty($content)) return false;
		//preg_match_all("/".$this->template_tag_left."if\s+(.*?)".$this->template_tag_right."/is", $content, $match);

		$match = $this->preg_match_all("if\s+(.*?)", $content);
		if(!isset($match[1]) || !is_array($match[1])) return $content;

		foreach($match[1] as $k => $v) {
			$s = preg_split("/\s+/is", $v);
			$s = array_filter($s);
			$content = str_replace($match[0][$k], "<?php if({$s[0]}) { ?>", $content);
		}

		return $content;
	}

	private function parse_elseif($content) {
		if(empty($content)) return false;
		//preg_match_all("/".$this->template_tag_left."elseif\s+(.*?)".$this->template_tag_right."/is", $content, $match);
		$match = $this->preg_match_all("elseif\s+(.*?)", $content);
		if(!isset($match[1]) || !is_array($match[1])) return $content;

		foreach($match[1] as $k => $v) {
			$s = preg_split("/\s+/is", $v);
			$s = array_filter($s);
			$content = str_replace($match[0][$k], "<?php } elseif ({$s[0]}) { ?>", $content);
		}

		return $content;
	}
	/**
	 * 解析 include    include标签不是实时更新的  当主体文件更新的时候 才更新标签内容，所以想include生效 请修改一下主体文件
	 * 使用方法 <{include file=""}>
	 * @param $content 模板内容
	 * @return html
	 */
	private function parse_include($content) {
		if(empty($content)) return false;

		//preg_match_all("/".$this->template_tag_left."include\s+(.*?)".$this->template_tag_right."/is", $content, $match);
		$match = $this->preg_match_all("include\s+(.*?)", $content);
		if(!isset($match[1]) || !is_array($match[1])) return $content;

		foreach($match[1] as $match_key => $match_value) {
			$a = preg_split("/\s+/is", $match_value);

			$new_tag = array();
			//分析元素
			foreach($a as $t) {
				$b = explode('=', $t);
				if(in_array($b[0], $this->tag_include)) {
					if(!empty($b[1])) {
						$new_tag[$b[0]] = str_replace("\"", "", $b[1]);
					} else {
						core::show_error('模板路径不存在!');
					}
				}
			}

			extract($new_tag);
			//查询模板文件
			foreach($this->conf['view_path'] as $v){
				$conf_view_tpl = $v.$file.$this->_tpl_suffix;//include 模板文件
				if(is_file($conf_view_tpl)) {
					$c = $this->read_file($conf_view_tpl);
					break;
				} else {
					core::show_error('模板文件不存在,请仔细检查 文件:'. $conf_view_tpl);
				}
			} 
	
			$content = str_replace($match[0][$match_key], $c, $content);
		}

		return $content;
			
	}
	/**
	 * 解析 foreach
	 * 使用方法 <{foreach from=$lists item=value key=kk}>
	 * @param $content 模板内容
	 * @return html 解析后的内容
	 */
	private function parse_foreach($content) {
		if(empty($content)) return false;

		//preg_match_all("/".$this->template_tag_left."foreach\s+(.*?)".$this->template_tag_right."/is", $content, $match);
		$match = $this->preg_match_all("foreach\s+(.*?)", $content);
		if(!isset($match[1]) || !is_array($match[1])) return $content;

		foreach($match[1] as $match_key => $value) {
	
			$split = preg_split("/\s+/is", $value);
			$split = array_filter($split);

			$new_tag = array();
			foreach($split as $v) {
				$a = explode("=", $v);
				if(in_array($a[0], $this->tag_foreach)) {//此处过滤标签 不存在过滤
					$new_tag[$a[0]] = $a[1];
				}
			}
			$key = '';

			extract($new_tag);
			$key = ($key) ? '$'.$key.'=>' : '' ;
			$s = '<?php foreach('.$from.' as '.$key.' $'.$item.') { ?>';
			$content = $this->str_replace($match[0][$match_key], $s, $content);

		}

		return $content;
	}

	/**
	 * 匹配结束 字符串
	 */
	private function parse_comm($content) {
		$search = array(
			"/".$this->template_tag_left."\/foreach".$this->template_tag_right."/is",
			"/".$this->template_tag_left."\/if".$this->template_tag_right."/is",
			"/".$this->template_tag_left."else".$this->template_tag_right."/is",

		);

		$replace = array(
			"<?php } ?>",
			"<?php } ?>",
			"<?php } else { ?>"
		);
		$content = preg_replace($search, $replace, $content);
		return $content;
	}
	/**
	 *  检查编译目录	如果没有创建 则递归创建目录
	 * 	@param  string  $path   文件完整路径
	 *  @return 模板内容
	 */
	private function check_temp_compile() {
		//$paht = $this->template_c.
		$tpl_path = $this->get_tpl_path();
		$all_tpl_apth = $this->template_c.$tpl_path;

		if(!is_dir($all_tpl_apth)) {
			$this->create_dir($tpl_path);
		}

		return $all_tpl_apth;
	}
	/**
	 *	读文件
	 * 	@param  string  $path   文件完整路径
	 *  @return 模板内容
	 */
	private function read_file($path) {
		$this->check_file_limits($path, 'r');
		
		$r = fopen($path, 'r');
		$content = fread($r, filesize($path));
		fclose($r);
		return $content;
	}

	/**
	 *	写文件
	 * 	@param  string  $filename   文件名
	 *  @param  string  $content    模板内容
	 *  @return 文件名
	 */
	private function compile_file($filename, $content, $dir) {
		if(empty($filename)) core::show_error("{$filename} Creation failed");

		$content = $this->body_content($content);//对文件内容操作
		echo '开始编译了=====';
		$f = $dir.$filename.$this->tpl_compile_suffix;

		$this->check_file_limits($f, 'w');
		$fp = fopen($f, 'wb');
		fwrite($fp, $content, strlen($content));
		fclose($fp);

		return $f;
	}

	/**
	 * @param  [$path] [路径]
	 * @param  [status] [w=write, r=read]
	 */
	public function check_file_limits($path , $status = 'rw') {
		if(!is_writable($path) && $status == 'w') {
			core::show_error("{$path}<br/>没有写入权限，请检查.");
		} elseif(!is_readable($path) && $status == 'r') {
			core::show_error("{$path}<br/>没有读取权限，请检查.");
		} elseif($status == 'rw') {//check wirte and read
			if(!is_writable($path) || !is_readable($path)) {
				core::show_error("{$path}<br/>没有写入或读取权限，请检查");
			}
		}
	}

	/**
	 *  读取编译后模板的第一行 并分析成数组
	 * 	@param  string  $filepath  文件路径
	 *  @param  number  $line        行数
	 *  @return 返回指定行数的字符串 
	 */
	private function get_compile_header($filepath, $line = 0) {

		$file_arr = file($filepath);
		return $file_arr[0];
	}

	/**
	 * 分析头部注释的日期
	 * 	@param  string  $cotnent  编译文件头部第一行
	 *  @return 返回上一次日期 
	 */
	private function get_compile_header_comment($content) {
		preg_match("/\/\*(.*?)\*\//", $content, $match);
		if(!isset($match[1]) || empty($match[1])) core::show_error('编译错误!');	
		$arr = explode('|', $match[1]);
		$arr_date = explode('##', $arr[0]);

		return $arr_date[1];
	}
	/**
	 *	获取模板完整路径 并返回已存在文件
	 * 	@param  string  $filename   文件名
	 *  @param  string  $view_path  模板路径 暂时没用 
	 *  @return 
	 */
	private function get_tpl($filename, $view_path) {
		empty($filename) && $filename = $this->tpl_name;

		//遍历模板路径
		foreach($this->conf['view_path'] as $path) {
			$view_path = ($tpl_path = $this->get_tpl_path($path)) ? $tpl_path.$filename.$this->_tpl_suffix  : exit(0);

			if(is_file($view_path)) {
				//向指针传送模板路径和模板名称
				$this->template_path = $tpl_path;//
				$this->template_name = $filename.$this->_tpl_suffix;
				return true;
			} else {
				core::show_error($filename.$this->_tpl_suffix.'模板不存在');
			}
		}
	}

	/**
	 *	获取模板路径
	 * 	@param  string  $path   主目录
	 *  @return URL D和M的拼接路径
	 */
	private function get_tpl_path($path = '') {
		core::get_directory_name() && $path_arr[0] = core::get_directory_name();
		core::get_model_name() && $path_arr[1] = core::get_model_name();
		(is_array($path_arr)) ? $newpath = implode('/', $path_arr) : core::show_error('获取模板路径失败!') ;

		return $path.$newpath.'/';
	}

	/**
	 *	创建目录
	 * 	@param  string  $path   目录
	 *  @return 
	 */
	private function create_dir($path){
		if(is_dir($path)) return false;
		$dir_arr = explode('/', $path);
		$dir_arr = array_filter($dir_arr);

		$allpath = '';
		$newdir = $this->template_c;
		foreach($dir_arr as $dir) {
			$allpath = $newdir.'/'.$dir;
			if(!is_dir($allpath)) {
				$newdir = $allpath;
				mkdir($allpath);
				chmod($allpath, 0777);
			} else {
				break;
			}
		}
		return true;
	}

};