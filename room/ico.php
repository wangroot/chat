<?php
require_once '../include/common.inc.php';
$str='
[DEFAULT]
BASEURL=http://'.$_SERVER['HTTP_HOST'].'/
[{000214A0-0000-0000-C000-000000000046}]
Prop3=19,2
[InternetShortcut]
IDList=
URL=http://'.$_SERVER['HTTP_HOST'].'/
IconFile=http://www.ifcde.com/images/logo.ico 
IconIndex=1
';
header("Content-type:application/octet-stream");
header("Content-Disposition:attachment;filename=".$cfg['config']['title'].".url");
echo $str;
?>