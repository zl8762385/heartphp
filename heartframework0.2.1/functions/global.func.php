<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 框架核心公用函数库
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:979314>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */



/**
 * 获取基础数据模型 实例
 * @param $model 数据模块名
 */
function D($model) {
	if(empty($model)) core::show_error($model.'不存在');
	$model_path = get_conf('model_path');
	if(empty($model_path)) core::show_error('数据模块路径不正确，请检查配置文件！');
	static $_model = array();//单例：防止重复加载 MODEL

	$model_filepath = $model_path.$model.'.class.php';
	if(is_file($model_filepath)) {

		if(empty($_model[$model])) {
			core::_require($model_filepath);
			$m = new $model();
			$_model[$model] = $m;
			return $_model[$model];
		} else {
			return $_model[$model];
		}
	} else {
		core::show_error($model.'.class.php 文件不存在.');
	}

}

/**
 * 多层控制器支持
 * @param $filename 文件名
 * @param $目录名 如果获取深层次目录可以 admin/login
 */
function C($filename, $controller = '') {
	//if
	$file_suffix = '.php';
	empty($filename) && core::show_error('缺少参数,实例名.');
	$filename = $filename.'Controller';
	$controller_path = get_conf('controller_path');
	$controller_path = $controller_path[0];
	empty($controller_path) && core::show_error('实例目录不存在，请检查配置文件.');

	//variable
	static $_class = array();//单例 方式重复加载
	$all_filename = $controller_path.'/'.$controller.'/'.$filename.$file_suffix;
	
	if(is_file($all_filename)) {
		if(!isset($_class[$all_filename])) {
			include $all_filename;
			$_class[$all_filename] = new $filename();
			return $_class[$all_filename];
		} else {
			//echo '存在无需实例 直接返回对象';
			return $_class[$all_filename];
		}
	} else {
		core::show_error($filename.$file_suffix.' 文件不存在.');
	}
}

/**
 * 获取实例助手工具包
 * @param $helper 工具包名称
 */
function H($helper) {
	if(empty($helper)) core::show_error($helper.'不存在');
	$helper_path = get_conf('helper_path');
	if(empty($helper_path)) core::show_error('工具包路径不正确，请检查配置文件！');
	static $_helper = array();//单例：防止重复加载

	$helper_filepath = $helper_path.$helper.'.php';
	if(is_file($helper_filepath)) {

		if(empty($_helper[$helper])) {
			core::_require($helper_filepath);
			$h = new $helper();
			$_helper[$helper] = $h;
			return $_helper[$helper];
		} else {
			return $_helper[$helper];
		}
	} else {
		core::show_error($helper.'.php 文件不存在.');
	}

}

/**
 * 对用户的密码进行加密
 * @param $password
 * @param $encrypt //传入加密串，在修改密码时做认证
 * @return array/password
 */
function password($password, $encrypt='') {
	$pwd = array();
	$pwd['encrypt'] =  $encrypt ? $encrypt : create_randomstr();
	$pwd['password'] = md5(md5(trim($password)).$pwd['encrypt']);
	return $encrypt ? $pwd['password'] : $pwd;
}

/**
 * 生成随机字符串
 * @param string $lenth 长度
 * @return string 字符串
 */
function create_randomstr($lenth = 6) {
	return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}

/**
 * 产生随机字符串
 *
 * @param    int        $length  输出长度
 * @param    string     $chars   可选的 ，默认为 0123456789
 * @return   string     字符串
 */
function random($length, $chars = '0123456789') {
	$hash = '';
	$max = strlen ( $chars ) - 1;
	for($i = 0; $i < $length; $i ++) {
		$hash .= $chars [mt_rand ( 0, $max )];
	}
	return $hash;
}

/**
 * 双向加密
 * @param $string： 明文 或 密文
 * @param $operation：DECODE表示解密,其它表示加密
 * @param $key： 如果没有传密钥参数 默认是HEARTPHP_KEY
 * @param $expiry：密文有效期
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;	// 随机密钥长度 取值 0-32;
	// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
	// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
	// 当此值为 0 时，则不产生随机密钥

	$key = md5($key ? $key : HEARTPHP_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

/**
 * 设置 cookie
 * @param string $var 变量名
 * @param string $value 变量值
 * @param int $time 过期时间
 * @param string $pre 前缀
 * @param $encrypt 是否加密 0=no 1=yes
 */
