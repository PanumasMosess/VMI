<?
/**********************************************************************************/
/*conn db *************************************************************************/
//error reporting
//open
ini_set('display_errors', 1);
error_reporting(~0);

set_time_limit(1200);

/**********************************************************************************/
/*connect to the database *********************************************************/ 
//connect to the database $dbname on $dbhost with the user/password pair
//"CharacterSet" =>'UTF-8' for support thai lang
//set var to array (var must be string type)
$connectionInfo = array("Database"=>"$CFG->dbname", "UID"=>"$CFG->dbuser", "PWD"=>"$CFG->dbpass", "MultipleActiveResultSets"=>true, 'ReturnDatesAsStrings'=>true, "CharacterSet" =>'UTF-8' );
$db_con = sqlsrv_connect($CFG->dbhost, $connectionInfo);

if($db_con === false)
{
	echo "Connection could not be established. <br/>";
    die( print_r( sqlsrv_errors(), true));
}

/**********************************************************************************/
/*convert lang for support phpexcel fully *****************************************/
//tis620_to_utf8
function tis620_to_utf8($tis)
{
  $utf8 = "";
  for( $i=0 ; $i< strlen($tis) ; $i++ ){
    $s = substr($tis, $i, 1);
    $val = ord($s);
    if( $val < 0x80 ){
	 $utf8 .= $s;
    } elseif ((0xA1 <= $val and $val <= 0xDA) 
              or (0xDF <= $val and $val <= 0xFB))  {
	 $unicode = 0x0E00 + $val - 0xA0;
	 $utf8 .= chr( 0xE0 | ($unicode >> 12) );
	 $utf8 .= chr( 0x80 | (($unicode >> 6) & 0x3F) );
	 $utf8 .= chr( 0x80 | ($unicode & 0x3F) );
    }
  }
  return $utf8;
} 

//utf8_to_tis620
function utf8_to_tis620($string)
{
  $str = $string;
  $res = "";
  for ($i = 0; $i < strlen($str); $i++) {
	if (ord($str[$i]) == 224) {
	  $unicode = ord($str[$i+2]) & 0x3F;
	  $unicode |= (ord($str[$i+1]) & 0x3F) << 6;
	  $unicode |= (ord($str[$i]) & 0x0F) << 12;
	  $res .= chr($unicode-0x0E00+0xA0);
	  $i += 2;
	} else {
	  $res .= $str[$i];
	}
  }
  return $res;
}

//mb_utf8_to_tis620
function mb_utf8_to_tis620($string) 
{
    return mb_convert_encoding($string, 'UTF-8', 'TIS-620');
}

/**********************************************************************************/
/*filter_string *******************************************************************/
function filter_string($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  
  //remove
  $data = str_replace("<br />", "", $data);
  $data = str_replace("<br/>", "", $data);
  $data = str_replace("<br>", "", $data);
  $data = str_replace("\n", "", $data);
  $data = str_replace("\r", "", $data);
  $data = str_replace("\r\n", "", $data);
  
  return $data;
}

/**********************************************************************************/
/*Remove all special characters from a string *************************************/
function func_remove_char($string)
{
	//remove '
	$conv_string = str_replace("'",'', $string);
	
	//remove &
	$conv_string1 = str_replace("&",'and', $conv_string);
    return $conv_string1;
}

/**********************************************************************************/
/*endoce, decode  *****************************************************************/
function var_encode($value)
{
	$str_encode = base64_encode(base64_encode(base64_encode($value)));
	return $str_encode;
}

function var_decode($value)
{
	$str_decode = base64_decode(base64_decode(base64_decode($value)));
	return $str_decode;
}

/**********************************************************************************/
/*date diff ***********************************************************************/
function DateDiff($strDate1,$strDate2)
{
	return (strtotime($strDate2) - strtotime($strDate1)) / ( 60 * 60 * 24 );  // 1 day = 60*60*24
}

function TimeDiff($strTime1,$strTime2)
{
	return (strtotime($strTime2) - strtotime($strTime1)) / ( 60 * 60 ); // 1 Hour =  60*60
}

function DateTimeDiff($strDateTime1,$strDateTime2)
{
	return (strtotime($strDateTime2) - strtotime($strDateTime1)) / ( 60 * 60 * 24 ); // 1 Hour =  60*60
}

