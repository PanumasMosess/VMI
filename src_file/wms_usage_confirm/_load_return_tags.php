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
$str_user_type = isset($_POST['str_user_type']) ? $_POST['str_user_type'] : '';

//check permission
if($str_user_type == "Administrator")
{
	$sql_get_type = " where re_date = '$buffer_date' ";
}
else
{
	$sql_get_type = " where re_date = '$buffer_date' and re_terminal_name = '$t_cur_user_section_VMI_GDJ' ";
}

$strSql = " SELECT	
	  [re_tags_code]
	  ,[tags_packing_std]
      ,[re_fg_code_set_abt]
      ,[re_sku_code_abt]
      ,[re_fg_code_gdj]
      ,[re_ship_type]
      ,[re_part_customer]
      ,[re_terminal_name]
      ,[re_unit_type]
	  ,[bom_sku_code]
	  ,[re_datetime]
  FROM [tbl_return_tags]
  left join tbl_tags_running
  ON tbl_return_tags.re_tags_code = tbl_tags_running.tags_code
  LEFT JOIN tbl_bom_mst ON tbl_return_tags.re_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
  AND tbl_return_tags.re_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
  AND tbl_return_tags.re_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
  AND tbl_return_tags.re_terminal_name = tbl_bom_mst.bom_pj_name
  AND tbl_return_tags.re_ship_type = tbl_bom_mst.bom_ship_type
  AND tbl_return_tags.re_part_customer = tbl_bom_mst.bom_part_customer
  $sql_get_type
  group by
	  [re_tags_code]
	  ,[tags_packing_std]
      ,[re_fg_code_set_abt]
      ,[re_sku_code_abt]
      ,[re_fg_code_gdj]
      ,[re_ship_type]
      ,[re_part_customer]
      ,[re_terminal_name]
      ,[re_unit_type]
	  ,[bom_sku_code]
	  ,[re_datetime]
  order by re_datetime desc
";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;

    $json_array_ = array(
        "row_no" => $row_id,
		"re_tags_code" => $objResult['re_tags_code'],
		"tags_packing_std" => $objResult['tags_packing_std'],
		"re_fg_code_set_abt" => $objResult['re_fg_code_set_abt'],
		"re_sku_code_abt" => $objResult['re_sku_code_abt'],
		"re_fg_code_gdj" => $objResult['re_fg_code_gdj'],
		"re_ship_type" => $objResult['re_ship_type'],
		"re_part_customer" => $objResult['re_part_customer'],
		"re_terminal_name" => $objResult['re_terminal_name'],
		"re_unit_type" => $objResult['re_unit_type'],
		"bom_sku_code" => $objResult['bom_sku_code']
    );
	
    array_push($json, $json_array_);
}
	
echo json_encode($json);
?>