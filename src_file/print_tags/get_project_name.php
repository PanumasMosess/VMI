<?
require_once("../../application.php");

$cus = $_GET['cus_code'];

$sql = "SELECT [bom_pj_name]
FROM [VMI].[dbo].[tbl_bom_mst] where bom_status = 'Active' and bom_cus_code IN ('$cus') group by bom_pj_name";
$query = sqlsrv_query($db_con, $sql);
 
$json = array();
while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {    
    array_push($json, $result);
}
echo json_encode($json);
?>