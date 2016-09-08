<?php
/**
 * Created by PhpStorm.
 * User: roy
 * Date: 16-8-31
 * Time: 上午9:38
 */

if (!session_id()){
    session_start();
}

require_once 'data.php';
$uid = isset($_POST['uid'])?$_POST['uid']:'';
$pwd = isset($_POST['pwd'])?$_POST['pwd']:'';
if ($uid && $pwd){
    $pwd = substr(md5($md5_code.$pwd),4,24);
    $sql = "select * from {$table_prefix}anchor where uid='$uid' and pwd='$pwd'";
    $user = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($user);

    if ($user){
        $_SESSION['zhibo_anchor'] = $user;
        echo json_encode(array('status'=>0,'info'=>'登录成功'));
    }else{
        echo json_encode(array('status'=>4,'info'=>'用户名或密码错误'));
    }
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
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
                <th>密码：</th>
                <td><input class="txt" type="password" id="pwd" title="密码"></td>
            </tr>
            <tr>
                <th></th>
                <td><input type="button" id="submit" value="登录"> &nbsp; <a href="register.php">注册</a></td>
            </tr>
        </table>
    </div>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/layer/2.1/layer.js"></script>
    <script type="text/javascript">
        $(function () {
            $("#submit").click(function () {
                var uid = $("#uid");
                var pwd = $("#pwd");
                if (uid.val() == ''){
                    layer.msg('用户名不能为空', {icon: 2, time: 1000}, function () {
                        uid.focus();
                    });
                    return false;
                }else if(pwd.val() == ''){
                    layer.msg('密码不能为空', {icon: 2, time: 1000}, function () {
                        pwd.focus();
                    });
                    return false;
                }else{
                    $.ajax({
                        type: 'post', dataType: 'json', cache: false,
                        url: './login.php',
                        data: {'uid': uid.val(), 'pwd': pwd.val()},
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
