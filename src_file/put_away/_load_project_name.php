<?
require_once("../../application.php");

/**********************************************************************************/
/*var *****************************************************************************/
$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

$strSql = " SELECT bom_cus_name,bom_pj_name
  FROM tbl_bom_mst
  where 
  bom_status = 'Active'
  and 
  bom_pj_name LIKE '%".$searchTerm."%'
  group by bom_cus_name,bom_pj_name
  order by [bom_pj_name] asc
";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = [];
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;
	$json[] = ['id'=>$objResult['bom_pj_name'], 'text'=>$objResult['bom_pj_name']." - ".$objResult['bom_cus_name']];
}
echo json_encode($json);
?>