<?php

$ch = curl_init();

//设置选项，包括URL
curl_setopt($ch, CURLOPT_URL, "http://zbj.jdss8888.com/getdata.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
//执行并获取HTML文档内容
$output = curl_exec($ch);

//释放curl句柄
curl_close($ch);

//打印获得的数据
echo $output;


