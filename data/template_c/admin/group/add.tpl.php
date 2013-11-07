<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381479835|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/group/add.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'group', 'index') ?>" class="button"><span>列表</span></a>
</div>

<div class="col w10">
          <form action="<?php echo get_url('admin', 'group', 'add') ?>" method="post">
            <fieldset>
            <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
           
            <p>
              <label>用户组名称</label>
                <?php echo form::input('data[name]') ?>
            </p>
            <p>
              <label>用户组描述</label>
                <?php echo form::textarea('data[description]', '', '10', '50', 'textarea') ?>
            </p>
            <p>
              <label><a href="<?php echo get_url('admin', 'group', 'power') ?>" rel="facebox">点击设置权限</a></label>
                
                <span action-type="user_group_lists"></span>
            </p>
           

            <p>
              <input class="button" type="submit" name="dosubmit" value="提交" />
            </p>
            </fieldset>
            <div class="clear"></div>
            <!-- End .clear -->
          </form>
</div>