<?php
require_once './include/common.inc.php';
if($cfg['config']['loginguest']=='0' && !isset($_SESSION['login_uid']) )header('location:./logging.php');
require_once NUOYUN_ROOT.'/include/json.php';
$json=new JSON_obj;
//房间状态
if($cfg['config']['state']=='2' and $_SESSION['room_'.$cfg['config']['id']]!=true){header("location:login.php?1");exit();}
if($cfg['config']['state']=='0'){exit("<script>location.href='error.php?msg=系统处于关闭状态！请稍候……'</script>");exit();}
//游客登录
if(!isset($_SESSION['login_uid'])  &&  $cfg['config']['loginguest']=="1"){gusetLogin();}

//是否登录
if(!isset($_SESSION['login_uid'])){header("location:./logging.php");exit();}

//用户信息
$uid=$_SESSION['login_uid'];
$db->query("update {$tablepre}members set regip='$onlineip' where uid='{$uid}'");
$userinfo=$db->fetch_row($db->query("select m.*,ms.* from {$tablepre}members m,{$tablepre}memberfields ms  where m.uid=ms.uid and m.uid='{$uid}'")); 
$_SESSION['login_gid']=$userinfo['gid'];
//游客
if($_SESSION['login_uid']==0){$userinfo['username']=$userinfo['nickname']=$_SESSION['login_nick'];$userinfo['sex']=$_SESSION['login_sex'];$userinfo['uid']=$_SESSION['login_guest_uid'];$userinfo['fuser']=$_SESSION['guest_fuser'];$userinfo['redbags']='0';}

//黑名单
$query=$db->query("select * from {$tablepre}ban where (username='{$userinfo[username]}' or ip='{$onlineip}') and losttime>".gdate()." limit 1");
while($row=$db->fetch_row($query)){
	exit("<script>location.href='error.php?msg=用户名或IP受限！过期时间".date("Y-m-d H:i:s",$row['losttime'])."'</script>");exit();
}
//聊天过滤词汇
$msg_unallowable=$msg_unallowable.$cfg['config']['msgban'];


