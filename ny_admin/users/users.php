<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'users_admin')===false)exit("没有权限！");
$auth_rule_userdel=false;
if(stripos(auth_group($_SESSION['login_gid']),'users_del')!==false)$auth_rule_userdel=true;

switch($act){
	case "user_del":
		if(stripos(auth_group($_SESSION['login_gid']),'users_del')===false)exit("没有权限！");
		  if(is_array($id)){
                     foreach ($id as $value) {
                      $username=userinfo($value,'{username}');
                    $db->query("update {$tablepre}members set fuser='' where fuser='$username'");
                    $db->query("update {$tablepre}guest set fuser='' where fuser='$username'");
                      }
                  user_del(implode(',',$id));  
                }
		else{
                    $username=userinfo($id,'{username}');
                    $db->query("update {$tablepre}members set fuser='' where fuser='$username'");
                    $db->query("update {$tablepre}guest set fuser='' where fuser='$username'");
                       user_del($id); 
                }
		header("location:?gid=".$gid);
	break;
}

$query=$db->query("select id,title from {$tablepre}auth_group order by id desc");
while($row=$db->fetch_row($query)){
	$group.='<option value="'.$row[id].'">'.$row[title].'</option>';
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
	return new Date(time*1000).Format("yyyy-MM-dd hh:mm"); ; 
}
function ftime2(time){
	if(time>(60*60*24)) return parseInt(time/(60*60*24))+"天";
	else if(time>(60*60))return parseInt(time/(60*60))+"小时";
	else if(time>60)return parseInt(time/60)+"分钟";
	else return parseInt(time)+"秒";
}
function sgid(id){
	var arr=new Array();
	if(isNaN(id)) return '';
	<?php
	$query=$db->query("select * from {$tablepre}auth_group order by id desc");
while($row=$db->fetch_row($query)){
	echo "arr['{$row[id]}']='$row[title]';";
	}
	?>
	return arr[id];
}
</script>
</head>
<body>
<div class="container"  style=" min-width:1300px;">
<form  class="form-horizontal" action="" method="get"> 
  <ul class="breadcrumb">
      <button type="button" class="button button-success" id="add_group_bt" style="float: right" onclick="addUser()">添加用户</button>
      
    <li class="active">关键字：
      <input type="text" name="skey" id="rolename"class="abc input-default" placeholder="">
      用户组：
      <select name="sgid" id="sgid" >
			<option value="-1" selected = "selected">全部</option>
            <?=$group?>
            </select>
      <input type="hidden" name="fuser" value="<?=$fuser?>">
      &nbsp;&nbsp;
      <button type="submit"  class="button ">查询</button>
       <button type="button" class="button button-success"  onclick="doexcel()">导出Excel</button>
      <button type="button"  class="button  button-danger"  onClick="if(confirm('确定删除？'))$('#hd_list').submit()">删除所选</button>
    &nbsp;&nbsp; 用户名、昵称、推广人为查询字段</li>
   
  </ul>
  </form>
    <form action="" method="POST" enctype="application/x-www-form-urlencoded"  class="form-horizontal" id="hd_list">
	<input type="hidden" name="gid" value="<?=$gid?>">
	<input type="hidden" name="act" value="user_del">
  <table  class="table table-bordered table-hover definewidth m10">
    <thead>
      <tr style="font-weight:bold" >
        <td width="40" align="center" bgcolor="#FFFFFF">用户ID</td>
        <td width="70" align="center" bgcolor="#FFFFFF"><input type="checkbox" onClick="$('.ids').attr('checked',this.checked); "></td>
        <td width="100" align="center" bgcolor="#FFFFFF">用户名</td>
        <td  width="80" align="center" bgcolor="#FFFFFF">昵称</td>
        <td  width="50" align="center" bgcolor="#FFFFFF">红包余额</td>
        <td width="80" align="center" bgcolor="#FFFFFF">手机</td>
		<td width="80" align="center" bgcolor="#FFFFFF">QQ</td>
        <td width="60" align="center" bgcolor="#FFFFFF">用户组</td>
        <td width="60" align="center" bgcolor="#FFFFFF">用户归属</td>
        <td width="60" align="center" bgcolor="#FFFFFF">推广人</td>
        <td width="50" align="center" bgcolor="#FFFFFF">备注</td>
        <td width="100" align="center" bgcolor="#FFFFFF">注册时间</td>
        <td width="100" align="center" bgcolor="#FFFFFF">最近登录</td>
        <td width="30" align="center" bgcolor="#FFFFFF">审核</td>
        <td width="120" align="center" bgcolor="#FFFFFF">操作</td>
      </tr>
    </thead>
