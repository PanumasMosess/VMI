<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

//session user //

$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

/**********************************************************************************/
/*var *****************************************************************************/
$iden_pj_name = isset($_POST['iden_pj_name']) ? $_POST['iden_pj_name'] : '';
$iden_txt_scn_driver_id = isset($_POST['iden_txt_scn_driver_id']) ? $_POST['iden_txt_scn_driver_id'] : '';
$iden_txt_scn_dtn_id = isset($_POST['iden_txt_scn_dtn_id']) ? $_POST['iden_txt_scn_dtn_id'] : '';
$iden_driver_sign = isset($_POST['iden_driver_sign']) ? $_POST['iden_driver_sign'] : '';
$iden_cus_sign = isset($_POST['iden_cus_sign']) ? $_POST['iden_cus_sign'] : '';


//for loop save log all tag DTN ID.
$strSql_DTNSheetDetails = " 
	SELECT 
	[dn_t_dtn_code]
	,[dn_h_status]
	,[ps_t_picking_code]
	,[ps_t_ref_replenish_code]
	,[ps_t_pallet_code]
	,[ps_t_tags_code]
	,[ps_t_fg_code_gdj]
	,[bom_fg_desc]
	,[bom_ctn_code_normal]
	,[ps_t_part_customer]
	,[ps_t_location]
	,[ps_t_tags_packing_std]
	,[ps_t_cus_name]
	,[ps_t_pj_name]
	,[ps_t_order_type]
	,[ps_t_replenish_unit_type]
	,[ps_t_replenish_qty_to_pack]
	,[ps_t_terminal_name]
	,[ps_t_order_type]
	,[ps_t_status]
	,[ps_t_issue_date]
	,[repn_qc_tags_code]
FROM tbl_dn_head
left join tbl_dn_tail 
on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
left join tbl_picking_head
on tbl_dn_tail.dn_t_picking_code = tbl_picking_head.ps_h_picking_code
left join tbl_picking_tail
on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
left join tbl_bom_mst 
on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
left join
tbl_replenishment_qc
on tbl_picking_tail.ps_t_tags_code = tbl_replenishment_qc.repn_qc_tags_code
and tbl_dn_head.dn_h_driver_code = tbl_replenishment_qc.repn_qc_driver_code
and tbl_dn_head.dn_h_dtn_code = tbl_replenishment_qc.repn_qc_dtn_code
where
[dn_t_dtn_code] = '$iden_txt_scn_dtn_id'
and
[repn_qc_tags_code] is null
and
[dn_h_status] = 'Delivery Transfer Note' 
ORDER BY repn_qc_tags_code DESC, ps_t_tags_code ASC
";

$objQuery_DTNSheetDetails = sqlsrv_query($db_con, $strSql_DTNSheetDetails, $params, $options);
$num_row_DTNSheetDetails = sqlsrv_num_rows($objQuery_DTNSheetDetails);

