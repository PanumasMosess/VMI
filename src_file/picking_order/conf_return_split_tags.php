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

//get tags split log order by asc
$strSql_getTags_split = " 
SELECT 
	  [repn_id]
	  ,[tags_split_repn_id]
      ,[tags_split_code]
      ,[tags_split_packing_std]
      ,[tags_split_qty]
  FROM [VMI].[dbo].[tbl_replenishment]
  left join tbl_tags_split_log
  on tbl_replenishment.repn_id = tbl_tags_split_log.tags_split_repn_id
  where tags_split_repn_id = '$iden_t_repn_id'
";

$objQuery_getTags_split = sqlsrv_query($db_con, $strSql_getTags_split, $params, $options);
$num_row_getTags_split = sqlsrv_num_rows($objQuery_getTags_split);

$row_id_getTags_split = 0;
while($objResult_getTags_split = sqlsrv_fetch_array($objQuery_getTags_split, SQLSRV_FETCH_ASSOC))
{
	$row_id_getTags_split++;

	$tags_split_repn_id = $objResult_getTags_split['tags_split_repn_id'];
	$tags_split_code = $objResult_getTags_split['tags_split_code'];
	$tags_split_packing_std = $objResult_getTags_split['tags_split_packing_std'];
	$tags_split_qty = $objResult_getTags_split['tags_split_qty'];
}

//update split tags qty.
$sqlUpdate_splitTags = " UPDATE tbl_tags_running
   SET tags_packing_std = tags_packing_std+'$tags_split_qty'
 WHERE tags_code = '$tags_split_code'
 ";
$result_sqlUpdate_splitTags = sqlsrv_query($db_con, $sqlUpdate_splitTags);

if($result_sqlUpdate_splitTags)
{
	//Del log split tags qty.
	$sqlDelLog_splitTags = " DELETE FROM [dbo].[tbl_tags_split_log] WHERE [tags_split_repn_id] = '$tags_split_repn_id' and  [tags_split_code] = '$tags_split_code' ";
	$result_sqlDelLog_splitTags = sqlsrv_query($db_con, $sqlDelLog_splitTags);
	
	echo var_encode($tags_split_code);
}

sqlsrv_close($db_con);
?>