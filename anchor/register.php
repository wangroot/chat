<?php
if (!session_id()){
    session_start();
}

require_once 'data.php';

$uid = $_POST['uid'];
$pwd = substr(md5($md5_code.$_POST['pwd']),4,24);
$name = $_POST['name'];
$photo = $_POST['photo'];
$intro =$_POST['intro'];
$is_online =0;
//拼装sql语句添加数据
if(!empty($_POST['uid'])){
	 $sql = "select * from {$table_prefix}anchor where uid='$uid'";
	 $userid = mysqli_query($conn,$sql);
	 $userid = mysqli_fetch_assoc($userid);
	 if($userid['uid'] == $uid){
		 echo json_encode(array('status'=>4,'info'=>'账号以注册'));
	 }else{
		$sql = "insert into {$table_prefix}anchor (uid,pwd,name,photo,introduction,is_online) values('".$uid."','".$pwd."','".$name."','".$photo."','".$intro."','".$is_online."')";
		$user = mysqli_query($conn, $sql);
		if ($user){
			echo json_encode(array('status'=>0,'info'=>'注册成功'));
		}else{
			echo json_encode(array('status'=>4,'info'=>'注册失败'));
		}
	 }
	
    exit;
	
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>注册</title>
	<link rel="stylesheet" type="text/css" href="js/uploadify/uploadify.css">
    <style type="text/css" rel="stylesheet">
        #main{width: 100%;}
        #main table th{width: 80px;text-align: right;}
        #main table td .txt{border: solid 1px #999;width: 200px;height: 26px;}
    </style>
</head>
<body>
    <div id="main">
        <table border="0" cellpadding="10" cellspacing="0" width="50%">
            <tr>
                <th>用户名：</th>
                <td><input class="txt" type="text" id="uid" title="用户名"></td>
            </tr>
			<tr>
                <th>姓名：</th>
                <td><input class="txt" type="text" id="name" title="姓名"></td>
            </tr>
            <tr>
                <th>密码：</th>
                <td><input class="txt" type="password" id="pwds" title="密码"></td>
            </tr>
			<tr>
                <th>确认密码：</th>
                <td><input class="txt" type="password" id="pwd" title="确认密码"></td>
            </tr>
			<tr>
				<th>头像</th>
					<td>
						<input type="file" id="photo">
						<input type="hidden" id="path">
						<input type="hidden" id="thumb">
					</td>
			</tr>
			<tr>
					<th>简介</th>
					<td><script id="editor" type="text/plain" style="width:400px;height:200px;"></script></td>
			</tr>
            <tr>
                <th></th>
                <td><input type="button" id="submit" value="注册"></td>
            </tr>
        </table>
    </div>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/layer/2.1/layer.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/layer/2.1/layer.js"></script>
	<script type="text/javascript" src="js/uploadify/jquery.uploadify.min.js"></script>
	<script type="text/javascript" charset="UTF-8" src="js/ueditor/ueditor.config.js"></script>
	<script type="text/javascript" charset="UTF-8" src="js/ueditor/ueditor.all.min.js"></script>
	<script type="text/javascript" charset="UTF-8" src="js/ueditor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript">
        $(function () {
			var ue = UE.getEditor('editor');
                $("#class_begin").click(function () {
                });
                $('#photo').uploadify({
                    //'auto':false,
                    'swf':'js/uploadify/uploadify.swf',
                    'uploader':'upload.php',
                    'fileTypeExts':'*.jpg; *.gif; *.png',
                    'fileTypeDesc':'请选择图片',
                    'buttonText':'选择图片',
                    'height':22,
                    'width': 100,
                    'multi':false,
                    'onUploadSuccess':function(file,data,response){
                        var str = JSON.parse(data);
                        $("#path").val(str.path);
                        $('#thumb').val(str.thumb);
                    }
                });
				
            $("#submit").click(function () {
                var uid = $("#uid");
                var pwd = $("#pwd");
                var pwds = $("#pwds");
                var name = $("#name");
                var photo = $("#path").val();
				var intro = ue.getContent();
				//alert(photo);
                if (uid.val() == ''){
                    layer.msg('用户名不能为空', {icon: 2, time: 1000}, function () {
                        uid.focus();
                    });
                    return false;
                }
				if(name.val() == ''){
					yer.msg('昵称不能为空', {icon: 2, time: 1000}, function () {
                        name.focus();
                    });
                    return false;
				}
				if(pwd.val() == ''){
					layer.msg('密码不能为空', {icon: 2, time: 1000}, function () {
                        pwd.focus();
                    });
                    return false;
				}
				if(pwds.val() == ''){
					layer.msg('确认密码不能为空', {icon: 2, time: 1000}, function () {
                        pwds.focus();
                    });
                    return false;
				}
				if(pwd.val() != pwds.val()){
                    layer.msg('密码不一致', {icon: 2, time: 1000}, function () {
                        pwd.focus();
                    });
                    return false;
                }else{
                    $.ajax({
                        type: 'post', dataType: 'json', cache: false,
                        url: './register.php',
                        data: {'uid': uid.val(), 'pwd': pwd.val(),'name':name.val(),'photo':photo,'intro':intro},
                        success: function (data) {
                            if (data.status == 0){
                                layer.msg(data.info, {icon: 6, time: 1000}, function () {
                                    window.location.href = './index.php';
                                });
                            }else{
                                layer.msg(data.info, {icon: 5, time: 1000});
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
