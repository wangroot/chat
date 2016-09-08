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
//游客
if($_SESSION['login_uid']==0){$userinfo['username']=$userinfo['nickname']=$_SESSION['login_nick'];$userinfo['sex']=$_SESSION['login_sex'];$userinfo['uid']=$_SESSION['login_guest_uid'];$userinfo['fuser']=$_SESSION['guest_fuser'];$userinfo['redbags']='0';}

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
		//$omsg="<div style='clear:both;'></div><div class='msg'  id='{$row[msgid]}'><div class='msg_head'><img src='".$group["m".$row[ugid]][ico]."' class='msg_group_ico' title='".$group["m".$row[ugid]][title]."-".$group["m".$row[ugid]][sn]."'></div><div class='msg_content'><div><font class='u'  onclick='ToUser.set(\"{$row[uid]}\",\"{$row[uname]}\")'>{$row[uname]}</font> &nbsp;&nbsp;  <font class='dui'>对</font> <font class='u' onclick='ToUser.set(\"{$row[tuid]}\",\"{$row[tname]}\")'>{$row[tname]}</font> 说 <font class='date'>[".date('H:i:s',$row[mtime])."]</font></div><div class='layim_chatsay' style='margin:5px 0px;'><font  style='{$row[style]};'>{$row[msg]}</font><em class='layim_zero'></em></div></div></div><div style='clear:both;'></div>".$omsg;
             $omsg="<div style='clear:both;'></div><div class='lts_right3' id='{$row[msgid]}' fromconnid=''><span class='time'>".date('H:i:s',$row[mtime])."</span><img src='".$group["m".$row[ugid]][ico]."' title='".$group["m".$row[ugid]][title]."-".$group["m".$row[ugid]][sn]."' class='RoomUserRole'><a class='name' href='javascript:void(0)' onclick='ToUser.set(\"{$row[uid]}\",\"{$row[uname]}\")'>{$row[uname]}</a><font class='dui'>对</font><a class='name' href='javascript:void(0)' onclick='ToUser.set(\"{$row[tuid]}\",\"{$row[tname]}\")'>{$row[tname]}</a><span class='to_m' style='{$row[style]};'>{$row[msg]}</span></div>".$omsg;
             }
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
if(!isset($_SESSION['room_'.$uid.'_'.$cfg['config']['id']])){
    $laiyuan=$_SERVER['HTTP_REFERER'] ; //获取访客来源url
    if($laiyuan==''){$laiyuan='直接输入网址或打开标签'; }
$db->query("insert into  {$tablepre}msgs(rid,ugid,uid,uname,tuid,tname,mtime,ip,msg,`type`,laiyuan)values('{$cfg[config][id]}','{$userinfo[gid]}','{$userinfo[uid]}','{$userinfo[username]}','{$cfg[config][defvideo]}','{$cfg[config][defvideonick]}','".gdate()."','{$onlineip}','进入直播间','3','{$laiyuan}')");
$_SESSION['room_'.$uid.'_'.$cfg['config']['id']]=1;
}
//是否为房间管理
$room_admin=check_auth('room_admin');
?>
<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="UTF-8">
		<title>讯财直播</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<link rel="stylesheet" href="css/common.css" />
		<link rel="stylesheet" href="css/index.css" />
		<link rel="shortcut icon" type="image/x-icon" href="/upload/upfile/day_160316/201603161433526577.ico" />
		<!--[if lt IE 9]>
<link href="skins/qqxiaoyou/less.css" rel="stylesheet" type="text/css">
<![endif]-->
<script src="room/script/jquery.min.js"></script>
  <script type="text/javascript" src="room/script/swfobject.js"></script>
  <script type="text/javascript" src="room/script/web_socket.js"></script>
<script src="room/script/layer.js"></script>
<script src="room/script/jquery.nicescroll.min.js"></script>
<script src="room/script/pastepicture.js"></script>
<script src="room/script/function.js?<?=time()?>" type="text/javascript" charset="utf-8"></script>
<script src="room/script/init.js?<?=time()?>" type="text/javascript" charset="utf-8"></script>
<script src="room/script/device.min.js"></script>

