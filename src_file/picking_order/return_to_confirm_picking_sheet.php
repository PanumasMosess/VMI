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
$t_ps_h_picking_code = isset($_POST['t_ps_h_picking_code']) ? $_POST['t_ps_h_picking_code'] : '';
$t_ps_h_cus_code = isset($_POST['t_ps_h_cus_code']) ? $_POST['t_ps_h_cus_code'] : '';
$t_ps_h_cus_name = isset($_POST['t_ps_h_cus_name']) ? $_POST['t_ps_h_cus_name'] : '';
$t_ps_t_pj_name = isset($_POST['t_ps_t_pj_name']) ? $_POST['t_ps_t_pj_name'] : '';
$t_ps_h_status = isset($_POST['t_ps_h_status']) ? $_POST['t_ps_h_status'] : '';
$t_ps_h_issue_date = isset($_POST['t_ps_h_issue_date']) ? $_POST['t_ps_h_issue_date'] : '';



////Select for update tbl_receive and tbl_replenishment
$strSql_PickingSheetDetails = " 
	SELECT 
	  [ps_t_picking_code]
      ,[ps_t_pallet_code]
      ,[ps_t_tags_code]
	  ,[ps_t_fg_code_gdj]
      ,[ps_t_location]
      ,[ps_t_tags_packing_std]
      ,[ps_t_cus_name]
      ,[ps_t_pj_name]
      ,[ps_t_replenish_unit_type]
      ,[ps_t_terminal_name]
      ,[ps_t_order_type]
      ,[ps_t_status]
      ,[ps_t_issue_date]
      ,[receive_repn_id]
  FROM [tbl_picking_tail]
  LEFT JOIN tbl_receive
  ON tbl_picking_tail.ps_t_tags_code = tbl_receive.receive_tags_code
  where
  [ps_t_picking_code] = '$t_ps_h_picking_code'
";

$objQuery_PickingSheetDetails = sqlsrv_query($db_con, $strSql_PickingSheetDetails, $params, $options);
$num_row_PickingSheetDetails = sqlsrv_num_rows($objQuery_PickingSheetDetails);

$row_id_PickingSheetDetails = 0;
while($objResult_PickingSheetDetails = sqlsrv_fetch_array($objQuery_PickingSheetDetails, SQLSRV_FETCH_ASSOC))
{
	$row_id_PickingSheetDetails++;
	
	$ps_t_picking_code = $objResult_PickingSheetDetails['ps_t_picking_code'];
	$ps_t_pallet_code = $objResult_PickingSheetDetails['ps_t_pallet_code'];
	$ps_t_tags_code = $objResult_PickingSheetDetails['ps_t_tags_code'];
	$ps_t_fg_code_gdj = $objResult_PickingSheetDetails['ps_t_fg_code_gdj'];
	$ps_t_location = $objResult_PickingSheetDetails['ps_t_location'];
	$ps_t_tags_packing_std = $objResult_PickingSheetDetails['ps_t_tags_packing_std'];
    $receive_repn_id = $objResult_PickingSheetDetails['receive_repn_id'];
    

    if($objQuery_PickingSheetDetails)
	{
		//update tbl_receive - receive_status = Picking
		$sqlUpdatePicking = " UPDATE tbl_receive SET receive_status = 'Received' WHERE receive_tags_code = '$ps_t_tags_code' and receive_pallet_code = '$ps_t_pallet_code' ";
		$result_sqlUpdatePicking = sqlsrv_query($db_con, $sqlUpdatePicking);

        //update tbl_replenishment - repn_conf_status = Picking
        $sqlUpdateReplenishment = " UPDATE tbl_replenishment SET repn_conf_status = 'Confirmed' WHERE repn_id = '$receive_repn_id' and repn_conf_status = 'Picking' ";
        $result_sqlUpdateReplenishment = sqlsrv_query($db_con, $sqlUpdateReplenishment);
	}

}

//update tbl_picking_running - picking_status = Matched
$sqlUpdatePickingRunning = " UPDATE tbl_picking_running SET picking_status = 'Return' WHERE picking_code = '$t_ps_h_picking_code' ";
$result_sqlUpdatePickingRunning = sqlsrv_query($db_con, $sqlUpdatePickingRunning);
	
//delete from tbl_picking_tail - ps_t_picking_code = $t_ps_h_picking_code
$sqlDeletePickingTail = " DELETE FROM tbl_picking_tail  WHERE ps_t_picking_code = '$t_ps_h_picking_code' and ps_t_status = 'Picking' ";
$result_sqlDeletePickingTail = sqlsrv_query($db_con, $sqlDeletePickingTail);

//delete from tbl_picking_head - ps_h_picking_code = $t_ps_h_picking_code
$sqlDeletePickingHead = " DELETE FROM tbl_picking_head  WHERE ps_h_picking_code = '$t_ps_h_picking_code' and ps_h_status = 'Picking' ";
$result_sqlDeletePickingHead = sqlsrv_query($db_con, $sqlDeletePickingHead);

sqlsrv_close($db_con);

?>