//用户组
$query=$db->query("select * from {$tablepre}auth_group order by ov desc");
while($row=$db->fetch_row($query)){
    if($row[id]==0){$groupli.='<div id="group_rebots"></div>';}
	$groupli.="<div id='group_{$row[id]}'></div>";
	$grouparr.="grouparr['{$row[id]}']=".json_encode($row).";\n";
	$group["m".$row[id]]=$row;
}
//聊天历史记录
$query=$db->query("select * from {$tablepre}chatlog  order by id desc limit 0,20 ");
while($row=$db->fetch_row($query)){
	$row['msg']=str_replace(array('&amp;', '','&quot;', '&lt;', '&gt;'), array('&', "\'",'"', '<', '>'),$row['msg']);
	if($row[tuid]!="ALL"){
		//$omsg="<div style='clear:both;'></div><div class='msg'  id='{$row[msgid]}'><div class='msg_head'><img src='".$group["m".$row[ugid]][ico]."' class='msg_group_ico' title='".$group["m".$row[ugid]][title]."-".$group["m".$row[ugid]][sn]."'></div><div class='msg_content'><div><font class='u'  onclick='ToUser.set(\"{$row[uid]}\",\"{$row[uname]}\")'>{$row[uname]}</font> &nbsp;&nbsp;  <font class='dui'>对</font> <font class='u' onclick='ToUser.set(\"{$row[tuid]}\",\"{$row[tname]}\")'>{$row[tname]}</font> 说 <font class='date'>[".date('H:i:s',$row[mtime])."]</font></div><div class='layim_chatsay' style='margin:5px 0px;'><font  style='{$row[style]};'>{$row[msg]}</font><em class='layim_zero'></em></div></div></div><div style='clear:both;'></div>".$omsg;
             $omsg="<div style='clear:both;'></div><div class='lts_right3' id='{$row[msgid]}' fromconnid=''><span class='time'>".date('H:i:s',$row[mtime])."</span><img src='".$group["m".$row[ugid]][ico]."' title='".$group["m".$row[ugid]][title]."-".$group["m".$row[ugid]][sn]."' class='RoomUserRole'><a class='name' href='javascript:void(0)' onclick='ToUser.set(\"{$row[uid]}\",\"{$row[uname]}\")'>{$row[uname]}</a><font class='dui'>对</font><a class='name' href='javascript:void(0)' onclick='ToUser.set(\"{$row[tuid]}\",\"{$row[tname]}\")'>{$row[tname]}</a><span class='to_m' style='{$row[style]};'>{$row[msg]}</span></div>".$omsg;
	}
	else{
		//$omsg="<div style='clear:both;'></div><div class='msg' id='{$row[msgid]}'><div class='msg_head'><img src='".$group["m".$row[ugid]][ico]."' class='msg_group_ico' title='".$group["m".$row[ugid]][title]."-".$group["m".$row[ugid]][sn]."'></div><div class='msg_content'><div><font class='u'  onclick='ToUser.set(\"{$row[uid]}\",\"{$row[uname]}\")'>{$row[uname]}</font>&nbsp;&nbsp; <font class='date'>[".date('H:i:s',$row[mtime])."]</font></div><div class='layim_chatsay' style='margin:5px 0px;'><font  style='{$row[style]};'>{$row[msg]}</font><em class='layim_zero'></em></div></div></div><div style='clear:both;'></div>".$omsg;
            $omsg="<div style='clear:both;'></div><div class='lts_right3' id='{$row[msgid]}' fromconnid=''><span class='time'>".date('H:i:s',$row[mtime])."</span><img src='".$group["m".$row[ugid]][ico]."' title='".$group["m".$row[ugid]][title]."-".$group["m".$row[ugid]][sn]."' class='RoomUserRole'><a class='name' href='javascript:void(0)' onclick='ToUser.set(\"{$row[uid]}\",\"{$row[uname]}\")'>{$row[uname]}</a><span class='to_m' style='{$row[style]};'>{$row[msg]}</span></div>".$omsg;
	}
}
//游客观看直播时间
if(!isset($_COOKIE['first_access_time'])){
  
setcookie("first_access_time", time(), time()+315360000);
    
  }
    

//其他处理
$ts=explode(':',$cfg['config']['tserver']);
if(!isset($_SESSION['room_'.$uid.'_'.$cfg['config'][id]])){
    $laiyuan=$_SERVER['HTTP_REFERER'] ; //获取访客来源url
    if($laiyuan==''){$laiyuan='直接输入网址或打开标签'; }
$db->query("insert into  {$tablepre}msgs(rid,ugid,uid,uname,tuid,tname,mtime,ip,msg,`type`,laiyuan)values('{$cfg[config][id]}','{$userinfo[gid]}','{$userinfo[uid]}','{$userinfo[username]}','{$cfg[config][defvideo]}','{$cfg[config][defvideonick]}','".gdate()."','{$onlineip}','登陆直播间','3','{$laiyuan}')");
$_SESSION['room_'.$uid.'_'.$cfg['config'][id]]=1;
}

?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta name="renderer" content="webkit">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
<title><?=$cfg['config']['title']?></title>
<meta  name="keywords" content="<?=$cfg['config']['keys']?>">
<meta  name="description" content="<?=$cfg['config']['dc']?>">
<link rel="shortcut icon" type="image/x-icon" href="<?=$cfg['config']['ico']?>" />
<link href="room/skins/qqxiaoyou/iconfont.css" rel="stylesheet" type="text/css"  />
<link href="room/skins/qqxiaoyou/css.css" rel="stylesheet" type="text/css"  />
<link href="room/images/layim.css" rel="stylesheet" type="text/css"  />

