<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1383193841|Compiled from##/home/zhangliang/workspace/webapps/heartphp/tpl/admin/index/home.html*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HeartPHP 后台管理系统</title>
<link rel="stylesheet" href="<?php echo $config['static_path_css'] ?>style.css" type="text/css"/>

<link rel="stylesheet" href="<?php echo $config['static_path_css'] ?>facebox.css" type="text/css"/>
<!-- jQuery -->
<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>jquery-1.7.2.min.js"></script>
	<script>
		var _domain = '<?php echo $config['domain']; ?>';
	</script>
<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>global.js"></script>
<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>modal.js"></script>
<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>facebox.js"></script>
<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>admin_common.js"></script>

<link href="<?php echo $config['static_path_css'] ?>/kindeditor/themes/default/default.css" type="text/css"/>
<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>/kindeditor/kindeditor.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	
  	$('a[rel*=facebox]').facebox()
}) 
</script>
</head>
<body>