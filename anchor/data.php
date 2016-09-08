<?php
/**
 * Created by PhpStorm.
 * User: roy
 * Date: 16-8-30
 * Time: 下午4:59
 */

$mysql_server_name='139.196.55.146'; //改成自己的mysql数据库服务器
$mysql_username='root'; //改成自己的mysql数据库用户名
$mysql_password='root'; //改成自己的mysql数据库密码
$mysql_database='900001'; //改成自己的mysql数据库名
$table_prefix='chat_';
$md5_code = 'zhi_bo';

$conn=mysqli_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database) or die("Unable to connect to the MySQL!");;
mysqli_query($conn, "set names 'utf8'");
