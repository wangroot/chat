<?php
require_once '../../include/common.inc.php';

// 添加用户私信

$sendId = $_POST['sendid'];
$sendname = $_POST['uname'];
$acceptid = $_POST['user_id'];
$content = $_POST['content'];
$createtime = date('Y-m-d H:i:s');

if(!$sendname || !$sendId){
    $data['code'] = '500';
    $data['status'] = false;
    $data['msg'] = '请登录';
    echo json_encode($data); exit;
}

if($acceptid && $content && $sendId && $sendname){
    $db->query("insert into {$tablepre}private_letter (sendid, sendname, acceptid, content, createtime) values ({$sendId}, '{$sendname}', {$acceptid}, '{$content}', '{$createtime}')");

    $id = $db->insert_id();
}

if($id){
    $data['code'] = '200';
    $data['status'] = true;
    $data['msg'] = '添加成功';
} else{

    $data['code'] = '500';
    $data['status'] = false;
    $data['msg'] = '添加失败';
}

echo json_encode($data);exit;


?>