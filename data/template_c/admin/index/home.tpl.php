<?php if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1383193841|Compiled from##/home/zhangliang/workspace/webapps/heartphp/tpl/admin/index/home.html*/?>
 <?php include("/home/zhangliang/workspace/webapps/heartphp/data/template_c/public/header.tpl.php")?> 
<div class="col w10 buttons_demo" style="margin:20px 0 0 0;">
   <table border="0" cellspacing="1" cellpadding="3" class="table1">
        <tr>
          <th width="90">框架开发</th>
          <td width="210">张晓亮</td>
          <th width="90">主机名</th>
          <td width="210"><?php echo php_uname('n'); ?></td>
        </tr>
       
        <tr>
          <th>WEB服务器</th>
          <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
          <th>服务器域名</th>
          <td><?php echo $_SERVER['SERVER_NAME']?></td>
        </tr>
        <tr>
          <th>服务器IP</th>
          <td><?php echo isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '获取失败'; ?></td>
          <th>服务器端口</th>
          <td><?php echo $_SERVER['SERVER_PORT']; ?></td>
        </tr>

        <tr>
          <th>管理员</th>
          <td><?php echo isset($_SERVER['SERVER_ADMIN']) ? $_SERVER['SERVER_ADMIN'] : '没填'; ?></td>
          <th>服务器时间</th>
          <td><?php echo date("Y-m-d H:i:s")?></td>
        </tr>
        <tr>
            <th>根目录</th>
            <td colspan="3"><?php echo $_SERVER['DOCUMENT_ROOT']; ?></td>
        </tr>

      <th>Apache模块:</th>
      <td colspan="3"><?php echo implode(', ', (array) apache_get_modules()); ?></td>
    </tr>

    </table>
</div>
 