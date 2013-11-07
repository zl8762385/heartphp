<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1381583655|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/model/filed.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10 margin_top_10 buttons_demo">
    <a href="<?php echo get_url('admin', 'model', 'filed', 'id='.$m_id) ?>" class="button"><span>列表</span></a>
    <a href="<?php echo get_url('admin', 'model', 'add_filed', 'id='.$m_id) ?>" class="button"><span>增加字段</span></a>
</div>

<div class="col w10">
         <form action="<?php echo get_url('admin', 'model', 'filed_action', 'id='.$m_id) ?>" method="post">
          <input type="hidden" name="m_id" value="<?php echo $m_id ?>" />
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>显示在内容列表</th>
                <th>排序</th>
                <th>标题</th>
                <th>字段名</th>
                <th>字段类型</th>
                <th>操作</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="7">
                 <div class="bulk-actions align-left">
                    <select name="actions_switch" style="float:left;">
                      <option value="0">选择...</option>
                      <option value="orders">排序</option>
                      <option value="content_list">显示在内容列表</option>
                      <!--<option value="delete">删除</option>-->
                    </select>
                    <input type="submit" value="应用" name="dosubmit" style="margin:3px 0 0 5px;"> </div>

                  <div class="pagination">  </div>
                  <!-- End .pagination -->
                  <div class="clear"></div>
                </td>
              </tr>
            </tfoot>
            <tbody>
            <?php foreach($lists as $k => $v) { ?>
              <tr>
                 <td><?php echo $v['id'] ?></td>
                 <td>
                  <?php echo form::radio('content_list['.$v['id'].']', '1', $v['content_list']) ?>是 <?php echo form::radio('content_list['.$v['id'].']', '0', $v['content_list']) ?>否
               </td>
                <td><input style='width:50px;text-align:center;' type='text' name='orders[<?php echo $v['id'] ?>]' value='<?php echo $v['orders'] ?>'/></td>
                <td><?php echo $v['title'] ?></td>
                <td><?php echo $v['name'] ?></td>
                <td><?php echo $filed_type[$v['type']] ?></td>
                <td>
                  <a href="<?php echo get_url('admin', 'model', 'edit_filed', 'id='.$v['id'].'') ?>" title="">修改</a> 
                  <a href="javascript:_confirm('<?php echo get_url('admin', 'model', 'del_filed', 'id='.$v['id'].'') ?>', '您确认要删除该信息吗?')" title="">删除</a>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
          </form>
</div>