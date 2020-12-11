<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$hdn_ip_client = isset($_POST['hdn_ip_client']) ? $_POST['hdn_ip_client'] : '';

$hdn_ip_client = ltrim(rtrim($hdn_ip_client));

//not null, &nbsp;
if($hdn_ip_client != "" || $hdn_ip_client != "&nbsp;" || $hdn_ip_client != null)
{
	//check duplicate
	$strSQL_chk = "select * from tbl_counter where counter_ip = '$hdn_ip_client' and counter_date = '$buffer_date' ";
	$objQuery_chk = sqlsrv_query($db_con, $strSQL_chk);
	$objResult_chk = sqlsrv_fetch_array($objQuery_chk, SQLSRV_FETCH_ASSOC);
	if(!$objResult_chk)
	{
		if($hdn_ip_client != "" || $hdn_ip_client != "&nbsp;" || $hdn_ip_client != null)
		{
			//Insert tbl_counter
			$strSQL_insert = sqlsrv_query($db_con, " insert into tbl_counter (counter_ip,counter_date) values ('$hdn_ip_client','$buffer_date') ");
		}
	}
}

sqlsrv_close($db_con);
?>