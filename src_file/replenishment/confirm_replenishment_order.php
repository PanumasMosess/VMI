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
$iden_t_order_type = isset($_POST['iden_t_order_type']) ? $_POST['iden_t_order_type'] : '';
$iden_t_ref_no = isset($_POST['iden_t_ref_no']) ? $_POST['iden_t_ref_no'] : '';
$iden_t_fifo_picking_pack = isset($_POST['iden_t_fifo_picking_pack']) ? $_POST['iden_t_fifo_picking_pack'] : '';
$iden_hdn_repn_qty = isset($_POST['iden_hdn_repn_qty']) ? $_POST['iden_hdn_repn_qty'] : '';


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
repn_id = '$iden_t_repn_id' ";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;

	$index_repn_fg_code_set_abt = $objResult['repn_fg_code_set_abt'];
	$index_repn_sku_code_abt = $objResult['repn_sku_code_abt'];
	$index_bom_fg_code_gdj = $objResult['bom_fg_code_gdj'];
	$index_bom_pj_name = $objResult['bom_pj_name'];
	$index_bom_ship_type = $objResult['bom_ship_type'];
	$index_bom_part_customer = $objResult['bom_part_customer'];
}
		
//get stock each fg code gdj
$str_stock = get_stock_each_fg_gdj($db_con,$index_repn_fg_code_set_abt,$index_repn_sku_code_abt,$index_bom_fg_code_gdj,$index_bom_pj_name,$index_bom_ship_type,$index_bom_part_customer);

//Check Stock is not enough
if($str_stock < $iden_hdn_repn_qty)
{
	//Stock is not enough
}
else
{
	if($iden_t_order_type == "VMI Order")
	{
		////9 digit (000000001)////
		$sprintf_repn_id = sprintf("%09d",$iden_t_repn_id);//generate to 9 digit
		$full_repn_running_code = "VMI".$sprintf_repn_id;//full tags
	}
	else if($iden_t_order_type == "Special Order")
	{
		////9 digit (000000001)////
		$sprintf_repn_id = sprintf("%09d",$iden_t_repn_id);//generate to 9 digit
		$full_repn_running_code = "SPECIAL".$sprintf_repn_id;//full tags
	}
	else
	{
		$full_repn_running_code = $iden_t_ref_no;
	}
			
	//update status
	$sqlUpdate = " UPDATE tbl_replenishment
	   SET [repn_running_code] = '$full_repn_running_code'
		  ,[repn_conf_status] = 'Confirmed'
		  ,[repn_conf_by] = '$t_cur_user_code_VMI_GDJ'
		  ,[repn_conf_date] = '$buffer_date'
		  ,[repn_conf_time] = '$buffer_time'
		  ,[repn_conf_datetime] = '$buffer_datetime'
	 WHERE repn_id = '$iden_t_repn_id'
	 ";
	$result_sqlUpdate = sqlsrv_query($db_con, $sqlUpdate);

	$result_sqlUpdate = true;

	if($result_sqlUpdate)
	{
	$str_total_qty_c;
	while($str_total_qty_c < $iden_hdn_repn_qty){

		$str_total_qty_c = 0;

		//check tags
		$strSql_picking_order_details_check = " 
		select top $iden_t_fifo_picking_pack 
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
		receive_repn_id is NULL
		and
		repn_fg_code_set_abt = '$index_repn_fg_code_set_abt'
		and
		repn_sku_code_abt = '$index_repn_sku_code_abt'
		and
		bom_fg_code_gdj = '$index_bom_fg_code_gdj'
		and
		bom_pj_name = '$index_bom_pj_name'
		and
		bom_ship_type = '$index_bom_ship_type'
		and
		bom_part_customer = '$index_bom_part_customer'
		and
		bom_status = 'Active'
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

		$objQuery_picking_order_details_c = sqlsrv_query($db_con, $strSql_picking_order_details_check, $params, $options);
		$num_row_picking_order_details_c = sqlsrv_num_rows($objQuery_picking_order_details_c);
		
		
		while($objResult_picking_order_details_c = sqlsrv_fetch_array($objQuery_picking_order_details_c, SQLSRV_FETCH_ASSOC))
		{
			
			
			$tags_packing_std_c = $objResult_picking_order_details_c['tags_packing_std'];
			
			//sum total qty
			$str_total_qty_c = $str_total_qty_c + $tags_packing_std_c;		
		}

		if($str_total_qty_c < $iden_hdn_repn_qty){
			$iden_t_fifo_picking_pack = $iden_t_fifo_picking_pack + 1;
		}

	}
	
			//reserve tags
			$strSql_picking_order_details = " 
			select top $iden_t_fifo_picking_pack 
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
			receive_repn_id is NULL
			and
			repn_fg_code_set_abt = '$index_repn_fg_code_set_abt'
			and
			repn_sku_code_abt = '$index_repn_sku_code_abt'
			and
			bom_fg_code_gdj = '$index_bom_fg_code_gdj'
			and
			bom_pj_name = '$index_bom_pj_name'
			and
			bom_ship_type = '$index_bom_ship_type'
			and
			bom_part_customer = '$index_bom_part_customer'
			and
			bom_status = 'Active'
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
				
				
				//tbl_receive reserve by replenish id
				$sqlUpdate_reserve_repn_id = " UPDATE tbl_receive
				   SET receive_repn_id = '$iden_t_repn_id'
				 WHERE receive_tags_code = '$receive_tags_code'
				 ";
				$result_sqlUpdate_reserve_repn_id = sqlsrv_query($db_con, $sqlUpdate_reserve_repn_id);
			}
	
	}
}

sqlsrv_close($db_con);
?>