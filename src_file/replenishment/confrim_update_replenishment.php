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
$repn_id = isset($_POST['repn_id']) ? $_POST['repn_id'] : '';
$repn_code_set_abt = isset($_POST['repn_code_set_abt']) ? $_POST['repn_code_set_abt'] : '';
$repn_component_abt = isset($_POST['repn_component_abt']) ? $_POST['repn_component_abt'] : '';
$repn_bom_fg_code_gdj = isset($_POST['repn_bom_fg_code_gdj']) ? $_POST['repn_bom_fg_code_gdj'] : '';
$repn_bom_pj_name = isset($_POST['repn_bom_pj_name']) ? $_POST['repn_bom_pj_name'] : '';
$repn_bom_ship_type = isset($_POST['repn_bom_ship_type']) ? $_POST['repn_bom_ship_type'] : '';
$repn_bom_part_customer = isset($_POST['repn_bom_part_customer']) ? $_POST['repn_bom_part_customer'] : '';
$repn_qty = isset($_POST['repn_qty']) ? $_POST['repn_qty'] : '';
$repn_unit_type = isset($_POST['repn_unit_type']) ? $_POST['repn_unit_type'] : '';
$repn_terminal_name = isset($_POST['repn_terminal_name']) ? $_POST['repn_terminal_name'] : '';
$repn_delivery_date = isset($_POST['repn_delivery_date']) ? $_POST['repn_delivery_date'] : '';  
$repn_order_ref = isset($_POST['repn_order_ref']) ? $_POST['repn_order_ref'] : ''; 

	//update status
	$sqlUpdate = " UPDATE tbl_replenishment
	   SET [repn_order_ref] = '$repn_order_ref'
          ,[repn_fg_code_set_abt] = '$repn_code_set_abt'
		  ,[repn_sku_code_abt] = '$repn_component_abt'
		  ,[repn_fg_code_gdj] = '$repn_bom_fg_code_gdj'
		  ,[repn_pj_name] = '$repn_bom_pj_name'
		  ,[repn_ship_type] = '$repn_bom_ship_type'
		  ,[repn_part_customer] = '$repn_bom_part_customer'
          ,[repn_qty] = '$repn_qty'
          ,[repn_unit_type] = '$repn_unit_type'
          ,[repn_terminal_name] = '$repn_terminal_name'
          ,[repn_delivery_date] = '$repn_delivery_date'
          ,[repn_by] = '$t_cur_user_code_VMI_GDJ'
	 WHERE repn_id = '$repn_id'
	 ";

$result_sqlUpdate = sqlsrv_query($db_con, $sqlUpdate);

if($result_sqlUpdate){
		$item_code = "";
		$ord_code = "";
		$sql_get_order_ = "SELECT [repn_ord_code], [repn_odt_item_code] FROM tbl_replenishment Where repn_id = '$repn_id'";
		$result_sql_oreder = sqlsrv_query($db_con, $sql_get_order_);
		while($objResult_or = sqlsrv_fetch_array($result_sql_oreder, SQLSRV_FETCH_ASSOC))
		{  
				$ord_code =  $objResult_or["repn_ord_code"];
				$item_code =  $objResult_or["repn_odt_item_code"];
		}

		$orQL = "UPDATE tbl_customer_order_detail_mst
		SET [odt_fg_code] = '$repn_bom_fg_code_gdj',
			[odt_fg_codeset] = '$repn_code_set_abt',
			[odt_part_customer] = '$repn_bom_part_customer',
			[odt_project] = '$repn_bom_pj_name',
			[odt_order_qty] = '$repn_qty',
			[odt_unit_type] = '$repn_unit_type',
			[odt_status] = 'edited',
			[odt_ship_type] = '$repn_bom_ship_type',
			[odt_delivery_date] = '$repn_delivery_date'
		WHERE odt_ord_code = '$ord_code' AND odt_item_code = '$item_code'";
		$orQuery = sqlsrv_query($db_con_mrp,$orQL);
}
    
if($orQuery){
    echo "UPDATE_SUCCESS";
}else{
    echo "UPDATE_NOTWORK";
}

sqlsrv_close($db_con_mrp);
sqlsrv_close($db_con);
?>