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
$iden_hdn_pre_picking_iden_rows = isset($_POST['iden_hdn_pre_picking_iden_rows']) ? $_POST['iden_hdn_pre_picking_iden_rows'] : '';
$hdn_row_PickingQCSheetDetails = isset($_POST['hdn_row_PickingQCSheetDetails']) ? $_POST['hdn_row_PickingQCSheetDetails'] : '';

//get tbl_picking_tail_pre_qc
$strSql_get_pre_qc = " 
SELECT 
	[pick_pre_qc_cover_code]
	,[pick_pre_qc_picking_code]
	,[pick_pre_qc_tags_code]
	,[pick_pre_qc_fg_code_gdj]
	,[pick_pre_qc_part_customer] 
  FROM tbl_picking_tail_pre_qc 
  WHERE 
  pick_pre_qc_tags_code = '$iden_hdn_pre_picking_tags_id' 
  and 
  pick_pre_qc_by = '$t_cur_user_code_VMI_GDJ' 
";
$objQuery_get_pre_qc = sqlsrv_query($db_con, $strSql_get_pre_qc, $params, $options);
$num_row_get_pre_qc = sqlsrv_num_rows($objQuery_get_pre_qc);
$objResult_get_pre_qc = sqlsrv_fetch_array($objQuery_get_pre_qc, SQLSRV_FETCH_ASSOC);

//buffer data
$pick_pre_qc_cover_code = $objResult_get_pre_qc['pick_pre_qc_cover_code'];
$pick_pre_qc_picking_code = $objResult_get_pre_qc['pick_pre_qc_picking_code'];	
$pick_pre_qc_tags_code = $objResult_get_pre_qc['pick_pre_qc_tags_code'];
$pick_pre_qc_fg_code_gdj = $objResult_get_pre_qc['pick_pre_qc_fg_code_gdj'];
$pick_pre_qc_part_customer = $objResult_get_pre_qc['pick_pre_qc_part_customer'];

//insert tbl_picking_tail_qc
$sqlInsQc = " 
INSERT INTO [dbo].[tbl_picking_tail_qc]
   (
	[pick_qc_cover_code]
	,[pick_qc_picking_code]
	,[pick_qc_tags_code]
	,[pick_qc_fg_code_gdj]
	,[pick_qc_part_customer]
	,[pick_qc_by]
	,[pick_qc_date]
	,[pick_qc_time]
	,[pick_qc_datetime]
   )
VALUES
   (
	'$pick_pre_qc_cover_code'
	,'$pick_pre_qc_picking_code'
	,'$pick_pre_qc_tags_code'
	,'$pick_pre_qc_fg_code_gdj'
	,'$pick_pre_qc_part_customer'
	,'$t_cur_user_code_VMI_GDJ'
	,'$buffer_date'
	,'$buffer_time'
	,'$buffer_datetime'
   )
";
$result_sqlInsQc = sqlsrv_query($db_con, $sqlInsQc);

if($result_sqlInsQc)
{
	//delete tbl_picking_tail_pre_qc
	$sqlDelete = " DELETE FROM tbl_picking_tail_pre_qc WHERE pick_pre_qc_tags_code = '$iden_hdn_pre_picking_tags_id' and pick_pre_qc_by = '$t_cur_user_code_VMI_GDJ' ";
	$result_sqlDelete = sqlsrv_query($db_con, $sqlDelete);
	
	//last rows
	if($iden_hdn_pre_picking_iden_rows == $hdn_row_PickingQCSheetDetails)
	{
		//update tbl_picking_head set status ps_h_qc = Completed
		$sqlUpdatePickingHead = " UPDATE tbl_picking_head SET ps_h_qc = 'Completed' WHERE ps_h_picking_code = '$pick_pre_qc_picking_code' ";
		$result_sqlUpdatePickingHead = sqlsrv_query($db_con, $sqlUpdatePickingHead);
	}
}

sqlsrv_close($db_con);
?>