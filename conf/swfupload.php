<?php
/**
 *  swfupload FLASH上传文件配置
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:979314>  
 * @lastmodify			2013.6.17
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

 /**
  * 上传图片扩展名 设置
  * @param ext 文件扩展名|分割
  * @param allow_multi 是否采用多文件上传 1=使用  0=不使用
  * @param filesize 文件大小
  */
$config = array();

$config['images'] = array(
	'ext' => 'gif|png|bmp|jpeg|jpg|psd',
	'allow_multi' => 1,
	'dir' => 'att_images/',//必须设置目录，否则会出错,在根目录/data/uploadfiles子目录里
	'filesize' => 20971520
);//图片

$config['files'] = array(
	'ext' => 'doc|docx|xls|xlsx|ppt|htm|html|txt|zip|rar|gz|bz2|apk',
	'allow_multi' => 1,
	'dir' => 'att_files/',//必须设置目录，否则会出错,在根目录/data/uploadfiles子目录里
	'filesize' => 20971520
);//文件