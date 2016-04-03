<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 * 框架核心公用函数库
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2014.04.21
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */


/**
 * 综合过滤 POST GET COOKIE SESSION COOKIE通过这个可以进行安全过滤
 * @param $k
 * @param string $type
 * @return mixed
 */
if(!function_exists('gpc')) {
    function gpc($key, $type = 'G') {
        $vars = array();
        switch($type) {
            case 'G':
                $vars = &$_GET;
                break;
            case 'P':
                $vars = &$_POST;
                break;
            case 'C':
                $vars = &$_COOKIE;
                break;
            case 'R':
                $vars = isset($_GET[$key]) ? $_GET : (isset($_POST[$key]) ? $_POST : $_COOKIE);
                break;
            case 'S':
                $vars = &$_SESSION;
                break;
        }

        //数组批量获取参数 array(id, name, age)
        if(is_array($key) && !empty($key)) {

            $rt = array();
            foreach($key as $k => $v) {
                if(!isset($vars[$v])) continue;
                $rt[$v] = safefilter($vars[$v]);
            }

            return $rt;
        } elseif(isset($vars[$key]) && is_array($vars[$key])) {

            foreach($vars[$key] as $vk => $vv) {
                $vars[$key][$vk] = (!empty($vv)) ? safefilter($vv) : '' ;
            }

            return $vars[$key];
        } elseif(isset($vars[$key])) {
            return $vars[$key];
        }
    }
}

/**
 * 最好使用GPC来获取GET POST数据，因为增加了安全过滤和防注入
 * GET POST COOKIE SERVER = GPC
 * 使用方法
 * gpc(array('username', 'pwd'), 'R');
 * gpc('get', 'G');
 * gpc('post', 'P');
 */
if (!function_exists('safefilter')) {
    function safefilter($data) {
        if(empty($data)) return $data;

        if(is_numeric($data)) {
            return floatval($data);
        } elseif(is_array($data)) {
            foreach($data as $k => $v) {
               $data[$k] = safefilter($v);
            }
        } else {
            if(!get_magic_quotes_gpc()) {
                return addcslashes($data,"");
            }
        }

        return (!is_array($data)) ? trim($data) : $data ;
    }
}

/**
 * 获取基础数据模型 实例
 * @param $model 数据模块名
 */

if(!function_exists('D')) {
    function D($model) {
        if(empty($model)) core::show_error($model.'不存在');
        $model_path = C('model_path');
        if(empty($model_path)) core::show_error('数据模块路径不正确，请检查配置文件！');
        static $_model = array();//单例：防止重复加载 MODEL

        $model_filepath = $model_path.$model.PHPCLASS_EXT;
        if(is_file($model_filepath)) {

            if(empty($_model[$model])) {
                require $model_filepath;
                $m = new $model();
                $_model[$model] = $m;
                return $_model[$model];
            } else {
                return $_model[$model];
            }
        } else {
            core::show_error($model.PHPCLASS_EXT.' 文件不存在.');
        }

    }
}

/**
 * 获取配置文件
 * @param $key 配置文件KEY
 * @param $filename 文件名
 */
if(!function_exists('C')) {
	function C($key = '', $filename = 'system') {

		//单例
		static $_config = array();
		if(!isset($_config[$filename])) {
			$file = SYSTEM_PATH.'conf/'.$filename.PHPFILE_EXT;
			$include_data = (file_exists($file)) ? include($file)  : core::show_error('文件不存在') ;
			$_config[$filename] = $include_data;	
		}


		if(empty($key)) {
			return $_config[$filename];
		} else {
			return (isset($_config[$filename][$key])) ? $_config[$filename][$key] : core::show_error("$filename <br/> $key 不存在!") ;
		}
	}
}

/**
 * 多层控制器支持
 * @param $filename 文件名
 * @param $目录名 如果获取深层次目录可以 admin/login
 */
if(!function_exists('A')) {
    function A($filename, $controller = '') {
        empty($filename) && core::show_error('缺少参数,实例名.');
        $filename = $filename.'Controller';
        $controller_path = C('controller_path');
        $controller_path = $controller_path[0];
        empty($controller_path) && core::show_error('实例目录不存在，请检查配置文件.');

        //variable
        static $_class = array();//单例 方式重复加载
        $all_filename = $controller_path.'/'.$controller.'/'.$filename.PHPFILE_EXT;

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
            core::show_error($filename.PHPFILE_EXT.' 文件不存在.');
        }
    }

}

/**
 * 获取实例助手工具包
 * @param $helper 工具包名称
 */
