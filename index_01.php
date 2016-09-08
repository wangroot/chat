<?php
require_once './include/common.inc.php';
if($cfg['config']['loginguest']=='0' && !isset($_SESSION['login_uid']) )header('location:./logging.php');
require_once NUOYUN_ROOT.'./include/json.php';
$json=new JSON_obj;
//房间状态
if($cfg['config']['state']=='2' and $_SESSION['room_'.$cfg['config']['id']]!=true){header("location:room/login.php?1");exit();}
if($cfg['config']['state']=='0'){exit("<script>location.href='room/error.php?msg=系统处于关闭状态！请稍候……'</script>");exit();}
if($cfg['config']['state']=='3'){system_open_time($cfg['config']['sysstart'],$cfg['config']['sysend']);}
//游客登录
if(!isset($_SESSION['login_uid'])  &&  $cfg['config']['loginguest']=="1"){gusetLogin();}

//是否登录
if(!isset($_SESSION['login_uid'])){header("location:./logging.php");exit();}

//用户信息
$uid=$_SESSION['login_uid'];
$db->query("update {$tablepre}members set regip='$onlineip' where uid='{$uid}'");
$userinfo=$db->fetch_row($db->query("select m.*,ms.* from {$tablepre}members m,{$tablepre}memberfields ms  where m.uid=ms.uid and m.uid='{$uid}'")); 
$_SESSION['login_gid']=$userinfo['gid'];
// var_dump($_SESSION);

//游客
if($_SESSION['login_uid']==0){

$userinfo['username']=$userinfo['nickname']=$_SESSION['login_nick'];
$userinfo['sex']=$_SESSION['login_sex'];
$userinfo['uid']=$_SESSION['login_guest_uid'];
$userinfo['fuser']=$_SESSION['guest_fuser'];
$userinfo['redbags']='0';
}

$_COOKIE['aaa']=$userinfo['nickname'];

