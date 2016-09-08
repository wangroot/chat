<?php
if(!isset($_SESSION['admincp']))exit("<script>top.location.href='http://".$_SERVER["HTTP_HOST"]."/ny_admin/login.php'</script>");
function group_add($title,$sn,$ico,$ov){
	global $db,$tablepre;
	$db->query("insert into {$tablepre}auth_group(title,sn,ico,type,ov)values('$title','$sn','$ico',0,'$ov')");	
}
function group_del($id){
	global $db,$tablepre;
        $db->query("update {$tablepre}members set gid='1' where gid='$id'");
        $db->query("update {$tablepre}rebot_custom set gid='1' where gid='$id'");
	$db->query("delete from {$tablepre}auth_group where id='$id' and id not in (1,2,3)");
}
function group_edit($id,$title,$sn,$ico,$ov){
	global $db,$tablepre;
	$db->query("update {$tablepre}auth_group set title='$title',sn='$sn',ico='$ico',ov='$ov' where id='$id'");
}

//添加房间
function house_add($tpid,$title,$keys,$dc){   
	global $db,$tablepre;
        $query=$db->query("select title from {$tablepre}config where title='{$title}' limit 1");
		if($db->num_rows($query))exit("<script>alert('房间名已经被使用!换一个，如{$username}2015');location.href='?'</script>");

    $strsql = "INSERT INTO `{$tablepre}config` (`title`,`keys`,`dc`,`regban`,`msgban`,`state`,`pwd`,`regaudit`,`msgblock`,`msgaudit`,`msglog`,`logintip`,`loginguest`,`loginqq`,`tongji`,`copyright`,`tserver`,`vserver`,`livetype`,`online`,`defvideo`,`phonefp`,`livefp`,`rebots`,`defkf`,`defvideonick`,`tiyantime`,`fayanjiange`,`logintc`,`redbags`,`uptime`)"
	." VALUES ('".$title."','".$keys."','".$dc."','qq|q|系统管理员|管理员|adminadmin|管理','黑平台|返佣|日返|高返佣|头寸|打包|手续费|刷单|套利|黑公司|私聊|群|加群|返佣|黑平台|代理|代客操盘|违规操作',1,'0',1,1,0,1,1,1,0,'','©2016 xx版权所有。','192.168.202.128:7272','rtmp://114.215.157.23/nuoyun',0,12,'1','<div id=\"player\" style=\"width:100%;height:100%;\">\r\n    <script type=\"text/javascript\" charset=\"utf-8\" src=\"http://yuntv.letv.com/player/live/blive.js\"></script>\r\n    <script>\r\n        var player = new CloudLivePlayer();\r\n        player.init({activityId:\"A2016070700000p1\"});\r\n    </script>\r\n</div>\r\n','<div id=\"player\" style=\"width:100%;height:100%;\">\r\n    <script type=\"text/javascript\" charset=\"utf-8\" src=\"http://yuntv.letv.com/player/live/blive.js\"></script>\r\n    <script>\r\n        var player = new CloudLivePlayer();\r\n        player.init({activityId:\"A2016070700000p1\"});\r\n    </script>\r\n</div>\r\n','0','','系统管理员',0,10,0,547,".time().")";         
    $db->query($strsql);
    return $db->insert_id();
}
//删除房间
function house_del($id){
	global $db,$tablepre;
	if($id=="")return;
	$db->query("delete from {$tablepre}config where id =$id");
}
//房间修改step_01
function config_edit_step_01($arr){
	global $db,$tablepre;
    $rid=$_SESSION['roomid'];
	$arr['uptime'] = time();
	foreach($arr as $key=>$v){
		$set[]="`$key`='$v'";
	}
	$sql="update {$tablepre}config set ".implode(",",$set)." where id=$rid";
	$db->query($sql);
	header('location:house_list.php');
}
//房间修改step_02
function config_edit_step_02($arr){
	global $db,$tablepre;
	$rid=$_SESSION['roomid'];
	$arr['uptime'] = time();
	foreach($arr as $key=>$v){
		$set[]="`$key`='$v'";
	}
	$sql="update {$tablepre}config set ".implode(",",$set)." where id=$rid";
	$db->query($sql);
	header('location:house_list.php');
}
//房间修改step_03
function config_edit_step_03($arr){
	global $db,$tablepre;
	$arr['uptime'] = time();
	$query1=$db->query("select id from {$tablepre}sysmsg where id='{$arr[id]}'");

	if($db->num_rows($query1)>=1){                
		foreach($arr as $key=>$v){
				$set[]="`$key`='$v'";
		}
		$sql="update {$tablepre}sysmsg set ".implode(",",$set)." where id=$arr[id]";
		$db->query($sql);
	}
	else{
		$db->query("insert into {$tablepre}sysmsg(id,state,content,jiange,fangshi)values('$arr[id]','$arr[state]','$arr[content]','$arr[jiange]','$arr[fangshi]')");
	}
	header('location:house_list.php');
}

