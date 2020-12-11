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
$iden_hdn_pre_tags_id = isset($_POST['iden_hdn_pre_tags_id']) ? $_POST['iden_hdn_pre_tags_id'] : '';
$iden_txt_move_scn_new_pallet = isset($_POST['iden_txt_move_scn_new_pallet']) ? $_POST['iden_txt_move_scn_new_pallet'] : '';

//get current pallet
$strSql_get_curr_pallet = " SELECT receive_pallet_code FROM tbl_receive where receive_tags_code = '$iden_hdn_pre_tags_id' ";
$objQuery_get_curr_pallet = sqlsrv_query($db_con, $strSql_get_curr_pallet, $params, $options);
$objResult_get_curr_pallet = sqlsrv_fetch_array($objQuery_get_curr_pallet, SQLSRV_FETCH_ASSOC);
$buffer_curr_pallet = $objResult_get_curr_pallet['receive_pallet_code'];

//update pallet
$strSql_update_pallet = " 
UPDATE [dbo].[tbl_receive]
   SET [receive_pallet_code] = '$iden_txt_move_scn_new_pallet'
 WHERE [receive_status] = 'Received' and [receive_tags_code] = '$iden_hdn_pre_tags_id'
";
$objQuery_update_pallet = sqlsrv_query($db_con, $strSql_update_pallet);

if($objQuery_update_pallet)
{
	//insert internal move log
	$strSql_insert_log = " 
	INSERT INTO [dbo].[tbl_internal_move_tags_log]
           (
		   [tags_log_move_tags_code]
           ,[tags_log_move_pallet_from]
           ,[tags_log_move_pallet_to]
           ,[tags_log_move_by]
           ,[tags_log_move_date]
           ,[tags_log_move_time]
           ,[tags_log_move_datetime]
		   )
     VALUES
           (
		   '$iden_hdn_pre_tags_id'
           ,'$buffer_curr_pallet'
           ,'$iden_txt_move_scn_new_pallet'
           ,'$t_cur_user_code_VMI_GDJ'
           ,'$buffer_date'
           ,'$buffer_time'
           ,'$buffer_datetime'
		   )
	";
	$objQuery_insert_log = sqlsrv_query($db_con, $strSql_insert_log);
	
	//clear
	$strSql_del_move_log = " 
	DELETE FROM [dbo].[tbl_internal_move_tags]
      WHERE 
	  [tags_move_tags_code] = '$iden_hdn_pre_tags_id'
	  and
	  [tags_move_by] = '$t_cur_user_code_VMI_GDJ'
	";
	$objQuery_del_move_log = sqlsrv_query($db_con, $strSql_del_move_log);
}

sqlsrv_close($db_con);
?>