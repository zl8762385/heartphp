<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1383193840|Compiled from##/home/zhangliang/workspace/webapps/heartphp/tpl/admin/index/index.html*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>HeartPHP 后台管理系统</title>
	    <script type="text/javascript" src="<?php echo $config['static_path_js']; ?>jquery-1.7.2.min.js"></script>
	    <script type="text/javascript" src="<?php echo $config['static_path_js']; ?>global.js"></script>
	    <script type="text/javascript" src="<?php echo $config['static_path_js']; ?>modal.js"></script>
	    <script type="text/javascript" src="<?php echo $config['static_path_js']; ?>facebox.js"></script>
	    <script type="text/javascript" src="<?php echo $config['static_path_js']; ?>jquery.cookie.js"></script>
	    <script type="text/javascript" src="<?php echo $config['static_path_js']; ?>jquery.treeview.js"></script>
	    <link rel="stylesheet" href="<?php echo $config['static_path_css'] ?>style.css" type="text/css" charset="utf-8" />
	    <link rel="stylesheet" href="<?php echo $config['static_path_css'] ?>jquery.treeview.css" type="text/css" charset="utf-8" />
	    <script type="text/javascript">
	    	
			jQuery(document).ready(function($) {
				
				$('a[rel*=facebox]').facebox();
				$("#category_tree").treeview({
						control: "#treecontrol",
						persist: "cookie",
						cookieId: "treeview-black"
				});

				
			});
			function _switch(ids, title) {
				//切换导航
				$('[node-type^=nav_]').removeClass('selected');
				$('[node-type^=nav_'+ ids +']').addClass('selected');
				$('[node-type=category_title]').html(title);
				$('[node-type^=m_]').hide();
				$('[node-type^=m_'+ids+']').show();
			}
			
			 function setTime() {
		        var currentTime = new Date().toLocaleString();
		        document.getElementById("htmer_time").innerHTML=currentTime;
		    }
		    setInterval(setTime,1000);

		    
		</script>
		<script type="text/javascript" src="<?php echo $config['static_path_js']; ?>admin_common.js"></script>

</head>
<body>

		<div id="header">
			<div class="col w5 bottomlast">
				<span style="font-weight:800;font-size:16px;">
					HeartPHP后台管理系统
				</span>
			</div>
			<div class="col w5 last right bottomlast">
				<p class="last">欢迎您 <?php echo $userinfo['groupinfo']['name'] ?>：<span class="strong"><?php echo $userinfo['username'] ?>,</span> <a href="<?php echo get_url('admin', 'index', 'logout') ?>" title="退出">退出</a></p>
			</div>
			<div class="clear"></div>
		</div>
		<div id="wrapper">
			<div id="minwidth">
				<div id="holder">
					<div id="menu">
						<div id="left"></div>
						<div id="right"></div>
						<ul>
							<li><a href="<?php echo get_url('admin', 'index', 'home') ?>" target="right"><span>管理首页</span></a></li>
							<?php foreach($menu_mater as  $v) { ?>
								<li><a node-type="nav_<?php echo $v['id'] ?>" href="javascript:_switch(<?php echo $v['id'] ?>, '<?php echo $v['name'] ?>')"><span><?php echo $v['name'] ?></span></a></li>
							<?php } ?>
						</ul>
						<div class="clear"></div>
					</div>
					<div id="submenu">
						<div class="modules_left">
							<div class="module buttons">
								<!--
								<a href="" class="dropdown_button"><small class="icon plus"></small><span>New Category</span></a>
								
								<div class="dropdown">
									<div class="arrow"></div>
									<div class="content">
										<form>
											<p>
												<label for="name">Category Name:</label>
												<input type="text" class="text w_22" name="name" id="name" value="" />
											</p>
											<p>
												<label for="description">Category Description:</label>
												<textarea name="description" id="description" class="w_22" rows="10"></textarea>
											</p>
										</form>
										<a href="" class="button green right"><small class="icon check"></small><span>Save</span></a>
										<a class="button red mr right close"><small class="icon cross"></small><span>Close</span></a>
										<div class="clear"></div>
									</div>
								</div>
								-->
							</div>
						</div>
						<div class="title">
							<div id="htmer_time"></div>
						</div>
						<div class="modules_right">
							
						</div>
					</div>
					<div id="desc">
						<div class="body">
							<div class="col w2">
								<div class="content">
									<div class="box header">
										<div class="head"><div></div></div>
										<h2 node-type="category_title">快捷方式</h2>
										<div class="desc">
											<?php foreach($menu_data as $k => $v) { ?>
												<div node-type="m_<?php echo $k ?>" style="display:none;">
													<?php echo $v ?>
												</div>
											<?php } ?>
										</div>
										<div class="bottom"><div></div></div>
									</div>
								</div>
							</div>
							<div class="col w8 last">
								<div class="content">
								<iframe name="right" id="rightMain" src="<?php echo $config['domain']; ?>index.php?d=admin&c=index&a=home" frameborder="false" scrolling="auto" style="border:none;" width="100%" allowtransparency="true"></iframe>
								</div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
						<div id="body_footer">
							<div id="bottom_left"><div id="bottom_right"></div></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<p class="last">Copyright 2013 HeartPHP | Powered by <a href="http://www.heartphp.com">HeartPHP</a></p>
		</div>
<script>

var getWindowSize = function(){
	var param = [];
	param[0] = document.documentElement.clientHeight || document.body[ "clientHeight"];
	param[1] = document.documentElement.clientWidth || document.body[ "clientWidth"];
	return param;
	/*
return ["Height","Width"].map(function(name){
	alert(name)
  return window["inner"+name] ||
	document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
});
*/
}
window.onload = function (){
	if(!+"\v1" && !document.querySelector) { // for IE6 IE7
	
	  document.body.onresize = resize;
	} else { 
	  window.onresize = resize;
	}
	function resize() {
		wSize();
		return false;
	}
}

function wSize(){
	//这是一字符串
	var str=getWindowSize();
	var strs= new Array(); //定义一数组
	strs=str.toString().split(","); //字符分割
	var heights = strs[0]-190,
	Body = $('body');

	$('#rightMain').height(heights);   
	//iframe.height = strs[0]-46;
	if(strs[1]<980){
		Body.attr('scroll','');
		Body.removeClass('objbody');
	}else{
		Body.attr('scroll','no');
		Body.addClass('objbody');
	}
	
	var openClose = $("#rightMain").height()+39;
	$('#center_frame').height(openClose+9);
	$("#openClose").height(openClose+30);	
	$("#Scroll").height(openClose-20);
	windowW();
}
wSize();
function windowW(){
	if($('#Scroll').height()<$("#leftMain").height()){
		$(".scroll").show();
	}else{
		$(".scroll").hide();
	}
}
</script>
</body>
</html>