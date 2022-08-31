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
$iden_t_repn_id = isset($_POST['iden_t_repn_id']) ? $_POST['iden_t_repn_id'] : '';
$iden_t_split_qty = isset($_POST['iden_t_split_qty']) ? $_POST['iden_t_split_qty'] : '';
$iden_t_new_qty = isset($_POST['iden_t_new_qty']) ? $_POST['iden_t_new_qty'] : '';

//get tags order by asc
$strSql_getTags = " 
SELECT 
	[receive_tags_code]
	,[tags_packing_std]
FROM tbl_receive
left join 
tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
where 
receive_repn_id = '$iden_t_repn_id'
order by 
[receive_tags_code] asc
";

$objQuery_getTags = sqlsrv_query($db_con, $strSql_getTags, $params, $options);
$num_row_getTags = sqlsrv_num_rows($objQuery_getTags);

$row_id_getTags = 0;
while($objResult_getTags = sqlsrv_fetch_array($objQuery_getTags, SQLSRV_FETCH_ASSOC))
{
	$row_id_getTags++;

	$index_receive_tags_code = $objResult_getTags['receive_tags_code'];
	$index_tags_packing_std = $objResult_getTags['tags_packing_std'];
}

//update split tags qty.
$sqlUpdate_splitTags = " UPDATE tbl_tags_running
   SET tags_packing_std = tags_packing_std-'$iden_t_split_qty'
 WHERE tags_code = '$index_receive_tags_code'
 ";
$result_sqlUpdate_splitTags = sqlsrv_query($db_con, $sqlUpdate_splitTags);

