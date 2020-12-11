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
$iden_hdn_pre_picking_tags_id = isset($_POST['iden_hdn_pre_picking_tags_id']) ? $_POST['iden_hdn_pre_picking_tags_id'] : '';

//delete all
$sqlDelete = " DELETE FROM tbl_picking_tail_pre_qc WHERE pick_pre_qc_tags_code = '$iden_hdn_pre_picking_tags_id' and pick_pre_qc_by = '$t_cur_user_code_VMI_GDJ' ";
$result_sqlDelete = sqlsrv_query($db_con, $sqlDelete);

sqlsrv_close($db_con);
?>