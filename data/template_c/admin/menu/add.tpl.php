<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381480008|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/menu/add.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?>

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'menu', 'index') ?>" class="button"><span>列表</span></a>
</div>

<div class="col w10">
      <form action="<?php echo get_url('admin', 'menu', 'add') ?>" method="post">
            <input type="hidden" name="dosubmit_add" value="1" />
            <input type="hidden" name="data[parentid]" value="<?php echo $parentid ?>">
            <fieldset>
            <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
            <?php if(!empty($parentname)) { ?>
             <p>
              <label>父级菜单</label>
                <?php echo $parentname ?>
              </p>
            <?php } ?>
            <p>
              <label>名称</label>
                <?php echo form::input('data[name]') ?>
              </p>
            <p>
              <label>目录</label>
              <?php echo form::input('data[directory]') ?> <span style="color:#ccc">如果您没有设置目录，此处可为空</span>
            </p>
            <p>
              <label>文件名</label>
              <?php echo form::input('data[controller]') ?> 
            </p>
            <p>
              <label>方法</label>
              <?php echo form::input('data[methed]') ?> <span style="color:#ccc">函数名</span>
            </p>
            <p>
              <label>显示</label>
              <?php echo form::radio('data[is_display]', '1', '1') ?>是 <?php echo form::radio('data[is_display]', '0') ?>否
            </p>

            <p>
              <input class="button" type="submit" name="dosubmit" value="提交" />
            </p>
            </fieldset>
            <div class="clear"></div>
            <!-- End .clear -->
      </form>
</div>