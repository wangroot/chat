<?php


$array=array (
  0 => 
  array (
    'client_id' => '7f00000108fe00000002',
    'client_name' => 
    array (
      'roomid' => '1',
      'chatid' => '7f00000108fe00000002',
      'nick' => '游客0727CEA3',
      'sex' => '0',
      'age' => '0',
      'qx' => '0',
      'ip' => '127.0.0.1',
      'vip' => '助理喵喵',
      'color' => '0',
      'cam' => '0',
      'state' => '0',
      'mood' => '',
    ),
  ),
  1 => 
  array (
    'client_id' => '7f00000108fe00000006',
    'client_name' => 
    array (
      'roomid' => '1',
      'chatid' => '7f00000108fe00000006',
      'nick' => '游客0727C14C',
      'sex' => '0',
      'age' => '0',
      'qx' => '0',
      'ip' => '127.0.0.1',
      'vip' => '助理喵喵',
      'color' => '0',
      'cam' => '0',
      'state' => '0',
      'mood' => '',
    ),
  ),
  2 => 
  array (
    'client_id' => '7f00000108fd00000005',
    'client_name' => 
    array (
      'roomid' => '1',
      'chatid' => '7f00000108fd00000005',
      'nick' => '游客0727C14C',
      'sex' => '0',
      'age' => '0',
      'qx' => '0',
      'ip' => '127.0.0.1',
      'vip' => '助理喵喵',
      'color' => '0',
      'cam' => '0',
      'state' => '0',
      'mood' => '',
    ),
  ),
  3 => 
  array (
    'client_id' => '7f00000108ff00000003',
    'client_name' => 
    array (
      'roomid' => '1',
      'chatid' => '7f00000108ff00000003',
      'nick' => '游客0727C14C',
      'sex' => '0',
      'age' => '0',
      'qx' => '0',
      'ip' => '127.0.0.1',
      'vip' => '助理喵喵',
      'color' => '0',
      'cam' => '0',
      'state' => '0',
      'mood' => '',
    ),
  ),
  4 => 
  array (
    'client_id' => '7f00000108fc00000006',
    'client_name' => 
    array (
      'roomid' => '1',
      'chatid' => '7f00000108fc00000006',
      'nick' => '游客0727C14C',
      'sex' => '0',
      'age' => '0',
      'qx' => '0',
      'ip' => '127.0.0.1',
      'vip' => '助理喵喵',
      'color' => '0',
      'cam' => '0',
      'state' => '0',
      'mood' => '',
    ),
  ),

  5=> 
  array (
    'client_id' => '7f00000108fe00000002',
    'client_name' => 
    array (
      'roomid' => '1',
      'chatid' => '7f00000108fe00000002',
      'nick' => '游客0727CEA3',
      'sex' => '0',
      'age' => '0',
      'qx' => '0',
      'ip' => '127.0.0.1',
      'vip' => '助理喵喵',
      'color' => '0',
      'cam' => '0',
      'state' => '0',
      'mood' => '',
    ),
  ),
);


$rf2=array (
  0 => 
  array (
    'cmd' => 'login',
    'fd' => '2',
    'nick' => '游客07258AC2',
    'chatid' => 'x07258AC2',
  ),
  1 => 
  array (
    'cmd' => 'login',
    'fd' => '3',
    'nick' => '游客07258AC2',
    'chatid' => 'x07258AC2',
  ),
    2 => 
  array (
    'cmd' => 'login',
    'fd' => '3',
    'nick' => '游客07258AC2',
    'chatid' => 'x07258AC2',
  ),
    4 => 
  array (
    'cmd' => 'login',
    'fd' => '3',
    'nick' => '游客07258AC2',
    'chatid' => 'x07258AC2',
  ),
  // 2 => 
  // array (
  //   'cmd' => 'login',
  //   'fd' => '3',
  //   'nick' => '游客07258AC3',
  //   'chatid' => 'x07258AC3',
  // ),
  //  3 => 
  // array (
  //   'cmd' => 'login',
  //   'fd' => '3',
  //   'nick' => '游客07258AC3',
  //   'chatid' => 'x07258AC3',
  // ),
);

echo "<pre>";
$count=count($rf2);
$b=array();
  for ($i=0; $i <$count ; $i++) { 
         
         unset($rf2[$i-1]);
  };



var_dump($rf2);

echo "<pre/>";
 // var_dump($array);
  $result=array();

 foreach ($array as $key => $value) {
 	   // var_dump($value['client_name']['nick']);

 	   // foreach ($value as $key => $v) {
 	   	    
 	   // 	    var_dump( $v[$key]);
 	   // }
        // $result[$key][$key]=$value['client_name']['nick'];
        $result[$key]['client_id']=$value['client_name']['nick'];
        $result[$key]['client_name']['roomid']=$value['client_name']['roomid'];
        $result[$key]['client_name']['chatid']=$value['client_name']['chatid'];
        $result[$key]['client_name']['nick']=$value['client_name']['nick'];
        $result[$key]['client_name']['sex']=$value['client_name']['sex'];
        $result[$key]['client_name']['age']=$value['client_name']['age'];
        $result[$key]['client_name']['qx']=$value['client_name']['qx'];
        $result[$key]['client_name']['ip']=$value['client_name']['ip'];
        $result[$key]['client_name']['vip']=$value['client_name']['vip'];
        $result[$key]['client_name']['color']=$value['client_name']['color'];
        $result[$key]['client_name']['state']=$value['client_name']['state'];
        $result[$key]['client_name']['cam']=$value['client_name']['cam'];
        $result[$key]['client_name']['mood']=$value['client_name']['mood'];

 }
echo '<hr>'; 
  print_r($result);

echo '<hr>';

$aa=array();
 foreach ($result as $k => $v) {
 	// var_dump($v['client_id']);
 
    $aa[]=$v['client_id'];
 	
 }

// var_dump($aa);

foreach ($aa as $key => $value) {
	$b=array_keys($aa,$value);
}

// var_dump($b);
  unset($b[0]);
  // var_dump($b);
 foreach ($b as $key => $value) {
 	 unset($result[$value]);
 }
  
  // var_dump($result);


  foreach ($result as $key => $value) {
  	 $result[$key]['client_id']=$value['client_name']['chatid'];

  }
print_r($result);

?>


