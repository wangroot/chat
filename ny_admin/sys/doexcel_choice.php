<?php
require_once '../../include/common.inc.php';

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

<form action="doexcel.php?keys=<?=$keys?>" method="post" enctype="application/x-www-form-urlencoded" id="regUser">
  <ul class="breadcrumb">

<table class="table table-bordered table-hover definewidth m10">
			  <tr>
            <td width="80" class="tableleft" style="width:70px;">导出日期：</td>
            <td><label><input name="dateinfo" type="radio" value="0" />全部</label> &nbsp;&nbsp;
<label><input name="dateinfo" type="radio" value="1" checked />今天</label> &nbsp;&nbsp;
<label><input name="dateinfo" type="radio" value="2" />昨天</label> &nbsp;&nbsp;
<label><input name="dateinfo" type="radio" value="3" />本周 </label> &nbsp;&nbsp;
<label><input name="dateinfo" type="radio" value="4" />本月</label>&nbsp;&nbsp;
<label><input name="dateinfo" type="radio" value="5" />本年</label>
            </td>
          </tr>
	
       
         
        </table>

  </ul>
  <div style=" background: #FFF; width:100%; ">

     <input type="submit" id="reg"  class="button button-success" value="确定"/>
    <button type="button"  class="button" onclick="window.parent.dialog3.close()">关闭</button>
    <input type="hidden" name="act" value="user_add">
</div>
  </form>

</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui.js"></script> 
<script type="text/javascript" src="../assets/js/config.js"></script> 
<script type="text/javascript" src="../../upload/swfupload/swfupload.js"></script> 

</body>
</html>
