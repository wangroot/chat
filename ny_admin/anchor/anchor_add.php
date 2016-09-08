<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'users_admin')===false)exit("没有权限！");
if($_POST){
    if($_POST['password'] != $_POST['repassword'] || !$_POST['password'] || !$_POST['repassword']){
        echo "<script>window.parent.dialog2.close();alert('密码与确认密码不相等，添加失败');window.parent.location.reload();</script>";
    }

    $uid = $_POST['uid'];
    $password = substr(md5($md5_code.$_POST['password']),4,24);
    $name = $_POST['name'];
    $introduction = $_POST['introduction'];
    $photo = $_POST['photo'];

    $db->query("insert into {$tablepre}anchor (uid,pwd,name,photo,introduction) values('{$uid}', '{$password}', '{$name}', '{$photo}', '{$introduction}')");

    echo "<script>window.parent.dialog2.close();window.parent.location.reload();</script>";
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

<!--引入百度编辑器-->
<script type="text/javascript" charset="utf-8" src="../Ueditor1_4_3/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="../Ueditor1_4_3/ueditor.all.min.js"> </script>
<script type="text/javascript">
    window.UEDITOR_HOME_URL="ny_admin/Ueditor1_4_3/"
    window.onload=function(){
        window.UEDITOR_CONFIG.initialFrameWidth='100%' ;
        window.UEDITOR_CONFIG.initialFrameHeight=200 ;

        // window.UEDITOR_CONFIG.imageUrl="__ROOT__/Upload/";
        //window.UEDITOR_CONFIG.imagePath="{|U:'Admin/Addshop/addshop'}";
        var ue = UE.getEditor('editor', {
            autoHeightEnabled: false  //内容超过高度,编辑器出现滚条
        });
    }
</script>

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
<form action="anchor_add.php" method="post" enctype="application/x-www-form-urlencoded" id="addanchor">
  <ul class="breadcrumb">
<table class="table table-bordered table-hover definewidth m10">
          <tr>
            <td width="80" class="tableleft" style="width:70px;">账号：</td>
            <td><input name="uid" type="text" id="uid" style="width:150px;" /></td>
          </tr>

            <tr>
                <td width="80" class="tableleft" style="width:70px;">密码：</td>
                <td><input name="password" type="password" id="password" style="width:150px;" /></td>
            </tr>
            <tr>

            <tr>
                <td width="80" class="tableleft" style="width:70px;">确认密码：</td>
                <td><input name="repassword" type="password" id="repassword" style="width:150px;" /></td>
            </tr>

        <td width="80" class="tableleft" style="width:70px;">姓名：</td>
            <td><input name="name" type="text" id="name" style="width:150px;" /></td>
          </tr>
          <tr>
            <td width="80" class="tableleft" style="width:70px;">图像：</td>
            <td><input name="photo" type="text" id="ico" value=""  class="input-large"/><br><br>
                <button  type="button" class="button button-mini button-success" ><span id="url_bt">上传</span></button></td>
          </tr>

          <tr>
            <td width="80" class="tableleft" style="width:70px;">简介：</td>
            <td><textarea  type="text" name="introduction"  id="editor" ></textarea></td>
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
            if ($.trim($('#uid').val()) == "") {
                $('#uid').focus().css({
                    border: "1px solid red",
                    boxShadow: "0 0 2px red"
                });
                alert('账号不能为空！');
                return false;
            }
            var u = /[=|+-]/ ;
            if(u.test($('#uid').val())){
                $('#uid').focus().css({
                    border: "1px solid red",
                    boxShadow: "0 0 2px red"
                });

                alert('账号不能包含+ | - =特殊字符！');
                return false;
            }

            if($.trim($('#password').val()) == ''){
                $('#password').focus().css({
                    border: "1px solid red",
                    boxShadow: "0 0 2px red"
                });
                alert('密码不能为空！');
                return false;
            }

            if($.trim($('#repassword').val()) == ''){
                $('#repassword').focus().css({
                    border: "1px solid red",
                    boxShadow: "0 0 2px red"
                });
                alert('确认密码不能为空！');
                return false;
            }

            if($.trim($('#repassword').val()) != $.trim($('#password').val())){
                alert('密码与确认密码不相等！');
                return false;
            }

            if ($.trim($('#name').val()) == "") {
                $('#name').focus().css({
                    border: "1px solid red",
                    boxShadow: "0 0 2px red"
                });
                alert('姓名不能为空！');
                return false;
            }

            $('#addanchor').submit();
        });
    });

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
</body>
</html>