<!--[if lt IE 9]>
<link href="room/skins/qqxiaoyou/less.css" rel="stylesheet" type="text/css">
<![endif]-->
<script src="room/script/jquery.min.js"></script>
<script src="room/script/swfobject.js" type="text/javascript" ></script>
<script src="room/script/web_socket.js" type="text/javascript" ></script>
<script src="room/script/layer.js"></script>
<script src="room/script/jquery.nicescroll.min.js"></script>
<script src="room/script/pastepicture.js"></script>
<script src="room/script/function.js?<?=time()?>" type="text/javascript" charset="utf-8"></script>
<script src="room/script/init.js?<?=time()?>" type="text/javascript" charset="utf-8"></script>
<script src="room/script/device.min.js"></script>
<!--系统开发：QQ76314154-->
<script>
layer.config({extend: ['skin/layer.ext.css']});
var UserList;
var ToUser;
var VideoLoaded=false;
var My={dm:'<?=$_SERVER['HTTP_HOST']?>',rid:'<?=$cfg['config']['id']?>',roomid:'<?=$cfg['config']['id']?>',chatid:'<?=$userinfo['uid']?>',name:'<?=$userinfo['username']?>',nick:'<?=$userinfo['nickname']?>',sex:'<?=$userinfo['sex']?>',age:'0',fuser:'<?=$userinfo['fuser']?>',qx:'<?=check_auth('room_admin')?'1':'0'?>',ip:'<?=$onlineip?>',vip:'<?=$userinfo['fuser']?>',color:'<?=$userinfo['gid']?>',cam:'<?=$userinfo['face']?>',state:'0',mood:'<?=$userinfo['mood']?>',rst:'<?=$time?>',camState:'1',key:'<?=connectkey()?>',redbags_num:'<?=$userinfo['redbags']?>'}

var RoomInfo={loginTip:'<?=$cfg['config']['logintip']?>',Msglog:'<?=$cfg['config']['msglog']?>',msgBlock:'<?=$cfg['config']['msgblock']?>',msgAudit:'<?=$cfg['config']['msgaudit']?>',defaultTitle:document.title,MaxVideo:'10',VServer:'<?=$cfg['config']['vserver']?>',VideoQ:'',TServer:'<?=$ts[0]?>',TSPort:'<?=$ts[1]?>',PVideo:'<?=$cfg['config']['defvideo']?>',AutoPublicVideo:'0',AutoSelfVideo:'0',type:'1',PVideoNick:'<?=$cfg['config']['defvide0nick']?>',OtherVideoAutoPlayer:'<?=$cfg['config']['livetype']?>',r:'<?=$cfg['config']['rebots']?>',tiyantime:'<?=$cfg['config']['tiyantime']?>',fayanjiange:'<?=$cfg['config']['fayanjiange']?>',logintc:'<?=$cfg['config']['logintc']?>',bg:'<?=$cfg['config']['bg']?>'}
var grouparr=new Array();
<?=$grouparr?>
var ReLoad;
 var tbox;
var isIE=document.all;
var aColor=['#FFF','#FFF','#FFF'];
var msg_unallowable="<?=$msg_unallowable?>";
   if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
    WEB_SOCKET_SWF_LOCATION = "script/WebSocketMain.swf";
    WEB_SOCKET_DEBUG = true;
    var ws, name, client_list={},timeid, reconnect=false;
	
</script>
</head>
<body onresize="OnResize()"  style="background:url(<?=$cfg['config']['bg']?>) repeat 0 0 #408080; background-size: 100%;">
<div id="UI_MainBox" >
<script>if (!device.desktop()){window.location = './m';}if(getCookie('bg_img')){$(document.body).css({'background': 'url(' + getCookie('bg_img') + ')  #408080','background-size': '100%'});}</script>
  <div id="UI_Head" style="display:none">                                                                                                                                                  
    <div class="head" style="background: rgba(50, 50, 50, 0.7) none repeat scroll 0% 0% / auto 50px;" >                                                                                                                                                                                       
      <div id="head_box" class="head_box">
        <div class="logo_bg" style="BACKGROUND: url(<?=$cfg['config']['logo']?>) no-repeat"> <span class="f_left" id="favlink">
	<a class="link1 left1" href="ico.php"><b>保存到桌面</b></a>
            <?php
    $query=$db->query("select * from {$tablepre}apps_manage where s='0' and position='top' order by ov desc ");
	while($row=$db->fetch_row($query)){
		
		$obj=$json->encode($row);
		echo "<a class='link1' href='javascript://'  onClick='openApp({$obj})' id='app_$row[id]'><b>{$row[title]}</b></a>";
	}
	?>
	
