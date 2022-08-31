<?
require_once("../../application.php");
require_once("../../get_authorized.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$t_cur_user_section_VMI_GDJ = isset($_SESSION['t_cur_user_section_VMI_GDJ']) ? $_SESSION['t_cur_user_section_VMI_GDJ'] : '';

/**********************************************************************************/
/*var *****************************************************************************/
$str_user_type = isset($_POST['str_user_type']) ? $_POST['str_user_type'] : '';

//check permission
if($str_user_type == "Administrator")
{
	$sql_get_type = " (receive_status != 'USAGE CONFIRM' and receive_status != 'Received' and receive_status != 'Picking' and
		receive_status != 'Delivery Transfer Note' and bom_status = 'Active') ";
}
else
{
	$sql_get_type = " (receive_status != 'USAGE CONFIRM' and receive_status != 'Received' and receive_status != 'Picking' and
		receive_status != 'Delivery Transfer Note' and bom_status = 'Active')  
		and (ps_t_pj_name IN (select bom_pj_name from tbl_bom_mst where bom_cus_code = '$t_cur_user_section_VMI_GDJ' GROUP BY bom_pj_name)) ";
}
	
$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

$strSql = " SELECT	
		ps_t_fg_code_gdj,
		SUM(ps_t_tags_packing_std) as total_QTY,
		ps_t_part_customer,
		receive_status,
		ps_t_pj_name,
		bom_sku_code
		FROM tbl_receive
		left join tbl_picking_tail
		on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
		left join tbl_usage_conf
		on tbl_usage_conf.usage_tags_code = tbl_receive.receive_tags_code
		left join tbl_picking_head
		on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
		left join tbl_dn_tail 
		on tbl_picking_head.ps_h_picking_code = tbl_dn_tail.dn_t_picking_code
		left join tbl_dn_head
		on tbl_dn_tail.dn_t_dtn_code = tbl_dn_head.dn_h_dtn_code	
		left join tbl_bom_mst 
		on tbl_bom_mst.bom_fg_code_set_abt = tbl_picking_tail.ps_t_fg_code_set_abt
		and tbl_bom_mst.bom_fg_sku_code_abt = tbl_picking_tail.ps_t_sku_code_abt
		and tbl_bom_mst.bom_pj_name = tbl_picking_tail.ps_t_pj_name
		and tbl_bom_mst.bom_fg_code_gdj = tbl_picking_tail.ps_t_fg_code_gdj
		and tbl_bom_mst.bom_part_customer = tbl_picking_tail.ps_t_part_customer
		and tbl_bom_mst.bom_ship_type = tbl_picking_tail.ps_t_ship_type
		where  
		$sql_get_type 
		group by 
		ps_t_fg_code_gdj,
		ps_t_part_customer,
		receive_status,
		ps_t_pj_name,
		bom_sku_code
		order by 
		ps_t_fg_code_gdj asc
";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;

    $json_array_ = array(
        "row_no" => $row_id,
        "ps_t_fg_code_gdj" => $objResult['ps_t_fg_code_gdj'],
		"var_encode_ps_t_fg_code_gdj" => var_encode($objResult['ps_t_fg_code_gdj']),
        "total_QTY" => $objResult['total_QTY'],
		"ps_t_part_customer" => $objResult['ps_t_part_customer'],
		"var_encode_ps_t_part_customer" => var_encode($objResult['ps_t_part_customer']),
		"receive_status" => $objResult['receive_status'],
        "ps_t_pj_name" => $objResult['ps_t_pj_name'],
		"var_encode_ps_t_pj_name" => var_encode($objResult['ps_t_pj_name']),
		"bom_sku_code" => $objResult['bom_sku_code'],
		"var_encode_bom_sku_code" => var_encode($objResult['bom_sku_code'])
    );
	
    array_push($json, $json_array_);
}
	
echo json_encode($json);
?>