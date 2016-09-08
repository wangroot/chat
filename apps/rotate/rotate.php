<?php
require_once '../../include/common.inc.php';
switch ($act) {
    case "ismobile";


        if (isset($_SESSION['login_uid']) && isset($_SESSION["call_phone"])) {
            $time = gdate();
            $rands = mt_rand(1, 100);
           
            $mobile = $_SESSION["call_phone"];
             switch($rands){
			case ($rands>=30 && $rands<45):
				$jiangpin_id=1;
				break;
			case $rands<30:
				$jiangpin_id=2;
				break;
			case ($rands>=45 && $rands<50):
				$jiangpin_id=3;
				break;
			case ($rands>=50 && $rands<60):
				$jiangpin_id=4;
				break;
			case ($rands>=60 && $rands<63):
				$jiangpin_id=5;
				break;
			case ($rands>=63 && $rands<70):
				$jiangpin_id=6;
				break;
			case ($rands>=70 && $rands<75):
				$jiangpin_id=7;
				break;
			case ($rands>=75 && $rands<80):
				$jiangpin_id=8;
				break;
			case ($rands>=80 && $rands<82):
				$jiangpin_id=9;
				break;
			case ($rands>=82 && $rands<90):
				$jiangpin_id=10;
				break;
			case $rands==91:
				$jiangpin_id=11;
				break;
			case ($rands>=92 && $rands<95):
				$jiangpin_id=12;
				break;
                        case $rands==96:
				$jiangpin_id=13;
				break;
			default:
				$jiangpin_id=14;
				break;
		}
                
            $jiangpin='';
            switch($jiangpin_id){
			case 1:
				$jiangpin='抽中了话费5元';
				break;
			case 2:
				$jiangpin='';
				break;
			case 3:
				$jiangpin='抽中了话费20元';
				break;
			case 4:
				$jiangpin='';
				break;
			case 5:
				$jiangpin='充电宝一个';
				break;
			case 6:
				$jiangpin='';
				break;
			case 7:
				$jiangpin='账户赠金1000元';
				break;
			case 8:
				$jiangpin='';
				break;
			case 9:
				$jiangpin='暖宝宝一个';
				break;
			case 10:
				$jiangpin='';
				break;
			case 11:
				$jiangpin='';
				break;
			case 12:
				$jiangpin='';
				break;
                        case 13:
				$jiangpin='小音响一套';
				break;
			case 14:
				$jiangpin='';
				break;
		}
            $db->query("insert into {$tablepre}rotate(phone,jiangpin,addtime,ip)values('$mobile','$jiangpin','$time','$onlineip')");
            unset($_SESSION["call_phone"]);
            echo intval($jiangpin_id);
        } else {
            echo 'noMobile';
        }
        break;

    case "addmobile":
        $call_yzm = $_SESSION["call_yzm"];

        if ($validate != $call_yzm || !isset($call_yzm)) {

            exit('invalidatecode');
        }
        if ($mobile != $_SESSION["call_phone"] || !isset($_SESSION["call_phone"])) {

            exit('invalidatemobile');
        }
        $query = $db->query("select id from {$tablepre}rotate where phone='{$mobile}' limit 1");
        if ($db->num_rows($query)) {
            exit('havemobile');
        } else {

            exit('success');
        }

        break;
}
?>