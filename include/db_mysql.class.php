<?php
/***********************
Editor:一路彩虹
QQ: 76314154
***********************/
if(!defined('IS_NUOYUN')) {
	exit('Access Denied');
}
class dbstuff{
var $link;
function connect($host,$user,$passwd,$dbname='')
{
		if(!$this->link=@mysql_connect($host,$user,$passwd,1)) 
		$this->halt('数据库连接失败！ 请联系管理员！');
		
		if($this->version() > '4.1') {
				global $charset, $dbcharset;
				$dbcharset = !$dbcharset && in_array(strtolower($charset), array('gbk', 'big5', 'utf-8')) ? str_replace('-', '', $charset) : $dbcharset;
				$serverset = $dbcharset ? 'character_set_connection='.$dbcharset.', character_set_results='.$dbcharset.', character_set_client=binary' : '';
				$serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',').'sql_mode=\'\'') : '';
				$serverset && mysql_query("SET $serverset", $this->link);
		}
		if($dbname)
		mysql_select_db($dbname,$this->link) or die($this->halt("没有找到{$dbname}！ 请联系管理员！"));
}

function waiconnect($host,$user,$passwd,$dbname='')
{

	if(!$this->link=@mysql_connect($host,$user,$passwd,1)) 
		$this->halt('数据库连接失败！ 请联系管理员！');
		
		if($this->version() > '4.1') {
				global $charset, $dbcharset;
				$dbcharset = !$dbcharset && in_array(strtolower($charset), array('gbk', 'big5', 'utf-8')) ? str_replace('-', '', $charset) : $dbcharset;
				$serverset = $dbcharset ? 'character_set_connection='.$dbcharset.', character_set_results='.$dbcharset.', character_set_client=binary' : '';
				$serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',').'sql_mode=\'\'') : '';
				$serverset && mysql_query("SET $serverset", $this->link);
		}
		if($dbname)
		mysql_select_db($dbname,$this->link) or die($this->halt("没有找到{$dbname}！ 请联系管理员！"));
	
}

function close(){
	mysql_close($this->link);
}
function halt($str)
{
echo '<p style="font-family: Verdana, Tahoma; font-size: 11px; background: #FFFFFF;">'.$str.'</p>';
exit;
}
function query($sql)
{
	if(!($re=mysql_query($sql,$this->link)))
	$this->halt('MySQL Query Error<br>'.mysql_error($this->link).'<br><br>'.$sql);
	return $re;
}
function fetch_row($query)
{
	return dhtmlspecialchars(mysql_fetch_assoc($query));
}
function num_rows($query)
{
	return @mysql_num_rows($query);
}
function insert_id() {
	return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
}
function result($query, $row = 0) {
	$query = @mysql_result($query, $row);
	return $query;
}
function totxt($str)
{
	$str=strip_tags($str);
          $str=str_replace("\r","",$str);
        $str=str_replace("\r\n","",$str);
	$str=str_replace("\"","“",$str);
	$str=str_replace("'","‘",$str);
	$str=str_replace('\\',"/",$str);
	return $str;
}
function fhtml($str)
{
	//$str=trim($str);
	$str=str_replace("<","&lt;",$str);
	$str=str_replace(">","&gt;",$str);
	$str=str_replace("\n","<br>",$str);	
	return $str;
}
function version() {
	return mysql_get_server_info($this->link);
}
function caihong(){
echo "
<![CDATA[
*************************
程序设计:一路彩虹
QQ:76314154

*************************
]]>
";	
}
}
?>