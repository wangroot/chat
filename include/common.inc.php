<?php
error_reporting(0);
setcookie('V','3.8.4',time()+3600*24);
session_start();
//set_magic_quotes_runtime(0);

define('IS_NUOYUN', TRUE);
define('NUOYUN_ROOT', substr(dirname(__FILE__), 0, -7));
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

if (isset($_REQUEST['GLOBALS']) OR isset($_FILES['GLOBALS'])) {
	exit('Request tainting attempted.');
}
require_once NUOYUN_ROOT.'./include/global.func.php';
define('IS_ROBOT', getrobot());
if(defined('NOROBOT') && IS_ROBOT) {
	exit(header("HTTP/1.1 403 Forbidden"));
}
    $pos = strpos($_SERVER['PHP_SELF'],'ny_admin');
foreach(array('_COOKIE', '_POST', '_GET') as $_request) {
     foreach($$_request as $_key => $_value) {
    if ($pos === false) {
            if($_request=='_POST'){
             webscan_StopAttack($_key,$_value,$postfilter);}
             if($_request=='_GET'){
             webscan_StopAttack($_key,$_value,$getfilter);}
             if($_request=='_COOKIE'){
             webscan_StopAttack($_key,$_value,$cookiefilter);}
             }
		$_key{0} != '_' && $$_key = daddslashes($_value);
	}
}
if (!MAGIC_QUOTES_GPC && $_FILES && $pos === false){
	$_FILES = daddslashes($_FILES);
}
require_once NUOYUN_ROOT.'./config.inc.php';
if($linkUCenter)
require_once NUOYUN_ROOT.'./uc_client/client.php';
if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
	$onlineip = getenv('HTTP_CLIENT_IP');
} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
	$onlineip = getenv('HTTP_X_FORWARDED_FOR');
} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
	$onlineip = getenv('REMOTE_ADDR');
} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
	$onlineip = $_SERVER['REMOTE_ADDR'];
}
if(isset($_SESSION['login_uid']))$u_id=$_SESSION['login_uid'];
preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
$onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : '0.0.0.0';
unset($onlineipmatches);
require_once NUOYUN_ROOT.'./include/db_mysql.class.php';
$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname);

$dbuser = $dbpw = $dbname = NULL;
header('Content-Type: text/html; charset='.$charset);

if(empty($rid)){
 if(isset($_SESSION['roomid'])){
    $rid=$_SESSION['roomid'];
 }else{
     $rid=51888;  
   $_SESSION['roomid']=$rid;  
 }
 }else{
     
  $_SESSION['roomid']=$rid;   
 }

$cfg['config']=$db->fetch_row($db->query("select * from {$tablepre}config where id='$rid'"));
//if(empty($cfg['config'])){header("Location: /room/inputroom.html"); }
//推广用户id
if(isset($t))setcookie("tg", $t, gdate()+315360000);
?>