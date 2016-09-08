<?php

// [CH] 以下变量请根据空间商提供的账号参数修改,如有疑问,请联系服务器提供商

	$dbhost = '127.0.0.1';			// 数据库服务器
	$dbuser = 'root';			// 数据库用户名
	$dbpw = 'root';				// 数据库密码
	$dbname = '900001';			// 数据库名开

// [CH] 投入使用后不能修改的变量

	$tablepre = 'chat_';   			// 表名前缀, 同一数据库安装多个聊天室请修改此处


	$dbhost2 = '127.0.0.1';			// 数据库服务器
	$dbuser2 = 'root';			// 数据库用户名
	$dbpw2 = 'root';				// 数据库密码
	$dbname2 = 'heqiang';
	$tablepre2 = '';   		// 表名前缀, 同一数据库安装多个聊天室请修改此处

    $rm='123456';

// [CH] 小心修改以下变量, 否则可能导致无法正常使用

	$dbcharset = '';			// MySQL 字符集, 可选 'gbk', 'big5', 'utf8', 'latin1', 留空为按照论坛字符集设定
	$charset = 'utf-8';			// 页面默认字符集, 可选 'gbk', 'big5', 'utf-8'
	$def_cfg='1';
	$goldname="金币";
	$discount=0.5;//礼物折扣率
	$adminemail = 'admin@nuoyun.tv';		// 系统管理员 Email
	date_default_timezone_set("Asia/Shanghai");
	$timeoffset = 0; //时差 单位 秒
	$upgrade=15; //15小时升一级
	$tserver_key="this is key!!!";//服务器连接密钥！
	
	$guest=true;//游客登录 true开启 false关闭
	$reg_unallowable="|江泽民|毛泽东|邓小平"; //注册屏蔽关键字 并为空以"|" 开头并隔开
	$msg_unallowable="黑平台|返佣|iframe|script|傻逼"; //聊天屏蔽关键字 空以"|" 并隔开
	$ipmax=5;//同一IP每天限制注册次数
	
	//UCenter整合 开始
	$linkUCenter=false;//是否整合到UC，是：true  否：false

    //  主播密码加密
    $md5_code = 'zhi_bo';
	
if($linkUCenter){
//这里开始为UCenter管理中心自动生成代码
define('UC_CONNECT', 'mysql');
define('UC_DBHOST', 'localhost:3307');
define('UC_DBUSER', 'root');
define('UC_DBPW', 'phpnow');
define('UC_DBNAME', 'ucenter');
define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', '`ucenter`.uc_');
define('UC_DBCONNECT', '0');
define('UC_KEY', '123123');
define('UC_API', 'http://192.168.1.2:81/uc_server');
define('UC_CHARSET', 'utf-8');
define('UC_IP', '');
define('UC_APPID', '1');
define('UC_PPP', '20');
//UC自动生成代码到这里结束
}
?>