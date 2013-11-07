<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1382719774|Compiled from##/home/zhangliang/workspace/webapps/heartphp/tpl/admin/group/edit.html*/?>
<?php include("/home/zhangliang/workspace/webapps/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'group', 'index') ?>" class="button"><span>列表</span></a>
</div>

<div class="col w10">
          <form action="<?php echo get_url('admin', 'group', 'edit') ?>" method="post">
            <?php echo form::input('id', $data['id'], 'hidden') ?>
            <fieldset>
            <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
           
            <p>
              <label>用户组名称</label>
                <?php echo form::input('data[name]', $data['name']) ?>
            </p>
            <p>
              <label>用户组描述</label>
                <?php echo form::textarea('data[description]', $data['description'], '10', '50', 'textarea') ?>
            </p>
            <p>
              <label><a href="<?php echo get_url('admin', 'group', 'power') ?>" rel="facebox">设置用户组权限</a></label>
                <span action-type="user_group_lists">
                  <?php if($data['group_value'] != '') { ?>
                    <?php $grouplist = explode(',', $data['group_value']); ?>
                    <?php foreach($grouplist as $k => $v) { ?>
                 
                      <input checked="checked" type="checkbox" value="<?php echo $v ?>" name="data[group_value][]" /> 
                      <?php echo $menulists[$v]['name'] ?>
                    <?php } ?>
                  <?php } ?>                  
                </span>
            </p>
           

            <p>
              <input class="button" type="submit" name="dosubmit" value="提交" />
            </p>
            </fieldset>
            <div class="clear"></div>
            <!-- End .clear -->
          </form>
</div>