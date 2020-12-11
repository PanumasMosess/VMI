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
$iden_hdn_repn_id = isset($_POST['iden_hdn_repn_id']) ? $_POST['iden_hdn_repn_id'] : '';
$iden_txt_curr_picking_no = isset($_POST['iden_txt_curr_picking_no']) ? $_POST['iden_txt_curr_picking_no'] : '';
$iden_hdn_repn_fg_code_set_abt = isset($_POST['iden_hdn_repn_fg_code_set_abt']) ? $_POST['iden_hdn_repn_fg_code_set_abt'] : '';
$iden_hdn_repn_sku_code_abt = isset($_POST['iden_hdn_repn_sku_code_abt']) ? $_POST['iden_hdn_repn_sku_code_abt'] : '';
$iden_hdn_bom_fg_code_gdj = isset($_POST['iden_hdn_bom_fg_code_gdj']) ? $_POST['iden_hdn_bom_fg_code_gdj'] : '';
$iden_hdn_bom_cus_code = isset($_POST['iden_hdn_bom_cus_code']) ? $_POST['iden_hdn_bom_cus_code'] : '';
$iden_hdn_bom_cus_name = isset($_POST['iden_hdn_bom_cus_name']) ? $_POST['iden_hdn_bom_cus_name'] : '';
$iden_hdn_bom_pj_name = isset($_POST['iden_hdn_bom_pj_name']) ? $_POST['iden_hdn_bom_pj_name'] : '';
$iden_hdn_bom_ship_type = isset($_POST['iden_hdn_bom_ship_type']) ? $_POST['iden_hdn_bom_ship_type'] : '';
$iden_hdn_bom_part_customer = isset($_POST['iden_hdn_bom_part_customer']) ? $_POST['iden_hdn_bom_part_customer'] : '';
$iden_hdn_str_conv_pack = isset($_POST['iden_hdn_str_conv_pack']) ? $_POST['iden_hdn_str_conv_pack'] : '';
$tmp_sel = isset($_POST['tmp_sel']) ? $_POST['tmp_sel'] : '';

//get details for tbl_picking_tail
$strSql_data_picking_tail = " 
	SELECT 
		repn_running_code
		,repn_qty
		,repn_unit_type
		,repn_terminal_name
		,repn_order_type
	FROM 
	tbl_replenishment 
	where repn_id = '$iden_hdn_repn_id' 
";
$objQuery_data_picking_tail = sqlsrv_query($db_con, $strSql_data_picking_tail, $params, $options);
$num_row_data_picking_tail = sqlsrv_num_rows($objQuery_data_picking_tail);

while($objResult_data_picking_tail = sqlsrv_fetch_array($objQuery_data_picking_tail, SQLSRV_FETCH_ASSOC))
{
	$repn_running_code = $objResult_data_picking_tail['repn_running_code'];
	$repn_qty = $objResult_data_picking_tail['repn_qty'];
	$repn_unit_type = $objResult_data_picking_tail['repn_unit_type'];
	$repn_terminal_name = $objResult_data_picking_tail['repn_terminal_name'];
	$repn_order_type = $objResult_data_picking_tail['repn_order_type'];
}

//check first row
if($tmp_sel == 1)
{
	///////////////////insert picking header///////////////////
	$strSql_insert_picking_header = " 
		INSERT INTO [dbo].[tbl_picking_head]
			   (
			   [ps_h_picking_code]
			   ,[ps_h_cus_code]
			   ,[ps_h_cus_name]
			   ,[ps_h_status]
			   ,[ps_h_issue_by]
			   ,[ps_h_issue_date]
			   ,[ps_h_issue_time]
			   ,[ps_h_issue_datetime]
			   )
		 VALUES
			   (
			   '$iden_txt_curr_picking_no'
			   ,'$iden_hdn_bom_cus_code'
			   ,'$iden_hdn_bom_cus_name'
			   ,'Picking'
			   ,'$t_cur_user_code_VMI_GDJ'
			   ,'$buffer_date'
			   ,'$buffer_time'
			   ,'$buffer_datetime'
			   )
	";
	$objQuery_insert_picking_header = sqlsrv_query($db_con, $strSql_insert_picking_header);
}
	
///////////////////insert picking tail///////////////////
//read pallet on inventory
$strSql_picking_order_details = " 
select top $iden_hdn_str_conv_pack 
	  [receive_tags_code]
      ,[receive_pallet_code]
      ,[receive_location]
      ,[tags_fg_code_gdj]
      ,[tags_fg_code_gdj_desc]
	  ,[repn_fg_code_set_abt]
      ,[repn_sku_code_abt]
      ,[repn_pj_name]
	  ,[tags_packing_std]
	  ,[receive_date]
	  from tbl_replenishment 
