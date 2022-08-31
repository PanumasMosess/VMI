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

$str_split_TagsProject = explode(',', $var_arr_list);
$str_count_arr = count($str_split_TagsProject);
$i_rows = 0 ;
$str_tags = "";

$json = array();

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
	$objResult = sqlsrv_fetch_array($objQuery_get_data, SQLSRV_FETCH_ASSOC);

    $json_array_detail = array(
        "tags_code" => $objResult['tags_code'],
		"tags_fg_code_gdj" => $objResult['tags_fg_code_gdj'],
		"ps_t_part_customer" => $objResult['ps_t_part_customer'],
		"tags_fg_code_gdj_desc" => $objResult['tags_fg_code_gdj_desc'],
		"tags_packing_std" => $objResult['tags_packing_std'],
		"bom_price_sale_per_pcs" => $objResult['bom_price_sale_per_pcs'],
		"bom_snp" => $objResult['bom_snp'],
    );
	
    array_push($json, $json_array_detail);
	
}

echo json_encode($json);

sqlsrv_close($db_con);
?>