//黑名单
$query=$db->query("select * from {$tablepre}ban where (username='{$userinfo[username]}' or ip='{$onlineip}') and losttime>".gdate()." limit 1");
while($row=$db->fetch_row($query)){
    exit("<script>location.href='room/error.php?msg=用户名或IP受限！过期时间".date("Y-m-d H:i:s",$row['losttime'])."'</script>");exit();
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
$query=$db->query("select * from {$tablepre}chatlog where rid='".$cfg['config']['id']."' and p='false' and state!='1' and `type`='0' order by id desc limit 0,30 ");
while($row=$db->fetch_row($query)){
    $row['msg']=html_entity_decode($row['msg']);
    if($row['tuid']!="ALL"){ 
            if($row['tuid']=="hongbao"){
                $omsg=$row['msg'].$omsg;
                
            }else{
          
            // $omsg="<li class='room-chat-item' id='{$row[msgid]}' fromconid=''><span class='majia'>".$group["m".$row[ugid]][title]."</span><span class='room-chat-name'><a href='javascript:void(0)' onclick='ToUser.set(\"{$row[uid]}\",\"{$row[uname]}\")'>{$row[uname]}</a>对<a href='javascript:void(0)' onclick='ToUser.set(\"{$row[tuid]}\",\"{$row[tname]}\")'>{$row[tname]}</a></span>:<span class='room-chat-content'>{$row[msg]}</span></li>".$omsg; 
             $omsg="<li class='room-chat-item' id='{$row[msgid]}' fromconid=''><img src='".$group["m".$row[ugid]][ico]."' title='".$group["m".$row[ugid]][title]."-".$group["m".$row[ugid]][sn]."' class='RoomUserRole heqiang1'><span class='room-chat-name'><a href='javascript:void(0)' onclick='ToUser.set(\"{$row[uid]}\",\"{$row[uname]}\")'>{$row[uname]}</a>对<a href='javascript:void(0)' onclick='ToUser.set(\"{$row[tuid]}\",\"{$row[tname]}\")'>{$row[tname]}</a></span>:<span class='room-chat-content'>{$row[msg]}</span></li>".$omsg; 
             
             }
    }
    else{
             //$omsg="<li class='room-chat-item' id='{$row[msgid]}' fromconid=''><span class='majia'>".$group["m".$row[ugid]][title]."</span><span class='room-chat-name'><a href='javascript:void(0)' onclick='ToUser.set(\"{$row[uid]}\",\"{$row[uname]}\")'>{$row[uname]}</a></span>:<span class='room-chat-content'>{$row[msg]}</span></li>".$omsg; 
             $omsg="<li class='room-chat-item' id='{$row[msgid]}' fromconid=''><img src='".$group["m".$row[ugid]][ico]."' title='".$group["m".$row[ugid]][title]."-".$group["m".$row[ugid]][sn]."' class='RoomUserRole heqiang1'><span class='room-chat-name'><a href='javascript:void(0)' onclick='ToUser.set(\"{$row[uid]}\",\"{$row[uname]}\")'>{$row[uname]}</a></span>:<span class='room-chat-content'>{$row[msg]}</span></li>".$omsg; 
             
             }
}
//游客观看直播时间
if(!isset($_COOKIE['first_access_time'])){
  
setcookie("first_access_time", time(), time()+315360000);
    
  }
    

//其他处理
$ts=explode(':',$cfg['config']['tserver']);
if(!isset($_SESSION['room_'.$uid.'_'.$cfg['config']['id']])){
    $laiyuan=$_SERVER['HTTP_REFERER'] ; //获取访客来源url
    if($laiyuan==''){$laiyuan='直接输入网址或打开标签'; }
$db->query("insert into  {$tablepre}msgs(rid,ugid,uid,uname,tuid,tname,mtime,ip,msg,`type`,laiyuan)values('{$cfg[config][id]}','{$userinfo[gid]}','{$userinfo[uid]}','{$userinfo[username]}','{$cfg[config][defvideo]}','{$cfg[config][defvideonick]}','".gdate()."','{$onlineip}','进入直播间','3','{$laiyuan}')");
$_SESSION['room_'.$uid.'_'.$cfg['config']['id']]=1;
}
//是否为房间管理
$room_admin=check_auth('room_admin');
$room_admin=check_admin($_COOKIE['uid']);

function check_admin($admin)
{
    if (isset($admin)&& $admin=='1') {
        return $admin;
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>财讯直播房间内容1</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <!-- <link rel="stylesheet" href="css/bootstrap.css" /> -->
        <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/live-room.css" />
        <link rel="stylesheet" href="./css/jquery.mCustomScrollbar.min.css">
    

<link href="room/skins/qqxiaoyou/iconfont.css" rel="stylesheet" type="text/css"  />
<link href="room/images/layim.css" rel="stylesheet" type="text/css"  />

<script src="room/script/jquery.min.js"></script>
<script type="text/javascript" src="room/script/swfobject.js"></script>
<script type="text/javascript" src="room/script/web_socket.js"></script>
<script src="room/script/layer.js"></script>
<script src="room/script/jquery.nicescroll.min.js"></script>
<script src="room/script/pastepicture.js"></script>
<script src="room/script/function.js?<?=time()?>" type="text/javascript" charset="utf-8"></script>
<script src="room/script/init.js?<?=time()?>" type="text/javascript" charset="utf-8"></script>
<script src="room/script/device.min.js"></script>
<script src="./js/jquery.cookie.js"></script>

<style type="text/css">
    
.bar{width: 49px;height:20px; text-align: center;line-height:20px;border:#ccc 1px solid;border-radius:2px;margin:7px 0 0 5px; display:inline-block;}
.bar:hover{border:#999 1px solid;}
.bar_1{background-position:4px 4px;}
.bar_2{background-position:4px -36px;}
.bar_3{background-position:3px -77px;}
.bar_4{background-position:4px -118px;}
.bar_5{background-position:4px -158px;}
.bar_6{background:url(../../images/icon_1.png) no-repeat;background-position:-7px -5px;margin-right:5px;}
.bar_1:hover{background-position:4px -201px;}
.bar_2:hover{background-position:4px -241px;}
.bar_3:hover{background-position:3px -282px;}
.bar_4:hover{background-position:4px -323px;}
.bar_5[select=true]{background-position:4px -363px;}
.bar_7:hover{ background-position:-7px -355px;}
.bar_8 {background: url(../../images/ico_flow2.png) no-repeat 3px 2px;background-color: #ff0;border: 1px #f00 solid;color: #f00;width: auto;     padding-right: 3px; padding-left: 20px;}
.bar_8:hover{border:#ff0 1px solid;}
.tool_bar .s_right{position:absolute;right:3px;top:7px;background:rgb(243,243,243);padding-left:30px;}
.tool_bar select{margin:0 5px;}
.tool_div {display: inline-block;position: relative;}
/* 红包 */
#myimage,#myliwu{background:#fff; border:#f00 1px solid; border-right:none; border-bottom:none; border-radius:3px; line-height:20px;bottom:30px; left:4px; text-align:center;z-index: 99999999;}
#myimage ul,#myliwu ul{ width:80px; float:left; border:#f00 1px solid; border-left:none; border-top:none;}
#myimage li,#myliwu li{ border-top:1px solid #f00; font-size:12px;}
#myimage ul li:first-child,#myliwu ul li:first-child{ border:none;}
#myimage li a,#myliwu li a{ display:block; line-height:30px;color: #000;}
#myimage li a:hover,#myliwu li a:hover{ background:#E6E6E6;}
.RegBagNumBox{position:absolute;left:9px;top:54px;z-index:100;text-align:left;}
.RegBagNum{display:block;height:38px;overflow: hidden;}
.RegBagNum em{width:35px;height:38px;float:left;display:inline-block;background:url("../../images/redBagNum.png") no-repeat scroll 0 0 transparent;}
.RegBagNum span{color:#fff;line-height:54px;min-width:20px;padding:0 12px 0 4px;float:left;height:37px;display:inline-block;background:url("../../images/redBagNum.png") no-repeat scroll right 0 transparent;}
.RegBagNumBox span.addnum{color: #ffff00;position: absolute;top: 19px;right: 6px;font-size: 10px;display:none;}
.input_area{margin:0 5px;background:rgb(255,255,255);height:62px;position:relative;padding:5px 130px 0 5px;}
.input_area input{font-size:20px;padding:5px 0;width:100%;height: 42px;border:#B67233 1px solid;outline:none;font-family:Microsoft Yahei}
.sub_btn{background:#aaa url(../../images/sent.png) center center no-repeat; width:110px;height:55px;line-height:35px;display:block;border-radius:5px;font-size:0px;position:absolute;right:5px; top:5px;}
/* 表情、彩条 */
.facelist {
    clear: both;
    _height: 1px;
    overflow: hidden;
    border-right: 1px solid #ebebeb;
    border-top: 1px solid #ebebeb;
}
#face{background:#fff; color:#000;border-radius:3px;padding:8px;width:280px;height:208px;position:fixed;z-index:99999999;}
#face dd{float:left;width:27px;height:27px;overflow:hidden;cursor:pointer;padding-left: 3px;padding-top: 3px;    border-left: 1px solid #ebebeb; border-bottom: 1px solid #ebebeb;}
#face ul,#caitiao ul{height:22px; line-height:22px;background:#e4e4e3 url(../../images/g_bg.jpg) left top repeat-x;position:absolute;left:0;bottom:0;width:100%;}
#face ul li,#caitiao ul li{float:left; padding:0 5px;border-left:#e4e4e3 1px solid;border-right:#e4e4e3 1px solid;}
#face ul li:hover,#face ul .f_cur,#caitiao ul li:hover,#caitiao ul .f_cur{background:#fff;border-color:#d9d9d8;cursor:pointer;}
#caitiao{background:#fff;border:rgb(146,146,142) 1px solid; border-radius:3px;padding:5px;width:70px;height:115px;left:73px;bottom:81px;box-shadow: 1px 1px 5px #666; color:#000;z-index: 99999999;    margin: 0px 50px 20px 0px ;position: absolute}
#caitiao dd{background:#f8f6fe;height:21px; line-height:22px;text-align:center;cursor:pointer;}
#caitiao dd:hover{background:#E6E6E6;}
.gray { -webkit-filter: grayscale(100%);-moz-filter: grayscale(100%); -ms-filter: grayscale(100%); -o-filter: grayscale(100%); filter: grayscale(100%); filter: gray; } 

#Div_VN1{width:100px;text-align:center;background:rgba(255,255,255,0.3);color:#fff;padding: 2px 16px 5px 16px;}
#shuaxin{{margin-left:10px;font-size: 14px;margin-right: 10px;}





</style>
<script>
layer.config({extend: ['skin/layer.ext.css']});
var UserList;
var ToUser;
var VideoLoaded=false;


var My={dm:'<?=$_SERVER['HTTP_HOST']?>',rid:'<?=$rid?>',roomid:'<?=$rid?>',chatid:'<?php echo $_COOKIE['uid']?$_COOKIE['uid']:$userinfo['uid']?>',name:'<?php echo $_COOKIE['username']?>',nick:'<?php echo $_COOKIE['uname']?$_COOKIE['uname']:$userinfo['username']?>',sex:'<?php echo 2;?>',age:'0',fuser:'<?=$userinfo['fuser']?>',qx:'<?php echo $_COOKIE['qx']?'1':'0'?>',ip:'<?=$onlineip?>',vip:'<?=$userinfo['fuser']?>',color:'<?php echo $_COOKIE['color']?$_COOKIE['color']:$userinfo['gid']?>',cam:'<?php echo $_COOKIE['cam']?$_COOKIE['cam']:$userinfo['face']?>',state:'0',mood:'<?php echo $_COOKIE['uname']?$_COOKIE['uname']:$userinfo['mood']?>',rst:'<?=$time?>',camState:'1',key:'<?=connectkey()?>',redbags_num:'<?=$userinfo['redbags']?>'}

var RoomInfo={loginTip:'<?=$cfg['config']['logintip']?>',Msglog:'<?=$cfg['config']['msglog']?>',msgBlock:'<?=$cfg['config']['msgblock']?>',msgAudit:'<?=$cfg['config']['msgaudit']?>',defaultTitle:document.title,MaxVideo:'10',VServer:'<?=$cfg['config']['vserver']?>',VideoQ:'',TServer:'<?=$ts[0]?>',TSPort:'<?=$ts[1]?>',PVideo:'<?=$cfg['config']['defvideo']?>',AutoPublicVideo:'0',AutoSelfVideo:'0',type:'1',PVideoNick:'<?=$cfg['config']['defvide0nick']?>',OtherVideoAutoPlayer:'<?=$cfg['config']['livetype']?>',r:'<?=$cfg['config']['rebots']?>',tiyantime:'<?=$cfg['config']['tiyantime']?>',fayanjiange:'<?=$cfg['config']['fayanjiange']?>',logintc:'<?=$cfg['config']['logintc']?>',bg:'<?=$cfg['config']['bg']?>'}
var grouparr=new Array();
<?=$grouparr?>
var ReLoad;
 var tbox;
var isIE=document.all;
var aColor=['#FFF','#FFF','#FFF'];
var msg_unallowable="<?=$msg_unallowable?>";
   if (typeof console == "undefined") {    }
    WEB_SOCKET_SWF_LOCATION = "script/WebSocketMain.swf";
    WEB_SOCKET_DEBUG = true;
    var ws, name, client_list={},timeid, reconnect=false;
    
</script>
    </head>


    <body>
        <body>
    <div class="watch-fl pull-left f-13">
        <div class="logo text-center"><a href="/"><img src="img/logo.png" alt="财讯直播" width="174" /></a></div>
        <div class="my-info clearfix">
        	<?php
            if($_COOKIE['uname']!=null && $_COOKIE['uid']!=null)
			{
			?>           
			 <div class="clearfix header-wrap">
                <a href="javascript:void(0);" class="pull-left header" onClick="openWin(2,false,'room/profiles.php?uid=<?=$userinfo['uid']?>',460,600)" >
                                <img class="head" src="../face/img.php?t=p1&u=<?=$userinfo['uid']?>" border="0" alt="头像" width="30" height="30"/>
                                </a>
                <div class="pull-left">
                    <a href="javascript:void(0);" class="pull-left nickname nowrap"  onClick="openWin(2,false,'room/profiles.php?uid=<?=$userinfo['uid']?>',460,600)" ><?=$_COOKIE['uname']?></a>
                </div>
                <a href="../logging.php?act=logout" class="exit pull-right" title="退出"></a>
            </div>
            <div class="wbtns clearfix">
                <a href="javascript:void(0);" onClick="openWin(2,false,'room/profiles.php?uid=<?=$userinfo['uid']?>',460,600)"  class="btn wbtn">我的信息</a>
                <a href="#" class="btn wbtn">主播信息</a>
            </div>
           <?php
			}else{
			?>
			<div class="wbtns clearfix">
                                <a href="javascript:void(0);" class="btn wbtn" data-toggle="modal"  onclick="openWin(2,false,'room/minilogin.php',390,360)">登录</a>
                                <a href="#" class="btn wbtn active" onclick="openWin(2,false,'room/minilogin.php?a=0',390,533)">注册</a>
            </div>
      <?php }?>
                    </div>
        <input type="text" class="tosearch f-12" placeholder="搜索" id="tosearch" />
        <div class="wbtns-wrap">
            <div class="wbtns clearfix">
                                <a href="#" class="btn wbtn">金融宝贝</a>
                                <a href="#" class="btn wbtn">股票</a>
                                <a href="#" class="btn wbtn">现货</a>
                                <a href="#" class="btn wbtn">期货</a>
                                <a href="#" class="btn wbtn">股指</a>
                                <a href="#" class="btn wbtn">其它</a>
                            </div>
        </div>
        <h6>人气直播<a href="#" class="pull-right f-13 showmore">更多</a></h6>
        <div class="erweima text-center">
                        <a href="./article.html" title="三物老师">
                        <img src="http://ccstatic01.e.vhall.com/upload/webinars/img_url/74/df/74dfd3c2dc0e0e8751ac6ddd0a6ffb2a.jpg?size=180x100" alt="汕头大学2016届毕业典礼" width="176" height="96"/>
                        </a>
            <p class="f-12" title="汕头大学2016届毕业典礼">汕头大学2016届毕...</p>
                    </div>
                    <div class="erweima text-center">
                        <a href="#" title="三物老师">
                        <img src="http://ccstatic01.e.vhall.com/upload/webinars/img_url/74/df/74dfd3c2dc0e0e8751ac6ddd0a6ffb2a.jpg?size=180x100" alt="汕头大学2016届毕业典礼" width="176" height="96"/>
                        </a>
            <p class="f-12" title="汕头大学2016届毕业典礼">汕头大学2016届毕...</p>
                    </div>
                    <div class="erweima text-center">
                        <a href="#" title="三物老师">
                        <img src="http://ccstatic01.e.vhall.com/upload/webinars/img_url/74/df/74dfd3c2dc0e0e8751ac6ddd0a6ffb2a.jpg?size=180x100" alt="汕头大学2016届毕业典礼" width="176" height="96"/>
                        </a>
            <p class="f-12" title="汕头大学2016届毕业典礼">汕头大学2016届毕...</p>
                    </div>
        <div>
            <a class="btn btn-light-red tocreate" href="#" target="_blank">我要发起直播</a>
        </div>
        <p class="text-center custom-service-btn"><a href="javascript:void(0);" data-toggle="modal" data-target="#ticketTips">直播说明</a><a href="javascript:void(0);" class="custom-service">客服</a></p>
    </div>
    <!--//left导航折叠***-->
        <div class="watch-fr pull-right">
        <div class="chat-area"  style="margin-top:30px;">
            <h4 class="f-14 text-center"></h4>
            <div class="chatlist-box mCustomScrollbar">
                <img id="loading-chat" src="img/loading.gif" width="18" style="display:none;margin:0 auto;" />
               
   <ul class="chatlist list-unstyled f-12" id="chatlist">
                        
    
      

<!--header end-->
  <div id="UI_Left" style="display:none">

    <div id="UI_Left2"  class="bg_png1">
 
     <!--<span id="OnlineUserNum"></span>-->
       
  <div  class="title_tab"> <a href="javascript:void(0)" class="bg_png2" onClick="bt_SwitchListTab('User')" id="listTab1">在线人数<font style="display: block;">()</font></a> <a href="javascript:void(0)" onClick="bt_SwitchListTab('Other')" id="listTab2"><?=($userinfo['gid']=='3'?"我的客户":"官方客服")?><font style="display: block;">(<span id="OnlineOtherNum"></span>)</font></a> </div>


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

  
    </div>
    
  </div>



  
  <div id="UI_Central"  class="bg_png">
    <div class="title_bar2">
     <span class="fl clearfix horn">公告：<i></i></span>
     <div class="notice-scroll fl" id="notice-scrollbox">
    <marquee scrollamount="3" id="msg_tip_show">
        <?php
        $query=$db->query("select * from {$tablepre}chatlog where  rid='".$rid."' and type='2'  order by id desc limit 1");
        while($row=$db->fetch_row($query)){
            echo "<span style='color:black'>".tohtml($row[msg])."</span>";
        }
        ?>
    </marquee>
         </div>
    </div>
    <div id="MsgBox" style="position:relative;">
           <div id='divnotice'></div>
           <!--<div class="dimensionCode" id="rotateMain">-->
            <!--<img src="/images/choujiang.png"><br>抽奖</div>-->
           <!--<div class="hongBao" title="红包"><img src="/images/hongbao.png"><br><span class="bagmoney">发红包</span></div>-->
        
      <div id="Video_List"></div>
      <div id="MsgBox1" style="overflow:auto; height:auto; float: left; padding:10px 6px 0px 6px;position:relative" >
        <?=$omsg?>
      </div>
      <div class="drag_skin" id="drag_bar" style=" display:none"></div>
      <div id="MsgBox2" style="height:100px; overflow:auto;  padding:0px 10px 0px 10px;position:relative; display:none" ></div>
    </div>
    <div id="UI_Control" class="tool_bar" >
      <div style="height:22px; line-height:22px; overflow: hidden; font-size:14px;overflow: hidden;">
    <!--<span  id="msg_tip_admin_show" >-->
    <?php
//      $query=$db->query("select * from {$tablepre}chatlog where  rid='".$rid."' and type='3'  order by id desc limit 1");
//      while($row=$db->fetch_row($query)){
//          echo "<span style='color:red;display:block;width:1000px;margin-left: 13px;margin-right: 13px;'>".tohtml($row[msg])."</span>";
//      }
    ?>
    <!--</span>-->
    </div>
    <!--<div id="qqbts"><p>-->
      <?php
//$query=$db->query("select realname from {$tablepre}members  where gid=3 and tuser='admin' order by lastactivity DESC limit 0,4 ");
//while($row=$db->fetch_row($query)){
//  $kefulist.="<a target='_blank' title='点击咨询' href='http://wpa.qq.com/msgrd?v=3&amp;uin={$row['realname']}&amp;site=qq&amp;menu=yes'>房间助理</a>";
//}
//echo $kefulist;
//?>
<!--</p></div>-->    

                     
              
   

<!-- <span style="margin-left:5px;">   对<div id="ToUser" class="lts_chat5">游客4cb6731a</div><span style="position: relative;"><div id="apDiv2" ></div></span>&nbsp;说</span>-->
 
        <!--  <input name="Personal" value="false" type="image" id="Personal" title="公聊/私聊" bt_Personal(this)" src="images/Personal_false.gif"/>-->
        <input  type="hidden" name="Personal" id="Personal" value="false" />
        <!--<input name="BTFont" type="image" id="BTFont" title="设置字体颜色和格式" onClick="bt_FontBar(this)" src="images/Font.gif"/>
        <input type="image" title="视频密聊" src="images/Vlove.gif" onclick="if(My.qx!=0||My.vip!=0)VideoList.Connect(ToUser.id,ToUser.name,0);else alert('你不是VIP用户！不能使用该功能');"/>

        <input type="image" title="声音提示" src="images/So.gif" id="toggleaudio" onClick="bt_toggleAudio();"/>-->
    </div>


</div>

<div id="FontBar" style="display:none">

 
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
<div id="face" style="display:none"></div>
<div id="caitiao" class="hid ption_a" style="display: none;"></div>
<form id="imgUpload" name="imgUpload" action="" method="post" enctype="multipart/form-data" target="e">
<input type="hidden" name="info" id="imgUptag" value="#Msg">
<input id="filedata" contenteditable="false" type="file" style="display:none;" onchange="$('#imgUpload').attr('action','../upload/upload_frame.php?act=InsertImg&' + new Date().getTime() );document.imgUpload.submit();" name="filedata">
</form>
<iframe name="e" id="e" style="display:none"></iframe>
<div id="tip_login_win" style="display:none">
<?php
if($cfg['config']['logintc']==1){
$query=$db->query("select realname from {$tablepre}members  where gid=3 and realname!=''   order by lastactivity DESC  ");
while($row=$db->fetch_row($query)){
    $row['realname']=  trim($row['realname']);
    $t_kefulist.="

    <li> 
<a target=\"_blank\" href=\"http://wpa.qq.com/msgrd?v=3&amp;uin={$row['realname']}&amp;site=qq&amp;menu=yes\" ><img border=\"0\" style=\"vertical-align:middle\" src=\"http://wpa.qq.com/pa?p=2:{$row['realname']}:41\" alt=\"{$row['realname']}\" title=\"请加QQ：{$row['realname']}\" /></a> 
</li>
";
}
    
$tipopen='<style>.kf_content {position: absolute;top: 50%;left: 50%;width: 800px;height: 285px;margin: -140px 0 0 -400px;color: #f00;z-index: 999;background: url('.$cfg['config']['tipimg'].') no-repeat;}';
$tipopen.='.kf_content div {position: relative;}.kf_content div img#cls {position: absolute;width: 20px;height: 20px;top: 0px;right: 0px;overflow: hidden;text-indent: -99px;cursor: pointer;}#kfpn {margin-top: 215px;padding: 10px;}#kfs {text-align: center;display: block;width: 770px;}#kfpn li {float: left;height: 28px;line-height: 28px;width: 95px;list-style-type: none;display: inline-block;}#kfpn li a {margin-top: 0px;margin-right: 2px;padding-left: 12px;}#kfpn li img {height: height:22px;width: 77px;}</style> ';
$tipopen.='<div class="kf_content" id="kf_content"><div><img id="cls" onclick="layer.close(tipopen);" src="/upload/upfile/day_150605/close.gif" alt="" /></div><div id="kfpn"><div id="kfs">{kefulist}</div></div></div>';
    echo str_replace("{kefulist}",$t_kefulist,$tipopen);
}
?>
</div>



                      

                </ul>
            </div>
           







         
            <div class="send-msg f-12">
                   <!--  <textarea class="mywords"  id="Msg1"  style= "resize:none; " name="mywords" placeholder="说点儿什么吧" maxlength="140" ></textarea> 
                    <div class="clearfix btns">
                        <button class="btn btn-light-red sendmsg pull-right f-12" id="sendBt" >发送</button>
                        <a href="javascript:void(0);" class="pull-right expression" title="表情"></a>
                    </div> -->


    <div  class="inputmsg">
      <div class="liuyan_huang">
          <div id="Msg" contentEditable="true"  class="mywords" onClick="HideMenu();"></div>
          <div style=" width:30px; float:right;"><input name="Send_bt" type="image" class="btn btn-light-red sendmsg pull-right f-12" id="Send_bt" title="发送" onclick="SysSend.msg()"/> </div>
      </div>

      <div >


      
        <!-- <button class="btn-input" name="Send_bt" id="Send_bt" style="border:1px solid red; width:20px;height:30px; float:left"></button> -->
        <input type="hidden" name="Send_key" id="Send_key" value="1" />
        
     <span id="bar_list" style="float:left; width:100%;">
       
        <!-- <a href="javascript:void(0)" class="bar_6 bar"  id="openPOPChat" style="float:right" >我的私聊</a> -->
        
        <a href="javascript:void(0)" id="bt_face" title="贺强1" class="bar_2 bar" onclick="showFacePanel(this,'#Msg');" isface="2"><i class="iconfont"></i></a>
        <a href="javascript:void(0)" class="bar_3 bar" title="贺强2" id="bt_caitiao"><i class="iconfont"></i></a>
        <a href="javascript:void(0)" class="bar_1 bar" title="贺强3" id="bt_myimage" onclick="bt_insertImg('#Msg')"><i class="iconfont"></i></a>
        <!-- <? if($room_admin){?>  <a href="javascript:void(0)" class="bar_1 bar" id="bt_myimage" onclick="bt_rollnotice()"><i class="iconfont"></i></a><?}?> -->
               
                <!-- <a href="javascript:void(0)" class="bar_4 bar" id="bt_qingping"  onClick="bt_MsgClear();">清屏</a>-->
         <!--<a href="javascript:void(0)" class="bar_5 bar" id="bt_gundong" select="true"  onClick="bt_toggleScroll()" >滚动</a>-->
                
     <!--    <div class="tool_div">
                    <div id="myimage" class="hid ption_a hongbao" onmouseout="_toolTimeRed()" onmouseover="_toolCloseTimeRed()" style="display: none;width: 243px;"></div>
                <a class="bar_8 bar" id="bt_myimage" onmouseout="_toolTimeRed()" onmouseover="_toolCloseTimeRed()" onclick="user_hb()">鲜花：<font id="redbags"><?=$userinfo['redbags']?></font></a>
        </div> -->
        <div id="Y_pub_Tools">
                    <a href="javascript:void(0)" class="qingkong" onclick="bt_MsgClear();" title="贺强4" ><span class="clear" style="height:20px; width:20px; display:inline-block;"></span></a>
                    <a href="javascript:void(0)" class="gundong" onclick="bt_toggleScroll();" title="贺强5" ><span class="scroll" id="bt_gundong" style="height:20px; width:20px; display:inline-block;"></span></a>
        </div>      
    </span>    
    


      
    </div>
    <div id="manage_div">
      <select id="chat_type" style="display:none">
        <option value="me" selected>发言人-自己</option>
        <option value="he" title="当前聊天">发言人-他人</option>
      </select>
        &nbsp;&nbsp;
      <label>
      <!-- <input type="checkbox" id="msg_tip">置顶公告</label>&nbsp;&nbsp; -->
      <!-- <input type="checkbox" id="msg_tip_admin">管理提示</label>&nbsp;&nbsp;&nbsp;&nbsp; -->
      <? if($room_admin){?>  <a href="javascript:void(0)" style="color:red;" id="bt_myimage" onclick="bt_automsg()">自动发言</a><?}?>
    </div>
  <!-- </div> -->
            </div>



        </div>
    </div>
        <div class="arrow-left">&lt</div><div class="arrow-right">&gt</div>    <div class="watch-box clearfix mCustomScrollbar" >
        <div>




    <h3 class="pull-left"><?=$cfg['config']['title']?></h3>
    <a href="javascript:;" class="report pull-left" title="举报" data-toggle="modal" data-target="#myReport">举报</a>
    <div style="clear:both"></div>
</div>
<div class="f-13 shareinfo clearfix">
    <!--<p class="time">
        <span class="event-start-time">2016-07-16 09:00</span>
    </p>-->
    <p class="pull-left">
        主播：<span class="hoster"><a href="#"><?=$cfg['config']['defvideonick']?></a></span> </p>
        <div class="take-attention pull-left">
              <?php 
                  $rid =explode('@', $_COOKIE['user_roomid']);
                  $curid=intval (substr($_SERVER['REQUEST_URI'], 1));
              if(in_array($curid, $rid)){

              ?>
               <button class="btn btn-light-red follow-someone  pull-left attentions" uid="12610441">已关注</button>
              <?php
                  }else{ ?> 
                         <button class="btn btn-light-red follow-someone  pull-left attentions" uid="12610441"><span class="add">+</span>关注</button>
                  <?php }?>
               <button class="btn btn-light-red private-msg  pull-left"  uid="12610441" receiver="三物老师" data-toggle="modal" data-target="#sendMsg">私信</button>
    
        <div class="attention">
            <div class="attention-style"><?=$cfg['config']['follows']?></div>
            <div class="attention-s"></div> 
        </div>
        </div>


 <script type="text/javascript">
     var aa='<?php echo $_COOKIE['uid']?$_COOKIE['uid']:null?>';
     var bb='<?php echo $_COOKIE['uname']?$_COOKIE['uname']:null?>';

    var url = window.location.href;
    var roomid=parseInt(url.substring(url.length-1));
   
   $(function(){

       $('.attentions').click(function(){
            if(aa==''||bb==''){
                   layer.msg('您还没有注册账号！',{shift: 6});return false;
            }else{

                     $.ajax({
                              type: 'get',
                              dataType: 'jsonp',
                              cache: false,
                              url: 'http://user.good100.top/api/user_follow',
                              data: {'uid':aa, 'roomid':roomid},
                              success: function(data){
                                
                                  console.log(data);
                                  if(data.status==3){ //首次关注

                                        return;
                                  }

                                   $.post('./include/follows.func.php',{'follows':1,'roomid':roomid},function(data){
      
                                            if(data.status==false){return false;}else{
                                              $('.attention-style').html(parseInt($('.attention-style').html())+1);
                                              $('.add').html('已关注');
                                            };           

                                   },'json');   

                              }
                      });

              

            }
       }) 
   })
 </script>



    <div class="pull-right" >
                        <span class="total status" title="观看量" id="OnlineUserNum" ></span>
            </div>
    <div class="pull-right appdownload-box">
        <a href="javascript:void(0);" class="appdownload v-gray dropdown-toggle" data-toggle="dropdown" aria-expanded="true" >下载app观看</a>
        <div class="dropdown-menu appdownload-code" role="menu">
            <i class="ico-arw"></i>
            <img src="<?=$cfg['config']['ewm']?>" width="115" />
            <p class="red f-12 text-center">扫描二维码下载APP<br/>观看更多直播</p>
        </div>
    </div>
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

<!-- <script src="ui/jquery/1.11.2/jquery.min.js"></script> -->
<script>
    var searchPageUrl='http://www.caixun.com/search';
    $("button.attentions").hover(function(){
        $(".attention").show();
    },function(){
        $(".attention").hide();
    });
</script>
        <div class="video-doc-box">
            <div class="video-doc">

                <div class="video-box ">
                 <span class="video-execption" style="display: inline;"><a href="javascript:showLive()" id="video-flash">刷新视频</a></span>                          
                	
                    <div id="OnLine_MV" style="width: 100%;height: 96%;">
                        
      <?php 
           $curid=intval (substr($_SERVER['REQUEST_URI'], 1));
           $query=$db->query("select livefp from {$tablepre}config where id='$curid'");
            while($row=$db->fetch_row($query)){
               $txt2=tohtml($row['livefp']);
            }
      ?>          <?=$txt2?>
                   </div>
                </div>
                    
            </div>
            <div class="toolbar f-12 clearfix">
                <p class="pull-right">
                    <!-- [暫時屏蔽] span class="online status">50</span-->
                    <a href="javascript:;" class="rss-pay status" title="打赏" ></a>
                    <span class="rss-reward-down"><img class="" src="http://ccstatic01.e.vhall.com/static/img/reward-pay-down.png"/></span>
                                        <span style="display: inline-block;width: 12px;"></span>
                    <!-- [暫時屏蔽] span class="rss-total">200</a></span-->
                </p>
                <ul class="funlist clearfix">
                    <!-- [暫時屏蔽] li>
                        <a href="#" class="embed dropdown-toggle" data-toggle="dropdown" aria-expanded="true" title="分享嵌入"></a>
                        <ul class="dropdown-menu share-menu v-gray f-12" role="menu">
                            <i class="ico-arw-bottom"></i>
                            <li><label>直播间地址：</label><pre>http://e.vhall.com/256858816</pre></li>
                            <li><label>视频嵌入地址：</label><pre>http://e.vhall.com/256858816</pre></li>
                            <li><label>聊天嵌入地址：</label><pre>http://watch.vhall.com/2134546</pre></li>
                        </ul>
                    </li-->
                    <!--<li><a href="#" class="Ind-mode" title="独立模式"></a></li>-->

                    <!--
                    <li><a href="#" class="send-p-msg" title="发私信" uid="12610441" receiver="21世纪经济报道" data-toggle="modal" data-target="#sendMsg"></a></li>
                -->
                    <li><a href="#" class="watch-share" title="分享" uid="12610441"  data-toggle="dropdown" aria-expanded="true" receiver="三物老师" data-toggle="modal">分享</a>
                        <div class="menu-share dropdown-menu watch-share-body" role="menu" id="watch-share">
                            <div class="watch-share-div">
                                <div class="watch-share-title">
                                    分享链接：
                                    <div class="pull-right zeroclipboard-is-hover copy-button margin-left-15" data-clipboard-target="link_text4" data-clipboard-target="link_text4">
                                        <button type="button" data-dismiss="modal" class="btn btn-light-red cancel  f-12">复制</button>
                                    </div>
                                    <p class="pull-right code">
                                        <input type="input"  id="link_text4" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>"/>
                                    </p>

                                </div>
                                <div class="watch-share-content">
                                    分享到：
                                    <div class="pull-right" >
                                        <a class="jiathis_button_tsina"><img src="http://ccstatic01.e.vhall.com/static/img/sina_login.png" width="24" alt="" /></a>
                                        <a class="jiathis_button_qzone"><img src="http://ccstatic01.e.vhall.com/static/img/qq_login.png" width="24" alt="" /></a>
                                        <a class="jiathis_button_weixin"><img src="http://ccstatic01.e.vhall.com/static/img/weixin_login.png" width="24" alt="" /></a>
                                    </div>
                                </div>
                            </div>
                            <i class="triangle-up"></i>  
                        </div>
                    </li>
                    <li>
                        <a href="#" class="code dropdown-toggle" data-toggle="dropdown" aria-expanded="true" title="app扫码观看">扫码观看</a>
                        <span class="appcode dropdown-menu" role="menu">
                            <i class="ico-arw-bottom"></i>  
                            <img src="#" alt="app扫码观看" width="101">
                        </span>
                    </li>
                    <li><a href="#" class="feedback" title="问题反馈" data-toggle="modal" data-target="#myFeedback">问题反馈</a></li>

                    <!--
                    <li><a href="#" class="report" title="举报" data-toggle="modal" data-target="#myReport"></a></li>
                -->
            </ul>   
            </div>
        </div>
        
         <?php
         $curid=intval (substr($_SERVER['REQUEST_URI'], 1));
    $query=$db->query("select * from {$tablepre}notice where  id='1'  and type='1' and roomid='$curid' ");
	while($row=$db->fetch_row($query)){
		$tab.="<a href='javascript:void(0)' id='notice_{$row[id]}'>{$row[title]}</a>";
		$txt.="<div id='notice_{$row[id]}_div' class='con f-13' >".tohtml($row['txt'])."</div>";
	}
	
	?>

  <div class="event-info">
    <menu class="tab-btns"><a href="javascript:void(0)" class="active">平台介绍</a><a href="javascript:void(0)" >版权申明</a></menu>
    <div>
        <div class="tab-content" style="display: block;">
            <div class="con f-13">
                               <?= $txt?>
            </div>
            <div class="ad">
                <h4>官方活动</h4>
                <ul class="ad-list">
                  <li><a href="#"><img src="img/gg-img-ad.jpg" class="mCS_img_loaded"></a></li>
                  <li><a href="#"><img src="img/gg-img-ad.jpg" class="mCS_img_loaded"></a></li>
                  <li><a href="#"><img src="img/gg-img-ad.jpg" class="mCS_img_loaded"></a></li>
                </ul>
            </div>
        </div>  
         <div class="tab-content" style="display: none;">
            <div class="con f-13">
                              dsdsdsddass
            </div>
            <div class="ad">
                <h4>官方活动</h4>
                <ul class="ad-list">
                  <li><a href="#"><img src="img/gg-img-ad.jpg" class="mCS_img_loaded"></a></li>
                  <li><a href="#"><img src="img/gg-img-ad.jpg" class="mCS_img_loaded"></a></li>
                  <li><a href="#"><img src="img/gg-img-ad.jpg" class="mCS_img_loaded"></a></li>
                </ul>
            </div>
        </div> 
    </div>
</div>

<script>
	$('.tab-btns a:first').addClass('active');
	$('.notice_div:first').css('display','inline-block');
	$('.tab-btns a').on('click',function(){
            if($(this).attr("id")=='app_1'){return;}
		$('.tab a').removeClass('active');
		$(this).addClass('active');
		$('.notice_div').css('display','none');
		$('#'+$(this)[0].id+'_div').css('display','');
	});
	
</script> 



</div>
    <!--div id="name-card-info" class="f-12">
        <a href="javascript:void(0);" class="pull-left header"><img src="http://ccstatic01.e.vhall.com/static/img/head50.jpg" alt="" width="54" class="head"></a>
        <div class="pull-left">
            <p class="clearfix">
                <a href="javascript:void(0);" class="nickname nowrap pull-left f-14" title="yang">yang</a>
            </p>
            <p class="btn-box">
                <button class="btn btn-light-red btn-h-22 follow-someone"><span class="add">+</span>关注</button>
                <a href="javascript:void(0);" class="private-chat" title="发私信" data-toggle="modal" data-target="#sendMsg">私信</a>
            </p>
        </div>
    </div-->
    <div class="sharetypes text-center f-12" id="shareout-div">
        <i class="ico-arw"></i>
        <div class="share-jiathis" style="overflow:hidden;">
            <a class="jiathis_button_tsina" title="分享到新浪微博"><img src="img/sina_login.png" alt="" /><span>新浪微博</span></a>
            <a class="jiathis_button_qzone" title="分享到QQ空间"><img src="img/qq_login.png" alt="" /><span>QQ好友</span></a>
            <a class="jiathis_button_weixin" title="分享到微信"><img src="img/weixin_login.png" alt="" /><span>微信好友</span></a>
        </div>
    </div>
    <div class="modal fade bs-example-modal-sm watch-dialog" id="sendMsg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">发私信</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
                <div class="form-group">
                    <label for="msg-receiver" class="col-sm-2 control-label">发给：</label>
                    <p class="col-sm-9 no-padding" id="msg-receiver"></p>
                </div>
                <div class="form-group">
                    <label for="msg-con" class="col-sm-2 control-label">内容：</label>
                    <div class="col-sm-9 no-padding">
                        <textarea class="form-control" id="msg-con" maxLength="200" placeholder="请输入想对主持人说的话（1-200字）"></textarea>
                    </div>
                </div>
                <div class="form-group text-right">
                    <button type="button" class="btn btn-light-red f-12 btn-submit">发送</button>
                    <input type="hidden" name="sendid" id="sendid" value="<?=$_COOKIE['uid']?>" />
                    <input type="hidden" name="uname" id="uname" value="<?=$_COOKIE['uname']?>" />
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade bs-example-modal-sm" id="loginBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
           <div class="login-box v-gray pull-right" id="common_login">
                <h3 class="f-16">登录<a href="#" class="v-color f-14 pull-right to-reg">注册帐号</a></h3>
                <form class="form-horizontal" role="form" method="POST" action="room/minilogin.php?action=login">
                    <input type="hidden" name="_token" value="1nmIt1HNFzmsF7w3HBV7lGM8Aiqr88Fp8XN31f4Y">
                                        <div class="input-group">
                      <input type="text" class="form-control" id="username" name="account" placeholder="输入您的账号/邮箱/手机号" aria-describedby="" value="">
                    </div>
                    <div class="input-group">
                      <input type="password" class="form-control" id="pwd" name="password" placeholder="输入您的密码" aria-describedby="">
                    </div>
                    <!--
                    <div class="form-group clearfix">
                        <input type="text" class="form-control pull-left v-code" name="captcha" placeholder="输入验证码" />
                        <p class="code-box"><img src="http://e.vhall.com/captcha/default?1eBXQn5Z" id="code" onclick="javascript:this.src='http://e.vhall.com/captcha/default?xWPkSSTv&tm='+Math.random()"></p>
                    </div>
                    -->
                    <div class="form-group clearfix">
                        <input type="checkbox" id="auto-login" name="remember" class="pull-left" /><label class="control-label f-12 control-auto-login" for="auto-login" >下次自动登录</label>
                        <a href="#" class="v-gray f-12 pull-right forget-pwd">忘记密码？</a>
                    </div>
                    <div>
                        <button type="button" class="btn btn-block btn-v-red btn-submit" id="to-login">登录</button>
                    </div>
                </form>
                <div class="other-login text-center">
                    <p class="other-title">第三方登录<p>
                    <div class="">
                        <a href="#"><img src="img/sina_login.png" alt=""></a>
                        <a href="#"><img src="img/qq_login.png" alt=""></a>
                        <a href="#"><img src="img/weixin_login.png" alt=""></a>
                    </div>
                </div>
            </div>  
        </div>
    </div>
    <div class="modal fade bs-example-modal-sm watch-dialog" id="myFeedback" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">问题反馈</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
                <div class="form-group f-12">
                    <div class="col-sm-3">
                        <input type="checkbox" name="feedback">
                        <label class="feedback-item">卡顿</label>
                    </div>
                    <div class="col-sm-3 no-padding black">
                        <input type="checkbox" name="feedback">
                        <label class="feedback-item">黑屏</label>
                    </div>
                    <div class="col-sm-3 no-padding black">
                        <input type="checkbox" name="feedback">
                        <label class="feedback-item">模糊</label>
                    </div>
                    <div class="col-sm-4 no-padding">
                        <input type="checkbox" name="feedback">
                        <label class="feedback-item">声音不同步</label>
                    </div>
                </div>
                <div class="form-group">
                        <textarea class="form-control" id="feedback-con" placeholder="如遇其他问题，请具体描述~~"></textarea>
                </div>
                <div class="form-group text-center">
                    <button type="button" class="btn btn-light-red f-12">确定</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade bs-example-modal-sm watch-dialog" id="myReport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">举报该活动</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
                <div class="form-group f-12">
                    <div class="col-sm-8">
                        <input type="radio" name="report" value="1">
                        <label class="report-item">传播色情、暴力、反动等违法不良信息</label>
                    </div>
                    <div class="col-sm-4 no-padding black">
                        <input type="radio" name="report" value="2">
                        <label class="report-item">欺诈</label>
                    </div>
                    
                </div>
                <div class="form-group f-12">
                    <div class="col-sm-4 no-padding">
                        <input type="radio" name="report" value="3">
                        <label class="report-item">传销</label>
                    </div>    
                    <div class="col-sm-4 no-padding">
                        <input type="radio" name="report" value="4">
                        <label class="report-item">邪教</label>
                    </div>
                    <div class="col-sm-4 no-padding black">
                        <input type="radio" name="report" value="5">
                        <label class="report-item">其他</label>
                    </div>
                </div>
                <div class="form-group">
                        <textarea class="form-control" id="report-con" placeholder="如遇其他问题，请具体描述~~"></textarea>
                        <p class="tips red f-12">请填写您要举报的问题</p>
                </div>
                <div class="form-group text-center">
                    <button type="button" class="btn btn-light-red f-12">确定</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
        <div class="modal fade bs-example-modal-sm in del-dialog" id="reward" style="margin-top:150px;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body text-center">
                <div class="form-group">
                    <p class="form-control-static f-12 text-center text">
                        <input name="" id="payAmount" type="text" placeholder="请输入金额(1~1000元)"/>
                    </p>
                </div>
                <div class="form-group form-control-static">
                    <input name="" id="payNote" type="text" placeholder="很精彩，赞一个！" maxLength="15"/>
                </div>
                <div class="form-group pay-btn">
                    <a href="javascript:;" class="pull-right btn btn-light-red confirm f-12 ali-pay">支付宝支付</a>
                    <a href="javascript:;" class="btn btn-light-red confirm f-12 wx-pay">微信支付</a>
                </div>
          </div>
           <div id="wxpay-qrcode">
                <img src='#'/>
                <div>请用微信扫描二维码</div>
            </div>
        </div>
      </div>
    </div>

<script src="js/jquery.min.js" type="text/javascript"></script>
<!--<script src="./js/common.js" type="text/javascript"></script>-->
<script type="text/javascript" src="./js/jquery.placeholder.min.js"></script>
 <script src="./css/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
<script src="./js/jquery.mCustomScrollbar.concat.min.js" type="text/javascript"></script>
<!--<script src="./js/swf.js" type="text/javascript"></script>
<script src="./js/base64.js" type="text/javascript"></script>-->
<script src="./js/caixunapp.js"></script>
<script src="./js/jquery-migrate.min.js"></script>
<script src="./js/jquery.hashchange.min.js" type="text/javascript"></script>
<!--<script src="./js/record.js" type="text/javascript"></script>-->



<script src="./js/pageInit.js" type="text/javascript"></script>
<script>
        vhallApp.sendMsg('/room/UserCenter/addletter.php');
        $("#to-exchange").click(function(){
            $(".video-box,.doc-box").toggleClass("exchange");
        });
      
  
        //反馈和举报
                $('.rss-pay').click(function(){
             
                        $('#loginBox').modal('show');
            return false;
                                        
            $('#reward').modal('show');
            $('#payAmount').focus().blur();
            $('#reward .modal-body').css('visibility','visible');
            $('#reward input').val('');
            $('#wxpay-qrcode').hide();
        });
        $('.rss-pay').hover(function(){
            //$(".rss-reward-down").stop().slideDown();
            $(".rss-reward-down").show().stop().animate({'margin-top':"20px"},500,function(){
                $(".rss-reward-down").animate({'margin-top':'0px'},200,function(){
                    $(".rss-reward-down").animate({'margin-top':"20px"},200);
            });
            });
        },function(){
            $(".rss-reward-down").stop().animate({'margin-top':'-10px'},600,function(){$(".rss-reward-down").hide();});
        });

        $('#reward .pay-btn').on('click','.btn',function(){
            var reg = /^\d{0,4}\.{0,1}(\d{1,2})?$/,val = $("#payAmount").val(),note = $("#payNote").val();
            if($.trim(val)<=0||!reg.test($.trim(val))||parseInt(val)>1000){
                $("#payAmount").addClass("warning").val("请输入1~1000之间数字");
                return false;  
            }
            var type = 0; // 阿里支付
            if($(this).hasClass("wx-pay")){
                //微信支付
                type = 1;
            }
            if($.trim(note) === ''){
                note = $("#payNote").attr("placeholder");
            }
            $.ajax({
                url : 'http://e.vhall.com/pay/rewardpay',
                data : {"fee":val,"webinar_id":'256858816','type':type,"note":note},
                async : false,
                type :'post',
                success : function(res){
                    if(parseInt(res['code']) === 200){
                        console.log(res);
                        getComment_reward = setInterval(function(){
                            if(getComment_reward_count > 10){
                                clearInterval(getComment_reward);
                            }
                            getComment_reward_count++;
                            getComment(true,1);
                        },5000);
                        if(type){
                            $('#reward .modal-body').css('visibility','hidden');
                            $('#wxpay-qrcode').show(); 
                            $(

                                '#wxpay-qrcode img').attr('src',res.data.url);
                        }else{
                            window.open(res.data.url);
                        }
                        if(pageinfo.userinfo_id === ''){
                            pageinfo.userinfo_id = res.data.user_id;
                        }
                    }
                }
            });
        });
      

      



    $("#myFeedback .btn").click(function(){
        watchApp.sendReport($("#myFeedback"),'feedback',$("#feedback-con"),'http://e.vhall.com/api/webinar/v1/webinar/feedback');
    }); 
    $("#myReport .btn").click(function(){
        watchApp.sendReport($("#myReport"),'report',$("#report-con"),'http://e.vhall.com/api/webinar/v1/webinar/report');
    });

   


</script>
<!-- <script type="text/javascript" src="./js/ZeroClipboard.min.js" charset="utf-8"></script> -->
<!--//分享复制-->
<script>
    $(document).ready(function(e) {
        if(typeof ZeroClipboard ==='undefined'){
            $('.copy-button').on('click',function(){
                alert('您的浏览器版本过低，不支持复制功能请手动复制！');
            })
        }else{
            zeroclient=new ZeroClipboard($('.copy-button'));
            zeroclient.on("ready",function(client,args){
                console.log('ready copy');
            });
            zeroclient.on("aftercopy",function(client,args){
                vhallApp.showMsg('复制成功!');
                //setTimeout(function(){ $('#copy_tip').html(''); },3000);
            });
        }
    });
    $('#watch-share .code').on('click',function(e){e.stopPropagation();})
</script>



<script src="room/script/jquery.danmu.min.js"></script>
<script src="room/script/nuoyun.js"></script>
</body>
</html>
