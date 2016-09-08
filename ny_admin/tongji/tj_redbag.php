<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'tongji_reg')===false)exit("没有权限！");
            $c1['title']="所有鲜花统计";
            $follow_sql='';
		$sql="select m.uid as uuid,m.username,r.*,count(*) as num from  {$tablepre}members m left join {$tablepre}redbag r on m.uid=r.juid  where m.gid='4'";
		if($ym!=""){$sql.=" and FROM_UNIXTIME(sendtime,'%Y-%m')='$ym'";
                 $c1['title']=$ym."月鲜花统计";
                   $follow_sql=" where FROM_UNIXTIME(sendtime,'%Y-%m')='$ym'";
                
                }elseif(!isset($ym)){
                   $yms=date("Y-m");
                    $sql.=" and FROM_UNIXTIME(sendtime,'%Y-%m')='$yms'";
                    $c1['title']=$yms."月鲜花统计";
                     $follow_sql=" where FROM_UNIXTIME(sendtime,'%Y-%m')='$yms'";
                }
		$query=$db->query($sql." GROUP BY m.username order by num desc");
		$c1['tag']=array();
		$c1['data']=array();
		$c1['sn']="";
	
		while($row=$db->fetch_row($query)){
                   $nickname= userinfo($row['uuid'],'{nickname}');
			array_push($c1['tag'],"'{$nickname}'");
                
                        if($row['juid']==''){
                            $row['num']='0';
                        }
			array_push($c1['data'],$row['num']);
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
	return new Date(time*1000).Format("yyyy-MM-dd hh:mm"); ; 
}
</script>
</head>
<body>
<div class="container"  style=" min-width:800px;  margin-left: 100px;">
<form  class="form-horizontal" action="" method="get"> 
  <ul class="breadcrumb">
    <li class="active">

    按统计月份：
      <input type="text" name="ym" id="ym"  class="calendar" value="<?=$ym?>"> 
      &nbsp;&nbsp;
      <button type="submit"  class="button ">查询</button> 为空统计所有
    &nbsp;&nbsp;</li>
   
  </ul>
  </form>
 
	
	<div class="row">
        <div style="width:750px;" id="canvas"></div>
      </div>
	

  <table  class="table table-bordered table-hover definewidth m10" style="width:600px">
    <thead>
      <tr style="font-weight:bold" >
      <td width="80" align="center" bgcolor="#FFFFFF">发鲜花者</td>
      <td width="80" align="center" bgcolor="#FFFFFF">发鲜花者昵称</td>
        <td  width="80" align="center" bgcolor="#FFFFFF">收鲜花者</td>
        <td width="80" align="center" bgcolor="#FFFFFF">时间</td>
  </tr>
    </thead>
<?php
$sql="select *  from {$tablepre}redbag ";

$sql=$sql.$follow_sql;

$sql.=" order by sendtime desc";

echo redbaglist(20,$sql,'
    <tr>
      <td align="center" bgcolor="#FFFFFF">{username}</td>
      <td align="center" bgcolor="#FFFFFF">{nickname}</td>
      <td align="center" bgcolor="#FFFFFF">{teacher}</td>
 <td align="center" bgcolor="#FFFFFF"><script>document.write(ftime({sendtime})); </script></td>

    </tr>
')?>


  </table>

     <ul class="breadcrumb" style="width:570px">
    <li class="active"><?=$pagenav?>
    </li>
  </ul>
</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui.js"></script> 
<script type="text/javascript" src="../assets/js/config.js"></script> 
  <script type="text/javascript">
    BUI.use('bui/calendar',function(Calendar){
          var datepicker = new Calendar.DatePicker({
            trigger:'.calendar',
			dateMask : 'yyyy-mm',
            autoRender : true
          });
        });
    BUI.use('common/page');

    BUI.use('bui/chart',function (Chart) {
      
        var chart = new Chart.Chart({
          render : '#canvas',
          width : 700,
          height : 400,
          
          title : {
            text : '<?=$c1['title']?>',
            'font-size' : '18px'
            
          },
          subTitle : {
            text : '<?=$c1['sn']?>'
          },
          xAxis : {
            categories: [
                      <?=implode(',',$c1[tag])?>
                  ]
          },
          yAxis : {
            title : {
              text : ''
            },
            min : 0
          },  
          tooltip : {
            shared : true
          },
          seriesOptions : {
              columnCfg : {
                 'color':'#00a3d7'
             
              }
          },
          series: [ {
                  name: '收到的鲜花',
                  autoWidth : false,
                 columnWidth : 20,
                  data: [<?=implode(',',$c1[data])?>],
                          labels : { //显示的文本信息
              label : {
                rotate : 0,
                y : -10,
                'fill' : '#CC0000',
                'text-anchor' : 'end',
                textShadow: '0 0 3px black',
                'font-size' : '15px'
              }
            }
 
              }]
              
        });
 
        chart.render();
    });

  </script>

</body>
</html>
