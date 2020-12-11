<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$t_cur_ses_company_VMI_GDJ = isset($_SESSION['ses_company_VMI_GDJ']) ? $_SESSION['ses_company_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*force logout when swicth path ***************************************************/
$strSQL_chk_path = "select * from tbl_company_mst where com_code = '".$t_cur_ses_company_VMI_GDJ."' ";
$objQuery_chk_path = sqlsrv_query($db_con, $strSQL_chk_path);
$objResult_chk_path = sqlsrv_fetch_array($objQuery_chk_path, SQLSRV_FETCH_ASSOC);

//force log out when switch path
if(!$objResult_chk_path)
{
	print "<meta http-equiv=refresh content=0;URL=logout>";
	exit();
}

/**********************************************************************************/
/*force logout to user logging ****************************************************/
$strSQL_user_logging_realtime = "select * from tbl_user 
where 
user_code = '".$t_cur_user_code_VMI_GDJ."' 
and 
user_enable = '1' 
and
user_type = '$t_cur_user_type_VMI_GDJ' ";
$objQuery_user_logging_realtime = sqlsrv_query($db_con, $strSQL_user_logging_realtime);
$objResult_user_logging_realtime = sqlsrv_fetch_array($objQuery_user_logging_realtime, SQLSRV_FETCH_ASSOC);

//force logout incase enable is 0
if(!$objResult_user_logging_realtime)
{
	print "<meta http-equiv=refresh content=0;URL=logout>";
	exit();
}

/**********************************************************************************/
/*get user online *****************************************************************/
$sql_get_user_online = "select * from tbl_user_online 
where 
UserCode = '".$t_cur_user_code_VMI_GDJ."' ";
$query_get_user_online = sqlsrv_query($db_con, $sql_get_user_online);
$objResult_user_online = sqlsrv_fetch_array($query_get_user_online, SQLSRV_FETCH_ASSOC);

/**********************************************************************************/
/*check online ********************************************************************/
if($objResult_user_online) //update
{
	$sql_user_online_update = "UPDATE tbl_user_online set UserCode = '".$t_cur_user_code_VMI_GDJ."',UserSection = '".$t_cur_user_type_VMI_GDJ."', OnlineLastTime = '$buffer_datetime' where UserCode = '".$t_cur_user_code_VMI_GDJ."' ";
	$query_user_online_update = sqlsrv_query($db_con, $sql_user_online_update);
}
else //insert
{
	$sql_user_online_insert = "INSERT INTO tbl_user_online (UserCode,UserSection,OnlineLastTime) VALUES ('".$t_cur_user_code_VMI_GDJ."','".$t_cur_user_type_VMI_GDJ."','$buffer_datetime')";
	$query_user_online_insert = sqlsrv_query($db_con, $sql_user_online_insert);
}

/**********************************************************************************/
/*reject user not online **********************************************************/
$sql_user_online_delete = "DELETE FROM tbl_user_online WHERE OnlineLastTime <= dateadd(minute, -1, getdate()) ";
$query_user_online_delete = sqlsrv_query($db_con, $sql_user_online_delete);

/**********************************************************************************/
/*alert network *******************************************************************/
function ping($host, $port, $timeout)
{ 
  $tB = microtime(true);
  $fP = fSockOpen($host, $port, $errno, $errstr, $timeout);
  if (!$fP) { return "Server is down !!!"; }
  $tA = microtime(true);
  return round((($tA - $tB) * 1000), 0)." ";
}

//Echoing it will display the ping if the host is up, if not it'll say "down".
//echo ping("www.google.com", 80, 10); 
echo ping("$CFG->dbhostPing", 80, 10);

sqlsrv_close($db_con);
?>