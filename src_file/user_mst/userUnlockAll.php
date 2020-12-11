<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

/**********************************************************************************/
/*var *****************************************************************************/
$cmd_ok = isset($_POST['cmd_ok']) ? $_POST['cmd_ok'] : '';

if($cmd_ok == "unlockAll")
{
	//truncate
	$sqltruncate = " truncate table tbl_user_failed_login ";
	$resulttruncate = sqlsrv_query($db_con, $sqltruncate);

	if($resulttruncate)
	{
		echo "[D003] --- Unlock all account success";
	} 
	else 
	{
		echo "[D002] ---  Cannot operate !!!";
	}
}
else
{
	echo "[D002] ---  Cannot operate !!!";
}

sqlsrv_close($db_con);
?>