/**********************************************************************************/
/*date thai ***********************************************************************/
function DateThai($strDate)
{
	$strYear = date("Y",strtotime($strDate))+543;
	$strMonth = date("n",strtotime($strDate));
	$strDay = date("j",strtotime($strDate));
	$strHour = date("H",strtotime($strDate));
	$strMinute = date("i",strtotime($strDate));
	$strSeconds = date("s",strtotime($strDate));
	$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
	$strMonthThai = $strMonthCut[$strMonth];
	//return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
	return "$strMonthThai";
}

/**********************************************************************************/
/*get profile photo ***************************************************************/
function get_profile_photo($db_con,$user_code,$photo_size)
{
	//check have photo
	$strSQL_p_photo = " select * from tbl_avatars where avatars_user = '$user_code' ";
	$objQuery_p_photo = sqlsrv_query($db_con, $strSQL_p_photo);
	$objResult_p_photo = sqlsrv_fetch_array($objQuery_p_photo, SQLSRV_FETCH_ASSOC);
	$num_row_p_photo = sqlsrv_has_rows($objQuery_p_photo);
		
	//check have file
	if($num_row_p_photo === true)
	{
		return $objResult_p_photo['avatars_name'];
	}
	else
	{
		//check size
		if($photo_size == '160')
		{
			return "user2-160x160.png";
		}
		else if($photo_size == '30')
		{
			return "user2-30x30.png";
		}		
	}
	
	sqlsrv_close($db_con);
}

/**********************************************************************************/
/*sqlsrv_num_rows *****************************************************************/
/*
===Examples===
$query_xxx = sqlsrv_query($db_con, $sql_xxx, $params, $options);
*/
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

/**********************************************************************************/
/*autolink ************************************************************************/
function autolink($temp)
{
	//email link
	//$temp = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\"><font color=#FF6600>\\2@\\3</font></a>", $temp);

	//link http://
	//$temp = preg_replace("#(^|[\n ])([\w]+?://[^ \"\n\r\t<]*)#is", "\\1<a href=\"\\2\" target=\"_blank\"><font color=#FF6600>\\2</font></a>", $temp);

	//link www.
	$temp = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\"><font color=#FF6600>\\2</font></a>", $temp);

	return $temp;
}

/**********************************************************************************/
/*.htaccess ***********************************************************************/
function redirectTohttps()
{
	if($_SERVER['HTTPS']!="on")
	{
		$redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		header("Location:$redirect");
	}
}

/**********************************************************************************/
/*Convert a string to an array with multibyte string ******************************/
/*
===Description===
array getMBStrSplit ( string $string [, int $split_length = 1 ] )
===Parameters===
string = ข้อความที่ต้องการนำมาแบ่ง
split_length = จำนวนความยาวของตัวอักษรที่จะแบ่ง (ค่าปริยายคือ 1)

===Examples===
$str = "สวัสดีชาวโลก";
$arr1 = getMBStrSplit($str);
$arr2 = getMBStrSplit($str, 3);
*/
function getMBStrSplit($string, $split_length = 1)
{
	mb_internal_encoding('UTF-8');
	mb_regex_encoding('UTF-8'); 
	
	$split_length = ($split_length <= 0) ? 1 : $split_length;
	$mb_strlen = mb_strlen($string, 'utf-8');
	$array = array();
	$i = 0; 
	
	while($i < $mb_strlen)
	{
		$array[] = mb_substr($string, $i, $split_length);
		$i = $i+$split_length;
	}
	
	return $array;
}

/**********************************************************************************/
/*Get string length for Character Thai ********************************************/
/*
===Description===
int getStrLenTH ( string $string )
===Parameters===
string = ข้อความที่ต้องการนับ

===Examples===
$str = 'สวัสดีชาวโลก';
echo getStrLenTH($str); // 10

$str = 'เรารักประเทศไทย';
echo getStrLenTH($str); // 14
*/
function getStrLenTH($string)
{
	$array = getMBStrSplit($string);
	$count = 0;
	
	foreach($array as $value)
	{
		$ascii = ord(iconv("UTF-8", "TIS-620", $value ));
		
		if( !( $ascii == 209 ||  ($ascii >= 212 && $ascii <= 218 ) || ($ascii >= 231 && $ascii <= 238 )) )
		{
			$count += 1;
		}
	}
	return $count;
}

