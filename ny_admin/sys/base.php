<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'sys_base')===false)exit("没有权限！");
$operation=0;
if($act=="config_edit"){
    $arr[title]=$title;
    $arr[keys]=$keys;
    $arr[dc]=$dc;
    $arr[logo]=$logo;
    $arr[ico]=$ico;
    $arr[bg]=$bg;
    $arr[msgban]=$msgban;
    $arr[state]=$state;
    $arr[pwd]=$pwd;
    $arr[regaudit]=$regaudit;
    $arr[msgaudit]=$msgaudit;
    $arr[msglog]=$msglog;
    $arr[logintip]=$logintip;
    $arr[loginguest]=$loginguest;
    $arr[loginqq]=$loginqq;
    $arr[tongji]=$tongji;
    $arr[copyright]=$copyright;
    $arr[regban]=$regban;
    $arr[msgblock]=$msgblock;
    $arr[ewm]=$ewm;
        $arr['tipimg']=$tipimg;
        $arr[logintc]=$logintc;
        $arr['sysstart']=$sminute*60+$shour*60*60;
        $arr['sysend']=59+$xminute*60+$xhour*60*60;
    config_edit($arr);
        $operation=1;
}


$query=$db->query("select * from  {$tablepre}config where id='$rid'");
$row=$db->fetch_row($query);
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

<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>直播间基础设置</title>
    <meta name="keywords" content="直播间基础设置">
    <meta name="description" content="直播间基础设置">
    <link rel="shortcut icon" href="favicon.ico"> 
    <link href="../assets/H+/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="../assets/H+/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="../assets/H+/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="../assets/H+/css/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/H+/css/plugins/webuploader/webuploader.css">
    <link href="../assets/H+/css/style.min862f.css?v=4.1.0" rel="stylesheet">

    <script src="../assets/H+/js/jquery.min63b9.js?v=2.1.4"></script>
	</head>

<style type="text/css">
  #opentime .control-label{padding: 8px 6px;
    padding-top: 0px;}

