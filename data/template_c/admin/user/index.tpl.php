<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1382928050|Compiled from##/home/zhangliang/workspace/webapps/heartphp/tpl/admin/user/index.html*/?>
<?php include("/home/zhangliang/workspace/webapps/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'user', 'index') ?>" class="button"><span>列表</span></a>
    <a class="button" href="<?php echo get_url('admin', 'user', 'add') ?>"><small class="icon plus"></small><span>增加管理员</span></a>
</div>

<div class="col w10">
         <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>用户名</th>
                <th>真实姓名</th>
                <th>Email</th>
                <th>所属用户组</th>
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
                <td><?php echo $v['userid'] ?></td>
                <td><?php echo $v['username'] ?></td>
                <td><?php echo $v['truename'] ?></td>
                <td><?php echo $v['email'] ?></td>
                <td><?php echo $groupdata[$v['groupid']]['name'] ?></td>
                <td>
                  <?php if($v['userid'] == $userinfo['userid'] || $userinfo['userid'] == 1) { ?>
                  <a href="<?php echo get_url('admin', 'user', 'edit', 'id='.$v['userid'].'') ?>" title="">修改</a> 
                  <a href="javascript:_confirm('<?php echo get_url('admin', 'user', 'delete', 'id='.$v['userid'].'') ?>', '您确认要删除该信息吗?')" title="">删除</a>
                  <?php } ?>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
</div>