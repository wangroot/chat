<?php
require_once '../../include/common.inc.php';
if($cfg['config']['loginguest']=='0' && !isset($_SESSION['login_uid']) )header('location:../logging.php');
require_once PPCHAT_ROOT.'./include/json.php';
$json=new JSON_obj;
//房间状态
if($cfg['config']['state']=='2' and $_SESSION['room_'.$cfg['config']['id']]!=true){header("location:../login.php?1");exit();}
if($cfg['config']['state']=='0'){exit("<script>location.href='../error.php?msg=系统处于关闭状态！请稍候……'</script>");exit();}
//游客登录
if((!isset($_SESSION['login_uid']) || $_SESSION['login_uid']=='0')  &&  $cfg['config']['loginguest']=="1"){gusetLogin();}

//是否登录
if(!isset($_SESSION['login_uid'])){header("location:../../logging.php");exit();}

//用户信息
$uid=$_SESSION['login_uid'];
$db->query("update {$tablepre}members set regip='$onlineip' where uid='{$uid}'");
$userinfo=$db->fetch_row($db->query("select m.*,ms.* from {$tablepre}members m,{$tablepre}memberfields ms  where m.uid=ms.uid and m.uid='{$uid}'")); 
$_SESSION['login_gid']=$userinfo['gid'];
//游客
if($_SESSION['login_uid']==0){$userinfo['username']=$userinfo['nickname']=$_SESSION['login_nick'];$userinfo['sex']=$_SESSION['login_sex'];$userinfo['uid']=$_SESSION['login_guest_uid'];$userinfo['fuser']=$_SESSION['guest_fuser'];}

//黑名单
$query=$db->query("select * from {$tablepre}ban where (username='{$userinfo[username]}' or ip='{$onlineip}') and losttime>".gdate()." limit 1");
while($row=$db->fetch_row($query)){
	exit("<script>location.href='error.php?msg=用户名或IP受限！过期时间".date("Y-m-d H:i:s",$row['losttime'])."'</script>");exit();
}

//聊天过滤词汇
//$msg_unallowable=$msg_unallowable.$cfg['config']['msgban'];


//用户组
$query=$db->query("select * from {$tablepre}auth_group order by ov desc");
while($row=$db->fetch_row($query)){
	$groupli.="<div id='group_{$row[id]}'></div>";
	$grouparr.="grouparr['{$row[id]}']=".json_encode($row).";\n";
	$group["m".$row[id]]=$row;
}
//聊天历史记录
$query=$db->query("select * from {$tablepre}chatlog where rid='".$cfg['config']['id']."' and p='false' and state!='1' and `type`='0' order by id desc limit 0,20 ");

while($row=$db->fetch_row($query)){
	$row['msg']=str_replace(array('&amp;', '','&quot;', '&lt;', '&gt;'), array('&', "\'",'"', '<', '>'),$row['msg']);
	if($row[tuid]!="ALL"){
		$omsg="<div class='msg_li'><div style='clear:both;'></div><div class='msg' id='{$row['msgid']}'><div class='msg_head'><span class='time'>".date('H:i',$row['mtime'])."</span><span class='u'>{$row['uname']}</span><span class='dui'>对</span><span class='u'>{$row['tname']}</span><span class='shuo'>说</span></div>
		 <div class='msg_content'>{$row['msg']}</div></div></div>".$omsg;
	}
	else{
		$omsg="<div class='msg_li'><div style='clear:both;'></div><div class='msg' id='{$row[msgid]}'><div class='msg_head'><span class='time'>".date('H:i',$row['mtime'])."</span><span class='u'> {$row[uname]}：</spa></div> <div class='msg_content'>{$row[msg]}</div></div></div>".$omsg;
	}
}
//其他处理
$ts=explode(':',$cfg['config']['tserver']);


if(!isset($_SESSION['room_'.$uid.'_'.$cfg['config'][id]])){
$db->query("insert into  {$tablepre}msgs(rid,ugid,uid,uname,tuid,tname,mtime,ip,msg,`type`)values('{$cfg[config][id]}','{$userinfo[gid]}','{$userinfo[uid]}','{$userinfo[username]}','{$cfg[config][defvideo]}','{$cfg[config][defvideonick]}','".gdate()."','{$onlineip}','登陆直播间','3')");
$_SESSION['room_'.$uid.'_'.$cfg['config'][id]]=1;
}	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width">
<title>
<?=$cfg['config']['title']?>
</title>
<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="default">
<meta name="browsermode" content="application">
<meta name="apple-touch-fullscreen" content="no">
<meta http-equiv="expires" content="0">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<link rel="shortcut icon" type="image/x-icon" href="<?=$cfg['config']['ico']?>" />
<link rel="stylesheet" href="./css/index.min.css">
 <script type="text/javascript" src="js/jquery.min.js"></script>
<script src="script/jquery.nicescroll.min.js"></script>
<script type="text/javascript" src="js/swfobject.js"></script>
 <script type="text/javascript" src="js/web_socket.js"></script>
 <script type="text/javascript" src="js/json.js"></script>

