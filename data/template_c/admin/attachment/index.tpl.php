<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381480033|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/attachment/index.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'attachment', 'index') ?>" class="button"><span>附件管理</span></a>
</div>

<div class="col w10">
         <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>模块</th>
                <th>文件名</th>
                <th>后缀</th>
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
                <td><?php echo (empty($catetory_data[$v['catid']]['name'])) ? '' : '栏目：'.$catetory_data[$v['catid']]['name'] ; ?></td>
                <td><a target="_blank" href="<?php echo $v['filepath'] ?><?php echo $v['filename'] ?>"><?php echo $v['filename'] ?></a></td>
                <td><?php echo $v['fileext'] ?></td>
                <td>
                  <?php if($v['userid'] != 1) { ?>
                
                  <a href="javascript:_confirm('<?php echo get_url('admin', 'attachment', 'delete', 'id='.$v['id'].'') ?>', '您确认要删除该信息吗?')" title="">删除</a>
                  <?php } ?>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
</div>