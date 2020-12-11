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
$iden_hdn_pre_receive_id = isset($_POST['iden_hdn_pre_receive_id']) ? $_POST['iden_hdn_pre_receive_id'] : '';

//delete all follow check box is checked
$sqlDelete = " DELETE FROM tbl_pre_receive WHERE pre_receive_id = '$iden_hdn_pre_receive_id' ";
$result_sqlDelete = sqlsrv_query($db_con, $sqlDelete);

sqlsrv_close($db_con);
?>