</style>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>基础系统设置 </h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                    <form action="" method="post" class="form-horizontal" enctype="application/x-www-form-urlencoded">

                     
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><i class="fa fa-internet-explorer"></i> 网站名称</label>
                                <div class="col-sm-10">
                                    <input type="text" name="title" id="title" class="form-control" value="<?=$row[title]?>">
                                </div>
                            </div>
                                <div class="form-group">
                                <label class="col-sm-2 control-label"> 关键词</label>
                                <div class="col-sm-10">
                                    <input type="text" name="keys" id="keys" class="form-control" value="<?=$row[keys]?>">
                                </div>
                            </div>
                                <div class="form-group">
                                <label class="col-sm-2 control-label">站点描述</label>
                                <div class="col-sm-10">
                                    <input type="text" name="dc" id="dc" class="form-control" value="<?=$row[dc]?>">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><i class="fa fa-gg"></i> 站点logo</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                    <input type="text" class="form-control" name="logo" id="logo"  value="<?=$row[logo]?>">
                                    <span class="input-group-btn">
                                    		<!-- <div class="btn btn-primary" id="filePicker">选择图片</div> -->
                                         <button  type="button" class="btn btn-primary " ><span id="logo_up_bnt" ></span></button>
                                    </span>
                                </div>
                               
                            </div>
                            </div>
                              <div class="form-group">
                                <label class="col-sm-2 control-label">	站点背景</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                    <input type="text" name="bg" id="bg" class="form-control" value="<?=$row[bg]?>">
                                    <span class="input-group-btn">
                                    		<!-- <div class="btn btn-primary" id="filePicker">选择图片</div> -->
                                            <button  type="button" class="btn btn-primary"><span id="bg_up_bnt"></span></button>
                                    </span>
                                </div>
                            </div>
                            </div>
                              <div class="form-group">
                                <label class="col-sm-2 control-label">站点ico</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                    <input type="text" name="ico" class="form-control" value="<?=$row[ico]?>">
                                    <span class="input-group-btn">
                                    		<!-- <div class="btn btn-primary" id="filePicker">选择图片</div> -->
                                               <button  type="button" class="btn btn-primary"><span id="ico_up_bnt"></span></button>
                                               <style type="text/css">
                                                   #SWFUpload_0{width: 50px; height: 16px;  color: #ffffff;}
                                               </style>
                                    </span>
                                </div>
                            </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">二维码</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                    <input type="text" name="ewm" class="form-control" value="<?=$row[ewm]?>">
                                    <span class="input-group-btn">
                                    		<!-- <div class="btn btn-primary" id="filePicker">选择图片</div> -->
                                               <button  type="button" class="btn btn-primary"><span id="ewm_up_bnt"></span></button>
                                    </span>
                                </div>
                            </div>
                            </div>
                            <div class="hr-line-dashed"></div> 
                            <div class="form-group">
                                <label class="col-sm-2 control-label">注册过滤</label>
                                <div class="col-sm-10">
                                    <input type="text" name="regban" id="regban"  class="form-control" value="<?=$row[regban]?>"> <span class="help-block m-b-none">设置违反信息进行过滤，包括黄赌毒的信息。</span>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label">聊天过滤</label>
                                <div class="col-sm-10">
                                    <input type="text" name="msgban" id="msgban" class="form-control" value="<?=$row[msgban]?>"> <span class="help-block m-b-none">设置违反信息进行过滤，包括黄赌毒的信息。</span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                                                        <div class="form-group">
                                <label class="col-sm-2 control-label"><i class="fa fa-external-link"></i> 系统状态</label>

                                <div class="col-sm-10">
                                   
                                    <div class="radio i-checks sysstatus">
                                       
                                         <label> <input type="radio"  value="1" name="state" <? if($row[state]==1) echo 'checked'; ?>> <i></i>开启</label>
                                         <label> <input type="radio"  value="0" name="state" <? if($row[state]==0) echo 'checked'; ?>> <i></i>关闭</label>
                                         <label> <input type="radio"  value="2" name="state" <? if($row[state]==2) echo 'checked'; ?>> <i></i>加密</label>
                                       
                                        <?php  if($row['state']==2):?>
                                         
                                             <div style="float:left;padding-left: 10px; " class="syspwd">
                                                <input type="text"  name="pwd" id="pwd" class="form-control" name="password" value="<?=$row[pwd]?>">
                                            </div>
                                        <?php else: ?>
                                             <div style="float:left;padding-left: 10px;display:none" class="syspwd">
                                                <input type="text"  name="pwd" id="pwd" class="form-control" name="password" value="<?=$row[pwd]?>">
                                            </div>
                                        <?php endif ?>

                                         <label><input type="radio" value="3" name="state" <? if($row['state']==3) echo 'checked'; ?>> <i></i>开启时间</label>

                                       <?php  if($row['state']==3):?>    
                                         <div style="float:left;padding-left: 10px;"  class="systime">
                                             <label id="opentime" style="">&nbsp;&nbsp;
                 <select name="shour" id="shour" class="form-control m-b" style="width:70px;height: 30px;float: left;"><option value="0" selected="">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option></select><label class="control-label">时</label>
                 <select name="sminute" id="sminute" class="form-control m-b" style="width:70px;height: 30px;float: left;"><option value="0" selected="">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option></select><label class="control-label">分</label>
                &nbsp;<span style="width:50px;height: 30px;float: left;text-align: center;color: red; font-weight: bold; ">至</span>&nbsp;
                    <select name="xhour" id="xhour" class="form-control m-b" style="width:70px;height: 30px;float: left;"><option value="0" selected="">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option></select><label class="control-label">时</label>
                 <select name="xminute" id="xminute" class="form-control m-b" style="width:70px;height: 30px;float: left;"><option value="0" selected="">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option></select><label class="control-label">分</label>
                  </label>
                                        </div>
                
                 <?php else: ?>
                        
                        
                  <div style="float:left;padding-left: 10px; display:none" class="systime">
                        <label id="opentime" style="">&nbsp;&nbsp;
                 <select name="shour" id="shour" class="form-control m-b" style="width:70px;height: 30px;float: left;"><option value="0" selected="">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option></select><label class="control-label">时</label>
                 <select name="sminute" id="sminute" class="form-control m-b" style="width:70px;height: 30px;float: left;"><option value="0" selected="">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option></select><label class="control-label">分</label>
                &nbsp;<span style="width:50px;height: 30px;float: left;text-align: center;color: red; font-weight: bold; ">至</span>&nbsp;
                    <select name="xhour" id="xhour" class="form-control m-b" style="width:70px;height: 30px;float: left;"><option value="0" selected="">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option></select><label class="control-label">时</label>
                 <select name="xminute" id="xminute" class="form-control m-b" style="width:70px;height: 30px;float: left;"><option value="0" selected="">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option></select><label class="control-label">分</label>
                  </label>
                                        </div>



                 <?php endif ?>   



                                    </div>
                                    
                                </div>
                            </div>
                          <!--   <div class="form-group">
                                <label class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-2">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div> -->
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">广告弹窗</label>

                                <div class="col-sm-10">
                                   <div class="col-sm-10">
                                    <div class="radio i-checks popads">
                                        
                                        <label>
                                         <input type="radio" value="0" name="logintc" <? if($row['logintc']==0) echo 'checked'; ?>> <i></i>关闭</label>
                                        <label>
                                        <input type="radio" value="1"  name="logintc" <? if($row['logintc']==1) echo 'checked'; ?>> <i></i>开启</label>
                                         
                                      <?php  if($row['logintc']==1):?>
                                          <div class="col-sm-4 " style="display:block">
                                           <input type="text" name="tipimg" id="tipimg" class="form-control" value="<?=$row['tipimg']?>">
                                          </div>
                                          <span class="input-group-btn" style="display:block" >
                                            <!-- <div class="btn btn-primary" id="filePicker">选择图片</div> -->
                                              <button  type="button" class="btn btn-primary"><span id="tipimg_up_bnt"></span></button>
                                          </span>

                                      <?php else :?>
                                            
                                          <div class="col-sm-4" style="display:none">
                                           <input type="text" name="tipimg" id="tipimg" class="form-control" value="<?=$row['tipimg']?>">
                                          </div>
                                          <span class="input-group-btn" style="display:none">
                                            <!-- <div class="btn btn-primary" id="filePicker">选择图片</div> -->
                                              <button  type="button" class="btn btn-primary"><span id="tipimg_up_bnt"></span></button>
                                          </span>
                                      <?php endif ?>


                                    </div>
                                   
                                </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
  
                            <div class="form-group">
                                <label class="col-sm-2 control-label">系统控制</label>

                                <div class="col-sm-10">
                                    <div class="col-sm-10">
                                    <label class="checkbox-inline i-checks">
                                        <input type="checkbox" name="msgblock" value="1" <? if($row['msgblock']==1) echo 'checked'; ?>>消息屏蔽</label>

                                    <label class="checkbox-inline i-checks">
                                        <input type="checkbox" name="msglog" value="1"   <? if($row['msglog']==1) echo 'checked'; ?>>消息记录</label>

                                    <label class="checkbox-inline i-checks">
                                        <input type="checkbox" name="msgaudit" value="1" <? if($row['msgaudit']==1) echo 'checked'; ?>>消息审核</label>

                                        <label class="checkbox-inline i-checks">
                                        <input type="checkbox" name="logintip" value="1" <? if($row['logintip']==1) echo 'checked'; ?>>登录提示</label>

                                    <label class="checkbox-inline i-checks">
                                        <input type="checkbox" name="loginguest" value="1" <? if($row['loginguest']==1) echo 'checked'; ?>>游客登录</label>

                                    <label class="checkbox-inline i-checks">
                                        <input type="checkbox" name="regaudit" value="1" <? if($row['regaudit']==1) echo 'checked'; ?>>注册审核</label>


                                        <span class="help-block m-b-none">勾选后，将开启相应的功能，反之将取消对应的功能</span>
                                </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                           <div class="form-group">
                                <label class="col-sm-2 control-label"><i class="fa fa-line-chart"></i> 站点统计</label>
                                <div class="col-sm-10">
                                    <input type="text" name="tongji" id="tongji" class="form-control input-lg" value="<?=tohtml($row[tongji])?>">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                           <div class="form-group">
                                <label class="col-sm-2 control-label"><i class="fa fa-copyright"></i> 站点版权</label>
                                <div class="col-sm-10">
                                    <input type="text" name="copyright" class="form-control input-lg" value="<?=tohtml($row[copyright])?>">
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-w-m btn-primary" type="submit">保存站点</button>
                                     <input type="hidden" name="act" value="config_edit"></td>
                                  
                                </div>
                            </div>
          </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-form" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6 b-r">
                            <h3 class="m-t-none m-b">登录</h3>

                            <p>欢迎登录本站(⊙o⊙)</p>

                            <form role="form">
                                <div class="form-group">
                                    <label>用户名：</label>
                                    <input type="email" placeholder="请输入用户名" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>密码：</label>
                                    <input type="password" placeholder="请输入密码" class="form-control">
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>登录</strong>
                                    </button>
                                    <label>
                                        <input type="checkbox" class="i-checks">自动登录</label>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6">
                            <h4>还不是会员？</h4>
                            <p>您可以注册一个账户</p>
                            <p class="text-center">
                                <a href="form_basic.html"><i class="fa fa-sign-in big-icon"></i></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    <script src="../assets/H+/js/bootstrap.min14ed.js?v=3.3.6"></script>
    <script src="../assets/H+/js/content.mine209.js?v=1.0.0"></script>
    <script src="../assets/H+/js/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript">
        var BASE_URL = 'js/plugins/webuploader/index.html';
    </script>
    <script src="../assets/H+/js/plugins/webuploader/webuploader.min.js"></script>
    <script src="../assets/H+/js/demo/webuploader-demo.min.js"></script>   
    <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>
