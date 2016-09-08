<?php
   header("Content-type: text/html; charset=utf-8");
 //   try {  
 //    $db = new PDO('mysql:host=120.24.159.227;dbname=hsdcw', 'root', '.0');  
 //    //查询  
 //    $rows = $db->query('SELECT * from  hsdcw_news')->fetchAll(PDO::FETCH_ASSOC);
 //    $rs = array();
 //    foreach($rows as $row) {  
 //        $rs[] = $row; 
 //    }  
	//     $db = null;  
	// } catch (PDOException $e) {  
	//     print "Error!: " . $e->getMessage() . "<br/>";  
	//     die();  
	// }
	// print_r($rs);
  
  echo substr(md5(123456123456123456), 5, 25);
  
    
    
   

?>