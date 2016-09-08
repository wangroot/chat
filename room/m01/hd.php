<?php
require_once '../../include/common.inc.php';
function app_hd_add($ktime,$ptime,$sp,$kcj,$lx,$cw,$zsj,$zyj,$username,$pcj,$sn){
	global $db,$tablepre;
	$time=gdate();
	$ktime=strtotime($ktime);
	$ptime=strtotime($ptime);
	$username=$_SESSION['login_user'];
	$db->query("insert into {$tablepre}apps_hd(ktime,sp,kcj,lx,cw,zsj,zyj,username,sn,ttime)values('$ktime','$sp','$kcj','$lx','$cw','$zsj','$zyj','$username','$sn','$time')");
}
function app_hd_list($num,$key,$tpl){
	global $db,$tablepre,$firstcount,$displaypg;
	$sql="select * from {$tablepre}apps_hd";
	if($key!="")$sql.=" where uname like '%$key%'";
	
	$count=$db->num_rows($db->query($sql));
	pageft($count,$num,"");
	$sql.=" order by id desc";
	$sql.=" limit $firstcount,$displaypg";
	$query=$db->query($sql);
	while($row=$db->fetch_row($query)){
		$t=$tpl;
		if($row['username']==$_SESSION['login_user']&&$row['pcj']==""){
			$t=str_replace('{pcj}',"<a href=\"javascript:bt_hd_pc('{$row[id]}','{$row[lx]}','{$row[sp]}')\">平仓</a>",$t);
		}
		if($row['username']==$_SESSION['login_user']){
			$t=str_replace('{username}',"{username} <a href=\"javascript:bt_hd_del('{$row[id]}','{$row[lx]}','{$row[sp]}')\">删</a>",$t);
		}
		if(strpos($row[lx],'买')&&$row['pcj']!=""){
			$t=str_replace('{yld}',round($row['pcj']-$row['kcj'],2),$t);
		}
		else if(strpos($row[lx],'卖')&&$row['pcj']!=""){
			$t=str_replace('{yld}',round($row['kcj']-$row['pcj'],2),$t);
		}else{
			$t=str_replace('{yld}','',$t);
		}
		foreach($row as $k=>$value){
			$t=str_replace('{'.$k.'}',$value,$t);	
		}
		$str.=$t;
		
	}
	return $str;	
}
switch($act){
	case "z":
		if($_SESSION['z'.$id]==""&&$_COOKIE['z'.$id]==""){
			$db->query("update {$tablepre}apps_hd set z=z+1 where id='$id' ");
			$_SESSION['z'.$id]=1;
			setcookie('z'.$id, '1', gdate()+315360000);
		}
	break;
	case "hd_del":
		$db->query("delete from {$tablepre}apps_hd where username='$_SESSION[login_user]' and id='$id'");
	break;
	case "app_hd_pc":
		$db->query("update {$tablepre}apps_hd set pcj='{$pc_pcj}',ptime='".gdate()."' where id='{$pc_id}'");
		$str="<font style='border-bottom:1px solid #999; color:red;font-size:14px;'>[喊单提醒]</font><br>单号：$pc_id,$pc_lx,$pc_sp 平仓 [<font style='color:red;  cursor:pointer' onClick='$(\\\"#app_1\\\").trigger(\\\"click\\\")'>详细</font>]";
		exit('<script>top.app_sendmsg("'.$str.'");location.href="?"</script>');
	break;
	case "app_hd_add": 	
		app_hd_add($ktime,$ptime,$sp,$kcj,$lx,$cw,$zsj,$zyj,$username,$pcj,$sn);
		$id=$db->insert_id();
		$str="<font style='border-bottom:1px solid #999; color:red;font-size:14px;'>[喊单提醒]</font><br>单号：$id,$lx,$sp …… [<font style='color:red;  cursor:pointer' onClick='$(\\\"#app_1\\\").trigger(\\\"click\\\")'>详细</font>]";
		exit('<script>top.app_sendmsg("'.$str.'");location.href="?"</script>');
	break;	
}
?>
<!DOCTYPE html>

<html class="page-fin-news"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>操作建议</title>
 <link rel="stylesheet" href="http://page.yy.com/finance/assets/css/liveFinance.css">
  <style type="text/css">
/* CSS Document */


body{background-color: #c2ddf3;color: #fff;font-size: 17px;font-family: Tahoma,Arial,Roboto,”Droid Sans”,”Helvetica Neue”,”Droid Sans Fallback”,”Heiti SC”,”Hiragino Sans GB”,Simsun,sans-self;}
.hdul{margin-bottom: 20px;padding: 10px;background: rgba(50, 50, 50, 0.4) no-repeat ; width: 250px;border-radius: 5px;}
.hdul span{margin-right: 20px;}
.hdul a{color: red;}
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
	if(!time)return "";
	return new Date(time*1000).Format("yyyy-MM-dd hh:mm"); ; 
}
</script>
<div class="fg-notice-wrap">
    <div class="fg-notice-box">
<?php

if(check_auth('hd_view')){
?>

      
<?php
echo app_hd_list(20,$key,'

    <ul class="hdul">
    <li  bgcolor="#FFFFFF"  ><span>单号:</span>{id}</li>
      <li " bgcolor="#FFFFFF"><span>喊单时间:</span><script>document.write(ftime({ktime})); </script></li>
      <li bgcolor="#FFFFFF"><span>类型:</span>{lx}</li>
      <li  bgcolor="#FFFFFF"><span>仓位:</span>{cw}%&nbsp;</li>
      <li  bgcolor="#FFFFFF"><span>商品:</span>{sp}&nbsp;</li>
      <li  bgcolor="#FFFFFF"><span>开仓价:</span>{kcj}</li>
      <libgcolor="#FFFFFF"><span>止损价:</span>{zsj}&nbsp;</li>
      <li bgcolor="#FFFFFF"><span>止盈价:</span>{zyj}&nbsp;</li>
      <li bgcolor="#FFFFFF"><span>平仓时间:</span><script>document.write(ftime({ptime})); </script></li>
      <li bgcolor="#FFFFFF"><span>平仓价:</span>{pcj}&nbsp;</li>
      <li  bgcolor="#FFFFFF"><span>盈利点数:</span>{yld}&nbsp;</li>
      <li bgcolor="#FFFFFF">{z}<span>跟单:</span> <a href="?id={id}&act=z">跟单</a></li>
      <li  bgcolor="#FFFFFF"><span>分析师:</span>{username}</li>
      
    </ul>
 ')?>   



<div style="height:30px; line-height:30px;color: #000;font-size: 13px;"><?=$pagenav?></div>
<?php
}else{
	echo "<div style=\" font-size:20px;color: red;text-align: center;padding-top: 30px;\">没有权限查看喊单数据！请联系客服！</div>";
}
?>
    </div>
</div>
</body>
</html>

