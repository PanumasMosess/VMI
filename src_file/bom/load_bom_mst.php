<?
require_once("../../application.php");


$buffer_date = date("Y-m-d");

$strSql = " select * from tbl_bom_mst where bom_status = 'Active' order by bom_issue_datetime desc ";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;
    $inround__ = array(
        "no" => $row_id,
        "bom_id" => $objResult['bom_id'],
		"bom_fg_code_set_abt" => $objResult['bom_fg_code_set_abt'],
		"bom_fg_sku_code_abt" => $objResult['bom_fg_sku_code_abt'],
		"bom_fg_code_gdj" => $objResult['bom_fg_code_gdj'],
		"bom_fg_desc" => $objResult['bom_fg_desc'],
		"bom_cus_code" => $objResult['bom_cus_code'],
		"bom_cus_name" => $objResult['bom_cus_name'],
		"bom_pj_name" => $objResult['bom_pj_name'],
		"bom_ctn_code_normal" => $objResult['bom_ctn_code_normal'],
		"bom_snp" => $objResult['bom_snp'],
		"bom_sku_code" => $objResult['bom_sku_code'],
		"bom_ship_type" => $objResult['bom_ship_type'],
		"bom_pckg_type" => $objResult['bom_pckg_type'],
		"bom_dims_w" => $objResult['bom_dims_w'],
		"bom_dims_l" => $objResult['bom_dims_l'],
		"bom_dims_h" => $objResult['bom_dims_h'],
		"bom_usage" => $objResult['bom_usage'],
		"bom_space_paper" => $objResult['bom_space_paper'],
		"bom_flute" => $objResult['bom_flute'],
		"bom_packing" => $objResult['bom_packing'],
		"bom_wms_min" => $objResult['bom_wms_min'],
		"bom_wms_max" => $objResult['bom_wms_max'],
		"bom_vmi_min" => $objResult['bom_vmi_min'],
		"bom_vmi_max" => $objResult['bom_vmi_max'],
		"bom_vmi_app" => $objResult['bom_vmi_app'],
		"bom_part_customer" => $objResult['bom_part_customer'],
		"bom_cost_per_pcs" => $objResult['bom_cost_per_pcs'],
		"bom_price_sale_per_pcs" => $objResult['bom_price_sale_per_pcs'],
		"bom_status" => $objResult['bom_status'],
		"bom_issue_by" => $objResult['bom_issue_by'],
		"bom_issue_date" => $objResult['bom_issue_date'],
		"bom_issue_time" => $objResult['bom_issue_time'],
		"bom_issue_datetime" => substr($objResult['bom_issue_datetime'],0,19)
    );

    array_push($json, $inround__);

}
		
    echo json_encode($json);
	
?>

				