<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1383193392|Compiled from##/home/zhangliang/workspace/webapps/heartphp/tpl/index/index.html*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>HeartPHP - 用心做PHP、简单、高效、上手快</title>
<meta name="description" content="HeartPHP 是为了敏捷开发和通用管理后台简化企业和开发人员快速开发而诞生的.">
<meta name="keywords" content="php,框架,快速开发,开源框架,开发框架,MVC,tp,官方网站,php通用管理后台">
<link rel="stylesheet" href="<?php echo $config['static_path_css'] ?>default.css" charset="utf-8">
<script type="text/javascript" src="<?php echo $config['static_path_js'] ?>jquery-1.7.2.min.js"></script>

</head>
<body youdao="bind">
<div class="topline"></div>
<div class="nav">
	<div class="box">
    	<div class="logo"></div>
        <ul>
        	<li class="help "><a href="<?php echo $config['domain'] ?>index/news">新闻</a></li>
            <li class="jailbreak "><a href="<?php echo $config['domain'] ?>manuals/index.html" target="_blank">在线手册</a></li>
            <li class="pc "><a target="_blank" href="<?php echo $config['domain'] ?>index.php?d=admin&c=index&a=login">在线演示</a></li>
            <li class="ios "><a href="#" onclick="javascript:alert('开发中...')">关于HeartPHP</a></li>
        	<li class="home current"><a href="<?php echo $config['domain'] ?>" onclick="_hmt.push([&#39;_trackEvent&#39;, &#39;index&#39;, &#39;click&#39;]);">首页</a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div><script type="text/javascript">
jQuery(document).ready(function(){
	var _wwwroot = "<?php echo $config['domain'] ?>statics/";
    var ad = [_wwwroot+"/images/heart/banner_02.jpg",_wwwroot+"/images/heart/banner_01.jpg",_wwwroot+"/image/banner_03.jpg"];
    var im = new Image;
    im.onload = function(){$("#ad1").html("<img src='"+im.src+"' />");}
    im.src = ad[0];
    
    var im1 = new Image;
    im1.onload = function(){$("#ad2").html("<img src='"+im1.src+"' />");}
    im1.src = ad[1];
    
    var im2 = new Image;
    im2.onload = function(){$("#ad3").html("<img src='"+im2.src+"' />");}
    im2.src = ad[2];
    var Banner = function(){
        var t  = this;
        t.bw   = $('.banner_wrap');
        t.ul   = t.bw.children('.banner_ul');
        t.lm   = $('.banner_lmask');
        t.rm   = $('.banner_rmask');
        t.prevBtn = $('.banner_prev');
        t.nextBtn = $('.banner_next');
        t.m = ($(window).width()-800)/2;
        t.move = false;
        t.act = null;   
        t.timer = setTimeout(function(){t.init();},4000);   
        //bind event
        $(window).bind("resize",function(){t.m = ($(window).width()-800)/2;t.resize();});
        $('.banner').bind({"mouseenter":function(){clearTimeout(t.timer);},"mouseleave":function(){clearTimeout(t.timer);t.timer = setTimeout(function(){t.init();},4000);  }});
        t.nextBtn.bind("click",function(){if(t.move==false){t.next();}});
        t.prevBtn.bind("click",function(){if(t.move==false){t.prev();}});
        t.rm.bind("click",function(){if(t.move==false){t.next();}});
        t.lm.bind("click",function(){if(t.move==false){t.prev();}});
        t.resize();
		$('.banner').show();
        
    };Banner.prototype = {
        init:function(){
            var t = this;
            t.resize();
            t.next();
            clearTimeout(t.timer);
            t.timer = setTimeout(function(){t.init();},4000);
        },
        next:function(){
            var t = this;
            t.move=true;
            var uf  = t.ul.children('li:first');
            var ufc = uf.clone();
            t.ul.append(ufc).animate({"left":"-800"},250,function(){uf.remove();t.ul.css("left","0");t.move=false;});
        },
        prev:function(){
            var t = this;
            t.move=true;
            var ue = t.ul.children('li:last');
            var uec = ue.clone();
            t.ul.css('left','-800px').prepend(uec).animate({"left":"0"},250,function(){ue.remove();t.move=false;});
        },
        stop:function(){clearTimeout(this.timer);this.timer = null;},
        resize:function(){
            var t = this;
            t.ul.css("width",(t.ul.children('li').length+1)*800+"px");
            t.bw.css("margin-left","-"+(800-t.m)+"px");
            t.lm.css("width",t.m+"px");
            t.rm.css({"width":t.m+"px","left":(800+t.m)+"px"});
            t.prevBtn.css("left",(t.m-100)+"px");
            t.nextBtn.css("left",(t.m+840)+"px");
        }
    };var banner = new Banner();   
	
});
</script>
<div class="banner" style="">
    <div class="banner_wrap" style="margin-left: -525.5px;">
        <ul class="banner_ul clearfix" style="width: 3200px; left: 0px;">   
           	<li id="ad1"><img src="<?php echo $config['domain'] ?>statics/images/heart/banner_02.jpg"></li>
            <li id="ad2"><img src="<?php echo $config['domain'] ?>statics/images/heart/banner_01.jpg"></li>
            <li id="ad3"><img src="<?php echo $config['domain'] ?>statics/images/heart/banner_03.jpg"></li>
        </ul>
    </div>
    <div class="banner_lmask" style="width: 274.5px;"></div>
    <div class="banner_rmask" style="width: 274.5px; left: 1074.5px;"></div>
    <a href="javascript:;" target="_self" class="banner_prev" style="left: 174.5px;"></a>
    <a href="javascript:;" target="_self" class="banner_next" style="left: 1114.5px;"></a>
    <div class="bmask"></div>