</span>   
<a href="javascript:void(0)" class="kefu" onClick="openWin(2,'高级客服QQ','/apps/kefu.php',810,500)"><img src="room/images/onlineQQ.png"></a>

        </div>
          
        <div class="head_user">
            <a style="line-height: 14px;display: inline-block;text-align: center;font-weight:bold;border:solid 1px #FF0;color: #FF0;padding: 5px;width: 30px; cursor:pointer; height:auto;margin-right: 20px;float:left;" id="changeskin">换肤</a>
          <?php
            if($_SESSION['login_uid']>0)
			{
			?>
          <a href="javascript:void(0)" class="userinfo" onClick="openWin(2,false,'room/profiles.php?uid=<?=$userinfo['uid']?>',460,600)"><img src='../face/img.php?t=p1&u=<?=$userinfo['uid']?>' border="0" class="userimg"/>
          <?=$userinfo['username']?>
          ▼</a> <a href='../logging.php?act=logout' class="userlogout">退出</a>
          <?php
			}else{
			?>
           <a href="javascript:void(0)" class="login" onClick="openWin(2,false,'room/minilogin.php',390,310)">登录</a><a href="javascript:void(0)" class="reg" onClick="openWin(2,false,'room/minilogin.php?a=0',390,533)">注册</a>
          <?php }?>
        </div>
      </div>
    </div>
  </div>
  <div id="UI_Left" style="display:none">

    <div id="UI_Left2"  class="bg_png1">
    <div>
       <!-- <iframe src="http://panel.kuaixun360.com/hangqing5.php" height="116px" width="190px" scrolling="no" frameborder="0"></iframe>-->
     <iframe height="78" width="100%" allowTransparency="true" marginwidth="0" marginheight="0"  frameborder="0" scrolling="no" src="./apps/vote.php?rid=<?=$cfg['config']['id']?>"></iframe>
      </div>
        <div id="modules">
                <ul id="modules-box">
                     <?php
            $query=$db->query("select * from {$tablepre}apps_manage where s='0' and position='left' order by ov desc ");
            $h=1;
	while($row=$db->fetch_row($query)){
            $style=($h%3==0)?'style="margin: 0px 0px 2px;"':'';
		$obj=$json->encode($row);
		 echo "<li {$style}><a id='app_$row[id]' href='javascript://'  onClick='openApp({$obj})'><img src='$row[ico]' ><span>{$row[title]}</span></a></li>";
	$h++;
                 
        }
        $style=($h++%3==0)?'style="margin: 0px 0px 2px;"':'';
         echo "<li {$style} class='wapbtn'><a  href='javascript://'  ><img src='room/images/phone_live.png' ><span style='color:#FF0;'>手机直播</span></a><div class='openerweima'><img src='{$cfg['config']['ewm']}'>扫一扫二维码<br>进入手机直播室<i class='sanjiao'></i></div>  </li>";
	?>
                  
            </ul>
            </div>
      <div  class="title_tab"> <a href="javascript:void(0)" class="bg_png2" onClick="bt_SwitchListTab('User')" id="listTab1">在线会员<font style="display: block;">(<span id="OnlineUserNum"></span>)</font></a> <a href="javascript:void(0)" onClick="bt_SwitchListTab('Other')" id="listTab2"><?=($userinfo['gid']=='3'?"我的客户":"我的客服")?><font style="display: block;">(<span id="OnlineOtherNum"></span>)</font></a> </div>
      <div style="clear:both"></div>
      <div id="OnlineUser_Find" style="height:25px; margin:0px; padding:2px; overflow:hidden; line-height:25px; border:1px solid #999" class="bg_png2">
        <input name="" type="image" title="找人" onClick="bt_FindUser()" src="room/images/search.png" style="float:right; margin:5px;" />
        <input name="finduser" type="text" id="finduser"  style="border:0px; width:150px; height:25px; line-height:25px; padding:0px; background:none; color:#FFF; "/>
      </div>
      <div id="OnLineUser_OhterList" class="OnLineUser" style="height:50px;display:none" >
        <div id="group_myuser"></div>
      </div>
      <div id="OnLineUser_FindList" class="OnLineUser" style="height:50px;display:none" ></div>
      <div id="OnLineUser" class="OnLineUser"  style="height:50px;">
        <div id="group_my"></div>
        <?=$groupli?>
      
      </div>
      <!--二维码图片-->
     <div style="height:90px; background:#fff;  margin-bottom: 3px;display: none;"><img src="<?=$cfg['config']['ewm']?>" width=196></div>
    </div>
    
  </div>
  <div id="UI_Right" class="bg_png" style="display:none">
    <div id="RoomMV">
      <div class="title_bar">
          <span id="defvideosrc" style="">当前讲师:&nbsp;<span><?=$cfg['config']['defvideonick']?></span></span> <span id="bt_defvideosrc" style="display:none;"> [<a href='javascript:bt_defvideosrc()'>上课</a>]</span>
		
                 <span class="video-execption" style="display: inline;"><a href="javascript:showLive()" id="video-flash">刷新视频</a></span>
           <a href="javascript://" onClick="openWin(2,'讲师榜','/apps/rank.php',820,600)" style="float:right; color:#FF0;padding-top: 4px;"><img src="room/images/teacherBtn.png" height="27" border="0/"></a></div>
      <div id="OnLine_MV">
      <span style="font-size:18px">您还没有安装flash播放器,请点击<a href="http://www.adobe.com/go/getflash" target="_blank"  style="font-size:18px;color:#090">这里</a>安装</span>
        
      </div>
    </div>
      <div class="RegBagNumBox" title="主播收到的红包">
