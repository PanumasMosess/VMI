<?
require_once("../../application.php");

/**********************************************************************************/
/*var *****************************************************************************/
$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

$strSql = " SELECT 
	[sts_name]
      ,[sts_type]
      ,[sts_status]
  FROM tbl_fulfillment_status
  where 
  sts_status = 'Active'
  and 
  sts_name LIKE '%".$searchTerm."%'
  order by [sts_name] asc
";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = [];
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;
	$json[] = ['id'=>$objResult['sts_name'], 'text'=>$objResult['sts_type'] ." - ". $objResult['sts_name']];
}
echo json_encode($json);
?>