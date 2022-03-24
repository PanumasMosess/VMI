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
$iden_hdn_pj_pre_tags_id = isset($_POST['iden_hdn_pj_pre_tags_id']) ? $_POST['iden_hdn_pj_pre_tags_id'] : '';

//delete all
$sqlDelete = " DELETE FROM tbl_internal_move_project WHERE tags_move_pj_tags_code = '$iden_hdn_pj_pre_tags_id' and tags_move_pj_by = '$t_cur_user_code_VMI_GDJ' ";
$result_sqlDelete = sqlsrv_query($db_con, $sqlDelete);

sqlsrv_close($db_con);
?>