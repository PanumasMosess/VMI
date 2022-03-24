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
$iden_txt_scn_picking_id = isset($_POST['iden_txt_scn_picking_id']) ? $_POST['iden_txt_scn_picking_id'] : '';
$iden_txt_cover_id = isset($_POST['iden_txt_cover_id']) ? $_POST['iden_txt_cover_id'] : '';
$iden_txt_qc_scn_tag_id = isset($_POST['iden_txt_qc_scn_tag_id']) ? $_POST['iden_txt_qc_scn_tag_id'] : '';

//concat
$str_concat_cov = $iden_txt_scn_picking_id."_".$iden_txt_cover_id;

//check picking qc
$strSql_chk_picking_no = "
	SELECT 
	  [ps_t_picking_code]
	  ,[ps_t_ref_replenish_code]
	  ,[ps_t_pallet_code]
	  ,[ps_t_tags_code]
	  ,[ps_t_fg_code_set_abt]
      ,[ps_t_sku_code_abt]
	  ,[ps_t_fg_code_gdj]
	  ,[bom_fg_desc]
	  ,[ps_t_location]
	  ,[ps_t_tags_packing_std]
	  ,[ps_t_cus_name]
	  ,[ps_t_pj_name]
	  ,[ps_t_replenish_unit_type]
	  ,[ps_t_replenish_qty_to_pack]
	  ,[ps_t_terminal_name]
	  ,[ps_t_order_type]
	  ,[ps_t_part_customer]
	  ,[ps_t_status]
	  ,[ps_h_qc]
	  ,[ps_t_issue_date]
	  ,[pick_pre_qc_cover_code]
	  ,[pick_pre_qc_tags_code]
  FROM [tbl_picking_head]
  left join tbl_picking_tail
  on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
  left join tbl_bom_mst
  on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
  and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
  and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
  and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
  and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
  and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
  left join tbl_picking_tail_pre_qc
  on tbl_picking_tail.ps_t_tags_code = tbl_picking_tail_pre_qc.pick_pre_qc_tags_code
  where
  [ps_t_picking_code] = '$iden_txt_scn_picking_id'
  and
  [ps_t_tags_code] = '$iden_txt_qc_scn_tag_id'
  and
  [ps_t_status] = 'Picking'
  and 
  [ps_h_qc] is NULL
  and bom_status = 'Active'
  ";
$objQuery_chk_picking_no = sqlsrv_query($db_con, $strSql_chk_picking_no, $params, $options);
$num_row_chk_picking_no = sqlsrv_num_rows($objQuery_chk_picking_no);
$objResult_chk_picking_no = sqlsrv_fetch_array($objQuery_chk_picking_no, SQLSRV_FETCH_ASSOC);

$ps_t_fg_code_gdj = $objResult_chk_picking_no['ps_t_fg_code_gdj'];
$ps_t_part_customer = $objResult_chk_picking_no['ps_t_part_customer'];

//null
if($num_row_chk_picking_no == 0)
{	
	echo "NG";
}
else
{
	//check tags ID is scan completed
	$strSql_chk_tags_scanned = " 
		SELECT [pick_pre_qc_tags_code]
		FROM tbl_picking_tail_pre_qc
		where
		[pick_pre_qc_picking_code] = '$iden_txt_scn_picking_id'
		and
		[pick_pre_qc_tags_code] = '$iden_txt_qc_scn_tag_id'
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
		INSERT INTO [dbo].[tbl_picking_tail_pre_qc]
		   (
			[pick_pre_qc_cover_code]
			,[pick_pre_qc_picking_code]
			,[pick_pre_qc_tags_code]
			,[pick_pre_qc_fg_code_gdj]
			,[pick_pre_qc_part_customer]
			,[pick_pre_qc_by]
			,[pick_pre_qc_date]
			,[pick_pre_qc_time]
			,[pick_pre_qc_datetime]
		   )
		VALUES
		   (
			'$str_concat_cov'
			,'$iden_txt_scn_picking_id'
			,'$iden_txt_qc_scn_tag_id'
			,'$ps_t_fg_code_gdj'
			,'$ps_t_part_customer'
			,'$t_cur_user_code_VMI_GDJ'
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