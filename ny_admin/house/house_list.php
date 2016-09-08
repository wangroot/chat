<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'users_group')===false)exit("没有权限！");
switch($act){
	case "group_add":
		group_add($title,$sn,$ico,$ov);
	break;
	case "group_del":
		group_del($id);
	break;
	case "group_edit":
		group_edit($id,$title,$sn,$ico,$ov);
	break;
        case "house_del":
               house_del($id);
        break;
}
?>
<!DOCTYPE HTML>
<html>
   <head>
   <title></title>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <link href="../assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="../assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   <link href="../assets/css/page-min.css" rel="stylesheet" type="text/css" />
   <!-- 下面的样式，仅是为了显示代码，而不应该在项目中使用-->
   <link href="../assets/css/prettify.css" rel="stylesheet" type="text/css" />
   <style type="text/css">
code { padding: 0px 4px; color: #d14; background-color: #f7f7f9; border: 1px solid #e1e1e8; }
</style>
   </head>
   <body>
<div class="container" style=" min-width:700px;">
     <ul class="breadcrumb">
    <li class="active">
         <button type="submit"  class="button button-success" id="add_group_bt" onclick="addHouse();"><i class="icon icon-plus icon-white"></i> 添加</button>
         &nbsp;&nbsp;</li>
  </ul>
     <table  class="table table-bordered table-hover definewidth m10" >
    <thead>
         <tr style="font-weight:bold" >
        <td width="50" align="center" bgcolor="#FFFFFF">ID</td>
        <td width="150" align="center" bgcolor="#FFFFFF">标题</td>
        <td width="150" align="center" bgcolor="#FFFFFF">名称</td>
        <td width="150" align="center" bgcolor="#FFFFFF">关键字</td>
        <td width="150" align="center" bgcolor="#FFFFFF">描述</td>
        <td width="60" align="center" bgcolor="#FFFFFF">模板</td>
        <td width="80" align="center" bgcolor="#FFFFFF">直播源</td>
        <td width="60" align="center" bgcolor="#FFFFFF">机器人数</td>
        <td width="60" align="center" bgcolor="#FFFFFF">房间状态</td>
        <td width="60" align="center" bgcolor="#FFFFFF">排序</td>
        <td width="120" align="center" bgcolor="#FFFFFF">修改时间</td>
        <td width="100" align="center" bgcolor="#FFFFFF">操作</td>
      </tr>
       </thead>
<?php
$query=$db->query("select * from {$tablepre}config");
while($row=$db->fetch_row($query)){
?>      
    <tr>
         <td bgcolor="#FFFFFF" align="center"><?=$row[id]?>&nbsp; </td>
         <td bgcolor="#FFFFFF" align="center"><a href="http://<?php echo $_SERVER['SERVER_NAME'].'/'.$row[id]?>" target="_bank"><?=$row[title]?></a></td>
         <td align="center" bgcolor="#FFFFFF"><?=$row[subject]?>&nbsp; </td>
         <td align="center" bgcolor="#FFFFFF"><?=$row[keys]?>&nbsp; </td>
         <td align="center" bgcolor="#FFFFFF"><?=$row[dc]?>&nbsp; </td>
         <td align="center" bgcolor="#FFFFFF"><?=$row[tpid]?>&nbsp; </td>
         <td align="center" bgcolor="#FFFFFF"><? if($row['livetype']==1) echo 'YY转播';if($row['livetype']==0) echo '流媒体直播';?>&nbsp; </td>
         <td align="center" bgcolor="#FFFFFF"><?=$row[rebots]?>&nbsp; </td>
         <td align="center" bgcolor="#FFFFFF"><? if($row[state]==1) echo '开启';if($row[state]==0) echo '关闭';if($row[state]==2) echo '加密';if($row[state]==3) echo '开启时间';?>&nbsp; </td>
         <td align="center" bgcolor="#FFFFFF"><?=$row[sortid]?>&nbsp;</td>
         <td bgcolor="#FFFFFF" align="center"><?=date('Y-m-d H:i',$row[uptime]);?>&nbsp;</td>
         <td bgcolor="#FFFFFF" align="center">
         <!--<button class="button button-mini button-warning" onClick="openRule('<?=$row[id]?>','<?=$row[type]?>')"><i class="x-icon x-icon-small icon-check icon-white"></i>权限</button>-->

        <button class="button button-mini button-info"  onClick="house_edit_bt('<?=$row[id]?>')"><i class="x-icon x-icon-small icon-wrench icon-white"></i>修改</button>
<?php if($row[id]!=1){?><button class="button button-mini button-danger" onclick="if(confirm('确认要删除此房间吗，是否继续？'))location.href='?act=house_del&id=<?=$row[id]?>'"><i class="x-icon x-icon-small icon-trash icon-white"></i>删除</button><?php }?></td>
    </tr>
<?php }?>       
  </table>
     <div class="row">
    
  </div>
   </div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui.js"></script> 
<script type="text/javascript" src="../assets/js/config.js"></script> 
<script type="text/javascript" src="../../upload/swfupload/swfupload.js"></script> 
<script>
BUI.use('bui/overlay',function(Overlay){
            dialog = new Overlay.Dialog({
            title:'用户组权限编辑',
            width:800,
            height:600,
            buttons:[],
            bodyContent:''
          });
});
function openRule(id,type){
	dialog.set('bodyContent','<iframe src="group_rule.php?id='+id+'&type='+type+'" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
	dialog.updateContent();
	dialog.show();
}
function openGroupUser(id,name){
	top.topManager.openPage({
		id : 'GroupUser'+id,
		href : 'users/users.php?gid='+id,
		title : name+' 成员'
	  });
	top.topManager.reloadPage();
}

  function house_edit_bt(id){        
              //alert(id);
              window.location.href="house_edit.php?rid="+id;
  }
  
 BUI.use('bui/overlay',function(Overlay){
            dialog2 = new Overlay.Dialog({
            title:'添加房间',
            width:590,
            height:420,
            buttons:[],
            bodyContent:''
          });
});    
  
 //添加房间 
 function addHouse(){
	dialog2.set('bodyContent','<iframe src="house_add.php" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
	dialog2.updateContent();
	dialog2.show();
} 
  </script>
</body>
</html>
