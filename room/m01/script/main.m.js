var ws;
var page_fire
function OnSocket(){
	ws=new WebSocket("ws://"+RoomInfo.TServer+":"+RoomInfo.TSPort);
	ws.onopen=onConnect;
	ws.onmessage=function(e){WriteMessage(e.data)};
	ws.onclose=function(){setTimeout('location.reload()',3000);};
	ws.onerror=function(){setTimeout('location.reload()',3000);};
}
function Datetime(tag)
{
	return new Date().toTimeString().split(' ')[tag];	
}
function showLeftTime()
{
var now=new Date();
var hours=now.getHours();
var minutes=now.getMinutes();
return hours+":"+minutes;
}

function MsgAutoScroll(){
	if($('#publicChat').find(".msg_li").length>30){$('#publicChat').find(".msg_li:first").remove();}
	$('#publicChat').animate({scrollTop:$('#publicChat')[0].scrollHeight}, 1000);
}
UserList=(function(){
	var list=[];
	list['ALL']={sex:2,chatid:'ALL',nick:'大家'}
	return{
		List:function(){return list},
		init:function(){
			list['ALL']={sex:2,chatid:'ALL',nick:'大家'}
        var request_url='../../ajax.php?act=robotlist&rid='+My.rid+'&r='+RoomInfo.r+'&'+Math.random() * 10000;
			$.ajax({type: 'get',dataType:'text',url: request_url,
				success:function(data){
					WriteMessage2(data);
				}});
			},
		get:function(id){return list[id];},
		add:function(id,u){
			if($("#"+id).length >0)return;
			list[id]=u;
		},
		del:function(id,u){
			if(id==My.chatid)return;
			delete(list[id]);
		}
	}
})();
function MsgShow(str,type){
	//alert(str);
	str="<div class='msg_li'><div style='clear:both;'></div>"+str+"</div>";
	$("#publicChat").append(str);
	MsgAutoScroll();
}
function MsgSend(){
	var msgid=randStr()+randStr();
        if(!check_auth("msg_send")){alert('没有发言权限！');return false;}
        var str=$("#messageEditor").html();
         //敏感词语审核
       var reg = new RegExp(msg_unallowable, "ig");
	if(reg.test(str)&&!check_auth("room_admin")){
      str="<div class='msg'><div class='msg_head'><span class='time'>"+showLeftTime()+"</span><span class='u'> "+My.nick+"：</span></div> <div class='msg_content'>含敏感关键字，内容被屏蔽</div></div>";
          MsgShow(str,1);
           $("#messageEditor").html(""); 
           return false;
        }
	 str=encodeURIComponent($.trim(str.str_replace()));
	if(str==""){alert('发言内容不能为空！');return false;}
	if(device.iphone())str+=" [IPhone用户]";
	else if(device.ios())str+=" [IOS用户]"
	else if(device.ipad())str+=" [IPad用户]";
	else if(device.mobile())str+=" [手机用户]";		
	 var wsstr = '{"type":"SendMsg","ToChatId":"ALL","IsPersonal":"false","Style":"font-size:13px;color:#000","Txt":"'+msgid+'_+_'+str+'"}'; 
	//ws.send('SendMsg=M=ALL|false|font-size:13px;color:#000|'+msgid+'_+_'+str);
ws.send(wsstr);	
	PutMessage(My.rid,My.chatid,'ALL',My.nick,'大家','false','font-size:13px;color:#000',str,msgid);
	$("#messageEditor").html("");
	//$("#messageEditor").focus();
}
function randStr(){
	return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
}
function PutMessage(rid,uid,tid,uname,tname,privacy,style,str,msgid){
	if(RoomInfo.Msglog=='0')return;
	var request_url='../../ajax.php?act=putmsg';
	var postdata='msgid='+msgid+'&uname='+uname+'&tname='+tname+'&muid='+uid+'&rid='+rid+'&tid='+tid+'&privacy='+privacy+'&style='+style+'&msg='+str+'&'+Math.random() * 10000;
	
	$.ajax({type: 'POST',url:request_url,data:postdata});
}
function Mkick(adminid,rid,ktime,cause)
{
	$.ajax({type: 'get',dataType:'json',url: '../../ajax.php?act=kick&aid='+adminid+'&rid='+rid+'&ktime='+ktime+'&cause='+cause+'&u='+My.name+'&'+Math.random() * 10000,
			success:function(data){
				//alert(data);
				if(data.state=="yes"){
				location.href="../error.php?msg="+encodeURI('你被踢出！并禁止'+ktime+'分钟内登陆该房间！<br>原因是 '+cause+'');
				}
			}
	});
}
String.prototype.str_replace=function(t){
	var str=this;
	str= str.replace(/<\/?(?!br|img|font|p|span|\/font|\/p|\/span)[^>]*>/ig,'').replace(/\r?\n/ig,' ').replace(/(&nbsp;)+/ig," ").replace(/(=M=)+/ig,"").replace(/(|)+/ig,"").replace(/(SendMsg)/ig,'');
	return str;
	};