<script>
layer.config({extend: ['skin/layer.ext.css']});
var UserList;
var ToUser;
var VideoLoaded=false;
var My={dm:'<?=$_SERVER['HTTP_HOST']?>',rid:'<?=$rid?>',roomid:'<?=$rid?>',chatid:'<?=$userinfo['uid']?>',name:'<?=$userinfo['username']?>',nick:'<?=$userinfo['nickname']?>',sex:'<?=$userinfo['sex']?>',age:'0',fuser:'<?=$userinfo['fuser']?>',qx:'<?=$room_admin?'1':'0'?>',ip:'<?=$onlineip?>',vip:'<?=$userinfo['fuser']?>',color:'<?=$userinfo['gid']?>',cam:'<?=$userinfo['face']?>',state:'0',mood:'<?=$userinfo['mood']?>',rst:'<?=$time?>',camState:'1',key:'<?=connectkey()?>',redbags_num:'<?=$userinfo['redbags']?>'}

var RoomInfo={loginTip:'<?=$cfg['config']['logintip']?>',Msglog:'<?=$cfg['config']['msglog']?>',msgBlock:'<?=$cfg['config']['msgblock']?>',msgAudit:'<?=$cfg['config']['msgaudit']?>',defaultTitle:document.title,MaxVideo:'10',VServer:'<?=$cfg['config']['vserver']?>',VideoQ:'',TServer:'<?=$ts[0]?>',TSPort:'<?=$ts[1]?>',PVideo:'<?=$cfg['config']['defvideo']?>',AutoPublicVideo:'0',AutoSelfVideo:'0',type:'1',PVideoNick:'<?=$cfg['config']['defvide0nick']?>',OtherVideoAutoPlayer:'<?=$cfg['config']['livetype']?>',r:'<?=$cfg['config']['rebots']?>',tiyantime:'<?=$cfg['config']['tiyantime']?>',fayanjiange:'<?=$cfg['config']['fayanjiange']?>',logintc:'<?=$cfg['config']['logintc']?>',bg:'<?=$cfg['config']['bg']?>'}
var grouparr=new Array();
<?=$grouparr?>
var ReLoad;
 var tbox;
var isIE=document.all;
var aColor=['#FFF','#FFF','#FFF'];
var msg_unallowable="<?=$msg_unallowable?>";
   if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
    WEB_SOCKET_SWF_LOCATION = "room/script/WebSocketMain.swf";
    WEB_SOCKET_DEBUG = true;
    var ws, name, client_list={},timeid, reconnect=false;
	
</script>
	<body>
	<!--head-->
		<div class="header">
			<div class="top">
				<div class="nav">
					<div class="logo"><img src="images/logo.jpg" alt="logo" /></div>
					<ul>
						<li class="on"><a	href="/" target="_blank" title="首页">首页</a></li>
						<li><a href="/room/inputroom.html" target="_blank" title="直播">直播</a></li>
						<li><a href="/room/ico.php" target="_blank" title="保存桌面" id="bczm">保存桌面</a></li>
						<script>
function fake_click(obj) {
    var ev = document.createEvent("MouseEvents");
    ev.initMouseEvent(
        "click", true, false, window, 0, 0, 0, 0, 0
        , false, false, false, false, 0, null
        );
    obj.dispatchEvent(ev);
}
 
function export_raw(name, data) {
   var urlObject = window.URL || window.webkitURL || window;
 
   var export_blob = new Blob([data]);
 
   var save_link = document.createElementNS("http://www.w3.org/1999/xhtml", "a")
   save_link.href = urlObject.createObjectURL(export_blob);
   save_link.download = name;
   fake_click(save_link);
}
var test=document.getElementsByTagName('html')[0].outerHTML;
console.log(test);
$('bczm').click(function() {
export_raw('讯财直播.html', test);
});
</script>
					</ul>
				</div>
                <div class="search-input">
            <form id="search-form" action="/">
                <div class="search-div">
                <input class="search" type="text"  name="key" placeholder="搜索房间号/ID" id="roomName"/>
                <a href="javascript:void(0); " type="submit" onclick="()">进入房间</a>
                </div>
            </form>
                </div>
                <div class="nav-login">
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
                	<a href="javascript:void(0)" class="login" onClick="openWin(2,false,'room/minilogin.php',390,310)">登录</a>
                	<a href="javascript:void(0)" class="reg" onClick="openWin(2,false,'room/minilogin.php?a=0',390,533)">注册</a>
                	<a class="action" href="#" target="_blank">开始直播</a>
                	<a class="action" href="#" target="_blank">申请房间</a>
                	<?php }?>
                </div>
			</div>
		</div>
			<script type="text/javascript" src="./room/script/jquery.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$('#search-form').submit(function(){
				var roomName = $('#roomName').val();
				window.location.href = '/' + roomName;
				return false;
			});
		});
	</script>
