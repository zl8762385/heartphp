<? if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1378188357|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/group/power_lists.html*/?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HeartPHP 后台管理系统</title>
<link rel="stylesheet" href="<?php echo $config['static_path_css'] ?>ztreestyle/zTreeStyle.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>ztree/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>ztree/jquery.ztree.excheck-3.5.js"></script>
<SCRIPT type="text/javascript">
		<!--
		var setting = {
			check: {
				enable: true
			},
			data: {
				simpleData: {
					enable: true,
					idKey:'id',
					pIdKey:'parentid'
				}
			},
			async: {
				enable: true,
				url:"<?php echo get_url('admin', 'group', 'menu_list') ?>",
				autoParam:["id", "name=n", "level=lv"],
				otherParam:{"otherParam":"zTreeAsyncTest"},
				dataFilter: filter
			},  
            callback: {   
                onCheck: onCheck  
            } 
		};

        function onCheck(e, treeId, treeNode) {
			var zTree = $.fn.zTree.getZTreeObj("power_tree_check"),
			nodes = zTree.getCheckedNodes(true),
			html = '';

			for(var n in nodes) {
				html += '<input checked="checked" type="checkbox" value="'+nodes[n]['id']+'" name="data[group_value][]" /> '+ nodes[n]['name'];
			}

			$('[action-type=user_group_lists]').html(html);
		}

		function filter(treeId, parentNode, childNodes) {
			if (!childNodes) return null;
			for (var i=0, l=childNodes.length; i<l; i++) {
				childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
			}
			return childNodes;
		}
		
		$(document).ready(function(){
			$.fn.zTree.init($("#power_tree_check"), setting);
		});
		//-->
	</SCRIPT>
</head>
<body> 
<div class="content_wrap" style="margin:0 0 0 35px;">
	<div class="zTreeDemoBackground left">
		<ul id="power_tree_check" class="ztree"></ul>
	</div>
	<input type="button" onclick="jQuery(document).trigger('close.facebox')" value="关闭"/>
</div>

</body>
</html>