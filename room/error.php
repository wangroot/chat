<?php
require_once '../include/common.inc.php';
if($type=='OtherLogin'){
    unset($_SESSION['login_uid']);
    unset($_SESSION['login_user']);
     session_destroy(); 
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>提示</title>
</head>

<body>
<style>
        body {
    background: #F6FEFF url(images/room_bg5.jpg) no-repeat 50% 0;
    background-size: 100% auto;
}
*{font-family:'Microsoft YaHei UI', 'Microsoft YaHei', SimSun, 'Segoe UI', Tahoma, Helvetica, Sans-Serif;}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div style="color:#F00; font-size:35px; margin-top:150px; margin-bottom:20px; text-align:center">提 示</div>
<div style="text-align:center; line-height:20px;">
<span  style="display:block; font-size:20px; color:#000;  padding:20px; margin:10px auto; width:600px; white-space:200px; line-height:40px"><?=addslashes($_GET['msg']);?></span>
<a href="../" style="display:inline-block; width:60px; margin:10px; padding:5px; background:#f46b0a;text-decoration: none; color:#FFF;border-radius: 4px;font-size: 14px;">返回</a>
</div>
</body>
</html>
