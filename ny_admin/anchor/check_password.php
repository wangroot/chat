<?php
require_once '../../include/common.inc.php';
require_once '../function.php';

if($_POST['password'] && $_POST['id']){
    $password = substr(md5($md5_code.$_POST['password']),4,24);
    $query = $db->query("select * from  {$tablepre}anchor where id='{$id}' and pwd='{$password}'");
}

if(!$db->fetch_row($query)){
    echo '1';exit;
}

echo '2';exit;
?>
