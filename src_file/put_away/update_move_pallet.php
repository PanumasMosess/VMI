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
$iden_hdn_pre_pallet_id = isset($_POST['iden_hdn_pre_pallet_id']) ? $_POST['iden_hdn_pre_pallet_id'] : '';
$iden_txt_move_scn_pallet_location = isset($_POST['iden_txt_move_scn_pallet_location']) ? $_POST['iden_txt_move_scn_pallet_location'] : '';

//get current location
$strSql_get_curr_location = " SELECT receive_location FROM tbl_receive where receive_pallet_code = '$iden_hdn_pre_pallet_id' ";
$objQuery_get_curr_location = sqlsrv_query($db_con, $strSql_get_curr_location, $params, $options);
$objResult_get_curr_location = sqlsrv_fetch_array($objQuery_get_curr_location, SQLSRV_FETCH_ASSOC);
$buffer_curr_location = $objResult_get_curr_location['receive_location'];

//update location
$strSql_update_location = " 
UPDATE [dbo].[tbl_receive]
   SET [receive_location] = '$iden_txt_move_scn_pallet_location'
 WHERE [receive_status] = 'Received' and [receive_pallet_code] = '$iden_hdn_pre_pallet_id'
";
$objQuery_update_location = sqlsrv_query($db_con, $strSql_update_location);

if($objQuery_update_location)
{
	//insert internal move log
	$strSql_insert_log = " 
	INSERT INTO [dbo].[tbl_internal_move_pallet_log]
           (
		   [pl_log_move_pallet_code]
           ,[pl_log_move_location_from]
           ,[pl_log_move_location_to]
           ,[pl_log_move_by]
           ,[pl_log_move_date]
           ,[pl_log_move_time]
           ,[pl_log_move_datetime]
		   )
     VALUES
           (
		   '$iden_hdn_pre_pallet_id'
           ,'$buffer_curr_location'
           ,'$iden_txt_move_scn_pallet_location'
           ,'$t_cur_user_code_VMI_GDJ'
           ,'$buffer_date'
           ,'$buffer_time'
           ,'$buffer_datetime'
		   )
	";
	$objQuery_insert_log = sqlsrv_query($db_con, $strSql_insert_log);
	
	//clear
	$strSql_del_move_log = " 
	DELETE FROM [dbo].[tbl_internal_move_pallet]
      WHERE 
	  [pl_move_pallet_code] = '$iden_hdn_pre_pallet_id'
	  and
	  [pl_move_by] = '$t_cur_user_code_VMI_GDJ'
	";
	$objQuery_del_move_log = sqlsrv_query($db_con, $strSql_del_move_log);
}

sqlsrv_close($db_con);
?>