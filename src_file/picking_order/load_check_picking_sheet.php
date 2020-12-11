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
$t_txt_scn_picking_id = isset($_POST['t_txt_scn_picking_id']) ? $_POST['t_txt_scn_picking_id'] : '';

//check picking sheet
$strSql_chk_picking_sheet = " SELECT * FROM tbl_picking_head where ps_h_picking_code = '$t_txt_scn_picking_id' and ps_h_status = 'Picking' and ps_h_qc is NULL ";
$objQuery_chk_picking_sheet = sqlsrv_query($db_con, $strSql_chk_picking_sheet, $params, $options);
$num_row_chk_picking_sheet = sqlsrv_num_rows($objQuery_chk_picking_sheet);
//$objResult_chk_picking_sheet = sqlsrv_fetch_array($objQuery_chk_picking_sheet, SQLSRV_FETCH_ASSOC);

//null
if($num_row_chk_picking_sheet == 0)
{	
	echo "NG";
}
else
{
	
	echo "OK";
}

sqlsrv_close($db_con);
?>