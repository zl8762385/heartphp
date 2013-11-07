<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381480031|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/log/index.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'log', 'index') ?>" class="button"><span>日志管理</span></a>
</div>

<div class="col w10">
         <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>IP</th>
                <th>目录</th>
                <th>控制器</th>
                <th>方法</th>
                <th>操作时间</th>
                <th>其他</th>
                <th>操作</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="8">
                 
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
                <td><?php echo $v['ip'] ?></td>
                <td><?php echo $v['d'] ?></td>
                <td><?php echo $v['c'] ?></td>
                <td><?php echo $v['a'] ?></td>
                <td>
                  <?php if(empty($v['createtime'])) { ?>
                    无
                  <?php } else { ?>
                    <?php echo date('Y-m-d H:i:s', $v['createtime']) ?>
                  <?php } ?>
                  </td>
                <td>
                  <?php $op = string2array($v['op']) ?>
                  <?php if(empty($op['username'])) { ?>
                    无
                  <?php } else { ?>
                    <?php echo $op['username'] ?> [<span style="color:red"><?php echo $op['userid'] ?></span>]
                  <?php } ?>
                  
                </td>
                <td>
                  <a href="<?php echo get_url('admin', 'log', 'details', 'id='.$v['id'].'') ?>">详细</a>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
</div>