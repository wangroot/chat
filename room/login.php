<?php
/*require_once '../include/common.inc.php';
if($cfg['config']['state']=='1')header("location:./");


function is_mobile()
{
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_mac = (strpos($agent, 'mac os')) ? true : false;
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;
        

        if($is_pc){
              return  false;
        }
        
        if($is_mac){
              return  true;
        }
        
        if($is_iphone){
              return  true;
        }
        
        if($is_android){
              return  true;
        }
        
        if($is_ipad){
              return  true;
        }
}



if(isset($login))
{
	if($pwd==$cfg['config']['pwd'])
	{
		$_SESSION['room_'.$cfg['config']['id']]=true;
		$flag = is_mobile();
		if($flag){
			header("location:/room/m/?rid="+$rid);
		} else {
			header("location:/"+$rid);
		}
		
	}
	else
	exit("<script>alert('房间密码错误！');location.href='/".$rid."'</script>");
}*/

require_once '../include/common.inc.php';
if($cfg['config']['state']=='1')header("location:./");


if(isset($login))
{
	if($pwd==$cfg['config']['pwd'])
	{
		$_SESSION['room_'.$cfg['config']['id']]=true;
		exit("<script>location.href='/".$rid."'</script>");
	}
	else
	exit("<script>alert('房间密码错误！');location.href='/".$rid."'</script>");
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>房间被锁！请输入密码</title>
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
<div style="color:#F00; font-size:25px; margin-top:150px; margin-bottom:20px; text-align:center">房间被锁！请输入密码</div>
<div style="text-align:center; line-height:20px;">
<span  style="display:block; font-size:12px; color:#666;  padding:10px; margin:5px auto; width:400px; border:1px solid #CCC; white-space:200px; vertical-align:middle">
<form action="?login&id=<?=$id?>" method="post" enctype="application/x-www-form-urlencoded" name="room" id="room">
<input name="pwd" type="password" id="pwd" style=" height:24px;display:inline-block;   "/><input type="submit" value="登陆"  style=" height:30px;display:inline-block; width:60px; margin:10px; padding:5px; background:#09F; color:#FFF; border:0px;"/>
</form></span>

</div>
</body>
</html>
