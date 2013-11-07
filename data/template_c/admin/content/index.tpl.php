<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381578934|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/content/index.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
</div>

<div class="col w10">
          <table>
            <thead>
              <tr>
                <!--
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
              -->
                <th>ID</th>
                <th>栏目名称</th>
                <th>操作</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="6">
                  <!--
                  <div class="bulk-actions align-left">
                    <select name="dropdown">
                      <option value="0">选择...</option>
                      <option value="edit">更改</option>
                      <option value="delete">删除</option>
                    </select>
                    <a class="button" href="#">应用</a> </div>
                  -->
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
              </tr>-->
            </tbody>
          </table>
</div>