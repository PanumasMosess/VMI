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
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
$str_user_type = isset($_POST['str_user_type']) ? $_POST['str_user_type'] : '';

//check permission
if($str_user_type == "Administrator")
{
	$sql_get_type = " (receive_status != 'USAGE CONFIRM' and receive_status != 'Received' and receive_status != 'Picking' and
	receive_status != 'Delivery Transfer Note' and bom_status = 'Active') and";
}else if($str_user_type == "B2C")
{
	$sql_get_type = " (receive_status != 'USAGE CONFIRM' and receive_status != 'Received' and receive_status != 'Picking' and
		receive_status != 'Delivery Transfer Note' and bom_status = 'Active')  
		and (ps_t_pj_name IN (select bom_pj_name from tbl_bom_mst where bom_pj_name = 'OUTLET' GROUP BY bom_pj_name)) and ";
}
else
{
	$sql_get_type = " (receive_status != 'USAGE CONFIRM' and receive_status != 'Received' and receive_status != 'Picking' and
		receive_status != 'Delivery Transfer Note' and bom_status = 'Active')  
		and (ps_t_pj_name IN (select bom_pj_name from tbl_bom_mst where bom_cus_code = '$t_cur_user_section_VMI_GDJ' GROUP BY bom_pj_name)) and ";
}

$strSql = "
	SELECT	
	[bom_pj_name],[bom_cus_name]
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
	bom_pj_name LIKE '%".$searchTerm."%'
	and bom_pj_name is not null
	group by [bom_pj_name],[bom_cus_name]
	order by [bom_pj_name] asc
";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = [];
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;
	$json[] = ['id'=>$objResult['bom_pj_name'], 'text'=>$objResult['bom_pj_name'] ." - ". $objResult['bom_cus_name']];
}
echo json_encode($json);
?>