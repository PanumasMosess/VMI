<?
require_once("../../application.php");

$shipType = $_GET['shipId'];

$sql = "SELECT bom_ship_type
FROM [VMI].[dbo].[tbl_bom_mst] where bom_status = 'Active' and bom_fg_sku_code_abt IN ('$shipType') group by bom_ship_type";
$query = sqlsrv_query($db_con, $sql);
 
$json = array();
while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {    
    array_push($json, $result);
}
echo json_encode($json);
?>