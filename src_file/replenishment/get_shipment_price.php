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

/**********************************************************************************/
/*var *****************************************************************************/
$ajax_provinId_id_geo = isset($_POST['ajax_provinId_id_geo']) ? $_POST['ajax_provinId_id_geo'] : '';
$ajax_product_weight = isset($_POST['ajax_product_weight']) ? $_POST['ajax_product_weight'] : '';   
$ajax_product_qty = isset($_POST['ajax_product_qty']) ? $_POST['ajax_product_qty'] : '';

$ajax_last_weight = $ajax_product_weight * $ajax_product_qty;
// $ajax_last_weight = $ajax_product_weight;

$sql = "SELECT [id]
,[name]
FROM [PUR].[dbo].[tbl_geographies_mst] WHERE id = '$ajax_provinId_id_geo'";
$query = sqlsrv_query($db_con_pur, $sql);
 
$section = '';
while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {    
    $section = $result['name'];
}

if($section == 'ภาคตะวันออก'){
$price = 0;
$sql_price = "SELECT [shipment_b2c_id]
,[shipment_b2c_weight]
,[shipment_b2c_east_price]
,[shipment_b2c_orther_price] FROM [tbl_shipement_b2c_price]";

$query_price = sqlsrv_query($db_con, $sql_price);
while($result_price = sqlsrv_fetch_array($query_price, SQLSRV_FETCH_ASSOC)) {    
    $weight = $result_price['shipment_b2c_weight'];

    if(($ajax_last_weight <= $weight) && ($price == 0)){
        $price = $result_price['shipment_b2c_east_price'];
        
    }
    else if (($ajax_last_weight > 25000) && ($price == 0)){
        $price = 325;
    }
   

}
echo number_format($price,2);


}else{
    $price = 0;
    $sql_price = "SELECT [shipment_b2c_id]
    ,[shipment_b2c_weight]
    ,[shipment_b2c_east_price]
    ,[shipment_b2c_orther_price] FROM [tbl_shipement_b2c_price]";
    
    $query_price = sqlsrv_query($db_con, $sql_price);
    while($result_price = sqlsrv_fetch_array($query_price, SQLSRV_FETCH_ASSOC)) {    
        $weight = $result_price['shipment_b2c_weight'];
    
        if(($ajax_last_weight <= $weight) && ($price == 0) ){
            $price = $result_price['shipment_b2c_orther_price'];
        }
        else if (($ajax_last_weight > $weight) && ($price == 0)){
            $price = 345;
        }
       
    }
    echo number_format($price,2);
   
}

sqlsrv_close($db_con);
sqlsrv_close($db_con_pur);
?>