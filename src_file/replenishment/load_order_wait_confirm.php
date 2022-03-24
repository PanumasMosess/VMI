<?
require_once("../../application.php");

$strSql_wait_confirm = "SELECT [repn_id]
,[repn_running_code]
,[repn_order_ref]
,[repn_fg_code_set_abt]
,[repn_sku_code_abt]
,[repn_fg_code_gdj]
,[repn_pj_name]
,[repn_ship_type]
,[repn_part_customer]
,[repn_snp]
,[repn_qty]
,[repn_unit_type]
,[repn_terminal_name]
,[repn_order_type]
,[repn_delivery_date]
,[repn_by]
,[repn_date]
,[repn_time]
,[repn_datetime]
,[repn_conf_status]
,[repn_conf_remark]
,[repn_conf_by]
,[repn_conf_date]
,[repn_conf_time]
,[repn_conf_datetime]
,bom_cus_code, bom_ship_type, bom_part_customer, bom_packing, bom_pj_name, bom_fg_code_gdj
FROM [tbl_replenishment]
left join tbl_bom_mst 
on tbl_replenishment.repn_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
and tbl_replenishment.repn_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
and tbl_replenishment.repn_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
and tbl_replenishment.repn_pj_name = tbl_bom_mst.bom_pj_name
and tbl_replenishment.repn_ship_type = tbl_bom_mst.bom_ship_type
and tbl_replenishment.repn_part_customer = tbl_bom_mst.bom_part_customer
where repn_conf_status is null and repn_order_ref like '%(deposit)%'";
$objQuery = sqlsrv_query($db_con, $strSql_wait_confirm);


$row_id = 0;
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;
    $json_array_ = array(
        "row_id" => $row_id,
        "repn_id" => $objResult['repn_id'],
        "repn_order_ref" => $objResult['repn_order_ref'],
        "repn_sku_code_abt" => $objResult['repn_sku_code_abt'],
		"repn_fg_code_set_abt" => $objResult['repn_fg_code_set_abt'],
		"repn_qty" => $objResult['repn_qty'],
        "repn_unit_type" => $objResult['repn_unit_type'],
		"repn_terminal_name" => $objResult['repn_terminal_name'],
		"repn_order_type" => $objResult['repn_order_type'],
        "repn_delivery_date" => $objResult['repn_delivery_date'],
        "repn_by" => $objResult['repn_by'],
        "repn_date" => $objResult['repn_date'],
        "repn_time" => $objResult['repn_time'],
        "bom_fg_code_gdj" => $objResult['bom_fg_code_gdj'],
        "bom_cus_code" => $objResult['bom_cus_code'],
        "bom_pj_name" => $objResult['bom_pj_name'],
        "bom_ship_type" => $objResult['bom_ship_type'],
        "bom_packing" => $objResult['bom_packing'],
        "bom_part_customer" => $objResult['bom_part_customer'],
        "repn_datetime" => $objResult['repn_datetime'],
        "repn_conf_status" => $objResult['repn_conf_status'],
        "repn_datetime_cut" => substr($objResult['repn_datetime'], 0, 19)
    );
	
    array_push($json, $json_array_);
}
echo json_encode($json);
?>

