<?
require_once("../../application.php");

$fg_component = $_GET['componenttId'];

$sql = "SELECT bom_fg_sku_code_abt
FROM [VMI].[dbo].[tbl_bom_mst] where  bom_status = 'Active' and bom_fg_code_set_abt IN ('$fg_component') group by bom_fg_sku_code_abt";
$query = sqlsrv_query($db_con, $sql);
 
$json = array();
while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {    
    array_push($json, $result);
}
echo json_encode($json);
?>