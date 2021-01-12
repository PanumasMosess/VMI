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
$t_txt_dtn_scan_driver_iden_card = isset($_POST['t_txt_dtn_scan_driver_iden_card']) ? $_POST['t_txt_dtn_scan_driver_iden_card'] : '';

//check driver
$strSql_chk_driver = " SELECT * FROM tbl_driver_mst where driver_code = '$t_txt_dtn_scan_driver_iden_card' ";
$objQuery_chk_driver = sqlsrv_query($db_con, $strSql_chk_driver, $params, $options);
$num_row_chk_driver = sqlsrv_num_rows($objQuery_chk_driver);
//$objResult_chk_driver = sqlsrv_fetch_array($objQuery_chk_driver, SQLSRV_FETCH_ASSOC);

//null
if($num_row_chk_driver == 0)
{	
	echo "NG";
}
else
{
	
	echo "OK";
}

sqlsrv_close($db_con);
?>