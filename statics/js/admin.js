/**
 * 后台管理JS
 */
(function ($) {
	var heartphp_admin = function() {};

	$.extend(heartphp_admin.prototype, {
		
		'login_bind': function () {//bind login
			var self = this;
			$('[action-type=login_submit]').bind('click', this.login);
			$('[node-type=pwd]').bind('keydown', function (e) {
			
				if(e.keyCode == 13) {
					self.login();
				}
			});
		},
		'login': function (e) {//绑定登陆验证
			var username = $('[node-type=username]'),
			pwd = $('[node-type=pwd]'),
			error_element = $('[node-type=error_message]'),
			message = '';

			if(username.val() == '') {
				message = '请输入用户名';
			}

			if(pwd.val() == '') {
				message = '请输入密码';
			}

			if(username.val() == '' && pwd.val() == '') {
				message = '请输入用户名和密码';
			}
			

			if(message != '') {
				$('[node-type=error_message]').Message({
					target:'[node-type=error_message]', 
					type:'error', 
					text:"错误提示："+message, 
					time:2000
				});
			} else {
				$.post(domain+'index.php?d=admin&c=index&a=check_login', {'username': username.val(), 'pwd': pwd.val()}, function (rt) {
					if(rt == '1') {
						location.href=domain+'index.php?d=admin&c=index&a=main';
					} else {
						$('[node-type=error_message]').Message({
							target:'[node-type=error_message]', 
							type:'error', 
							text:"错误提示："+ rt
						});
					}
				});
			}

		}
	});

	window.heartphp_admin = heartphp_admin;
})(jQuery);

jQuery(document).ready(function () {
	(new heartphp_admin()).login_bind();
});