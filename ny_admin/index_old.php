<?php
require_once '../include/common.inc.php';
require_once 'function.php';
$auth_rules=auth_group($_SESSION['login_gid']);
?>
<!DOCTYPE HTML>
<html>
 <head>
  <title><?=$cfg['config']['title']?></title>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <link href="assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   <link href="assets/css/main-min.css" rel="stylesheet" type="text/css" />
 </head>
 <body>

  <div class="header">
    
      <div class="dl-title">
          <span class="dl-title-text"><?=mb_substr($cfg['config']['title'], 0, 30, 'utf-8');?></span>
      </div>

    <div class="dl-log">欢迎您，<span class="dl-log-user"><?=$_SESSION[admincp]?></span><a href="/room/" title="返回前台" target="_blank" class="dl-log-quit">[返回前台]</a><a href="login.php?act=user_logout" title="退出系统" class="dl-log-quit">[退出]</a>
    </div>
  </div>
   <div class="content">
    <div class="dl-main-nav">
      <div class="dl-inform"><div class="dl-inform-title"><s class="dl-inform-icon dl-up"></s></div></div>
      <ul id="J_Nav"  class="nav-list ks-clear">
        <li class="nav-item dl-selected" <?php if(stripos($auth_rules,'sys_')===false)echo " style='display:none'";?>><div class="nav-item-inner nav-home">系统设置</div></li>
        <li class="nav-item" <?php if(stripos($auth_rules,'users_')===false)echo " style='display:none'";?>><div class="nav-item-inner nav-supplier">用户管理</div></li>
        <li class="nav-item" <?php if(stripos($auth_rules,'apps_')===false)echo " style='display:none'";?>><div class="nav-item-inner nav-order">应用管理</div></li>
		<li class="nav-item" <?php if(stripos($auth_rules,'tongji_')===false)echo " style='display:none'";?>><div class="nav-item-inner nav-marketing">统计</div></li>
      </ul>
    </div>
    <ul id="J_NavContent" class="dl-tab-conten">

    </ul>
   </div>
  <script type="text/javascript" src="assets/js/jquery-1.8.1.min.js"></script>
  <script type="text/javascript" src="./assets/js/bui.js"></script>
  <script type="text/javascript" src="./assets/js/config.js"></script>

  <script>
    BUI.use('common/main',function(){
      var config = [{
          id:'sys', 
          menu:[{
              text:'系统设置',
              items:[
                <?php if(stripos($auth_rules,'sys_base')!==false) echo "{id:'1',text:'基本配置',href:'sys/base.php'},";?>
				<?php if(stripos($auth_rules,'sys_server')!==false) echo "{id:'5',text:'聊天&直播&机器人',href:'sys/server.php'},";?>
				<?php if(stripos($auth_rules,'sys_ban')!==false) echo "{id:'2',text:'用户&IP屏蔽',href:'sys/ban.php'},";?>
                                    <?php if(stripos($auth_rules,'sys_server')!==false) echo " {id:'6',text:'自动广播',href:'sys/sysmsg.php'},";?>
				<?php if(stripos($auth_rules,'sys_notice')!==false) echo " {id:'3',text:'公告板',href:'sys/notice.php'},";?>
				<?php if(stripos($auth_rules,'sys_log')!==false) echo "{id:'4',text:'日志管理',href:'sys/log.php'},";?>
                                    <?php if(stripos($auth_rules,'sys_log')!==false) echo "{id:'5',text:'聊天记录',href:'sys/chatlog.php'},";?>  
                
               
                
              ]
            }]
          },{
            id:'users',
            menu:[{
                text:'用户管理',
                items:[
				<?php if(stripos($auth_rules,'users_admin')!==false) echo "{id:'0',text:'我的用户',href:'users/users.php?fuser={$_SESSION[admincp]}'},";?>
				<?php if(stripos($auth_rules,'users_admin')!==false) echo "{id:'1',text:'用户管理',href:'users/users.php'},";?>
                                <?php if(stripos($auth_rules,'users_group')!==false) echo "{id:'2',text:'用户推广',href:'users/tuiguang.php'},";?>
				<?php if(stripos($auth_rules,'users_group')!==false) echo "{id:'2',text:'分组管理',href:'users/group.php'},";?>          
                  
				  
                ]
              }]
          },{
            id:'app',
            menu:[{
                text:'应用列表',
                items:[
				<?php if(stripos($auth_rules,'apps_hd')!==false) echo "{id:'1',text:'喊单系统',href:'apps/app_hd.php'},";?>
				<?php if(stripos($auth_rules,'apps_wt')!==false) echo "{id:'2',text:'问题答疑',href:'apps/app_wt.php'},";?>
				<?php if(stripos($auth_rules,'apps_jyts')!==false) echo "{id:'3',text:'交易提示',href:'apps/app_jyts.php'},";?>
				<?php if(stripos($auth_rules,'apps_scpl')!==false) echo "{id:'4',text:'市场评论',href:'apps/app_scpl.php'},";?>
				<?php if(stripos($auth_rules,'apps_files')!==false) echo "{id:'5',text:'共享文档',href:'apps/app_files.php'},";?>
				<?php if(stripos($auth_rules,'apps_rank')!==false) echo "{id:'6',text:'服务排行榜',href:'apps/app_rank.php'},";?>
				
                  
                  
				  
				  
				  
				  
                ]
              },
			  {
                text:'边栏应用',
                items:[
				<?php if(stripos($auth_rules,'apps_manage')!==false) echo "{id:'tab',text:'应用管理',href:'apps/app_manage.php'}";?>
                  
                ]
              }]
          },{
			 id:'tj',
			 menu:[{
                text:'讲师统计',
                items:[
				<?php if(stripos($auth_rules,'apps_hd')!==false) echo "{id:'1',text:'发展会员数',href:'tongji/tj_reg.php?type=newuser'},";?>
				<?php if(stripos($auth_rules,'apps_hd')!==false) echo "{id:'1',text:'访客数',href:'tongji/tj_login.php?type=loginroom'},";?>
				
                  
                  
				  
				  
				  
				  
                ]
              },
			  {
                text:'在线统计',
                items:[
				<?php if(stripos($auth_rules,'apps_manage')!==false) echo "{id:'zaixian',text:'在线用户',href:'tongji/tj_zaixian.php?type=newuser'}";?>
                  
                ]
              }]
		  }];
      new PageUtil.MainPage({
        modulesConfig : config
      });
    });
  </script>
 </body>
</html>
