<?php
require_once '../include/common.inc.php';
function app_jyts_list($num,$key,$tpl){
	global $db,$tablepre,$firstcount,$displaypg;
	$sql="select * from {$tablepre}apps_jyts";
	if($key!="")$sql.=" where title like '%$key%' or txt like '%$key%' or `user` like '%$key%'";
	
	$count=$db->num_rows($db->query($sql));
	pageft($count,$num,"");
	$sql.=" order by id desc";
	$sql.=" limit $firstcount,$displaypg";
	$query=$db->query($sql);
	return for_each($query,$tpl);	

}

switch($act){
	case "jyts_add":
		$user=$_SESSION['login_user'];
		$db->query("insert into {$tablepre}apps_jyts(title,txt,`user`,atime)values('$title','$txt','$user','".gdate()."')");
		$str="<font style='border-bottom:1px solid #999; color:red;font-size:14px;'>[发布交易提示]</font><br>{$title} …… [<font style='color:red;  cursor:pointer' onClick='$(\\\"#app_3\\\").trigger(\\\"click\\\")'>详细</font>]";
		exit('<script>top.app_sendmsg("'.$str.'");location.href="?"</script>');
	break;
}
$sql="select * from {$tablepre}apps_jyts";
if($id!="")$sql.=" where id='$id'";
else $sql.=" limit 1";
$row=$db->fetch_row($db->query($sql));
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>在线答疑</title>
<link href="css/apps.css" rel="stylesheet" type="text/css" />
<style type="text/css">
a {     color: #4f6b72;}
</style>
</head>

<body>
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
	return new Date(time*1000).Format("yyyy-MM-dd hh:mm");
}
</script>

<?php

if(check_auth('jyts_add')){
?>
<form action="?act=jyts_add" method="post" enctype="application/x-www-form-urlencoded" onsubmit="return docheck();">
<table width="100%" cellspacing="0" id="jyts_add" class="our"  style=" margin-bottom:5px; display:none ">
          <tr>
            <td class="tableleft" style="width:80px;">标题：</td>
            <td><input name="title" type="text" id="title" style="width:98%" value=""></td>
          </tr>
          <tr>
            <td width="30" class="tableleft" style="width:80px;">问题：</td>
            <td><textarea name="txt" id="txt" style="width:100%" class="xheditor {cleanPaste:0,height:'300',internalScript:true,inlineScript:true,linkTag:true,upLinkUrl:'../upload/upload.php',upImgUrl:'../upload/upload.php',upFlashUrl:'../upload/upload.php',upMediaUrl:'../upload/upload.php'}"></textarea></td>
      </tr>
          <tr>
            <td class="tableleft">&nbsp;</td>
            <td><input type="submit" name="button" id="button" class="btn2" value="发布"></td>
          </tr>
    </table>
</form>
<div style="margin:5px 0px;"><button class="btn1" onClick="document.getElementById('jyts_add').style.display=''">发布交易提示</button></div>
<?php
}
if(check_auth('jyts_view')){
?>
<div  style=" padding:20px; margin-bottom:10px;border:1px solid #CCC; <?php if($act!='jyts_view')echo 'display:none';?>">
          <div style="font-size:20px; line-height:25px; text-align:center"><strong><?=$row['title']?></strong></div>
		  <div style="text-align:center;color: #a2a2a2;"><?=date('Y-m-d H:i:s', $row['atime'])?> <?=$row['user']?></div>
          <div><?=tohtml($row['txt'])?></div>
</div>
    
<table width="100%" cellspacing="0" id="mytable">

      <tr  >
        <th width="30" align="center" bgcolor="#FFFFFF"  style="border-left: 1px solid #CCC;">编号</th>
        <th  align="left" bgcolor="#FFFFFF">标题</th>
        <th width="100"  align="left" bgcolor="#FFFFFF">发布时间</th>
        <th width="100"  align="left" bgcolor="#FFFFFF">发布人</th>
  </tr>
      

<?php
echo app_jyts_list(20,$key,'
    <tr>
    <td align="center" bgcolor="#FFFFFF"  style="border-left: 1px solid #C1DAD7;">{id}</td>
      <td align="left" bgcolor="#FFFFFF"><a href="?id={id}&act=jyts_view">{title}</a></td>
	  <td bgcolor="#FFFFFF"> <script>document.write(ftime({atime})); </script></td>
      <td bgcolor="#FFFFFF">{user}</td>
    </tr>
')?>



</table>
<div style="height:30px; line-height:30px;"><?=$pagenav?></div>
<?php
}else{ ?>
	<div class="robot-Tips">您暂时没有权限查看交易提示，请联系客服!</div>
<?php }
?>
<script type="text/javascript" src="../xheditor/jquery/jquery-1.4.4.min.js"></script> 
<script type="text/javascript" src="../xheditor/xheditor.js"></script>
<script type="text/javascript" src="../xheditor/xheditor_lang/zh-cn.js"></script>
<script>
 function docheck() {
        
      var title =$.trim($("#title").val());
      var txt =$.trim($("#txt").val());
       
        if(title==''){
            
           alert('标题不能为空！');
            return false;
            
        }
        if(txt==''){
            
           alert('内容不能为空！');
            return false;
            
        }
        return true;
    }
   

</script>
</body>
</html>

