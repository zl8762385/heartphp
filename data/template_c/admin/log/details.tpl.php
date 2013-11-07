<? if(!defined('IS_HEARTPHP')) exit('Access Denied');/*Create On##1378188763|Compiled from##D:/PHPnow/htdocs/heartphp/tpl/admin/log/details.html*/?>
<?php include("D:/PHPnow/htdocs/heartphp/data/template_c/public/header.tpl.php")?> 

<div class="col w10">
         <form>
            <fieldset>
              <?php if(!empty($infos['createtime'])) { ?>
            <p>
              <label>创建时间</label>
                <input type="text" class="text w_15" value="<?php echo date('Y-m-d H:i:s', $infos['createtime']) ?>" />
            </p>
              <?php } ?>
            <p>
              <label>IP</label>
                <input type="text" class="text w_15" value="<?php echo $infos['ip'] ?>" />
            </p>
            <p>
              <label>目录</label>
                <input type="text" class="text w_15" value="<?php echo $infos['d'] ?>" />
            </p>
            <p>
              <label>控制器</label>
                <input type="text" class="text w_15" value="<?php echo $infos['c'] ?>" />
            </p>
            <p>
              <label>方法</label>
                <input type="text" class="text w_15" value="<?php echo $infos['a'] ?>" />
            </p>
            <?php if(!empty($infos['referer'])) { ?>
            <p>
              <label>REFERER</label>
                <input type="text" class="text w_15" value="<?php echo $infos['referer'] ?>" />
            </p>
            <?php } ?>
            <p>
              <label>操作信息</label>
                  <textarea cols="50" rows="5">
                    <?php echo trim($infos['op']) ?>
                  </textarea>
            </p>       
            </fieldset>
            <div class="clear"></div>
            <!-- End .clear -->
          </form>
</div>