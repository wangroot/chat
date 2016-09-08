<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'users_admin')===false)exit("没有权限！");
if($act=="user_add"){
	user_add($realname,$password,$phone,$gid,1,$username);
          echo "<script>window.parent.dialog2.close();alert('用户添加成功！');window.parent.location.reload();</script>";
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/page-min.css" rel="stylesheet" type="text/css" />
<!-- 下面的样式，仅是为了显示代码，而不应该在项目中使用-->
<link href="../assets/css/prettify.css" rel="stylesheet" type="text/css" />
<style type="text/css">
code { padding: 0px 4px; color: #d14; background-color: #f7f7f9; border: 1px solid #e1e1e8; }
input,select{vertical-align:middle;}
.liw { width:160px; height:25px; line-height:25px;}
</style>
</head>
<body>
<div class="container" >
<?php
$query=$db->query("select * from {$tablepre}auth_group order by id desc");
while($row=$db->fetch_row($query)){
    if($row[id]==1){$selected='selected';}else{$selected='';}
	$group.='<option value="'.$row[id].'"'.$selected.'>GID:'.$row[id].'-'.$row[title].'</option>';
}
if(stripos(auth_group($_SESSION['login_gid']),'users_group')===false){
	$group='';
}

?>
<form action="user_add.php" method="post" enctype="application/x-www-form-urlencoded" id="regUser">
  <ul class="breadcrumb">

<table class="table table-bordered table-hover definewidth m10">
			  <tr>
            <td width="80" class="tableleft" style="width:70px;">用户名：</td>
            <td><input name="username" type="text" id="username" style="width:350px;" /></td>
          </tr>
	
          <tr>
            <td width="80" class="tableleft" style="width:70px;">QQ号码：</td>
            <td><input name="realname" type="text" id="realname" style="width:350px;" /></td>
          </tr>
          <tr>
            <td width="80" class="tableleft">用户密码：</td>
            <td><input name="password" type="text" id="password" /></td>
          </tr>
          <tr>
            <td width="80" class="tableleft">手机号码：</td>
            <td><input name="phone" type="text" id="phone" style="width:350px;" /></td>
          </tr>
          <tr>
            <td width="80" class="tableleft">用 户 组：</td>
            <td><select name="gid" id="gid" >
	      <?=$group?>
            </select>&nbsp;</td>
          </tr>
       
         
        </table>

  </ul>
  <div style=" background: #FFF; width:100%; ">

     <input type="button" id="reg"  class="button button-success" value="确定"/>
    <button type="button"  class="button" onclick="window.parent.dialog2.close()">关闭</button>
    <input type="hidden" name="act" value="user_add">
</div>
  </form>

</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui.js"></script> 
<script type="text/javascript" src="../assets/js/config.js"></script> 
<script type="text/javascript" src="../../upload/swfupload/swfupload.js"></script> 
<script>


$(document).ready(function() {



	$('#reg').click(function() {

		if ($.trim($('#username').val()) == "") {
			$('#username').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
		alert('用户名不能为空！');
			return false;
		}
             var u = /[=|+-]/ ;
                  if(u.test($('#username').val())){
                    $('#username').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
		
                        alert('用户名中不能包含+ | - =特殊字符！');
			return false;  
                  }
            if ($('#password').val().length < 6) {
			$('#password').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
			alert('密码不能小于6位！');
			return false;
		}

   var phone = /^1[0-9]{10}$/ ;
                  if ($.trim($('#phone').val()) == "" || !phone.test($('#phone').val())) {
			$('#phone').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
			alert('手机格式不正确！');
			return false;
		}
             
             
		
	
		

		$('#regUser').submit();
	});
	

});
</script>
</body>
</html>
