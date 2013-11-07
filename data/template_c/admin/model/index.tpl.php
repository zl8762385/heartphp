<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1383193443|Compiled from##/home/zhangliang/workspace/webapps/heartphp/tpl/admin/model/index.html*/?>
<?php include("/home/zhangliang/workspace/webapps/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'model', 'index') ?>" class="button"><span>列表</span></a>
    <a class="button" href="<?php echo get_url('admin', 'model', 'add') ?>"><small class="icon plus"></small><span>增加模型</span></a>
</div>

<div class="col w10">
        <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>模型名称</th>
                <th>数据表名</th>
                <th>描述</th>
                <th></th>
                <th>操作</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="6">
                 
                  <div class="pagination"> <?php echo $pages ?> </div>
                  <!-- End .pagination -->
                  <div class="clear"></div>
                </td>
              </tr>
            </tfoot>
            <tbody>
            <?php foreach($lists as $k => $v) { ?>
              <tr>
                <td><?php echo $v['id']; ?></td>
                <td><?php echo $v['name']; ?></td>
                <td><?php echo $v['table_name']; ?></td>
                <td><?php echo $v['description']; ?></td>
                <td></td>
                <td>
                  <a href="<?php echo get_url('admin', 'model', 'filed', 'id='.$v['id'].'') ?>" title="">管理字段</a> 
                  <a href="<?php echo get_url('admin', 'model', 'edit', 'id='.$v['id'].'') ?>" title="">修改</a> 
                  <a href="javascript:_confirm('<?php echo get_url('admin', 'model', 'delete', 'id='.$v['id'].'') ?>', '您确认要删除该信息吗?')" title="">删除</a>
               
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
</div>