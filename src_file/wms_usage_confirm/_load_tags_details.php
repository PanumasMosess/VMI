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
$t_fg_code = isset($_POST['t_fg_code']) ? $_POST['t_fg_code'] : '';
$t_part_customer = isset($_POST['t_part_customer']) ? $_POST['t_part_customer'] : '';
$t_pj_name = isset($_POST['t_pj_name']) ? $_POST['t_pj_name'] : '';

$strSql = " SELECT	
	  [tags_code]
      ,[tags_fg_code_gdj]
	  ,[receive_status]
	  ,[tags_packing_std]
	  ,[bom_sku_code]
	  ,[bom_part_customer]
	FROM tbl_receive
	left join tbl_tags_running
	on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
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
	tbl_receive.receive_status = '$t_pj_name'
	and tbl_bom_mst.bom_fg_code_gdj = '$t_fg_code'
	and tbl_bom_mst.bom_part_customer = '$t_part_customer'
	group by 
	[tags_code]
      ,[tags_fg_code_gdj]
	  ,[receive_status]
	  ,[tags_packing_std]
	  ,[bom_sku_code]
	  ,[bom_part_customer]
	order by tbl_tags_running.tags_code asc
";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;

    $json_array_ = array(
        "row_no" => $row_id,
        "tags_code" => $objResult['tags_code'],
		"tags_endcode" => var_encode($objResult['tags_code']),
		"tags_fg_code_gdj" => $objResult['tags_fg_code_gdj'],
		"receive_status" => $objResult['receive_status'],
		"tags_packing_std" => $objResult['tags_packing_std'],
        "bom_sku_code" => $objResult['bom_sku_code'],
		"bom_part_customer" => $objResult['bom_part_customer']
    );
	
    array_push($json, $json_array_);
}
	
echo json_encode($json);
?>