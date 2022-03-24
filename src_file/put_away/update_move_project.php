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
$iden_hdn_pj_pre_tags_id = isset($_POST['iden_hdn_pj_pre_tags_id']) ? $_POST['iden_hdn_pj_pre_tags_id'] : '';
$iden_sel_project_name = isset($_POST['iden_sel_project_name']) ? $_POST['iden_sel_project_name'] : '';

//get current project
$strSql_get_curr_pj = " SELECT tags_project_name FROM tbl_tags_running where tags_code = '$iden_hdn_pj_pre_tags_id' ";
$objQuery_get_curr_pj = sqlsrv_query($db_con, $strSql_get_curr_pj, $params, $options);
$objResult_get_curr_pj = sqlsrv_fetch_array($objQuery_get_curr_pj, SQLSRV_FETCH_ASSOC);
$curr_tags_project_name = $objResult_get_curr_pj['tags_project_name'];

//update project
$strSql_update_pj = " 
UPDATE [dbo].[tbl_tags_running]
   SET [tags_project_name] = '$iden_sel_project_name'
 WHERE [tags_code] = '$iden_hdn_pj_pre_tags_id'
";
$objQuery_update_pj = sqlsrv_query($db_con, $strSql_update_pj);

if($objQuery_update_pj)
{
	//insert internal move log
	$strSql_insert_log = " 
	INSERT INTO [dbo].[tbl_internal_move_project_log]
           (
		   [tags_log_move_pj_tags_code]
		  ,[tags_log_move_pj_project_from]
		  ,[tags_log_move_pj_project_to]
		  ,[tags_log_move_pj_by]
		  ,[tags_log_move_pj_date]
		  ,[tags_log_move_pj_time]
		  ,[tags_log_move_pj_datetime]
		   )
     VALUES
           (
		   '$iden_hdn_pj_pre_tags_id'
           ,'$curr_tags_project_name'
           ,'$iden_sel_project_name'
           ,'$t_cur_user_code_VMI_GDJ'
           ,'$buffer_date'
           ,'$buffer_time'
           ,'$buffer_datetime'
		   )
	";
	$objQuery_insert_log = sqlsrv_query($db_con, $strSql_insert_log);
	
	//clear
	$strSql_del_move_log = " 
	DELETE FROM [dbo].[tbl_internal_move_project]
      WHERE 
	  [tags_move_pj_tags_code] = '$iden_hdn_pj_pre_tags_id'
	  and
	  [tags_move_pj_by] = '$t_cur_user_code_VMI_GDJ'
	";
	$objQuery_del_move_log = sqlsrv_query($db_con, $strSql_del_move_log);
}

sqlsrv_close($db_con);
?>