function check_auth(auth){
	var auth_rules = grouparr[My.color].rules;
	if(auth_rules.indexOf(auth)>-1)return true;
	else false;
}
function remove_auth(auth){
	grouparr[My.color].rules=grouparr[My.color].rules.replace(auth,"");
}
function FormatMsg(Msg)
{
	var User=UserList.get(Msg.ChatId);
	var toUser=UserList.get(Msg.ToChatId);
	var date= Datetime(0);
	var IsPersonal='';
	if(typeof(User)=='undefined'||typeof(toUser)=='undefined')return false;
	if(Msg.IsPersonal=='true' && toUser.chatid!='ALL') IsPersonal='[私]';
	var Txt=decodeURIComponent(Msg.Txt.str_replace());

	if(Txt.indexOf('C0MMAND')!=-1)
	{
		var command=Txt.split('_+_');
		switch(command[1])
		{
			case 'msgAudit':
				$('#'+command[2]).show();
				MsgAutoScroll();
				
			break;
			case 'msgBlock':
				$('#'+command[2]).remove();
				MsgAutoScroll();
			
			break;
                         case 'hongbaoinfo':
				
				var str='<div class="message-wrap"><div class="redbag-info1"><p style="color:#333;">'+command[4]+' 领取了 <span style="color:red;">'+command[2]+'</span> 的红包获得 <span style="color:red;">'+command[3]+'元</span></p></div><div class="clear"></div></div>';
			
				
			break;
                        case 'send_Msgblock':
				if(My.chatid==toUser.chatid){
					remove_auth('msg_send');
					alert('你已被禁言！');
				}
			break;
			case 'rebotmsg':
                        case 'automsg':
				var msg={};
				msg.ChatId=command[2];
				msg.ToChatId='ALL';
				msg.IsPersonal='false';
				msg.Txt=command[4]+'_+_'+command[3];
				msg.Style=Msg.Style;
				MsgShow(FormatMsg(msg),0);
			break;		
			
			case 'kick':
				if(My.chatid==toUser.chatid){				
					Mkick(Msg.ChatId,My.roomid,command[2],command[3]);				
				}
			break;
		}
	}
	else
	{
	var msgid="";
	if(Txt.indexOf('_+_')>-1){
		var t=Txt.split('_+_');
		msgid= t[0];
		Txt=t[1];
	}
	var msgAuditShow ='';
	if(RoomInfo.msgAudit=="1"){
		msgAuditShow ='style="display:none"';
		if(User.chatid==My.chatid ||(Msg.IsPersonal=='true' && My.chatid==toUser.chatid ))  msgAuditShow ="";
	}
	if( My.chatid==toUser.chatid ){toUser.nick='我';}
	var str="";
	var html="";
        html+="<div class='msg' "+msgAuditShow+" id='[msgid]'>";
        html+="	<div class='msg_head'><span class='time'>"+showLeftTime()+"</span><span class='u'> [nick]：</span></div>";
        html+=" <div class='msg_content' style='"+Msg.Style+";'>[txt]</div>";
        html+="</div>";
	var html2="";
        html2+="<div class='msg'"+msgAuditShow+" id='[msgid]'>";
        html2+="	<div class='msg_head'><span class='time'>"+showLeftTime()+"</span><span class='u'>[nick]</span><span class='dui'>对</span><span class='u'>[tnick]</span><span class='shuo'>说</span></div>";
        html2+=" <div class='msg_content' style='"+Msg.Style+";'>[txt]</div>";
        html2+="</div>";
		
	if(toUser.chatid!="ALL"){
		str=html2.replace("[txt]",Txt).replace("[tnick]",toUser.nick).replace("[nick]",User.nick).replace("[tuid]",toUser.chatid).replace("[uid]",User.chatid).replace("[msgid]",msgid);
	}else{
		str=html.replace("[txt]",Txt).replace("[nick]",User.nick).replace("[uid]",User.chatid).replace("[msgid]",msgid);
	}
	}
	return str;
	
}
function sysend_command(cmd,value){ 
    var Msg='';
   switch(cmd){
                                 case 'hongbaoinfo':
					Msg+='C0MMAND_+_'+cmd+'_+_'+value+'_+_'+My.nick;
					IsPersonal='false';
                                        var msgid=randStr()+randStr();
                                        var neirong=value.split('_+_');
                                        var str='<div class="message-wrap"><div class="redbag-info1"><p style="color:#333;">'+My.nick+' 领取了 <span style="color:red;">'+neirong[0]+'</span> 的红包获得 <span style="color:red;">'+neirong[1]+'元</span></p></div><div class="clear"></div></div>';
				  PutMessage(My.rid,My.chatid,'hongbao',My.nick,'ALL','false','padding:0px;',str,msgid);
                                break;
                              
				default:
					Msg+='C0MMAND_+_'+cmd+'_+_'+value;
					IsPersonal='false';
					//ToUser.id='ALL';
				break;
			}
			if(Msg!='')
			{	
				
				//var str='SendMsg=M='+touser+'|'+IsPersonal+'|'+Style+'|'+Msg;
                               var str = '{"type":"SendMsg","ToChatId":"ALL","IsPersonal":"'+IsPersonal+'","Style":"font-size:13px;","Txt":"'+Msg+'"}'; 
				ws.send(str);
				
			} 
}
function WriteMessage(txt){
	//if(txt.indexOf('SendMsg')!=-1)
	

	var Msg;
	try{
		Msg=eval("("+txt+")");
	}catch(e){return;}
	if(Msg.stat!='OK')
	{
		if(Msg.stat=="MaxOnline"){
			document.body.innerHTML='<div  style="font-size:12px; text-align:center; top:100px; position:absolute;width:100%">O.O 对不起，服务端并发数已满！请您联系管理员对系统扩容升级！<br><br></div>';
			return;
			}
		return ;
	}
	switch(Msg.type)
	{
		case "Ulogin":
			var U=Msg.Ulogin;
			var vip_msg="到来";
			var date= Datetime(0);
			var str='<span class="info">欢迎：<font class="u" onclick="ToUser.set(\''+U.chatid+'\',\''+U.nick+'\')">'+U.nick+'</font>  <font class="date">'+date+'</font></span>';
			if(My.chatid!=U.chatid){
			UserList.add(U.chatid,U);
			}
			var type=0;
			if(U.chatid==My.chatid) type=2;
			//MsgShow(str,type);
			
		break;
		case "UMsg":
			var str=FormatMsg(Msg.UMsg);
                        if(!str)return;
                     if(Msg.UMsg.IsPersonal!='true'){
				MsgShow(str,1);
			}
			else
			{
			  if(Msg.UMsg.ToChatId==My.chatid || Msg.UMsg.ChatId==My.chatid){
                               MsgShow(str,1);
                            }
                        }
						
		break;
		case "UonlineUser":
			
			var onlineNum=Msg.roomListUser.length;
			for(i=0;i<onlineNum;i++)
			{
			var U=Msg.roomListUser[i]['client_name'];
			
			UserList.add(U.chatid,U);
			}
		break;
		case "Ulogout":
			var U=Msg.Ulogout;
			var date= Datetime(0);
			var str='<span class="info">用户：'+U.nick+'   离开！ <font class="date">'+date+'</font></span>';
			//MsgShow(str,0);
			UserList.del(U.chatid,U);
		break;
		case "SPing":
			//alert(Msg.SPing.time);
		break;
		case "Sysmsg":
			alert(Msg.Sysmsg.txt);
		break;
	}
	
}
function WriteMessage2(txt){
      var Msg;
	try{
		Msg=eval("("+txt+")");
	}catch(e){return;}
	
	switch(Msg.type)
	{
		
	
		case "UonlineUser":
			
			var onlineNum=Msg.roomListUser.length;
			for(i=0;i<onlineNum;i++)
			{
			var U=Msg.roomListUser[i];
			
			UserList.add(U.chatid,U);
			}
		break;
		case "Ulogout":
			var U=Msg.Ulogout;
			var date= Datetime(0);
			var str='<div style="height:22px; line-height:22px;">用户：<font class="u" onclick="ToUser.set(\''+U.chatid+'\',\''+U.nick+'\')">'+U.nick+'</font>   离开！ <font class="date">'+date+'</font></div>';
			//MsgShow(str,0);
			UserList.del(U.chatid,U);
		break;
		
	}
	
}
var pageii;
function OnInit(){
	OnSocket();
	Init();
	showLive();
	$("#sendBtn").click(function(){MsgSend();});
	$('#publicChat').height($(window).height()-310)

        $(".qqchat").click(function(){
        pageii=layer.open({type: 1,content: $("#qqchat_win").html() });
        });
       // MsgAutoScroll();
        getsysmsg();
}
function onConnect()
{
	//setInterval("online('<?=$time?>')",10000);
	var str='Login=M='+My.roomid+'|'+My.chatid+'|'+My.nick+'|'+My.sex+'|'+My.age+'|'+My.qx+'|'+My.ip+'|'+My.vip+'|'+My.color+'|'+My.cam+'|'+My.state+'|'+My.mood;
        var str = '{"type":"Login","roomid":"'+My.roomid+'","chatid":"'+My.chatid+'","nick":"'+My.nick+'","sex":"'+My.sex+'","age":"'+My.age+'","qx":"'+My.qx+'","ip":"'+My.ip+'","vip":"'+My.vip+'","color":"'+My.color+'","cam":"'+My.cam+'","state":"'+My.state+'","mood":"'+My.mood+'"}';

	ws.send(str);
		
	if(typeof(UserList)!='undefined'){
		UserList.init();
	}
	//bt_fenping();
}
function icon3() {
    $("#icon3").click(function() {
        var a = $("head  title").text();
		
        postToWb(a)
    })
}
function icon2() {
    $("#icon2").click(function() {
        var a = $("head  title").text();
        postToXinLang(a)
    })
}
function icon4() {
    $("#icon4").click(function() {
        var a = $("head  title").text();
        postToQzone(a)
    })
}
function postToXinLang(a) {
    window.open("http://v.t.sina.com.cn/share/share.php?title=" + encodeURIComponent(a) + "&url=" + encodeURIComponent(location.href) + "&rcontent=", "_blank", "scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes")
}
function postToQzone(a) {
    var b = encodeURI(a),
    c = encodeURI(a),
    d = encodeURI(document.location);
    return window.open("http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title=" + b + "&url=" + d + "&summary=" + c),
    !1
}
function postToWb(a) {
    var b = encodeURI(a),
    c = encodeURI(document.location),
    d = encodeURI("appkey"),
    e = encodeURI(""),
    f = "",
    g = "http://v.t.qq.com/share/share.php?title=" + b + "&url=" + c + "&appkey=" + d + "&site=" + f + "&pic=" + e;
    window.open(g, "转播到腾讯微博", "width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no")
}
function showLive(){
	$("#video-win").html();
}
function Init(){
$("#player").click(function(){showLive()});
$("body").click(function() { $(".setting-expression-layer").hide() });
$("#footer .smile img").click(function(a) {
    if (a.stopPropagation(), 0 == $("#expressions").find(".expr-tab").find("tr").length) {
        var b = '<tr><td><img src="/room/face/pic/m/kx.gif" alt="狂笑" title="狂笑" width="28" height="28" /></td>';
        b += ' <td><img src="/room/face/pic/m/jx.gif" alt="贱笑" title="贱笑" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/tx.gif" alt="偷笑" title="偷笑" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/qx.gif" alt="窃笑" title="窃笑" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/ka.gif" alt="可爱" title="可爱" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/kiss.gif" alt="kiss" title="kiss" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/up.gif" alt="up" title="up" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/bq.gif" alt="抱歉" title="抱歉" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/bx.gif" alt="鼻血" title="鼻血" width="28" height="28" /></td></tr>',
        b += '<tr><td><img src="/room/face/pic/m/bs.gif" alt="鄙视" title="鄙视" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/dy.gif" alt="得意" title="得意" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/fd.gif" alt="发呆" title="发呆" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/gd.gif" alt="感动" title="感动" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/glian.gif" alt="鬼脸" title="鬼脸" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/hx.gif" alt="害羞" title="害羞" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/jxia.gif" alt="惊吓" title="惊吓" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/zong.gif" alt="囧" title="囧" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/kl.gif" alt="可怜" title="可怜" width="28" height="28" /></td></tr>',
        b += '<tr><td><img src="/room/face/pic/m/kle.gif" alt="困了" title="困了" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/ld.gif" alt="来电" title="来电" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/lh.gif" alt="流汗" title="流汗" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/qf.gif" alt="气愤" title="气愤" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/qs.gif" alt="潜水" title="潜水" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/qiang.gif" alt="强" title="强" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/sx.gif" alt="伤心" title="伤心" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/suai.gif" alt="衰" title="衰" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/sj.gif" alt="睡觉" title="睡觉" width="28" height="28" /></td></tr>',
        b += '<tr><td><img src="/room/face/pic/m/tz.gif" alt="陶醉" title="陶醉" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/wbk.gif" alt="挖鼻孔" title="挖鼻孔" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/wq.gif" alt="委屈" title="委屈" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/xf.gif" alt="兴奋" title="兴奋" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/yw.gif" alt="疑问" title="疑问" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/yuan.gif" alt="晕" title="晕" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/zj.gif" alt="再见" title="再见" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/zan.gif" alt="赞" title="赞" width="28" height="28" /></td>',
        b += '<td><img src="/room/face/pic/m/zb.gif" alt="装逼" title="装逼" width="28" height="28" /></td></tr>',
        b += '<tr><td><img src="/room/face/pic/m/bd.gif" alt="被电" title="被电" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/gl.gif" alt="给力" title="给力" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/hjd.gif" alt="好激动" title="好激动" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/jyl.gif" alt="加油啦" title="加油啦" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/jjdx.gif" alt="贱贱地笑" title="贱贱地笑" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/lll.gif" alt="啦啦啦" title="啦啦啦" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/lm.gif" alt="来嘛" title="来嘛" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/lx.gif" alt="流血" title="流血" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/lgze.gif" alt="路过这儿" title="路过这儿" width="22" height="22" /></td></tr>',
        b += '<tr><td><img src="/room/face/pic/m/qkn.gif" alt="切克闹" title="切克闹" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/qgz.gif" alt="求关注" title="求关注" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/tzuang.gif" alt="推撞" title="推撞" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/ww.gif" alt="威武" title="威武" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/wg.gif" alt="围观" title="围观" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/xhh.gif" alt="笑哈哈" title="笑哈哈" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/zc.gif" alt="招财" title="招财" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/zf.gif" alt="转发" title="转发" width="22" height="22" /></td>',
        b += '<td><img src="/room/face/pic/m/zz.gif" alt="转转" title="转转" width="22" height="22" /></td></tr>',
        $("#expressions").find(".expr-tab").append(b),
        $(".setting-expression-layer").show();
        $("#expressions").find(".expr-tab").find("img").click(function() {
            var a = $(this).attr("src");
            $("#messageEditor").focus(),
            document.execCommand("insertImage", null, a),
            $(this).closest(".setting-expression-layer").hide()
        })
    } else $(".setting-expression-layer").toggle()
});
    $("#sharedBtn").click(function() {
        var a = '<div class="header">';
        a += '<div class="sharedClose" style=""></div>',
        a += "</div>",
        a += '<h2 style="margin-bottom: 12px;color: #000;font-weight: 400;">分享到:</h2>',
        a += "<ul>",
        a += '<li onclick="icon3()" id="icon3"><img src="images/tx.png" width="44px" height="44px">腾讯微博</li>',
        a += '<li onclick="icon2()" id="icon2"><img src="images/sina.png" width="44px" height="44px">新浪微博</li>',
        a += '<li onclick="icon4()" id="icon4"><img src="images/qz.png" width="44px" height="44px">QQ空间</li>',
        a += "</ul>",
        $("#shared").empty().append(a),
        $("#sharedWrap").show(),
        $("#shared").show(),
        $("#shared .sharedClose").click(function() {
            $("#shared,#sharedWrap").hide()
        })
    });
    $("nav li").click(function() {
        var e, a = !0,
        b = !0,
        c = !0,
        d = $(this).index();
        0 == d && ($(this).addClass("active").siblings().removeClass("active1").removeClass("active2"), $(this).find("span").addClass("activeCart"), $("nav li span").removeClass("activeCart1").removeClass("activeCart2").removeClass("activeCart")),
        1 == d && ($(this).addClass("active1").siblings().removeClass("active").removeClass("active2"), $(this).find("span").addClass("activeCart1"), $("nav li span").removeClass("activeCart").removeClass("activeCart2")),
        2 == d && ($(this).addClass("active2").siblings().removeClass("active").removeClass("active1"), $(this).find("span").addClass("activeCart2"), $("nav li span").removeClass("activeCart").removeClass("activeCart1")),
        $(this).find("span").addClass("activeCart"),
        2 == d ? $(".kuaiXun iframe").height($(document).height() - 260) : 0 != d && (e = $(window).height() - 260),
        0 == d && a && (a = !1, e = $(window).height() - 310, $(".publicChat,#footer").show(), $(".myCustomer,#qqOnline,.kuaiXun").hide(), $(".publicChat").height() > e),
        1 == d && b && (b = !1, e = $(window).height() - 260, $("#qqOnline").height(e).addClass("white"), $("#qqOnline").show(), $(".publicChat,.kuaiXun,#footer").hide(), $("#qqOnline ul").height() > e ),
        2 == d && c && (c = !1, $(".kuaiXun").show(), $(".publicChat,#qqOnline,#footer").hide())
    });
}

