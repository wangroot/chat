function getId(id)
{
	return document.getElementById(id);
}
function Datetime(tag)
{
	return new Date().toTimeString().split(' ')[0];	
}
function SetChatValue(Variables,value)
{
	ChatValue[Variables]=value;
}
function GetChatValue(Variables)
{
	return ChatValue[Variables];
}

var ChatValue =new Array(10);
function SwfloadCompleted(tag)
{
	
	if(tag=='Video_main')
	{ 
		VideoLoaded=true;
		if(RoomInfo.type=='0')
		{
			getId('RoomMV').style.display='none';
			
		}
		else
		{
			if(RoomInfo.AutoPublicVideo=='1'){
				if(RoomInfo.PVideo==My.chatid)
				thisMovie('pVideo').sConnect(RoomInfo.VServer,My.rid+'·'+My.chatid,'1');
				else
				thisMovie('pVideo').pConnect(RoomInfo.VServer,My.rid+'·'+RoomInfo.PVideo,RoomInfo.PVideoNick);
			}
			if(RoomInfo.AutoSelfVideo=='1'){
				if(RoomInfo.PVideo==My.chatid)
				thisMovie('pVideo').sConnect(RoomInfo.VServer,My.rid+'·'+My.chatid,'1');
				else
				thisMovie('pVideo').sConnect(RoomInfo.VServer,My.rid+'·'+My.chatid,'2');
			}
		}
	}
}
function showLive(){
	if(RoomInfo.OtherVideoAutoPlayer=='0'){location.reload();}
	$('#OnLine_MV').html($('#OnLine_MV').html());
}
var ws;
var page_fire;
function OnSocket(){
	ws=new WebSocket("ws://"+RoomInfo.TServer+":"+RoomInfo.TSPort);
	ws.onopen=onConnect;
	ws.onmessage=function(e){WriteMessage(e.data)};
	ws.onclose=function(){setTimeout('location.reload()',3000);};
	 ws.onclose = function() {
        console.log("连接关闭，定时重连");
          connect();
       };
	ws.onerror=function(){setTimeout('location.reload()',3000);};
}
function OnInit()
{
	$.ajaxSetup({ contentType: "application/x-www-form-urlencoded; charset=utf-8"});

	$("body").click(function() { MsgCAlert();});
	//auth
	if(check_auth("room_admin"))$('#manage_div').show();	
	if(check_auth("rebots_msg"))$('#chat_type').show();
	if(check_auth("def_videosrc"))$('#bt_defvideosrc').show();
   OnSocket();
	//5分钟提示登录
	if(RoomInfo.loginTip=='1'&&My.chatid.indexOf('x')>-1)
	setInterval("loginTip()",1000*60*5);
	
	$('#Msg').html("连接中...");
	
	
	window.moveTo(0,0);
	window.resizeTo(screen.availWidth,screen.availHeight);
	OnResize();
	
	if(RoomInfo.OtherVideoAutoPlayer!="0"){
		$('#OnLine_MV').html('<iframe height="100%" width="100%" allowTransparency="true" marginwidth="0" marginheight="0"  frameborder="0" scrolling="no" src="room/player.php?type=pc"></iframe>');
	}else{
	 $('#OnLine_MV').html('<iframe height="100%" width="100%" allowTransparency="true" marginwidth="0" marginheight="0"  frameborder="0" scrolling="no" src="room/player.php?type=pc"></iframe>');
	}

	interfaceInit();
	POPChat.Init();
	ToUser.set('ALL','大家');
	dragMsgWinx(getId('drag_bar'));
    $('#UI_Left1').niceScroll({cursorcolor:"#000",cursorwidth:"1px",cursorborder:"0px;"});
	$('#MsgBox1').niceScroll({cursorcolor:"#FFF",cursorwidth:"3px",cursorborder:"0px;"});
	$('#MsgBox2').niceScroll({cursorcolor:"#FFF",cursorwidth:"3px",cursorborder:"0px;"});
	$('#OnLineUser_FindList').niceScroll({cursorcolor:"#FFF",cursorwidth:"3px",cursorborder:"0px;"});
	$('#OnLineUser_OhterList').niceScroll({cursorcolor:"#FFF",cursorwidth:"3px",cursorborder:"0px;"});
	$('#OnLineUser').niceScroll({cursorcolor:"#FFF",cursorwidth:"3px",cursorborder:"0px;"});
	$('#NoticeList').niceScroll({cursorcolor:"#FFF",cursorwidth:"3px",cursorborder:"0px;"});
	$("#Msg").keydown(function(e){if(e.keyCode==13){ToUser.set($("#ToUser").attr("data-id"),$("#ToUser").html());SysSend.msg();HideMenu();MsgCAlert();return false}});
	$("#Send_bt").on("click",function(){ToUser.set($("#ToUser").attr("data-id"),$("#ToUser").html());HideMenu();MsgCAlert();SysSend.msg();});
         $("#apDiv2").on("click",function(){ToUser.del();});
         $('#changeskin').on('click',function(){
		layer.open({
	        type: 1,
		title: false,
                shade: false,
                area: ['532px', '178px'],
		content:'<div style="position: absolute;top: 10px; left: 3px; overflow: auto;"><div id="skinbox" style="width:525px;overflow: auto;"><style>#skinbox img {width:100px;height:50px;padding-left: 4px;}</style><a href="javascript:void(0)" rel="'+RoomInfo.bg+'" onclick="changeSkin(this)"><img src="'+RoomInfo.bg+'"></a><a href="javascript:void(0)" rel="images/skin/b1.jpg" onclick="changeSkin(this)"><img src="images/skin/a1.jpg"></a><a href="javascript:void(0)" rel="images/skin/b2.jpg" onclick="changeSkin(this)"><img src="images/skin/a2.jpg"></a><a href="javascript:void(0)" rel="images/skin/b3.jpg" onclick="changeSkin(this)"><img src="images/skin/a3.jpg"></a><a href="javascript:void(0)" rel="images/skin/b4.jpg" onclick="changeSkin(this)"><img src="images/skin/a4.jpg"></a><a href="javascript:void(0)" rel="images/skin/b5.jpg" onclick="changeSkin(this)"><img src="images/skin/a5.jpg"></a><a href="javascript:void(0)" rel="images/skin/b6.jpg" onclick="changeSkin(this)"><img src="images/skin/a6.jpg"></a><a href="javascript:void(0)" rel="images/skin/b7.jpg" onclick="changeSkin(this)"><img src="images/skin/a7.jpg"></a><a href="javascript:void(0)" rel="images/skin/b8.jpg" onclick="changeSkin(this)"><img src="images/skin/a8.jpg"></a><a href="javascript:void(0)" rel="images/skin/b9.jpg" onclick="changeSkin(this)"><img src="images/skin/a9.jpg"></a><a href="javascript:void(0)" rel="images/skin/b10.jpg" onclick="changeSkin(this)"><img src="images/skin/a10.jpg"></a><a href="javascript:void(0)" rel="images/skin/b11.jpg" onclick="changeSkin(this)"><img src="images/skin/a11.jpg"></a><a href="javascript:void(0)" rel="images/skin/b12.jpg" onclick="changeSkin(this)"><img src="images/skin/a12.jpg"></a><a href="javascript:void(0)" rel="images/skin/b13.jpg" onclick="changeSkin(this)"><img src="images/skin/a13.jpg"></a><a href="javascript:void(0)" rel="images/skin/b14.jpg" onclick="changeSkin(this)"><img src="images/skin/a14.jpg"></a></div></div>' 
		});
		});
	initFaceColobar();
	
	//openWin(1,false,$("#tip_login_win").html(),800,350);
        //游客直播体验时间是否结束
        if(My.chatid.indexOf('x')>-1 && RoomInfo.tiyantime>0){
    access_time();
    setInterval("access_time()",1000*60*1);
    }
    getsysmsg();
}
function OnResize(){
	
	var cw=$(window).width();
	var vw=cw-950;
	
	if(cw>=1280){
		if(cw>=1280)vw=cw-806;
		if(cw>=1400)vw=cw-882;
		if(cw>=1600)vw=cw-1008;
					
			$('#UI_Central').css("margin-right",vw+5);
			$('#UI_Right').css("width",vw);		
			$('#OnLine_MV').css("height",vw/16*10.2);	
		
		
	}else {
		$('#UI_Central').css("margin-right","525px");
		$('#UI_Right').css("width",520);
		$('#OnLine_MV').css("height",520/16*10.2);	
	}
	
	var cH=$(window).height();

	$('#MsgBox1').height(cH-180);
	
	// $('#OnLineUser').height($('#OnLineUser').height()+$('#UI_Central').height()-$('#UI_Left2').height());
	$('#OnLineUser').height($(window).height()-340);

	// $('#UI_Left1').height($('#UI_Left2').height());
	$('#UI_Left1').css({'height':'auto','display':'block','overflow':'auto','width':'200px'});
	$('#NoticeList').height($('#NoticeList').height()+$('#UI_Central').height()-$('#UI_Right').height());
		
	$("#OnLineUser_OhterList").height($('#OnLineUser').height());
	$("#OnLineUser_FindList").height($('#OnLineUser').height());
    $("#notice-scrollbox").width($('#UI_Central').width()+250);
 
}
function OnUnload(){
var str = '{"type":"Logout"}';
ws.send(str);
	}
