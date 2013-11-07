<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1382721221|Compiled from##/home/zhangliang/workspace/webapps/heartphp/tpl/admin/menu/edit.html*/?>
<?php include("/home/zhangliang/workspace/webapps/heartphp/data/template_c/public/header.tpl.php")?> 
 
 <div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'menu', 'index') ?>" class="button"><span>列表</span></a>
</div>

<div class="col w10">
          <form action="<?php echo get_url('admin', 'menu', 'edit') ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $edit_data['id'] ?>">
            <fieldset>
            <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
            <p>
              <label>名称</label>
                <?php echo form::input('data[name]', $edit_data[name]) ?>
              </p>
            <p>
            <p>
              <label>排序</label>
                <?php echo form::input('data[menuorder]', $edit_data[menuorder]) ?>
              </p>
            <p>
              <label>目录</label>
              <?php echo form::input('data[directory]', $edit_data[directory]) ?> <span style="color:#ccc">如果您没有设置目录，此处可为空</span>
            </p>
            <p>
              <label>文件名</label>
              <?php echo form::input('data[controller]', $edit_data[controller]) ?> 
            </p>
            <p>
              <label>方法</label>
              <?php echo form::input('data[methed]', $edit_data[methed]) ?> <span style="color:#ccc">函数名</span>
            </p>
            <p>
              <label>显示</label>
              <?php echo form::radio('data[is_display]', '1', $edit_data[is_display]) ?>是 <?php echo form::radio('data[is_display]', '0', $edit_data[is_display]) ?>否
            </p>

            <p>
              <input class="button" type="submit" name="dosubmit" value="提交" />
            </p>
            </fieldset>
            <div class="clear"></div>
            <!-- End .clear -->
          </form>
</div>