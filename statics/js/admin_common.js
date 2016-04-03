/**
 * 后台管理JS
 */

function _confirm(url,message) {
	if(confirm(message)) redirect(url);
}

function redirect(url) {
	location.href = url;
}


//字段设置
function field_setting(fieldtype, id) {
	if(fieldtype == '0') {
		$('[node-type=target_filed]').html('');
		return false;
	}

	if(typeof(id) == 'undefined') id = 0;
	$('[node-type=target_filed]').html('');
	$.getJSON(_domain+'index.php?d=admin&c=sitemodel_field&a=field_settings&filedtype='+fieldtype+'&id='+id, function (data) {
		var html = '';
		html = data.settings;

		$('[node-type=target_filed]').html(html);
	});
}


jQuery(document).ready(function () {
	//控制右侧菜单
	//$('a[class^=current]').parents('ul').show().prev().addClass('current');

});