<? if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1372306604|Compiled from##/workspace/webapps/heartphp.com/tpl/test/test.html*/?>
<?php $a = 'aa' ?>


<?php if($a == 'aa') { ?>
  my is if <br/>
<?php } ?>


<?php if(!empty($a)) { ?>
	empty <br/>
<?php } ?>

<?php if($a == 'bb') { ?>
	bbbb <br/>
<?php } else { ?>
	aaaaa <br/>
<?php } ?>

<?php if($a == 'bb') { ?>
	bbbb <br/>
<?php } elseif ($a == 'aa') { ?>
	elseif aaa
<?php } ?>

<?php if($a == 'bb') { ?>
	bbbb <br/>
<?php } elseif ($a == 'cc') { ?>
	elseif aaa
<?php } else { ?>
	else aaa
<?php } ?>