/*房间管理开始*/

function group_addroom($title,$sn,$ico,$tserver,$ov){
	global $db,$tablepre;
	$db->query("insert into {$tablepre}config(`title`, `keys`, `dc`, `logo`, `ico`, `bg`, `ewm`, `regban`, `msgban`, `state`, `pwd`, `regaudit`, `msgblock`, `msgaudit`, `msglog`, `logintip`, `loginguest`, `loginqq`, `tongji`, `copyright`, `tserver`, `vserver`, `livetype`, `online`, `defvideo`, `phonefp`, `livefp`, `rebots`, `defkf`, `defvideonick`, `tiyantime`, `fayanjiange`, `logintc`, `redbags`,`ov`)VALUES ('$title', '$sn', '天道酬勤理财直通车', '$ico', '/upload/upfile/day_150616/201506161648497797.ico', '/upload/upfile/day_160525/201605251528091100.jpg', '/upload/upfile/day_160525/201605251529205352.png', 'qq|q|系统管理员|管理员|adminadmin|管理', '黑平台|返佣|日返|高返佣|头寸|打包|手续费|刷单|套利|黑公司|私聊|群|加群|返佣|黑平台|代理|代客操盘|违规操作', 1, '518518', 1, 1, 0, 1, 1, 1, 0, '', '©2016 天道酬勤理财直通车版权所有。', '$tserver', 'rtmp://114.215.157.23/nuoyun', 1, 12, '1', '<div id=\"player\" style=\"width:100%;height:100%;\">\r\n    <script type=\"text/javascript\" charset=\"utf-8\" src=\"http://yuntv.letv.com/player/live/blive.js\"></script>\r\n    <script>\r\n        var player = new CloudLivePlayer();\r\n        player.init({activityId:\"A2016070700000p1\"});\r\n    </script>\r\n</div>\r\n', '<div id=\"player\" style=\"width:100%;height:100%;\">\r\n    <script type=\"text/javascript\" charset=\"utf-8\" src=\"http://yuntv.letv.com/player/live/blive.js\"></script>\r\n    <script>\r\n        var player = new CloudLivePlayer();\r\n        player.init({activityId:\"A2016070700000p1\"});\r\n    </script>\r\n</div>\r\n', '0', '', '系统管理员', 0, 10, 0, 547,'$ov')");	
}
function group_delroom($id){
	global $db,$tablepre;
        $db->query("update {$tablepre}members set gid='1' where gid='$id'");
        $db->query("update {$tablepre}rebot_custom set gid='1' where gid='$id'");
	$db->query("delete from {$tablepre}config where id='$id' and id not in (1,2,3)");
}
function group_editroom($id,$title,$sn,$tserver,$ico,$ov){
	global $db,$tablepre;
	// echo '<pre>';print_r($_POST);
	// echo '</pre>';
	
	// echo "update {$tablepre}config set title='$title',logo='$ico',tserver='$tserver',ov='$ov' where id='$id'";
	$db->query("update {$tablepre}config set `title`='$title',`keys`=\"$sn\",`logo`='$ico',`tserver`='$tserver',`ov`='$ov' where `id`='$id'");
}

/*房间管理结束*/