<div class="RegBagNum"><p><em> </em><span><?=$cfg['config']['redbags']?></span></p></div>
<span class="addnum">+1</span>
		</div>
    <div class="NoticeList">
    <?php
    $query=$db->query("select * from {$tablepre}notice where type='1' order by ov desc,id desc");
	while($row=$db->fetch_row($query)){
		$tab.="<a href='javascript:void(0)' id='notice_{$row[id]}' class='notice_tab'>{$row[title]}</a>";
		$txt.="<div id='notice_{$row[id]}_div' class='notice_div' style='display:none'>".tohtml($row['txt'])."</div>";
	}
	
	?>
        <div class="tab bg_png3">
      <?=$tab?>
          <div style=" clear:both"></div>
        </div>
      <div id="NoticeList" style="height:80px;">
       <?=$txt?>
      </div>
<script>
	$('.tab a:first').addClass('active');
	$('.notice_div:first').css('display','inline-block');
	$('.tab a').on('click',function(){
            if($(this).attr("id")=='app_1'){return;}
		$('.tab a').removeClass('active');
		$(this).addClass('active');
		$('.notice_div').css('display','none');
		$('#'+$(this)[0].id+'_div').css('display','inline-block');
	});
	
</script> 
    </div>
  </div>
  <div id="UI_Central"  class="bg_png" style="background: rgba(0,0,0,0);width:100%;margin-left:0px;">
    <div class="title_bar2" style="display:none;">
     <span class="fl clearfix horn">公告：<img src="room/images/laba.png"></span>
     <div class="notice-scroll fl" id="notice-scrollbox">
    <marquee scrollamount="3" id="msg_tip_show">
    	<?php
        $query=$db->query("select * from {$tablepre}chatlog where  rid='".$cfg['config']['id']."' and state='2' and type='0' order by id desc limit 1");
		while($row=$db->fetch_row($query)){
			echo "<span style='color:#FF0'>".tohtml($row[msg])."</span>";
		}
		?>
    </marquee>
         </div>
    </div>
    <div id="MsgBox" style="position:relative;">
           <div id='divnotice'></div>
        <div id="Y_pub_Tools">
                    <a href="javascript:void(0)" onclick="bt_MsgClear();" ><span class="clear">清屏</span></a>
                    <a href="javascript:void(0)" onclick="bt_toggleScroll();" ><span class="scroll" id="bt_gundong">滚动</span></a>
              </div>
      <div id="Video_List"></div>
      <div id="MsgBox1" style="overflow:auto; height:500px; padding:0px 6px 0px 6px;position:relative" >
        <?=$omsg?>
      </div>
      <div class="drag_skin" id="drag_bar" style=" display:none"></div>
      <div id="MsgBox2" style="height:100px; overflow:auto;  padding:0px 10px 0px 10px;position:relative; display:none" ></div>
    </div>
    <div id="UI_Control" class="tool_bar" style="display:none">
      <div style="height:22px; line-height:22px; overflow: hidden; font-size:14px;overflow: hidden;">
    <span  id="msg_tip_admin_show" >
    <?php
        $query=$db->query("select * from {$tablepre}chatlog where  rid='".$cfg['config']['id']."' and state='3' and type='0' order by id desc limit 1");
		while($row=$db->fetch_row($query)){
			echo "<span style='color:red;display:block;width:1000px;margin-left: 13px;margin-right: 13px;'>".tohtml($row[msg])."</span>";
		}
	?>
    </span>
    </div>
    <div id="qqbts"><p>
        <?php
