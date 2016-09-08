<?php
require_once '../config.inc.php';
require_once './common.inc.php';
require_once './db_mysql.class.php';
global $db,$tablepre,$onlineip,$cfg;
	$u = $_POST['username'];
	$p = $_POST['password'];
	$query=$db->query("select * from {$tablepre}members where username='$u' and password='".md5($p)."'");
	while($row=$db->fetch_row($query)){
		if($cfg['config']['regaudit']=='1'&&$row['state']=='0')
		return "用户未审核,暂不能登录！";
		
		$_SESSION['login_uid']=$row['uid'];
		$_SESSION['login_user']=$row['username'];
		$_SESSION['login_nick']=$row['username'];
		$_SESSION['login_gid']=$row['gid'];
		$_SESSION['login_sex']=$row['sex'];
		$time=gdate();
		$_SESSION['onlines_state']['time']=$time;
		$db->query("update {$tablepre}members set lastvisit=lastactivity,regip='$onlineip' where uid={$row[uid]}");
		$db->query("update {$tablepre}members set lastactivity=$time where uid={$row[uid]}");
		$db->query("update {$tablepre}memberfields set logins=logins+1 where uid={$row[uid]}");
		$db->query("insert into  {$tablepre}msgs(rid,ugid,uid,uname,tuid,tname,mtime,ip,msg,`type`)
	values('{$cfg[config][id]}','{$row[gid]}','{$row[uid]}','{$row[username]}','{$cfg[config][defvideo]}','{$cfg[config][defvideonick]}','".gdate()."','{$onlineip}','用户登陆','1')
		");
		echo true;
	}
	echo FALSE;
	//return "用户名或密码错误！";



?>