</div>
<div class="clearfix"></div>
<div class="mainbox">
	<div class="container newpage">
    	<ul>
        	<li class="ios">
            	<dl>
                	<dt>
                        <div class="label">HeartPHP框架核心文件</div>
                    </dt>
                    <dd>
                    	<a href="http://pan.baidu.com/s/1mTSQp" target="_blank" title="HeartPHP框架">立即下载</a>
                    </dd>
                </dl>
            </li>
            <li class="pc">
            	<dl>
                	<dt>
                        <div class="label">通用管理后台</div>
                    </dt>
                    <dd>
                    	<a href="http://pan.baidu.com/s/123DXi" target="_blank" title="通用管理后台">立即下载</a>
                    </dd>
                </dl>
            </li>
             <li class="jailbreak">
            	<dl>
                	<dt>
                        <div class="label">HeartPHP CMS</div>
                    </dt>
                    <dd>
                    	<a href="javascript:alert('请耐心等待,版本测试中...')" title="HeartPHP CMS">立即下载</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="javascript:alert('请耐心等待,版本测试中...')">演示</a>
                    </dd>
                </dl>
            </li>
        </ul>
        <div class="clearfix"></div>
 
    </div>
</div>
<div class="clearfix"></div>
<div class="mainpage">
	<dl>
    	<dt>产品特性</dt>
        <dd>
        	<ul>
            	<li>
                	<div class="title">HeartPHP框架</div>
                    <div class="desc">集成其他框架的优点，对代码简化可以让开发人员快速上手，本着开源精神，我会对框架一直保持更新，不断的增加类库，简化操作方法.</div>
                </li>
                <li>
                	<div class="title">通用后台管理系统</div>
                    <div class="desc">为了方便开发人员可以快速开发，不用在重新开发后台管理的基本架构，为此开发了一套包含权限管理、菜单管理、管理员设置相关功能.</div>
                </li>
         
            </ul>
        </dd>
    </dl>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<div class="links nobor">
	<dl class="clearfix">
    	<dt>友情链接：</dt>
        <dd>
        	<ul>
            	            	<!--
								<li><a href="http://www.pc6.com/" target="_blank" rel="nofollow"><img src="./images/13656792475392.jpg"></a>&nbsp;&nbsp;<a href="http://www.pc6.com/" target="_blank" rel="nofollow">PC6下载</a></li>
								-->
								<li>&nbsp;&nbsp;<a href="http://www.caihezi.com/" target="_blank">家常菜谱大全</a></li>
                                <li>&nbsp;&nbsp;<a href="http://www.nmxiu.com/" target="_blank">柠檬秀</a></li>

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
				<a href="<?php echo $config['domain'] ?>/index/news">更新列表</a> &nbsp;&nbsp;
				<a href="<?php echo $config['domain'] ?>">BUG反馈</a> &nbsp;&nbsp;
				<a href="<?php echo $config['domain'] ?>">功能建议</a>
			</div>
			<div class="footer_line">程序开发:张晓亮</div>
			<div class="footer_line">Email：979314@qq.com &nbsp;&nbsp; QQ：979314 &nbsp;&nbsp;  HeartPHP QQ群：283013592</div>
			<div class="footer_line">本站基于 HeartPHP 框架构建</div>
			<div class="footer_line">©HeartPHP 2013 heartphp.com<script src="http://s20.cnzz.com/stat.php?id=5229966&web_id=5229966&show=pic" language="JavaScript"></script></div>
			<a href="http://webscan.360.cn/index/checkwebsite/url/www.heartphp.com"><img border="0" src="http://img.webscan.360.cn/status/pai/hash/d346ea3f40fd74e8a59920c250620904"/></a>
        	
        </div>
    </div>
</div>

</body>
</html>