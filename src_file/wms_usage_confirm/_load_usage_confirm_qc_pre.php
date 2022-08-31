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
	$sql_get_type = " ";
}
else
{
	$sql_get_type = " where conf_qc_by = '$t_cur_user_code_VMI_GDJ' ";
}
	
$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

$strSql = " SELECT	
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
	  ,[tags_packing_std]
	  ,[bom_sku_code]
  FROM [tbl_usage_conf_qc]
  left join tbl_tags_running
  ON tbl_usage_conf_qc.conf_qc_tags_code = tbl_tags_running.tags_code
  LEFT JOIN tbl_bom_mst ON tbl_usage_conf_qc.conf_qc_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
  AND tbl_usage_conf_qc.conf_qc_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
  AND tbl_usage_conf_qc.conf_qc_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
  AND tbl_usage_conf_qc.conf_qc_terminal_name = tbl_bom_mst.bom_pj_name
  AND tbl_usage_conf_qc.conf_qc_ship_type = tbl_bom_mst.bom_ship_type
  AND tbl_usage_conf_qc.conf_qc_part_customer = tbl_bom_mst.bom_part_customer
  $sql_get_type
  group by 
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
  ,[tags_packing_std]
  ,[bom_sku_code]
  order by conf_qc_datetime desc
";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;

    $json_array_ = array(
        "row_no" => $row_id,
		"conf_qc_tags_code" => $objResult['conf_qc_tags_code'],
		"conf_qc_fg_code_set_abt" => $objResult['conf_qc_fg_code_set_abt'],
		"conf_qc_sku_code_abt" => $objResult['conf_qc_sku_code_abt'],
		"conf_qc_fg_code_gdj" => $objResult['conf_qc_fg_code_gdj'],
		"conf_qc_ship_type" => $objResult['conf_qc_ship_type'],
		"conf_qc_snp" => $objResult['conf_qc_snp'],
		"conf_qc_part_customer" => $objResult['conf_qc_part_customer'],
		"conf_qc_terminal_name" => $objResult['conf_qc_terminal_name'],
		"conf_price_sale_per_pcs" => $objResult['conf_price_sale_per_pcs'],
		"conf_unit_type" => $objResult['conf_unit_type'],
		"conf_qc_by" => $objResult['conf_qc_by'],
		"conf_qc_date" => $objResult['conf_qc_date'],
		"conf_qc_time" => $objResult['conf_qc_time'],
		"conf_qc_datetime" => $objResult['conf_qc_datetime'],
		"tags_packing_std" => $objResult['tags_packing_std'],
		"bom_sku_code" => $objResult['bom_sku_code']
    );
	
    array_push($json, $json_array_);
}
	
echo json_encode($json);
?>