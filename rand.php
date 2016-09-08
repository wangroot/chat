<?php


function add()
{
	 $num=array(1,2,3,4,5,6,7,8,9,0);
	 $en=array('A','B','C','D','E','F','G','H','I','J','K','L','N','M','P','Q','R','S','T');
	 $new_num=array_rand($num,6);
	 $new_en=array_rand($en,2);
	 $a='';
	 $b='';
	 foreach ($new_num as $key => $value) {
	 	  $a.=$value;

	 }
     foreach ($new_en as  $v) {
	 	  $b.=$en[$v];

	  };

    return $a.$b;
	
}

echo add();

?>