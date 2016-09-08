<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'users_admin')===false && stripos(auth_group($_SESSION['login_gid']),'users_my')===false)exit("没有权限！");
if($act=="user_edit"){
        $onlinestart=$sminute*60+$shour*60*60;
        $onlineend=59+$xminute*60+$xhour*60*60;
	user_edit($id,$realname,$password,$phone,$gid,$fuser,$tuser,$sn,$state,$nickname,$redbags,$onlinestart,$onlineend);
        echo "<script>window.parent.dialog.close();alert('用户信息修改成功！');window.parent.location.reload();</script>";
}
$query=$db->query("select * from {$tablepre}auth_group order by id desc");
while($row=$db->fetch_row($query)){
    if($row['id']==$gid){$selected='selected';}else{$selected='';}
	$group.='<option value="'.$row['id'].'"'.$selected.'>'.$row['title'].'</option>';
}
if(stripos(auth_group($_SESSION['login_gid']),'users_group')===false){
	$group='';
}

$query=$db->query("select m.*,ms.*  from {$tablepre}members m,{$tablepre}memberfields ms where m.uid=ms.uid and m.uid='{$id}'");
 $userinfo=$db->fetch_row($query);
 
 //系统开启时间转为为时分
 if($userinfo['onlinestart']>60*60){
     $shour=floor($userinfo['onlinestart']/(60*60));
 }
 $yu=$userinfo['onlinestart']-$shour*60*60;
  if($yu>=60){
     $sminute=floor($yu/60);
 }


    
 //关闭时间转化为时分
     if($userinfo['onlineend']>60*60){
     $xhour=floor($userinfo['onlineend']/(60*60));
 }
 $yu=$userinfo['onlineend']-$xhour*60*60;
  if($yu>=60){
     $xminute=floor($yu/60);
 }

  
 for($i=0;$i<24;$i++){
     if($i==$shour){$selected='selected';}else{$selected='';}
   $shour_list.='<option value="'.$i.'"'.$selected.'>'.$i.'</option>'; 
   if($i==$xhour){$selected='selected';}else{$selected='';}
   $xhour_list.='<option value="'.$i.'"'.$selected.'>'.$i.'</option>'; 
    }
 for($i=0;$i<60;$i++){
     if($i==$sminute){$selected='selected';}else{$selected='';}
   $sminute_list.='<option value="'.$i.'"'.$selected.'>'.$i.'</option>'; 
    if($i==$xminute){$selected='selected';}else{$selected='';}
   $xminute_list.='<option value="'.$i.'"'.$selected.'>'.$i.'</option>'; 
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
input,select{vertical-align:middle;}
.liw { width:160px; height:25px; line-height:25px;}
</style>
</head>
<body>
<div class="container" style="margin-bottom:50px;">
<form action="?id=<?=$userinfo['uid']?>" method="post" enctype="application/x-www-form-urlencoded">
  <ul class="breadcrumb">
  <div style="border-bottom:1px dashed #CCCCCC; padding:5px; margin-bottom:5px;"><strong>[<?=$userinfo['username']?>] <?=$userinfo['nickname']?></strong></div>
<table class="table table-bordered table-hover definewidth m10">
			<tr>
            <td width="80" class="tableleft">审核状态：</td>
            <td><select name="state" id="state" >

              <option value="1" <? if($userinfo['state']==1) echo 'selected'; ?>>已审核</option>
			  <option value="0" <? if($userinfo['state']==0) echo 'selected'; ?>>未审核</option>
            </select>&nbsp;</td>
          </tr>
		  <tr>
            <td width="80" class="tableleft" style="width:70px;">昵称：</td>
            <td><input name="nickname" type="text" id="nickname" style="width:350px;" value="<?=$userinfo['nickname']?>"/></td>
          </tr>
          <tr>
            <td width="80" class="tableleft" style="width:70px;">QQ号码：</td>
            <td><input name="realname" type="text" id="realname" style="width:350px;" value="<?=$userinfo['realname']?>"/></td>
          </tr>
          <?php if($userinfo['gid']==3){?>
           <tr>
            <td width="80" class="tableleft" style="width:70px;">在线时间：</td>
            <td><label id="opentime">&nbsp;&nbsp;
                 <select name="shour" id="shour" style="width:70px;" ><?=$shour_list?></select>&nbsp;<label class="control-label">时</label>
                 <select name="sminute" id="sminute" style="width:70px;" ><?=$sminute_list?></select>&nbsp;<label class="control-label">分</label>
                &nbsp;<span style="color:#F90">至</span>&nbsp;
                    <select name="xhour" id="xhour" style="width:70px;" ><?=$xhour_list?></select>&nbsp;<label class="control-label">时</label>
                 <select name="xminute" id="xminute" style="width:70px;" ><?=$xminute_list?></select>&nbsp;<label class="control-label">分</label>
                  </label></td>
          </tr>
          <?}?>
          <tr>
            <td width="80" class="tableleft">用户密码：</td>
            <td><input name="password" type="text" id="password" /> 为空不修改密码</td>
          </tr>
          <tr>
            <td width="80" class="tableleft">手机号码：</td>
            <td><input name="phone" type="text" id="phone" style="width:350px;" value="<?=$userinfo['phone']?>"/></td>
          </tr>
          
          <tr <?php if($userinfo['uid']==1){echo 'style="display:none;"';}?>>
            <td width="80" class="tableleft">用 户 组：</td>
            <td><select name="gid" id="gid" >
            <?=$group?>
            </select>&nbsp;</td>
          </tr>
           <tr>
            <td width="80" class="tableleft">红包数：</td>
            <td><input name="redbags" type="text" id="redbags" value="<?=$userinfo['redbags']?>"/></td>
          </tr>
          <tr>
            <td width="80" class="tableleft">用户归属：</td>
            <td><input name="fuser" type="text" id="fuser" style="width:350px;" value="<?=$userinfo['fuser']?>"/></td>
          </tr>
          <tr>
            <td width="80" class="tableleft">推广用户：</td>
            <td><input name="tuser" type="text" id="tuser" style="width:350px;" value="<?=$userinfo['tuser']?>"/></td>
          </tr>
          <tr>
            <td width="80" class="tableleft">其他备注：</td>
            <td><textarea name="sn" id="sn" style="width:350px;"><?=$userinfo['sn']?></textarea></td>
          </tr>
        </table>

  </ul>
  <div style="position:fixed; bottom:0; background: #FFF; width:100%; padding-top:5px;">
    <button type="submit"  class="button button-success">确定</button>
    <button type="button"  class="button" onclick="window.parent.dialog.close()">关闭</button>
    <input type="hidden" name="act" value="user_edit">
</div>
  </form>

</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui.js"></script> 
<script type="text/javascript" src="../assets/js/config.js"></script> 
<script type="text/javascript" src="../../upload/swfupload/swfupload.js"></script> 
<script>

</script>
</body>
</html>