function tCam(tag)
{
	My.cam=tag;
}
function tCamState(tag)
{
	My.camState=tag;
	//alert(tag);
}
function onConnect()
{   

     if(My.color!='0'){
     setInterval("addRedBag()",60*20*1000);
     }
    if(My.color=='3'){
	setInterval("kefuonline(Datetime(0))",60000);
    }
	getId('Msg').innerHTML="";
  // console.log(Msg);
  
	//var str='Login=M='+My.roomid+'|'+My.chatid+'|'+My.nick+'|'+My.sex+'|'+My.age+'|'+My.qx+'|'+My.ip+'|'+My.vip+'|'+My.color+'|'+My.cam+'|'+My.state+'|'+My.mood;
        var str = '{"type":"Login","roomid":"'+My.roomid+'","chatid":"'+My.chatid+'","nick":"'+My.nick+'","sex":"'+My.sex+'","age":"'+My.age+'","qx":"'+My.qx+'","ip":"'+My.ip+'","vip":"'+My.vip+'","color":"'+My.color+'","cam":"'+My.cam+'","state":"'+My.state+'","mood":"'+My.mood+'"}';
    // console.log(My.nick);
    	
    	// if($.cookie('name')=null){
    		ws.send(str);
    	// }

		
	if(typeof(UserList)!='undefined'){
		UserList.init();
	}
	//bt_fenping();
}





