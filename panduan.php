<?php

function my_get_browser(){ 
if(empty($_SERVER['HTTP_USER_AGENT'])){ 
return '命令行，机器人来了！'; 
} 
if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 9.0')){ 
return 'Internet Explorer 9.0'; 
} 
if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8.0')){ 
return 'Internet Explorer 8.0'; 
} 
if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0')){ 
return 'Internet Explorer 7.0'; 
} 
if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0')){ 
return 'Internet Explorer 6.0'; 
} 
if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')){ 
return 'Firefox'; 
} 
if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Chrome')){ 
return 'Chrome'; 
} 
if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Safari')){ 
return 'Safari'; 
} 
if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Opera')){ 
return 'Opera'; 
} 
if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'360SE')){ 
return '360SE'; 
} 
}
echo my_get_browser();
?>