/**********************************************************************************/
/*Get part of string for Character Thai *******************************************/
/*
===Description===
string getSubStrTH ( string $string , int $start [, int $length ] )
===Parameters===
string = ข้อความที่ต้องการนำมาตัด
start = ตำแหน่งเริ่มต้นที่จะเก็บข้อความ โดนต่ำแหน่งแรกคือ 0 (ยังไม่สามารถทำให้ใช้คำสั่งติดลบได้นะครับ เช่น -10 แบบฟังก์ฟัน substr() )
length = ความยาวของตัวอักษรที่จะเก็บ นับจากต่ำแหน่งเริ่มต้น ( $start )

===Examples===
$str = 'ประเทศไทยน่าอยู่';
echo getSubStrTH($str, 0, 9); // ประเทศไทย

$str = 'เรารักประเทศไทย';
echo getSubStrTH($str, 6, 40); // ประเทศไทย

===Examples 1===
$Topic = "[แจก] ฟังก์ชั่น str-split(), strlen(), substr() สำหรับตัวอักษรแบบ UTF-8 และ ภาษาไทย (ไม่นับวรรณยุกต์และสระบนล่าง)";
echo (getStrLenTH($Topic) > 50) ? getSubStrTH($Topic, 0, 50). "..." : $Track;

===Examples 2===
$Topic = "HTML5 Articles, Examples, Tricks and Tips ความรู้เกี่ยวกับเทรนใหม่ HTML 5...ห้า...ห้า...ห้า ^___^ ";
if( getStrLenTH($Topic) > 50)
{
	echo getSubStrTH($Topic, 0, 50). "...";
}
else
{
	echo $Topic;
}

===result===
[แจก] ฟังก์ชั่น str-split(), strlen(), substr() สำหรับต...
HTML5 Articles, Examples, Tricks and Tips ความรู้เกี่ย...
*/
function getSubStrTH($string, $start, $length)
{			
	$length = ($length+$start)-1;
	$array = getMBStrSplit($string);
	$count = 0;
	$return = "";
		
	for($i=$start; $i < count($array); $i++)
	{
		$ascii = ord(iconv("UTF-8", "TIS-620", $array[$i] ));
		
		if( $ascii == 209 ||  ($ascii >= 212 && $ascii <= 218 ) || ($ascii >= 231 && $ascii <= 238 ) )
		{
			//$start++;
			$length++;
		}
		
		if( $i >= $start )
		{
			$return .= $array[$i];
		}
		
		if( $i >= $length )
			break;
		}
	
	return $return;
}

/**********************************************************************************/
/*detect mobile *******************************************************************/
function detect_mobile()
{ 
	if(preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']))
	return true;
	else
	return false;
}

/**********************************************************************************/
/*detect browser  *****************************************************************/
function get_browser_name()
{
    $arr_browsers = ["Firefox", "Chrome", "Safari", "Opera", "MSIE", "Trident", "Edge"];
 
	$agent = $_SERVER['HTTP_USER_AGENT'];
	 
	$user_browser = '';
	foreach ($arr_browsers as $browser) {
		if (strpos($agent, $browser) !== false) {
			$user_browser = $browser;
			break;
		}   
	}
	 
	switch ($user_browser) {
		case 'MSIE':
			$user_browser = 'Internet Explorer';
			break;
	 
		case 'Trident':
			$user_browser = 'Internet Explorer';
			break;
	 
		case 'Edge':
			$user_browser = 'Internet Explorer';
			break;
	}
	
	return $user_browser;
}

/**********************************************************************************/
/*detect browser2  ****************************************************************/
function getBrowser()
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
	
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    
    // Next get the name of the useragent yes seperately and for good reason
    if((preg_match('/MSIE/i',$u_agent) || preg_match('/Trident/i',$u_agent)) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    }
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
	elseif(preg_match('/Edge/i', $u_agent)) 
    { 
        $bname = 'Edge'; 
        $ub = "Edge"; 
    }	
    
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'platform'  => $platform
    );
}

// now try it
//$ua=getBrowser();
//$yourbrowser= "Your browser: " . $ua['name'] . " " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
//print_r($yourbrowser);

/**********************************************************************************/
/*IP for mobile for show results **************************************************/
//$serverIP = $_SERVER['REMOTE_ADDR'];
function getVisitorIP()
{
	// Get real visitor IP behind CloudFlare network
	if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])) 
	{
		$_SERVER['REMOTE_ADDR']    = $_SERVER["HTTP_CF_CONNECTING_IP"];
		$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
	
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = $_SERVER['REMOTE_ADDR'];

	if(filter_var($client, FILTER_VALIDATE_IP))
	{
		$ip = $client;
	}
	elseif(filter_var($forward, FILTER_VALIDATE_IP))
	{
		$ip = $forward;
	}
	else
	{
		$ip = $remote;
	}

	return $ip;
}