<script src="script/main.m.js"></script>
<script src="../script/device.min.js"></script>
<script>
//if (!device.mobile()){window.location = '.';}
var UserList;
var ToUser;
var VideoLoaded=false;
var My={dm:'<?=$_SERVER['HTTP_HOST']?>',rid:'<?=$cfg['config']['id']?>',roomid:'<?=$cfg['config']['id']?>',chatid:'<?=$userinfo['uid']?>',name:'<?=$userinfo['username']?>',nick:'<?=$userinfo['nickname']?>',sex:'<?=$userinfo['sex']?>',age:'0',fuser:'<?=$userinfo['fuser']?>',qx:'<?=check_auth('room_admin')?'1':'0'?>',ip:'<?=$onlineip?>',vip:'<?=$userinfo['fuser']?>',color:'<?=$userinfo['gid']?>',cam:'0',state:'0',mood:'<?=$userinfo['mood']?>',rst:'<?=$time?>',camState:'1',key:'<?=connectkey()?>'}

var RoomInfo={loginTip:'<?=$cfg['config']['logintip']?>',Msglog:'<?=$cfg['config']['msglog']?>',msgBlock:'<?=$cfg['config']['msgblock']?>',msgAudit:'<?=$cfg['config']['msgaudit']?>',defaultTitle:document.title,MaxVideo:'10',VServer:'<?=$cfg['config']['vserver']?>',VideoQ:'',TServer:'<?=$ts[0]?>',TSPort:'<?=$ts[1]?>',PVideo:'<?=$cfg['config']['defvideo']?>',AutoPublicVideo:'1',AutoSelfVideo:'0',type:'1',PVideoNick:'',OtherVideoAutoPlayer:'<?=$cfg['config']['livetype']?>',r:'<?=$cfg['config']['rebots']?>'}
var grouparr=new Array();
<?=$grouparr?>
var ReLoad;
var isIE=document.all;
var aSex=['<span class="sex-womon"></span>','<span class="sex-man"></span>',''];
var aColor=['#FFF','#FFF','#FFF'];
var msg_unallowable="<?=$msg_unallowable?>";
   if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
    WEB_SOCKET_SWF_LOCATION = "js/WebSocketMain.swf";
    WEB_SOCKET_DEBUG = true;
    var ws, name, client_list={},timeid, reconnect=false;
	
</script>
<style type="text/css" media="screen">
#flashContent { display: block; text-align: left; }
</style>
</head>
<body style="position: relative; top: 0px; background-image: url(./images/929199.jpg);background-size: cover; overflow: hidden; background-position: initial initial; background-repeat: no-repeat no-repeat;">
<div id="details"></div>
<div id="newsDetail" style="display: none;"></div>
<div class="zhezhao"></div>
<div id="sharedWrap"> </div>
<div id="shared"></div>
<article>
  <div id="logo"> <img src="<?=$cfg['config']['logo']?>" alt="" height="25px"> </div>
  <section id="#head_1">
    <div id="video-flash" class="videoTitle"> <img src="./images/refresh.png" alt="刷新" width="20px" height="20px">
      <div class="video-flashBtn">刷新视频</div>
    </div>
    <!-- 视频 
    <div class="video-box">
      <div class="video-wrap">
        <div class="bg-opacity"></div>
      </div>
      <div class="video-wrap" id="view-wrap-container">
        <div id="video-status-container" class="video-status-container"></div>
        <div class="video-win" id="video-win">
        </div>
      </div>
    </div>-->
    <nav>
      <ul>
        <li class="spec1 active">聊天<span class="activeCart"></span> </li>
        <li class="spec2">客服<span class=""></span> </li>
        <li class="spec3">财经数据<span class=""></span> </li>
        <li>
          <?php
            if($_SESSION['login_uid']>0)
			{
				echo $userinfo['username']." <a href='../minilogin.php?act=logout'>退出</a>";
			}else {
				echo '<div id="loginBtn" onClick="location.href=\'../minilogin.php\'">注册/登陆</div>';
			}
			?>
            
        </li>
      </ul>
    </nav>
  </section>
  <section>
    <div id="publicChat" class="publicChat"><?=$omsg?></div>
    <div id="qqOnline" style="width: 100%; display: none;" class="white">
		<iframe src="/apps/kefu.m.php" frameborder="0" scrolling-y="auto" width="99.9999%" height=100%></iframe>
    </div>
    <div class="kuaiXun" style="width: 100%; display: none;">
      <iframe src="http://www.yy.com/index/fin/news" frameborder="0" scrolling-y="auto" width="99.9999%"  height=100%></iframe>
    </div>
  </section>
</article>
<div id="footer" style="display: block;">
  <div class="sendBtn fr" id="sendBtn">发送</div>
  <div id="sharedBtn"> <span>分享</span> </div>
  <div class="smile"> <img src="./images/smile.png" alt="表情" width="26px" height="26px"> </div>
  <div id="editor">
    <div class="messageEditor" id="messageEditor" contenteditable="true"></div>
  </div>
</div>
<div class="loginWrap"></div>
<div class="tipMesWrap"></div>
<div class="setting-expression-layer" style='display: none;'>
  <div class="expression" id="expressions">
    <table class="expr-tab expr-tab1">
    </table>
  </div>
</div>
<script>OnInit();</script>
</body>
</html>