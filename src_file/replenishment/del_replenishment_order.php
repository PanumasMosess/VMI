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
$iden_t_repn_id = isset($_POST['iden_t_repn_id']) ? $_POST['iden_t_repn_id'] : '';
$iden_t_order_type = isset($_POST['iden_t_order_type']) ? $_POST['iden_t_order_type'] : '';
$iden_t_ref_no = isset($_POST['iden_t_ref_no']) ? $_POST['iden_t_ref_no'] : '';

//delete all
$sqlDelete = " DELETE FROM tbl_replenishment WHERE repn_id = '$iden_t_repn_id' ";
$result_sqlDelete = sqlsrv_query($db_con, $sqlDelete);

sqlsrv_close($db_con);
?>