<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'sys_log')===false)exit("没有权限！");
switch($act){
	case "log_del":
		log_del(implode(',',$id));
	break;
    
    case "delhistory":
       
     log_delhistory($value);
       
        break;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/page-min.css" rel="stylesheet" type="text/css" />
<!-- 下面的样式，仅是为了显示代码，而不应该在项目中使用-->
<link href="../assets/css/prettify.css" rel="stylesheet" type="text/css" />
<style type="text/css">
code { padding: 0px 4px; color: #d14; background-color: #f7f7f9; border: 1px solid #e1e1e8; }
</style>
<script>
Date.prototype.Format = function (fmt) { //author: meizz 
    var o = {
        "M+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "h+": this.getHours(), //小时 
        "m+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}
function ftime(time){
	return new Date(time*1000).Format("yyyy-MM-dd hh:mm:ss"); ; 
}
var type=[];
type["0"]="聊天";
type["1"]="登陆";
type["2"]="注册";
type["3"]="入室";
</script>
</head>
<body>
<div class="container"  style=" min-width:1300px;">
<form  class="form-horizontal" action="" method="get"> 
  <ul class="breadcrumb">
    <li class="active">
    关键字：
      <input type="text" name="key" id="key"class="abc input-default" placeholder=""> 
      &nbsp;&nbsp;
      <button type="submit"  class="button ">查询</button>
      <button type="button" class="button button-success"  onclick="doexcel()">导出Excel</button>
      <button type="button"  class="button  button-danger" id="add_ban_bt" onClick="if(confirm('确定删除？'))$('#log_list').submit()">删除所选</button>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <select name="moreselAge" id="addNew" OnChange="delhistory(this.value)">   
                         <option value="0" >删除日志历史记录</option>
                         <option value="1" >删除一天前日志历史记录</option>
                         <option value="7" >删除一周前日志历史记录</option>   
                         <option value="30" >删除一个月前日志历史记录</option>   
                         <option value="60">删除二个月前日志历史记录</option>    
                     </select>    
    &nbsp;&nbsp;</li>
   
  </ul>
  </form>
  <form action="" method="POST" enctype="application/x-www-form-urlencoded"  class="form-horizontal" id="log_list"><input type="hidden" name="act" value="log_del"> 
  <table  class="table table-bordered table-hover definewidth m10">
    <thead>
      <tr style="font-weight:bold" >
        <td width="30" align="center" bgcolor="#FFFFFF">编号</td>
        <td width="19" align="center" bgcolor="#FFFFFF"><input type="checkbox" onClick="$('.ids').attr('checked',this.checked); "></td>
        <td width="74" align="center" bgcolor="#FFFFFF">UID</td>
        <td width="100" align="center" bgcolor="#FFFFFF">用户名</td>
        <td width="100" align="center" bgcolor="#FFFFFF">IP</td>
        <td width="120" align="center" bgcolor="#FFFFFF">时间</td>
        <td width="36" align="center" bgcolor="#FFFFFF">类型</td>
        <td  width="100" align="center" bgcolor="#FFFFFF">描述</td>
        <td align="center" bgcolor="#FFFFFF">来源</td>
        <td width="55" align="center" bgcolor="#FFFFFF">操作</td>
      </tr>
    </thead>
    
<?php
echo log_list(20,$key,'
    <tr>
      <td align="center" bgcolor="#FFFFFF">{id}</td>
      <td align="center" bgcolor="#FFFFFF"><input type="checkbox" class="ids" name="id[]" value="{id}"></td>
      <td align="center" bgcolor="#FFFFFF">{uid}</td>
      <td align="center" bgcolor="#FFFFFF">{uname}</td>
      <td align="center" bgcolor="#FFFFFF">{ip}&nbsp;</td>
      <td align="center" bgcolor="#FFFFFF"><script>document.write(ftime({mtime})); </script></td>
      <td align="center" bgcolor="#FFFFFF"><script>document.write(type[\'{type}\']); </script></td>
      <td align="center" bgcolor="#FFFFFF">{msg}</td>
      <td align="center" bgcolor="#FFFFFF">{laiyuan}</td>
      <td align="center" bgcolor="#FFFFFF">
      <button type="button" class="button button-mini button-danger" onclick="if(confirm(\'确定删除？\'))location.href=\'?act=log_del&id[]={id}\'" ><i class="x-icon x-icon-small icon-trash icon-white"></i>删除</button></td>
    </tr>
')?>


  </table>
  </form> 
    <ul class="breadcrumb">
    <li class="active"><?=$pagenav?>
    </li>
  </ul>
</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui.js"></script> 
<script type="text/javascript" src="../assets/js/config.js"></script> 
<script type="text/javascript">
function delhistory(value){
    
    if(value=='0') return;
    if(confirm('确定删除日志历史记录吗？')){
      $.post("?act=delhistory",{value:value},
  function(data){
 location.reload() ;
 alert("删除历史记录成功");
  },
  "text");
    }
}
BUI.use('bui/overlay',function(Overlay){
            dialog3 = new Overlay.Dialog({
            title:'导出excel',
            width:600,
            height:240,
            buttons:[],
            bodyContent:''
          });
});
function doexcel(){
	dialog3.set('bodyContent','<iframe src="doexcel_choice.php?keys=<?=$key?>" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
	dialog3.updateContent();
	dialog3.show();
}
</script> 
</body>
</html>
