<?php
/**
 * Created by PhpStorm.
 * User: roy
 * Date: 16-8-30
 * Time: 上午11:14
 */
if (!session_id()){
    session_start();
}

if (empty($_SESSION['zhibo_anchor'])){
    header('location:login.php');
    exit;
}
$user = $_SESSION['zhibo_anchor'];

require_once 'data.php';

$type = isset($_POST['type'])?$_POST['type']:'';
if (!empty($type)) {
    $name = $_POST['name'];
    $photo = $_POST['photo'];
    $intro = $_POST['intro'];
    $pwd = $_POST['pwd'];

    $sql = "update {$table_prefix}anchor set name='{$name}',photo='{$photo}',introduction='{$intro}'";
    if (!empty($pwd)) {
        $pwd = substr(md5($md5_code . $pwd), 4, 24);
        $sql .= ",pwd='{$pwd}'";
    }
    $sql .= "where id={$user['id']}";
    $res = mysqli_query($conn, $sql);
    if ($res) {
        $sql = "select * from {$table_prefix}anchor where id={$user['id']}";
        $user = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($user);
        $_SESSION['zhibo_anchor'] = $user;
    }
}
?>
<!doctype html>
<html>
    <head>
        <title>主播主页</title>
		<meta charset='utf-8' />
        <link rel="stylesheet" type="text/css" href="js/uploadify/uploadify.css">
        <link rel="stylesheet" type="text/css" href="css/index.css">
    </head>
    <body>
        <div id="main">
            <div class="left"></div>
            <div class="right">
                <div class="data">
                    <table border="0" cellspacing="10" cellpadding="0" width="100%">
                        <tr>
                            <th>姓名</th>
                            <td><input class="txt" type="text" id="name" value="<?php echo $user['name']?>" title="姓名"></td>
                        </tr>
						<tr>
                            <th>修改密码</th>
                            <td><input class="txt" type="password" id="pwds"  title="修改密码"></td>
                        </tr>
						<tr>
                            <th>确认密码</th>
                            <td><input class="txt" type="password" id="pwd"  title="确认密码"></td>
                        </tr>
                        <tr>
                            <th>头像</th>
                            <td>
                                <input type="file" id="photo">
                                <input type="hidden" id="path" value="<?php echo $user['photo'] ?> ">
                                <input type="hidden" id="thumb">
                                <img src="./Uploads/<?php echo $user['photo']?> ">
                            </td>
                        </tr>
                        <tr>
                            <th>简介</th>
                            <td><script id="editor" type="text/plain" style="width:500px;height:300px;"></script></td>
                        </tr>
                        <tr>
                            <th></th>
                            <td><input type="button" id="submit" value="提交"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/layer/2.1/layer.js"></script>
        <script type="text/javascript" src="js/uploadify/jquery.uploadify.min.js"></script>
        <script type="text/javascript" charset="UTF-8" src="js/ueditor/ueditor.config.js"></script>
        <script type="text/javascript" charset="UTF-8" src="js/ueditor/ueditor.all.min.js"></script>
        <script type="text/javascript">
            $(function () {
                var ue = UE.getEditor('editor');
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
		ue.addListener("ready", function () {
			// editor准备好之后才可以使用
			ue.setContent('<?php echo $user['introduction'] ?>');
		});

                $("#submit").click(function () {
                    //alert(ue.getContent())
                    var name = $("#name");
                    var pwds = $("#pwds");
                    var pwd = $("#pwd");
                    var photo = $("#path").val();
                    var intro = ue.getContent();
                    if (name.val() == ''){
                        layer.msg('姓名不能为空', {icon: 2, time: 1000}, function () {
                            name.focus();
                        });
                        return false;
					}
					if(pwd.val() != pwds.val()){
						layer.msg('密码不一致', {icon: 2, time: 1000}, function () {
                        pwd.focus();
                    });
						return false;
                    }else if(photo == ''){
                        layer.msg('请上传头像', {icon: 2, time: 1000});
                        return false;
                    }else if(intro == ''){
                        layer.msg('请填写简介', {icon: 2, time: 1000}, function () {
                            ue.focus();
                        });
                        return false;
                    }else{
                     layer.msg('修改成功', {icon: 6, time: 1000});
					//延迟跳转
					setTimeout(function(){
						 window.location.href="./index.php";
					 }, 1000);
					//ajax修改信息
                        $.ajax({
                            type: 'post', dataType: 'json', cache: false,
                            url: './anchor.php',
                            data: {'name': name.val(), 'photo': photo, 'intro': intro,'pwd':pwd.val(), 'type': 'edit'},
                            success: function (data) {
                                if (data.status == 0){
                                    layer.msg('保存成功', {icon: 6, time: 1000});
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
