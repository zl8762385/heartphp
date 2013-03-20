<? if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##2013-03-20 19:59:30|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/ad/index/testzhangliang.html*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>/ad/index/test111</title>

<meta name="baidu-site-verification" content="lnq7Ugb41pCq6Azs" />

</head>

<body>
<?php echo $content ?>
<?php print_r($list) ?>


<?php foreach($list as $v) { ?>
	第一个:<?php echo $v ?><br/>
		<?php foreach($list as $v1) { ?>
			第二个:<?php echo $v1 ?>&nbsp;&nbsp;<br/>
				<?php foreach($list as $v2) { ?>
					第三个:<?php echo $v2 ?>&nbsp;&nbsp;&nbsp;&nbsp;<br/>
				<?php } ?>
		<?php } ?>
<?php } ?>
</body>
</html>