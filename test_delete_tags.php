<?
require_once("application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

////////////////////////delete tags put away
/*
$strSql = " SELECT *
  FROM [VMI].[dbo].[tbl_receive]
  left join tbl_tags_running
  on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
  where [receive_pallet_code] = 'PL0000000XX' ";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;
	
	$tags_code = $objResult['tags_code'];
	
	echo $tags_code.'<br>';

	$sqlDelete1 = " DELETE FROM tbl_tags_running WHERE tags_code = '$tags_code' ";
	$result_sqlDelete1 = sqlsrv_query($db_con, $sqlDelete1);

	$sqlDelete2 = " DELETE FROM tbl_receive WHERE receive_tags_code = '$tags_code' ";
	$result_sqlDelete2 = sqlsrv_query($db_con, $sqlDelete2);

}
*/

?>