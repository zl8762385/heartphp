<?php
/**
 *  page.class.php  分页类
 *
 * @copyright			(C) 20013-2015 HeartPHP
 * @author              zhangxiaoliang  <zl8762385@163.com> <qq:3677989>  
 * @lastmodify			2013.04.19
 *
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 */
class page{
	
	public $page; //当前页
	public $pagenum;  // 页数
	public $pagesize;  // 每页显示条数
	public function __construct($count, $pagesize, &$conf){
		$p = gpc('p');
		$this->pagenum = ceil($count/$pagesize);
		$this->pagesize = $pagesize;
		$this->page =(isset($p)&&$p>0) ? intval($p) : 1;
		$this->conf = $conf;
	}
	/**
	 * 获得 url 后面GET传递的参数
	 * @return ?url
	 */ 
	public function getUrl(){   
		$get = $_GET;
		$this->filter_url($get);
		$url = '?'.http_build_query($get);
		$url = preg_replace('/[?,&]p=(\w)+/','',$url);
		$url .= (strpos($url,"?") === false) ? '?' : '&';
		return $url;
	}

	/**
	 * 如果是PATHINFO就过滤URL中的 D M C
	 * @param $data data
	 * @return
	 */
	public function filter_url(&$data){
		if($this->conf['path_info']) {
			unset($data['d'], $data['c'], $data['a']);
		}
	} 
	/**
	 * 获得分页HTML
	 * @return html
	 */
	public function getPage(){
		$url = $this->getUrl();
		$start = $this->page-5;
		$start=$start>0 ? $start : 1; 
		$end   = $start+9;
		$end = $end<$this->pagenum ? $end : $this->pagenum;
		$pagestr = '';
		if($this->page>5){
			$pagestr = "<a href=".$url."p=1".">首页</a> ";
		}
		if($this->page!=1){
			$pagestr.= "<a href=".$url."p=".($this->page-1).">上一页</a>";
		}
		
		for($i=$start;$i<=$end;$i++){
			$classname = ($this->page == $i) ? 'current' : '' ;
			$pagestr.= "<a class='number $classname' href=".$url."p=".$i.">".$i."</a>  ";						
		}
		if($this->page!=$this->pagenum){
			$pagestr.="<a href=".$url."p=".($this->page+1).">下一页</a>";
			
		}
		if($this->page+5<$this->pagenum){
			$pagestr.="<a href=".$url."p=".$this->pagenum.">尾页</a> ";
		}
		return $pagestr;	
	}
	
}