while($objResult_DTNSheetDetails = sqlsrv_fetch_array($objQuery_DTNSheetDetails, SQLSRV_FETCH_ASSOC))
	{
		
		$dn_t_dtn_code = $objResult_DTNSheetDetails['dn_t_dtn_code'];
		$ps_t_picking_code = $objResult_DTNSheetDetails['ps_t_picking_code'];
		$ps_t_pallet_code = $objResult_DTNSheetDetails['ps_t_pallet_code'];
		$ps_t_tags_code = $objResult_DTNSheetDetails['ps_t_tags_code'];
		$ps_t_fg_code_gdj = $objResult_DTNSheetDetails['ps_t_fg_code_gdj'];
		$bom_fg_desc = $objResult_DTNSheetDetails['bom_fg_desc'];
		$bom_ctn_code_normal = $objResult_DTNSheetDetails['bom_ctn_code_normal'];
		$repn_unit_type = $objResult_DTNSheetDetails['ps_t_replenish_unit_type'];
		$repn_order_type = $objResult_DTNSheetDetails['ps_t_order_type'];
		$repn_part_customer = $objResult_DTNSheetDetails['ps_t_part_customer'];
		$ps_t_location = $objResult_DTNSheetDetails['ps_t_location'];
		$ps_t_pj_name = $objResult_DTNSheetDetails['ps_t_pj_name'];
		$ps_t_tags_packing_std = $objResult_DTNSheetDetails['ps_t_tags_packing_std'];
		$ps_t_replenish_qty_to_pack = $objResult_DTNSheetDetails['ps_t_replenish_qty_to_pack'];
		$ps_t_order_type = $objResult_DTNSheetDetails['ps_t_order_type'];
		$ps_t_terminal_name = $objResult_DTNSheetDetails['ps_t_terminal_name'];
		$ps_t_ref_replenish_code = $objResult_DTNSheetDetails['ps_t_ref_replenish_code'];
		$repn_qc_tags_code = $objResult_DTNSheetDetails['repn_qc_tags_code'];
        
        
        		//check DTN
        $strSql_chk_dtn_no = " 
        SELECT [dn_t_dtn_code]
        ,[dn_h_status]
        ,[ps_t_picking_code]
        ,[ps_t_ref_replenish_code]
        ,[ps_t_pallet_code]
        ,[ps_t_tags_code]
        ,[ps_t_fg_code_gdj]
        ,[bom_ctn_code_normal]
        ,[repn_unit_type]
        ,[repn_order_type]
        ,[repn_part_customer]
        ,[ps_t_location]
        ,[ps_t_tags_packing_std]
        ,[ps_t_cus_name]
        ,[ps_t_pj_name]
        ,[ps_t_replenish_unit_type]
        ,[ps_t_replenish_qty_to_pack]
        ,[ps_t_terminal_name]
        ,[ps_t_order_type]
        ,[ps_t_status]
        ,[ps_t_issue_date]
        ,[repn_qc_tags_code]
        FROM tbl_dn_head
        left join
        tbl_dn_tail
        on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
        left join
        tbl_picking_tail
        on tbl_dn_tail.dn_t_picking_code = tbl_picking_tail.ps_t_picking_code
        left join
        tbl_replenishment
        on tbl_picking_tail.ps_t_ref_replenish_code = tbl_replenishment.repn_running_code
        left join tbl_bom_mst 
        on tbl_replenishment.repn_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
        and tbl_replenishment.repn_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
        and tbl_replenishment.repn_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
        and tbl_replenishment.repn_pj_name = tbl_bom_mst.bom_pj_name
        and tbl_replenishment.repn_ship_type = tbl_bom_mst.bom_ship_type
        and tbl_replenishment.repn_part_customer = tbl_bom_mst.bom_part_customer
        left join
        tbl_replenishment_qc
        on tbl_picking_tail.ps_t_fg_code_gdj = tbl_replenishment_qc.repn_qc_tags_code
        and tbl_dn_head.dn_h_driver_code = tbl_replenishment_qc.repn_qc_driver_code
        and tbl_dn_head.dn_h_dtn_code = tbl_replenishment_qc.repn_qc_dtn_code
        where
        [ps_t_pj_name] = '$iden_pj_name'
        and
        [dn_h_driver_code] = '$iden_txt_scn_driver_id'
        and
        [dn_t_dtn_code] = '$iden_txt_scn_dtn_id'
        and
        [ps_t_tags_code] = '$ps_t_tags_code'
        and
        [dn_h_status] = 'Delivery Transfer Note'
        ";
        
        $objQuery_chk_dtn_no = sqlsrv_query($db_con, $strSql_chk_dtn_no, $params, $options);
        $num_row_chk_dtn_no = sqlsrv_num_rows($objQuery_chk_dtn_no);
        $objResult_chk_dtn_no = sqlsrv_fetch_array($objQuery_chk_dtn_no, SQLSRV_FETCH_ASSOC);
        
        $ps_t_fg_code_gdj = $objResult_chk_dtn_no['ps_t_fg_code_gdj'];
        $repn_part_customer = $objResult_chk_dtn_no['repn_part_customer'];
        
        //null
    if($num_row_chk_dtn_no == 0)
    {	
        echo "NG";
    }
     else
    {
        //check tags ID is scan completed
        $strSql_chk_tags_scanned = " 
        	SELECT [repn_qc_driver_code]
        		   ,[repn_qc_dtn_code]
        		   ,[repn_qc_tags_code]
        		   ,[repn_qc_fg_code_gdj]
        		   ,[repn_qc_part_customer]
        	FROM tbl_replenishment_qc
        	where
        	[repn_qc_driver_code] = '$iden_txt_scn_driver_id'
        	and
        	[repn_qc_dtn_code] = '$iden_txt_scn_dtn_id'
        	and
        	[repn_qc_tags_code] = '$ps_t_tags_code'
          ";
        $objQuery_chk_tags_scanned = sqlsrv_query($db_con, $strSql_chk_tags_scanned, $params, $options);
        $num_row_chk_tags_scanned = sqlsrv_num_rows($objQuery_chk_tags_scanned);
        
        //true
        if($num_row_chk_tags_scanned == 1)
        {
        	echo "DUL";
        }
        else
        {
        	//qc check insert pre-tags
        	$sqlInsQcCheck = " 
        	INSERT INTO [dbo].[tbl_replenishment_qc]
        		   (
        		   [repn_qc_driver_code]
        		   ,[repn_qc_dtn_code]
        		   ,[repn_qc_tags_code]
        		   ,[repn_qc_fg_code_gdj]
        		   ,[repn_qc_part_customer]
        		   ,[repn_qc_by]
        		   ,[repn_qc_date]
        		   ,[repn_qc_time]
        		   ,[repn_qc_datetime]
        		   )
        	 VALUES
        		   (
        		   '$iden_txt_scn_driver_id'
        		   ,'$iden_txt_scn_dtn_id'
        		   ,'$ps_t_tags_code'
        		   ,'$ps_t_fg_code_gdj'
        		   ,'$repn_part_customer'
        		   ,'$iden_txt_scn_driver_id'
        		   ,'$buffer_date'
        		   ,'$buffer_time'
        		   ,'$buffer_datetime'
        		   )
        	";
        	$result_sqlInsQcCheck = sqlsrv_query($db_con, $sqlInsQcCheck);
        	
        	if($result_sqlInsQcCheck)
        	{
        		echo "OK";
        	}
        }
		
	}
}
        
        
        
        //update status for control process flow
        //tbl_dn_head, tbl_dn_tail ==> Confirmed
        //tbl_receive ==> Project Name (ex. TSESA)
        
        //update 