function group_rules_edit($id,$rules){
	global $db,$tablepre;
	$db->query("update {$tablepre}auth_group set rules='$rules'  where id='$id'");
}
function user_del($ids){
	global $db,$tablepre;
	if($ids=="")return;
	$db->query("delete from {$tablepre}members where uid  in ($ids) and uid not in (0,1)");
	$db->query("delete from {$tablepre}memberfields where uid in ($ids) and uid not in (0,1)");
}
function guest_del($ids){
	global $db,$tablepre;
	if($ids=="")return;
	$db->query("delete from {$tablepre}guest where uid  in ($ids)");
	
}
function rebots_del($ids){
	global $db,$tablepre;
	if($ids=="")return;
	$db->query("delete from {$tablepre}rebot_custom where id  in ($ids)");
}
function user_edit($id,$realname,$password,$phone,$gid,$fuser,$tuser,$sn,$state,$nickname,$redbags,$onlinestart,$onlineend){
	global $db,$tablepre;
	if($password!="")$pwd=" ,password='".md5($password)."',";
	else $pwd=',';
	if(stripos(auth_group($_SESSION['login_gid']),'users_group')!==false)
	{
		$db->query("update {$tablepre}members set gid='$gid'  where uid='$id'");
	}
	
	$db->query("update {$tablepre}members set realname='$realname' $pwd phone='$phone',fuser='$fuser',tuser='$tuser',state='$state',redbags='$redbags' where uid='$id'");
	$db->query("update {$tablepre}memberfields set sn='$sn',nickname='$nickname',onlinestart='$onlinestart',onlineend='$onlineend' where uid='$id'");
}
function rebot_edit($id,$rebotname,$gid,$sex,$shour,$sminute,$ssecond,$xhour,$xminute,$xsecond,$week){
	global $db,$tablepre;
	
	 $shangxian=$ssecond+$sminute*60+$shour*60*60;
         $xiaxian=$xsecond+$xminute*60+$xhour*60*60;
	$db->query("update {$tablepre}rebot_custom set rebotname='$rebotname',gid='$gid',sex='$sex',shangxian='$shangxian',xiaxian='$xiaxian',week='$week'  where id='$id'");
}
function user_add($realname,$password,$phone,$gid,$state,$username){
	global $db,$tablepre;
        $query=$db->query("select uid from {$tablepre}members where username='{$username}' limit 1");
		if($db->num_rows($query))exit("<script>alert('用户名已经被使用!换一个，如{$username}2015');location.href='?'</script>");
	if($password==""){$password='123456';}
        $pwd= md5($password);
        $regtime=gdate();
	if(isset($_COOKIE['tg'])){
		$tuser=userinfo($_COOKIE['tg'],'{username}');}else{
                   $tuser='system'; 
                }

    $db->query("insert into {$tablepre}members(username,password,sex,regdate,regip,lastvisit,lastactivity,gold,realname,gid,phone,tuser,state)	values('$username','$pwd','2','$regtime','$onlineip','$regtime','$regtime','0','$realname','$gid','$phone','$tuser','$state')");
    $uid=$db->insert_id();
		$db->query("replace into {$tablepre}memberfields (uid,nickname)	values('$uid','$username')	");
}
function rebot_add($rebotname,$gid,$sex,$shour,$sminute,$ssecond,$xhour,$xminute,$xsecond,$week){
	global $db,$tablepre;
        $query=$db->query("select id from {$tablepre}rebot_custom where rebotname='{$rebotname}' limit 1");
		if($db->num_rows($query))exit("<script>alert('机器人名称已经被使用!换一个，如{$u}2016');location.href='?'</script>");
	
        $regtime=gdate();
        $shangxian=$ssecond+$sminute*60+$shour*60*60;
         $xiaxian=$xsecond+$xminute*60+$xhour*60*60;
       $db->query("insert into {$tablepre}rebot_custom(rebotname,gid,sex,shangxian,xiaxian,regtime,week) values('$rebotname','$gid','$sex','$shangxian','$xiaxian','$regtime','$week')");

}
function config_edit($arr){
	global $db,$tablepre;
        $rid=$_SESSION['roomid'];
	foreach($arr as $key=>$v){
		$set[]="`$key`='$v'";
	}
	$sql="update {$tablepre}config set ".implode(",",$set)." where id=$rid";
	$db->query($sql);
}
function sysmsg_edit($arr){
	global $db,$tablepre;
	foreach($arr as $key=>$v){
		$set[]="`$key`='$v'";
	}
	$sql="update {$tablepre}sysmsg set ".implode(",",$set)." where id=1";
	$db->query($sql);
}
function ban_del($id){
	global $db,$tablepre;
	$db->query("delete from {$tablepre}ban where id='$id'");
}
function ban_delold(){
	global $db,$tablepre;
         $time=gdate();
	$db->query("delete from {$tablepre}ban where losttime<'$time'");
}
function ban_add($username,$ip,$sn,$losttime){
	global $db,$tablepre;
	$losttime=strtotime($losttime);
        $addtime=time();
        $author=$_SESSION['admincp'];
	$db->query("insert into {$tablepre}ban(username,ip,sn,losttime,addtime,author)values('$username','$ip','$sn','$losttime','$addtime','$author')");
}
function ban_list($num,$key,$tpl){
	global $db,$tablepre,$firstcount,$displaypg;
	$sql="select * from {$tablepre}ban";
	if($key!="")$sql.=" where username like '%$key%' or ip like '%$key%'";
	
	$count=$db->num_rows($db->query($sql));
	pageft($count,$num,"");
	$sql.=" order by id desc";
	$sql.=" limit $firstcount,$displaypg";
	$query=$db->query($sql);
	return for_each($query,$tpl);
	

}
function notice_del($id){
	global $db,$tablepre;
	$db->query("delete from {$tablepre}notice where id='$id'");
}

