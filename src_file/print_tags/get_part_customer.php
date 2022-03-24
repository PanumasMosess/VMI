<?
require_once("../../application.php");

$partCustomer = $_GET['partCusId'];
$projectSend = $_GET['projectSend'];
$fgSend = $_GET['fgSend'];


$sql = "SELECT bom_part_customer
FROM [VMI].[dbo].[tbl_bom_mst] where bom_status = 'Active' and bom_fg_code_gdj = '$fgSend' and bom_pj_name = '$projectSend' and bom_fg_sku_code_abt IN ('$partCustomer') group by bom_part_customer";
$query = sqlsrv_query($db_con, $sql);
 
$json = array();
while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {    
    array_push($json, $result);
}
echo json_encode($json);
?>