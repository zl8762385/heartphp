<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381391581|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/index/news.html*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>HeartPHP - 用心做PHP、简单、高效、上手快</title>
<meta name="description" content="HeartPHP 是为了敏捷开发和通用管理后台简化企业和开发人员快速开发而诞生的.">
<meta name="keywords" content="php,框架,快速开发,开源框架,开发框架,MVC,tp,官方网站,php通用管理后台">
<link rel="stylesheet" href="<?php echo $config['static_path_css'] ?>default.css" charset="utf-8">
<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>ad.util.js"></script>
</head>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.tab li').click(function(){
            var _for = jQuery(this).attr('for');
            jQuery('.tab').find('.selected').removeClass('selected');
            jQuery(this).addClass('selected');
            
            jQuery('#pone,#ptwo,#pthree,#pfour,#pfive').hide();
            jQuery('#'+_for).show();
        });
    });
</script>
<style>a.blue { color:blue; text-decoration:underline}</style>
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

<div class="mainbox">
    <div class="container">
        <div class="help">
            <div class="top">
                <ul class="tab">
                    <li class="selected" for="pone">
                        <em class="left"></em>
                        <span>HeartPHP动态</span>
                        <em class="right"></em>
                    </li>
                    <!--
                    <li for="ptwo">
                        <em class="left"></em>
                        <span>a1</span>
                        <em class="right"></em>
                    </li>
                    <li for="pthree">
                        <em class="left"></em>
                        <span>a2</span>
                        <em class="right"></em>
                    </li>
                    <li for="pfour">
                        <em class="left"></em>
                        <span>a3</span>
                        <em class="right"></em>
                    </li>
                    <li for="pfive">
                        <em class="left"></em>
                        <span>a4</span>
                        <em class="right"></em>
                    </li>
                    -->
                </ul>
            </div>
            <div class="content">
                <div id="pfive" style="display:none;">
                    
                    pfivepfivepfive
                </div>
                <div id="ptwo" style="display:none;">
                    ptwoptwoptwo
                </div>
                
                <div id="pone">
                    <?php foreach($lists as $k => $v) { ?>
                    <div class="faq">
                        <p class="title">
                            <a href="<?php echo get_url('', 'index', 'show', 'id='.$v['id'].'') ?>"><?php echo $v['title'] ?></a>
                        </p>
                        <ul><li><?php echo $v['description'] ?></li></ul>
                    </div>
                    <?php } ?>
                    <div class="pagination"><?php echo $pages ?></div>
                </div>
                <div id="pthree" style="display:none;">
                   pthreepthreepthreepthree
                </div>
                <style>
                    .helplist { margin-left:35px;}
                    .helplist li{ list-style-type:decimal}
                </style>
                
            </div>
            <div class="bottom"></div>
        </div>
    </div>
    <script type="text/javascript">
        var _url = document.location.href;
        if(_url.indexOf('ytbhelper')>=0){
            jQuery('.tab .selected').removeClass('selected');
            jQuery('#pone,#ptwo,#pthree,#pfour,#pfive').hide();
            jQuery('#ptwo').show();
            jQuery('.tab li').eq(1).addClass('selected');
            
        }else if(_url.indexOf('jailbreak')>=0){
            jQuery('.tab .selected').removeClass('selected');
            jQuery('#pone,#ptwo,#pthree,#pfour,#pfive').hide();
            jQuery('#pfour').show();
            jQuery('.tab li').eq(3).addClass('selected');
        }else if(_url.indexOf('backup')>=0){
            jQuery('.tab .selected').removeClass('selected');
            jQuery('#pone,#ptwo,#pthree,#pfour,#pfive').hide();
            jQuery('#pfive').show();
            jQuery('.tab li').eq(4).addClass('selected');
        }
    </script>
</div>
<div class="clearfix"></div>
<div class="links ">
    <dl class="clearfix">
        <dt>友情链接：</dt>
        <dd>
            <ul>
                               
            </ul>
            <ul class="textlink">
                <li></li>
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