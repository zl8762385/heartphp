<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381583651|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/model/edit_filed.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?> 
<script type="text/javascript">
  jQuery(document).ready(function () {
      field_setting('<?php echo $filed_info['type'] ?>', '<?php echo $filed_info['id'] ?>');
  });
</script>
<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'model', 'filed', 'id='.$filed_info['m_id']) ?>" class="button"><span>列表</span></a>
    <a href="<?php echo get_url('admin', 'model', 'add_filed', 'id='.$filed_info['m_id']) ?>" class="button"><span>增加字段</span></a>
</div>

<div class="col w10">
          <form action="<?php echo get_url('admin', 'model', 'edit_filed') ?>" method="post">
            <fieldset>
            <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
           
            <p>
              <label>所在模型</label>
               <?php echo form::input("m_id", $filed_info['m_id'], 'hidden') ?>
               <?php echo form::input("filed_id", $filed_info['id'], 'hidden') ?>
               <?php echo $model_info['name'] ?> 
            </p>
            <p>
              <label>标题</label>
               <?php echo form::input("data[title]", $filed_info['title']) ?>
            </p>
             <p>
              <label>字段名</label>
              <?php echo form::input("data[source_name]", $filed_info['name'], 'hidden') ?>
               <?php echo form::input("data[name]", $filed_info['name']) ?>
            </p>
            <p>
              <label>字段类型</label>
              <?php echo form::select("data[type]", $filed_type, $filed_info['type'], '<option value="0">请选择字段类型</option>', 'small-input', 'onchange="javascript:field_setting(this.value);"') ?>
            </p>
            <span node-type="target_filed"></span>
           
            <p>
              <label>字符长度取值范围</label>
              <?php $number_range = string2array($filed_info['number_range']) ?>
              最小 <?php echo form::input("data[number_range][min]", $number_range['min'], 'text', 'text w_5') ?>　
                　最大 <?php echo form::input("data[number_range][max]", $number_range['max'], 'text', 'text w_5') ?>
            </p>

            <p style="overflow:hidden">
              <label>数据校验正则</label>
              <?php echo form::input("data[pattern]", $filed_info['pattern'], 'text', 'text w_15', 'pattern') ?>
              <?php echo form::select('pattern_select', $pattern, '', '', '', 'onchange="javascript:$(\'#pattern\').val(this.value)"', 'float:left;margin:0 5px 0 0;') ?>
  
            </p>

            <p style="width:500px;">
              <label>描述</label>
              <?php $description = (empty($filed_info['description'])) ? '' : $filed_info['description'] ; ?>
              <?php echo form::textarea("data[description]", $description) ?>
            </p>
           

            <p>
              <input class="button" type="submit" name="dosubmit" value="提交" />
            </p>
            </fieldset>
            <div class="clear"></div>
            <!-- End .clear -->
          </form>
</div>