function set_cookie($var, $value = '', $time = 0,$pre = '', $encrypt = 0) {
	$time = $time > 0 ? $time : ($value == '' ? time() - 3600 : 0);
	$s = $_SERVER ['SERVER_PORT'] == '443' ? 1 : 0;
	$cookie_pre  = $pre ? $pre : core::load_config('config', 'cookie_pre');
	$var = $cookie_pre . $var;
	$_COOKIE [$var] = $value;
	if (is_array ( $value )) {
		foreach ( $value as $k => $v ) {
			encrypt_authcode($value, $encrypt);//对COOKIE加密
			setcookie ( $var . '[' . $k . ']', $v, $time, core::load_config('config', 'cookie_path'), core::load_config('config', 'cookie_domain'), $s );
		}
	} else {
		encrypt_authcode($value, $encrypt);//对COOKIE加密
		setcookie ( $var, $value, $time, core::load_config('config', 'cookie_path'), core::load_config('config', 'cookie_domain'), $s );
	}
}

/**
 * 获取 cookie
 * @param string $var 变量名
 * @param string $pre 前缀
 * @param $encrypt 解密 0=no 1=yes
 */
function get_cookie($var, $pre = '', $encrypt = 0) {
	$cookie_pre  = $pre ? $pre : core::load_config('config', 'cookie_pre');
	$var = $cookie_pre . $var;

	//在COOKIE中增加POST获取 是为了使用swfupload  因为默认FLASH是不会把cookie和session传递过来的，需要使用POST
	if(!isset($_COOKIE[$var])) return false;
	$cookie_var = (empty($_COOKIE [$var])) ? urldecode($_POST[$var]) : $_COOKIE[$var] ;
	encrypt_authcode($cookie_var, $encrypt);

	return isset ($cookie_var) && !empty($cookie_var) ? $cookie_var : false;
}

/**
 * 加密
 * @param $data value
 * @param $encrypt 加密 1=加密 0=原值返回 2=解密
 */
function encrypt_authcode(&$data, $encrypt) {
	
	$key = (core::load_config('config', 'server_authkey')) ? core::load_config('config', 'server_authkey') : HEARTPHP_KEY ;
	if($encrypt == 1) {//加密
		$data = authcode($data, 'ENCODE', $key);	
	} elseif($encrypt == 2) {//解密
		$data = authcode($data, 'DECODE', $key);
	}

}

/**
 * 组合URL
 * @param $d 目录
 * @param $c 文件 控制器
 * @param $m 方法函数
 * @param $param GET
 */
function get_url($d, $c, $a, $param = '') {
	global $conf;
	$config = core::load_config('config');

	$newarr = array();
	!empty($d) && $newarr[0] = $d;
	!empty($c) && $newarr[1] = $c;
	!empty($a) && $newarr[2] = $a;

	if($conf['path_info']) {
		if(!empty($param)) $param = '?'.$param;
		return $config['domain'].implode('/', $newarr).$param;
	} else {
		if(!empty($param)) $param = '&'.$param;

        //此处是为了项目管理增加的URL GET
        if(isset($_GET['define_project']) && !empty($_GET['define_project'])) $param .= "&define_project={$_GET['define_project']}";
		return $config['domain'].'index.php?d='.$d.'&c='.$c.'&a='.$a.''. $param;
	}
}
/**
 * 获取conf配置信息
 * @param [string] [$k] [key]
 */
function get_conf($k) {
	global $conf;
	if(isset($conf[$k])) {
		return $conf[$k];
	} else {
		return NULL;
	}	
}


/**
* 将字符串转换为数组
*
* @param	string	$data	字符串
* @return	array	返回数组格式，如果，data为空，则返回空数组
*/
function string2array($data) {
	if($data == '') return array();
	eval("\$array = $data;");
	return $array;
}
/**
* 将数组转换为字符串
*
* @param	array	$data		数组
* @return	string	返回字符串，如果，data为空，则返回空
*/
function array2string($data, $isformdata = 1) {
	if($data == '') return '';
	if($isformdata) $data = new_stripslashes($data);
	return addslashes(var_export($data, TRUE));
}

/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}

?>