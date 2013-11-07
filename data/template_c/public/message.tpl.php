<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1382721236|Compiled from##/home/zhangliang/workspace/webapps/heartphp/tpl/public/message.html*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HeartPHP 后台管理系统</title>

<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>jquery-1.7.2.min.js"></script>
<link rel="stylesheet" href="<?php echo $config['static_path_css'] ?>style.css" type="text/css" media="screen" />

</head>
<body>
        <div style="text-align:center;font-size:18px;height:300px;line-height:300px;">
            <?php echo $content; ?>
            <?php if($url_http=='goback') { ?>
                  <a href="javascript:history.back();">返回上一页 </a>
            <?php } else { ?>
              <script type="text/javascript">
                  jQuery(document).ready(function () {
                    var j = <?php echo $ms ?>;
                    setInterval(function () {
                      $('#jtime').html(j);
                      if(j == 1) location.href = '<?php echo $url_http ?>';
                      j--;
                    }, 850)
                  })
                </script>
                <a href="<?php echo $url_http ?>"><span id="jtime">3</span>秒钟自动跳转</a>
            <?php } ?>
        </div>

</body>
</html>