$query=$db->query("select realname from {$tablepre}members  where gid=3 and tuser='admin' order by lastactivity DESC limit 0,4 ");
while($row=$db->fetch_row($query)){
	$kefulist.="<a target='_blank' href='http://wpa.qq.com/msgrd?v=3&amp;uin={$row[realname]}&amp;site=qq&amp;menu=yes' ><img src='room/images/qqjt.gif'  title='点击咨询QQ：{$row[realname]}' class='qqimg'></a>";
}
echo $kefulist;
?>
</p></div>    

                     
              
    <span id="bar_list">
       
    	<a href="javascript:void(0)" class="bar_6 bar"  id="openPOPChat" style="float:right" >我的私聊</a>
        <a href="javascript:void(0)" class="bar_7 bar" id="bt_myimage" onClick="bt_FontBar(this)" ><i class="ico1">A</i>字体</a>
		<a href="javascript:void(0)" id="bt_face" class="bar_2 bar" onclick="showFacePanel(this,'#Msg');" isface="2"><i class="iconfont"></i>表情</a>
		<a href="javascript:void(0)" class="bar_3 bar" id="bt_caitiao"><i class="iconfont"></i>彩条</a>
		<a href="javascript:void(0)" class="bar_1 bar" id="bt_myimage" onclick="bt_insertImg('#Msg')"><i class="iconfont"></i>图片</a>
                <? if(check_auth('room_admin')){?>  <a href="javascript:void(0)" class="bar_1 bar" id="bt_myimage" onclick="bt_rollnotice()"><i class="iconfont"></i>弹幕</a><?}?>
               
                <!-- <a href="javascript:void(0)" class="bar_4 bar" id="bt_qingping"  onClick="bt_MsgClear();">清屏</a>-->
		 <!--<a href="javascript:void(0)" class="bar_5 bar" id="bt_gundong" select="true"  onClick="bt_toggleScroll()" >滚动</a>-->
                
        <div class="tool_div">
                    <div id="myimage" class="hid ption_a hongbao" onmouseout="_toolTimeRed()" onmouseover="_toolCloseTimeRed()" style="display: none;width: 243px;"></div>
                <a class="bar_8 bar" id="bt_myimage" onmouseout="_toolTimeRed()" onmouseover="_toolCloseTimeRed()" onclick="user_hb()">红包：<font id="redbags"><?=$userinfo['redbags']?></font></a>
		</div>
              
	</span>
