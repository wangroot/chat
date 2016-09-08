<?php
require_once '../config.inc.php';
require_once './common.inc.php';
require_once './db_mysql.class.php';
global $db,$tablepre,$onlineip,$cfg;

    // $follows=$_POST['follows'];
    $roomid=$_POST['roomid'];
 
    switch ($act) {
    	case 'sure':
			$row=$db->query("select follows from {$tablepre}config where id={$roomid}");
			$follows=$db->fetch_row($row)['follows'];//获取原来的关注数量
		 	$result=$db->query("update {$tablepre}config set follows={$follows}+1 where id={$roomid}");  
    	    if($result){
	    		echo json_encode(['status'=>true]);
			    }else{
			    	echo json_encode(['status'=>false]);

			    }	
		 	setcookie('user_roomid',$roomid,0,'/');   
    	   break;
    	case 'cancle':
    	   	
    	   	$row=$db->query("select follows from {$tablepre}config where id={$roomid}");
			$follows=$db->fetch_row($row)['follows'];//获取原来的关注数量
		 	$result=$db->query("update {$tablepre}config set follows={$follows}-1 where id={$roomid}");
		 	setcookie('user_roomid',null,0,'/');   
            if($result){
	    		    echo json_encode(['status'=>true]);
			    }else{
			    	echo json_encode(['status'=>false]);

			    }

    		break;	
    }


?>