$sqlUpdateDTN_h = " 
UPDATE 
tbl_dn_head 
SET
[dn_h_driver_sign] = '$iden_driver_sign'
,[dn_h_cus_sign] = '$iden_cus_sign'
,[dn_h_receive_date] = '$buffer_date'
,[dn_h_receive_time] = '$buffer_time'
,[dn_h_receive_datetime] = '$buffer_datetime'
,dn_h_status = 'Confirmed' 
WHERE 
dn_h_dtn_code = '$iden_txt_scn_dtn_id' 
and 
dn_h_driver_code = '$iden_txt_scn_driver_id' 
and 
dn_h_status = 'Delivery Transfer Note' 
";

$result_sqlUpdateDTN_h = sqlsrv_query($db_con, $sqlUpdateDTN_h);

if($result_sqlUpdateDTN_h)
{
	//update 
	$sqlUpdateDTN_t = " UPDATE tbl_dn_tail SET dn_t_status = 'Confirmed' WHERE dn_t_dtn_code = '$iden_txt_scn_dtn_id' and dn_t_status = 'Delivery Transfer Note' ";
	$result_sqlUpdateDTN_t = sqlsrv_query($db_con, $sqlUpdateDTN_t);
	
	if($result_sqlUpdateDTN_t)
	{
		//read tbl_picking_tail for update tags ID to Project Name (ex. TSESA)
		$strSql_get_dtn_details = " 
         SELECT [dn_t_dtn_code]
                ,[dn_h_status]
                ,[ps_t_picking_code]
                ,[ps_t_ref_replenish_code]
                ,[ps_t_pallet_code]
                ,[ps_t_tags_code]
                ,[ps_t_fg_code_gdj]
                ,[bom_part_customer]
                ,[bom_fg_code_set_abt]
                ,[bom_sku_code]
                ,[bom_price_sale_per_pcs]
                ,[ps_t_location]
                ,[ps_t_tags_packing_std]
                ,[ps_t_cus_name]
                ,[ps_t_pj_name]
                ,[ps_t_replenish_unit_type]
                ,[ps_t_replenish_qty_to_pack]
                ,[ps_t_terminal_name]
                ,[ps_t_order_type]
                ,[bom_ship_type]
                ,[ps_t_status]
                ,[ps_t_issue_date]
            FROM tbl_dn_head
            left join
            tbl_dn_tail
            on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
            left join
            tbl_picking_tail
            on tbl_dn_tail.dn_t_picking_code = tbl_picking_tail.ps_t_picking_code
            left join
            tbl_replenishment
            on tbl_picking_tail.ps_t_ref_replenish_code = tbl_replenishment.repn_running_code
            left join tbl_bom_mst 
            on tbl_replenishment.repn_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
            and tbl_replenishment.repn_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
            and tbl_replenishment.repn_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
            and tbl_replenishment.repn_pj_name = tbl_bom_mst.bom_pj_name
            and tbl_replenishment.repn_ship_type = tbl_bom_mst.bom_ship_type
            and tbl_replenishment.repn_part_customer = tbl_bom_mst.bom_part_customer
            where
			[dn_t_dtn_code] = '$iden_txt_scn_dtn_id' 
		";
		$objQuery_get_dtn_details = sqlsrv_query($db_con, $strSql_get_dtn_details, $params, $options);
		$num_row_get_dtn_details = sqlsrv_num_rows($objQuery_get_dtn_details);

		while($objResult_get_dtn_details = sqlsrv_fetch_array($objQuery_get_dtn_details, SQLSRV_FETCH_ASSOC))
		{
			$ps_t_picking_code = $objResult_get_dtn_details['ps_t_picking_code'];
			$ps_t_tags_code = $objResult_get_dtn_details['ps_t_tags_code'];
      $ps_t_fg_code_gdj = $objResult_get_dtn_details['ps_t_fg_code_gdj'];
      $bom_sku_code = $objResult_get_dtn_details['bom_sku_code']; 
      $bom_fg_code_set_abt = $objResult_get_dtn_details['bom_fg_code_set_abt']; 
      $bom_ship_type = $objResult_get_dtn_details['bom_ship_type'];
      $bom_part_customer = $objResult_get_dtn_details['bom_part_customer'];
      $bom_price_sale_per_pcs = $objResult_get_dtn_details['bom_price_sale_per_pcs'];
      $ps_t_pj_name = $objResult_get_dtn_details['ps_t_pj_name'];
      $ps_t_replenish_unit_type = $objResult_get_dtn_details['ps_t_replenish_unit_type']; 

			//update tbl_receive - receive_status ==> Project Name (ex. TSESA)
			$sqlUpdateConfRefill = " UPDATE tbl_receive SET receive_status = '$iden_pj_name' WHERE receive_tags_code = '$ps_t_tags_code' and receive_status = 'Delivery Transfer Note' ";
			$result_sqlUpdateConfRefill = sqlsrv_query($db_con, $sqlUpdateConfRefill);

      if($result_sqlUpdateConfRefill){

        $Sql_Insert_Usage = "INSERT INTO tbl_usage_conf
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
          '$ps_t_tags_code'
          ,'$bom_fg_code_set_abt'
          ,'$bom_sku_code'
          ,'$ps_t_fg_code_gdj'
          ,'$bom_ship_type'
          ,'$bom_part_customer'
          ,'$ps_t_pj_name'
          ,'$bom_price_sale_per_pcs'
          ,'$ps_t_replenish_unit_type'
          ,'$t_cur_user_code_VMI_GDJ'
          ,'$buffer_date'
          ,'$buffer_time'
          ,'$buffer_datetime'
        )
        ";

        //echo  $Sql_Insert_Usage; exit();
        $Result_Nnsert_Usage = sqlsrv_query($db_con, $Sql_Insert_Usage);
        
        if($Result_Nnsert_Usage)
        {
         
        $sql_cut_inventory = " UPDATE tbl_receive SET receive_status = 'USAGE CONFIRM' WHERE receive_tags_code = '$ps_t_tags_code' and receive_status = 'Delivery Transfer Note' ";
        $result_cut_inventory = sqlsrv_query($db_con, $sql_cut_inventory);

        }

      }
          
		}
	}
}