function h_l(e)
{
	if(getId('UI_Left').style.display=='')
	{
		getId('UI_Left').style.display='none';
		getId('UI_Central').style.marginLeft='2px';
		e.src="images/h_r.gif";
	}
	else
	{
		getId('UI_Left').style.display='';
		getId('UI_Central').style.marginLeft='157px';
		e.src="images/h_l.gif";
	}
	getId('FontBar').style.display='none';
}
function h_r(e)
{
	if(getId('UI_Right').style.display=='')
	{
		getId('UI_Right').style.display='none';
		getId('UI_Central').style.marginRight='2px';
		e.src="images/h_l.gif";
	}
	else
	{
		getId('UI_Right').style.display='';
		getId('UI_Central').style.marginRight='248px';
		e.src="images/h_r.gif";
	}
	getId('FontBar').style.display='none';
}
function getXY(obj)
{ 
var a = new Array(); 
var t = obj.offsetTop; 
var l = obj.offsetLeft;
var w = obj.offsetWidth; 
var h = obj.offsetHeight;
while(obj=obj.offsetParent)
{ t+=obj.offsetTop; l+=obj.offsetLeft; } 
a[0] = t; a[1] = l; a[2] = w; a[3] = h; return a; 
}


function CloseColorPicker()
{
	getId('ColorTable').style.display='none'
}


