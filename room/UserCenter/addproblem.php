<?php
require_once '../../include/common.inc.php';

// 添加用户反馈

$type = $_POST['type'];
$roomid = $_SESSION['roomid'];
$uid = $_POST['uid'];
$content = $_POST['content'];
$createtime = date('Y-m-d H:i:s');

if(!$uid){
    $data['code'] = '500';
    $data['status'] = false;
    $data['msg'] = '请登录';
    echo json_encode($data); exit;
}

if($type && $roomid && $uid && $content){
    $db->query("insert into {$tablepre}problem (type, roomid, uid, content, createtime) values ('{$type}', {$roomid}, {$uid}, '{$content}', '{$createtime}')");
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