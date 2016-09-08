<?php
require_once './include/common.inc.php';
require_once NUOYUN_ROOT.'./include/json.php';
$json=new JSON_obj;
$uid=(int)$uid;
$id=(int)$id;
switch($act)
{
		case "setdefvideosrc";
                    if(check_auth('def_videosrc')){
			$db->query("update {$tablepre}config set defvideo='$vid',defvideonick='$nick' where id='$rid'");
                    }
		break;
		//私聊聊天记录
		case "mymsgold":
                    if($_SESSION['login_uid']=='0'){
                      $uid=$_SESSION['login_guest_uid'];
                    }else{
			$uid=$_SESSION['login_uid'];
                         }
			$query=$db->query("select *  from {$tablepre}chatlog where (uid='$uid' and tuid='$tuid')or(uid='$tuid' and tuid='$uid') and type='4' order by id desc limit 0,20");
			while($row=$db->fetch_row($query)){
				$str1='
				<li class="layim_chate[me]"><div class="layim_chatuser"><span class="layim_chattime">[date]</span><span class="layim_chatname">[uname]</span><img src="../face/img.php?t=p1&u=[uid]"></div><div class="layim_chatsay"><font style="color:#000">[msg]</font><em class="layim_zero"></em></div></li>
				';
				$str2='
				<li class="layim_chate[me]"><div class="layim_chatuser"><img src="../face/img.php?t=p1&u=[uid]"><span class="layim_chatname">[uname]</span><span class="layim_chattime">[date]</span></div><div class="layim_chatsay"><font style="color:#000">[msg]</font><em class="layim_zero"></em></div></li>
				';
				if($row['uid']==$uid)
					$str=str_replace("[me]","me",$str1);
				else 
					$str=str_replace("[me]","he",$str2);
				$str=str_replace("[uid]",$row['uid'],$str);
				$str=str_replace("[uname]",$row['uname'],$str);
				$str=str_replace("[msg]",tohtml($row['msg']),$str);
				$str=str_replace("[date]",date("Y-m-d H:i:s",$row['mtime']),$str);
				$msgold=$str.$msgold;
			}
			$data['realname']=userinfo($tuid,'{realname}');
			$data['tuid']=$tuid;
			$data['msg']=$msgold;
			exit($json->encode($data));
		break;
		//屏蔽消息
		case "msgblock":
			$db->query("update {$tablepre}chatlog set state='$s' where msgid='$msgid'");
			exit();
		break;
            //自动广播
            case "getsysmsg":
              
			$query=$db->query("select * from  {$tablepre}sysmsg where id=1");
                        $row=$db->fetch_row($query);
                      $row['content']=html_entity_decode($row['content']);
                        $arr = explode("\n",$row[content]);
                       $data['info']=$arr;
                       $data['state']=$row[state];
                       $data['fangshi']=$row[fangshi];
                       $data['jiange']=$row[jiange];
			exit($json->encode($data));
		break;
            //确定我的客服
                 case "remyfuser":
			$uid=$_SESSION['login_uid'];
			$tuser=userinfo($fuserid,'{username}');
                        if($uid!=0){
			$db->query("update {$tablepre}members set fuser='$tuser' where (fuser='' or fuser is null) and uid='$uid'");
                        }else{
                          $guestuid=  $_SESSION['login_guest_uid'];
                      $db->query("update {$tablepre}guest set fuser='$tuser' where (fuser='' or fuser is null)  and guestuid='$guestuid'");
                         
                        }
                        
                        break;
		//我的客服
		case "getmylist":
			//exit(print_r($_GET));
			$data['state']='false';
			$uid=$_SESSION['login_uid'];
                        if($uid!=0){
			$userinfo=$db->fetch_row($db->query("select m.*,ms.* from {$tablepre}members m,{$tablepre}memberfields ms  where m.uid=ms.uid and m.uid='{$uid}'")); 
                        }else{
                             $guestuid=  $_SESSION['login_guest_uid'];
                    $userinfo=$db->fetch_row($db->query("select * from {$tablepre}guest where  guestuid='$guestuid' ")); 
                   }
                        $i=0;
                        
			if($userinfo['gid']!='3'){
                       
				if($userinfo['fuser']=="")$userinfo['fuser']=$cfg['config']['defkf'];
                                if($userinfo['fuser']!=''){
			$query=$db->query("select m.*,ms.* from {$tablepre}members m left join {$tablepre}memberfields ms
							  on m.uid=ms.uid   where m.username ='$userinfo[fuser]'");
			while($row=$db->fetch_row($query)){
				$tmp['uid']=$row['uid'];
				$tmp['chatid']=$row['uid'];
				$tmp['nick']=$row['nickname'];
				$tmp['phone']=$row['phone'];
					$tmp['qq']=$row['realname'];
				$tmp['color']=$row['gid'];
					$tmp['mood']=$row['mood'];
                                        $tmp['cam']=$row['face'];
				$data['row'][$i++]=$tmp;
					$data['state']='true';
			}
                                }
			}else{
			$query=$db->query("select m.*,ms.* from {$tablepre}members m left join {$tablepre}memberfields ms
							  on m.uid=ms.uid   where m.fuser='{$user}' and m.username!='{$user}' order by m.uid desc");
			while($row=$db->fetch_row($query)){
				$tmp['uid']=$row['uid'];
				$tmp['chatid']=$row['uid'];
				$tmp['nick']=$row['nickname'];
				$tmp['phone']=$row['phone'];
					$tmp['qq']=$row['realname'];
				$tmp['color']=$row['gid'];
                                 $tmp['cam']=$row['face'];
				$data['row'][$i++]=$tmp;
					$data['state']='true';
			}
			}
			
			exit($json->encode($data));
		break;
		case "getrlist":
		
		//机器人列表
		//$rid:房间ID $r:20|50 20-50随机数 机器人个数
		$r=explode("|",$r);
		$r_max=mt_rand($r[0],$r[1]);
              if($r_max<=0)exit("");
		$time=time();
		$query=$db->query("select * from {$tablepre}rebots where rid='$rid' and losttime>{$time}");
		if($db->num_rows($query)<=0){		
			$query=$db->query("select * from {$tablepre}rebots where id='1'");
			$row=$db->fetch_row($query);
			$rebots_arr=explode("\r\n",$row['rebots']);
			shuffle($rebots_arr);
			$roomListUserJsonStr=array("type"=>"UonlineUser","stat"=>"OK");
			$roomListUser=array();
			$roomUser=array("roomid"=>$_SERVER['HTTP_HOST'].".".$rid,"chatid"=>"","ip"=>"0.0.0.0","qx"=>"0","cam"=>"","vip"=>"0","age"=>"-","sex"=>"","mood"=>"","state"=>"0","nick"=>"","color"=>"1");		
			$count=count($rebots_arr);
		
			for($i=0;$i<$count;$i++){
				if(trim($rebots_arr[$i])=="")continue;
				$roomUser['chatid']='x_r'.$i;
				$roomUser['sex']=rand(0,2);
				$roomUser['cam']=0;
				$roomUser['nick']=$rebots_arr[$i];
				$roomListUser[$i]=$roomUser;
			
				if($i>=$r_max)break;
			}
			$roomListUserJsonStr['roomListUser']=$roomListUser;
			$data=base64_encode($json->encode($roomListUserJsonStr));
			//机器人列表20分钟一换
			$losttime=time()+20*60;
			$db->query("delete from {$tablepre}rebots where rid='$rid'");
			$db->query("insert into {$tablepre}rebots(rid,rebots,losttime)values('$rid','$data','$losttime')");
		}
		else{
			//获取有效列表
			$row=$db->fetch_row($query);
			$data=$row['rebots'];
		}
		
		exit(base64_decode($data));
	break;
        case "robotlist":
		
		//获取自定义机器人列表
                     if($r<=0)exit("");
                     $weeks=array("Monday" => "1", "Tuesday" => "2", "Wednesday" => "3", "Thursday" => "4", "Friday" => "5", "Saturday" => "6", "Sunday" => "7");
                     $week=$weeks[date('l')];
		$time=mktime(0,0,0,date('m'),date('d'),date('Y'));
                 $time=time()-$time;
		$query=$db->query("select r.*,g.ov from {$tablepre}rebot_custom r,{$tablepre}auth_group g where r.gid=g.id and r.week like '%{$week}%' and  r.shangxian<{$time} and r.xiaxian>{$time} order by g.ov DESC,r.shangxian desc");

                 if($db->num_rows($query)>0){	
                     $roomListUserJsonStr=array("type"=>"UonlineUser","stat"=>"OK");
			$roomListUser=array();
			$roomUser=array("roomid"=>$_SERVER['HTTP_HOST'].".".$rid,"chatid"=>"","ip"=>"0.0.0.0","qx"=>"0","cam"=>"","vip"=>"0","age"=>"-","sex"=>"","mood"=>"","state"=>"0");		
		$i=0;
                      while($row=$db->fetch_row($query)){
                          $i++;
                          $roomUser['chatid']='x_r'.$row['id'];
				$roomUser['sex']=$row['sex'];
				$roomUser['cam']=0;
				$roomUser['nick']=$row['rebotname'];
                                $roomUser['color']=$row['gid'];
				$roomListUser[]=$roomUser;
                                if($i>=$r)break;
                      }

			$roomListUserJsonStr['roomListUser']=$roomListUser;
			$data=base64_encode($json->encode($roomListUserJsonStr));
	       	}
		else{
			exit("");
		}
		
		exit(base64_decode($data));
	break;
	case "putmsg":
		if($cfg['config']['msgaudit']=='1'){
			$state='1';	
		}
		if($msgtip=="2"){
                    if(!check_auth('room_admin')){  return;}
                       $type='2';
                  }
		if($msgtip=="3"){
                    if(!check_auth('room_admin')){  return;}
                       $type='3';
                  }
                  //判断是否为私聊
                 $type=($privacy=='true')?4:$type;
                 //判断是否为机器人发言
                $pos = strpos($muid,'x_r');
                if ($pos === false) {$ugid=$_SESSION[login_gid];} else {$ugid=$privacy;$privacy='false';}

	$sql="insert into {$tablepre}chatlog(rid,uid,tuid,uname,tname,p,style,msg,mtime,ugid,msgid,ip,state,type)
				  values('$rid','$muid','$tid','$uname','$tname','$privacy','$style','$msg',".gdate().",'$ugid','$msgid','$onlineip','$state','$type')";
	  	$db->query($sql);
	break;
	case "regcheck":
			$guestexp = '^Guest|'.$cfg['config']['regban']."Guest";
			if(preg_match("/\s+|{$guestexp}/is", $username))
			exit('-1');
			
			if($db->num_rows($db->query("select * from {$tablepre}members where username='$username' "))>0)exit('0');
			else exit('1');
	break;
	case "setvideo":
		$uid=$_SESSION['login_uid'];
		if(check_auth('room_admin')){
		$db->query("update {$tablepre}config set defvideo='{$vid}' where id='{$def_cfg}'");
		}
	break;
	case "userstate":
		if(isset($_SESSION['login_uid']))
		{
			$userstate['state']="login";
			$id=$_SESSION['login_uid'];
			$query=$db->query("select m.uid,m.sex,m.onlinetime,m.gold,ms.nickname,ms.mood,ms.city,ms.bday
						  from {$tablepre}members m,{$tablepre}memberfields ms
						  where m.uid=ms.uid and m.uid='{$id}'
						  ");
  			$row=$db->fetch_row($query);
			$userinfo['id']	 =$row['uid'];
			$userinfo['nick']=$row['nickname'];
			$userinfo['sn']=$row['mood'];
			$userinfo['rank']=showstars($row['onlinetime']);
			$userinfo['gold']=$goldname.':'.$row['gold'];
			$userstate['info']=$userinfo;
			
		}
		else
		{
			$userstate['state']="logout";
		}
		$data=$json->encode($userstate);
		exit($data);
	break;
	case "userinfo":
		$query=$db->query("select m.*,ms.*
						  from {$tablepre}members m,{$tablepre}memberfields ms
						  where m.uid=ms.uid and m.uid='{$id}'
						  ");
  		$row=$db->fetch_row($query);
		$row['password']='';
		$data=$json->encode($row);
		exit($data);
	break;
	case "delimpression":
		if(!isset($_SESSION['login_uid'])||$_SESSION['login_uid']==0)
		$state['state']='logout';
		else
		{
			$uid=$_SESSION['login_uid'];
			$db->query("delete from {$tablepre}membersapp1 where uid='$uid' and fuid='$fuid' and ftime='$ftime'");
			$state['state']='ok';
		}
		$data=$json->encode($state);
		exit($data);
	break;
	case "impression":
		if(!isset($_SESSION['login_uid'])||$_SESSION['login_uid']==0)
		$state['state']='logout';
		else
		{
			$color=rand_color();
			$time=gdate();
			$fuid=$_SESSION['login_uid'];
			$db->query("delete from {$tablepre}membersapp1 where uid='$uid' and fuid='$fuid'");
			$sql="insert into {$tablepre}membersapp1(uid,color,txt,fuid,ftime)
				  values('$uid','$color','$t','$fuid','$time')";
	  		$db->query($sql);
	  		$state['state']='ok';
		}
		$data=$json->encode($state);
		exit($data);
	break;
	case "memberfriends":
		if(!isset($_SESSION['login_uid']))
		$state['state']='logout';
		else
		{
		$ftime=gdate();
		$uid=$_SESSION['login_uid'];
		if(isset($a))$db->query("replace into {$tablepre}membersapp3(uid,fuid,ftime)values('$uid','$a','$ftime')");
		if(isset($d))$db->query("delete from {$tablepre}membersapp3 where uid='$uid' and fuid='$d'");
		$state['state']='ok';
		}
		$data=$json->encode($state);
		exit($data);
	break;
	case "message":
		if(!isset($_SESSION['login_uid'])||$_SESSION['login_uid']==0)
		$state['state']='logout';
		else
		{
			if(isset($d))
			{
			$db->query("delete from {$tablepre}membersapp4 where id='$d' and uid='$_SESSION[login_uid]'");
			$state['state']='ok';
			}
			else{
			if(trim($txt)!=''){
				$txt=$db->totxt($txt);
				$ftime=gdate()-2;
				$fuid=$_SESSION['login_uid'];
				$db->query("insert into {$tablepre}membersapp4(uid,fuid,ftime,tag,txt)values('$uid','$fuid','$ftime','$tag','$txt')");
				}
			$state['state']='ok';
			}
		}
		$data=$json->encode($state);
		exit($data);
		
	break;
	case "kick":
            
           if(!isset($_SESSION['login_uid']) || ($_SESSION['login_user']!=$u && $_SESSION['login_nick']!=$u )){exit('false');}
		if(check_user_auth($aid,'user_kick')){
			$losttime=$ktime*60+gdate();
                        $author=userinfo($aid,'{username}');
                        $addtime=gdate();
			$db->query("insert into {$tablepre}ban(username,ip,losttime,sn,addtime,author)values('$u','$onlineip','$losttime','$cause','$addtime','$author')");
			$state['state']='yes';
			$data=$json->encode($state);
			exit($data);
		}
		
	break;
	case "online":
		if(!isset($_SESSION['login_uid'])){
		$state['state']='logout';
                }else
		{
			if($_SESSION['login_uid']==0){$state['state']='ok';$data=$json->encode($state);exit($data);}
			if(!empty($rst)){
				$time=gdate();
				$u_id=$_SESSION['login_uid'];
				$query_row=$db->fetch_row($db->query("select lastactivity from {$tablepre}members where uid='$u_id'"));
				$_time=(int)($time-$query_row['lastactivity']);
				
				$db->query("update {$tablepre}members set lastactivity='$time',onlinetime=onlinetime+$_time where uid='$u_id'");
				$state['state']='ok';
				
			}
			else
			{
				//reonline();
				$state['state']='ok';
			}
			
		}
		$data=$json->encode($state);
		exit($data);
		
	break;
        case "kefuonline":
            
            if($_SESSION['login_gid']=='3'){
                $time=gdate();
                $u_id=$_SESSION['login_uid'];
               $username= $_SESSION['login_user'];
                $db->query("replace into {$tablepre}kefuonlines(uid,username,rid,lastactivity,ip,rst)values('$u_id','$username','$rid','$time','$onlineip','$rst')");
              $db->query("delete from {$tablepre}kefuonlines where lastactivity<$time-300");  
            }
		
	break;
        //获取分析师
         case "getAnalysts":
            
           if(isset($_SESSION['login_uid'])){
         $sql="select m.uid,m.username,ms.nickname  from {$tablepre}members m,{$tablepre}memberfields ms  where m.uid=ms.uid and m.uid!=0 and m.gid=4";
         $query=$db->query($sql);
         $analystslist=array();
         while($row=$db->fetch_row($query)){
                 $analystslist[]=$row;
                    }
                    exit(json_encode($analystslist));
            }
		
	break;
        
          //获取分析师
         case "addredbag":
            
           if(isset($_SESSION['login_uid']) && $_SESSION['login_uid']!='0' ){
               $u_id=$_SESSION['login_uid'];
       $db->query("update {$tablepre}members set redbags=redbags+1 where uid='$u_id'");
       exit('1');
            }
		
	break;
        
           //发红包
         case "SendRedBagNew":
            $result=array();
           if(isset($_SESSION['login_uid']) && $_SESSION['login_uid']!='0' ){
            
                $u_id=$_SESSION['login_uid'];
                $query=$db->query("select redbags from {$tablepre}members where uid='$u_id'");
  		$row=$db->fetch_row($query);
                if($row['redbags']>=1){
                       $time=gdate();
               $u_nickname=userinfo($u_id,'{nickname}');
               $js_nickname=userinfo($jid,'{nickname}');
          $db->query("update {$tablepre}members set redbags=redbags-1 where uid='$u_id'");
          $db->query("insert into {$tablepre}redbag(rid,uid,juid,sendtime)values('1','$u_id','$jid','$time')");
         $redbagcount=$db->num_rows($db->query("select id from {$tablepre}redbag "));
          $db->query("update {$tablepre}config set redbags='$redbagcount' where id='$def_cfg'");
          $result['state']='ok';
          $result['num']=$redbagcount;
          $result['msg']=$u_nickname.'送给'.$js_nickname.'老师一朵鲜花';
                }else{
                    $result['state']='no';
                }
                echo json_encode($result);
                exit;
            }
		
	break;

	//房间机器人数修改
        case "getHouseInfo":
           $result = array('state'=>0);
           $houseInfo=$db->fetch_row($db->query("select * from {$tablepre}config where id= ".$_POST['rid'])); 
           if($houseInfo){
               $result['state'] = 1;
               $result['pwd'] = $houseInfo['pwd'];
               $result['rebots'] = $houseInfo['rebots'];
           }
           
        exit (json_encode($houseInfo));
           
        break;

        case "updateHouseInfo":
        $result = $db->query("update {$tablepre}config set pwd='".$_POST['pwd']."',rebots='".$_POST['rebots']."',state='".$_POST['state']."' where id=".$_POST['rid']);     
        
        exit(json_encode($result));
        break;  
}

?>