function ck_Font(e,act)
{
	if(e!=null)
	{
	e.value=='true'?e.value='false':e.value='true';
	}
	switch(act)
	{
		case 'FontBold':
			if(e.value=='true')getId('Msg').style.fontWeight='bold';
			else getId('Msg').style.fontWeight='';
		break;
		case "FontItalic":
			if(e.value=='true')getId('Msg').style.fontStyle='italic';
			else getId('Msg').style.fontStyle='';
		break;
		case 'Fontunderline':
			if(e.value=='true')getId('Msg').style.textDecoration='underline';
			else getId('Msg').style.textDecoration='';
		break;
		case 'FontColor':
			getId('Msg').style.color=getId('ColorShow').style.backgroundColor;
		break;
		case 'ShowColorPicker':
			bt_ColorPicker();
		break;
	}
}
function ColorPicker()   
{		
  	  var baseColor=new Array(6);   
      baseColor[0]="00";     
      baseColor[1]="33";   
      baseColor[2]="66";   
      baseColor[3]="99";   
      baseColor[4]="CC";   
      baseColor[5]="FF";   
      var   myColor;   
      myColor="";   
      var   myHTML="";      
      myHTML=myHTML+"<div style='WIDTH:180px;HEIGHT:120px;' onclick='ck_Font(null,\"FontColor\");CloseColorPicker()'>";       
      for(i=0;i<6;i++)   
      {
              for(j=0;j<3;j++)   
                {     for(k=0;k<6;k++)   
                      {                     
                          myColor=baseColor[j]+baseColor[k]+baseColor[i];   
                          myHTML=myHTML+"<li data="+myColor+" onmousemove=\"document.getElementById('ColorShow').style.backgroundColor=this.style.backgroundColor\" style='background-color:#"+myColor+"'></li>";   
                      }   
                    }   
          
      }           
      for(i=0;i<6;i++)   
      { 
              for(j=3;j<6;j++)   
                {   for(k=0;k<6;k++)   
                      {                     
                          myColor=baseColor[j]+baseColor[k]+baseColor[i];//get   Color   
                          myHTML=myHTML+"<li data="+myColor+" onmousemove=\"document.getElementById('ColorShow').style.backgroundColor=this.style.backgroundColor\" style='background-color:#"+myColor+"'></li>";   
                      }   
                  }           
      }   
        
      myHTML=myHTML+"</div><div style='border:0px; width:180px; height:10px; background:#333333' id='ColorShow'></div>";      
      document.getElementById("ColorTable").innerHTML=myHTML;       
}
var ColorInit=false;
function bt_ColorPicker()
{
	var t=getXY(getId('FontColor'));
	getId('ColorTable').style.top=t[0]-145+'px';
	getId('ColorTable').style.left=t[1]+1+'px';
	if(!ColorInit)
	{
	ColorPicker();
	ColorInit=true;
	}
	getId('ColorTable').style.display='';
	getId('ColorTable').focus();
	return true;
	
}

function bt_Personal(e)
{
	if(e.value=='false')
	{
		e.value='true';
		e.src="images/Personal_true.gif";
		e.title='私聊中...[公聊/私聊]';
	}
	else
	{
		e.value='false';
		e.src="images/Personal_false.gif";
		e.title='公聊中...[公聊/私聊]';
	}
}
function bt_FontBar(e)
{
	if(getId('FontBar').style.display=='none')
	{
		var t=getXY(getId('bar_list'));
		getId('FontBar').style.display='';
		getId('FontBar').style.top=t[0]-47+'px';
		getId('FontBar').style.left=isIE?t[1]+1:t[1]+'px';
		getId('FontBar').style.width=t[2]-8+'px';
	}
	else
	{
		getId('FontBar').style.display='none';
	}
}
function bt_Send_key_option(e)
{
	var t=getXY(e);
	getId('Send_key_option').style.top=t[0]-50+'px';
	getId('Send_key_option').style.left=t[1]+2-165+'px';
	getId('Send_key_option').style.display='';
	getId('Send_key_option').focus();
}



