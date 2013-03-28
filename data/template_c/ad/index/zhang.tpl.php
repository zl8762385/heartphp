<? if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1364472017|Compiled from##/workspace/webapps/heartphp/tpl/ad/index/zhang.html*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>/ad/index/test111112423	ooooo321o</title>

<meta name="baidu-site-verification" content="lnq7Ugb41pCq6Azs" />

</head>

<body>112
模板标签：if 判断语句
<?php if(empty($zhang1)) { ?>
	zhang
	<?php $header ?>
<?php } elseif (empty($liang)) { ?>
<?php } elseif (empty($liang)) { ?>
<?php } elseif (empty($liang)) { ?>
	liang
<?php } else { ?>
	zhangliang
<?php } ?>

模板标签: include 调用其他模板 11111111
<?php echo $header ?>
我是脑袋文件了 header<br/>
模板标签：foreach 循环
<?php foreach($lists as $kk=> $value1) { ?>
嵌套1
	<?php foreach($lists as $kk=> $value11) { ?>
	嵌套2
		<?php foreach($lists as $kk=> $value12) { ?>
		嵌套3
		<?php } ?>
	<?php } ?>
<?php } ?>

<?php foreach($lists as  $value2) { ?>
嵌套1
	<?php foreach($lists as  $value21) { ?>
	嵌套2
		<?php foreach($lists as  $value22) { ?>
		嵌套3
			<?php foreach($lists as  $value23) { ?>
			嵌套4
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>


<?php foreach($lists as  $value3) { ?>
嵌套
<?php } ?>

<?php foreach($lists as  $value4) { ?>
嵌套
<?php } ?>

<?php echo $footer ?>
我是底部文件 footer<br/>
</body>
</html>