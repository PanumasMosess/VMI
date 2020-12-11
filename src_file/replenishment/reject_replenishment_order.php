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
$iden_repn_reject_remark = isset($_POST['iden_repn_reject_remark']) ? $_POST['iden_repn_reject_remark'] : '';
	
//update status
$sqlUpdate = " UPDATE tbl_replenishment
   SET [repn_conf_status] = 'Rejected'
	  ,[repn_conf_remark] = '".ltrim(rtrim($iden_repn_reject_remark))."'
	  ,[repn_conf_by] = '$t_cur_user_code_VMI_GDJ'
	  ,[repn_conf_date] = '$buffer_date'
	  ,[repn_conf_time] = '$buffer_time'
	  ,[repn_conf_datetime] = '$buffer_datetime'
 WHERE repn_id = '$iden_repn_id'
 ";
$result_sqlUpdate = sqlsrv_query($db_con, $sqlUpdate);

sqlsrv_close($db_con);
?>