function InsertImg(id,src,imagewidth,imageheight){

var msgwidth=$('#UI_Central').width();
var msgheight=$('#UI_Central').height();
var msgimgwidth='';
if(imagewidth<50){
 
  msgimgwidth= imagewidth;
}else{
    
   msgimgwidth=50; 
}
 var str='<img src=\"'+src+'\"  onclick="open_img(\''+src+'\',\''+imagewidth+'\',\''+imageheight+'\')" style="height:'+msgimgwidth+'px; cursor:pointer;" alt="点击看大图" title="点击看大图"/>';   
  

$(id).append(str);	
       
}
function bt_insertImg(id)
{
	$('#imgUptag').val(id);	
	$('#filedata').click();
}
function bt_MsgClear(){
	getId('MsgBox1').innerHTML = '';
	getId('MsgBox2').innerHTML = '';
}
function bt_SendEmote(obj){
	getId('Msg').innerHTML=obj.innerHTML;SysSend.msg();
	getId('Emote').style.display='none';
}
function bt_SwitchListTab(tag){
	
	if(tag=='User'){
		$("#OnLineUser_OhterList").hide();
		$("#OnLineUser_FindList").hide();
		$("#OnLineUser").show();
		$("#listTab1")[0].className='bg_png2';
		$("#listTab2")[0].className='';
	}
	else if(tag=="Other"){
		$("#OnLineUser_OhterList").show();
		$("#OnLineUser_FindList").hide();
		$("#OnLineUser").hide();
		$("#listTab1")[0].className='';
		$("#listTab2")[0].className='bg_png2';
		UserList.getmylist(My.name);
	}
}
function bt_defvideosrc(){
	if(check_auth('def_videosrc')){
		SysSend.command('setVideoSrc',My.chatid+'_+_'+My.nick);
		$.ajax({type: 'get',url: '../ajax.php?act=setdefvideosrc&vid='+encodeURIComponent(My.chatid)+'&nick='+encodeURIComponent(My.nick)+'&rid='+My.rid});
	}
}
function bt_msgBlock(id){
		SysSend.command('msgBlock',id);
                 $.ajax({type: 'get',url: '../ajax.php?act=msgblock&s=1&msgid='+id});
}
function bt_msgAudit(id,a){
		SysSend.command('msgAudit',id);
		$(a).hide();
                $.ajax({type: 'get',url: '../ajax.php?act=msgblock&s=0&msgid='+id});
}
function bt_FindUser(){
	getId("OnLineUser_OhterList").style.display="none";
	var username=getId('finduser').value;
	getId("OnLineUser_FindList").style.display="none";
	getId("OnLineUser").style.display="";
	//alert(username);
	if(username==""){
		getId("OnLineUser_FindList").style.display="none";
		
		getId("OnLineUser").style.display="";
	}
	else{
		getId("OnLineUser_FindList").style.display="";		
		getId("OnLineUser_FindList").innerHTML="";
		getId("OnLineUser_FindList").style.height=getId("OnLineUser").offsetHeight +'px';
		getId("OnLineUser").style.display="none";
		
		var ulist=UserList.List();
		var li="";
		for(c in ulist){				
			if(ulist[c].nick.toLowerCase().indexOf(username.toLowerCase())>=0){
				//alert(ulist[c].nick);
				li=getId(ulist[c].chatid);
				var t_li=CreateElm(getId("OnLineUser_FindList"),'li',false,'fn'+ulist[c].chatid);
				t_li.innerHTML=li.innerHTML;
				t_li.oncontextmenu=li.oncontextmenu;
				t_li.onclick=li.onclick;
				t_li.ondblclick=li.ondblclick;
			}				
		}
	}
}
var fenping = true;

function bt_fenping(){
	if(fenping == true) {
      fenping = false;
      //getId('btfp').src = 'images/FP_true.gif';
	  getId('drag_bar').style.display="";
	  getId('MsgBox2').style.display="";
	  getId('MsgBox2').style.height='px';
	  getId('MsgBox1').style.height=getId('MsgBox1').offsetHeight-100-getId('drag_bar').offsetHeight+"px";
   } else {
      fenping = true;
      //getId('btfp').src = 'images/FP_false.gif';
	  getId('MsgBox1').style.height=getId('MsgBox').offsetHeight+"px";
	  getId('drag_bar').style.display="none";
	  getId('MsgBox2').style.display="none";
	  
   }
   MsgAutoScroll();
}
var audioNotify=true;
function bt_toggleAudio() {
   if(audioNotify == true) {
      audioNotify = false;
      getId('toggleaudio').src = 'images/Sc.gif';
   } else {
      audioNotify = true;
      getId('toggleaudio').src = 'images/So.gif';
   }
}
var toggleScroll = true;
function bt_toggleScroll()
{
  
	if($("#bt_gundong").hasClass('noscroll')){
		$("#bt_gundong").removeClass('noscroll');
		toggleScroll = true;	
	}
	else {
	       $("#bt_gundong").addClass('noscroll');
		toggleScroll = false;
	}
}

