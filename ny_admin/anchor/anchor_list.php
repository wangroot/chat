<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'users_group')===false)exit("没有权限！");

if($_REQUEST['act'] == 'anchor_del'){
    $id = $_REQUEST['id'];
    if($id){
        $db->query("delete from {$tablepre}anchor  where id = {$id}");
    }

    header('Location:anchor_list.php');
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
        <td width="150" align="center" bgcolor="#FFFFFF">账号</td>
        <td width="150" align="center" bgcolor="#FFFFFF">名称</td>
<!--        <td width="150" align="center" bgcolor="#FFFFFF">房间</td>-->
        <td width="150" align="center" bgcolor="#FFFFFF">图像</td>
<!--        <td width="150" align="center" bgcolor="#FFFFFF">简介</td>-->
        <td width="100" align="center" bgcolor="#FFFFFF">操作</td>
      </tr>
       </thead>
<?php
$query=$db->query("select * from {$tablepre}anchor");
while($row=$db->fetch_row($query)){
?>      
    <tr>
         <td bgcolor="#FFFFFF" align="center"><?=$row[id]?>&nbsp; </td>
         <td align="center" bgcolor="#FFFFFF"><?=$row[uid]?>&nbsp; </td>
         <td align="center" bgcolor="#FFFFFF"><?=$row[name]?>&nbsp; </td>
<!--         <td align="center" bgcolor="#FFFFFF">--><?//=$row[uid]?><!--&nbsp; </td>-->
         <td align="center" bgcolor="#FFFFFF"><img width="50" height="50" src="<?=$row[photo]?>" /></td>
<!--         <td align="center" bgcolor="#FFFFFF">--><?php //echo mb_strimwidth(html_entity_decode($row[introduction]),0,60); ?><!--...</td>-->
         <td bgcolor="#FFFFFF" align="center">
         <!--<button class="button button-mini button-warning" onClick="openRule('<?=$row[id]?>','<?=$row[type]?>')"><i class="x-icon x-icon-small icon-check icon-white"></i>权限</button>-->

        <button class="button button-mini button-info"  onClick="anchor_edit_bt('<?=$row[id]?>')"><i class="x-icon x-icon-small icon-wrench icon-white"></i>修改</button>
<?php if($row[id]!=1){?><button class="button button-mini button-danger" onclick="if(confirm('确认要删除此主播吗，是否继续？'))location.href='?act=anchor_del&id=<?=$row[id]?>'"><i class="x-icon x-icon-small icon-trash icon-white"></i>删除</button><?php }?></td>
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

  function anchor_edit_bt(id){
              //alert(id);
              window.location.href="anchor_edit.php?rid="+id;
      return;
  }
  
 BUI.use('bui/overlay',function(Overlay){
            dialog2 = new Overlay.Dialog({
            title:'添加主播',
            width:890,
            height:720,
            buttons:[],
            bodyContent:''
          });
});    
  
 //添加主播
 function addHouse(){
	dialog2.set('bodyContent','<iframe src="anchor_add.php" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
	dialog2.updateContent();
	dialog2.show();
} 
  </script>
</body>
</html>
