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
$iden_t_repn_id = isset($_POST['iden_t_repn_id']) ? $_POST['iden_t_repn_id'] : '';
$iden_t_split_number = isset($_POST['iden_t_split_number']) ? $_POST['iden_t_split_number'] : '';


//replenishment get unique
$strSql = " select * from tbl_replenishment 
left join tbl_bom_mst 
on tbl_replenishment.repn_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
and tbl_replenishment.repn_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
and tbl_replenishment.repn_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
and tbl_replenishment.repn_pj_name = tbl_bom_mst.bom_pj_name
and tbl_replenishment.repn_ship_type = tbl_bom_mst.bom_ship_type
and tbl_replenishment.repn_part_customer = tbl_bom_mst.bom_part_customer
where
repn_conf_status is null
and bom_status = 'Active'
and repn_id = '$iden_t_repn_id'";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;

	$repn_id = $objResult['repn_id'];
	$repn_order_ref = $objResult['repn_order_ref'];
	$repn_fg_code_set_abt = $objResult['repn_fg_code_set_abt'];
	$repn_sku_code_abt = $objResult['repn_sku_code_abt'];
	$repn_qty = $objResult['repn_qty'];
	$repn_unit_type = $objResult['repn_unit_type'];
	$repn_terminal_name = $objResult['repn_terminal_name'];
	$repn_order_type = $objResult['repn_order_type'];
	$repn_delivery_date = $objResult['repn_delivery_date'];
	$repn_by = $objResult['repn_by'];
	$repn_date = $objResult['repn_date'];
	$repn_time = $objResult['repn_time'];
	$repn_datetime = $objResult['repn_datetime'];
	$repn_conf_status = $objResult['repn_conf_status'];
	$repn_conf_remark = $objResult['repn_conf_remark'];
	$repn_conf_by = $objResult['repn_conf_by'];
	$repn_conf_date = $objResult['repn_conf_date'];
	$repn_conf_time = $objResult['repn_conf_time'];
	$repn_conf_datetime = $objResult['repn_conf_datetime'];
	$bom_fg_code_gdj = $objResult['bom_fg_code_gdj'];
	$bom_cus_code = $objResult['bom_cus_code'];
	$bom_pj_name = $objResult['bom_pj_name'];
	$bom_ship_type = $objResult['bom_ship_type'];
	$bom_snp = $objResult['bom_snp'];
	$bom_usage = $objResult['bom_usage'];
	$bom_packing = $objResult['bom_packing'];
	$bom_part_customer = $objResult['bom_part_customer'];
	$repn_ord_code = $objResult['repn_ord_code'];
	$repn_odt_item_code = $objResult['repn_odt_item_code'];


	//Check plan is not enough
	if(($repn_qty <= $iden_t_split_number) || ($repn_qty == "1"))
	{
		echo "not enough";
	}
	else
	{
		$new_qty = $repn_qty - $iden_t_split_number;
		$new_item_code_repn = $repn_odt_item_code + 1;

		$string_ref_split =  $repn_order_ref."(From Split)";

		//update old replenishment
		$sqlUpdateOld = " UPDATE tbl_replenishment
		SET [repn_qty] = '$new_qty'
	   	,[repn_by] = '".$t_cur_user_code_VMI_GDJ."'
  		WHERE repn_id = '$iden_t_repn_id'
  		";
		//   ,[repn_date] = '$buffer_date'
		//   ,[repn_time] = '$buffer_time'
		//   ,[repn_datetime] = '$buffer_datetime'

 		$result_sqlUpdateOld = sqlsrv_query($db_con, $sqlUpdateOld);

		//insert new replenishment

		if($result_sqlUpdateOld){
			$strSQL_insert = "
						INSERT INTO [dbo].[tbl_replenishment]
							   (
							   [repn_order_ref]
							   ,[repn_fg_code_set_abt]
							   ,[repn_sku_code_abt]
							   ,[repn_fg_code_gdj]
							   ,[repn_pj_name]
							   ,[repn_ship_type]
							   ,[repn_part_customer]
							   ,[repn_qty]
							   ,[repn_unit_type]
							   ,[repn_terminal_name]
							   ,[repn_order_type]
							   ,[repn_delivery_date]
							   ,[repn_by]
							   ,[repn_date]
							   ,[repn_time]
							   ,[repn_datetime]
							   ,[repn_ord_code]
							   ,[repn_odt_item_code]
							   )
						 VALUES
							   (
							    '$string_ref_split'
							   ,'$repn_fg_code_set_abt'
							   ,'$repn_sku_code_abt'
							   ,'$bom_fg_code_gdj'
							   ,'$bom_pj_name'
							   ,'$bom_ship_type'
							   ,'$bom_part_customer'
							   ,'$iden_t_split_number'
							   ,'$repn_unit_type'
							   ,'$repn_terminal_name'
							   ,'$repn_order_type'
							   ,'$repn_delivery_date'
							   ,'".$t_cur_user_code_VMI_GDJ."'
							   ,'$repn_date'
							   ,'$repn_time'
							   ,'$repn_datetime'
							   ,'$repn_ord_code'
							   ,'$new_item_code_repn'
							   )
						";
						$objQuery_insert = sqlsrv_query($db_con, $strSQL_insert);
		}

		///STAMP TO MRP 
		$mrp_order = "SELECT [odt_uniq],[odt_ord_code],[odt_otr_code],[odt_cus_code],[odt_item_code],[odt_fg_code],[odt_fg_codeset],[odt_comp_code],[odt_fg_type],[odt_part_customer]
		,[odt_project],[odt_order_qty],[odt_boh_now_in],[odt_qty_confirm],[odt_unit_type],[odt_order_type],[odt_status],[odt_now_in],[odt_ship_type],[odt_delivery_date],[odt_create_datetime],[odt_create_by],[odt_order_remark],[ontime],[act_by] 
		FROM [tbl_customer_order_detail_mst] WHERE odt_ord_code = '$repn_ord_code' and odt_item_code = '$repn_odt_item_code'";	
		$result_check_mpp = sqlsrv_query($db_con_mrp, $mrp_order);

		if($result_check_mpp){
			while($objResult = sqlsrv_fetch_array($result_check_mpp, SQLSRV_FETCH_ASSOC))
			{			
				$odt_uniq = $objResult['odt_uniq'];
				$odt_ord_code = $objResult['odt_ord_code'];
				$odt_otr_code = $objResult['odt_otr_code'];
				$odt_cus_code = $objResult['odt_cus_code'];
				$odt_item_code = $objResult['odt_item_code'];
				$odt_fg_code = $objResult['odt_fg_code'];
				$odt_fg_codeset = $objResult['odt_fg_codeset'];
				$odt_comp_code = $objResult['odt_comp_code'];
				$odt_fg_type = $objResult['odt_fg_type'];
				$odt_part_customer = $objResult['odt_part_customer'];
				$odt_project = $objResult['odt_project'];
				$odt_order_qty = $objResult['odt_order_qty'];
				$odt_boh_now_in = $objResult['odt_boh_now_in'];
				$odt_qty_confirm = $objResult['odt_qty_confirm'];
				$odt_unit_type = $objResult['odt_unit_type'];
				$odt_order_type = $objResult['odt_order_type'];
				$odt_status = $objResult['odt_status'];
				$odt_now_in = $objResult['odt_now_in'];
				$odt_ship_type = $objResult['odt_ship_type'];
				$odt_delivery_date = $objResult['odt_delivery_date'];
				$odt_create_by = $objResult['odt_create_by'];
				$odt_order_remark = $objResult['odt_order_remark'];
				$ontime = $objResult['ontime'];   
				$act_by = $objResult['act_by']; 	

			$new_qty_mrp = $odt_order_qty - $iden_t_split_number;

			$update_order = "UPDATE tbl_customer_order_detail_mst SET odt_order_qty = '$new_qty_mrp' WHERE odt_ord_code = '$repn_ord_code' and odt_item_code = '$repn_odt_item_code'";
			$update_mpp = sqlsrv_query($db_con_mrp, $update_order);

			$new_item_code = $odt_item_code + 1 ;

			if($update_mpp){
				$insert_new_order_mrp = "INSERT INTO tbl_customer_order_detail_mst(odt_ord_code, odt_otr_code, odt_cus_code, odt_item_code, odt_fg_code, odt_fg_codeset, odt_comp_code, odt_part_customer, odt_project, odt_order_qty, odt_unit_type, odt_order_type, odt_status, odt_now_in, odt_ship_type, odt_delivery_date, odt_create_datetime, odt_create_by)
				VALUES('$odt_ord_code','$odt_otr_code','$odt_cus_code','$new_item_code','$odt_fg_code','$odt_fg_codeset','$odt_comp_code','$odt_part_customer','$odt_project','$iden_t_split_number','$odt_unit_type','$odt_order_type','$odt_status','$odt_now_in','$odt_ship_type','$buffer_date','$buffer_datetime','$t_cur_user_code_VMI_GDJ')";
				$listQuery = sqlsrv_query($db_con_mrp,$insert_new_order_mrp);
			}
		  }
		}

	}

}
	

sqlsrv_close($db_con_mrp);
sqlsrv_close($db_con);
?>