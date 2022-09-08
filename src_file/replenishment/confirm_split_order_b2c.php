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


	//Check plan is not enough
	if(($repn_qty <= $iden_t_split_number) || ($repn_qty == "1"))
	{
		echo "not enough";
	}
	else
	{
		$new_qty = $repn_qty - $iden_t_split_number;

		$string_ref_split = $repn_order_ref."_split";

		//update old replenishment
		$sqlUpdateOld = " UPDATE tbl_replenishment
		SET [repn_qty] = '$new_qty'
	   	,[repn_by] = '".$t_cur_user_code_VMI_GDJ."'
	   	,[repn_date] = '$buffer_date'
	   	,[repn_time] = '$buffer_time'
	   	,[repn_datetime] = '$buffer_datetime'
  		WHERE repn_id = '$iden_t_repn_id'
  		";

 		$result_sqlUpdateOld = sqlsrv_query($db_con, $sqlUpdateOld);

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
							   ,'$buffer_date'
							   ,'$buffer_time'
							   ,'$buffer_datetime'
							   )
						";
						$objQuery_insert = sqlsrv_query($db_con, $strSQL_insert);

			$str_sql_check_detail = "SELECT * FROM [tbl_b2c_detail] 
			LEFT JOIN tbl_b2c_sale ON
			tbl_b2c_sale.b2c_sale_order_id = tbl_b2c_detail.b2c_repn_order_ref
			WHERE  b2c_repn_order_ref = '$repn_order_ref'";

			$result_check_detail = sqlsrv_query($db_con, $str_sql_check_detail);
			while($objResult = sqlsrv_fetch_array($result_check_detail, SQLSRV_FETCH_ASSOC)){
				$b2c_repn_order_ref = $objResult['b2c_repn_order_ref'];
				$b2c_customer_code = $objResult['b2c_customer_code'];
				$b2c_cus_company = $objResult['b2c_cus_company'];
				$b2c_inv_company = $objResult['b2c_inv_company'];
				$b2c_cus_branch_id = $objResult['b2c_cus_branch_id'];
				$b2c_customer_name = $objResult['b2c_customer_name'];
				$b2c_delivery_address = $objResult['b2c_delivery_address'];
				$b2c_cus_zipcode = $objResult['b2c_cus_zipcode'];
				$b2c_inv_address = $objResult['b2c_inv_address'];
				$b2c_cus_zipcode = $objResult['b2c_cus_zipcode'];
				$b2c_zipcode = $objResult['b2c_zipcode'];
				$b2c_contact_name = $objResult['b2c_contact_name'];
				$b2c_tel = $objResult['b2c_tel'];
				$b2c_note = $objResult['b2c_note'];
				$b2c_dtn = $objResult['b2c_dtn'];
				$b2c_track_num = $objResult['b2c_track_num'];
				$b2c_tax_inv = $objResult['b2c_tax_inv'];
				$b2c_status = $objResult['b2c_status'];
				$b2c_case = $objResult['b2c_case'];
				$b2c_order_date = $objResult['b2c_order_date'];
				$b2c_sender = $objResult['b2c_sender'];
				//sale data
				$b2c_sale_date =  $objResult['b2c_sale_date'];
           		$b2c_sale_time =  $objResult['b2c_sale_time'];
           		$b2c_sale_count_time = $objResult['b2c_sale_count_time'];
           		$b2c_sale_user_id = $objResult['b2c_sale_user_id'];
           		$b2c_sale_pos_no =  $objResult['b2c_sale_pos_no'];
           		$b2c_sale_inv_no = $objResult['b2c_sale_inv_no'];
           		$b2c_sale_excluding_vat = $objResult['b2c_sale_excluding_vat'];
           		$b2c_sale_tax = $objResult['b2c_sale_tax'];
           		$b2c_sale_including_vat = $objResult['b2c_sale_including_vat'];
           		$b2c_sale_remark =  $objResult['b2c_sale_remark'];
           		$b2c_sale_branch = $objResult['b2c_sale_branch'];
           		$b2c_sale_transport_fee = $objResult['b2c_sale_transport_fee'];
           		$b2c_sale_discount_amount = $objResult['b2c_sale_discount_amount'];

				$str_insert_detail = "
				INSERT INTO [dbo].[tbl_b2c_detail]
				([b2c_repn_order_ref]
				,[b2c_customer_code]
				,[b2c_cus_company]
				,[b2c_inv_company]
				,[b2c_cus_branch_id]
				,[b2c_customer_name]
				,[b2c_delivery_address]
				,[b2c_cus_zipcode]
				,[b2c_inv_address]
				,[b2c_zipcode]
				,[b2c_contact_name]
				,[b2c_tel]
				,[b2c_note]
				,[b2c_order_date]
				,[b2c_dtn]
				,[b2c_track_num]
				,[b2c_tax_inv]
				,[b2c_status]
				,[b2c_case]
				,[b2c_sender])
		  VALUES
				(
				 '$string_ref_split'
				,'$b2c_customer_code'
				,'$b2c_cus_company'
				,'$b2c_inv_company'
				,'$b2c_cus_branch_id'
				,'$b2c_customer_name'
				,'$b2c_delivery_address'
				,'$b2c_cus_zipcode'
				,'$b2c_inv_address'
				,'$b2c_zipcode'
				,'$b2c_contact_name'
				,'$b2c_tel'
				,'$b2c_note'
				,'$b2c_order_date'
				,'$b2c_dtn'
				,'$b2c_track_num'
				,'$b2c_tax_inv'
				,'$b2c_status'
				,'$b2c_case'
				,'$b2c_sender'
				)";

				$objQuery_insert_detail = sqlsrv_query($db_con, $str_insert_detail);
				if($objQuery_insert_detail){
					$str_insert_sale = "
					INSERT INTO [dbo].[tbl_b2c_sale]
           			([b2c_sale_date]
           			,[b2c_sale_time]
           			,[b2c_sale_count_time]
           			,[b2c_sale_order_id]
           			,[b2c_sale_user_id]
           			,[b2c_sale_pos_no]
           			,[b2c_sale_inv_no]
           			,[b2c_sale_excluding_vat]
           			,[b2c_sale_tax]
           			,[b2c_sale_including_vat]
           			,[b2c_sale_remark]
           			,[b2c_sale_branch]
           			,[b2c_sale_transport_fee]
           			,[b2c_sale_discount_amount])
    		 VALUES
           			('$b2c_sale_date'
           			,'$b2c_sale_time'
           			,'$b2c_sale_count_time'
           			,'$string_ref_split'
           			,'$b2c_sale_user_id'
           			,'$b2c_sale_pos_no'
           			,'$b2c_sale_inv_no'
           			,'$b2c_sale_excluding_vat'
           			,'$b2c_sale_tax'
           			,'$b2c_sale_including_vat'
           			,'$b2c_sale_remark'
           			,'$b2c_sale_branch'
           			,'$b2c_sale_transport_fee'
           			,'$b2c_sale_discount_amount')
					";
					$objQuery_insert_sale = sqlsrv_query($db_con, $str_insert_sale);
				}
			}
					
		}

	}

}
	

sqlsrv_close($db_con);
?>