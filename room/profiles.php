<?php
require_once '../include/common.inc.php';
if($_COOKIE['login_uid']=='0'){exit('Access Denied');}
switch($act){
	case "edit":
		$uid=$_COOKIE['login_uid'];
		$guestexp = '^Guest|'.$cfg['config']['regban']."Guest";
                $query=$db->query("select uid,nickname from {$tablepre}memberfields where nickname='$nickname' ");
                $userarr=$db->fetch_row($query);
			if($db->num_rows($query)>0 && $userarr['uid']!=$uid){  
                       $msg="<script>$('.tab').hide();$('#tab_2').show();alert('昵称已经被占用！');</script>";
			$db->query("update {$tablepre}memberfields set mood='$mood' where uid='$uid'");
			$db->query("update {$tablepre}members set  email='$email',realname='$realname',phone='$phone',sex='$sex' where uid='$uid'");
                         }
                 elseif(preg_match("/\s+|{$guestexp}/is", $nickname)&&!check_auth('room_admin')){
			$msg="<script>$('.tab').hide();$('#tab_2').show();alert('昵称禁用！');</script>";
			$db->query("update {$tablepre}memberfields set mood='$mood' where uid='$uid'");
			$db->query("update {$tablepre}members set  email='$email',realname='$realname',phone='$phone',sex='$sex' where uid='$uid'");
		}
		else{
			$db->query("update {$tablepre}memberfields set nickname='$nickname',mood='$mood' where uid='$uid'");
			$db->query("update {$tablepre}members set  email='$email',realname='$realname',phone='$phone',sex='$sex' where uid='$uid'");
			header("location:?uid={$uid}");
		}
	break;
	case "editpwd":
		$uid=$_COOKIE['login_uid'];
		if($pwd1!=$pwd2){
			$msg="<script>$('.tab').hide();$('#tab_3').show();alert('新密码不一致！');</script>";
		}else{
			$query=$db->query("select * from {$tablepre}members where uid='$uid' and password='".md5($oldpwd)."'");
			if($db->num_rows($query)>0){
				$db->query("update {$tablepre}members set  password='".md5($pwd1)."' where uid='$uid'");
				$msg="<script>$('.tab').hide();$('#tab_3').show();alert('密码已修改！');</script>";
			}
			else
			$msg="<script>$('.tab').hide();$('#tab_3').show();alert('旧密码错误！');</script>";
		}
	break;
}
if(isset($editface) and isset($GLOBALS["HTTP_RAW_POST_DATA"]))
{
	$filename='../face/'.$editface.'/'.$_COOKIE['login_uid'].'.gif';
	$somecontent = $GLOBALS["HTTP_RAW_POST_DATA"];
    if (!$handle = fopen($filename, 'w+')) {
         print '{code:"#1057", msg:"不能打开图片"}';
         exit;
    }
    if (!fwrite($handle, $somecontent)) {
        print '{code:"#1058", msg:"不能写图片"}';
        exit;
    }
    print '{code:"#1057", msg:"成功"}';
    fclose($handle);
    if($editface=='p1'){
    $uid=$_COOKIE['login_uid'];
    $db->query("update {$tablepre}memberfields set face=1 where uid='$uid'");
    }
	exit();
}
function GetCurl($url){
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl,CURLOPT_URL, $url);
    curl_setopt($curl,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    $resp = curl_exec($curl);
    curl_close($curl);
    return $resp;
}
$uid = $_COOKIE['login_uid'];
if(!empty($uid)){
    $data = '';
    if($_COOKIE['login_uid']){
        $data = "id={$_COOKIE['login_uid']}";
    }
    $url = "http://user.good100.top/api/user_info?$data";
    //header("Content-type:text/html;charset=utf-8");
    $resp = GetCurl($url);
    $resp = json_decode($resp,true);
    $userinfo = $resp['data'];
    //var_dump($userinfo);exit;
}
//$userinfo=$db->fetch_row($db->query("select m.*,ms.* from {$tablepre}members m,{$tablepre}memberfields ms  where m.uid=ms.uid and m.uid='{$uid}'")); 
//获取推广人数
$tuiguang_num=$db->num_rows($db->query("select uid from {$tablepre}members   where tuser='{$userinfo['username']}'")); 

