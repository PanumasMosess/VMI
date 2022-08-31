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
$var_arr_list = isset($_POST['var_arr_list']) ? $_POST['var_arr_list'] : '';
$customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
$customer_tel = isset($_POST['customer_tel']) ? $_POST['customer_tel'] : '';
$product_price_sum = isset($_POST['product_price_sum']) ? $_POST['product_price_sum'] : '';
$customer_price = isset($_POST['customer_price']) ? $_POST['customer_price'] : '';
$customer_inv = isset($_POST['customer_inv']) ? $_POST['customer_inv'] : '';

$str_split_TagsProject = explode(',', $var_arr_list);
$str_count_arr = count($str_split_TagsProject);
$i_rows = 0 ;
$str_tags = "";

//clear
$buffer_substr_running = 0;
$buffer_substr_outlet = "";
$full_OLCode = "";

$str_outlet_code = "
	 SELECT TOP(1) SUBSTRING(customer_outlet_code,0,3) as str_outlet 
	,SUBSTRING(customer_outlet_code,4,8) as num_runing
	 FROM [tbl_customer_outlet] ORDER BY  customer_outlet_code DESC
";
$objQuery_get_code_outlet = sqlsrv_query($db_con, $str_outlet_code);

while($objResult_get_ol_code = sqlsrv_fetch_array($objQuery_get_code_outlet, SQLSRV_FETCH_ASSOC))
{
	$buffer_substr_outlet = $objResult_get_ol_code['str_outlet'];
	$buffer_substr_running = $objResult_get_ol_code['num_runing'];
}

if($buffer_substr_running == "")
{
	$buffer_substr_ol = "OL";
	$sum_OLCode = 1;
	$sprintf_OLCode = sprintf("%05d",$sum_OLCode);//generate to 4 digit
	$full_OLCode = $buffer_substr_ol.$sprintf_OLCode;//full	
}
else
{
	$buffer_substr_ol = "OL";
	$sum_OLCode = $buffer_substr_running + 1;//sum + 1
	$sprintf_OLCode = sprintf("%05d",$sum_OLCode);//generate to 4 digit
	$full_OLCode = $buffer_substr_ol.$sprintf_OLCode;//full

}




