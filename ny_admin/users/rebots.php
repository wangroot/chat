<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'users_admin')===false)exit("没有权限！");
$auth_rule_userdel=false;
if(stripos(auth_group($_SESSION['login_gid']),'users_del')!==false)$auth_rule_userdel=true;

switch($act){
    case "rebots_del":
        if(stripos(auth_group($_SESSION['login_gid']),'users_del')===false)exit("没有权限！");
          if(is_array($id)){
                  rebots_del(implode(',',$id));  
                }
        else{
                    rebots_del($id); 
                }
        header("location:?gid=".$gid);
    break;
}

$query=$db->query("select id,title from {$tablepre}auth_group where id not in (2,3,4) order by id desc");
while($row=$db->fetch_row($query)){
    $group.='<option value="'.$row[id].'">'.$row[title].'</option>';
}
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>机器人管理</title>
    <meta name="keywords" content="机器人管理">
    <meta name="description" content="机器人管理">

    <link rel="shortcut icon" href="favicon.ico"> 
    <link href="../assets/H+/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="../assets/H+/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="../assets/H+/css/plugins/jqgrid/ui.jqgridffe4.css?0820" rel="stylesheet">
    <link href="../assets/H+/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="../assets/H+/css/plugins/bui/bui-min.css" rel="stylesheet">
    <link href="../assets/H+/css/animate.min.css" rel="stylesheet">
    <link href="../assets/H+/css/animate.min.css" rel="stylesheet">
    <link href="../assets/H+/css/style.min862f.css?v=4.1.0" rel="stylesheet">


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

function ftime2(time){
    var hour=0;
    var minute=0;
    var second=0;
       if(time>(60*60)){
        hour=parseInt(time/(60*60));
       }
       var yu=time-hour*60*60;
        if(yu>60){
           minute =parseInt(yu/60);}
         second=yu-minute*60;
        second   = second.toString()  
        if(second.length<2){
            second='0'+second;
        }
        minute   = minute.toString()  
        if(minute.length<2){
            minute='0'+minute;
        }
     return hour+":"+minute+":"+second;
}

</script>
</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
       
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>机器人管理</h5>
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
                    
                    <form  class="form-horizontal" action="" method="get">   
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" placeholder="请输入关键词"  name="skey" id="rolename" class="input-sm form-control"> <span class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-primary"> 搜索</button> </span>
                                </div>
                            </div>

                            <div class="col-sm-3">
                            	<div class="input-group">
                                   <select name="sgid" id="sgid" class="form-control m-b" >
                                    <option value="-1" selected = "selected">全部</option>
                                    <?=$group?>
                                    </select>
                            	<button type="submit" class="btn btn-sm btn-primary">查询</button>
                            	<button type="button" class="btn-sm btn-primary"  onclick="doexcel()">导入机器人</button>
                            	<button class="btn btn-sm"  onclick="if(confirm('确定删除所有记录？'))location.href='?act=user_del&amp;id=all'">删除所有</button>
                            	<p>用户名、昵称、推广人为查询字段</p>
                            	</div>
                            </div>
                            
                        </div>
                    </form>



                        <div class="table-responsive">

   <form action="" method="POST" enctype="application/x-www-form-urlencoded"  class="form-horizontal" id="hd_list">
    <input type="hidden" name="gid" value="<?=$gid?>">
    <input type="hidden" name="act" value="rebots_del">                        
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    	<th>ID</th>
                                        <th><input type="checkbox" onClick="$('.ids').attr('checked',this.checked); "></th>
                                        <th>机器人昵称</th>
                                        <th>用户组</th>
                                        <th>用户组ico</th>
                                        <th>性别</th>
                                        <th>上线时间</th>
                                        <th>下线时间</th>
                                        <th>审核</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   

<?php
$sql="select r.*,a.title,a.ico from {$tablepre}rebot_custom r,{$tablepre}auth_group a  where r.gid=a.id ";
$skey=trim($skey);
if($skey!=""){
    $sql.=" and r.rebotname like '%$skey%' ";
}

if($sgid!="" && $sgid!="-1" ){
    $sql.=" and r.gid='$sgid'";
}


$sql.=" order by id desc";
if(!$auth_rule_userdel)$display_delbutton='style="display:none"';
echo rebotlist(20,$sql,'
                <tr>
                <td>{id}</td>
                <td>
               <input type="checkbox" class="ids" name="id[]" value="{id}">
                </td>
                <td>{rebotname}</td>
                <td>{title}</td>
                <td>{ico}</td>
                <td>{sex}</td>
                <td><script>document.write(ftime2({shangxian})); </script></td>
                <td><script>document.write(ftime2({xiaxian})); </script></td>
                <td>
                <div class="onoffswitch">
                <input type="checkbox" checked="" class="onoffswitch-checkbox" id="example1">
                <label class="onoffswitch-label" for="example1">
                <span class="onoffswitch-inner"></span>
                <span class="onoffswitch-switch"></span>
                </label>
                </div>
                </td>
                <td>
                <button type="button" class="btn btn-xs" onclick="openUser(3425,3)" ><i class="fa fa-edit text-navy"></i></button>
                <button type="button" class="button button-mini button-danger" onclick="if(confirm(\'确定删除用户？\'))location.href=\'?act=rebots_del&id={id}\'" '.$display_delbutton.'><i class="x-icon x-icon-small icon-trash icon-white"></i>删除</button>
                </td>
                </tr>
                </td>
                </tr>
')?>
                                </tbody>
                            </table>
                </form>                 
                        </div>
                     <div class="row">
                     	<div class="col-sm-6">
                     		<!-- <div class="input-group">
                     			<button type="button" class="btn btn-white fa fa-plus" onclick="plusUser(3425,3)" ></button>
                     			<button type="button" class="btn btn-white fa fa-edit" onclick="openUser(3425,3)" ></button>
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
    <script src="js/jquery.min63b9.js?v=2.1.4"></script>
  
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui.js"></script> 
<script type="text/javascript" src="../assets/js/config.js"></script> 
<script type="text/javascript" src="../../upload/swfupload/swfupload.js"></script> 
<script>
    
BUI.use('bui/overlay',function(Overlay){
            dialog = new Overlay.Dialog({
            title:'机器人设置',
            width:630,
            height:470,
            buttons:[],
            bodyContent:''
          });
});
function openUser(id,type){
    dialog.set('bodyContent','<iframe src="rebot_edit.php?id='+id+'&gid='+type+'" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
    dialog.updateContent();
    dialog.show();
}
BUI.use('bui/overlay',function(Overlay){
            dialog2 = new Overlay.Dialog({
            title:'添加机器人',
            width:630,
            height:470,
            buttons:[],
            bodyContent:''
          });
});
function addUser(){
    dialog2.set('bodyContent','<iframe src="rebot_add.php" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
    dialog2.updateContent();
    dialog2.show();
}
BUI.use('bui/overlay',function(Overlay){
            dialog3 = new Overlay.Dialog({
            title:'导入机器人',
            width:600,
            height:230,
            buttons:[],
            bodyContent:''
          });
});
function doexcel(){
    dialog3.set('bodyContent','<iframe src="dotxt.php" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
    dialog3.updateContent();
    dialog3.show();
}
  </script>
</body>
</html>