/**********************************************************************************/
/*application settings ************************************************************/
//smtp mail config
function get_smtp_mail_config($db_con,$type_selector,$ver_status)
{
	$strSQL_app_smtp = "
	select 
		[email_srv_host]
		,[email_srv_port]
		,[email_srv_usr]
		,[email_srv_pass]
		,[email_srv_form_title]
	from [tbl_config_email_server]
	where 
		[email_srv_status] = '$ver_status'
	";
	
	//clear
	$str_email_srv_host = "";
	$str_email_srv_port = "";
	$str_email_srv_usr = "";
	$str_email_srv_pass = "";
	$str_email_srv_form_title = "";
	
	$objQuery_app_smtp = sqlsrv_query($db_con, $strSQL_app_smtp);					
	while($objResult_app_smtp = sqlsrv_fetch_array($objQuery_app_smtp, SQLSRV_FETCH_ASSOC))
	{
		$str_email_srv_host = $objResult_app_smtp["email_srv_host"];
		$str_email_srv_port = $objResult_app_smtp["email_srv_port"];
		$str_email_srv_usr = $objResult_app_smtp["email_srv_usr"];
		$str_email_srv_pass = $objResult_app_smtp["email_srv_pass"];
		$str_email_srv_form_title = $objResult_app_smtp["email_srv_form_title"];
	}
	
	//selector by type
	if($type_selector == "SmtpHost")
	{
		return $str_email_srv_host;
	}
	else if($type_selector == "SmtpPort")
	{
		return $str_email_srv_port;
	}
	else if($type_selector == "SmtpUsr")
	{
		return $str_email_srv_usr;
	}
	else if($type_selector == "SmtpPass")
	{
		return $str_email_srv_pass;
	}
	else if($type_selector == "SmtpFormTitle")
	{
		return $str_email_srv_form_title;
	}

	sqlsrv_close($db_con);
}

