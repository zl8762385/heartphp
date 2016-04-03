<?php
if(!defined('IS_HEARTPHP')) exit('Access Denied');
/**
 *  base_controller.class.php   控制器基础类
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */

abstract class base_controller {
	protected $view;
	protected $conf;
	
	public function __construct() {
		$this->conf = C('');
		$this->view = new template($this->conf);//全局变量传递给template
	}

	/**
	 * 跳转
	 * @param $url
	 * @return
	 */
	public function redirect($url) {
		header('location: '. $url);
		exit(0);
	}
	/**
	 * 美化版提示信息页面
	 * @param $message string 提示信息
	 * @param $title string 提示信息标题
	 * @param $url_http url URL跳转地址，如果默认不传 则是返回上一页
	 * @param $ms 跳转秒数
	 * @return 
	 */
	public function show_message($message, $title = '',$url_http = 'goback', $ms = 3) {
		empty($title) && $title = '信息提示';

		$this->view->assign('title', $title);
		$this->view->assign('ms', $ms);
		$this->view->assign('url_http', $url_http);
		$this->view->assign('content', $message);
		$this->view->display('message', 'public/');
		exit;
	}

	/**
	 * 分页
	 * @param $count 总数
	 * @param $pagesize 每页数量
	 * @return html
	 */
	public function page($count=0, $pagesize=10) {
		$page = new page($count,$pagesize, $this->conf);
		return $page->getPage();
	}

	/**
	 * 生成唯一令牌
	 * @return [type] [description]
	 */
	public function gen_token() {
		$token = md5(uniqid(rand(), true));
		return $token;
	}
}