foreach ($str_split_TagsProject as $str_tagsProject)
{
	$str_split = explode('#####', $str_tagsProject);
	$str_tags = $str_split[0];

	//get data
	$strSQL_get_data = " 
	SELECT 
		tags_code,
		tags_fg_code_gdj,
		tags_fg_code_gdj_desc,
		tags_packing_std,
		receive_status,
		ps_t_fg_code_set_abt,
		ps_t_sku_code_abt,
		ps_t_ship_type,
		ps_t_part_customer,
		bom_snp,
		bom_price_sale_per_pcs
	FROM tbl_receive T1
	LEFT JOIN tbl_tags_running T2 ON T1.receive_tags_code = T2.tags_code
	LEFT JOIN tbl_picking_tail T3 ON T1.receive_tags_code = T3.ps_t_tags_code
	LEFT JOIN tbl_bom_mst T4 ON T3.ps_t_fg_code_set_abt = T4.bom_fg_code_set_abt
	AND T3.ps_t_sku_code_abt = T4.bom_fg_sku_code_abt
	AND T3.ps_t_fg_code_gdj = T4.bom_fg_code_gdj
	AND T3.ps_t_pj_name = T4.bom_pj_name
	AND T3.ps_t_ship_type = T4.bom_ship_type
	AND T3.ps_t_part_customer = T4.bom_part_customer
	WHERE 
	T1.receive_tags_code = '$str_tags'
	AND T4.bom_status = 'Active'
	";
	$objQuery_get_data = sqlsrv_query($db_con, $strSQL_get_data);
	$result_get_data = sqlsrv_fetch_array($objQuery_get_data, SQLSRV_FETCH_ASSOC);
	
	if($result_get_data)//Check true
	{
		//insert tbl_usage_conf
		$sqlInsert_usage_conf = "
		INSERT INTO [dbo].[tbl_usage_conf]
			   (
			   [usage_tags_code]
			   ,[usage_fg_code_set_abt]
			   ,[usage_sku_code_abt]
			   ,[usage_fg_code_gdj]
			   ,[usage_ship_type]
			   ,[usage_part_customer]
			   ,[usage_terminal_name]
			   ,[usage_price_sale_per_pcs]
			   ,[usage_unit_type]
			   ,[usage_pick_by]
			   ,[usage_pick_date]
			   ,[usage_pick_time]
			   ,[usage_pick_datetime]
			   )
		 VALUES
			   (
			   '$str_tags'
			   ,'".$result_get_data['ps_t_fg_code_set_abt']."'
			   ,'".$result_get_data['ps_t_sku_code_abt']."'
			   ,'".$result_get_data['tags_fg_code_gdj']."'
			   ,'".$result_get_data['ps_t_ship_type']."'
			   ,'".$result_get_data['ps_t_part_customer']."'
			   ,'".$result_get_data['receive_status']."'
			   ,'".$result_get_data['bom_price_sale_per_pcs']."'
			   ,'Component'
			   ,'".$t_cur_user_code_VMI_GDJ."'
			   ,'$buffer_date'
			   ,'$buffer_time'
			   ,'$buffer_datetime'
			   )
		";
		$result_sqlInsert_usage_conf = sqlsrv_query($db_con, $sqlInsert_usage_conf);
		
		if($result_sqlInsert_usage_conf)
		{
			//tbl_receive update to USAGE CONFIRM
			$sqlUpdate_receive = "
					
					UPDATE [dbo].[tbl_receive]
					   SET [receive_status] = 'USAGE CONFIRM'
					 WHERE [receive_tags_code] = '$str_tags'
				   ";
			$resultUpdate_receive = sqlsrv_query($db_con, $sqlUpdate_receive);
			
			if($resultUpdate_receive)
			{
				////////////////////////////////////
				/////////////vmi auto order/////////
				////////////////////////////////////
				$Sql_Get_Part_VMI = "SELECT conf_qc_fg_code_set_abt,conf_qc_sku_code_abt
				,conf_qc_fg_code_gdj,conf_qc_ship_type,conf_qc_part_customer,
				conf_qc_terminal_name,conf_qc_snp
				FROM tbl_usage_conf_qc
				WHERE conf_qc_terminal_name = '".$result_get_data['receive_status']."'
				GROUP BY conf_qc_fg_code_set_abt,conf_qc_sku_code_abt,conf_qc_fg_code_gdj,
				conf_qc_ship_type,conf_qc_part_customer,conf_qc_terminal_name,conf_qc_snp";
				$Query_Get_Part_VMI = sqlsrv_query($db_con, $Sql_Get_Part_VMI, $params, $options);
				$Num_Row_Get_Part_VMI = sqlsrv_num_rows($Query_Get_Part_VMI);
				
				if($Query_Get_Part_VMI)
				{
					$sql_Get_Date_Item = " SELECT leadtime_value FROM tbl_project_lead_time WHERE leadtime_project = '".$result_get_data['receive_status']."' ";
					$Day_Item = '';
					$query_Get_Date_Item = sqlsrv_query($db_con, $sql_Get_Date_Item, $params, $options);
					
					if($query_Get_Date_Item)
					{
						$num_row_Get_Date_Item = sqlsrv_num_rows($query_Get_Date_Item);
						if ($num_row_Get_Date_Item > 0)
						{
							while($Read_Get_Date_Item = sqlsrv_fetch_array($query_Get_Date_Item, SQLSRV_FETCH_ASSOC))
							{   
								$Day_Item = $Read_Get_Date_Item["leadtime_value"];  
							}
						}
						else
						{ 
							$Day_Item = '0';
						}
					}
					
					$Format_Date = str_replace('-', '/', $buffer_date);
					$Delivery_Date = date('Y-m-d',strtotime($Format_Date . "+".$Day_Item." days"));
					
					while($objResult_Part_VMI = sqlsrv_fetch_array($Query_Get_Part_VMI, SQLSRV_FETCH_ASSOC))
					{
						$fg_code_gdj = $objResult_Part_VMI['conf_qc_fg_code_gdj'];
						$fg_code_set_abt = $objResult_Part_VMI['conf_qc_fg_code_set_abt'];
						$sku_code_abt = $objResult_Part_VMI['conf_qc_sku_code_abt'];
						$FG_Code_Customer = $objResult_Part_VMI['conf_qc_part_customer'];
						$Ship_Type = $objResult_Part_VMI['conf_qc_ship_type'];
						$Bom_snp = $objResult_Part_VMI['conf_qc_snp'];

						$Sql_VMI_Stock = " SELECT  T1.bom_fg_code_gdj,T1.bom_fg_code_set_abt,
						REPLACE(T1.bom_fg_sku_code_abt, ' ', '') AS bom_fg_sku_code_abt,
						CASE WHEN (T3.receive_status = '".$result_get_data['receive_status']."') THEN ISNULL(SUM(T2.tags_packing_std), 0) 
						ELSE 0 END AS Stocm_VMI
						FROM tbl_bom_mst T1
						LEFT JOIN tbl_tags_running T2 ON T1.bom_fg_code_gdj = T2.tags_fg_code_gdj                 
						LEFT JOIN tbl_receive T3 ON T2.tags_code = T3.receive_tags_code
						WHERE  T1.bom_part_customer = '$FG_Code_Customer' 
						AND T1.bom_pj_name = '".$result_get_data['receive_status']."' AND T3.receive_status = '".$result_get_data['receive_status']."' 
						AND T1.bom_status = 'Active' AND T1.bom_ship_type = '$Ship_Type' 
						AND T1.bom_snp = '$Bom_snp' 
						AND T1.bom_fg_code_gdj = '$fg_code_gdj'
						AND T1.bom_fg_sku_code_abt = '$sku_code_abt'
						GROUP BY T1.bom_fg_sku_code_abt,T1.bom_fg_code_gdj,T1.bom_fg_code_set_abt,T3.receive_status ";
						
						$objQuery_VMI_Stock = sqlsrv_query($db_con, $Sql_VMI_Stock);        
						while($objResult_VMI_Stock = sqlsrv_fetch_array($objQuery_VMI_Stock, SQLSRV_FETCH_ASSOC))
						{
							$Stock_VMI = $objResult_VMI_Stock["Stocm_VMI"];
						}
						
						$Sql_Bom_Min_Max = "SELECT bom_vmi_min,bom_vmi_max
						FROM tbl_bom_mst
						WHERE bom_fg_code_set_abt = '$fg_code_set_abt'
						AND bom_fg_sku_code_abt = '$sku_code_abt'
						AND bom_fg_code_gdj = '$fg_code_gdj'
						AND bom_pj_name = '".$result_get_data['receive_status']."'
						AND bom_ship_type = '$Ship_Type'
						AND bom_part_customer = '$FG_Code_Customer'";
						
						$objQuery_Bom_Min_Max = sqlsrv_query($db_con, $Sql_Bom_Min_Max);         
						while($objResult_Bom_Min_Max = sqlsrv_fetch_array($objQuery_Bom_Min_Max, SQLSRV_FETCH_ASSOC))
						{
							$Bom_Min = $objResult_Bom_Min_Max["bom_vmi_min"];
							$Bom_Max = $objResult_Bom_Min_Max["bom_vmi_max"]; 
						}
						
						//check min,max
						if($Stock_VMI <= $Bom_Min)
						{
							$Qty_Repen = ($Bom_Max - $Stock_VMI);
							
							//Check duplicate
							$strSQL_dup = " 
							select TOP (1)
								   repn_fg_code_set_abt
								   ,repn_sku_code_abt
								   ,repn_fg_code_gdj
								   ,repn_pj_name
								   ,repn_ship_type
								   ,repn_part_customer
								   ,repn_snp
							from tbl_replenishment 
							where 
							repn_fg_code_set_abt = '$fg_code_set_abt' 
							and repn_sku_code_abt = '$sku_code_abt' 
							and repn_fg_code_gdj = '$fg_code_gdj'
							and repn_pj_name = '".$result_get_data['receive_status']."'
							and repn_ship_type = '$Ship_Type'
							and repn_part_customer = '$FG_Code_Customer'
							and repn_snp = '$Bom_snp'
							and repn_conf_status is null
							";
							$objQuery_dup = sqlsrv_query($db_con, $strSQL_dup);
							$result_dup = sqlsrv_fetch_array($objQuery_dup, SQLSRV_FETCH_ASSOC);
							
							//update
							if($result_dup)//Check duplicate true
							{
								$sql_Update_Auto_Min = " 
								UPDATE tbl_replenishment SET
									repn_fg_code_set_abt = '$fg_code_set_abt'
								   ,repn_sku_code_abt = '$sku_code_abt'
								   ,repn_fg_code_gdj = '$fg_code_gdj'
								   ,repn_pj_name = '".$result_get_data['receive_status']."'
								   ,repn_ship_type = '$Ship_Type'
								   ,repn_part_customer = '$FG_Code_Customer'
								   ,repn_snp = '$Bom_snp'
								   ,repn_qty = '$Qty_Repen'
								   ,repn_unit_type = 'Component'
								   ,repn_terminal_name = '".$result_get_data['receive_status']."'
								   ,repn_order_type = 'VMI Order'
								   ,repn_by = '".$t_cur_user_code_VMI_GDJ."'
								   ,repn_date = '$buffer_date'
								   ,repn_time = '$buffer_time'
								   ,repn_datetime = '$buffer_datetime'
								   ,repn_delivery_date = '$Delivery_Date'
								where 
								repn_fg_code_set_abt = '$fg_code_set_abt' 
								and repn_sku_code_abt = '$sku_code_abt' 
								and repn_fg_code_gdj = '$fg_code_gdj'
								and repn_pj_name = '".$result_get_data['receive_status']."'
								and repn_ship_type = '$Ship_Type'
								and repn_part_customer = '$FG_Code_Customer'
								and repn_snp = '$Bom_snp'
								and repn_conf_status is null
								";
								$Result_Update_Auto_Min = sqlsrv_query($db_con, $sql_Update_Auto_Min,$params, $options);
							}
							else //insert
							{
								$sql_Insert_Auto_Min = " 
								INSERT INTO tbl_replenishment
								   (
								   repn_fg_code_set_abt
								   ,repn_sku_code_abt
								   ,repn_fg_code_gdj
								   ,repn_pj_name
								   ,repn_ship_type
								   ,repn_part_customer
								   ,repn_snp
								   ,repn_qty
								   ,repn_unit_type
								   ,repn_terminal_name
								   ,repn_order_type
								   ,repn_by
								   ,repn_date
								   ,repn_time
								   ,repn_datetime
								   ,repn_delivery_date
								   )
								VALUES
								   (
								   '$fg_code_set_abt'
								   ,'$sku_code_abt'
								   ,'$fg_code_gdj'
								   ,'".$result_get_data['receive_status']."'
								   ,'$Ship_Type'
								   ,'$FG_Code_Customer'
								   ,'$Bom_snp'
								   ,'$Qty_Repen'
								   ,'Component'
								   ,'".$result_get_data['receive_status']."'
								   ,'VMI Order'
								   ,'".$t_cur_user_code_VMI_GDJ."'
								   ,'$buffer_date'
								   ,'$buffer_time'
								   ,'$buffer_datetime'
								   ,'$Delivery_Date'
								   )
								";
								$Result_Insert_Auto_Min = sqlsrv_query($db_con, $sql_Insert_Auto_Min,$params, $options);
							}
						}
					}
				}
				
				////////////////////////////////////
				///////////end vmi auto order///////
				////////////////////////////////////
				
				$sqlDel = " DELETE FROM [dbo].[tbl_usage_conf_qc] WHERE conf_qc_tags_code = '$str_tags' ";
				$resultDel = sqlsrv_query($db_con, $sqlDel);
			}

			
				////////////////////////////////////
				/// insert customer outlet//////////
				////////////////////////////////////
				
				$sql_insert_customer_outlet = "
				INSERT INTO [dbo].[tbl_customer_outlet]
				(
				 [customer_outlet_code]
				,[customer_outlet_name]
				,[customer_outlet_buy_product_fg]
				,[customer_outlet_buy_product_des]
				,[customer_outlet_tex_inv]
				,[customer_outlet_tel]
				,[customer_outlet_qty]
				,[customer_outlet_product_price]
				,[customer_outlet_customer_price]
				,[customer_outlet_date]
				,[customer_outlet_time]
				,[customer_outlet_date_time]
				)
		  		VALUES
				(
				 '$full_OLCode'
				,'$customer_name'
				,'".$result_get_data['tags_fg_code_gdj']."'
				,'".$result_get_data['tags_fg_code_gdj_desc']."'
				,'$customer_inv'
				,'$customer_tel'
				,'".$result_get_data['tags_packing_std']."'
				,'".$result_get_data['bom_price_sale_per_pcs']."'
				,'$customer_price'
				,'$buffer_date'
				,'$buffer_time'
				,'$buffer_datetime'
				)
				";
				
				$resultInsert_outlet = sqlsrv_query($db_con, $sql_insert_customer_outlet);
		}
	}
	
	$i_rows++;
}

//ctrl alert
if($str_count_arr == $i_rows)
{
	$full_OLCode = var_encode($full_OLCode);
	echo "$full_OLCode#####<b>--> Usage confirm success.</b>";
}
else
{
	echo "ERR#####Error!!!<b>--> Cannot operate !!!</b>";
}

sqlsrv_close($db_con);
?>