<!--header end-->
<!--main-->
<div class="main">
	<div class="live-wrap">
                <div class="liveshow" style="background-image:url(images/live-back.png)">
                    <div class="layout">
                        <div class="video_chat">
                        <div id="video">
                            <div class="loadbox">
                                <a href="#" onclick="javascript:return false;" id="loadingflash"></a> 
                            </div>
                            <div id="swf">
                            	<div id="player" style="width:830px;height:550px;">
    <script type="text/javascript" charset="utf-8" src="http://yuntv.letv.com/player/live/blive.js"></script>
    <script>
        var player = new CloudLivePlayer();
        player.init({activityId:"A2016062800002ip"});
    </script>
</div>

                            	<!--<object type="application/x-shockwave-flash" id="webyy_client" name="webyy_client" data="http://weblbs.yystatic.com/s/45978908/2504749472/finscene.swf" width="830" height="548">-->
                            		<!--<param name="menu" value="false"></param>-->
                            		<!--<param name="quality" value="high"></param>-->
                            		<!--<param name="bgcolor" value="#292A2C"></param>-->
                            		<!--<param name="wmode" value="opaque"></param>-->
                            		<!--<param name="allowScriptAccess" value="always"></param>-->
                            		<!--<param name="allowFullScreen" value="true"></param>-->
                            		<!--<param name="flashvars" value="source=goldFin"></param>-->
                            	<!--</object>-->
                            </div>
                            <p></p>
                        </div>
                        <div id="chat">
                            <img src="images/banner5.jpg"> 
                        </div> 
                    </div>
                </div>
                            </div>
        </div>
        <!--第一屏-->
	<div class="live-list">
		<div class="live-titie"><h2>交易快报</h2><span>10-12点</span></div>
		<div class="live-list-left">
			
			<div class="live-room">
				<ul>
					<li><a href="/80008" target="_blank"><img src="images/laoshi/teacher10.jpg"/><p>股市说</p><span>3333</span></a></li>
					<li><a href="/90000" target="_blank"><img src="images/laoshi/teacher11.jpg"/><p>涨停部落</p><span>4968</span></a></li>
					<li><a href="/91988" target="_blank"><img src="images/laoshi/teacher12.jpg"/><p>三股演义</p><span>7552</span></a></li>
					<li><a href="/91988" target="_blank"><img src="images/laoshi/teacher2.jpg"/><p>股来金</p><span>6845</span></a></li>
					<li><a href="/60606" target="_blank"><img src="images/laoshi/teacher9.jpg"/><p>股市沉浮</p><span>7652</span></a></li>
					<li><a href="/98989" target="_blank"><img src="images/laoshi/teacher3.jpg"/><p>猴股学堂</p><span>7562</span></a></li>
				</ul>
			</div>
		</div>
		<div class="live-list-right">
			
			<div class="wenda"><img src="images/questions.png"></div>
			<div class="live-titie-table">
					<ul id="live-titie-tab">
						<li class="current"><a href="javascript:;">热门板块</a></li>
						<li><a href="javascript:;">热门股票</a></li>
					</ul>
				<div id="live-titie-content">
				<div class="live-titie-box" style="display:block">
					<ul>
						<li class="bg"><a href="#">股票板块</a></li>
						<li class="bg"><a href="#">现货板块</a></li>
						<li class="bg"><a href="#">期指板块</a></li>
						<li class="bg"><a href="#">基金板块</a></li>
						<li class="bg"><a href="#">新三板</a></li>
						<li class="bg"><a href="#">新三板</a></li>
					</ul>
				</div>
				<div class="live-titie-box">
					<ul>
						<li><a href="#">江苏阳光</a><span>600220</span></li>
						<li><a href="#">中科三环</a><span>600970</span></li>
						<li><a href="#">口子窖</a><span>603589</span></li>
						<li><a href="#">江南化工</a><span>002226</span></li>
						<li><a href="#">泸天化</a><span>600411</span></li>
						<li><a href="#">迪安诊断</a><span>300244</span></li>
					</ul>
				</div>
				</div>
			</div>
			<script>
