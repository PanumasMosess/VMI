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
	$sqlInsLog_splitTags = " 
		INSERT INTO [dbo].[tbl_tags_split_log]
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
	
	echo var_encode($index_receive_tags_code);
}

sqlsrv_close($db_con);
?>