<?php
/**
 *  core.class.php   基础核心类【用来提供各种静态方法】
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang
 * @email               zl8762385@163.com
 * @lastmodify			2013.03.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

class core {
	/** * 最好使用GPC来获取GET POST数据，因为增加了安全过滤和防注入
	 * GET POST COOKIE SERVER = GPC
	 */
	static public function gpc($k, $vars = 'G') {
		switch ($vars) {
			case 'G': $vars = &$_GET;break;
			case 'P': $vars = &$_POST;break;
			case 'C': $vars = &$_COOKIE;break;
			case 'R': $vars = isset($_GET) ? $_GET : (isset($_POST) ? $_POST : $_COOKIE ) ;break;
			case 'S': $vars = &$_SERVER;break;
			default:
				$vars = &$_GET;
		}

		if(isset($vars[$k])) {
			self::safe_filter($vars[$k]);
			return $vars[$k];
		} else {
			return NULL;
		}
	}

	/**
	 * @param [string] [$str] [string]
	 * @return 
	 */
	static public function safe_filter(&$str) {
		self::addslashes($str);
		$str = str_replace("_", "\_", $str);//这里如果在URL上增加下划线会影响 D M C操作
		$str = str_replace("%", "\%", $str);
	}

	static public function addslashes(&$data) {
		if(is_array($data)) {
			foreach($data as $k => $v) {
				self::addcslashes($v);
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
					print_r($_SERVER['class_model']);
				break;
				default:
					# code...
					break;
			}
		}
	}
	/**
	 * 获取CONF指定KEY
	 */
	static public function get_conf($appname) {
		global $conf;
		if(isset($conf[$appname])) {
			return $conf[$appname];
		} else {
			return NULL;
		}
	}
	/**
	 * 获取类名
	 */
	static public function get_directory_name() {
		$get['d'] = (!empty($_GET['d']) && isset($_GET['d'])) ? $_GET['d'] : '' ;
		return $get['d'];
	}

	/**
	 * 获取类名
	 */
	static public function get_model_name() {
		$get['c'] = $_GET['c'];
		return $get['c'];
	}

	/**
	 * 获取实际操作方法名
	 */
	static public function get_action_name() {
		$get['m'] = $_GET['m'];
		return $get['m'];
	}

	/**
	 * 错误
	 */	
	static function show_error($content, $title="错误提示"){
		$stringHtml = '<html>';
		$stringHtml .= '<head>';
		$stringHtml .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
		$stringHtml .= '<title>404 Not Found</title>';
		$stringHtml .= '<style>';
		$stringHtml .= '.t{font-family: Verdana, Arial, Helvetica, sans-serif;color: #CC0000;}';
		$stringHtml .= '.c{ font-family: Verdana, Arial, Helvetica, sans-serif;padding:20px;font-size: 14px;font-weight: normal;color: #000000;line-height: 18px;text-align: center;border: 1px solid #CCCCCC;background-color: #FFFFEC;}';
		$stringHtml .= '</style>';
		$stringHtml .= '</head>';
		$stringHtml .= '<body style="background:#ffffff; margin-top:100px;">';
		$stringHtml .= '<div align="center">';
		  $stringHtml .= '<h2><span class="t">'.$title.'</span></h2>';
		  $stringHtml .= '<table border="0" cellpadding="8" cellspacing="0" width="460">';
		    $stringHtml .= '<tbody>';
		      $stringHtml .= '<tr>';
		        $stringHtml .= '<td class="c">'.$content.'</td>';
		      $stringHtml .= '</tr>';
		    $stringHtml .= '</tbody>';
		  $stringHtml .= '</table>';
		$stringHtml .= '</div>';
		$stringHtml .= '</body>';
		$stringHtml .= '</html>';

	    exit($stringHtml);
	}
}