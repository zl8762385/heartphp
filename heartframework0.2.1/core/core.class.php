<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  core.class.php   基础核心类【用来提供各种静态方法】
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:979314>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class core {
	static public $_instance = array();//防止重复加载

	/**
	 * 加载CLASS
	 * @param $filename
	 * @param $model 
	 */
	static public function load_class($filename, $model = '') {

	}
	/** 
	 * 最好使用GPC来获取GET POST数据，因为增加了安全过滤和防注入
	 * GET POST COOKIE SERVER = GPC
	 * 使用方法
	 * core::gpc(array('username', 'pwd'), 'R');
	 * core::gpc('get', 'G');
	 * core::gpc('post', 'P');
	 */
	static public function gpc($k, $vars = 'G') {
		switch ($vars) {
			case 'G': $vars = &$_GET;break;
			case 'P': $vars = &$_POST;break;
			case 'C': $vars = &$_COOKIE;break;
			case 'R': $vars = isset($_GET[$k]) ? $_GET : (isset($_POST[$k]) ? $_POST : $_COOKIE);break;
			case 'S': $vars = &$_SERVER;break;
			default:
				$vars = &$_GET;
		}

		if(isset($vars[$k]) && is_array($vars[$k])) {
			$newdata = array();
			foreach($vars[$k] as $key => $v) {
				self::safe_filter($v);
				$newdata[$key] = (isset($v)) ? $v : NULL ;
			}

			return $newdata;
		} else {
			if(isset($vars[$k])) {
				self::safe_filter($vars[$k]);
				return $vars[$k];
			} else {
				return NULL;
			}
		}

		
	}

	/**
	 * @param [string] [$str] [string]
	 * @return 
	 */
	static public function safe_filter(&$str) {
		self::addslashes($str);
		//$str = str_replace("_", "\_", $str);//这里如果在URL上增加下划线会影响 D M C操作
		$str = str_replace("%", "\%", $str);
		$str = str_replace('%20', '', $str);
		$str = str_replace('%27', '', $str);
		$str = str_replace('%2527', '', $str);
		//$str = str_replace("{", '', $str);
		//$str = str_replace('}', '', $str);
	}

	static public function addslashes(&$data) {
		if(is_array($data)) {
			foreach($data as $k => $v) {
				self::addslashes($v);
			}
		} else {
			if (!get_magic_quotes_gpc()) {
				$data = addslashes($data);
			}
		}
	}
	/**
	 * @param [string] [$info] [调试信息]
	 * @param [string] [$type] [type]
	 * @return
	 */
	static public function debug($type, $info = '') {
		if(DEBUG) {
			switch ($type) {
				case 'class_name':
					array_push($_SERVER['class_model'], $info);//记录加载类
				break;
				case 'sqls':
					array_push($_SERVER['sqls'], $info);//sql
				break;
				case 'show':
					echo '<pre>';
					print_r($_SERVER['class_model']);
					echo '</pre>';
				break;
				default:
					# code...
					break;
			}
		}
	}

	/**
	 * 封装include 对导入类文件进行统一管理
	 */
	static public function _require($file) {
		require $file;
		core::debug('class_name', $file);
	}

	/**
	 * 在用json_encode 请使用框架订制的，因为针对中文编码进行处理了
	 * @param $a array 要转成JSON的数组
	 * @return json
	 */
	static public function json_encode($a = false) {
	    if (is_null($a)) return 'null';
	    if ($a === false) return 'false';
	    if ($a === true) return 'true';
	    if (is_scalar($a)) {
	        if (is_float($a)) {
	            // Always use "." for floats.
	            return floatval(str_replace(",", ".", strval($a)));
	        }

	        if (is_string($a)) {
	            static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
	            return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
	        } else {
	            return $a;
	        }
	    }

	    $isList = true;
	    for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
	        if (key($a) !== $i) {
	            $isList = false;
	            break;
	        }
	    }

	    $result = array();
	    if ($isList) {
	        foreach ($a as $v) $result[] = self::json_encode($v);
	        return '[' . join(',', $result) . ']';
	    } else {
	        foreach ($a as $k => $v) $result[] = self::json_encode($k).':'.self::json_encode($v);
	        return '{' . join(',', $result) . '}';
	    }
	}

	/**
	 * config 配置文件
	 * @param $filename 配置目录下的文件名
	 * @param $key key
	 * @return array
	 */
	static public function load_config($filename, $key = '') {
		if(file_exists(SYSTEM_PATH.'conf/'.$filename.'.php')) {
			@include SYSTEM_PATH.'conf/'.$filename.'.php'; 
			//配置文件下的变量名必须以config命名
			return (isset($config[$key])) ? $config[$key] : $config ;
		}
	}
	/**
	 * 获取类名
	 * @return 目录名 
	 */
	static public function get_directory_name() {
		$get['d'] = (!empty($_GET['d']) && isset($_GET['d'])) ? $_GET['d'] : '' ;
		return $get['d'];
	}

	/**
	 * 获取类名
	 * @return 控制器
	 */
	static public function get_controller_name() {
		$get['c'] = $_GET['c'];
		return $get['c'];
	}

	/**
	 * 获取实际操作方法名
	 * @return 方法名
	 */
	static public function get_action_name() {
		$get['a'] = $_GET['a'];
		return $get['a'];
	}

	/**
	 * hooks 静态方法
	 * @param  string $hooks_name 钩子名
	 * @param  string $args 参数
	 * @param  string $type hooks类型 默认插件模式
	 * @return 
	 */
	static public function hooks($hooks_name, $args = '',$type="plugin") {

		$hooks = new base_hooks();
		$hooks->load_hooks($type, $hooks_name, $args);
	}

	/**
	 * 核心部分错误提示 HTML，这个方法不做美化了。
	 * 给用户重新美化一个方法
	 * @param $content 信息
	 * @param $title 标题
	 * @return 
	 */	
	static function show_error($content, $title="错误提示"){

		$string =<<<EOF
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>{$title}</title>
		<style>
			.error_box {width:1000px;margin:0 auto;}
			.error_box h1{}
			.error_content {border:1px solid #DDDDDD;line-height:35px;padding:10px;font-size:16px;}
		</style>
		</head>
		<body style="background:#ffffff; margin-top:20px;">
		<div class="error_box">
			<h1>{$title}</h1>
			<div class="error_content">{$content}</div>
		</div>
		</div>
		</body>
		</html>
EOF;
	    exit($string);
	}
}
?>