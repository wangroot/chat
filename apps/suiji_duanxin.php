<?php

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
 
    
    $message = '您的验证码是' . $str . '，请提交验证码完成验证。【诺云直播系统】'; 
$username='nuoyun2';
$password='rpphvqf7';
$url= "http://sms-cly.cn/smsSend.do?";
$password=md5($username.md5($password));
$curlPost ='username='.$username.'&password='.$password.'&mobile='.$sendto.'&content='. $message . '';
    $ch = curl_init(); //初始化curl
    curl_setopt($ch, CURLOPT_URL, $url); //抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //允许curl提交后,网页重定向  
    curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch); //运行curl
    curl_close($ch);
    


    if ($data >0) {
        $_SESSION["call_yzm"] = $str;
        $_SESSION["call_phone"] = $sendto;
        $json['status'] = 1;
    }else {
        $json['status'] = 0;
    }
    echo json_encode($json);
}
?>	