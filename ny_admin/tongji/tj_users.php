<?php
require_once '../../include/common.inc.php';
require_once '../function.php';
if(stripos(auth_group($_SESSION['login_gid']),'tongji_reg')===false)exit("没有权限！");
$arr_true=array();
$arr_true_guest=array();
   $sql="SELECT FROM_UNIXTIME(regdate,'%Y-%m-%d' ) ymd,count(*) zong FROM {$tablepre}members";
   $sql_guest="SELECT FROM_UNIXTIME(regdate,'%Y-%m-%d' ) ymd,count(distinct regip) zong FROM {$tablepre}guest";
		if($ym!=""){
                    $sql.=" where FROM_UNIXTIME(regdate,'%Y-%m')='$ym'";
                    $sql_guest.=" where FROM_UNIXTIME(regdate,'%Y-%m')='$ym'";
                $month=$ym;
                 $c1['title']=$ym."月用户统计";
                
                }elseif(empty($ym)){
                   $yms=date("Y-m");
                    $sql.=" where FROM_UNIXTIME(regdate,'%Y-%m')='$yms'";
                    $sql_guest.=" where FROM_UNIXTIME(regdate,'%Y-%m')='$yms'";
                     $month=$yms;
                    $c1['title']=$yms."月用户统计";
                
                }
		$query=$db->query($sql." GROUP BY FROM_UNIXTIME(regdate,'%Y-%m-%d' ) ");
	while($row=$db->fetch_row($query)){
               $arr_true[$row['ymd']]=$row['zong'];
		}
                $query=$db->query($sql_guest." GROUP BY FROM_UNIXTIME(regdate,'%Y-%m-%d' ) ");
	while($row=$db->fetch_row($query)){
               $arr_true_guest[$row['ymd']]=$row['zong'];
		}
$month=strtotime($month);
$t=date('t',$month);
$arr_eday=array();
$ymd=date('Y-m-d',strtotime("+1 day",$month));
for($i=0;$i<$t;$i++){
  $NewDay = !$i ? date('Y-m-d',strtotime("+0 day",$month)): date('Y-m-d',strtotime("+1 day",$month));
  $month = strtotime($NewDay);   
 $arr_eday[$NewDay]='0';
}
$arr_he=array_merge($arr_eday,$arr_true);
$arr_he_guest=array_merge($arr_eday,$arr_true_guest);
foreach ($arr_he as $key => $value) {
$c1['tag'].="'$key'".',';
$c1['data'].=$value.',';
$sum+=$value;
} 
$c1['tag']=substr($c1['tag'], 0, -1);
$c1['data']=substr($c1['data'], 0, -1);
foreach ($arr_he_guest as $key => $value) {

$c2['data'].=$value.',';
$sum_guest+=$value;
} 
$c2['data']=substr($c2['data'], 0, -1);
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
<div class="container"  style=" min-width:1300px;">
<form  class="form-horizontal" action="" method="get"> 
  <ul class="breadcrumb">
    <li class="active">
    <input type="hidden" name="type" value="<?=$type?>">
    按统计月份：
      <input type="text" name="ym" id="ym"  class="calendar" value="<?=$ym?>"> 
      &nbsp;&nbsp;
      <button type="submit"  class="button ">查询</button> 
    &nbsp;&nbsp;</li>
   
  </ul>
  </form>

	
	
        <div class="span24" id="canvas" style="width: 100%"></div>
   
	 <div class="span24" id="canvas2" style="width: 100%"></div>




</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui.js"></script> 
<script type="text/javascript" src="../assets/js/config.js"></script> 
<script src="../assets/js/acharts-min.js"></script>
  <script type="text/javascript">
    BUI.use('bui/calendar',function(Calendar){
          var datepicker = new Calendar.DatePicker({
            trigger:'.calendar',
			dateMask : 'yyyy-mm',
            autoRender : true
          });
        });
    BUI.use('common/page');
    var Theme = AChart.Theme;
 
    //重写一个theme对象 颜色不同于默认皮肤
    Theme.color = {
       
       
        "subTitle": {
            "font-size": 14,
            "font-family": "tahoma,arial,SimSun,Georgia, Times, serif",
            "fill": "#4d759e"
        },
        "xAxis": {
            "labels": {
                "label": {
                    "y": 12
                }
            }
        },
        "yAxis": {
            "line": null,
            "tickLine": null,
            "grid": {
                "line": {
                    "stroke": "#c0c0c0"
                }
            },
            "title": {
                "text": "",
                "rotate": -90,
                "x": -30
            },
            "position": "left",
            "labels": {
                "label": {
                    "x": -12
                }
            }
        },
        "legend": {
            "dy": 60
        },
        "seriesOptions": {
            "lineCfg": {
                "duration": 1000,
                "line": {
                    "stroke-width": 2,
                    "stroke-linejoin": "round",
                    "stroke-linecap": "round"
                },
                "lineActived": {
                    "stroke-width": 3
                },
                "markers": {
                    "marker": {
                        "radius": 3
                    },
                    "actived": {
                        "radius": 6,
                        "stroke": "#00a3d7"
                    }
                },
                "animate": true
            }
          
        },
        "tooltip": {
            "x": -999,
            "y": -999
        },
        "colors" : [
            '#E67E22',
            '#3498DB',
            '#3498DB',
            '#E74C3C',
            '#F1C40F',
            '#E67E22',
            '#9B59B6',
            '#34495E',
            '#95A5A6'
        ]
      
    };
     var chart = new AChart({
    
           theme: AChart.Theme.color,//引用了新定义的皮肤
        id : 'canvas',
        forceFit : true, //自适应宽度
          height : 400,
          title : {
            text : '<?=$c1['title']?>',
            'font-size' : '21px',
            'fill':'#00a3d7'
            
          },
           subTitle : {
            text : '会员总数（<?=$sum?>）游客总数（<?=$sum_guest?>）'
          },
        plotCfg : {
          margin : [50,20,80,40] //画板的边距
        },
        xAxis : {
          categories : [<?=$c1['tag']?>],
           labels : {
              label : {
                rotate : -45,
                'text-anchor' : 'end'
              }
            }
        },
         seriesOptions : { //设置多个序列共同的属性
            lineCfg : { //不同类型的图对应不同的共用属性，lineCfg,areaCfg,columnCfg等，type + Cfg 标示
                 smooth : true,
                 
               labels : { //标示显示文本
                label : { //文本样式

                  y : -15
                },
                //渲染文本
                renderer : function(value,item){ //通过item修改属性
                  if(value >500){
                    item.fill = 'red';
                    item['font-weight'] = 'bold';
                    item['font-size'] = 16;
                  }
                  return value;
                }
              }
            }
          },
        tooltip : {
          valueSuffix : '个',
          shared : true, //是否多个数据序列共同显示信息
          crosshairs : true //是否出现基准线
        },
        series : [{
            name: '新增会员',
            data: [<?=$c1['data']?>]
            
                
        },{
                name: '新增游客',
                data: [<?=$c2['data']?>]
               
            }]
    });
 
    chart.render();
 
  </script>

</body>
</html>
