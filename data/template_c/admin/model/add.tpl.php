<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381482262|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/model/add.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'model', 'index') ?>" class="button"><span>列表</span></a>
</div>

<div class="col w10">
          <form action="<?php echo get_url('admin', 'model', 'add') ?>" method="post">
            <fieldset>
            <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
           
            <p>
              <label>模型名称</label>
                <?php echo form::input('data[name]', '') ?>
            </p>
            <p>
              <label>数据表名</label>
                <?php echo form::input('data[table_name]', '','') ?>
            </p>
            <p>
              <label>描述</label>
                <?php echo form::textarea('data[description]', '', '', '', '', 'width:300px;height:50px;') ?>
            </p>
           

            <p>
              <input class="button" type="submit" name="dosubmit" value="提交" />
            </p>
            </fieldset>
            <div class="clear"></div>
            <!-- End .clear -->
          </form>
</div>