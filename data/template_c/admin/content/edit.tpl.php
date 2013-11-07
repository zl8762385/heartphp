<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381579561|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/content/edit.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?> 
<div class="col w10 margin_top_10 buttons_demo">
  <span class="strong red">当前栏目：<?php echo $category['name'] ?></span></a>
</div>

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'content', 'content_list', 'catid='.$category['id']) ?>" class="button"><span>返回内容列表</span></a>
</div>

<div class="col w10">
          <form id="form1" action="<?php echo get_url('admin', 'content', 'edit') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="data[catid]" value="<?php echo $category['id'] ?>">
            <input type="hidden" name="data[modelid]" value="<?php echo $category['modelid'] ?>">
            <fieldset>
            <?php foreach($form_data as $k => $v) { ?>
            <p>
              <label><?php echo $v['name'] ?></label>
              <?php echo $v['input'] ?>
            </p>
            <?php } ?>
            <p>
              <?php if(!empty($form_data)) { ?>
                  <input class="button" type="submit" name="dosubmit" value="提交" />
              <?php } ?>
              
            </p>
            </fieldset>
            <div class="clear"></div>
          </form>
</div>