//判断房间是否有公告
function notice_add($title,$txt,$cop,$ov,$type){
	global $db,$tablepre;
        $rid=$_SESSION['roomid'];
	$losttime=strtotime($losttime);
	$db->query("insert into {$tablepre}notice(title,txt,cop,ov,`type`,roomid)values('$title','$txt','$cop','$ov','$type','$rid')");
}
function notice_edit($id,$title,$txt,$cop,$ov,$type){
	global $db,$tablepre;
        $rid=$_SESSION['roomid'];
	$db->query("update {$tablepre}notice set title='$title',txt='$txt',cop='$cop',ov='$ov',`type`='$type' where id='$id'");
}
function log_list($num,$key,$tpl){
	global $db,$tablepre,$firstcount,$displaypg;
        $key=trim($key);
	$sql="select * from {$tablepre}msgs";
	if($key!="")$sql.=" where uid like '%$key%' or ip like '%$key%' or uname like '%$key%' or msg like '%$key%'";
	
	$count=$db->num_rows($db->query($sql));
	pageft($count,$num,"");
	$sql.=" order by id desc";
	$sql.=" limit $firstcount,$displaypg";
	$query=$db->query($sql);
	return for_each($query,$tpl);	

}
function chatlog_list($num,$key,$tpl){
	global $db,$tablepre,$firstcount,$displaypg;
	$sql="select * from {$tablepre}chatlog";
	if($key!="")$sql.=" where uid like '%$key%' or ip like '%$key%' or uname like '%$key%' or tname like '%$key%' or msg like '%$key%'";
	
	$count=$db->num_rows($db->query($sql));
	pageft($count,$num,"");
	$sql.=" order by id desc";
	$sql.=" limit $firstcount,$displaypg";
	$query=$db->query($sql);
	return for_each_chatlog($query,$tpl);	

}
function log_del($ids){
	global $db,$tablepre;
	if($ids=="")return;
	$db->query("delete from {$tablepre}msgs where id in ($ids)");
}
function chatlog_del($ids){
	global $db,$tablepre;
	if($ids=="")return;
	$db->query("delete from {$tablepre}chatlog where id in ($ids)");
}
function log_delhistory($value){
	global $db,$tablepre;
	if(empty($value)) return;
        $time=gdate();
        $time=$time-$value*24*3600;
	$db->query("delete from {$tablepre}msgs where mtime<'$time' and state<>'2' and state<>'3'  ");
}
function chatlog_delhistory($value){
	global $db,$tablepre;
	if(empty($value)) return;
        $time=gdate();
        $time=$time-$value*24*3600;
	$db->query("delete from {$tablepre}chatlog where mtime<'$time' and type<>'2' and type<>'3'  ");
}
function hd_del($ids){
	global $db,$tablepre;
	if($ids=="")return;
	$db->query("delete from {$tablepre}apps_hd where id in ($ids)");
}
function app_hd_add($ktime,$ptime,$sp,$kcj,$lx,$cw,$zsj,$zyj,$username,$pcj,$sn){
	global $db,$tablepre;
	$time=gdate();
	$ktime=strtotime($ktime);
	$ptime=strtotime($ptime);
	
	$db->query("insert into {$tablepre}apps_hd(ktime,ptime,sp,kcj,lx,cw,zsj,zyj,username,pcj,sn,ttime)values('$ktime','$ptime','$sp','$kcj','$lx','$cw','$zsj','$zyj','$username','$pcj','$sn','$time')");
}
function app_hd_edit($id,$ktime,$ptime,$sp,$kcj,$lx,$cw,$zsj,$zyj,$username,$pcj,$sn){
	global $db,$tablepre;
	$ktime=strtotime($ktime);
	$ptime=strtotime($ptime);
	$db->query("update {$tablepre}apps_hd set ktime='$ktime',ptime='$ptime',sp='$sp',kcj='$kcj',lx='$lx',cw='$cw',zsj='$zsj',zyj='$zyj',username='$username',pcj='$pcj',sn='$sn' where id='$id'");
}
function app_hd_list($num,$key,$tpl){
	global $db,$tablepre,$firstcount,$displaypg;
	$sql="select * from {$tablepre}apps_hd";
	if($key!="")$sql.=" where uname like '%$key%'";
	
	$count=$db->num_rows($db->query($sql));
	pageft($count,$num,"");
	$sql.=" order by id desc";
	$sql.=" limit $firstcount,$displaypg";
	$query=$db->query($sql);
		while($row=$db->fetch_row($query)){
		$t=$tpl;
		
		if(strpos($row[lx],'买')&&$row['pcj']!=""){
			$t=str_replace('{yld}',round($row['pcj']-$row['kcj'],2),$t);
		}
		else if(strpos($row[lx],'卖')&&$row['pcj']!=""){
			$t=str_replace('{yld}',round($row['kcj']-$row['pcj'],2),$t);
		}else{
			$t=str_replace('{yld}','',$t);
		}
		foreach($row as $k=>$value){
			$t=str_replace('{'.$k.'}',$value,$t);	
		}
		$str.=$t;
		
	}
	return $str;

}

