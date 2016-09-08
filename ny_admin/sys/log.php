<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'sys_log')===false)exit("没有权限！");
switch($act){
    case "log_del":
        log_del(implode(',',$id));
    break;
    
    case "delhistory":
       
     log_delhistory($value);
       
        break;
}
?>



<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>日志管理</title>
    <meta name="keywords" content="日志管理">
    <meta name="description" content="日志管理">

    <link rel="shortcut icon" href="favicon.ico"> 
    <link href="../assets/H+/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="../assets/H+/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="../assets/H+/css/plugins/jqgrid/ui.jqgridffe4.css?0820" rel="stylesheet">
    <link href="../assets/H+/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="../assets/H+/css/plugins/bui/bui-min.css" rel="stylesheet">
    <link href="../assets/H+/css/animate.min.css" rel="stylesheet">
    <link href="../assets/H+/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="../assets/H+/css/style.min862f.css?v=4.1.0" rel="stylesheet">
        <script src="../assets/H+/js/jquery.min63b9.js?v=2.1.4"></script>
<script>
Date.prototype.Format = function (fmt) { //author: meizz 
    var o = {
        "M+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "h+": this.getHours(), //小时 
        "m+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}
function ftime(time){
    return new Date(time*1000).Format("yyyy-MM-dd hh:mm:ss"); ; 
}
var type=[];
type["0"]="聊天";
type["1"]="登陆";
type["2"]="注册";
type["3"]="入室";
</script>
</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
       
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>日志管理</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="table_basic.html#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                      <form  class="form-horizontal" action="" method="get">   
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" placeholder="请输用户名"  name="key" id="key" class="input-sm form-control"> 
                                   
                                    	<span class="input-group-btn">
                                        <button type="submit" class="btn btn-sm btn-primary"> 搜索</button> 
                                        <button type="button" class="btn btn-sm button-success"  onclick="doexcel()">导出Excel</button>
                                        <button type="button"  class="btn btn-sm btn-success  " id="add_ban_bt" onClick="if(confirm('确定删除？'))$('#log_list').submit()">删除所选</button>
                                       
                                    	</span>
                                </div>
                            </div>
                       </form>     
                            <div class="col-sm-3">
                            <div class="form-group">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" name="start" value="2014-11-11" />
                                <span class="input-group-addon">到</span>
                                <input type="text" class="input-sm form-control" name="end" value="2014-11-17" />
                            </div>
                        </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                   <form action="" method="POST" enctype="application/x-www-form-urlencoded"  class="form-horizontal" id="log_list"><input type="hidden" name="act" value="log_del">        
                            <table class="table table-striped">
                                <thead>
                                    <tr>

                                        <th>编号</th>   
                                        <th><input type="checkbox" onClick="$('.ids').attr('checked',this.checked); "></th>
                                        <th>UID</th>
                                        <th>用户名</th>
                                        <th>IP</th>
                                        <th>时间</th>
                                        <th>类型</th>
                                        <th>房间号</th>
                                        <th>描述</th>
                                        <th>来源</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>



                    <?php
                    echo log_list(20,$key,'

                                    <tr>
                                    	<td>{id}</td>
                                        <td><input type="checkbox" class="ids" name="id[]" value="{id}"></td>
                                        <td>{uid}</td>
                                        <td>{uname}</td>
                                        <td>{ip}</td>
                                        <td><script>document.write(ftime({mtime})); </script></td>
                                        <td><script>document.write(type[\'{type}\']); </script></td>
                                        <td>60606</td>
                                        <td>{msg}</td>
                                        <td>{laiyuan}</td>
                                        <td>
                                           <button type="button" class=" btn btn-xs" onclick="if(confirm(\'确定删除？\'))location.href=\'?act=log_del&id[]={id}\'" ><i class="fa fa-trash-o text-navy"></i>删除</button></td>
                                        </td>
                                    </tr>
                                    

                ')?>


                                </tbody>
                            </table>
                        </div>
                   </form>        
                     <div class="row">
                     	<div class="col-sm-6">
                     		<!-- <div class="input-group">
                     			
                     			<button type="button" class="btn btn-white fa fa-trash" onclick="openUser(3425,3)" ></button>
                     			<button type="button" class="btn btn-white fa fa-search" onclick="openUser(3425,3)" ></button>
                     			<button type="button" class="btn btn-white fa fa-refresh" onclick="openUser(3425,3)" ></button>
                     		</div> -->
                     	</div>
                     	
                      <div class="col-sm-6">
                      	<div class="input-group">
                      		<div class="btn-group">
                               <?=$pagenav?>
                            </div>
                      	</div>
                      </div>
                     </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui.js"></script> 
<script type="text/javascript" src="../assets/js/config.js"></script> 
<script type="text/javascript">
function delhistory(value){
    
    if(value=='0') return;
    if(confirm('确定删除日志历史记录吗？')){
      $.post("?act=delhistory",{value:value},
  function(data){
 location.reload() ;
 alert("删除历史记录成功");
  },
  "text");
    }
}
BUI.use('bui/overlay',function(Overlay){
            dialog3 = new Overlay.Dialog({
            title:'导出excel',
            width:600,
            height:240,
            buttons:[],
            bodyContent:''
          });
});
function doexcel(){
  dialog3.set('bodyContent','<iframe src="doexcel_choice.php?keys=<?=$key?>" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
  dialog3.updateContent();
  dialog3.show();
}
</script> 

 </script>
</body>
</html>

