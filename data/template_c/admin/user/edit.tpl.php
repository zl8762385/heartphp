<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381479946|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/user/edit.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'user', 'index') ?>" class="button"><span>列表</span></a>
</div>
<div class="col w10">
         <form action="<?php echo get_url('admin', 'user', 'edit') ?>" method="post">
            <?php echo form::input('userid', $userdata['userid'], 'hidden') ?>
            <fieldset>
            <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
           
            <p>
              <label>用户名</label>
                <?php echo form::input('data[username]', $userdata['username']) ?>
            </p>
            <p>
              <label>密码</label>
                <?php echo form::input('data[password]', '','password') ?>
            </p>
            <p>
              <label>确认密码</label>
                <?php echo form::input('data[confirmpassword]', '', 'password') ?>
            </p>
            <p>
              <label>Email</label>
                <?php echo form::input('data[email]', $userdata['email']) ?>
            </p>
            <p>
              <label>真实姓名</label>
                <?php echo form::input('data[truename]', $userdata['truename']) ?>
            </p>
            <p>
              <label>所属用户组</label>
                <?php echo form::select('data[groupid]', $groupdata, $userdata['groupid']) ?>
            </p>       

            <p>
              <input class="button" type="submit" name="dosubmit" value="提交" />
            </p>
            </fieldset>
            <div class="clear"></div>
            <!-- End .clear -->
          </form>
</div>