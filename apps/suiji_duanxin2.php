<?php
error_reporting(0);
session_start();

if ($_GET['action'] == "call_yzm") {
  
$sendto  = $_POST['static'];

$ychar="0,1,2,3,4,5,6,7,8,9";
$list=explode(",",$ychar);
$authnum = "";

for($i=0;$i<4;$i++){
    $randnum = rand(0,9);
    $authnum .= $list[$randnum];
}
$str =$authnum;
    ////生成php随机数
  
    $uid="nuoyun"; //分给你的账号
$pwd="x186706" ;//密码
$mob=$sendto; //发送号码用逗号分隔
$content='您的验证码是' . $str . '，请提交验证码完成验证。【诺云直播系统】'; //短信内容

//===========================



$sendurl="http://service.winic.org:8009/sys_port/gateway/?id=".$uid."&pwd=".$pwd."&to=".$mob."&content=". iconv("utf-8","gb2312",$content)."&time=";
$xhr=new COM("MSXML2.XMLHTTP"); 
$xhr->open("GET",$sendurl,false); 
$xhr->send(); 
$result=  $xhr->responseText;  
$data=explode("/",$result);


    if ($data[0] =='000') {
        $_SESSION["call_yzm"] = $str;
		 $_SESSION["call_phone"] = $sendto;
        $json['status'] = 1;
    }else {
        $json['status'] = 0;
    }
    echo json_encode($json);
}
?>	