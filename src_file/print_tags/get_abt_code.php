<?
require_once("../../application.php");

$fg_code = $_GET['abtId'];
$project_code  = $_GET['projectSend'];

$sql = "SELECT bom_fg_code_set_abt
FROM [VMI].[dbo].[tbl_bom_mst] where bom_status = 'Active' and bom_pj_name = '$project_code' and bom_fg_code_gdj IN ('$fg_code') group by bom_fg_code_set_abt";
$query = sqlsrv_query($db_con, $sql);
 
$json = array();
while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {    
    array_push($json, $result);
}
echo json_encode($json);
?>