window.onload = function ()
{
	var oLi = document.getElementById("live-titie-tab").getElementsByTagName("li");
	var oUl = document.getElementById("live-titie-content").getElementsByTagName("div");
	
	for(var i = 0; i < oLi.length; i++)
	{
		oLi[i].index = i;
		oLi[i].onmouseover = function ()
		{
			for(var n = 0; n < oLi.length; n++) oLi[n].className="";
			this.className = "current";
			for(var n = 0; n < oUl.length; n++) oUl[n].style.display = "none";
			oUl[this.index].style.display = "block"
		}	
	}
}
</script>
             <div class="live-img-box">
             	<ul>
             		<li>
             		<img src="images/122.jpg">
             		<dl><a href="#">网友记录火箭发射</a></dl>
             		<dd>网友记录火箭发射网友记录火箭发射..[<a href="#">详细</a>]</dd>
             		</li>
             		<li>
             		<img src="images/123.jpg">
             		<dl><a href="#">模特神似游戏人物</a></dl>
             		<dd>模特神似游戏人物模特神似游戏人物..[<a href="#">详细</a>]</dd>
             		</li>
             	</ul>
             </div>

		</div>
	</div>
	<!--第一屏 end-->
	<div class="clear"></div>
	<!--第二屏-->
    <div class="live-list">
    	<div class="live-titie"><h2>投资风格</h2></div>
    	<div class="live-list-left">	
    		<div class="live-room">
				<ul>
					<li><a href="/51888" target="_blank"><img src="images/laoshi/teacher1.jpg"/><p>金融大观</p><span>3444</span></a></li>
					<li><a href="/91988" target="_blank"><img src="images/laoshi/teacher2.jpg"/><p>股来金</p><span>4444</span></a></li>
					<li><a href="/98989" target="_blank"><img src="images/laoshi/teacher3.jpg"/><p>猴股学堂</p><span>4567</span></a></li>
					<li><a href="/90000" target="_blank"><img src="images/laoshi/teacher4.jpg"/><p>千股长红</p><span>5632</span></a></li>
					<li><a href="/12530" target="_blank"><img src="images/laoshi/teacher5.jpg"/><p>龙腾股跃</p><span>8554</span></a></li>
					<li><a href="/12306" target="_blank"><img src="images/laoshi/teacher6.jpg"/><p>股缘社区</p><span>7222</span></a></li>				
					<li><a href="/12580" target="_blank"><img src="images/laoshi/teacher7.jpg"/><p>飞来红财</p><span>9222</span></a></li>
					<li><a href="/12345" target="_blank"><img src="images/laoshi/teacher8.jpg"/><p>点石成金</p><span>5550</span></a></li>
					<li><a href="/60606" target="_blank"><img src="images/laoshi/teacher9.jpg"/><p>股市沉浮</p><span>4566</span></a></li>
				</ul>
    		</div>
    	</div>
    	<div class="live-list-right">
    	<div class="live-li-title"><a href="#">官方公告</a></li></div>
    	<div class="live-li-box">
    		<ul>
    			<li><a href="#">构建新型合作伙伴关系促进亚洲设施发展</a></li>
    			<li><a href="#">金立群介绍亚投行成立6个月工作进展</a></li>
    			<li><a href="#">促银团贷款 约束银行降杠杆</a></li>
    			<li><a href="#">货币政策失效直升机撒钱成最佳选择？</a></li>
    			<li><a href="#">退休方案年底求意见 第一批或延退3个月</a></li>
    			<li><a href="#">支付牌照转一张纸卖2.5亿-4.8亿</a></li>
    			<li><a href="#">货币投放方式：MLF和PSL地位越来越重要</a></li>
    		</ul>
    	</div>
    	<div class="live-li-title"><a href="#">每日密报</a></li></div>
    	<div class="live-li-box">
    		<ul>
    			<li><a href="#">央行副行：允许金融机构有序破产</a><span>6-23</span></li>
    			<li><a href="#">民生银行唯一一位副行长遭解聘</a><span>6-23</span></li>
    			<li><a href="#">A股股灾一周年股民人均亏损50万元</a><span>6-23</span></li>
    			<li><a href="#">央行连续净回笼反衬流动性充裕</a><span>6-23</span></li>
    			<li><a href="#">A股股灾一周年股民人均亏损50万元</a><span>6-23</span></li>
    			<li><a href="#">央行调整存款准备金考核基数</a><span>6-23</span></li>
    			<li><a href="#">A股行情有起色多数私募仍在蛰伏</a><span>6-23</span></li>
    		</ul>
    	</div> 
    	</div>
    </div>
	<div class="main-index"></div>
