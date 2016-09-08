<?php
require_once './include/common.inc.php';
$uid=$_SESSION['login_uid'];
switch($mod){
	case "vote":
		$db->query("insert into {$tablepre}room_vote(rid,uid,v,time)values('$rid','$uid','$vt','".gdate()."')");
		$_SESSION['vote'.$rid]='1';
	break;
	}
	$v1=for_each($db->query("select count(*) as v1 from {$tablepre}apps_vote where v='1' and rid='$rid'"),'{v1}');
	$v2=for_each($db->query("select count(*) as v2 from {$tablepre}apps_vote where v='2' and rid='$rid'"),'{v2}');
	$v3=for_each($db->query("select count(*) as v3 from {$tablepre}apps_vote where v='3' and rid='$rid'"),'{v3}');
	$vs=$v1+$v2+$v3;
	$v1=@round($v1/$vs,2)*100;
	$v2=@round($v2/$vs,2)*100;
	$v3=@round($v3/$vs,2)*100;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<meta http-equiv="refresh" content="30">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body, div, ul, li { margin:0px;}
body { font-size:12px;color:#2C4B5F; font-family:Arial, Helvetica, sans-serif; text-align:center; }
a { color:#999; font:12px;}
ul { list-style:none; }
.main { clear:both; padding:8px; text-align:center; } /*第一种形式*/
#tabs1 { text-align:left; }
.menu1box { position:relative; overflow:hidden; height:22px; text-align:left;}
#menu1 { position:absolute; top:0; left:0; z-index:1; padding:0px; padding-left:10px;  }
#menu1 li { float:left; display:block; cursor:pointer; width:60px; text-align:center; line-height:21px; height:21px; }
#menu1 li.hover { background:#fff; border-left:1px solid #333; border-top:1px solid #333; border-right:1px solid #333; }
.main1box { clear:both; margin-top:-1px; border-top:1px solid #333; height:390px;}
#main1 ul { display: none; padding:0px; }
#main1 ul.block { display: block; }
</style>
<div id="tabs1">
  
  <div class="main1box">
    <div class="main" id="main1">
      <ul class="block"  style="text-align:center">
        <li>
        <form action="?mod=vote&rid=<?=$rid?>" method="post" enctype="multipart/form-data" name="vf" id="vf" >
        <input type="hidden" name="vt" id="vt" value="0" />
        <a style="margin:5px 2px; border:1px solid #F63; background: #D74848; color:#FFF; width:52px; height:35px; display:block; padding-top:8px;line-height:16px;float:left;   cursor:pointer" onclick="vote(1)">看多<br><?=$v1?>%</a>
        <a style="margin:5px 2px; border:1px solid  #CFCFCF; background: #999; color:#FFF; width:52px; height:35px; display:block; padding-top:8px;line-height:16px;float:left;   cursor:pointer" onclick="vote(2)">盘整<br><?=$v2?>%</a>
        <a style="margin:5px 2px; border:1px solid  #090; background: #4FB554; color:#FFF; width:52px; height:35px; display: block; padding-top:8px; line-height:16px; float:left;   cursor:pointer" onclick="vote(3)">看空<br><?=$v3?>%</a>
        <div style=" clear:both"></div>
        </form>
        </li>
      </ul>
      
    </div>
  </div>
</div>
<script>
function vote(t){
	if('<?=$_SESSION['vote'.$rid]?>'=='1'){alert('您已经投过票了,不能重复投票！');return false;}
	document.getElementById('vt').value=t;
	document.getElementById('vf').submit();
}
function setTab(m,n){
var tli=document.getElementById("menu"+m).getElementsByTagName("li"); /*获取选项卡的LI对象*/
var mli=document.getElementById("main"+m).getElementsByTagName("ul"); /*获取主显示区域对象*/
for(i=0;i<tli.length;i++){
     tli[i].className=i==n?"hover":""; /*更改选项卡的LI对象的样式，如果是选定的项则使用.hover样式*/
     mli[i].style.display=i==n?"block":"none"; /*确定主区域显示哪一个对象*/
}
}
</script>
