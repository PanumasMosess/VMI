<?
require_once("../../application.php");

/**********************************************************************************/
/*var *****************************************************************************/
$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

$strSql = " SELECT [bom_fg_code_gdj]
  FROM tbl_bom_mst
  where 
  bom_status = 'Active'
  and 
  [bom_fg_code_gdj] is not NULL
  and 
  bom_fg_code_gdj LIKE '%".$searchTerm."%'
  group by [bom_fg_code_gdj]
  order by [bom_fg_code_gdj] asc
";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = [];
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;
	$json[] = ['id'=>$objResult['bom_fg_code_gdj'], 'text'=>$objResult['bom_fg_code_gdj']];
}
echo json_encode($json);
?>