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
$iden_repn_id = isset($_POST['iden_repn_id']) ? $_POST['iden_repn_id'] : '';

//update status
$sqlUpdate = " UPDATE tbl_replenishment
   SET [repn_conf_status] = NULL
      ,[repn_conf_remark] = NULL
      ,[repn_conf_by] = NULL
      ,[repn_conf_date] = NULL
      ,[repn_conf_time] = NULL
      ,[repn_conf_datetime] = NULL
 WHERE repn_id = '$iden_repn_id'
 ";
$result_sqlUpdate = sqlsrv_query($db_con, $sqlUpdate);

if($result_sqlUpdate)
{
	//tbl_receive return reserve by replenish id
	$sqlUpdate_return_reserve_repn_id = " UPDATE tbl_receive
	   SET receive_repn_id = NULL
	 WHERE receive_repn_id = '$iden_repn_id'
	 ";
	$result_sqlUpdate_return_reserve_repn_id = sqlsrv_query($db_con, $sqlUpdate_return_reserve_repn_id);
}

sqlsrv_close($db_con);
?>