function app_wt_edit($id,$q,$a,$quser,$auser,$zt){
	global $db,$tablepre;
	$db->query("update {$tablepre}apps_wt set q='$q',a='$a',quser='$quser',auser='$auser',zt='$zt'  where id='$id'");
}
function app_wt_add($q,$a,$quser,$auser,$zt){
	global $db,$tablepre;
	$qtime=gdate();
	$db->query("insert into {$tablepre}apps_wt(q,a,quser,auser,qtime,zt)values('$q','$a','$quser','$auser','$qtime','$zt')");
}
function wt_del($ids){
	global $db,$tablepre;
	if($ids=="")return;
	$db->query("delete from {$tablepre}apps_wt where id in ($ids)");
}
function app_wt_list($num,$key,$tpl){
	global $db,$tablepre,$firstcount,$displaypg;
	$sql="select * from {$tablepre}apps_wt";
	if($key!="")$sql.=" where q like '%$key%' or a like '%$key%' or quser like '%$key%'";
	
	$count=$db->num_rows($db->query($sql));
	pageft($count,$num,"");
	$sql.=" order by id desc";
	$sql.=" limit $firstcount,$displaypg";
	$query=$db->query($sql);
	return for_each($query,$tpl);	

}


function app_jyts_edit($id,$title,$txt,$user){
	global $db,$tablepre;
	$db->query("update {$tablepre}apps_jyts set title='$title',txt='$txt',`user`='$user' where id='$id'");
}
function app_jyts_add($title,$txt,$auser){
	global $db,$tablepre;
	$atime=gdate();
	$db->query("insert into {$tablepre}apps_jyts(title,txt,`user`,atime)values('$title','$txt','$user','$atime')");
}
function jyts_del($ids){
	global $db,$tablepre;
	if($ids=="")return;
	$db->query("delete from {$tablepre}apps_jyts where id in ($ids)");
}
function app_jyts_list($num,$key,$tpl){
	global $db,$tablepre,$firstcount,$displaypg;
	$sql="select * from {$tablepre}apps_jyts";
	if($key!="")$sql.=" where title like '%$key%' or txt like '%$key%' or `user` like '%$key%'";
	
	$count=$db->num_rows($db->query($sql));
	pageft($count,$num,"");
	$sql.=" order by id desc";
	$sql.=" limit $firstcount,$displaypg";
	$query=$db->query($sql);
	return for_each($query,$tpl);	

}


function app_scpl_edit($id,$title,$txt,$user,$dj){
	global $db,$tablepre;
	$db->query("update {$tablepre}apps_scpl set title='$title',txt='$txt',`user`='$user',dj='$dj' where id='$id'");
}
function app_scpl_add($title,$txt,$user,$jd){
	global $db,$tablepre;
	$atime=gdate();
	$db->query("insert into {$tablepre}apps_scpl(title,txt,`user`,atime,dj)values('$title','$txt','$user','$atime','$dj')");
}
function scpl_del($ids){
	global $db,$tablepre;
	if($ids=="")return;
	$db->query("delete from {$tablepre}apps_scpl where id in ($ids)");
}
function app_scpl_list($num,$key,$tpl){
	global $db,$tablepre,$firstcount,$displaypg;
	$sql="select * from {$tablepre}apps_scpl";
	if($key!="")$sql.=" where title like '%$key%' or txt like '%$key%' or `user` like '%$key%'";
	
	$count=$db->num_rows($db->query($sql));
	pageft($count,$num,"");
	$sql.=" order by id desc";
	$sql.=" limit $firstcount,$displaypg";
	$query=$db->query($sql);
	return for_each($query,$tpl);	

}



