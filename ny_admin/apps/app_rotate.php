<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'apps_rotate')===false)exit("没有权限！");
function rotate_del($ids){
    global $db,$tablepre;
       if($ids=="")return;
	$db->query("delete from {$tablepre}rotate where id  in ($ids)");
}
switch($act){
	case "rotate_del":
	   if(is_array($id)){
                  rotate_del(implode(',',$id));  
                }
		else{
                    
                    rotate_del($id); 
                }
		header("location:?");
	break;
        case "paijiang":
	   global $db,$tablepre;
            $db->query("update {$tablepre}rotate set status=1 where id='$jid'");
            exit('1');
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
<div class="container"  style="width:800px;">
    <div class="guide_title"><img src="../assets/img/rebot.png" width="25px" height="25px"/>&nbsp;抽奖统计</div>
<form  class="form-horizontal" action="" method="get"> 
  <ul class="breadcrumb">
   <li class="active">关键字：
      <input type="text" name="skey" id="rolename"class="abc input-default" placeholder="手机号或者奖品名称">
     

      &nbsp;&nbsp;
      <button type="submit"  class="button ">查询</button>
  
      <button type="button"  class="button  button-danger"  onClick="if(confirm('确定删除？'))$('#hd_list').submit()">删除所选</button>
 </li>
   
  </ul>
  </form>
   <form action="" method="POST" enctype="application/x-www-form-urlencoded"  class="form-horizontal" id="hd_list">
	<input type="hidden" name="act" value="rotate_del">
  <table  class="table table-bordered table-hover definewidth m10">
    <thead>
      <tr style="font-weight:bold" >
        <td width="20" align="center" bgcolor="#FFFFFF">ID</td>
        <td width="19" align="center" bgcolor="#FFFFFF"><input type="checkbox" onClick="$('.ids').attr('checked',this.checked); "></td>
        <td width="80" align="center" bgcolor="#FFFFFF">手机号</td>
        <td  width="100" align="center" bgcolor="#FFFFFF">奖品</td>
        <td width="120" align="center" bgcolor="#FFFFFF">抽奖时间</td>
	<td width="80" align="center" bgcolor="#FFFFFF">状态</td>
        <td width="120" align="center" bgcolor="#FFFFFF">操作</td>
      </tr>
    </thead>
<?php

$sql="select *  from {$tablepre}rotate where jiangpin!=''  ";
$skey=trim($skey);
if($skey!=""){
	$sql.=" and (phone like '%$skey%' or jiangpin like '%$skey%')";
}


$sql.=" order by addtime desc";


global $firstcount,$displaypg;
           $num=20;
	$count=$db->num_rows($db->query($sql));
	pageft($count,$num,"");
	$sql.=" limit $firstcount,$displaypg";
	$query=$db->query($sql);
        while($row=$db->fetch_row($query)){
     
       ?>
    <tr>
      <td bgcolor="#FFFFFF" align="center"><?=$row['id']?></td>
	  <td align="center" bgcolor="#FFFFFF"><input type="checkbox" class="ids" name="id[]" value="<?=$row['id']?>"></td>
 <td align="center" bgcolor="#FFFFFF"><?=$row['phone']?>&nbsp;</td>
	  <td align="center" bgcolor="#FFFFFF"><?=$row['jiangpin']?>&nbsp;</td>
            <td align="center" bgcolor="#FFFFFF"><?=date('Y-m-d H:i:s',$row['addtime'])?>&nbsp;</td>
     <td align="center" bgcolor="#FFFFFF" jid="<?=$row['id']?>"><? if($row['status']==1){echo '<font color="#090">已派奖</font>';}else{ echo '<font color="red">等待派奖</font>';}?></td>
      <td align="center" bgcolor="#FFFFFF">
     <?if($row['status']==0){?> <div type="button" class="button button-mini button-info paijiang"  jpid="<?=$row['id']?>" ><i class="x-icon x-icon-small icon-wrench icon-white"></i>派奖</div> <?}?>
      <button type="button" class="button button-mini button-danger" onclick="if(confirm('确定删除该条信息？'))location.href='?act=rotate_del&id=<?=$row['id']?>'"><i class="x-icon x-icon-small icon-trash icon-white"></i>删除</button></td>
    </tr>

<? }?>

  </table>
    </form> 
    <ul class="breadcrumb">
    <li class="active"><?=$pagenav?>
    </li>
  </ul>
</div>
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
$(".paijiang").live("click", function () { 
    if(confirm('确定派送奖品？')){
    var id = $(this).attr("jpid");
$.ajax({
                url: "?act=paijiang",
                data: {jid:id},
                 type: "POST",
                dataType: "JSON",
                success: function (rep) {
                    if (rep==1){
               
                   $('td[jid="' + id +'"]').html('<font color="#090">已派奖</font>');
                   $('div[jpid="' + id +'"]').hide();
                    }
                       
                }
            });
    }
});

  </script>
</body>
</html>