$Sql_Get_Part_Repen = "SELECT T3.ps_t_fg_code_gdj,T4.repn_part_customer,T5.bom_snp,
T5.bom_packing,T4.repn_fg_code_set_abt, T4.repn_sku_code_abt,T4.repn_ship_type
FROM tbl_dn_head T1
LEFT JOIN tbl_dn_tail T2 ON T1.dn_h_dtn_code = T2.dn_t_dtn_code
LEFT JOIN tbl_picking_tail T3 ON T2.dn_t_picking_code = T3.ps_t_picking_code
LEFT JOIN tbl_replenishment T4 ON T3.ps_t_ref_replenish_code = T4.repn_running_code
LEFT JOIN tbl_bom_mst T5 ON T4.repn_fg_code_set_abt = T5.bom_fg_code_set_abt
AND T4.repn_sku_code_abt = T5.bom_fg_sku_code_abt
AND T4.repn_fg_code_gdj = T5.bom_fg_code_gdj
AND T4.repn_pj_name = T5.bom_pj_name
AND T4.repn_ship_type = T5.bom_ship_type
AND T4.repn_part_customer = T5.bom_part_customer
LEFT JOIN tbl_replenishment_qc T6 ON T3.ps_t_tags_code = T6.repn_qc_tags_code
AND T1.dn_h_driver_code = T6.repn_qc_driver_code
AND T1.dn_h_dtn_code = T6.repn_qc_dtn_code
WHERE dn_t_dtn_code = '$iden_txt_scn_dtn_id' AND dn_h_driver_code = '$iden_txt_scn_driver_id'
GROUP BY T3.ps_t_fg_code_gdj,T4.repn_part_customer,T5.bom_snp,
T5.bom_packing,T4.repn_fg_code_set_abt, T4.repn_sku_code_abt,T4.repn_ship_type";
            
