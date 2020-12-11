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
$t_acc = isset($_POST['t_acc']) ? $_POST['t_acc'] : '';
$cmd_ok = isset($_POST['cmd_ok']) ? $_POST['cmd_ok'] : '';

if($cmd_ok == "unlock")
{
	//delete
	$sqldelete = " delete from tbl_user_failed_login where failed_login_ip_address = '$t_acc' ";
	$resultdelete = sqlsrv_query($db_con, $sqldelete);

	if($resultdelete)
	{
		echo "[D003] --- Unlock account ($t_acc) success";
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