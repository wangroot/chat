<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'users_admin')===false)exit("没有权限！");
if($act=="house_add"){
	$insert_id = house_add($_POST['tpid'],$title,$keys,$dc);
        
        //重写.htaccess文件
        $room_list = array();
	$query=$db->query("select * from {$tablepre}config");       
        $list = "<IfModule mod_rewrite.c>\r\n" ;
        $list .= "RewriteEngine on\r\n";
	while($row=$db->fetch_row2($query)){
                $list .= "RewriteRule ^([".$row['id']."])$   ".$row['tpid'].".php?rid=$1\r\n";
	} 
        $list .= "</IfModule>";
       
        file_put_contents(dirname(__FILE__).'/../../.htaccess',$list);     
           
        echo "<script>window.parent.dialog2.close();alert('用户房间成功！');window.parent.location.reload();</script>";
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
input,select{vertical-align:middle;}
.liw { width:160px; height:25px; line-height:25px;}
</style>
</head>
<body>
<div class="container" >
<?php
$query=$db->query("select * from {$tablepre}auth_group order by id desc");
while($row=$db->fetch_row($query)){
    if($row[id]==1){$selected='selected';}else{$selected='';}
	$group.='<option value="'.$row[id].'"'.$selected.'>GID:'.$row[id].'-'.$row[title].'</option>';
}
if(stripos(auth_group($_SESSION['login_gid']),'users_group')===false){
	$group='';
}

?>
<form action="house_add.php" method="post" enctype="application/x-www-form-urlencoded" id="addHouse">
  <ul class="breadcrumb">
<table class="table table-bordered table-hover definewidth m10">
          <tr>
            <td width="80" class="tableleft" style="width:70px;">房间名：</td>
            <td><input name="title" type="text" id="title" style="width:350px;" /></td>
          </tr>	
          <tr>
            <td width="80" class="tableleft" style="width:70px;">关键字：</td>
            <td><input name="keys" type="text" id="keys" style="width:350px;" /></td>
          </tr>
          <tr>
            <td width="80" class="tableleft" style="width:70px;">房间描述：</td>
            <td><input name="dc" type="text" id="dc" style="width:350px;"/></td>
          </tr> 
          <tr>
            <td width="80" class="tableleft" style="width:70px;">房间模板：</td>
            <td>
                <select name="tpid">
                    <?php 
                        foreach (glob(dirname(__FILE__)."/../../index_*.php") as $filename) { 
                           echo "<option value=".  str_replace('.php','',substr($filename,-12)).">".str_replace('.php','',substr($filename,-12))."</option>" ;
                       } 
                    ?>
                </select>
            </td>
          </tr>              
          
        </table>
  </ul>
  <div style=" background: #FFF; width:100%; ">

     <input type="button" id="add"  class="button button-success" value="确定"/>
    <button type="button"  class="button" onclick="window.parent.dialog2.close()">关闭</button>
    <input type="hidden" name="act" value="house_add">
</div>
  </form>

</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui.js"></script> 
<script type="text/javascript" src="../assets/js/config.js"></script> 
<script type="text/javascript" src="../../upload/swfupload/swfupload.js"></script> 
<script>


$(document).ready(function() {
	$('#add').click(function() {
		if ($.trim($('#title').val()) == "") {
			$('#title').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
		alert('房间名称不能为空！');
			return false;
		}
               var u = /[=|+-]/ ;
                  if(u.test($('#title').val())){
                    $('#title').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
		
                        alert('房间名中不能包含+ | - =特殊字符！');
			return false;  
                  }
		if ($.trim($('#keys').val()) == "") {
			$('#keys').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
		alert('房间关键字不能为空！');
			return false;
		}
		if ($.trim($('#dc').val()) == "") {
			$('#dc').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
		alert('房间描述不能为空！');
			return false;
		}             
		if ($.trim($('#tempalte').val()) == "0") {
			$('#tempalte').focus().css({
				border: "1px solid red",
				boxShadow: "0 0 2px red"
			});
		alert('请选择房间模板！');
			return false;
		}   		
		$('#addHouse').submit();
	});	
});
</script>
</body>
</html>