/**********************************************************************************/
/*get url *************************************************************************/
function FuncCurUrl()
{
	$cur_url = 'http' . (($_SERVER['HTTPS'] == 'on') ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	return $cur_url;
}

/**********************************************************************************/
/*Print Tags **********************************************************************/
function get_cus_code($db_con,$type_selector,$fg_code_gdj,$fg_code_gdj_desc)
{
	$strSQL = "
	SELECT [tags_code]
		  ,[tags_fg_code_gdj]
		  ,[tags_fg_code_gdj_desc]
		  ,[tags_prod_plan]
		  ,[tags_packing_std]
		  ,[tags_total_qty]
		  ,[tags_token]
		  ,[bom_cus_code]	  
	FROM tbl_tags_running
	inner join 
	  tbl_bom_mst
	  on 
	  tbl_tags_running.tags_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
	 where tags_fg_code_gdj = '$fg_code_gdj' and tags_fg_code_gdj_desc = '$fg_code_gdj_desc'
	 group by
	 [tags_code]
		  ,[tags_fg_code_gdj]
		  ,[tags_fg_code_gdj_desc]
		  ,[tags_prod_plan]
		  ,[tags_packing_std]
		  ,[tags_total_qty]
		  ,[tags_token]
		  ,[bom_cus_code]
	";
	
	//clear
	$bom_cus_code = "";
	
	$objQuery = sqlsrv_query($db_con, $strSQL);					
	while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
	{
		$bom_cus_code = $objResult["bom_cus_code"];
	}
	
	//selector by type
	if($type_selector == "print_tags")
	{
		return $bom_cus_code;
	}
	
	sqlsrv_close($db_con);
}

/**********************************************************************************/
/*customer from BOM MST ***********************************************************/
function get_customer($db_con,$type_selector,$fg_code_set_abt,$sku_code_abt,$fg_code_gdj,$pj_name)
{
	$strSQL = "
	SELECT 
		bom_cus_code
		,bom_cus_name
	FROM tbl_bom_mst
	where
	bom_fg_code_set_abt = '$fg_code_set_abt'
	and
    bom_fg_sku_code_abt = '$sku_code_abt'
	and
    bom_fg_code_gdj = '$fg_code_gdj'
	and
	bom_pj_name = '$pj_name'
	";
	
	//clear
	$bom_cus_code = "None";
	$bom_cus_name = "None";
	
	$objQuery = sqlsrv_query($db_con, $strSQL);					
	while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
	{
		$bom_cus_code = $objResult["bom_cus_code"];
		$bom_cus_name = $objResult["bom_cus_name"];
	}
	
	//selector by type
	if($type_selector == "cus_code")
	{
		return $bom_cus_code;
	}
	else if($type_selector == "cus_name")
	{
		return $bom_cus_name;
	}
	
	sqlsrv_close($db_con);
}

/**********************************************************************************/
/*Stock each FG code GDJ **********************************************************/
function get_stock_each_fg_gdj($db_con,$fg_code_set_abt,$sku_code_abt,$fg_code_gdj,$pj_name,$ship_type,$part_customer)
{
	$strSQL = "
		select 
			sum(tags_packing_std) as sum_packing_std
		from tbl_tags_running
		left join
		tbl_receive
		on tbl_tags_running.tags_code = tbl_receive.receive_tags_code
		where
		receive_status = 'Received'
		and
		receive_repn_id is NULL
		and
		tags_fg_code_gdj = '$fg_code_gdj'
	";
	$objQuery = sqlsrv_query($db_con, $strSQL);
	//clear
	$sum_packing_std = 0;
	while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
	{
		$sum_packing_std = $objResult["sum_packing_std"];
	}
	
	return $sum_packing_std;
	
	sqlsrv_close($db_con);
}

/**********************************************************************************/
/*get FIFO pick *******************************************************************/
function get_fifo_pick_qty($db_con,$repn_id)
{
	$strSql_fifo_pick_qty = " 
	SELECT 
		sum(tags_packing_std) as sum_fifo_pick_qty
	FROM tbl_receive
	left join 
	tbl_tags_running
	on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
	where 
	receive_repn_id = '$repn_id'
	";
	$objQuery_fifo_pick_qty = sqlsrv_query($db_con, $strSql_fifo_pick_qty);
	
	//clear
	$sum_fifo_pick_qty = 0;
	while($objResult_fifo_pick_qty = sqlsrv_fetch_array($objQuery_fifo_pick_qty, SQLSRV_FETCH_ASSOC))
	{
		$sum_fifo_pick_qty = $objResult_fifo_pick_qty['sum_fifo_pick_qty'];
	}
	
	return $sum_fifo_pick_qty;
	
	sqlsrv_close($db_con);
}

/**********************************************************************************/
/*check picking fifo split tags ***************************************************/
function check_picking_split_tags($db_con,$repn_id)
{
	$strSql_chk_split_tags = " 
	SELECT 
		count([tags_split_repn_id]) as count_tags_split_repn_id
	FROM [tbl_replenishment]
	left join tbl_tags_split_log
	on tbl_replenishment.repn_id = tbl_tags_split_log.tags_split_repn_id
	where tags_split_repn_id = '$repn_id'
	";
	$objQuery_chk_split_tags = sqlsrv_query($db_con, $strSql_chk_split_tags);
	
	while($objResult_chk_split_tags = sqlsrv_fetch_array($objQuery_chk_split_tags, SQLSRV_FETCH_ASSOC))
	{
		$count_tags_split_repn_id = $objResult_chk_split_tags['count_tags_split_repn_id'];
	}
	
	return $count_tags_split_repn_id;
	
	sqlsrv_close($db_con);
}

/**********************************************************************************/
/*ABT auto adjust qty pcs to packing **********************************************/
function auto_adjQty_pcs_to_packing($db_con,$fg_code_set_abt,$sku_code_abt,$fg_code_gdj,$pj_name,$ship_type,$part_customer,$order_qty)
{
	$strSQL = "
		select 
			bom_packing
		from 
			tbl_bom_mst
		where
			bom_fg_code_set_abt = '$fg_code_set_abt'
			and bom_fg_sku_code_abt = '$sku_code_abt'
			and bom_fg_code_gdj = '$fg_code_gdj'
			and bom_pj_name = '$pj_name'
			and bom_ship_type = '$ship_type'
			and bom_part_customer = '$part_customer'
	";
	$objQuery = sqlsrv_query($db_con, $strSQL);
	//clear
	$bom_packing = 0;
	$str_total_qty = 0;
	$str_pack = 0;
	
	while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
	{
		$bom_packing = $objResult["bom_packing"];
	}
	
	//cal
	if($bom_packing > 0)
	{
		$str_pack = ceil($order_qty / $bom_packing);
		$str_total_qty = $str_pack * $bom_packing;
	}
	
	return $str_total_qty;
	
	sqlsrv_close($db_con);
}

/**********************************************************************************/
/*query get project name **********************************************************/
function _get_all_project_name($db_con)
{
	$strSQL = " SELECT bom_pj_name FROM tbl_bom_mst group by bom_pj_name order by bom_pj_name asc ";
	$objQuery = sqlsrv_query($db_con, $strSQL) or die ("Error Query [".$strSQL."]");
	//clear
	$str_all_pj = '';
	while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
	{
		$str_all_pj.="".ltrim(rtrim($objResult["bom_pj_name"]))."".",";
	}
	
	//substr last digit
	$str_all_pj = substr($str_all_pj, 0, -1);
	
	return $str_all_pj;
	sqlsrv_close($db_con);
}

/**********************************************************************************/
/*query get project name customer **********************************************************/
function _get_all_project_name_customer($db_con, $section)
{			
	
	if($section == "IT"){
		$strSQL = " SELECT bom_pj_name FROM tbl_bom_mst group by bom_pj_name order by bom_pj_name asc ";
	}
	else 
	{     	
		$strSQL = " SELECT bom_pj_name FROM tbl_bom_mst where bom_cus_code = '$section' group by bom_pj_name";
	}
	

	$objQuery = sqlsrv_query($db_con, $strSQL) or die ("Error Query [".$strSQL."]");
	//clear
	$str_all_pj = '';
	while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
	{
		$str_all_pj.="".ltrim(rtrim($objResult["bom_pj_name"]))."".",";
	}
	
	//substr last digit
	$str_all_pj = substr($str_all_pj, 0, -1);
	
	return $str_all_pj;
	sqlsrv_close($db_con);
}

/**********************************************************************************/
/*query get each project name **********************************************************/
function _get_each_project_name($db_con,$pj_name)
{
	$strSQL = " SELECT bom_pj_name FROM tbl_bom_mst where bom_pj_name = '$pj_name' group by bom_pj_name ";
	$objQuery = sqlsrv_query($db_con, $strSQL) or die ("Error Query [".$strSQL."]");
	//clear
	$str_all_pj = '';
	while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
	{
		$str_all_pj.="".ltrim(rtrim($objResult["bom_pj_name"]))."".",";
	}
	
	//substr last digit
	$str_all_pj = substr($str_all_pj, 0, -1);
	
	return $str_all_pj;
	sqlsrv_close($db_con);
}

/**********************************************************************************/
/*load vmi stock ******************************************************************/
function get_vmi_stock($db_con,$pj_name,$type_name)
{
	//get project name
	$str_implode_all_PJ = _get_each_project_name($db_con,$pj_name);
	//explode
	$separated_all_PJ = explode(",", $str_implode_all_PJ);
	$num_all_PJ_separated = count($separated_all_PJ);

	foreach ($separated_all_PJ as $value_all_PJ) 
	{
		//get VMI stock
		$objQuery_sp_vmi_stock = sqlsrv_query($db_con, " EXEC sp_db_wms_vmi_stock '$value_all_PJ' ");
		$objResult_sp_vmi_stock = sqlsrv_fetch_array($objQuery_sp_vmi_stock, SQLSRV_FETCH_ASSOC);

		//check null
		if($objResult_sp_vmi_stock['sum_pcs_qty'] == NULL){ $str_sp_vmi_stock_sum_pcs_qty = '0'; } else { $str_sp_vmi_stock_sum_pcs_qty = $objResult_sp_vmi_stock['sum_pcs_qty']; }
		if($objResult_sp_vmi_stock['count_pack_qty'] == NULL){ $str_sp_vmi_stock_count_pack_qty = '0'; } else { $str_sp_vmi_stock_count_pack_qty = $objResult_sp_vmi_stock['count_pack_qty']; }
	}
	
	if($type_name == "pcs")
	{
		return $str_sp_vmi_stock_sum_pcs_qty;
	}
	else if($type_name == "pack")
	{
		return $str_sp_vmi_stock_count_pack_qty;
	}
	
	sqlsrv_close($db_con);
}

/**********************************************************************************/
/*strpos var word *****************************************************************/
function strpos_var($haystack, $needle, $offset=0) {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $query) {
        if(strpos($haystack, $query, $offset) !== false) 
		return true; // stop on first true result
    }
    return false;
}


