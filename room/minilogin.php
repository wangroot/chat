<?php
require_once '../include/common.inc.php';
switch($act){
	case "loginapi":

		if (strtolower($code)!=strtolower($_SESSION['vcode'])){
		   $data = array('status' =>false);
		   echo json_encode($data);
		}else{
		   $data = array('status' =>true);
		   echo json_encode($data);
		}
        exit;
		$msg=user_loginapi($username,$password);
		
		if($msg===true){exit("<script>top.location.reload(true);location.href='./';</script>");}
		else{ echo "<script>top.layer.msg('{$msg}',{shift: 6});layer.msg('{$msg}',{shift: 6});</script>";}
	break;
	case "reg":
		$guestexp = '^Guest|'.$cfg['config']['regban']."Guest";
		if(preg_match("/\s+|{$guestexp}/is", $u))
		exit("<script>alert('用户名禁用！');</script>");
                if($u=="")
		exit("<script>alert('用户名不能为空！');</script>");
		
		$query=$db->query("select uid from {$tablepre}members where username='{$u}' limit 1");
		if($db->num_rows($query))exit("<script>alert('用户名已经被使用!换一个，如{$u}2015');location.href='?a=0'</script>");

		$regtime=gdate();
		$p=md5($p);
		if(isset($_COOKIE['tg'])){
		$tuser=userinfo($_COOKIE['tg'],'{username}');}else{
                   $tuser='system'; 
                }
                $fuser=$_SESSION['guest_fuser'];
		if($cfg['config']['regaudit']=='1')$state='0';
		else $state='1';
		
		$db->query("insert into {$tablepre}members(username,password,sex,email,regdate,regip,lastvisit,lastactivity,gold,realname,gid,phone,fuser,tuser,state)	values('$u','$p','2','$email','$regtime','$onlineip','$regtime','$regtime','0','$qq','1','$phone','$fuser','$tuser','$state')");
		$uid=$db->insert_id();
		$db->query("replace into {$tablepre}memberfields (uid,nickname)	values('$uid','$u')	");
		
		$db->query("insert into  {$tablepre}msgs(rid,ugid,uid,uname,tuid,tname,mtime,ip,msg,type)
	values('{$cfg[config][id]}','1','{$uid}','{$u}','{$cfg[config][defvideo]}','{$cfg[config][defvideonick]}','".gdate()."','{$onlineip}','用户注册','2')
		");
		
		$msg=user_login($u,$p2);
		if($msg===true){exit("<script>top.location.reload(true);location.href='./';</script>");}
		else{ echo "<script>top.layer.msg('注册成功！$msg',{shift: 6});layer.msg('注册成功！$msg',{shift: 6});</script>";}
	break;
	case "logout":
		unset($_SESSION['login_uid']);
		unset($_SESSION['login_user']);
		// session_destroy(); 
		// foreach($_COOKIE as $key=>$value){
		//  setCookie($key,"",time()-60);
		// };
		// unset($_COOKIE);
		header("location:./");
	break;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=$cfg['config']['title']?> 迷你登录</title>
<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="default">
<link href="images/minilogin.css" rel="stylesheet" type="text/css"  />
<script src="script/jquery.min.js"></script>
<script src="script/layer.js"></script>
<script src="script/function.js"></script>

</head>

<body>
<div class="login">
    
    <div class="header">
        <div class="switch" id="switch"><a class="switch_btn_focus" id="switch_qlogin" href="javascript:void(0);" tabindex="7">快速登录</a>
			<a class="switch_btn" id="switch_login" href="javascript:void(0);" tabindex="8">快速注册</a><div class="switch_bottom" id="switch_bottom" style="position: absolute; width: 66px; left: 0px;"></div>
        </div>
    </div>    
  
    
    <div class="web_qr_login" id="web_qr_login" style="display: block; height: 270px;">    

            <!--登录-->
            <div class="web_login" id="web_login">
               
               
               <div class="login-box">
    
            
			<div class="login_form">
				<!-- <form action="?act=loginapi" method="post" enctype="application/x-www-form-urlencoded"  name="loginform"  id="login_form" class="loginForm" onsubmit="return  validates()" > -->
                <div class="uinArea" id="uinArea">
                <label class="input-tips" for="username">帐号：</label>
                <div class="inputOuter" id="uArea">
                    <input type="text" id="username" name="username" class="inputstyle"/>
                </div>
                </div>
                
                <div class="pwdArea" id="pwdArea">
               <label class="input-tips" for="password">密码：</label> 
               <div class="inputOuter" id="pArea">
                    
                    <input type="password" id="password" name="password" class="inputstyle"/>
                </div>
                </div>  
                <div class="pwdArea" id="pwdArea">
               <label class="input-tips" for="password">验码：</label> 
               <div class="inputOuter" id="pArea" style="width:220px">
                    
                    <input type="code" id="code" name="code" class="inputstyle" style="width:90px" />
                    <a id="ref-img" href="javascript:void(0)" class="input-group-addon input-captcha" style="padding: 5px 18px;display: block;float: right;">
                    <img id="captcha-img" title="点击更新"  src="../ny_admin/inc/vcode.php" onClick="this.src='../ny_admin/inc/vcode.php?'+Math.random()" alt="看不清，点击刷新">
                                                          
                    </a>

                </div>
                </div>
               
                <div style="padding-left:30px;margin-top:20px;"><input type="submit" id="loginsubmit" value="登 录"  style="width:150px;" class="button_blue"/><a onclick="layer.msg('忘记密码，请联系客服！',{shift: 6});" class="zcxy" target="_blank">忘记密码？</a></div>
              <!-- </form> -->
           </div>
           
            	</div>
               
            </div>
            <!--登录end-->
  </div>
<script type="text/javascript" src="../js/jquery.cookie.js"></script>
  <script type="text/javascript">
  	
    $(function(){
    	$('#loginsubmit').click(function(){

            var ip='<?php echo $_SERVER["REMOTE_ADDR"];?>';
          
			if(!checkusername($("#username").val()))return false;
            if(!checkpassword($("#password").val()))return false;
            if ($.trim($('#code').val()) == "" ) {
               layer.msg('验证码不能为空！',{shift: 6});
			   $('#code').focus();
			   return false;
		    }
           
            var  inputcode=$('#code').val().toLocaleLowerCase();

             $.post(self.location.href+'/room/minilogin.php?act=loginapi',{code:inputcode},function(data){
                 if(data.status==false){
                   
             	   layer.msg('验证码不正确！',{shift: 6});return false;

             	 };
                 $.ajax({
			    		type: 'get',
			    		dataType: 'jsonp',
			    	    cache: false,
			    		url: 'http://user.good100.top/api/user_login',
			    		data: {'uid':$('#username').val(), 'pwd':$('#password').val(),'ip':ip},
			    		success: function(data){
			    			if(data.status=='0'){
			    			console.log(data.follows);
			    			if(data.data.uid==1){
                                    $.cookie('automsg',data.data.color,{path:'/'});
                               };
                               var roomids = '';
                               //console.log(data.follows); return;
                               $.each(data.follows,function(i,item){
                               		roomids += item.roomid+'@';
                               });
                              // console.log(roomids); return;
                               if (roomids != '') {
                               	roomids = roomids.substr(0,roomids.length -1);
                               }
                               $.cookie('login_uid',data.data.id,{path:'/'});
                               $.cookie('user_roomid',roomids,{path:'/'});
                               setCookie('automsg',data.data.color);
                               $.cookie('username',$('#username').val(),{path:'/'});
                               $.cookie('uid',data.data.id,{path:'/'});
                               $.cookie('uname',data.data.name,{path:'/'});
                               $.cookie('qx',data.data.qx,{path:'/'});
                               $.cookie('cam',data.data.cam,{path:'/'});
                               $.cookie('state',data.data.state,{path:'/'});
                               $.cookie('mood',data.data.mood,{path:'/'});
                               $.cookie('color',data.data.color,{path:'/'});
                               $.cookie('power',data.data.power,{path:'/'});
                               // console.log(data.data);return;

                              parent.location.reload();
                             // self.opener.location.reload();
                              // window.opener.location.reload();
                                
			    			   var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                parent.layer.close(index);

			    			}else{
			    				alert(data.info);//非法操作
			    				
			    			}
			    			
			    			
			    		}
			    	});
             	     
             },'json');
	    });

			});

  </script>



 

  <!--注册-->
  <script>
          function get_yzm(){
		var static1 = $('#phone').val()
		var phone = /^1[0-9]{10}$/ ;
		
		if(!phone.test(static1)){
              $('#phone').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
              alert("请正确输入手机号码！");
               return false;
         }
		$.post("/apps/suiji_duanxin.php?action=call_yzm",{static:static1},function(data){
			if(data['status'] == 1){
				alert('手机验证码将以短信方式发送到您的手机，请注意接收！');
                verification_countdown--;
                u_class = '.getverify_sms';
                verification_settime(u_class);
                $(u_class).attr('disabled',true);
                               
				}else{
					alert("发送失败！请联系管理员！");
                u_class = '.getverify_sms';
                verification_settime(u_class);
					}
			},'json');
		}
          var verification_countdown=60;
          var verification_timeID;
          var verification_timeID_array = new Array();


          function verification_settime(u_class) {
              if (verification_countdown == 0) {
                  verification_countdown = 60;
                  $(u_class).removeAttr('disabled');
                  $(u_class).val('获取验证码');
                  for(i=0;i<verification_timeID_array.length;i++){
                      clearTimeout(verification_timeID_array[i]);
                  }
                  verification_timeID_array = new Array();

              } else if(verification_countdown == 60) {

              }else{
                  $(u_class).val(verification_countdown+'秒后重发')
                  verification_countdown--;
                  verification_timeID = setTimeout(function() {
                      verification_settime(u_class)
                  },1000)
                  verification_timeID_array.push(verification_timeID)
              }
          }
    </script>
    <div class="qlogin" id="qlogin" style="display: none; ">
   
    <div class="web_login"> 

    <!-- <form action="?act=reg" method="post" enctype="application/x-www-form-urlencoded" id="regUser"> -->
        <ul class="reg_form" id="reg-ul">
        		<div id="userCue" class="cue">快速注册请注意格式</div>
                <li>
                	
                    <label for="user"  class="input-tips2">用户名：</label>
                    <div class="inputOuter2">
                        <input type="text" id="u" name="u" maxlength="16" class="inputstyle2" placeholder="3-15位字符"/>
                    </div>
                    
                </li>
                  <li>
                <label for="passwd" class="input-tips2">密码：</label>
                    <div class="inputOuter2">
                        <input type="password" id="p"  name="p" maxlength="16" class="inputstyle2"/>
                    </div>
                    
                </li>
                <li>
                <label for="passwd2" class="input-tips2">确认密码：</label>
                    <div class="inputOuter2">
                        <input type="password" id="p2" name="p2" maxlength="16" class="inputstyle2" />
                    </div>
                    
                </li>             
                <li>
                    <div class="inputArea">
                        <input type="button" id="user_reg"  style="margin-top:10px;margin-left:85px;" class="button_blue" value="免费注册"/>
                    </div>
                    
                </li><div class="cl"></div>
            </ul>
            <!-- </form> -->
           
    
    </div>
  <script type="text/javascript">
  	
  	function register() {
		 
}
  </script>

<script type="text/javascript">
	var  pwdmin = 6;

    $(function(){

    		$('#user_reg').click(function(){
               
	    	   if ($.trim($('#u').val()) =='') {
					$('#u').focus().css({
						border: "1px solid red",
						boxShadow: "0 0 2px red"
					});
					$('#userCue').html("<font color='red'><b>×用户名不能为空</b></font>");
					return false;
				}else{
                    $('#u').removeAttr('style');
					$('#userCue').html('');
				}

           var u = /[=|+-]/ ;
            if(u.test($('#u').val())){
                    $('#u').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
			$('#userCue').html("<font color='red'><b>×用户名中不能包含+ | - =特殊字符</b></font>");
			return false;  
                  }
                      if ($('#u').val().length < 3 || $('#u').val().length > 15) {

			$('#u').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
			$('#userCue').html("<font color='red'><b>×用户名为3-15字符</b></font>");
			return false;

		}

			if ($('#p').val().length < pwdmin) {
			$('#p').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
			$('#userCue').html("<font color='red'><b>×密码不能小于" + pwdmin + "位</b></font>");
			return false;
			}else {
				$('#p').css({
					border: "1px solid #D7D7D7",
					boxShadow: "none"
				});
				
			}
			if ($('#p2').val() != $('#p').val()) {
				$('#p2').focus().css({
					border: "1px solid red",
					boxShadow: "0 0 2px red"
				});
				$('#userCue').html("<font color='red'><b>×两次密码不一致！</b></font>");
				return false;
			}else {
				$('#p2').css({
					border: "1px solid #D7D7D7",
					boxShadow: "none"
				});
				
			}
                $.ajax({
			    		type: 'get',
			    		dataType: 'jsonp',
			    		async:false,
			    		url: 'http://user.good100.top/api/user_register',
			    		beforeSend:function(argument) {
			    	    	 register(); 
			    	    },
			    		data: {'name':$('#u').val(), 'password':$('#p').val()},
			    		success: function(data){

			    			   if(data.status==0){
			    			   	    $('#userCue').html("<font color='green'><b>√注册成功</b></font>");
			    		
			    				    setTimeout('closeindex()',3000);
			    			   }else if(data.status==2){

			    			   		  $('#userCue').html("<font color='red'><b>×包含敏感词</b></font>");
			    			   		  $('#userCue').addClass('shake');
			    			   }
			    				
			    		}	    		
			    	});

    		})

    })
    function closeindex() {
    	var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
    	parent.layer.close(index);
    }
</script>
    <style type="text/css">
    	  
		@-webkit-keyframes shake {  
		  
		    0%, 100% {-webkit-transform: translateX(0);}  
		    10%, 30%, 50%, 70%, 90% {-webkit-transform: translateX(-10px);}  
		    20%, 40%, 60%, 80% {-webkit-transform: translateX(10px);}  
		}  
		@-moz-keyframes shake {  
		    0%, 100% {-moz-transform: translateX(0);}  
		    10%, 30%, 50%, 70%, 90% {-moz-transform: translateX(-10px);}  
		    20%, 40%, 60%, 80% {-moz-transform: translateX(10px);}  
		}  

    	#userCue.shake{  
		    -webkit-animation-name:shake;  
		    -webkit-animation-duration:1s;  
		    -moz--name:shake;  
		    -moz-animation-duration:1s;  
		 }  
    </style>
    </div>
    <!--注册end-->
</div>
<script>
$(function(){
	
	$('#switch_qlogin').click(function(){
		$('#switch_login').removeClass("switch_btn_focus").addClass('switch_btn');
		$('#switch_qlogin').removeClass("switch_btn").addClass('switch_btn_focus');
		$('#switch_bottom').animate({left:'0px',width:'66px'});
		$('#qlogin').css('display','none');
		$('#web_qr_login').css('display','block');
		try{
		parent.layer.iframeAuto(parent.layer.getFrameIndex(window.name));
		}catch(e){}
		});
	$('#switch_login').click(function(){
		
		$('#switch_login').removeClass("switch_btn").addClass('switch_btn_focus');
		$('#switch_qlogin').removeClass("switch_btn_focus").addClass('switch_btn');
		$('#switch_bottom').animate({left:'152px',width:'66px'});
		
		$('#qlogin').css('display','block');
		$('#web_qr_login').css('display','none');
		try{
		parent.layer.iframeAuto(parent.layer.getFrameIndex(window.name));
		}catch(e){}
		});
		if(getParam("a")=='0')
		{
			$('#switch_login').trigger('click');
		}

	});
	
function logintab(){
	scrollTo(0);
	$('#switch_qlogin').removeClass("switch_btn_focus").addClass('switch_btn');
	$('#switch_login').removeClass("switch_btn").addClass('switch_btn_focus');
	$('#switch_bottom').animate({left:'152px',width:'66px'});
	$('#qlogin').css('display','none');
	$('#web_qr_login').css('display','block');
	
}


//根据参数名获得该参数 pname等于想要的参数名 
function getParam(pname) { 
    var params = location.search.substr(1); // 获取参数 平且去掉？ 
    var ArrParam = params.split('&'); 
    if (ArrParam.length == 1) { 
        //只有一个参数的情况 
        return params.split('=')[1]; 
    } 
    else { 
         //多个参数参数的情况 
        for (var i = 0; i < ArrParam.length; i++) { 
            if (ArrParam[i].split('=')[0] == pname) { 
                return ArrParam[i].split('=')[1]; 
            } 
        } 
    } 
}  
    function checkusername(username) {
        
        username = $.trim(username);
        
       
        if(username==''){
      
            layer.msg('用户名不能为空！',{shift: 6});
            $("#username").focus();
            return false;
            
        }
        return true;
    }
    function checkpassword(pw)
    {
        pw = $.trim(pw);
        
        if(pw==''){
            layer.msg('密码不能为空！',{shift: 6});
            $("#password").focus();
            return false;
            
        }
        return true;
        
	
    }
    function validates()
    {
      
        if(!checkusername($("#username").val()))return false;
        if(!checkpassword($("#password").val()))return false;
        
        
    }

var reMethod = "GET",
	pwdmin = 6;

$(document).ready(function() {


	$('#user_reg2').click(function() {
                  
		if ($.trim($('#u').val()) == "") {
			$('#u').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
			$('#userCue').html("<font color='red'><b>×用户名不能为空</b></font>");
			return false;
		}
                    var u = /[=|+-]/ ;
                  if(u.test($('#u').val())){
                    $('#u').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
			$('#userCue').html("<font color='red'><b>×用户名中不能包含+ | - =特殊字符</b></font>");
			return false;  
                  }
                      if ($('#u').val().length < 3 || $('#u').val().length > 15) {

			$('#u').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
			$('#userCue').html("<font color='red'><b>×用户名为3-15字符</b></font>");
			return false;

		}
		$.ajax({
			type: reMethod,
                        async: true,
			url: '../ajax.php?act=regcheck',
			data: "username=" + $("#u").val() + '&temp=' + new Date(),
			dataType: 'html',
			success: function(result) {

				if (result!='1') {
					$('#u').focus().css({
						border: "1px solid red",
						boxShadow: "0 0 2px red"
					});
					if(result=='-1')
					$("#userCue").html("<font color='red'><b>×用户名含关键字，不能使用！</b></font>");
					else if(result=='0')
					$("#userCue").html("<font color='red'><b>×用户名被占用！</b></font>");
					return false;
				} else {
					$('#u').css({
						border: "1px solid #D7D7D7",
						boxShadow: "none"
					});
				}

			}
		});
         if ($('#p').val().length < pwdmin) {
			$('#p').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
			$('#userCue').html("<font color='red'><b>×密码不能小于" + pwdmin + "位</b></font>");
			return false;
		}else {
			$('#p').css({
				border: "1px solid #D7D7D7",
				boxShadow: "none"
			});
			
		}
		if ($('#p2').val() != $('#p').val()) {
			$('#p2').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
			$('#userCue').html("<font color='red'><b>×两次密码不一致！</b></font>");
			return false;
		}else {
			$('#p2').css({
				border: "1px solid #D7D7D7",
				boxShadow: "none"
			});
			
		}

		$('#regUser').submit();
	});
	

});


</script>
</body>
</html>