function app_files_edit($id,$title,$url,$user){
	global $db,$tablepre;
	$db->query("update {$tablepre}apps_files set title='$title',url='$url',`user`='$user' where id='$id'");
}
function app_files_add($title,$url,$user){
	global $db,$tablepre;
	$atime=gdate();
	$db->query("insert into {$tablepre}apps_files(title,url,`user`,atime)values('$title','$url','$user','$atime')");
}
function files_del($ids){
	global $db,$tablepre;
	if($ids=="")return;
	$db->query("delete from {$tablepre}apps_files where id in ($ids)");
}
function app_files_list($num,$key,$tpl){
	global $db,$tablepre,$firstcount,$displaypg;
	$sql="select * from {$tablepre}apps_files";
	if($key!="")$sql.=" where title like '%$key%'   or `user` like '%$key%'";
	
	$count=$db->num_rows($db->query($sql));
	pageft($count,$num,"");
	$sql.=" order by id desc";
	$sql.=" limit $firstcount,$displaypg";
	$query=$db->query($sql);
	return for_each($query,$tpl);	

}




function app_manage_edit($id,$title,$url,$ico,$w,$h,$target,$position,$s,$ov){
	global $db,$tablepre;
	$db->query("update {$tablepre}apps_manage set title='$title',url='$url',ico='$ico',w='$w',h='$h',target='$target',position='$position',s='$s',ov='$ov' where id='$id'");
}
function app_manage_add($title,$url,$ico,$w,$h,$target,$position,$s,$ov){
	global $db,$tablepre;
	$atime=gdate();
	$db->query("insert into {$tablepre}apps_manage(title,url,ico,w,h,target,position,s,ov)values('$title','$url','$ico','$w','$h','$target','$position','$s','$ov')");
}
function manage_del($ids){
	global $db,$tablepre;
	if($ids=="")return;
	$db->query("delete from {$tablepre}apps_manage where id in ($ids) and id not in(1,2,3,4,5,6)");
}
function app_manage_list($num,$key,$tpl){
	global $db,$tablepre,$firstcount,$displaypg;
	$sql="select * from {$tablepre}apps_manage";
	if($key!="")$sql.=" where title like '%$key%'";
	
	$count=$db->num_rows($db->query($sql));
	pageft($count,$num,"");
	$sql.=" order by s,ov desc,id desc";
	$sql.=" limit $firstcount,$displaypg";
	$query=$db->query($sql);
	return for_each($query,$tpl);	

}
function get_user_group($ids){
       global $db,$tablepre;
    $connect=(isset($ids) && $ids!='all')?" where id not in $ids":'';
    $sql="select id,title from {$tablepre}auth_group".$connect." order by id desc";
    $query=$db->query($sql);
while($row=$db->fetch_row($query)){
	$group.='<option value="'.$row[id].'">'.$row[title].'</option>';
}
    return $group;
}

function app_course_edit($id,$weekid,$coursetime,$teacher,$paixu){
	global $db,$tablepre;
        $weekarray=array("1" => "星期一", "2" => "星期二", "3" => "星期三","4" => "星期四", "5" => "星期五", "6" => "星期六", "7" => "星期日");
        $week=$weekarray[$weekid];
	$db->query("update {$tablepre}course set week='$week',weekid='$weekid',coursetime='$coursetime',teacher='$teacher',paixu='$paixu'  where id='$id'");
}
function app_course_add($weekid,$coursetime,$teacher,$paixu){
	global $db,$tablepre;
	$weekarray=array("1" => "星期一", "2" => "星期二", "3" => "星期三","4" => "星期四", "5" => "星期五", "6" => "星期六", "7" => "星期日");
         $week=$weekarray[$weekid];
	$db->query("insert into {$tablepre}course(week,weekid,coursetime,teacher,paixu)values('$week','$weekid','$coursetime','$teacher','$paixu')");
}
function course_del($id){
	global $db,$tablepre;
	if($id=="")return;
	$db->query("delete from {$tablepre}course where id='$id'");
}
?>