<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381572229|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/category/add.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'category', 'index') ?>" class="button"><span>列表</span></a>
</div>

<div class="col w10">
          <form action="<?php echo get_url('admin', 'category', 'add') ?>" method="post">
            <input type="hidden" name="data[parentid]" value="<?php echo $parentid ?>">
            <fieldset>
            <?php if(!empty($parentname)) { ?>
             <p>
              <label>父级菜单</label>
                <span class="colorred"><?php echo $parentname ?></span>
              </p>
            <?php } ?>
             <p>
              <label>模型</label>
                <?php echo form::select('data[modelid]', $model_data) ?>
              </p>
           
            <p>
              <label>名称</label>
                <?php echo form::input('data[name]') ?>
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