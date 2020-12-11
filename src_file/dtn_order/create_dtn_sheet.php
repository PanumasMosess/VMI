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
$iden_hdn_ps_h_picking_code = isset($_POST['iden_hdn_ps_h_picking_code']) ? $_POST['iden_hdn_ps_h_picking_code'] : '';
$iden_txt_curr_dtn_no = isset($_POST['iden_txt_curr_dtn_no']) ? $_POST['iden_txt_curr_dtn_no'] : '';
$iden_txt_dtn_delivery_date = isset($_POST['iden_txt_dtn_delivery_date']) ? $_POST['iden_txt_dtn_delivery_date'] : '';
$iden_txt_dtn_delivery_time = isset($_POST['iden_txt_dtn_delivery_time']) ? $_POST['iden_txt_dtn_delivery_time'] : '';
$iden_txt_dtn_curr_scan_driver_iden_card = isset($_POST['iden_txt_dtn_curr_scan_driver_iden_card']) ? $_POST['iden_txt_dtn_curr_scan_driver_iden_card'] : '';
$tmp_sel = isset($_POST['tmp_sel']) ? $_POST['tmp_sel'] : '';

//get details for tbl_picking_head
$strSql_data_picking_h = " 
	SELECT 
		[ps_h_picking_code]
		,[ps_h_cus_code]
		,[ps_h_cus_name]
	FROM 
	tbl_picking_head 
	where ps_h_picking_code = '$iden_hdn_ps_h_picking_code' 
";
$objQuery_data_picking_h = sqlsrv_query($db_con, $strSql_data_picking_h, $params, $options);
$num_row_data_picking_h = sqlsrv_num_rows($objQuery_data_picking_h);

while($objResult_data_picking_h = sqlsrv_fetch_array($objQuery_data_picking_h, SQLSRV_FETCH_ASSOC))
{
	$ps_h_cus_code = $objResult_data_picking_h['ps_h_cus_code'];
	$ps_h_cus_name = $objResult_data_picking_h['ps_h_cus_name'];
}

//check first row
if($tmp_sel == 1)
{
	///////////////////insert tbl_dn_head///////////////////
	$strSql_insert_dtn_header = " 
		INSERT INTO [dbo].[tbl_dn_head]
			   (
				[dn_h_dtn_code]
				,[dn_h_cus_code]
				,[dn_h_cus_name]
				,[dn_h_cus_address]
				,[dn_h_driver_code]
				,[dn_h_delivery_date]
				,[dn_h_delivery_time]
				,[dn_h_status]
				,[dn_h_issue_by]
				,[dn_h_issue_date]
				,[dn_h_issue_time]
				,[dn_h_issue_datetime]
			   )
		 VALUES
			   (
				'$iden_txt_curr_dtn_no'
				,'$ps_h_cus_code'
				,'$ps_h_cus_name'
				,'336/11 Moo7 Bowin, Sriracha, Chonburi 20230'
				,'$iden_txt_dtn_curr_scan_driver_iden_card'
				,'$iden_txt_dtn_delivery_date'
				,'$iden_txt_dtn_delivery_time'
				,'Delivery Transfer Note'
			   ,'$t_cur_user_code_VMI_GDJ'
			   ,'$buffer_date'
			   ,'$buffer_time'
			   ,'$buffer_datetime'
			   )
	";
	$objQuery_insert_dtn_header = sqlsrv_query($db_con, $strSql_insert_dtn_header);
}
	
///////////////////insert tbl_dn_tail///////////////////
$strSql_insert_dtn_tail = " INSERT INTO tbl_dn_tail
	   (
		[dn_t_dtn_code]
		,[dn_t_picking_code]
		,[dn_t_status]
		,[dn_t_issue_by]
		,[dn_t_issue_date]
		,[dn_t_issue_time]
		,[dn_t_issue_datetime]
	   )
 VALUES
	   (
		'$iden_txt_curr_dtn_no'
		,'$iden_hdn_ps_h_picking_code'
		,'Delivery Transfer Note'
		,'$t_cur_user_code_VMI_GDJ'
		,'$buffer_date'
		,'$buffer_time'
		,'$buffer_datetime'
	   )	
";

$objQuery_insert_dtn_tail = sqlsrv_query($db_con, $strSql_insert_dtn_tail);

if($objQuery_insert_dtn_tail)
{
	//read tbl_picking_tail for update tags ID to Delivery Transfer Note
	$strSql_update_tags_to_dtn = " 
		SELECT 
			[ps_t_picking_code]
			,[ps_t_tags_code]
		FROM [tbl_picking_tail]
		where
		[ps_t_picking_code] = '$iden_hdn_ps_h_picking_code' 
	";
	$objQuery_update_tags_to_dtn = sqlsrv_query($db_con, $strSql_update_tags_to_dtn, $params, $options);
	$num_row_update_tags_to_dtn = sqlsrv_num_rows($objQuery_update_tags_to_dtn);

	while($objResult_update_tags_to_dtn = sqlsrv_fetch_array($objQuery_update_tags_to_dtn, SQLSRV_FETCH_ASSOC))
	{
		$ps_t_picking_code = $objResult_update_tags_to_dtn['ps_t_picking_code'];
		$ps_t_tags_code = $objResult_update_tags_to_dtn['ps_t_tags_code'];
		
		//update tbl_receive - receive_status = Delivery Transfer Note
		$sqlUpdateDTN = " UPDATE tbl_receive SET receive_status = 'Delivery Transfer Note' WHERE receive_tags_code = '$ps_t_tags_code' ";
		$result_sqlUpdateDTN = sqlsrv_query($db_con, $sqlUpdateDTN);
	}
}

//update tbl_picking_head - ps_h_status = Delivery Transfer Note
$sqlUpdatePicking_head = " UPDATE tbl_picking_head SET ps_h_status = 'Delivery Transfer Note' WHERE ps_h_picking_code = '$iden_hdn_ps_h_picking_code' and ps_h_status = 'Picking' ";
$result_sqlUpdatePicking_head = sqlsrv_query($db_con, $sqlUpdatePicking_head);

//update tbl_picking_tail - ps_t_status = Delivery Transfer Note
$sqlUpdatePicking_tail = " UPDATE tbl_picking_tail SET ps_t_status = 'Delivery Transfer Note' WHERE ps_t_picking_code = '$iden_hdn_ps_h_picking_code' and ps_t_status = 'Picking' ";
$result_sqlUpdatePicking_tail = sqlsrv_query($db_con, $sqlUpdatePicking_tail);
	
//update tbl_dn_running - dn_status = Matched
$sqlUpdateDTNRunning = " UPDATE tbl_dn_running SET dn_status = 'Matched' WHERE dn_dtn_code = '$iden_txt_curr_dtn_no' ";
$result_sqlUpdateDTNRunning = sqlsrv_query($db_con, $sqlUpdateDTNRunning);

sqlsrv_close($db_con);
?>