<?php
$sql="select m.*,ms.*  from {$tablepre}members m,{$tablepre}memberfields ms  where m.uid=ms.uid and m.uid!=0";
$skey=trim($skey);
if($skey!=""){
	$sql.=" and (m.username like '%$skey%' or m.fuser like '%$skey%' or m.tuser like '%$skey%' or ms.nickname like 	'%$skey%' or m.phone like '%$skey%')";
}

if($sgid!="" && $sgid!="-1" ){
	$sql.=" and m.gid='$sgid'";
}
if($fuser!=""){
	$sql.=" and(m.fuser='$fuser' or m.tuser='$fuser')";
}

$sql.=" order by m.uid desc";
if(!$auth_rule_userdel)$display_delbutton='style="display:none"';
echo userlist(20,$sql,'
    <tr>
      <td bgcolor="#FFFFFF" align="center">{uid}</td>
	  <td align="center" bgcolor="#FFFFFF"><input type="checkbox" class="ids" name="id[]" value="{uid}"><a title="充值" class="icon-cancel-chong" onclick="chongmoney({uid})"></a><a title="提现" class="icon-cancel-tixian" onclick="tixianmoney({uid})"></a></td>
      <td align="center" bgcolor="#FFFFFF">{username}</td>
      <td align="center" bgcolor="#FFFFFF">{nickname}</td>
      <td align="center" bgcolor="#FFFFFF">{moneyred}</td>
	  <td align="center" bgcolor="#FFFFFF">{phone}&nbsp;</td>
	  <td align="center" bgcolor="#FFFFFF">{realname}&nbsp;</td>
      <td align="center" bgcolor="#FFFFFF"><script>document.write(sgid({gid})); </script>&nbsp;</td>
      <td align="center" bgcolor="#FFFFFF">{fuser}&nbsp;</td>
      <td align="center" bgcolor="#FFFFFF">{tuser}&nbsp;</td>
      <td align="center" bgcolor="#FFFFFF">{sn}</td>
      <td align="center" bgcolor="#FFFFFF"><script>document.write(ftime({regdate})); </script></td>
      <td align="center" bgcolor="#FFFFFF" title="登录IP:{regip}" onClick="alert(\'登录IP:{regip}\')"><script>document.write(ftime({lastactivity})); </script></td>
	  <td align="center" bgcolor="#FFFFFF">{state}</td>
      <td align="center" bgcolor="#FFFFFF">
      <button type="button" class="button button-mini button-info" onClick="openUser({uid},{gid})" ><i class="x-icon x-icon-small icon-wrench icon-white"></i>修改</button>
      <button type="button" class="button button-mini button-danger" onclick="if(confirm(\'确定删除用户？\'))location.href=\'?act=user_del&id={uid}\'" '.$display_delbutton.'><i class="x-icon x-icon-small icon-trash icon-white"></i>删除</button></td>
    </tr>
')?>


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
            title:'用户设置',
            width:630,
            height:600,
            buttons:[],
            bodyContent:''
          });
});
function openUser(id,type){
	dialog.set('bodyContent','<iframe src="user_edit.php?id='+id+'&gid='+type+'" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
	dialog.updateContent();
	dialog.show();
}
BUI.use('bui/overlay',function(Overlay){
            dialog2 = new Overlay.Dialog({
            title:'添加用户',
            width:630,
            height:450,
            buttons:[],
            bodyContent:''
          });
});
function addUser(){
	dialog2.set('bodyContent','<iframe src="user_add.php" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
	dialog2.updateContent();
	dialog2.show();
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
	dialog3.set('bodyContent','<iframe src="doexcel_choice.php?gid=<?=$sgid?>" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
	dialog3.updateContent();
	dialog3.show();
}
BUI.use('bui/overlay',function(Overlay){
            dialog4 = new Overlay.Dialog({
            title:'充值',
            width:350,
            height:300,
            buttons:[],
            bodyContent:''
          });
});
function chongmoney(uid){
    
    dialog4.set('bodyContent','<iframe src="chongmoney.php?uid='+uid+'" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
	dialog4.updateContent();
	dialog4.show();
}
BUI.use('bui/overlay',function(Overlay){
            dialog5 = new Overlay.Dialog({
            title:'提现',
            width:350,
            height:300,
            buttons:[],
            bodyContent:''
          });
});
function tixianmoney(uid){
    
    dialog5.set('bodyContent','<iframe src="tixianmoney.php?uid='+uid+'" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
	dialog5.updateContent();
	dialog5.show();
}
  </script>
</body>
</html>
