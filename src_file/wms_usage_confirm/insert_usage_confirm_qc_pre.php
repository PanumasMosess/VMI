<?
require_once("../../application.php");
require_once("../../get_authorized.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$t_cur_user_section_VMI_GDJ = isset($_SESSION['t_cur_user_section_VMI_GDJ']) ? $_SESSION['t_cur_user_section_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$txt_usage_conf_pj = isset($_POST['txt_usage_conf_pj']) ? $_POST['txt_usage_conf_pj'] : '';
$txt_usage_conf_fg_code_gdj = isset($_POST['txt_usage_conf_part_name']) ? $_POST['txt_usage_conf_part_name'] : '';
$txt_usage_conf_scan_tags = isset($_POST['txt_usage_conf_scan_tags']) ? $_POST['txt_usage_conf_scan_tags'] : '';

//Check not match
$strSQL = " 
SELECT 
	tags_code,
	tags_fg_code_gdj,
	receive_status,
	ps_t_fg_code_set_abt,
	ps_t_sku_code_abt,
	ps_t_ship_type,
	ps_t_part_customer,
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
T1.receive_tags_code = '$txt_usage_conf_scan_tags' 
AND T1.receive_status = '$txt_usage_conf_pj' 
AND T4.bom_status = 'Active'
";
$objQuery = sqlsrv_query($db_con, $strSQL);
$result = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC);

if($result)//Check not match false
{
	//Check duplicate
	$strSQL_wrong_model = " 
	SELECT 
		tags_code,
		tags_fg_code_gdj,
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
	T1.receive_tags_code = '$txt_usage_conf_scan_tags' 
	AND T2.tags_fg_code_gdj = '$txt_usage_conf_fg_code_gdj' 
	AND T1.receive_status = '$txt_usage_conf_pj' 
	AND T4.bom_status = 'Active'
	";
	$objQuery_wrong_model = sqlsrv_query($db_con, $strSQL_wrong_model);
	$result_wrong_model = sqlsrv_fetch_array($objQuery_wrong_model, SQLSRV_FETCH_ASSOC);
	
	if($result_wrong_model)//Check duplicate true
	{
		//Check duplicate
		$strSQL_dup = " 
		select * from tbl_usage_conf_qc 
		where 
		conf_qc_tags_code = '$txt_usage_conf_scan_tags' 
		and 
		conf_qc_fg_code_gdj = '$txt_usage_conf_fg_code_gdj' 
		and 
		conf_qc_terminal_name = '$txt_usage_conf_pj'
		";
		$objQuery_dup = sqlsrv_query($db_con, $strSQL_dup);
		$result_dup = sqlsrv_fetch_array($objQuery_dup, SQLSRV_FETCH_ASSOC);
		
		if($result_dup)//Check duplicate true
		{
			echo "duplicate";
		}
		else //OK
		{
			//insert tbl_usage_conf_qc
			$sqlInsert = "
			INSERT INTO [dbo].[tbl_usage_conf_qc]
				   (
				   [conf_qc_tags_code]
				   ,[conf_qc_fg_code_set_abt]
				   ,[conf_qc_sku_code_abt]
				   ,[conf_qc_fg_code_gdj]
				   ,[conf_qc_ship_type]
				   ,[conf_qc_snp]
				   ,[conf_qc_part_customer]
				   ,[conf_qc_terminal_name]
				   ,[conf_price_sale_per_pcs]
				   ,[conf_unit_type]
				   ,[conf_qc_by]
				   ,[conf_qc_date]
				   ,[conf_qc_time]
				   ,[conf_qc_datetime]
				   )
			 VALUES
				   (
				   '$txt_usage_conf_scan_tags'
				   ,'".$result_wrong_model['ps_t_fg_code_set_abt']."'
				   ,'".$result_wrong_model['ps_t_sku_code_abt']."'
				   ,'$txt_usage_conf_fg_code_gdj'
				   ,'".$result_wrong_model['ps_t_ship_type']."'
				   ,'".$result_wrong_model['bom_snp']."'
				   ,'".$result_wrong_model['ps_t_part_customer']."'
				   ,'$txt_usage_conf_pj' 
				   ,'".$result_wrong_model['bom_price_sale_per_pcs']."'
				   ,'Component'
				   ,'".$t_cur_user_code_VMI_GDJ."'
				   ,'$buffer_date'
				   ,'$buffer_time'
				   ,'$buffer_datetime'
				   )
		   ";
			$result_sqlInsert = sqlsrv_query($db_con, $sqlInsert);
		}
	}
	else //wrong model
	{
		echo "wrong model";
	}
}
else
{
	echo "not match";
}

sqlsrv_close($db_con);
?>