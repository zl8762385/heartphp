<?php
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

	//模板标签定义
	private $tag_foreach = array('from', 'item', 'key');//foreach 标签

	public function __construct($conf) {
		$this->conf = &$conf;

		$this->template_c = $this->conf['template_config']['template_c'];//编译目录
		$this->_tpl_suffix = $this->tpl_suffix();
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

		$include_file = $this->template_replace($this->r_file($filepath));//解析
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

		$left = $this->template_tag_left;
		$right = $this->template_tag_right;

		//规则先替换 函数部分，当函数部分全部替换完成 剩下的标签 前部转换成PHP代码.
		//foreach
		/*
			$str = preg_replace("/".$left."foreach\s+item=(\w+)\s+from=(.+?)".$right."/is", "<?php foreach($2 as $$1) { ?>", $str);
			$str = preg_replace("/".$left."\/foreach".$right."/is", "<?php } ?>", $str);
		*/

		///////////////////////////////////////////////////////苍天啊 感觉这个标签不符合我，我准备花时间从新写
		//开始测试单独解析模板引擎
		//$this->parse_foreach($str);
		//将剩下的标签前部转换成PHP代码
		$str = preg_replace("/".$left."(.+?)".$right."/is", "<?php $1 ?>", $str);

		$header_comment = "Create On##".time()."|Compiled from##".$this->template_path.$this->template_name;
		$template = "<? if(!defined('IS_HEARTPHP')) exit('Access Denied');/*{$header_comment}*/?>\r\n$str";

		$vars_template_c = $this->check_template_c();
		$vars_template_c_name = str_replace($this->_tpl_suffix, '', $this->template_name);

		//处理编译头部
		$template_c_filepath = $vars_template_c.$vars_template_c_name.$this->tpl_compile_suffix;//编译文件

		$header_content = $this->get_compile_header($template_c_filepath);
		$prev_compile_date = $this->get_compile_header_comment($header_content);

		$template_filename = $this->template_path.$this->template_name;//模板文件

		if(filemtime($template_filename) > $prev_compile_date) {//对比 模板文件查看是否大于上一次编译时间
			//缓存已经过期
			$return_c_file = $this->c_file($vars_template_c_name, $template, $vars_template_c);//编译模板
		} else {
			$return_c_file = $template_c_filepath;
		}

		return $return_c_file;
	}

	/**
	 *  检查编译目录	
	 * 	@param  string  $path   文件完整路径
	 *  @return 模板内容
	 */
	private function check_template_c() {
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
	private function r_file($path) {
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
	private function c_file($filename, $content, $dir) {
		if(empty($filename)) $this->error("{$filename} Creation failed");
		$filename = $dir.$filename.$this->tpl_compile_suffix;
	
		$fp = fopen($filename, 'wb');
		fwrite($fp, $content, strlen($content));
		fclose($fp);

		return $filename;
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