if(!function_exists('H')) {
    function H($helper) {
        if(empty($helper)) core::show_error($helper.'不存在');
        $helper_path = C('helper_path');
        if(empty($helper_path)) core::show_error('工具包路径不正确，请检查配置文件！');
        static $_helper = array();//单例：防止重复加载

        $helper_filepath = $helper_path.$helper.PHPFILE_EXT;
        if(is_file($helper_filepath)) {

            if(empty($_helper[$helper])) {
                require $helper_filepath;
                $h = new $helper();
                $_helper[$helper] = $h;
                return $_helper[$helper];
            } else {
                return $_helper[$helper];
            }
        } else {
            core::show_error($helper.PHPFILE_EXT.' 文件不存在.');
        }

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
if(!function_exists('set_cookie')) {
    function set_cookie($var, $value = '', $time = 0,$pre = '', $encrypt = 0) {
        $time = $time > 0 ? $time : ($value == '' ? time() - 3600 : 0);
        $s = $_SERVER ['SERVER_PORT'] == '443' ? 1 : 0;
        $cookie_pre  = $pre ? $pre : C('cookie_pre','config');
        $var = $cookie_pre . $var;
        $_COOKIE [$var] = $value;
        if (is_array ( $value )) {
            foreach ( $value as $k => $v ) {
                encrypt_authcode($value, $encrypt);//对COOKIE加密
                setcookie ( $var . '[' . $k . ']', $v, $time, C('cookie_path','config'), C('cookie_domain', 'config'), $s );
            }
        } else {
            encrypt_authcode($value, $encrypt);//对COOKIE加密
            setcookie ( $var, $value, $time, C('cookie_path','config'), C('cookie_domain','config'), $s );
        }
    }
}

/**
 * 获取 cookie
 * @param string $var 变量名
 * @param string $pre 前缀
 * @param $encrypt 解密 2=no 1=yes
 */
if(!function_exists('get_cookie')) {
    function get_cookie($var, $pre = '', $encrypt = 0) {

        $cookie_pre  = $pre ? $pre : C('cookie_pre', 'config');
        $var = $cookie_pre . $var;

        // if(empty($cookie_var)) return false;
        if(!isset($_COOKIE[$var])) return false;
        $cookie_var = $_COOKIE[$var] ;
        encrypt_authcode($cookie_var, $encrypt);

        return isset ($cookie_var) && !empty($cookie_var) ? $cookie_var : false;
    }
}

/**
 * 加密
 * @param $data value
 * @param $encrypt 加密 1=加密 0=原值返回 2=解密
 */
if(!function_exists('encrypt_authcode')) {
    function encrypt_authcode(&$data, $encrypt) {

        $key = (C('server_authkey', 'config')) ? C('server_authkey', 'config') : HEARTPHP_KEY ;
        if($encrypt == 1) {//加密
            $data = authcode($data, 'ENCODE', $key);
        } elseif($encrypt == 2) {//解密
            $data = authcode($data, 'DECODE', $key);
        }

    }

}

/**
 * 组合URL
 * @param $d 目录
 * @param $c 文件 控制器
 * @param $m 方法函数
 * @param $param GET
 */
if(!function_exists('get_url')) {
    function get_url($d, $c, $a, $param = '') {
        $config = C('', 'config');

        $newarr = $_param = array();
        !empty($d) && $newarr[0] = $d;
        !empty($c) && $newarr[1] = $c;
        !empty($a) && $newarr[2] = $a;

        if(C('path_info')) {
            if(!empty($param)) $param = '?'.$param;
            return $config['domain'].implode('/', $newarr).$param;
        } else {

            $param_str = '';
            if(!empty($d)) $_param[] = "d={$d}";
            if(!empty($c)) $_param[] = "c={$c}";
            if(!empty($a)) $_param[] = "a={$a}";
            if(!empty($param)) $_param[] = $param;

            if(is_array($_param) && !empty($_param)) $param_str = implode('&', $_param);

            return $config['domain'].'index.php?'.$param_str;
        }
    }
}


/**
* 将字符串转换为数组
*
* @param	string	$data	字符串
* @return	array	返回数组格式，如果，data为空，则返回空数组
*/
//if(!function_exists('string2array')) {
//    function string2array($data) {
//        if($data == '') return array();
//        eval("\$array = $data;");
//        return $array;
//    }
//}
/**
* 将数组转换为字符串
*
* @param	array	$data		数组
* @return	string	返回字符串，如果，data为空，则返回空
*/
//if(!function_exists('array2string')) {
//    function array2string($data, $isformdata = 1) {
//        if($data == '') return '';
//        if($isformdata) $data = new_stripslashes($data);
//        return addslashes(var_export($data, TRUE));
//    }
//}

/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
if(!function_exists('new_stripslashes')) {
    function new_stripslashes($string) {
        if(!is_array($string)) return stripslashes($string);
        foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
        return $string;
    }
}

/**
 * 输出调试信息
 */
if(!function_exists('output_trace')) {
   function output_trace($status = 0){
       if(!DEBUG) return false;
       $date = date("Y-m-d H:i:s");

       //获取include require相关信息
       $included_files = get_included_files();
       $included_files_dump = var_export($included_files, true);
       $included_files_count = count(get_included_files());

       $sqls_lists = (isset($_SERVER['sqls'])) ? var_export($_SERVER['sqls'], true) : '' ;
       $sqls_count = (isset($_SERVER['sqls']) && !empty($_SERVER['sqls'])) ? count($_SERVER['sqls']) : 0 ;

       include HEART_FRAMEWORK_TPL.'trace.tpl.php';
   }
}

?>
