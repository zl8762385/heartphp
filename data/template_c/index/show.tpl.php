<? if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1372860543|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/index/show.html*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>HeartPHP - 用心做PHP、简单、高效、上手快</title>
<meta name="description" content="HeartPHP <?php echo $infos['title'] ?>">
<meta name="keywords" content="PHP <?php echo $infos['title'] ?>">
<link rel="stylesheet" href="<?php echo $config['static_path_css'] ?>default.css" charset="utf-8">
<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>ad.util.js"></script>
</head>

<body>
<div class="topline"></div>
<div class="nav">
    <div class="box">
        <div class="logo"></div>
        <ul>
            <li class="help current"><a href="<?php echo $config['domain'] ?>index/news">新闻</a></li>
           
            <li class="jailbreak "><a href="<?php echo $config['domain'] ?>manuals/index.html" target="_blank">在线手册</a></li>
            <li class="pc "><a target="_blank" href="<?php echo $config['domain'] ?>admin/index/login">在线演示</a></li>
            <li class="ios "><a href="#" onclick="javascript:alert('开发中...')">关于HeartPHP</a></li>
            <li class="home "><a href="<?php echo $config['domain'] ?>" onclick="_hmt.push([&#39;_trackEvent&#39;, &#39;index&#39;, &#39;click&#39;]);">首页</a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>

<div class="article">
    <div class="title">
        <h1><?php echo $infos['title'] ?></h1>
    </div>
    <div class="content">
        <?php echo $infos['content'] ?>
    </div>
</div>
<div class="clearfix"></div>
<div class="links ">
    <dl class="clearfix">
        <dt>友情链接：</dt>
        <dd>
            <ul>
                               
            </ul>
            <ul class="textlink">
            </ul>
        </dd>
        <div class="clearfix"></div>

    </dl>
    <div class="clearfix"></div>
</div>
<div class="footer">
    <div class="fnav">
        <div class="clearfix"></div>
        <div class="copyright">
            <div class="footer_line">
                <a href="<?php echo $config['domain'] ?>">更新列表</a> &nbsp;&nbsp;
                <a href="<?php echo $config['domain'] ?>">BUG反馈</a> &nbsp;&nbsp;
                <a href="<?php echo $config['domain'] ?>">功能建议</a>
            </div>
            <div class="footer_line">程序设计:张晓亮 QQ:979314</div>
            <div class="footer_line">本站基于 HeartPHP 框架构建</div>
            <div class="footer_line">©HeartPHP 2013 heartphp.com<script src="http://s20.cnzz.com/stat.php?id=5229966&web_id=5229966&show=pic" language="JavaScript"></script></div>
            <a href="http://webscan.360.cn/index/checkwebsite/url/www.heartphp.com"><img border="0" src="http://img.webscan.360.cn/status/pai/hash/d346ea3f40fd74e8a59920c250620904"/></a>
            
        </div>
    </div>
</div>

</body>
</html>