left join tbl_bom_mst 
on tbl_replenishment.repn_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
and tbl_replenishment.repn_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
and tbl_replenishment.repn_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
and tbl_replenishment.repn_pj_name = tbl_bom_mst.bom_pj_name
and tbl_replenishment.repn_ship_type = tbl_bom_mst.bom_ship_type
and tbl_replenishment.repn_part_customer = tbl_bom_mst.bom_part_customer
left join tbl_tags_running
on tbl_bom_mst.bom_fg_code_gdj = tbl_tags_running.tags_fg_code_gdj
left join tbl_receive
on tbl_tags_running.tags_code = tbl_receive.receive_tags_code
where
repn_conf_status = 'Confirmed'
and
receive_status = 'Received'
and
repn_fg_code_set_abt = '$iden_hdn_repn_fg_code_set_abt'
and
repn_sku_code_abt = '$iden_hdn_repn_sku_code_abt'
and
bom_fg_code_gdj = '$iden_hdn_bom_fg_code_gdj'
and
bom_pj_name = '$iden_hdn_bom_pj_name'
and
bom_ship_type = '$iden_hdn_bom_ship_type'
and
bom_part_customer = '$iden_hdn_bom_part_customer'
group by
 [receive_tags_code]
      ,[receive_pallet_code]
      ,[receive_location]
      ,[tags_fg_code_gdj]
      ,[tags_fg_code_gdj_desc]
	  ,[repn_fg_code_set_abt]
      ,[repn_sku_code_abt]
      ,[repn_pj_name]
	  ,[tags_packing_std]
	  ,[receive_date]
order by 
receive_date asc
,SUBSTRING(receive_pallet_code,3,10) asc
,receive_tags_code asc
";

$objQuery_picking_order_details = sqlsrv_query($db_con, $strSql_picking_order_details, $params, $options);
$num_row_picking_order_details = sqlsrv_num_rows($objQuery_picking_order_details);

$row_id_picking_order_details = 0;
while($objResult_picking_order_details = sqlsrv_fetch_array($objQuery_picking_order_details, SQLSRV_FETCH_ASSOC))
{
	$row_id_picking_order_details++;
	
	$receive_pallet_code = $objResult_picking_order_details['receive_pallet_code'];
	$receive_tags_code = $objResult_picking_order_details['receive_tags_code'];
	$tags_fg_code_gdj = $objResult_picking_order_details['tags_fg_code_gdj'];
	$receive_location = $objResult_picking_order_details['receive_location'];
	$tags_packing_std = $objResult_picking_order_details['tags_packing_std'];
	
	//insert picking tbl_picking_tail
	$strSql_insert_picking_tail = " INSERT INTO [dbo].[tbl_picking_tail]
           (
		   [ps_t_picking_code]
           ,[ps_t_ref_replenish_code]
           ,[ps_t_pallet_code]
           ,[ps_t_tags_code]
           ,[ps_t_tags_packing_std]
           ,[ps_t_fg_code_set_abt]
           ,[ps_t_sku_code_abt]
           ,[ps_t_fg_code_gdj]
           ,[ps_t_location]
           ,[ps_t_cus_name]
           ,[ps_t_pj_name]
           ,[ps_t_replenish_qty]
           ,[ps_t_replenish_qty_to_pack]
           ,[ps_t_replenish_unit_type]
           ,[ps_t_terminal_name]
           ,[ps_t_order_type]
		   ,[ps_t_ship_type]
		   ,[ps_t_part_customer]
           ,[ps_t_status]
           ,[ps_t_issue_by]
           ,[ps_t_issue_date]
           ,[ps_t_issue_time]
           ,[ps_t_issue_datetime]
		   )
     VALUES
           (
		   '$iden_txt_curr_picking_no'
           ,'$repn_running_code'
           ,'$receive_pallet_code'
           ,'$receive_tags_code'
           ,'$tags_packing_std'
           ,'$iden_hdn_repn_fg_code_set_abt'
           ,'$iden_hdn_repn_sku_code_abt'
           ,'$tags_fg_code_gdj'
           ,'$receive_location'
           ,'$iden_hdn_bom_cus_name'
           ,'$iden_hdn_bom_pj_name'
           ,'$repn_qty'
           ,'$iden_hdn_str_conv_pack'
           ,'$repn_unit_type'
           ,'$repn_terminal_name'
           ,'$repn_order_type'
		   ,'$iden_hdn_bom_ship_type'
		   ,'$iden_hdn_bom_part_customer'
           ,'Picking'
           ,'$t_cur_user_code_VMI_GDJ'
           ,'$buffer_date'
           ,'$buffer_time'
           ,'$buffer_datetime'
		   )	
	";

	$objQuery_insert_picking_tail = sqlsrv_query($db_con, $strSql_insert_picking_tail);
	
	if($objQuery_insert_picking_tail)
	{
		//update tbl_receive - receive_status = Picking
		$sqlUpdatePicking = " UPDATE tbl_receive SET receive_status = 'Picking' WHERE receive_tags_code = '$receive_tags_code' and receive_pallet_code = '$receive_pallet_code' ";
		$result_sqlUpdatePicking = sqlsrv_query($db_con, $sqlUpdatePicking);
	}
}

//update tbl_replenishment - repn_conf_status = Picking
$sqlUpdateReplenishment = " UPDATE tbl_replenishment SET repn_conf_status = 'Picking' WHERE repn_id = '$iden_hdn_repn_id' and repn_conf_status = 'Confirmed' ";
$result_sqlUpdateReplenishment = sqlsrv_query($db_con, $sqlUpdateReplenishment);
	
//update tbl_picking_running - picking_status = Matched
$sqlUpdatePickingRunning = " UPDATE tbl_picking_running SET picking_status = 'Matched' WHERE picking_code = '$iden_txt_curr_picking_no' ";
$result_sqlUpdatePickingRunning = sqlsrv_query($db_con, $sqlUpdatePickingRunning);

sqlsrv_close($db_con);
?>