?>
<!doctype html>
<html>
<head>
<meta name="renderer" content="webkit">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
<link href="../images/base.css" rel="stylesheet" type="text/css"  />
<script src="script/jquery.min.js"></script>
<script src="script/swfobject2.js" ></script>
<title>用户资料</title>
<style>
.main { width: 460px; background: #FDFDFD }
.head { height: 119px; padding-top: 80px; background: url(images/b1.jpg) no-repeat }
#nav { border-top: 1px dotted #FFFFFF; width: 460px; }
#nav a { float: left; display: block; padding: 5px; width: 60px; color: #FFF; background: #09F; font-size: 12px; text-align: center; text-decoration: none; }
#tab { width: 460px; height: 400px; overflow: hidden; font-size: 12px; color: #666 }
#tab td { height: 30px; line-height: 30px; }
#tab tr { border-bottom: 1px dotted #EEEEEE }
#tab input {  border:0px; border-bottom:1px solid #333; width:240px;}
.th { text-align: justify; text-justify: distribute-all-lines; text-align-last: justify; width: 60px; padding-right: 10px; color: #333 }
.user_pic{ width:120px; height:150px; position:absolute; top:203px; right:0px; border:5px solid #FFF}
.user_pic img{width:110px; height:150px; border:1px #CCCCCC solid; padding:1px; }
.bc { border:0px; padding:2px;width:40px; height:20px; color:#FFF; background: #0C0}
.user_edit_pic{ width:460px; height:350px; position:absolute;top:203px; right:0px; background:#FDFDFD; text-align:center; padding-top:30px; display:none}
.tixian{color: #F90 !important;cursor: pointer;}
</style>
</head>

<body>
<div class="main">
  <div class="head">
    <div class="m10 fl" style="text-shadow:2px 2px 2px #000;">
      <div class="fl mr10" style="width:74px; height:74px;"><img src='../face/img.php?t=p1&u=<?=$userinfo['uid']?>' style="width:74px; height:74px; border:0px"/></div>
      <div class="fl" style="width:350px; height:74px;">
        <div style="height:34px; overflow:hidden"><span class="ttff f24 cfff">
          <?=$userinfo['nickname']?>
          </span> &nbsp;<span class="ttff  f14 cfff">
          <?=$userinfo['username']?>
          </span></div>
        <div>
          <?=showstars($userinfo['onlinetime'])?>
        </div>
        <div class="ttff f14 cfff" style="height:20px; overflow:hidden">
          <?=$userinfo['mood']?>
        </div>
      </div>
    </div>
    <div class="cl"></div>
    <div class="fl" id="nav"> 
    <a href="javascript://" onClick="$('.tab').hide();$('#tab_1').show()">用户资料</a>
    <?php
    if($uid==$_COOKIE['login_uid']){
	?>
    <!--<a href="javascript://" style="background:#F63" onClick="$('.tab').hide();$('#tab_2').show()">编辑资料</a>-->
    <a href="javascript://" style="background:#666" onClick="$('.tab').hide();$('#tab_3').show()">修改密码</a>
    <?php }?>
    </div>
    <div class="cl"></div>
  </div>
  <div id="tab">
    <div id="tab_1" style="margin:10px;" class="ttff tab">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  class="th">姓名：</td>
          <td><?php echo $userinfo['name']?></td>
        </tr>
        <tr>
          <td class="th">用户性别：</td>
          <td><?php $sex=array("保密","男","女");echo  $sex[$userinfo['sex']];?>
            &nbsp;</td>
        </tr>
        <tr>
          <td  class="th">昵称：</td>
          <td>&nbsp; <?=$userinfo['nickname']?></td>
        </tr>
        <tr>
          <td class="th">注册时间：</td>
          <td><?php echo $userinfo['created_at'];?>&nbsp;</td>
        </tr>
<?php
if($userinfo['gid']=='3' && $uid!=$_COOKIE['login_uid']){


?>
        <!--<tr>
          <td class="th">客服手机：</td>
          <td><?=$userinfo['phone']?>
            &nbsp;</td>
        </tr>
        <tr>
          <td class="th">客服QQ：</td>
          <td><a href="http://wpa.qq.com/msgrd?v=3&uin=<?=$userinfo['realname']?>&site=qq&menu=yes">
    <?=$userinfo['realname']?></a>
            &nbsp;</td>
        </tr>-->
<?php
}
//后台管理资料
if(check_auth('user_info_gl')){

?>
        <tr>
          <td class="th">登录IP：</td>
          <td><?=$userinfo['regip']?>
            &nbsp;</td>
        </tr>
        <tr>
          <td class="th">邮箱：</td>
          <td><?=$userinfo['email']?>
            &nbsp;</td>
        </tr>
        <tr>
          <td class="th">手机：</td>
          <td><?=$userinfo['phone']?>
            &nbsp;</td>
        </tr>
        <tr>
          <td class="th">QQ：</td>
          <td><a href="http://wpa.qq.com/msgrd?v=3&uin=<?=$userinfo['realname']?>&site=qq&menu=yes">
    <?=$userinfo['realname']?></a>
            &nbsp;</td>
        </tr>
        <tr>
          <td class="th">用户关联：</td>
          <td>用户客服：
            <?=$userinfo['fuser']?>
            &nbsp; 推广人：
            <?=$userinfo['tuser']?></td>
        </tr>
        <tr>
          <td class="th">备注：</td>
          <td><?=$userinfo['sn']?>
            &nbsp;</td>
        </tr>
        <?php }
//end后台管理资料
?>
        <?php
    if($uid==$_COOKIE['login_uid']){
	?>
         <!--<tr >
          <td class="th" >红包余额:</td>
          <td><span id="mymoney2" style="color: #F90 !important;"><?=$userinfo['moneyred']?></span>元&nbsp;&nbsp;&nbsp;&nbsp; <a class="tixian" onclick="tixian(<?=$_COOKIE['login_uid']?>)">(提现)</a></td>
        </tr>
        <tr>
         
          <td colspan="2">
         我的推广链接：  <?='http://'.$_SERVER['HTTP_HOST'].'/?t='.$_COOKIE['login_uid'];?>
            &nbsp;&nbsp;&nbsp;（已推广<?=$tuiguang_num?>人）</td>
        </tr>-->
      <?php }?>
      </table>
      <!--<div class="user_pic"><img src="../face/img.php?t=p2&u=<?=$userinfo['uid']?>"></div>-->
    </div>
    
    <div id="tab_2" style="margin:10px;" class="ttff tab hide ">
    <form action="" method="post" enctype="application/x-www-form-urlencoded">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
              
        <tr>
          <td class="th">用户昵称：</td>
          <td><input name="nickname" type="text" id="nickname" value="<?=$userinfo['nickname']?>"><input type="hidden" name="uid" value="<?=$userinfo['uid']?>"><input type="hidden" name="act" value="edit"></td>
        </tr>
        <tr>
          <td  class="th">用户签名：</td>
          <td><input name="mood" type="text" id="mood" value="<?=$userinfo['mood']?>">  </td>
        </tr>
        <tr>
          <td class="th">联系邮箱：</td>
          <td><input name="email" type="text" id="email" value="<?=$userinfo['email']?>"></td>
        </tr>
        <tr>
          <td  class="th">联系QQ：</td>
          <td><input name="realname" type="text" id="realname" value="<?=$userinfo['realname']?>"> 
            </td>
        </tr>
                <tr>
          <td  class="th">联系手机：</td>
          <td><input name="phone" type="text" id="phone" value="<?=$userinfo['phone']?>"  value="readonly" readonly> 
            </td>
        </tr>
                        <tr>
          <td  class="th">联系性别：</td>
          <td><select name="sex" id="sex">
            <option value="1" <? if($userinfo['sex']==1) echo 'selected'; ?>>男</option>
            <option value="0" <? if($userinfo['sex']==0) echo 'selected'; ?>>女</option>
            <option value="2" <? if($userinfo['sex']==2) echo 'selected'; ?>>保密</option>
          </select>
            </td>
        </tr>
                        <tr>
          <td  class="th">用户头像：</td>
          <td> <span id="ep1"></span> <span id="ep2"></span></td>
        </tr>
                                <tr>
          <td  class="th">&nbsp;</td>
          <td> <input type="submit" value="保存" class="bc" style="width:40px; border:0px;"></td>
        </tr>
      </table>
      </form>
      <div class="user_pic"><img src="../face/img.php?t=p2&u=<?=$userinfo['uid']?>"></div>
      <div class="user_edit_pic"><div id="epf"></div>
      <input type="button" onClick="saveToServer();" class="bc"  value="确定"  style="width:40px; border:0px;"> 
      <input type="button" onClick="$('.user_edit_pic').hide()" class="bc"  value="取消" style="width:40px; border:0px; background:#666"></div>
    </div>

<div id="tab_3" style="margin:10px;" class="ttff tab hide">
    <form action="" method="post" enctype="application/x-www-form-urlencoded">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
              
        <tr>
          <td class="th">旧密码：</td>
          <td><input name="oldpwd" type="password" id="oldpwd" ><input type="hidden" name="uid" value="<?=$userinfo['uid']?>"><input type="hidden" name="act" value="editpwd"></td>
        </tr>
        <tr>
          <td  class="th">新密码：</td>
          <td><input name="pwd1" type="password" id="pwd1">  </td>
        </tr>
        <tr>
          <td class="th">确认密码：</td>
          <td><input name="pwd2" type="password" id="pwd2"></td>
        </tr>
                                <tr>
                                  <td  class="th">&nbsp;</td>
                                  <td> <input type="button" id="resetpwd" value="保存" class="bc" style="width:40px; border:0px;"></td>
          </tr>
      </table>
      </form>
    </div>    
  </div>
</div>
<?=$msg?>
<script type="text/javascript" src="../upload/swfupload/swfupload.js"></script> 
<script type="text/javascript">
$(function(){
    $("#resetpwd").click(function(){
        var oldpwd = $("#oldpwd");
        var pwd1 = $("#pwd1");
        var pwd2 = $("#pwd2");
        if(oldpwd.val() == ''){
            alert("请输入旧密码");
            oldpwd.focus();
            return false;
        }else if(pwd1.val() == ''){
            alert("请输入新密码");
            pwd1.focus();
            return false;
        }else if(pwd2.val() == ''){
            alert("请确认新密码");
            pwd2.focus();
            return false;
        }else if(pwd1.val() != pwd2.val()){
            alert("两次输入的密码不一致");
            pwd1.focus();
            return false;
        }else{
            $.ajax({
                type: 'get', dataType: 'jsonp', cache: false,
                url: 'http://user.good100.top/api/reset_pwd',
                data: {'id':<?php echo $uid?>,'oldpwd':oldpwd.val(),'newpwd':pwd2.val()},
                success: function(data){
                    if(data.status == 0){
                        alert("修改成功,请重新登陆");
                        window.top.location.href = "../logging.php?act=logout";
                    }else{
                        alert(data.info);
                    }
                }
            });
        }
    });
});
function thisMovie(movieName)
{
	if (navigator.appName.indexOf("Microsoft") != -1)
	{
		return window[movieName];
	}
	else
	{
		return document[movieName];
	}
}
function loadImage()
{
	thisMovie("PhotoEditor").loadImage(img);
}
function flashInit(arg)
{
	setTimeout('loadImage()', 1000);
}
function init()
{
}
function flashError(event)
{
	//alert(event.code);
	switch(event.code)
	{
		case 0:
		if (event.msg == "图片加载成功！")
		{
			return;
		}
		if (1)
		{
			location.reload();
		}
		break;
		case "#1057":
		//Fid('errDiv').innerHTML = '选择的照片太小，请';
		break;
	}
}
function saveToServer()
{
	var b=thisMovie('PhotoEditor').saveToServer('profiles.php?editface='+ept+'&w');
}
  function swfupload_ok(fileObj,server_data){
	  $('.user_edit_pic').show();
	  var data=eval("("+server_data+")") ;
	  img=data.msg.url;
	  var wh;
	  if(data.msg.info=="ep1"){wh="slice_width=100&slice_height=100"; ept='p1'}
	  else {wh="slice_width=110&slice_height=150"; ept='p2'}
	  
	  var so = new SWFObject("../images/PhotoEditor.swf?imageEncoder=jpg&jpgQuality=97&"+wh, 'PhotoEditor', "325", "312", "9", "#FFF");	
		  so.write("epf");
	 
	 //alert(server_data);
	 //var data=eval("("+server_data+")") ;
	 //$("#"+data.msg.info).val(data.msg.url);
  }
  $(function(){


  var swfdef={
	  // 按钮设置
	    file_post_name:"filedata",
		button_width: 60,
		button_height: 28,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		button_text: '修改头像',
		button_text_style: ".upbnt{}",
		button_text_left_padding: 0,
		button_text_top_padding: 10,
		upload_success_handler : swfupload_ok,
		file_dialog_complete_handler:function(){this.startUpload();},
		file_queue_error_handler:function(){alert("选择文件错误");}
		}
  swfdef.flash_url="../upload/swfupload/swfupload.swf";
  swfdef.button_placeholder_id="ep1";
  swfdef.file_types="*.png;*.jpg;*.gif";
  swfdef.upload_url="../upload/upload.php";
  swfdef.post_params={"info":"ep1"}
  
  swfu = new SWFUpload(swfdef);
  
  var swfico=swfdef;
  swfico.button_text='修改照片';
  swfico.button_placeholder_id="ep2";
  swfico.post_params={"info":"ep2"}
  swfuico = new SWFUpload(swfico);
	  
});
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
function tixian(uid){ 
var a;
 a = '<div id="setEnvelope">',
            a += '<div class="redbagclose"></div>',
            a += '<div class="envelopeBody">',
            a += '<div class="mt10 pt10" style="color: #666 !important;font-family: Microsoft Yahei,Verdana, Geneva, sans-serif;margin-left: 50px;font-size: 18px;"><span class="f_14">提现方式：</span> <input type="radio" name="type" value="1" class="tixian_type" checked="checked">支付宝<input type="radio" name="type" value="2" class="tixian_type">银行卡</div>',
            a += '<div class="mt10"><div class="registerNewconter" style="padding-right:55px;"><input type="text" id="username" name="username" maxlength="10" value="输入您的姓名" title="输入您的姓名"></div><p class="envelopetip" id="realusernametip" style="margin-left:97px;"></p></div>',
            a += '<div class="mt10"><div class="registerNewconter" style="padding-right:55px;"><input type="text" name="bankcard" id="bankcard" maxlength="22" value="输入您的银行卡号或支付宝账户" title="输入您的银行卡号或支付宝账户"> </div><p class="envelopetip" id="realbankcardtip" style="margin-left:97px;"></p></div>',
            a += '<div class="mt10"><div class="registerNewconter" style="padding-right:55px;"><input type="text" name="realmoney" id="realmoney" maxlength="6" value="输入提现金额（最低不少于100元）" title="输入提现金额（最低不少于100元）"> </div><p class="envelopetip" id="realmoneytip" style="margin-left:97px;"></p></div>',
            a += '<div class="registerNewconter" style="padding-right:0;"><p style="font-size:14px;color:#fc4c4c;">可提现总额￥<span id="mymoney1">' + $("#MyMoney",parent.document).val() + "</span>元</p></div>",
            a += '<div class="registerNewconter"><button class="btnEnvelope mt10" id="btnenvelope">确定</button><button class="btnEnvelope mt10" id="btnenvelopeing">正在提交..</button></div>',
            a += "</div>",
            a += "</div>";
            $("body").append(a),
            center("#setEnvelope", !0),
            $("#setEnvelope input").focus(function() {
                $(this).val() == $(this).attr("title") && ($(this).val(""))
            }),
            $("#setEnvelope input").blur(function() {
                "" == $(this).val() && $(this).val($(this).attr("title"))
            });
}

$("body").on("click", ".redbagclose",
    function() {
        $(this).parent().remove()
    });
    $("body").on("click", "#btnenvelope",function() {  
                var mymoney=$("#MyMoney",parent.document).val();
                var t=$("input[name='type']:checked").val();
                var a, d, e, f,g,m,k,y;
                 e= new RegExp("^([\u4E00-\uFA29]|[\uE7C7-\uE7F3]){2,6}$"); 
                 g= new RegExp("^([a-zA-Z]|[0-9]|@|[.]){5,25}$");
                 k = /^[0-9]+(\.[0-9]+)?$/,
                a = 0, d = $.trim($("#username").val()), e.test(d)?(d != $("#username").attr("title")? $("#realusernametip").text(""):a++):($("#realusernametip").text("请填写正确的姓名"),a++),
                f=$.trim($("#bankcard").val()),g.test(f)?$("#realbankcardtip").text(""):( f != $("#bankcard").attr("title") && $("#realbankcardtip").text("请填写正确的银行卡或支付宝账户"),a++),
                m = $.trim($("#realmoney").val()), k.test(m) && "0" != m && "0.0" != m && "0.00" != m ? (m = Math.floor(100 * m),m<10000 ? ($("#realmoneytip").text("提现金额最低不少于100元"),a++): $("#realmoneytip").text(""), y=$("#MyMoney",parent.document).val(),y = Math.floor(100 * y),m>y?( $("#realmoney").val($("#MyMoney",parent.document).val()),$("#realmoneytip").text("余额不足 最多提现" + $("#MyMoney",parent.document).val() + "元"),a++):'') : (m != $("#realmoney").attr("title") && ("0" == m || "0.0" == m || "0.00" ==m ? $("#realmoneytip").text("金额不能为0") : $("#realmoneytip").text("请填写正确的金额")), a++),
               0 == a && (m=$("#realmoney").val(), $.post("/room/UserCenter/tixian.php?act=GetMoney", {
                    type:t,
                    username: d,
                    bankcard: f,
                    getmoney: m
                },
                function(a) {
                    if(a.status=='ok'){mymoney=Math.floor(100 * mymoney),a.tixian=Math.floor(100 * a.tixian),mymoney=mymoney-a.tixian,mymoney /= 100, mymoney.toString().indexOf(".") < 0 && (mymoney += ".00"),$("#mymoney2").text(mymoney),$("#realmoney").val(mymoney), $("#MyMoney",parent.document).val(mymoney);$("#realmoney").val();$("#setEnvelope").remove();alert(a.msg);}else{alert('余额不足');}
                } ,"json"))
            });
</script>
</body>
</html>
