<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'sys_base')===false)exit("没有权限！");

switch($act){
	case "notice_del":
		notice_del($id);
	break;
}

$operation=0;

switch($act){
    case 'config_edit_step_01':    
        //基本配置
        $arr['id']=$rid;
	      $arr['title']=$title;
        $arr['subject']=$subject;
	      $arr['keys']=$keys;
	      $arr['dc']=$dc;
	      $arr['bg']=$bg;
	      $arr['ewm']=$ewm;
        $arr['state']=$state;        
        $arr['pwd']=$pwd;
        $arr['tserver']=$tserver;
        $arr['untruefollows']=$untruefollows;
        $arr['tongji']=$tongji;
        $arr['livejs']=$livejs;
	config_edit_step_01($arr);  
        $operation=1; //提示       
        require_once '../rewrite.php' ;         
        break;
    case 'config_edit_step_02': 
        $arr[livefp]=$livefp;
	$arr[phonefp]=$phonefp;
        $arr[rebots]=$rebots;  
        config_edit_step_02($arr); 
        $operation=1;       
        require_once '../rewrite.php' ;             
        break;  
    case 'config_edit_step_03':
        $arr[id]=$rid;
        $arr[state]=$state;
	$arr[content]=$content;
	$arr[fangshi]=$fangshi;
	$arr[jiange]=$jiange;
        $operation=1;     
        config_edit_step_03($arr);  
        require_once '../rewrite.php';    		
        break;        
}
$query=$db->query("select * from  {$tablepre}config where id='$rid'");
$row=$db->fetch_row($query);

$query2=$db->query("select * from  {$tablepre}sysmsg where id=$rid");
$row2=$db->fetch_row($query2);

