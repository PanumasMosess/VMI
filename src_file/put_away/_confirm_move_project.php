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
$var_arr_list = isset($_POST['var_arr_list']) ? $_POST['var_arr_list'] : '';
$var_project_name = isset($_POST['var_project_name']) ? $_POST['var_project_name'] : '';
$str_token = isset($_POST['str_token']) ? $_POST['str_token'] : '';

$str_split_TagsProject = explode(',', $var_arr_list);
$str_count_arr = count($str_split_TagsProject);
$i_rows = 0 ;
$str_tags = "";
$str_project = "";

foreach ($str_split_TagsProject as $str_tagsProject) {
	
	$str_split = explode('#####', $str_tagsProject);
	$str_tags = $str_split[0];
	$str_project = $str_split[1];
	
    $sqlUpdate = "
			
			UPDATE [dbo].[tbl_tags_running]
			   SET [tags_project_name] = '$var_project_name'
			 WHERE [tags_code] = '$str_tags'
		   ";
	$resultUpdate = sqlsrv_query($db_con, $sqlUpdate);
	
	if($resultUpdate)
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
			  ,[tags_log_move_pj_token]
			   )
		 VALUES
			   (
			   '$str_tags'
			   ,'$str_project'
			   ,'$var_project_name'
			   ,'$t_cur_user_code_VMI_GDJ'
			   ,'$buffer_date'
			   ,'$buffer_time'
			   ,'$buffer_datetime'
			   ,'$str_token'
			   )
		";
		$objQuery_insert_log = sqlsrv_query($db_con, $strSql_insert_log);
	}
	
	$i_rows++;
}

//ctrl alert
if($str_count_arr == $i_rows)
{
	echo "SUC#####<b>--> Update Move Project success.</b>";
}
else
{
	echo "ERR#####Error!!!<b>--> Cannot operate !!!</b>";
}

sqlsrv_close($db_con);
?>