</div>
<!--main end-->
<!--footer-->
<div class="footer">
        <div class="foot-menu clearfix">
            <dl class="fot-first">
                <dt>中启金融研究院</dt>
                <dd><a href="#">启富财富</a></dd>
                <dd><a href="#">启金科技</a></dd>
                <dd><a href="#">天启资本</a></dd>
                <dd><a href="#">解套网</a></dd>
                <dd><a href="#">财富中国</a></dd>
                <dd><a href="#">东方财经网</a></dd>
                <dd><a href="#">银辉投资</a></dd>
            </dl>
            <dl class="fot-second">
                <dt>保证金专户管理银行</dt>
                <dd><a href="#">中国工商银行</a></dd>
                <dd><a href="#">华夏银行</a></dd>
                <dd><a href="#">招商银行</a></dd>
                <dd><a href="#">中国农业银行</a></dd>
                <dd><a href="#">中国光大银行</a></dd>
                <dd><a href="#">交通银行</a></dd>
                <dd><a href="#">中国建设银行</a></dd>
                <dd><a href="#">中信银行</a></dd>
                <dd><a href="#">浦发银行</a></dd>
                <dd><a href="#">兴业银行</a></dd>
                <dd><a href="#">浙商银行</a></dd>
            </dl>
            <dl class="fot-three">
                <dt>合作专业机构及交易所</dt>
                <dd><img src="images/foot-imglogo1.jpg" alt="" class="mr35"><img src="images/foot-imglogo2.jpg" alt=""></dd>
            </dl>
        </div>
        <div class="foot-copy">
            <div class="copyright">
			    <p style="color:#adadad;font-size:14px;border:none;line-height:120%;">
			    <span style="font-size:20px;"> 投资有风险，入市需谨慎</span>
			    <br>
			    <br>            本产品属于高风险、高收益投资品种；投资者应具有较高的风险识别能力、资金实力与风险承受能力。投资者应合理配置资产、不应用全部资金做投资，不应借钱来做投资。</p>
                <div>
                    <a onclick="ga('send','event','底部','关于我们');" href="#" target="_blank">关于我们<i>|</i></a>
                    <a onclick="ga('send','event','底部','联系我们');" href="#" target="_blank">联系我们<i>|</i></a>
                    <a onclick="ga('send','event','底部','加入我们');" href="#" target="_blank">加入我们<i>|</i></a>
                    <a onclick="ga('send','event','底部','法律声明');" href="#" target="_blank">法律声明<i>|</i></a>
                    <a onclick="ga('send','event','底部','风险提示');" href="#" target="_blank">风险提示<i>|</i></a>
                    <a onclick="ga('send','event','底部','隐私保密条款');" href="#" target="_blank">隐私保密条款<i>|</i></a>
                    <a onclick="ga('send','event','底部','设置勿扰');" href="#" class="msgwin" pop="box04" target="_blank">设置勿扰</a>
                </div>
                <p>备案/许可证编号：鄂ICP备13045265号-3 www.zqyanjiu.com &nbsp;&nbsp; Copyright &copy; 2015版权所有 复制必究 </p>
            </div>
        </div>
    </div>

<!--footer end-->
	</body>
</html>
