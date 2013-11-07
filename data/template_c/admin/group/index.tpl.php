<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1382928050|Compiled from##/home/zhangliang/workspace/webapps/heartphp/tpl/admin/group/index.html*/?>
<?php include("/home/zhangliang/workspace/webapps/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'group', 'index') ?>" class="button"><span>列表</span></a>
    <a class="button" href="<?php echo get_url('admin', 'group', 'add') ?>"><small class="icon plus"></small><span>增加用户组</span></a>
</div>

<div class="col w10">
          <table>
            <thead>
              <tr>
          
                <th>ID</th>
                <th>名称</th>
                <th>创建时间</th>
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
                <td><?php echo $v['id'] ?></td>
                <td><?php echo $v['name'] ?></td>
                <td><?php echo date('Y-m-d H:i:s', $v['createtime']) ?></td>
                <td>
                  <a href="<?php echo get_url('admin', 'group', 'edit', 'id='.$v['id'].'') ?>">修改</a> 
                  <a href="javascript:_confirm('<?php echo get_url('admin', 'group', 'delete', 'id='.$v['id'].'') ?>', '您确认要删除该信息吗?')" title="">删除</a>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
</div>