if($result_sqlUpdate_splitTags)
{
	//insert log split tags qty.
	$sqlInsLog_splitTags = " INSERT INTO [tbl_tags_split_log]
			   (
			   [tags_split_repn_id]
			   ,[tags_split_code]
			   ,[tags_split_packing_std]
			   ,[tags_split_qty]
			   ,[tags_split_issue_by]
			   ,[tags_split_issue_date]
			   ,[tags_split_issue_time]
			   ,[tags_split_issue_datetime]
			   )
		 VALUES
			   (
			   '$iden_t_repn_id'
			   ,'$index_receive_tags_code'
			   ,'$index_tags_packing_std'
			   ,'$iden_t_split_qty'
			   ,'$t_cur_user_code_VMI_GDJ'
			   ,'$buffer_date'
			   ,'$buffer_time'
			   ,'$buffer_datetime'
			   )
	 ";
	$result_sqlInsLog_splitTags = sqlsrv_query($db_con, $sqlInsLog_splitTags);


	$sql_temp_detail = "
	SELECT 
	 [tags_code]
	,[tags_fg_code_gdj]
	,[tags_fg_code_gdj_desc]
	,[tags_project_name]
	,[tags_prod_plan]
	,[tags_packing_std]
	,[tags_total_qty]
	,[tags_token]
	,[tags_job_number]
	,receive_pallet_code
	,receive_location
	FROM [tbl_tags_running]
	left join tbl_receive
	ON tbl_tags_running.tags_code = tbl_receive.receive_tags_code
	where [tags_code] = '$index_receive_tags_code'
	";
    $object_splitTags_New = sqlsrv_query($db_con, $sql_temp_detail, $params, $options);

	while($objResult_detailTags = sqlsrv_fetch_array($object_splitTags_New, SQLSRV_FETCH_ASSOC))
	{

	$tags_fg_code_gdj_ = $objResult_detailTags['tags_fg_code_gdj'];
	$tags_fg_code_gdj_desc_ = $objResult_detailTags['tags_fg_code_gdj_desc'];
	$tags_project_name_ = $objResult_detailTags['tags_project_name'];
	$tags_prod_plan_ = $objResult_detailTags['tags_prod_plan'];
	$tags_packing_std_ = $objResult_detailTags['tags_packing_std'];
	$tags_total_qty_ = $objResult_detailTags['tags_total_qty'];
	$tags_token_ = $objResult_detailTags['tags_token'];
	$receive_pallet_code = $objResult_detailTags['receive_pallet_code'];
	$receive_location = $objResult_detailTags['receive_location']; 
	$tags_job_number_ = $objResult_detailTags['tags_job_number'];

	$strSql_get_tags = " SELECT top(1) tags_code FROM tbl_tags_running order by tags_id DESC ";
	$objQuery_get_tags = sqlsrv_query($db_con, $strSql_get_tags, $params, $options);
	
	while($objResult_get_tags = sqlsrv_fetch_array($objQuery_get_tags, SQLSRV_FETCH_ASSOC))
	{
		$buffer_tags_code = $objResult_get_tags['tags_code'];
	}

	$sum_tags = $buffer_tags_code + 1;
	$sprintf_tags = sprintf("%09d",$sum_tags);//generate to 9 digit
	$full_tags = $sprintf_tags;//full tags

	$str_new_tags = "INSERT INTO tbl_tags_running
						   (
						   [tags_code]
						   ,[tags_fg_code_gdj]
						   ,[tags_fg_code_gdj_desc]
						   ,[tags_project_name]
						   ,[tags_prod_plan]
						   ,[tags_packing_std]
						   ,[tags_total_qty]
						   ,[tags_token]
						   ,[tags_job_number]
						   ,[tags_issue_by]
						   ,[tags_issue_date]
						   ,[tags_issue_time]
						   ,[tags_issue_datetime]
						   )
					 VALUES
						   (
						   '$full_tags'
						   ,'$tags_fg_code_gdj_'
						   ,'$tags_fg_code_gdj_desc_'
						   ,'$tags_project_name_'
						   ,'$tags_prod_plan_'
						   ,'$iden_t_split_qty'
						   ,'$tags_total_qty_'
						   ,'$tags_token_'
						   ,'$tags_job_number_'
						   ,'$t_cur_user_code_VMI_GDJ'
						   ,'$buffer_date'
						   ,'$buffer_time'
						   ,'$buffer_datetime'
						   ) 
						   ";
		$objQuery_insert_tags = sqlsrv_query($db_con, $str_new_tags);


	$sql_new_tags_log = "INSERT INTO tbl_tags_split_log_new
           (
			[log_new_tagcode]
           ,[log_old_tagcode]
           ,[log_new_token]
           ,[log_new_time]
           ,[log_new_date]
           ,[log_new_datetime]
		   )
     VALUES
           (
			'$full_tags'
           ,'$index_receive_tags_code'
           ,'$tags_token_'
           ,'$buffer_time'
           ,'$buffer_date'
           ,'$buffer_datetime'
		   )
		   ";

	$objQuery_insert_log_new_tags = sqlsrv_query($db_con, $sql_new_tags_log);	


		$sqlIns = " INSERT INTO tbl_receive
		(
		[receive_tags_code]
		,[receive_pallet_code]
		,[receive_location]
		,[receive_status]
		,[receive_date]
		,[receive_time]
		,[receive_datetime]
		,[receive_issue_by]
		,[receive_issue_date]
		,[receive_issue_time]
		,[receive_issue_datetime]
		)
  	VALUES
		(
		 '$full_tags'
		,'$receive_pallet_code'
		,'$receive_location'
		,'Received'
		,'$buffer_date'
		,'$buffer_time'
		,'$buffer_datetime'
		,'$t_cur_user_code_VMI_GDJ'
		,'$buffer_date'
		,'$buffer_time'
		,'$buffer_datetime'
		)
 ";
 	$result_sqlIns = sqlsrv_query($db_con, $sqlIns);

	}

	$tags_old = var_encode($index_receive_tags_code);
	$tags_new = var_encode($full_tags);
	$back = $tags_old."dubble".$tags_new;

	echo $back;

}


sqlsrv_close($db_con);
?>