$Result_Get_Part_Repen = sqlsrv_query($db_con, $Sql_Get_Part_Repen);
if($Result_Get_Part_Repen == false) 
{
    http_response_code(200);
    echo json_encode(array("tStatus" => "0","tStatus_Text" => "Error SP","tError_Focus" => ""));
    die(print_r(sqlsrv_errors(), true));
}
else
{
    $Num_Row_Get_Part_Repen = sqlsrv_num_rows($Result_Get_Part_Repen);
    if ($Num_Row_Get_Part_Repen == 0) 
    {
        echo json_encode(array("Status" => "0","Status_Text" => "No Data"));
        exit();
    }
    while($Obj_Result_Part_Repen = sqlsrv_fetch_array($Result_Get_Part_Repen, SQLSRV_FETCH_ASSOC))
    {
        $part_FG_GDJ = $Obj_Result_Part_Repen['ps_t_fg_code_gdj'];
        $part_customer = $Obj_Result_Part_Repen['repn_part_customer'];
        $fg_code_set_abt = $Obj_Result_Part_Repen['repn_fg_code_set_abt'];
        $sku_code_abt = $Obj_Result_Part_Repen['repn_sku_code_abt'];
        $ship_type = $Obj_Result_Part_Repen['repn_ship_type'];
        $bom_snp = $Obj_Result_Part_Repen['bom_snp'];
        $bom_packing = $Obj_Result_Part_Repen['bom_packing'];

        $Sql_VMI_Stock = "SELECT T1.bom_fg_code_gdj,T1.bom_fg_code_set_abt,
        REPLACE(T1.bom_fg_sku_code_abt, ' ', '') AS bom_fg_sku_code_abt,
        CASE WHEN (T3.receive_status = '$Project_Name') THEN ISNULL(SUM(T2.tags_packing_std), 0) 
        ELSE 0 END AS Stocm_VMI
        FROM tbl_bom_mst T1
        LEFT JOIN tbl_tags_running T2 ON T1.bom_fg_code_gdj = T2.tags_fg_code_gdj                 
        LEFT JOIN tbl_receive T3 ON T2.tags_code = T3.receive_tags_code
        WHERE  T1.bom_part_customer = '$part_customer' 
        AND T1.bom_pj_name = '$Project_Name' AND T3.receive_status = '$Project_Name' 
        AND T1.bom_status = 'Active' AND T1.bom_ship_type = '$ship_type' 
        AND T1.bom_snp = '$bom_snp' 
        AND T1.bom_fg_code_gdj = '$part_FG_GDJ'
        AND T1.bom_fg_sku_code_abt = '$sku_code_abt'
        GROUP BY T1.bom_fg_sku_code_abt,T1.bom_fg_code_gdj,T1.bom_fg_code_set_abt,T3.receive_status";
        //echo $Sql_VMI_Stock;exit();
        $objQuery_VMI_Stock = sqlsrv_query($db_con, $Sql_VMI_Stock);        
        while($objResult_VMI_Stock = sqlsrv_fetch_array($objQuery_VMI_Stock, SQLSRV_FETCH_ASSOC))
        {
            $Stock_VMI = $objResult_VMI_Stock["Stocm_VMI"];
        }

        $Sql_Bom_Min_Max = "SELECT bom_vmi_min,bom_vmi_max
        FROM tbl_bom_mst
        WHERE bom_fg_code_set_abt = '$fg_code_set_abt'
        AND bom_fg_sku_code_abt = '$sku_code_abt'
        AND bom_fg_code_gdj = '$part_FG_GDJ'
        AND bom_pj_name = '$Project_Name'
        AND bom_ship_type = '$ship_type'
        AND bom_part_customer = '$part_customer'";
        $objQuery_Bom_Min_Max = sqlsrv_query($db_con, $Sql_Bom_Min_Max);         
        while($objResult_Bom_Min_Max = sqlsrv_fetch_array($objQuery_Bom_Min_Max, SQLSRV_FETCH_ASSOC))
        {
            $Bom_Min = $objResult_Bom_Min_Max["bom_vmi_min"];
            $Bom_Max = $objResult_Bom_Min_Max["bom_vmi_max"]; 
        }
        if ($Stock_VMI <= $Bom_Min)
        {
            $Format_Date = str_replace('-', '/', $buffer_date);
            $Delivery_Date = date('Y-m-d',strtotime($Format_Date . "+5 days"));

            $Qty_Repen = ($Bom_Max - $Stock_VMI);
            $sql_Insert_Auto_Min = "INSERT INTO tbl_replenishment
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
                ,'$part_FG_GDJ'
                ,'$Project_Name'
                ,'$ship_type'
                ,'$part_customer'
                ,'$bom_snp'
                ,'$Qty_Repen'
                ,'Component'
                ,'$Project_Name'
                ,'VMI Order'
                ,'$Project_Name'
                ,'$buffer_date'
                ,'$buffer_time'
                ,'$buffer_datetime'
                ,'$Delivery_Date'
               )";
            $Result_Insert_Auto_Min = sqlsrv_query($db_con, $sql_Insert_Auto_Min);
        }
        else
        {
              
        }
    }
}
echo json_encode(array("Status" => "1","Status_Text" => "Confirm Complete"));

sqlsrv_close($db_con);
?>