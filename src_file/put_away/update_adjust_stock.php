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
$iden_hdn_pre_adj_stock_tags_id = isset($_POST['iden_hdn_pre_adj_stock_tags_id']) ? $_POST['iden_hdn_pre_adj_stock_tags_id'] : '';

//insert adj stock log
$strSql_insert_adj_stock_log = " 
INSERT INTO [dbo].[tbl_adjust_inventory_log]
   (
	[tags_log_tags_code]
	,[tags_log_query]
	,[tags_log_by]
	,[tags_log_date]
	,[tags_log_time]
	,[tags_log_datetime]
   )
VALUES
   (
	'$iden_hdn_pre_adj_stock_tags_id'
	,'delete'
	,'$t_cur_user_code_VMI_GDJ'
	,'$buffer_date'
	,'$buffer_time'
	,'$buffer_datetime'
   )
";
$objQuery_insert_adj_stock_log = sqlsrv_query($db_con, $strSql_insert_adj_stock_log);

if($objQuery_insert_adj_stock_log)
{
	/////////del receive/////////
	$strSql_del_receive = " 
	DELETE FROM [dbo].[tbl_receive]
	  WHERE 
	  [receive_tags_code] = '$iden_hdn_pre_adj_stock_tags_id'
	";
	$objQuery_del_receive = sqlsrv_query($db_con, $strSql_del_receive);
	
	/////////del tags/////////
	$strSql_del_tags = " 
	DELETE FROM [dbo].[tbl_tags_running]
	  WHERE 
	  [tags_code] = '$iden_hdn_pre_adj_stock_tags_id'
	";
	$objQuery_del_tags = sqlsrv_query($db_con, $strSql_del_tags);
	
	/////////clear/////////
	$strSql_del_adj_stock_log = " 
	DELETE FROM [dbo].[tbl_adjust_inventory]
	  WHERE 
	  [tags_tags_code] = '$iden_hdn_pre_adj_stock_tags_id'
	  and
	  [tags_by] = '$t_cur_user_code_VMI_GDJ'
	";
	$objQuery_del_adj_stock_log = sqlsrv_query($db_con, $strSql_del_adj_stock_log);
}

sqlsrv_close($db_con);
?>