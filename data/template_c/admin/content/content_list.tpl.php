<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381579575|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/content/content_list.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?> 
<div class="col w10 margin_top_10 buttons_demo">
  <span class="strong red">当前栏目：<?php echo $category['name'] ?></span></a>
</div>
<div class="col w10 margin_top_10 buttons_demo">
  
  
  <a href="<?php echo get_url('admin', 'content', 'index') ?>" class="button"><span>返回栏目列表</span></a>
  <a class="button" href="<?php echo get_url('admin', 'content', 'add', 'catid='.$category['id']) ?>"><small class="icon plus"></small><span>增加内容</span></a>
</div>

<div class="col w10">
          <table>
            <thead>
              
              <tr>
                <th style="width:5%;text-align:center;">ID</th>
                <?php foreach($filed_list as $k => $v) { ?>
                <th><?php echo $v['title'] ?></th>
                <?php } ?>
                <th style="width:10%;">操作</th>
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
                <td style="text-align:center;"><?php echo $v['id'] ?></td>
              
                <?php foreach($filed_list as $filed_k => $filed_v) { ?>
                <td>
                <?php if($filed_v['type'] == 'datetime') { ?>
                  <?php echo (!empty($v[$filed_v['name']])) ? date('Y-m-d', $v[$filed_v['name']]) : '无' ; ?>
                <?php } else { ?>
                  <?php echo $v[$filed_v['name']] ?>
                <?php } ?>
                </td>
                <?php } ?>
                <td>

                  <a href="javascript:_confirm('<?php echo get_url('admin', 'content', 'delete', 'id='.$v['id'].'&catid='.$v['catid'].'') ?>', '您确认要删除该信息吗?')">删除</a> 
                  | 
                  <a href="<?php echo get_url('admin', 'content', 'edit', 'id='.$v['id'].'&catid='.$v['catid'].'') ?>">修改</a></td>
              </tr>
            <?php } ?>
            <?php if(empty($lists)) { ?>
                <tr>
                <td colspan="6">
                  暂无数据
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
          
</div>