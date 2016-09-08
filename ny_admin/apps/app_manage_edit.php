<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'apps_manage')===false)exit("没有权限！");
if($act=="app_manage_add"){
	
	app_manage_add($title,$url,$ico,$w,$h,$target,$position,$s,$ov);
	$id=$db->insert_id();
	$type='edit';
        echo "<script>window.parent.dialog.close();alert('添加成功！');window.parent.location.reload();</script>";
}else if($act=="app_manage_edit"){
	app_manage_edit($id,$title,$url,$ico,$w,$h,$target,$position,$s,$ov);
        echo "<script>window.parent.dialog.close();alert('修改成功！');window.parent.location.reload();</script>";
}

$query=$db->query("select * from {$tablepre}apps_manage where id='$id'");
if($db->num_rows($query)>0)$row=$db->fetch_row($query);
else {$row[s]='0';$row[target]="POPWin"; }

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
<div class="container" style="margin-bottom:50px;">

<form action="?id=<?=$id?>&type=<?=$type?>" method="post" enctype="application/x-www-form-urlencoded">

  
<table class="table table-bordered table-hover definewidth m10">

          <tr>
            <td width="30" class="tableleft" style="width:80px;">应用名称：</td>
            <td><input name="title" type="text" id="title" value="<?=$row[title]?>"  /></td>
      </tr>
          <tr>
            <td class="tableleft">应用图标：</td>
            <td><input name="ico" type="text" id="ico" value="<?=$row[ico]?>"  class="input-large"/><br><br>
            <button  type="button" class="button button-mini button-success" ><span id="url_bt"></span></button>  </td>
          </tr>
          <tr>
            <td class="tableleft">应用连接：</td>
            <td><input name="url" type="text" id="url" value="<?=$row[url]?>"  class="input-large"/><br><br>
           <?if ($id==8){?>  <button  type="button" class="button button-mini button-success" ><span id="url_img"></span></button><? }?> </td>
          </tr>
          <tr>
            <td class="tableleft">打开方式：</td>
            <td><select name="target" id="target">
            <option value="NewWin" <? if($row[target]=='NewWin') echo 'selected'; ?>>新窗口</option>
              <option value="POPWin" <? if($row[target]=='POPWin') echo 'selected'; ?>>弹出框</option>
              <option value="QPWin" <? if($row[target]=='QPWin') echo 'selected'; ?>>气泡框</option>
            </select></td>
          </tr>
          <tr>
            <td class="tableleft">显示位置：</td>
            <td><select name="position" id="position">
               <option value="top" <? if($row[position]=='top') echo 'selected'; ?>>顶部导航</option>
              <option value="left" <? if($row[position]=='left') echo 'selected'; ?>>左侧导航</option>
          </select></td>
          </tr>
          <tr>
            <td class="tableleft">窗体宽高：</td>
            <td><input name="w" type="text" id="w" value="<?=$row[w]?>"  />
              宽（像素） 
                <br>
                <br>
<input name="h" type="text" id="h" value="<?=$row[h]?>"  />
高（像素）</td>
          </tr>
          <tr>
            <td class="tableleft">启用排序：</td>
            <td><select name="s" id="s">
            <option value="<?=$row[s]?>"><?=$row[s]=="1"?'未启用':'启用'?></option>
              <option value="0">启用</option>
              <option value="1">未启用</option>
            </select>
              状态<br>
              <br>
<input name="ov" type="text" id="ov" value="<?=$row[ov]?>"  />
排序（大至小排序）</td>
          </tr>
    </table>

  <div style="position:fixed; bottom:0; background: #FFF; width:100%; padding-top:5px;">
    <button type="submit"  class="button button-success">确定</button>
    <button type="button"  class="button" onclick="window.parent.dialog.close()">关闭</button>
    <input type="hidden" name="act" value="app_manage_<?=$type?>">
    <input type="hidden" name="id" value="<?=$id?>">
    <input type="hidden" name="type" value="<?=$type?>">
</div>
  </form>

</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui.js"></script> 
<script type="text/javascript" src="../assets/js/config.js"></script> 
<script type="text/javascript" src="../../upload/swfupload/swfupload.js"></script> 
<script>
  function swfupload_ok(fileObj,server_data){

	 var data=eval("("+server_data+")") ;
	 $("#"+data.msg.info).val(data.msg.url);
  }
  $(function(){


  var swfdef={
	  // 按钮设置
	    file_post_name:"filedata",
		button_width: 30,
		button_height: 18,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		button_text: '上传',
		button_text_style: ".upbnt{ color:#00F}",
		button_text_left_padding: 0,
		button_text_top_padding: 0,
		upload_success_handler : swfupload_ok,
		file_dialog_complete_handler:function(){this.startUpload();},
		file_queue_error_handler:function(){alert("选择文件错误");}
		}
  swfdef.flash_url="../../upload/swfupload/swfupload.swf";
  swfdef.button_placeholder_id="url_bt";
  swfdef.file_types="*.jpg;*.gif;*.png";
  swfdef.upload_url="../../upload/upload.php";
  swfdef.post_params={"info":"ico"}
  
  swfu = new SWFUpload(swfdef);
  
    var swfbg=swfdef;
  swfbg.button_placeholder_id="url_img";
  swfbg.file_types="*.gif;*.jpg;*.png";
  swfbg.post_params={"info":"url"}
  swfubg = new SWFUpload(swfbg);
});


</script>
</body>
</html>