function showsyssmg(txt){

var s='<div class="msg"><div class="msg_head"><span class="time">'+showLeftTime()+'</span><span class="u" style="color:red;">系统消息</span></div><div class="msg_content">'+txt+'</div></div>';
	
	MsgShow(s,0);
}
function getsysmsg(){
  
	$.getJSON("../../ajax.php?act=getsysmsg","rid="+My.rid,
	function(data){
        
		if(data.state=='1'){
			data.sysmsg_id=0;
			timer_fun=function(){
				if(data.info.length<1)return;
				if(data.fangshi=="1"){
                                    data.sysmsg_id=Math.ceil(Math.random()*(data.info.length-1));
					
				}else{
					if(data.sysmsg_id>=data.info.length){data.sysmsg_id=0;}
				
				}
				showsyssmg(data.info[data.sysmsg_id++]);
			}
			timer_fun();
                        if(data.fangshi!='3'){
			setInterval('timer_fun()',data.jiange*1000);
                        }
		}
	});
}
function getmsg(){
   $.ajax({type: 'get',dataType:'text',url: 'ajax.php?act=getmsg',
				success:function(data){
                                 
					$("#publicChat").append(data);
	MsgAutoScroll();
				}}); 
    
    
}


//红包
function center(a, b) {
     b &&
    function(a) {
        var b = $(a),
        c = ($(window).width() - b.outerWidth()) / 2;
        b.css({
            left: c
        })
    } (a),
    function(a) {
        var e, f, b = $(a),
        c = b.outerHeight(),
        d = $(window).height();
        c > d ? (b.css("top", "0px"), e = 0) : (f = (d - c) / 2, b.css("top", f + "px"), e = f)
    } (a)
}

