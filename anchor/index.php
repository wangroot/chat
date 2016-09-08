<?php
if (!session_id()){
    session_start();
}


if (empty($_SESSION['zhibo_anchor'])){
    header('location:login.php');
    exit;
}
$user = $_SESSION['zhibo_anchor'];
$string = explode(',',$user['rooms']);

require_once '../include/common.inc.php';
require_once 'data.php';

$sql = "select * from {$table_prefix}anchor where id={$user['id']}";
$user = mysqli_query($conn,$sql);
$user = mysqli_fetch_assoc($user);
$_SESSION['zhibo_anchor'] = $user;
$type = isset($_POST['type'])?$_POST['type']:'';
if (!empty($type)){
    $term = '';
    $r = '';
    if ($type == 1){
        $r = '上课';
        $rooms = $_POST['rooms'];
        $term = ",rooms='$rooms'";
    }else{
        $r = '下课';
        $term = ",rooms=''";
    }
	if($type == 2){
		$type = 0;
	}
    $sql = "update {$table_prefix}anchor set is_online=$type$term where id={$user['id']}";
    $res = mysqli_query($conn, $sql);
    if ($res){
        $sql = "select * from {$table_prefix}anchor where id={$user['id']}";
        $user = mysqli_query($conn,$sql);
        $user = mysqli_fetch_assoc($user);
        $_SESSION['zhibo_anchor'] = $user;
        $string = explode(',',$user['rooms']);
        echo json_encode(array('status'=>0, 'info'=>'成功'));
    }else{
        echo json_encode(array('status'=>4, 'info'=>'失败'));
    }
    exit;
}
?>
<!doctype html>
<html>
    <head>
        <title>主播主页</title>
		<meta charset='utf-8' />
        <link rel="stylesheet" type="text/css" href="js/uploadify/uploadify.css">
        <link rel="stylesheet" type="text/css" href="css/index.css">
    </head>
    <body>
        <div id="main">
            <div class="left">
			<iframe src="../index_04.php" style="width:100%;height:100%;"></iframe>
			</div>
            <div class="right">
                <div class="btn">
                    <input type="button" value="上课" id="class_begin" class="btn_click">
                    <input type="button" value="下课" id="class_over" class="btn_click">
                </div>
                <div class="btn" id="rooms">
                    全选<input id="all" type="checkbox" title="全部">
                    <?php for ($i=1;$i<21;$i++){$checked=''; ?>
                        <?php for ($j=0;$j<count($string);$j++){ ?>
                            <?php if($i == $string[$j]){$checked = 'checked';break;}else{$checked='';} ?>
                        <?php } ?>
                        <?php echo $i;?><input class="item" type="checkbox" <?php echo $checked ?>  value="<?php echo $i; ?>" title="<?php echo $i;?>">
                    <?php } ?>
                </div>
                <div class="data">
                    <table border="0" cellspacing="10" cellpadding="0" width="100%">
                        <tr>
                            <th>姓名</th>
                            <td><?php echo $user['name']?></td>
                        </tr>
                        <tr>
                            <th>头像</th>
                            <td>
                                <img src="./Uploads/<?php echo $user['photo']?> ">
                            </td>
                        </tr>
                        <tr>
                            <th>简介</th>
                            <td class="heqiang_heqiang"><?php echo $user['introduction'] ?></td>
                        </tr>
						
                    </table>
						<a href="./anchor.php"><input type="button" value="信息修改"></a>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div id="canvas" style=" margin-top: 10px;">

        </div>
    <?php 
               $query=$db->query("select * from  {$tablepre}onlinenum where rid not in(4) order by rid ASC");
               $c1['rooms']=array();
               $c1['data1']=array();
               $c1['data2']=array();
               $c1['data3']=array();
        
		while($row=$db->fetch_row($query)){
                        array_push($c1['rooms'],"'".$row['rid'].'号房'."'");
			array_push($c1['data1'],intval($row['reals']));
			array_push($c1['data2'],intval($row['rebots']));
			array_push($c1['data3'],intval($row['num']));
                      
		} 
                
            $list = array(array('name'=>'在线人数','data'=> $c1['data1']),array('name'=>'机器人数','data'=>$c1['data2']),array('name'=>'总人数','data'=>$c1['data3'])); 
            
            $list = str_replace("'",'',str_replace('"data"','data',str_replace('"name"','name',json_encode($list,JSON_UNESCAPED_UNICODE))));

    ?>
    <script src="../js/acharts-min.js"></script>        
    <script type="text/javascript">
          var chart = new AChart({
            theme : AChart.Theme.SmoothBase,
            id : 'canvas',
            width : 1300,
            height : 400,
            xAxis : {
              categories: [
                   <?=implode(',',$c1['rooms'])?>
                                  
                    ]
            },
            yAxis : {
              min : 0
            },
            tooltip : {
              shared : true
            },
            seriesOptions : {
                columnCfg : {
                  stackType : 'normal' //层叠
                }
            },
            series: 
            <?=$list?>                

          });

          chart.render();
        </script>       
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/layer/2.1/layer.js"></script>
        <script type="text/javascript">
            $(function () {
                $("#all").click(function(){
                    if($(this).is(":checked")){
                        $(".item").attr("checked", true);
                    }else{
						$(".item").attr("checked", false);
					}
                });
                $("#class_begin").click(function () {
                    var rooms = '';
					$("#rooms input:checkbox").each(function () {
						if ($(this).val() != 'all' && $(this).is(":checked")) {
							rooms += $(this).val() + ",";
						}
					});
                    if (rooms != ''){
                        if (rooms != 'all') {
                            rooms = rooms.substr(0, rooms.length - 1);
                        }
                        $.ajax({
                            type: "post", dataType: "json", cache: false,
                            url: './index.php',
                            data: {'rooms': rooms, 'type': 1},
                            success: function (data) {
                                if (data.status == 0){
                                    layer.msg(data.info, {icon: 6, time: 1000});
                                }else{
                                    layer.msg(data.info, {icon: 5, time: 1000});
                                }
                            }
                        });
                    }else{
                        layer.msg('请选择房间', {icon: 2, time: 1000});
                    }
                });
                $("#class_over").click(function () {
                    $.ajax({
                        type: "post", dataType: "json", cache: false,
                        url: './index.php',
                        data: {'type': 2},
                        success: function (data) {
                            if (data.status == 0){
                                layer.msg(data.info, {icon: 6, time: 1000});
                            }else{
                                layer.msg(data.info, {icon: 5, time: 1000});
                            }
                        }
                    });
                });
            });
        </script>
    </body>
</html>