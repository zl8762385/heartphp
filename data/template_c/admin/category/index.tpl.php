<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1383193444|Compiled from##/home/zhangliang/workspace/webapps/heartphp/tpl/admin/category/index.html*/?>
<?php include("/home/zhangliang/workspace/webapps/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'category', 'index') ?>" class="button"><span>列表</span></a>
    <a class="button" href="<?php echo get_url('admin', 'category', 'add') ?>"><small class="icon plus"></small><span>增加栏目</span></a>
</div>

<div class="col w10">
      <form action="<?php echo get_url('admin', 'category', 'action') ?>" method="post">
          <table>
            <thead>
              <tr>
                <th style="width:50px;">排序<!--<input class="check-all" type="checkbox" />--></th>
                <th>ID</th>
                <th>名称</th>
                <th>显示状态</th>
                <th>创建时间</th>
                <th>操作</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="6">
                  
                  <div class="bulk-actions align-left">
                    <select name="actions_switch" style="float:left;">
                      <option value="0">选择...</option>
                      <option value="orders">排序</option>
                      <!--<option value="delete">删除</option>-->
                    </select>
                    <input type="submit" value="应用" name="dosubmit" class="button" style="margin:3px 0 0 7px;"> 
                  </div>
                  
                  <div class="pagination"> <?php echo $pages ?> </div>
                  <!-- End .pagination -->
                  <div class="clear"></div>
                </td>
              </tr>
            </tfoot>
            <tbody>
              <?php echo $categorys ?>
              <!--
              <tr>
                <td><?php echo $v['id'] ?></td>
                <td><?php echo $v['name'] ?></td>
                <td><?php echo date('Y-m-d H:i:s', $v['createtime']) ?></td>
                <td>
                  <a href="#" title="">查看子菜单</a> 
                  <a href="#" title="">修改</a> 
                  <a href="javascript:_confirm('<?php echo get_url('admin', 'menu', 'delete', '?id='.$v['id'].'') ?>', '您确认要删除该信息吗?')" title="">删除</a>
                </td>
              </tr>
            -->
            </tbody>
          </table>
        </form>
</div>