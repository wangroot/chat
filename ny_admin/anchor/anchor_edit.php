<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'sys_base')===false)exit("没有权限！");

$query=$db->query("select * from  {$tablepre}anchor where id='$rid'");
$row=$db->fetch_row($query);

if($_POST['id']){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $introduction = $_POST['introduction'];
    $oldpassword = $_POST['oldpassword'];

    $password = '';
    if(($_POST['password'] == $_POST['repassword']) && $_POST['password'] && $_POST['repassword'] && $_POST['oldpassword']){
        $password = substr(md5($md5_code.$_POST['password']),4,24);
    }

    if(!empty($password) && $password != ''){
        $updateQuery = $db->query("update {$tablepre}anchor set name='{$name}',introduction='{$introduction}',photo='{$photo}',pwd='{$password}' where id='{$id}'");
    } else {
        $updateQuery = $db->query("update {$tablepre}anchor set name='{$name}',introduction='{$introduction}',photo='{$photo}' where id='{$id}'");
    }

    header("Location: anchor_list.php");
}

?>
<!DOCTYPE HTML>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets/layer/skin/layer.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/page-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/prettify.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/base.css" rel="stylesheet" type="text/css" />

<style type="text/css">
code {
	padding: 0px 4px;
	color: #d14;
	background-color: #f7f7f9;
	border: 1px solid #e1e1e8;
}
input, button {
	vertical-align:middle
}
/* tab */
#tab .tab_menu{width:100%;float:left;position:absolute;z-index:1;}
#tab .tab_menu li{float:left;width:92px;height:30px;line-height:30px;border:1px solid #ccc;border-bottom:0px;cursor:pointer;text-align:center;margin:0 2px 0 0;}
#tab .tab_box{clear:both;top:30px;position:relative;border:1px solid #CCC;border-bottom: none;background-color:#fff;}
#tab .tab_menu .selected{background-color:#fff;cursor:pointer;}
.hide{display:none;padding-top: 20px;}
.tab_box .tab_div{width: 100%; padding-top: 20px;} 

</style>


 <!--引入百度编辑器-->
   
    <script type="text/javascript" charset="utf-8" src="../Ueditor1_4_3/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="../Ueditor1_4_3/ueditor.all.min.js"> </script>
    <script type="text/javascript">
    window.UEDITOR_HOME_URL="ny_admin/Ueditor1_4_3/"
    window.onload=function(){
        window.UEDITOR_CONFIG.initialFrameWidth='80%' ; 
        window.UEDITOR_CONFIG.initialFrameHeight=320 ; 

        // window.UEDITOR_CONFIG.imageUrl="__ROOT__/Upload/"; 
        //window.UEDITOR_CONFIG.imagePath="{|U:'Admin/Addshop/addshop'}"; 
       var ue = UE.getEditor('editor', {
              autoHeightEnabled: false  //内容超过高度,编辑器出现滚条
              });
    }
    </script>  


</head>
<body>
<div class="container">
    <div class="crumbs">
        <ul id="breadcrumbs" class="breadcrumb" style=" margin-bottom: 0;border: 0;">
              <li>
                <i class="icon-home">
                </i>
                主播编辑
           </li>
           </ul>
    </div>
    
<div id="tab">
    <div class="tab_box">
    	<div class="tab_div">
            <form name="step_01" action="" method="post" id="anchor_edit" enctype="application/x-www-form-urlencoded">
              <table class="">
                <tr>
                  <td class="tableleft" style="width:100px;">账号：</td>
                  <td><?=$row[uid]?></td>
                </tr>
                  <tr>
                  <td class="tableleft" style="width:100px;">名称：</td>
                  <td><input name="name" type="text"  style="width:200px;" value="<?=$row[name]?>"/></td>
                </tr>
                  <tr>
                      <td class="tableleft" style="width:100px;">旧密码：</td>
                      <td><input id="oldpassword" name="oldpassword" type="password"  style="width:200px;" value=""/></td>
                  </tr>
                  <tr>
                      <td class="tableleft" style="width:100px;">新密码：</td>
                      <td><input id="password" name="password" type="password"  style="width:200px;" value=""/></td>
                  </tr>
                  <tr>
                      <td class="tableleft" style="width:100px;">确认密码：</td>
                      <td><input id="repassword" name="repassword" type="password"  style="width:200px;" value=""/></td>
                  </tr>
                <tr>
                  <td class="tableleft" style="width:100px;">图像：</td>
                  <td><input name="photo" type="text" id="ico" value="<?=$row[photo]?>"  class="input-large"/><br><br>
                      <button  type="button" class="button button-mini button-success" ><span id="url_bt">上传</span></button> </td>
                </tr>



                <tr>
                  <td class="tableleft">介绍</td>
                  <td><textarea  type="text" name="introduction"  id="editor" > <?=tohtml($row['introduction'])?> </textarea></td>
                </tr> 
        

                <tr>
                      <td class="tableleft">
                          &nbsp;<input type="hidden" name="uptime" value="<?=time();?>">
                          &nbsp;<input type="hidden" id="id" name="id" value="<?=$row[id];?>">
                      </td>
                      <td><button type="button" id="submit_edit" onclick="submit_edit1()" class="button button-success"> 修改 </button> <button type="button" class="button" onclick="javascript:window.location.href='anchor_list.php';"> 返回</button><input type="hidden" name="act" value="config_edit_step_01"></td>
                </tr>
              </table>
            </form>                      
        </div>

    </div>
</div>
    
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

    function submit_edit1(){
        var oldpassword = $('#oldpassword').val();
        var password = $('#password').val();
        var repassword = $('#repassword').val();
        var id = $('#id').val();

        if(oldpassword && password && repassword && (password != repassword)){
            alert('新密码与确认密码不一致');
            return false;
        } else if(oldpassword && password && repassword && (password == repassword)){
            $.post('check_password.php',{'id':id,'password':oldpassword},function(data){
                        if(data == 1){
                            alert('旧密码错误，修改密码失败');
                            return false;
                        } else {
                            $('#anchor_edit').submit();
                        }
                    },
                    'text'
            );

            return false;
        }

        $('#anchor_edit').submit();
    }

</script>


<body>
</html>
