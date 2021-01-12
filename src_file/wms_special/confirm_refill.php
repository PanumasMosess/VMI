<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

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
			
			//update tbl_receive - receive_status ==> Project Name (ex. TSESA)
			$sqlUpdateConfRefill = " UPDATE tbl_receive SET receive_status = '$iden_pj_name' WHERE receive_tags_code = '$ps_t_tags_code' and receive_status = 'Delivery Transfer Note' ";
			$result_sqlUpdateConfRefill = sqlsrv_query($db_con, $sqlUpdateConfRefill);
		}
	}
}

sqlsrv_close($db_con);
?>