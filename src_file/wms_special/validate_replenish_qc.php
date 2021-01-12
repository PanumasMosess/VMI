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
$iden_txt_qc_scn_tag_id = isset($_POST['iden_txt_qc_scn_tag_id']) ? $_POST['iden_txt_qc_scn_tag_id'] : '';

//check qty pre-cart 
//หา pack ทั้งหมด นำมาลบทุกรอบที่มีการสแกน tags id จนค่าเหลือเท่ากับ 0 ก็ แจ้งเตือนว่า ครบแล้ว



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
[ps_t_tags_code] = '$iden_txt_qc_scn_tag_id'
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
		[repn_qc_tags_code] = '$iden_txt_qc_scn_tag_id'
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
			   ,'$iden_txt_qc_scn_tag_id'
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

sqlsrv_close($db_con);
?>