/**********************************************************************************/
/*load vmi stock price******************************************************************/
function get_vmi_stock_price($db_con,$pj_name,$date_start,$date_end)
{
	//get project name
	$str_implode_all_PJ = _get_each_project_name($db_con,$pj_name);
	//explode
	$separated_all_PJ = explode(",", $str_implode_all_PJ);
	$num_all_PJ_separated = count($separated_all_PJ);

	if($date_start == "" && $date_end == ""){
		$date_start = '';
		$date_end = '';
	}



	foreach ($separated_all_PJ as $value_all_PJ) 
	{
		//get VMI stock
		$objQuery_sp_vmi_stock = sqlsrv_query($db_con, " EXEC sp_db_wms_vmi_stock_price '$value_all_PJ', '$date_start', '$date_end' ");
		$objResult_sp_vmi_stock_price = sqlsrv_fetch_array($objQuery_sp_vmi_stock, SQLSRV_FETCH_ASSOC);

		//check null
		if($objResult_sp_vmi_stock_price['total_wms_pcs_price_'] == NULL){ $str_sp_vmi_stock_sum_pcs_price = '0'; } else { $str_sp_vmi_stock_sum_pcs_price = $objResult_sp_vmi_stock_price['total_wms_pcs_price_']; }
		$vmi_stock_price = $str_sp_vmi_stock_sum_pcs_price;
	}
	

	return $vmi_stock_price;

	
	sqlsrv_close($db_con);
}