<span style="margin-left:5px;">   对<div id="ToUser" class="lts_chat5">游客4cb6731a</div><span style="position: relative;"><div id="apDiv2" ></div></span>&nbsp;说</span>
 
        <!--  <input name="Personal" value="false" type="image" id="Personal" title="公聊/私聊" bt_Personal(this)" src="images/Personal_false.gif"/>-->
        <input  type="hidden" name="Personal" id="Personal" value="false" />
        <!--<input name="BTFont" type="image" id="BTFont" title="设置字体颜色和格式" onClick="bt_FontBar(this)" src="images/Font.gif"/>
        <input type="image" title="视频密聊" src="images/Vlove.gif" onclick="if(My.qx!=0||My.vip!=0)VideoList.Connect(ToUser.id,ToUser.name,0);else alert('你不是VIP用户！不能使用该功能');"/>

        <input type="image" title="声音提示" src="images/So.gif" id="toggleaudio" onClick="bt_toggleAudio();"/>-->
        <!--<input type="image" title="分屏" src="images/FP_false.gif" onClick="bt_fenping()" id="btfp"/>
        <input name="BTFont" type="image" id="BTFont" title="送礼物" onClick="bt_gifts(this)" src="images/lw.gif" />-->
    </div>
    <div  class="inputmsg" style="display:none">
      <div id="Msg" contentEditable="true" style="height:60px; overflow:auto;font-size:13px; color:#000; outline: none;z-index: 3;top: 0;margin: 5px 126px 5px 5px; bottom: 0;left: 0;border: #B67233 1px solid;" onClick="HideMenu();"></div>
      <div style="text-align:right;top:0px; right: 5px;position: absolute; width: 116px;height: 62px;margin: 6px 0px;">
      
        <button class="btn-input" name="Send_bt" id="Send_bt"></button>
        <input type="hidden" name="Send_key" id="Send_key" value="1" />
        <!--
    <div style="text-align:right;top:-20px; left:100%;position:relative; margin-left:-77px; width:60px;"><input name="Send_bt" type="image" src="images/Send.gif" id="Send_bt" title="发送消息" onclick="SysSend.msg()"/><input name="Send_key" type="image" id="Send_key" src="images/Send_C.gif" / value="1" title="发送消息快捷键设置" onclick="bt_Send_key_option(this)">
    --> 
      </div>
      
    </div>

  </div>
</div>
</div>
<div id="FontBar" style="display:none">
  <select name="FontName" id="FontName" onChange="getId('Msg').style.fontFamily=this[this.selectedIndex].value">
    <option selected="selected">字体</option>
    <option value="SimSun" style="font-family: SimSun">宋体</option>
    <option value="SimHei" style="font-family: SimHei">黑体</option>
    <option value="KaiTi_GB2312" style="font-family: KaiTi_GB2312">楷体</option>
    <option value="FangSong_GB23122" style="font-family:FangSong_GB2312">仿宋</option>
    <option value="Microsoft YaHei" style="font-family: Microsoft YaHei">微软雅黑</option>
    <option value="Arial">Arial</option>
    <option value="MS Sans Serif">MS Sans Serif</option>
    <option value="Wingdings">Wingdings</option>
  </select>
  <select name="FontSize"  id="FontSize" onChange="getId('Msg').style.fontSize=this[this.selectedIndex].value+'pt'">
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
    <option value="11">11</option>
    <option value="12"  selected="selected">12</option>
    <option value="13">13</option>
    <option value="14">14</option>
  </select>
  <input type="image" class="bt_false" title="粗体" onMouseOver="this.className='bt_true'" onMouseOut="if(this.value=='false')this.className='bt_false'" src="room/images/bold.gif" onClick="ck_Font(this,'FontBold')" value="false"/>
  <input type="image" class="bt_false" title="斜体" onMouseOver="this.className='bt_true'" onMouseOut="if(this.value=='false')this.className='bt_false'" src="room/images/Italic.gif" onClick="ck_Font(this,'FontItalic')" value="false"/>
  <input type="image" class="bt_false" title="下划线" onMouseOver="this.className='bt_true'" onMouseOut="if(this.value=='false')this.className='bt_false'" src="room/images/underline.gif" onClick="ck_Font(this,'Fontunderline')" value="false"/>
  <input name="FontColor" type="image" class="bt_false" id="FontColor" title="文字颜色" onMouseOver="this.className='bt_true'" onMouseOut="this.className='bt_false'" src="room/images/color.gif" onClick="ck_Font(this,'ShowColorPicker');" value="false"/>