function open_img(src,width,height){

timg =layer.open({
    type: 1,
       title: false,
       shadeClose: true,
        move: '.layui-layer-content',
       moveOut: true,
      skin: 'layui-layer-rim', //加上边框
      area: [width,+'px', height+'px'],
    //  offset : [($(window).height()-height)/2+'px',($(window).width()-width)/2+'px'],
    content:'<div class="tcontent"><img src="'+src+'" /></div>'
});

             layer.style(timg, {
   'box-shadow':'none',
   'background-color': 'transparent'
    
}); 
}
function subStr(str, len, app){
	if(str == undefined){
		return "";
	}
	if(str.length>len){
	   return typeof(app) == 'string' ? str.substr(0, len)+app : str.substr(0, len);
	} 
	 return str;
}
// 获取本机构分析师
function getAnalysts(){
	var html = "";
                    $.ajax({
			type : "POST",
			url : "../ajax.php?act=getAnalysts",
			dataType : "json",
			async : false,
			success : function(data) {
				$.each(data, function(i, u) {
                                   var tempName = u.nickname.length > 6 ? u.nickname.substr(0,6) : u.nickname;
                                      html += "<li><a title='"+u.nickname+"' onClick=\"SendRedBagNew(" + u.uid + ")\">"
						+ tempName + "</a></li>";
	                                 });
			}
		});
		html = "<ul>"+html+"</ul>";
               $("#myimage").html(html);
}

// 红包
function user_hb() {
	//if(!permissionMap.pisShowRedPag){return}
	if ($("#myimage").is(":hidden")) {
		$("#myimage").show();
		if ($("#myimage").html() == '') {
			getAnalysts()
		}
		$("#myimage").width($("#myimage ul").length*81);
	} else {
		$("#myimage").hide();
	}
}
function SendRedBagNew(jid){

	if(My.redbags_num=='0'){
            if(My.color=='0'){
          layer.tips('您现在还没有鲜花,赶快登陆直播室积攒鲜花把！', '.bar_8');
            }else{
            layer.tips('对不起，您没有足够的鲜花！', '.bar_8');
        }
		return false;
	}
	 $.ajax({
         url: "../ajax.php?act=SendRedBagNew",  
         type: "POST",
	  async: true,
         data:{jid:jid},
         dataType: "json",
           success: function(data){
        	if(data.state=="ok"){ 	
                             layer.tips('送鲜花成功！', '.bar_8');
			     $('.RegBagNum span').html(data.num);
				My.redbags_num--;
				 $("#redbags").html(My.redbags_num);
				SysSend.command('sendredbag',data.num+'_+_'+data.msg);
				
			}else{
                  layer.tips('您已经没有鲜花了', '.bar_8');
			}
         }
     });  
}
var _timeRed;
function _toolTimeRed() {
	_timeRed = setTimeout("$('#myimage').hide()", 300)
}
function _toolCloseTimeRed() {
	clearTimeout(_timeRed)
}

// 鲜花提示
function _red() {
	var rewardUrl = "/room/face/swf/xianhua.swf";
	
	var openred = layer.open({
				type : 1,
				moveType : 1,
				shift : -1,
                                shade: 0,
				title : false,
				closeBtn : false,
				area: ['402px', '364px'],
				time : 3000,
				content : '<img width="402px" height="364px" src="/room/images/rose.png">'
			});
                        
          layer.style(openred, {
   'box-shadow':'none',
   'background-color': 'transparent'
    
});
	
}
var deskTopNotice=true
function deskTopNotifications(title, content) {  
    if( deskTopNotice ){
	    var iconUrl = "./images/send-ok.png";  
	      
	    if (window.webkitNotifications) {  
	        if (window.webkitNotifications.checkPermission() == 0) {  
	            var notif = window.webkitNotifications.createNotification(iconUrl, title, content);  
	            notif.display = function() {}  
	            notif.onerror = function() {}  
	            notif.onclose = function() {}  
	            notif.onclick = function() {this.cancel();}  
	            notif.replaceId = 'Meteoric';  
	            notif.show();  
	        } else {  
	            window.webkitNotifications.requestPermission($jy.notify);  
	        }  
	    }  
	    else if("Notification" in window){  
	        if (Notification.permission === "granted") {  
	            var notification = new Notification(title, {  
	                "icon": iconUrl,  
	                "body": content,  
	            });  
	        }  
	        else if (Notification.permission !== 'denied') {  
	            Notification.requestPermission(function(permission) {  
	                if (!('permission' in Notification)) {  
	                    Notification.permission = permission;  
	                }  
	                if (permission === "granted") {  
	                    var notification = new Notification(title, {  
	                        "icon": iconUrl,  
	                        "body": content, 
	                    });  
	                }  
	            });  
	        }  
	    }
	}
}
//deskTopNotifications('桌面通知', '猴年大吉！')


function generateMixed(n) {
	var chars = ['0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

     var res = "";
     for(var i = 0; i < n ; i ++) {
         var id = Math.ceil(Math.random()*35);
         res += chars[id];
     }
     return res;
}