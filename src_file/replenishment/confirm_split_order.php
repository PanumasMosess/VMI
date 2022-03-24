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

		$string_ref_split =  $repn_order_ref."(From Split)";

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
							   ,'$repn_date'
							   ,'$repn_time'
							   ,'$repn_datetime'
							   )
						";
						$objQuery_insert = sqlsrv_query($db_con, $strSQL_insert);
		}

	}

}
	

sqlsrv_close($db_con);
?>