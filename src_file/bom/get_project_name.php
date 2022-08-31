<?
require_once("../../application.php");

$customer_code = $_GET['customer_code'];

$sql = "SELECT FROM [tbl_bom_mst] where  bom_status = 'Active' and  bom_cus_code = '$customer_code'  group by bom_pj_name ";
$query = sqlsrv_query($db_con, $sql);
 
$json = array();
while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {    
    array_push($json, $result);
}
echo json_encode($json);
?>