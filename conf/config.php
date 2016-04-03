<?php
/**
 *  config.php 当前项目的配置文件，可在模版中读取这个配置
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
$config = array();

//系统密钥
$config['server_authkey'] = '!@#$%^&*)()POIUWEQ@';//服务端密钥 后台
$config['domain'] = 'http://test.heartphp.com/';
//配置静态文件URL
$config['static_path_images'] = 'http://test.heartphp.com/statics/images/';//images
$config['static_path_js'] = 'http://test.heartphp.com/statics/js/';//images
$config['static_path_css'] = 'http://test.heartphp.com/statics/css/';//images
//cookie 设置
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';//Cookie 作用路径
$config['cookie_pre'] = 'IYTRQQW_';//Cookie 前缀，同一域名下安装多套系统时，请修改Cookie前缀
$config['cookie_ttl'] = 0; //Cookie 生命周期，0 表示随浏览器进程

return $config;
