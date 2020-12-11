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
$data_tag_id = isset($_POST['data_tag_id_']) ? $_POST['data_tag_id_'] : '';


//delete all
$sqlDelete = " DELETE FROM tbl_stock_checking WHERE stock_tags_code = '$data_tag_id' ";
$result_sqlDelete = sqlsrv_query($db_con, $sqlDelete);

sqlsrv_close($db_con);
?>