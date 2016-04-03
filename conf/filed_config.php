<?php
/**
 *  字段配置文件
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
$config = array();

//字段类型 配对
$config['filed_type'] = array(
	'text' => array(
		'type' => 'varchar','length' => 100
	),
	'textarea' => array(
		'type' => 'text'
	),
	'editor' => array(
		'type' => 'text'
	),
	'box' => array(
		'type' => 'varchar','length' => 100
	),
	'image' => array(
		'type' => 'text'
	),
	'number' => array(
		'type' => 'int'
	),
	'datetime' => array(
		'type' => 'int'
	),
	'uploadfile' => array(
		'type' => 'text'
	)
);


//字段select类型
$config['select_type'] = array(
	'text' => '单行文本',
	'textarea' => '多行文本',
	'editor' => '编辑器',
	'box' => '选项',
	'image' => '上传图片',
	'number' => '数字',
	'datetime' => '日期和时间',
	'uploadfile' => '上传文件',
	//'omnipotent' => '万能字段',//暂时注释以后开发
);

//内容字段 验证正则
$config['check_pattern'] = array(
	'常用正则' => '',
	'数字' => '/^[0-9.-]+$/',
	'整数' => '/^[0-9-]+$/',
	'字母' => '/^[a-z]+$/i',
	'数字+字母' => '/^[0-9a-z]+$/i',
	'E-mail' => '/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/',
	'QQ' => '/^[0-9]{5,20}$/',
	'超级链接' => '/^http:\/\//',
	'手机号码' => '/^(1)[0-9]{10}$/',
	'电话号码' => '/^[0-9-]{6,13}$/',
	'邮政编码' => '/^[0-9]{6}$/'
);

return $config;
