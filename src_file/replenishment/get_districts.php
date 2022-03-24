<?
require_once("../../application.php");
$CFG->dbhost_pur = "203.154.39.115";
$CFG->dbhostPing_pur = "203.154.39.115";
$CFG->dbname_pur = "PUR";
$CFG->dbuser_pur = "sa";
$CFG->dbpass_pur = "P09iQA!WaT_?#R41!eXO";

//set var to array (var must be string type)
$connectionInfo_pur = array("Database" => "$CFG->dbname_pur", "UID" => "$CFG->dbuser_pur", "PWD" => "$CFG->dbpass_pur", "MultipleActiveResultSets" => true, 'ReturnDatesAsStrings' => true, "CharacterSet" => 'UTF-8');
$db_con_pur = sqlsrv_connect($CFG->dbhost_pur, $connectionInfo_pur);

if ($db_con_pur === false) {
    echo "Connection could not be established. <br/>";
    die(print_r(sqlsrv_errors(), true));
}

$districts_code = $_GET['districtsId'];

$sql = "SELECT id,
 [name_th]
,[name_en]
,[province_id]
FROM [PUR].[dbo].[tbl_districts_mst] WHERE province_id ='$districts_code'";
$query = sqlsrv_query($db_con_pur, $sql);
 
$json = array();
while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {    
    array_push($json, $result);
}
echo json_encode($json);
?>