<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  template.class.php   模板解析类
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
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
	private $template_path = '';//模板完整路径 /home/zhangliang/workspace/webapps/heartphp
	private $template_name = '';//模板名称 index.html
	private $template_label = array();//模板标签运行中加载的文件


	//定义每个模板的标签的元素
	private $tag_foreach = array('from', 'item', 'key');
//	private $tag_include = array('file');//目前只支持读取模板默认路径

	public function __construct($conf) {
		$this->conf = &$conf;
		$this->get_this();
	}
	
	/**
	 * 获取当前THIS相关的参数
	 */
	private function get_this() {
		//编译目录
		$this->template_c = $this->conf['template_config']['template_c'];
		
		//获取模板后缀名
		$this->_tpl_suffix = $this->tpl_suffix();
			
		//获取模板标签函数的路径
		if(isset($this->conf['template_config']['template_label'])) {
			$this->template_label_path = $this->conf['template_config']['template_label'];	
		} else {
			$this->template_label_path = SYSTEM_PATH.'functions/template_label/';
		}
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
	 *	此处不解释了
	 *  @return 
	 */	
	public function assign($key, $value) {
		$this->vars[$key] = $value;
	}

	/**
	 *	渲染页面
	 * 	使用方法 1
	 *	$this->view->display('error', 'comm/');
	 *	默认是指向TPL模版的跟目录，所以comm/就是 tpl/comm/error.html
	 *	使用方法 2
	 *	$this->view->display('errorfile'); 
	 *	默认指向控制器固定的文件夹
	 *  例如你的域名是 http://heartphp/admin/index, 那么正确路径就是tpl/admin/index/errorfile.html
	 *  @return 
	 */	
	public function display($filename = '', $view_path = '') {
		$filename = $this->get_filename($filename);//获取模板名

		$tpl_path_arr = $this->get_tpl($filename, $view_path);//获取TPL完整路径 并且向指针传送路径以及名称
		if(!$tpl_path_arr) {
			core::show_error($filename.$this->_tpl_suffix.'模板不存在');
			exit;
		}

		//编译开始
		$this->view_path_param = $view_path;//用户传递过来的模版跟目录
		$this->compile();
	}

	public function get_filename($filename) {
		if(empty($filename)) {
			$backtrace = debug_backtrace();
			if(!empty($backtrace)) {//通过debug_backtrace获取模板名
				return (!empty($backtrace[2]['function'])) ? $backtrace[2]['function'] : '' ;
			}
		} else {
			return $filename;
		}
	}
	/**
	 *	编译控制器
	 * 	@param 
	 *  @return 
	 */	
	private function compile() {
		$filepath = $this->template_path.$this->template_name;
		$compile_dirpath = $this->check_temp_compile();
		$vars_template_c_name = str_replace($this->_tpl_suffix, '', $this->template_name);


		$include_file = $this->template_replace($this->read_file($filepath), $compile_dirpath, $vars_template_c_name);//解析
		if($include_file) {
			$this->read_config() && $config = $this->read_config();
			extract($this->vars, EXTR_SKIP);
			@include $include_file;
		}
	}

	/**
	 * 读取当前项目配置文件
	 */
	protected function read_config() {
		if(file_exists(SYSTEM_PATH.'conf/config.php')) {
			@include SYSTEM_PATH.'conf/config.php'; 
			return $config;
		}

		return false;
	}
	/**
	 *	解析模板语法
	 * 	@param $str 内容
	 * 	@param $compile_dirpath 模版编译目录
	 * 	@param $vars_template_c_name 模版编译文件名
	 *  @return 编译过的PHP模板文件名
	 */	
	private function template_replace($str, $compile_dirpath, $vars_template_c_name) {
		if(empty($str)) core::show_error('模板内容为空！');

		//处理编译头部
		$compile_path = $compile_dirpath.$vars_template_c_name.$this->tpl_compile_suffix;//编译文件

		if(is_file($compile_path)) {
			//$header_content = $this->get_compile_header($compile_path);
			//$compile_date = $this->get_compile_header_comment($header_content);

			$tpl_filemtime = filemtime($this->template_path.$this->template_name);
			$compile_filemtime = filemtime($compile_path);

			//echo $tpl_filemtime.'=='.date('Y-m-d H:i:s', $tpl_filemtime).'<br/>';
			//echo $compile_filemtime.'=='.date('Y-m-d H:i:s', $compile_filemtime);
			//如果文件过期编译   当模板标签有include并且有修改时 也重新编译
			//<{include file="public/left.html"}> 当修改include里的文件，非DEBUG模式时  如果不更改主文件 目前是不重新编译include里的文件，我在考虑是否也要更改，没想好，暂时这样，所以在开发阶段一定要开启DEBUG=1模式 要不然修改include文件无效 。 有点罗嗦，不知道表述清楚没
			//模板文件大于编译文件重新编译
			if($tpl_filemtime > $compile_filemtime || DEBUG) {
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
	 * 加载模板标签函数路径
	 * @return [type] [description]
	 */
	private function load_template_label() {
		if(!isset($this->template_label) || empty($this->template_label)) return false;

		$include = array();
		foreach($this->template_label as $k => $v) {
			$p = $v['filepath'].PHPFILE_EXT;
			if(!file_exists($p)) {
				core::show_error($p." <br/>\n文件不存在，请检查对应文件！");
			}
			$include[$p] = "include('$p');";
		}
		
		if(is_array($include) && !empty($include)) {
			$include_str = implode("\n", $include);
		}
		return "<?php {$include_str}?>";
	}


	/**
	 *	模板文件主体
	 * 	@param  string  $str    内容
	 *  @return html
	 */
	private function body_content($body_content) {
		$body_content = $this->parse($body_content);
		$header_comment = "Create On##".time()."|Compiled from##".$this->template_path.$this->template_name;
		
		$content = "<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*{$header_comment}*/?>\r\n";
		$content .= $this->load_template_label()."\r\n";//写入板标签的函数文件 
		$content .= $body_content;//解析
		return $content;
	}

	/**
	 *  开始解析相关模板标签
	 * @param $content 模板内容
	 */
	private function parse($content) {
		//foreach
		$content = $this->parse_foreach($content);

		//include
		$content = $this->parse_include($content);

		//template_tags
		$content = $this->parse_label($content);//模板标签函数

		//echo
		$content = $this->parse_echo($content);

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
	//写了一半
	private function parse_hp_article($content) {
		$match = $this->preg_match_all("HP:article\s+(.*?)", $content);
		print_r($match);
	}

	/**
	 * 模板标签函数
	 * 使用方法 <{label::videolist2 limit="53" order="id desc" pagestatus="false" catid="aa" return="data3"}>
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	private function parse_label($content) {
		if(empty($content)) return false;

		$match = $this->preg_match_all("label::([\w]+) (.*?)", $content);
		if(!isset($match[1])) return false;

		foreach ($match[1] as $k => $v) {
			$argv = array();

			if(isset($match[2][$k])) {
				$argv_value = preg_split("/([\w]+)=\"/", $match[2][$k]);
				$argv_key = preg_split("/=\"(.*?)\"/", $match[2][$k]);
				//对标签VALUE重建数组
				$re_argv_value = $this->re_array($argv_value);
				$re_argv_key = $this->re_array($argv_key);
				if(!empty($re_argv_value) && !empty($re_argv_key)) {
					foreach($re_argv_value as $kk => $vv) {
						$argv[$re_argv_key[$kk]] = $vv;
					}
				}
			}

			$func_name = "template_func_$v";
			$this->template_label[] = array('filepath' => $this->template_label_path.$func_name, 'func_name' => $func_name);

			$return_param = (isset($argv['return'])) ? $argv['return'] : 'data' ;
			$content = str_replace($match[0][$k], "<?php \${$return_param}= ''; \${$return_param}= call_user_func(\"{$func_name}\", ".var_export($argv, true).")?>", $content);
		}

		return $content;
		//call_user_func("template_tags.{$match[1][0]}", 'dsf');
	}

	/**
	 * 对数组重建索引
	 * @param  [type] $arr [description]
	 * @return [type]      [description]
	 */
	private function re_array($arr) {
		if(!is_array($arr) || empty($arr))  return false;
		$data = array();
		foreach($arr as $k => $v) {
			if(empty($v)) continue;
			$v = str_replace("\"", "", $v);
			$data[] = trim($v);
		}

		return $data;
	}
	/**
	 * echo 如果默认直接<{$config['domain']}> 转成 <?php echo $config['domain']?>
	 */
	private function parse_echo($content) {

		$match = $this->preg_match_all('\$(.*?)', $content);
		if(!isset($match[1]) || !is_array($match[1])) return $content;

		foreach($match[1] as $k => $v) {
			if(strpos($v, '=')) continue;
			$content = str_replace($match[0][$k], "<?php echo \${$v}; ?>", $content);
		}
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
			//$s = preg_split("/\s+/is", $v);
			//$s = array_filter($s);
			$content = str_replace($match[0][$k], "<?php if({$v}) { ?>", $content);
		}

		return $content;
	}

	private function parse_elseif($content) {
		if(empty($content)) return false;
		//preg_match_all("/".$this->template_tag_left."elseif\s+(.*?)".$this->template_tag_right."/is", $content, $match);
		$match = $this->preg_match_all("elseif\s+(.*?)", $content);
		if(!isset($match[1]) || !is_array($match[1])) return $content;

		foreach($match[1] as $k => $v) {
			//$s = preg_split("/\s+/is", $v);
			//$s = array_filter($s);
			$content = str_replace($match[0][$k], "<?php } elseif ({$v}) { ?>", $content);
		}

		return $content;
	}
	/**
	 * 解析 include    include标签不是实时更新的  当主体文件更新的时候 才更新标签内容，所以想include生效 请修改一下主体文件
	 * 记录一下 有时间开发一个当DEBUG模式的时候 每次执行删除模版编译文件
	 * 使用方法 <{include file="" variable="参数1" variable="参数2"}>
	 * @param $content 模板内容
	 * @return html
	 */
	private function parse_include($content) {
		if(empty($content)) return false;

		$match = $this->preg_match_all("include\s+(.*?)", $content);
		if(!isset($match[1]) || !is_array($match[1])) return $content;

		
		foreach($match[1] as $match_key => $match_value) {
			$a = preg_split("/\s+/is", $match_value);

			$new_tag = array();
			//分析元素
			foreach($a as $t) {
				$b = explode('=', $t);
				$variable = '';
				if(!empty($b[1])) {
					$variable = substr($b[1],1);	
					$variable = substr($variable,0,strlen($variable)-1);	
				}
				if(empty($b[0])) continue;
				$new_tag[$b[0]] = $variable;
			}
			
			extract($new_tag);
			
			//查询模板文件
			foreach($this->conf['view_path'] as $v){
				if(empty($file)) core::show_error("include($v) <br/>模板文件路径出问题了."); 
				$conf_view_tpl = $v.$file;//include 模板文件

				if(is_file($conf_view_tpl)) {
					$_content = $this->read_file($conf_view_tpl);
					$inc_file = str_replace($this->_tpl_suffix, '', basename($file));
					$this->view_path_param = dirname($file).'/';
					
					$variable_str = '';
					foreach($new_tag as $nk => $nv) {
						$variable_str .= '$'.$nk.' = "'. $nv .'";';
					}
					
					$include_file = $this->template_replace($_content, $this->check_temp_compile(), $inc_file);//解析
					break;
				} else {
					core::show_error("{$conf_view_tpl} <br/> 模板文件不存在.");
				}
			} 
			
			$content = str_replace($match[0][$match_key], '<?php '.$variable_str.'include("'.$include_file.'")?>', $content);
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
			$key = ($key) ? '$'.$key.' =>' : '' ;
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

		$tpl_path = ($this->view_path_param) ? $this->view_path_param : $this->get_tpl_path() ;
		$all_tpl_apth = $this->template_c.$tpl_path;

		if(!is_dir($all_tpl_apth)) {
			$this->create_dir('/'.$tpl_path);
		}

		return $all_tpl_apth;
	}
	/**
	 *	读文件
	 * 	@param  string  $path   文件完整路径
	 *  @return 模板内容
	 */
	private function read_file($path) {
		//$this->check_file_limits($path, 'r');
		
		if(($r = @fopen($path, 'r')) === false) {
			core::show_error('模版文件没有读取或执行权限，请检查！');
		}
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
		//echo '开始编译了=====';
		$f = $dir.$filename.$this->tpl_compile_suffix;

		//$this->check_file_limits($f, 'w');
		if(($fp = @fopen($f, 'wb')) === false) {
			core::show_error($f.'<br/>编译文件失败，请检查文件权限.');
		}
		//开启flock
		flock($fp, LOCK_EX + LOCK_NB);
		fwrite($fp, $content, strlen($content));
		flock($fp, LOCK_UN + LOCK_NB);
		fclose($fp);

		return $f;
	}

	/**
	 * 这个检查文件权限函数 暂时废弃了
	 * @param  [$path] [路径]
	 * @param  [status] [w=write, r=read]
	 */
	public function check_file_limits($path , $status = 'rw') {
		clearstatcache();
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
	 *	获取模板完整路径 并返回已存在文件
	 * 	@param  string  $filename   文件名
	 *  @param  string  $view_path  模板路径 
	 *  @return 
	 */
	private function get_tpl($filename, $view_path) {
		empty($filename) && $filename = $this->tpl_name;

		//遍历模板路径
		foreach($this->conf['view_path'] as $path) {
			if($view_path) {//直接从tpl跟目录找文件
				$tpl_path = $path.$view_path;
				$view_file_path = $tpl_path.$filename.$this->_tpl_suffix;
			} else {//根据目录，控制器，方法开始找文件
				$view_file_path = ($tpl_path = $this->get_tpl_path($path)) ? $tpl_path.$filename.$this->_tpl_suffix  : exit(0);
			}

			if(is_file($view_file_path)) {
				//向指针传送模板路径和模板名称
				$this->template_path = $tpl_path;//
				$this->template_name = $filename.$this->_tpl_suffix;
				return true;
			} else {
				core::show_error($filename.$this->_tpl_suffix.'模板不存在');
				exit;
			}
		}
	}

	/**
	 *	获取模板路径
	 * 	@param  string  $path   主目录
	 *  @return URL D和M的拼接路径
	 */
	private function get_tpl_path($path = '') {
		if(defined('__D__')) {
			$path_arr[0] = __D__;
		}

		if(defined('__C__')) {
			$path_arr[1] = __C__;
		}

		$newpath = (is_array($path_arr)) ? implode('/', $path_arr) : core::show_error('获取模板路径失败!'); 

		return $path.$newpath.'/';
	}

	/**
	 *	创建目录
	 * 	@param  string  $path   目录
	 *  @return 
	 */
	private function create_dir($path, $mode = 0777){
		if(is_dir($path)) return false;

		$dir_arr = explode('/', $path);
		$dir_arr = array_filter($dir_arr);

		$allpath = '';
		$newdir = $this->template_c;

		foreach($dir_arr as $dir) {
			$allpath = $newdir.'/'.$dir;

			if(!is_dir($allpath)) {
				$newdir = $allpath;

				if(!@mkdir($allpath, $mode)) {
					core::show_error( $allpath.'<br/>创建目录失败，请检查是否有可都写权限！');
				}
				chmod($allpath, $mode);
			} else {
				$newdir = $allpath;
			}
		}
		return true;
	}
	
	/**
	 * 模板文件后缀 
	 */	
	public function tpl_suffix() {
		
		if(empty($this->conf['template_config']['template_suffix'])) {
			return $this->tpl_suffix;	
		} else {
			return $this->conf['template_config']['template_suffix'];	
		}
	}

	public function __destruct(){
		$this->vars = null;
		$this->view_path_param = null;
	}

};