//系统开启时间转为为时分
 if($row['sysstart']>60*60){
     $shour=floor($row['sysstart']/(60*60));
 }
 $yu=$row['sysstart']-$shour*60*60;
  if($yu>=60){
     $sminute=floor($yu/60);
 }


    
 //关闭时间转化为时分
     if($row['sysend']>60*60){
     $xhour=floor($row['sysend']/(60*60));
 }
 $yu=$row['sysend']-$xhour*60*60;
  if($yu>=60){
     $xminute=floor($yu/60);
 }

  
 for($i=0;$i<24;$i++){
     if($i==$shour){$selected='selected';}else{$selected='';}
   $shour_list.='<option value="'.$i.'"'.$selected.'>'.$i.'</option>'; 
   if($i==$xhour){$selected='selected';}else{$selected='';}
   $xhour_list.='<option value="'.$i.'"'.$selected.'>'.$i.'</option>'; 
    }
 for($i=0;$i<60;$i++){
     if($i==$sminute){$selected='selected';}else{$selected='';}
   $sminute_list.='<option value="'.$i.'"'.$selected.'>'.$i.'</option>'; 
    if($i==$xminute){$selected='selected';}else{$selected='';}
   $xminute_list.='<option value="'.$i.'"'.$selected.'>'.$i.'</option>'; 
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
              autoHeightEnabled: false,//内容超过高度,编辑器出现滚条
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
        房间设置
           </li>
           </ul>
       
          </div>
    
<div id="tab">
	<ul class="tab_menu">
    	<li class="selected">房间信息</li>
        <li>视频/机器人</li>
        <li>房间广播</li>
        <li>房间公告</li>
    </ul>
    
    <div class="tab_box">
    	<div class="tab_div">
            <form name="step_01" action="" method="post" enctype="application/x-www-form-urlencoded">
              <table class="">
                <tr>
                  <td class="tableleft" style="width:100px;">房间号：</td>
                  <td><input name="id" type="text"  style="width:400px;" value="<?=$row[id]?>"/></td>
                </tr>        
                <tr>
                  <td class="tableleft" style="width:100px;">房间标题：</td>
                  <td><input name="title" type="text" id="title" style="width:400px;" value="<?=$row[title]?>"/></td>
                </tr>
                <tr>
                  <td class="tableleft" style="width:100px;">房间名称：</td>
                  <td><input name="subject" type="text" id="subject" style="width:400px;" value="<?=$row[subject]?>"/></td>
                </tr>      
                <tr>
                  <td class="tableleft">关 键 字：</td>
                  <td><textarea name="keys" rows="2" id="keys" style="width:400px;"><?=$row[keys]?>
          </textarea></td>
                </tr>
                <tr>
                  <td class="tableleft">站点描述：</td>
                  <td><textarea name="dc" id="dc" style="width:400px;"><?=$row[dc]?>
          </textarea></td>
                </tr>
                <tr>
                  <td class="tableleft">背景：<a href="javascript:;" tip="直播室背景图"><i class="i_help"></i></a></td>
                  <td><input name="bg" type="text" id="bg" style="width:400px;" value="<?=$row[bg]?>"/>
                    <button  type="button" class="button button-mini button-success" ><span id="bg_up_bnt"></span></button></td>
                </tr>
                <tr>
                  <td class="tableleft">二维码：<a href="javascript:;" tip="手机访问直播室二维码，宽度：160px 高度：160px"><i class="i_help"></i></a></td>
                  <td><input name="ewm" type="text" id="ewm" style="width:400px;" value="<?=$row[ewm]?>"/>
                    <button  type="button" class="button button-mini button-success" ><span id="ewm_up_bnt"></span></button></td>
                </tr>
                  <tr>
                      <td class="tableleft">Tserver服务地址：</td>
                      <td><input name="tserver" type="text" id="rebots" value="<?=$row['tserver']?>">
                          IP地址加端口号 如<?=$row['tserver']?></td>
                  </tr>
                <tr>
                      <td class="tableleft">虚假关注人数</td>
                      <td><input name="untruefollows" type="text" id="rebots" value="<?=$row['untruefollows']?>"></td>
                </tr>
                <tr>
                  <td class="tableleft">房间状态：</td>
                  <td><select name="state" id="state" style="width:100px;" onChange="system_state(this.value);">
                     <option value="1"<? if($row[state]==1) echo 'selected'; ?>>开启</option>
                      <option value="0" <? if($row[state]==0) echo 'selected'; ?>>关闭</option>
                      <option value="2" <? if($row[state]==2) echo 'selected'; ?>>加密</option>
                     <option value="3" <? if($row[state]==3) echo 'selected'; ?>>开启时间</option>
                    </select>
                    <label id="pwd_s" style="display:none"> 密码：
                      <input name="pwd" type="text" id="pwd" value="<?=$row[pwd]?>"/>
                    </label>
                  <label id="opentime" style="display:none">&nbsp;&nbsp;
                           <select name="shour" id="shour" style="width:70px;" ><?=$shour_list?></select>&nbsp;<label class="control-label">时</label>
                           <select name="sminute" id="sminute" style="width:70px;" ><?=$sminute_list?></select>&nbsp;<label class="control-label">分</label>
                          &nbsp;<span style="color:#F90">至</span>&nbsp;
                              <select name="xhour" id="xhour" style="width:70px;" ><?=$xhour_list?></select>&nbsp;<label class="control-label">时</label>
                           <select name="xminute" id="xminute" style="width:70px;" ><?=$xminute_list?></select>&nbsp;<label class="control-label">分</label>
                            </label>
                  </td>
                </tr>
                <tr>
                  <td class="tableleft">统计代码：</td>
                  <td><textarea name="tongji"   id="tongji" style="width:400px;"><?=tohtml($row['tongji'])?></textarea></td>
                </tr> 


                <tr>
                  <td class="tableleft">主播介绍</td>
                  <td><textarea  type="text" name="livejs"  id="editor" > <?=tohtml($row['livejs'])?> </textarea></td>
                </tr> 
        

                <tr>
                      <td class="tableleft">&nbsp;<input type="hidden" name="uptime" value="<?=time();?>"></td>
                      <td><button type="submit" class="button button-success"> 修改 </button> <button type="button" class="button" onclick="javascript:window.location.href='house_list.php';"> 返回</button><input type="hidden" name="act" value="config_edit_step_01"></td>
                </tr>                    
              </table>
            </form>                      
        </div>
<!--直播机器人-->         
        <div class="hide">
            <form name="step_02" action="" method="post" enctype="application/x-www-form-urlencoded">
            <table class="">            
                    <tr>
                      <td class="tableleft">PC直播代码：</td>
                      <td><textarea name="livefp" rows="10" id="livefp" style="width:700px; height:150px;"><?=$row[livefp]?></textarea>
                        </td>
                    </tr>
                    <tr>
                      <td class="tableleft">手机直播代码：</td>
                      <td><textarea name="phonefp" rows="10" id="phonefp" style="width:700px;height:150px;"><?=$row[phonefp]?></textarea></td>
                    </tr>
                    <tr>
                      <td class="tableleft">机器人最大在线数：</td>
                      <td><input name="rebots" type="text" id="rebots"  value="<?=$row[rebots]?>"/>
                      默认为0 不调用机器人</td>
                    </tr>        
                    <tr>
                        <td class="tableleft">&nbsp;<input type="hidden" name="uptime" value="<?=time();?>"></td>
                      <td><button type="submit" class="button button-success"> 修改 </button> <button type="button" class="button" onclick="javascript:window.location.href='house_list.php';"> 返回</button><input type="hidden" name="act" value="config_edit_step_02"></td>
                    </tr>                 
              </table>
            </form>                      
        </div>              
<!--//直播机器人--> 
<!--房间广播-->
        <div class="hide">
        <form name="step_03" action="" method="post" enctype="application/x-www-form-urlencoded">
           <table>
             <tr>
               <td class="tableleft" style="width:100px;">自动广播：</td>
               <td><select name="state" id="state">
              <option value="1" <? if($row2[state]==1) echo 'selected'; ?>>开启</option>
              <option value="0" <? if($row2[state]==0) echo 'selected'; ?>>关闭</option>
               </select></td>
             </tr>
             <tr>
               <td class="tableleft">广播内容：</td>
               <td><textarea name="content" rows="10" id="content" style="width:700px; height:300px;"><?=$row2[content]?></textarea>
                 <br>
                 <a>一行一条广播</a></td>
             </tr>
             <tr>
               <td class="tableleft">广播方式：</td>
               <td><select name="fangshi" id="fangshi">
              <option value="1" <? if($row2[fangshi]==1) echo 'selected'; ?>>随机广播</option>
              <option value="2" <? if($row2[fangshi]==2) echo 'selected'; ?>>循环广播</option>
              <option value="3" <? if($row2[fangshi]==3) echo 'selected'; ?>>登陆广播</option>
               </select></td>
             </tr>
              <tr>
               <td class="tableleft">广播推送时间间隔(秒)：</td>
               <td><input name="jiange" type="text" id="jiange"  value="<?=$row2[jiange]?>"/>
             </td>
             </tr>

             <tr>
                 <td class="tableleft">&nbsp;<input type="hidden" name="id" value="<?=$row2[id]?>"></td>
               <td><button type="submit" class="button button-success"> 保存 </button> <button type="button" class="button" onclick="javascript:window.location.href='house_list.php';"> 返回</button><input type="hidden" name="act" value="config_edit_step_03"></td>
             </tr>
           </table>
         </form>
        </div>
<!--//房间广播-->
<!--房间公告-->
    <div class="hide">
        <div class="container"  style="width:800px;padding: 20px 20px 0 20px;">
        <form  class="form-horizontal" action="" method="get"> 
          <ul class="breadcrumb">
            <li class="active" >
              <button type="button"  class="button button-success" id="add_ban_bt" onClick="opennotice(0,'add')">添加</button>
              &nbsp;&nbsp;
            </li>
          </ul>
          </form>
          <table  class="table table-bordered table-hover definewidth m10" style="border: 1px solid #dddddd;">
            <thead>
              <tr style="font-weight:bold" >
                <td width="40" align="center" bgcolor="#FFFFFF">编号</td>
                <td  align="center" bgcolor="#FFFFFF">标题</td>
                <td width="60" align="center" bgcolor="#FFFFFF">状态</td>
                <td width="30" align="center" bgcolor="#FFFFFF">排序</td>
                <td width="120" align="center" bgcolor="#FFFFFF">操作</td>
              </tr>
            </thead>
            <tbody id="notice">
            <?php
                $query=$db->query("select * from {$tablepre}notice where roomid='$rid' order by ov desc");
                echo for_each_notice($query,'
            <tr>
              <td bgcolor="#FFFFFF" align="center">{id}</td>
              <td align="center" bgcolor="#FFFFFF">{title}</td>
                  <td align="center" bgcolor="#FFFFFF">{type}</td>
              <td align="center" bgcolor="#FFFFFF">{ov}</td>
              <td align="center" bgcolor="#FFFFFF">
              <button class="button button-mini button-success" onClick="opennotice(\'{id}\',\'edit\')"><i class="x-icon x-icon-small icon-trash icon-white"></i>修改</button>
              <button id="del{id}" class="button button-mini button-danger" onclick="if(confirm(\'确定删除？\'))location.href=\'?act=notice_del&id={id}\'" ><i class="x-icon x-icon-small icon-trash icon-white"></i>删除</button></td>
            </tr>
            ');?>
            </tbody>
          </table>

        </div>

    </div>
<!--//房间公告-->
    </div>
</div>
    
</div>

<script type="text/javascript" src="../assets/js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="../assets/js/bui.js"></script> 
<script type="text/javascript" src="../assets/layer/layer.js"></script>
<script type="text/javascript" src="../assets/js/tip.js"></script> 
<script type="text/javascript" src="../../upload/swfupload/swfupload.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var $tab_li = $('#tab ul li');
	$tab_li.click(function(){
		$(this).addClass('selected').siblings().removeClass('selected');
		var index = $tab_li.index(this);
		$('div.tab_box > div').eq(index).show().siblings().hide();
	});	
});
</script>
<script>
    var operation=<?=$operation?>;
    if(operation==1){
     alert('房间信息修改成功！');
     window.location.href="house_list.php";
    }

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
  swfdef.upload_url="../../upload/upload.php";
  swfdef.post_params={"info":"logo"}
  var swfbg=swfdef;
  swfbg.button_placeholder_id="bg_up_bnt";
  swfbg.file_types="*.gif;*.jpg;*.png";
  swfbg.post_params={"info":"bg"}
  swfubg = new SWFUpload(swfbg);
  
  var swfewm=swfdef;
  swfewm.button_placeholder_id="ewm_up_bnt";
  swfewm.file_types="*.gif;*.jpg;*.png";
  swfewm.post_params={"info":"ewm"}
  swfuewm = new SWFUpload(swfewm);	  
});
function system_state(value){ 

if(value=='2'){$('#pwd_s').show();
}else if(value=='3'){
    
    $('#pwd_s').hide();
 $('#opentime').show();   
}else {
    $('#pwd_s').hide();
    $('#opentime').hide(); 
}
}
function tipimg_state(value){ 

if(value=='1'){$('#tip_s').show();
}else {
    $('#tip_s').hide();
  }
}
var roomstate=<?=$row['state']?>; if(roomstate=='2')$('#pwd_s').show(); if(roomstate=='3')$('#opentime').show(); 
var logintc=<?=$row['logintc']?>;if(logintc=='1')$('#tip_s').show();

$('#del1').hide();
$('#del2').hide();
BUI.use('bui/overlay',function(Overlay){
            dialog = new Overlay.Dialog({
            title:'公告板',
            width:890,
            height:690,
            buttons:[],
            bodyContent:''
          });
});
function opennotice(id,type){
    var flag = true;
    $("#notice tr").each(function(){
        if(type == "add")
        flag = false;
    });
    if(flag){
        dialog.set('bodyContent','<iframe src="notice_edit.php?id='+id+'&type='+type+'" scrolling="yes" frameborder="0" height="100%" width="100%"></iframe>');
        dialog.updateContent();
        dialog.show();
    }else{
        alert("公告一添加，不能重复添加")
    }
}
</script>


<body>
</html>