function update_status_b2c($db_con)
{
	$strSql_DTNSheet = " 
	SELECT 
	[dn_h_dtn_code]
   ,[dn_h_cus_code]
   ,[dn_h_cus_name]
   ,[dn_h_driver_code]
   ,[dn_h_delivery_date]
   ,[dn_h_status]
   ,[ps_t_pj_name]
   ,[b2c_track_num]
   ,[b2c_repn_order_ref]
   ,sum([tags_packing_std]) as sum_picking_std 
		FROM [tbl_dn_head]
		left join
		tbl_dn_tail
		on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
		left join
		tbl_picking_tail
		on tbl_dn_tail.dn_t_picking_code = tbl_picking_tail.ps_t_picking_code
		left join
		tbl_receive
		on tbl_picking_tail.ps_t_tags_code = tbl_receive.receive_tags_code
		left join
		tbl_tags_running
		on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
		left join 
		tbl_b2c_detail
		on tbl_dn_head.dn_h_dtn_code = tbl_b2c_detail.b2c_dtn
		where
		dn_h_status = 'Delivery Transfer Note'
		and
		ps_t_pj_name = 'B2C'
		group by
			[dn_h_dtn_code]
		   ,[dn_h_cus_code]
		   ,[dn_h_cus_name]
		   ,[dn_h_driver_code]
		   ,[dn_h_delivery_date]
		   ,[dn_h_status]
		   ,[ps_t_pj_name]
		   ,[b2c_track_num]
		   ,[b2c_repn_order_ref]
			order by 
			dn_h_dtn_code desc";

$objQuery_DTNSheet = sqlsrv_query($db_con, $strSql_DTNSheet);

$row_id_DTNSheet = 0;
while($objResult_DTNSheet = sqlsrv_fetch_array($objQuery_DTNSheet, SQLSRV_FETCH_ASSOC))
{
	$row_id_DTNSheet++;

	$dn_h_dtn_code = $objResult_DTNSheet['dn_h_dtn_code'];
	$ps_t_pj_name = $objResult_DTNSheet['ps_t_pj_name'];
	$b2c_track_num = $objResult_DTNSheet['b2c_track_num'];
	$b2c_repn_order_ref = $objResult_DTNSheet['b2c_repn_order_ref'];

    $token_ = '';
	
    //login for send API 
    $url_login = "https://scgyamatodev.flare.works/api/authentication";

    $curl_login = curl_init($url_login);
    curl_setopt($curl_login, CURLOPT_URL, $url_login);
    curl_setopt($curl_login, CURLOPT_POST, true);
    curl_setopt($curl_login, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
       "Content-Type: application/x-www-form-urlencoded",
    );
    curl_setopt($curl_login, CURLOPT_HTTPHEADER, $headers);

    $data_login = "username=info%40GLONGDUANGJAI.com&password=Initial%401234";

    curl_setopt($curl_login, CURLOPT_POSTFIELDS, $data_login);

    //for debug only!
    curl_setopt($curl_login, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl_login, CURLOPT_SSL_VERIFYPEER, false);

    $resp_login = curl_exec($curl_login);
    $data_arr_login = json_decode($resp_login, true);
    curl_close($curl_login);

    $token_  = $data_arr_login['token'];

    //check Login 
    if($data_arr_login['status'] == true){
         
    //Status for Update
    $url_status = "https://scgyamatodev.flare.works/api/getHistoricalStatusByorder";

    $curl_status = curl_init($url_status);
    curl_setopt($curl_status, CURLOPT_URL, $url_status);
    curl_setopt($curl_status, CURLOPT_POST, true);
    curl_setopt($curl_status, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
       "Content-Type: application/x-www-form-urlencoded",
    );
    curl_setopt($curl_status, CURLOPT_HTTPHEADER, $headers);

    $data_status = "token=$token_&order_id=$dn_h_dtn_code";

    curl_setopt($curl_status, CURLOPT_POSTFIELDS, $data_status);

    //for debug only!
    curl_setopt($curl_status, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl_status, CURLOPT_SSL_VERIFYPEER, false);

    $resp_status = curl_exec($curl_status);
    $data_arr_status = json_decode($resp_status, true);
    curl_close($curl_status);

	$stats_b2c = $data_arr_status['parcel_data'][$b2c_track_num]['tracking_data'][0]['status_code'];
		
	$sqlUpdateStatusB2c = " UPDATE tbl_b2c_detail SET b2c_status = '$stats_b2c' WHERE b2c_dtn = '$dn_h_dtn_code'";
	$result_sqlUpdateStatusB2c = sqlsrv_query($db_con, $sqlUpdateStatusB2c);

    if($stats_b2c == '90'){

        //update tbl_picking_head - ps_h_status = Delivery Transfer Note
        $sqlUpdatePicking_head = " UPDATE tbl_picking_head SET ps_h_status = 'Confirmed' WHERE ps_h_picking_code = '$dn_h_dtn_code' and ps_h_status = 'Delivery Transfer Note' ";
        $result_sqlUpdatePicking_head = sqlsrv_query($db_con, $sqlUpdatePicking_head);
        
        //update tbl_picking_tail - ps_t_status = Delivery Transfer Note
        $sqlUpdatePicking_tail = " UPDATE tbl_picking_tail SET ps_t_status = 'Confirmed' WHERE ps_t_picking_code = '$dn_h_dtn_code' and ps_t_status = 'Delivery Transfer Note' ";
        $result_sqlUpdatePicking_tail = sqlsrv_query($db_con, $sqlUpdatePicking_tail);
        
    }	

	 //Status for Update
	 $url_b2c_web = "https://glong-duang-jai.com/wp-json/wc-ast/v3/orders/$b2c_repn_order_ref/shipment-trackings";
    
	 $curl_web = curl_init($url_b2c_web);
	 curl_setopt($curl_web, CURLOPT_URL, $url_b2c_web);
	 curl_setopt($curl_web, CURLOPT_POST, true);
	 curl_setopt($curl_web, CURLOPT_RETURNTRANSFER, true);

	 $user_web = 'ck_1bd61eba6c4bdb507d5c81f901f4a9a4d3b9447a';
	 $pass_web = 'cs_95b6835ebaf976a8444bb4942721946a6619f12b';
 
	 $headers = array(
		"Content-Type: application/x-www-form-urlencoded",
		'Authorization: Basic '. base64_encode("$user_web:$pass_web")
	 );

	 curl_setopt($curl_web, CURLOPT_HTTPHEADER, $headers);
 
	 $data_web = "tracking_number=$b2c_track_num&tracking_provider=SCG Express&status_shipped=1";
 
	 curl_setopt($curl_web, CURLOPT_POSTFIELDS, $data_web);
 
	 //for debug only!
	 curl_setopt($curl_web, CURLOPT_SSL_VERIFYHOST, false);
	 curl_setopt($curl_web, CURLOPT_SSL_VERIFYPEER, false);
 
	 $resp_web = curl_exec($curl_web);
	 curl_close($curl_web);

         
    }
    else
    {
        echo 'Login False';
        
    }

}

	sqlsrv_close($db_con);
}


?>