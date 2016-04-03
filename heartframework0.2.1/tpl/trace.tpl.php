<style type="text/css">
#tab1 li {list-style:none;}
#tab1{width:100%;height:34px;border-bottom:0; margin:10px 0 0 0; color:#d6d6d6}
#tab1 ul{margin:0;padding:0;}
#tab1 li{float:left;padding:0 30px;height:34px;line-height:34px;text-align:center;cursor:pointer;}
#tab1 li.now{color:#000;background:#fff;font-weight:bold;}
.tablist{width:99%;height:240px;font-size:9pt;line-height:24px;display:none;}
.block{display:block;}
.heart_lines {border-bottom:1px solid #ddddcc; height:30px; line-height:30px; }
.heart_lines span{font-weight:800;color:red;}
</style>

     <script type="text/javascript">
        function trace_settab(m,n){
            var menu=document.getElementById("tab"+m).getElementsByTagName("li");  
            var showdiv=document.getElementById("tablist"+m).getElementsByTagName("div");  
            for(i=0;i<menu.length;i++){
               menu[i].className=i==n?"now":""; 
               showdiv[i].style.display=i==n?"block":"none"; 
            }
        }
    </script>

       <div id="heart_page_trace" style="background:white;margin:6px;border:1px dashed silver;padding:8px; float:left;width:98%; font-size:9pt;">
<fieldset id="querybox" style="margin:5px;">
<legend style="color:gray;font-weight:bold">页面Trace信息</legend>
<div style="overflow:auto;height:300px;text-align:left; ">
    <div id="tab1">
        <ul>
           <li onmouseover="trace_settab(1,0)" class="now">基本</li>
           <li onmouseover="trace_settab(1,1)">文件</li>
           <li onmouseover="trace_settab(1,2)">SQL</li>
        </ul>
    </div>

    <div id="tablist1">
        <div class="tablist block">
            <p class="heart_lines"><span>请求信息：</span> <?php echo $date;?>  <?php echo $_SERVER['SERVER_PROTOCOL'];?></p>
            <p class="heart_lines"><span>当前页面：</span> <?php echo $_SERVER['REQUEST_URI'];?></p>
            <p class="heart_lines"><span>目录：</span> <?php echo $_SERVER['DOCUMENT_ROOT'];?></p>
            <p class="heart_lines"><span>加载文件：</span> <?php echo $included_files_count;?></p>
            <p class="heart_lines"><span>SQL：</span> <?php echo $sqls_count;?>条</p>
        </div>
        <div class="tablist">
                系统在运行时所加载的文件
            <p class="heart_lines">
                <span>文件总数：</span><?php echo $included_files_count;?>个文件
            </p>
            <pre>
            <?php echo $included_files_dump;?>
            </pre>
        </div>
        <div class="tablist">
            <pre>
                <?php echo $sqls_lists;?>
            </pre>
        </div>
    </div>
</div>
</fieldset>
</div>