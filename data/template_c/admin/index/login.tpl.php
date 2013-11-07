<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1383193428|Compiled from##/home/zhangliang/workspace/webapps/heartphp/tpl/admin/index/login.html*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <title>HeartPHP 后台管理系统</title>
    <script type="text/javascript" src="<?php echo $config['static_path_js']; ?>jquery-1.7.2.min.js"></script>
    <!--<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>menu.js"></script>-->
    <script type="text/javascript" src="<?php echo $config['static_path_js']; ?>global.js"></script>
    <script type="text/javascript" src="<?php echo $config['static_path_js']; ?>modal.js"></script> 
    <script type="text/javascript" src="<?php echo $config['static_path_js']; ?>admin.js"></script>
    <link rel="stylesheet" href="<?php echo $config['static_path_css']; ?>style.css" type="text/css" media="screen" charset="utf-8" />
    <script>
      var domain = '<?php echo $config['domain']; ?>';
    </script>
  </head>
  <body>

    <div id="wrapper_login">
      <div id="menu">
        <div id="left"></div>
        <div id="right"></div>
        <h2>HeartPHP</h2>
        <div class="clear"></div>   
      </div>
      <div id="desc">
        <div class="body">
          <div class="col w10 last bottomlast">
            <form onsubmit="return false;">
              <p node-type="error_message"></p>
              <p>
                <label for="username">用户名:</label>
                <input type="text" name="username" id="username" node-type='username' value="" size="28" class="text" />
                <br />
              </p>
              <p>
                <label for="password">密　码:</label>
                <input type="password" name="password" id="password" node-type="pwd" value="" size="28" class="text" />
                <br />
              </p>
              <p class="last">
                <input type="submit" value="Login" class="novisible" />
                <a href="javascript:void();" class="button form_submit" action-type="login_submit"><small class="icon play"></small><span>登录</span></a>
                <br />
              </p>
              <div class="clear"></div>
            </form>
          </div>
          <div class="clear"></div>
        </div>
        <div class="clear"></div>
        <div id="body_footer">
          <div id="bottom_left"><div id="bottom_right"></div></div>
        </div>
      </div>    
    </div>
  </body>
</html>