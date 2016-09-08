<?php
require_once '../../include/common.inc.php';

if($_GET['act']=="upload"){
  require_once '../inc/fileupload.class.php';
    $up = new FileUpload;
    //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
    $up -> set("path", "../upload/");
    $up -> set("maxsize", 2000000);
    $up -> set("allowtype", array("txt"));
    $up -> set("israndname", true);
  
    //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
    if($up ->upload("dotxt")) {
    
        //获取上传后文件名
      $filedir =substr(dirname(__FILE__), 0, -6) . '/upload/' . $up->getFileName();
               $fe=file($filedir);
           foreach($fe as $item){
                  $item=trim(iconv("GB2312", "UTF-8", $item));
               if(!empty($item)){
                    $query=$db->query("select id from {$tablepre}rebot_custom where rebotname='{$item}' limit 1");
		if($db->num_rows($query)) continue;
                $regtime=gdate();
               $db->query("insert into {$tablepre}rebot_custom(rebotname,gid,sex,shangxian,xiaxian,regtime)	values('$item','1','1','0','86399','$regtime')");      
               }
                
                }
            unlink($filedir);    
 echo "<script>window.parent.dialog3.close();alert('机器人导入成功！');window.parent.location.reload();</script>";
 
    } else {
         //获取上传失败以后的错误提示
 
  $result= "<script>window.parent.dialog3.close();alert(\"".$up->getErrorMsg()."\");</script>";
 echo $result;
  exit;

    }
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
.file-uploader{ 
  margin-left: 155px;
  padding-top: 16px;
cursor: pointer; 

} 
</style>
</head>
<body>
<div class="container" >
    <form action="?act=upload" method="post" enctype="multipart/form-data" >
      <ul class="breadcrumb">

<table class="table table-bordered table-hover definewidth m10">

    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
   <input type="file" name="dotxt" class="file-uploader" />
   </table>

  </ul>
   
      <div style=" background: #FFF; width:100%; ">

     <input type="submit" id="reg"  class="button button-success" value="导入"/>
    <button type="button"  class="button" onclick="window.parent.dialog3.close()">关闭</button>
    &nbsp;&nbsp;<span class="auxiliary-text" style="color:red;">(提示:上传txt格式的文件文件,一行一个机器人名称)</span>
</div>
</form>
   
</div>


</body>
</html>
