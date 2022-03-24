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
$var_status = isset($_POST['var_status']) ? $_POST['var_status'] : '';

$str_split_TagsStatus = explode(',', $var_arr_list);
$str_count_arr = count($str_split_TagsStatus);
$i_rows = 0 ;
$str_tags = "";
$str_curr_status = "";

foreach ($str_split_TagsStatus as $str_tagsStatus) {
	
	$str_split = explode('#####', $str_tagsStatus);
	$str_tags = $str_split[0];
	$str_curr_status = $str_split[1];
	
    $sqlUpdate = "
			
			UPDATE [dbo].[tbl_receive]
			   SET [receive_status] = '$var_status'
			 WHERE [receive_tags_code] = '$str_tags'
		   ";
	$resultUpdate = sqlsrv_query($db_con, $sqlUpdate);
	
	if($resultUpdate)
	{
		//insert internal move status
		$strSql_insert_log = " 
		INSERT INTO [dbo].[tbl_internal_change_status_log]
			   (
			   [tags_log_change_sta_tags_code]
			  ,[tags_log_change_sta_status_from]
			  ,[tags_log_change_sta_status_to]
			  ,[tags_log_change_sta_by]
			  ,[tags_log_change_sta_date]
			  ,[tags_log_change_sta_time]
			  ,[tags_log_change_sta_datetime]
			   )
		 VALUES
			   (
			   '$str_tags'
			   ,'$str_curr_status'
			   ,'$var_status'
			   ,'$t_cur_user_code_VMI_GDJ'
			   ,'$buffer_date'
			   ,'$buffer_time'
			   ,'$buffer_datetime'
			   )
		";
		$objQuery_insert_log = sqlsrv_query($db_con, $strSql_insert_log);
	}
	
	$i_rows++;
}

//ctrl alert
if($str_count_arr == $i_rows)
{
	echo "SUC#####<b>--> Update Move Status success.</b>";
}
else
{
	echo "ERR#####Error!!!<b>--> Cannot operate !!!</b>";
}

sqlsrv_close($db_con);
?>