function Hongbao(hbid){
   
        if(My.chatid.indexOf('x')>-1){
            window.location.href="../../room/minilogin.php"; 
         // openWin(2,false,'room/minilogin.php',390,310);
          return;
        }
        var json,hbid,json, mobile, mymoney, money1, success, fail, had;
        //  hbid  = $(this).attr("moneygiftid")
           $.post("/apps/redbag/index.php?act=GetMoneygift", {
        customerId: My.chatid,
        moneyGiftId: hbid
    },
    function(data) {
        data && (json = eval("(" + data + ")"),
          $("#successredbag,#meetredbag,#hadredbag,#lookredbag,#lookthisbag,#failredbag").remove(), 1 == json.status ? (mymoney = $.trim($("#MyMoney").val()), mymoney = Math.floor(100 * mymoney), money1 = $.trim(json.amount), money1 = Math.floor(100 * money1), mymoney = (mymoney + money1) / 100, $("#MyMoney").val(mymoney), success = '<div id="successredbag">', success += '<div class="redbagclose"></div>', success += "" != json.senderAvatar ? '<div class="img"><img src="' + json.senderAvatar + '" width="65" height="65"></div>': '<div class="img"><img src="../images/avatar/52/01.png" width="65" height="65"></div>', success += '<p style="margin-top:5px;font-size: 14px;color:#000;">' + json.senderName + "的红包</p>", success += '<p class="p1" style="margin-top:5px; font-size: 12px;color: #CCC;">' + json.moneygiftTitle + "</p>", success += '<div class="money bagred">' + json.amount + '<span style="color:#333;font-size: 14px;">元</span></div>', success += '<p class="baginformation">已领取' + json.robcount + "/" + json.allcount + "个，共" + json.realityMoney + "元</p>", success += '<div class="HBlist">', $.each(json.rows,
        function(a, b) {
            success += '<div class="list-info">',
            success += null != b.senderAvatar ? '<div class="user-img"><img src="' + b.senderAvatar + '" width="35" height="35"></div>': '<div class="user-img"><img src="../images/avatar/52/01.png" width="35" height="35"></div>',
            success += '<div class="list-right">',
            success += '<div class="list-right-top">',
            success += '<div class="user-name fl f14">' + b.nickname + "</div>",
            success += '<div class="user-money fr f14">' + b.getmoney + "元</div></div>",
            success += '<div class="user-time">' + b.addtime + "</div>",
            success += "</div></div>"
        }), success += "</div>", success += '<div class="minebag1 cursor">查看我的红包>></div>', success += "</div>", $("body").append(success), center("#successredbag", !0), $(".minebag1").click(function() {
            minebag()
        }),sysend_command('hongbaoinfo',json.senderName+'_+_'+json.amount)) : 0 == json.status ? (fail = '<div id="failredbag">', fail += '<div class="redbagclose"></div>', fail += "" != json.senderAvatar ? '<div class="img"><img src="' + json.senderAvatar + '" width="60" height="60"></div>': '<div class="img"><img src="../images/avatar/52/01.png" width="60" height="60"></div>', fail += '<p style="margin-top: 15px;">' + json.senderName + "</p>", fail += '<p class="memos">手慢了，红包派完了！</p>', fail += '<div class="lookthisbag">看看大家的手气>></div>', fail += "</div>", $("body").append(fail), center("#failredbag", !0), $(".lookthisbag").click(function() {
            thisbag(hbid)
        })) : (had = '<div id="hadredbag">', had += '<div class="redbagclose"></div>', had += "" != json.senderAvatar ? '<div class="img"><img src="' + json.senderAvatar + '" width="60" height="60"></div>': '<div class="img"><img src="../images/avatar/52/01.png" width="60" height="60"></div>', had += '<p style="margin-top: 15px;">' + json.senderName + "</p>", had += '<p class="memos mt10">您已经抢过这个红包了!</p>', had += '<div class="minebag cursor f14">看看大家的手气>></div>', had += "</div>", $("body").append(had), center("#hadredbag", !0), $(".minebag").click(function() {
            thisbag(hbid)
        }))
   ) })
    }
   
    var thisbag,minebag;
  thisbag= function(moneyGiftId) { 
   $.post("/apps/redbag/index.php?act=GetMoneygiftList",{ 
       moneyGiftId: moneyGiftId },
   function (data){ 
       var json, success; data && (json = eval(" (" + data + ")"), $("#successredbag,#meetredbag,#hadredbag,#lookredbag,#lookthisbag,#failredbag ").remove(), success = '<div id="lookthisbag">', success += '<div class="redbagclose"></div>', success += "" != json.senderAvatar ? '<div class="img"><img src="' + json.senderAvatar + '" width="65" height="65"></div>' : '<div class="img"><img src="../images/avatar/52/01.png" width="65" height="65"></div>', success += '<p style="margin-top:5px;font-size:14px;color:#000;">' + json.senderName + "的红包</p>", success += '<p class="p1" style="margin-top:5px;font-size:12px;color:#CCC;">' + json.moneygiftTitle + "</p>", success += '<div class="money bagred">' + json.realityMoney + '<span style="color:#333;font-size:14px;">元</span></div>', success += '<p class="baginformation">已领取' + json.robcount + "/" + json.allcount + "个，共" + json.realityMoney + "元 </p>",
        success += '<div class="HBlist">', $.each(json.rows, function (a, b) { success += '<div class="list-info">', 
        success += null != b.senderAvatar ? '<div class="user-img"><img src="' + b.senderAvatar + '" width="35" height="35"></div> ' : ' < div class = "user-img" > <img src = "../images/avatar/52/01.png" width = "35" height = "35"></div>', 
        success += '<div class="list-right">', success += '<div class="list-right-top">', 
        success += '<div class="user-name fl f14">' + b.nickname + "</div>", 
        success += '<div class="user-money fr f14">' + b.getmoney + "元 </div></div> ", 
        success += '<div class="user-time">' + b.addtime + " </div>", 
        success += "</div></div>" }), 
    success += "</div>", 
    success += '<div class="minebag1 cursor">查看我的红包>></div>', 
    success += " </div>", $("body").append(success), 
    center("#lookthisbag", !0), $(".minebag1").click(function () { minebag() })) }) }