</body>
</html>


<script type="text/javascript" src="../../upload/swfupload/swfupload.js"></script>


<script type="text/javascript">
    

function swfupload_ok(fileObj,server_data){
     
     var data=eval("("+server_data+")") ;
     $("#"+data.msg.info).val(data.msg.url);
  }
  $(function(){


  var swfdef={
      // 按钮设置
        file_post_name:"filedata",
        button_width: 50,
        button_height: 15,
        button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
        button_cursor: SWFUpload.CURSOR.HAND,
        button_text: '<span class="upbnt">上传图片</span>',
        button_text_style: ".upbnt{ color:#ffffff;font-size:11.8px;line-height:-12px;}",
        button_text_left_padding: 0,
        button_text_top_padding: 0,
        upload_success_handler : swfupload_ok,
        file_dialog_complete_handler:function(){this.startUpload();},
        file_queue_error_handler:function(){alert("选择文件错误");}
        }
  swfdef.flash_url="../../upload/swfupload/swfupload.swf";
  swfdef.button_placeholder_id="logo_up_bnt";
  swfdef.file_types="*.gif;*.jpg;*.png";
  swfdef.upload_url="../../upload/upload.php";
  swfdef.post_params={"info":"logo"}
  
  swfu = new SWFUpload(swfdef);
  
  var swfico=swfdef;
  swfico.button_placeholder_id="ico_up_bnt";
  swfico.file_types="*.ico";
  swfico.post_params={"info":"ico"}
  swfuico = new SWFUpload(swfico);
  
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

var swftip=swfdef;
  swftip.button_placeholder_id="tipimg_up_bnt";
  swftip.file_types="*.gif;*.jpg;*.png";
  swftip.post_params={"info":"tipimg"}
  swfutip = new SWFUpload(swftip);

      
});



</script>

<script type="text/javascript">

 $(function(){

      $(".sysstatus").children('label').find('.iCheck-helper').click(function(){

        var inputs=$(this).prev("input[name='state']").val();
         if(inputs==2){
             $('.syspwd').css('display','block');
         }else{

              $('.syspwd').css('display','none');
         }
       
         if(inputs==3){
             $('.systime').css('display','block');
         }else{

              $('.systime').css('display','none');
         }
      });

      $(".popads").children('label').find('.iCheck-helper').click(function(){

        var inputs=$(this).prev("input[name='logintc']").val();

         if(inputs==1){
           //  alert($(this).parents('label').siblings('.col-sm-4').html());
             $(this).parents('label').siblings('.col-sm-4').css('display','block');
             $(this).parents('label').siblings('.input-group-btn').css('display','block');
         }else{

             $(this).parents('label').siblings('.col-sm-4').css('display','none');
             $(this).parents('label').siblings('.input-group-btn').css('display','none');
         }
       
      })

 })

</script>