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

//check DTN
$strSql_chk_dtn_no = " 
  SELECT [dn_t_dtn_code]
	,[dn_h_status]
	,[dn_h_driver_code]
	,[ps_t_cus_name]
	,[ps_t_pj_name]
	,[ps_t_terminal_name]
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
[ps_t_pj_name] = '$iden_pj_name'
and
[dn_h_driver_code] = '$iden_txt_scn_driver_id'
and
[dn_t_dtn_code] = '$iden_txt_scn_dtn_id'
and
[dn_h_status] = 'Delivery Transfer Note'
  ";
$objQuery_chk_dtn_no = sqlsrv_query($db_con, $strSql_chk_dtn_no, $params, $options);
$num_row_chk_dtn_no = sqlsrv_num_rows($objQuery_chk_dtn_no);

//null
if($num_row_chk_dtn_no == 0)
{	
	echo "NG";
}
else
{
	
	echo "OK";
}

sqlsrv_close($db_con);
?>