minebag = function () {
$.post("/apps/redbag/index.php?act=GetUsertMoneygift", function(data){var json,lookredbag;data&&(json=eval("(" + data + ")"), 
    $("#successredbag,#meetredbag,#hadredbag,#lookredbag,#lookthisbag,#failredbag ").remove(), 
    lookredbag = '<div id="lookredbag">', 
    lookredbag += '<div class="redbagclose"></div>', 
    lookredbag += "" != json.avatar ? '<div class="img"><img src="' + json.avatar + '"width="65" height="65"></div>' : '<div class="img"><img src="../images/avatar/52/01.png" width="65" height="65"></div>', 
    lookredbag += '<p style="margin-top:10px;font-size: 14px;color:#000">' + json.nickName + '共收到<span style="color:red;">' + json.allcount + "</span>个红包</p>", 
    lookredbag += '<div class="money bagred">' + json.allmoney + '<span style="color:#333;font-size:14px;">元</span></div>', 
    lookredbag += '<div class="HBlist">', null!=json.rows ? $.each(json.rows, function (a, b) { lookredbag += '<div class="list-info">', 
        lookredbag += null != json.avatar ? '<div class="user-img"><img src="' + json.avatar+ '" width="35" height="35"></div>' : '<div class="user-img"><img src="../images/avatar/52/01.png" width="35" height="35"></div>', 
        lookredbag += '<div class="list-right">', lookredbag += '<div class="list-right-top">', 
        lookredbag += '<div class="user-name fl f14">' + b.nickname + " </div>", 
        lookredbag += '<div class="user-money fr f14">' + b.getmoney + "元</div></div>", 
        lookredbag += '<div class="user-time">' +  b.addtime + "</div>", 
        lookredbag += " </div></div> " }):'', 
    lookredbag += " </div></div>", $("body").append(lookredbag), center("#lookredbag", !0)) }) }
$(function() {

$("#publicChat").delegate(".content-detail", "click",
	function() {
		var b, c;
            
	b = $(this), c = b.attr("moneygiftid"),  $(this).unbind("click"),Hongbao(c)
	})

})

 