</div>
<div id='ColorTable' style="display:none; " onblur="BrdBlur('ColorTable');" tabIndex></div>
<div id="Smileys" style="display:none; height:180px;" onblur="BrdBlur('Smileys');" tabIndex></div>
<div id="Send_key_option" style="display:none" onblur="BrdBlur('Send_key_option');" tabIndex>
  <div onMouseOver="this.className='bt_true'" onMouseOut="this.className='bt_false'" style="padding-left:20px; height:20px; line-height:20px;" class="bt_false" onClick="$('Send_key').value='1';$('Send_key_option').style.display='none'">按 Enter 键发送消息</div>
  <div onMouseOver="this.className='bt_true'" onMouseOut="this.className='bt_false'" style="padding-left:20px; height:20px; line-height:20px;" class="bt_false" onClick="$('Send_key').value='2';$('Send_key_option').style.display='none'">按 Ctrl+Enter 键发送消息</div>
</div>

</div>
<div style="position:absolute; left: -300px;" id="MsgSound"></div>
<div id="face" style="position:absolute; display:none"></div>
<div id="caitiao" class="hid ption_a"></div>
<form id="imgUpload" name="imgUpload" action="" method="post" enctype="multipart/form-data" target="e">
<input type="hidden" name="info" id="imgUptag" value="#Msg">
<input id="filedata" contenteditable="false" type="file" style="display:none;" onchange="$('#imgUpload').attr('action','../upload/upload_frame.php?act=InsertImg&' + new Date().getTime() );document.imgUpload.submit();" name="filedata">
</form>
<iframe name="e" id="e" style="display:none"></iframe>
<div id="tip_login_win" style="display:none">
<?php
if($cfg['config']['logintc']==1){
$query=$db->query("select realname from {$tablepre}members  where gid=3   order by lastactivity DESC  ");
while($row=$db->fetch_row($query)){
	$t_kefulist.="

    <li> 
<a target=\"_blank\" href=\"http://wpa.qq.com/msgrd?v=3&amp;uin={$row[realname]}&amp;site=qq&amp;menu=yes\" ><img border=\"0\" style=\"vertical-align:middle\" src=\"http://wpa.qq.com/pa?p=2:{$row[realname]}:41\" alt=\"{$row[realname]}\" title=\"请加QQ：{$row[realname]}\" /></a> 
</li>
";
}
	$query=$db->query("select * from {$tablepre}notice where id='2'");
	while($row=$db->fetch_row($query)){
		$tipopen= tohtml($row['txt']);
	}
       echo str_replace("{kefulist}",$t_kefulist,$tipopen);
}
?>
</div>
<script>
   if(RoomInfo.logintc==1){
   	var tipopen =layer.open({
	        type: 1,
		title: false,
		shadeClose: true,
		shade: false,
               closeBtn: false,
                 bgcolor: '',
		area: ['800px', '230px'],
		content: $("#tip_login_win").html() 
		});
   }
    OnInit();
</script>
<script src="room/script/jquery.danmu.min.js"></script>
<div style="display:none">
  <?=tohtml($cfg['config']['tongji'])?>
</div>
<!--[if IE 6]>
	<script type='text/javascript' src='room/script/png.js'></script>
	<script type='text/javascript'>
	  DD_belatedPNG.fix('.bg_png,.bg_png1,.bg_png2');
	</script>
<![endif]-->

</body>
</html>
