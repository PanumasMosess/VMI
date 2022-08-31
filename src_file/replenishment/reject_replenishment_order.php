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
$iden_repn_id = isset($_POST['iden_repn_id']) ? $_POST['iden_repn_id'] : '';
$iden_repn_reject_remark = isset($_POST['iden_repn_reject_remark']) ? $_POST['iden_repn_reject_remark'] : '';
	
$order_code = "";
$item_code = "";

$select_mrd_code = "SELECT  [repn_ord_code], [repn_odt_item_code] from tbl_replenishment WHERE repn_id = '$iden_repn_id'";
$result_order_code = sqlsrv_query($db_con, $select_mrd_code);

while($objResult_code = sqlsrv_fetch_array($result_order_code, SQLSRV_FETCH_ASSOC))
{
			$order_code = 	$objResult_code["repn_ord_code"];
			$item_code = 	$objResult_code["repn_odt_item_code"];
}



//update status
$sqlUpdate = " UPDATE tbl_replenishment
   SET [repn_conf_status] = 'Rejected'
	  ,[repn_conf_remark] = '".ltrim(rtrim($iden_repn_reject_remark))."'
	  ,[repn_conf_by] = '$t_cur_user_code_VMI_GDJ'
	  ,[repn_conf_date] = '$buffer_date'
	  ,[repn_conf_time] = '$buffer_time'
	  ,[repn_conf_datetime] = '$buffer_datetime'
 WHERE repn_id = '$iden_repn_id'
 ";
$result_sqlUpdate = sqlsrv_query($db_con, $sqlUpdate);

if($result_sqlUpdate){
	$order = "UPDATE tbl_customer_order_detail_mst SET odt_status = 'cancel', odt_now_in = 'cancel', odt_order_remark = '$iden_repn_reject_remark' WHERE odt_ord_code = '$order_code' AND odt_item_code = '$item_code'";
	$orQuery = sqlsrv_query($db_con_mrp,$order);

	$checkQL = "SELECT COUNT(odt_ord_code) AS all_current, (SELECT COUNT(odt_ord_code) FROM tbl_customer_order_detail_mst WHERE odt_ord_code = '$order_code' AND odt_now_in = 'cancel') AS all_cancel  FROM tbl_customer_order_detail_mst where odt_ord_code = '$order_code'";
	$checkQuery = sqlsrv_query($db_con_mrp,$checkQL);
	$checkResult = sqlsrv_fetch_array($checkQuery, SQLSRV_FETCH_ASSOC);
	if(intval($checkResult['all_current']) == intval($checkResult['all_cancel'])){
		$stamp = "UPDATE tbl_customer_order_mst SET ord_now_in = 'cancel', ord_status = 'cancel' WHERE ord_code = '$order_code'";
		$stampQuery = sqlsrv_query($db_con_mrp,$stamp);
	}
}

sqlsrv_close($db_con_mrp);
sqlsrv_close($db_con);
?>