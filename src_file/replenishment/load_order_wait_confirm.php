<?
require_once("../../application.php");

$strSql_wait_change_date = "select in_id,installment, repn_fg_code_gdj, qty, price, b2c_contact_name, installment_payment_date , description, b2c_delivery_address, b2c_cus_zipcode
,issue_date from tbl_installment  
left join tbl_replenishment
on tbl_installment.order_ref = tbl_replenishment.repn_order_ref
left join tbl_b2c_detail 
on tbl_b2c_detail.b2c_repn_order_ref = tbl_installment.order_ref
left join tbl_b2c_sale
on tbl_b2c_sale.b2c_sale_order_id = tbl_b2c_detail.b2c_repn_order_ref";
$objQuery = sqlsrv_query($db_con, $strSql_wait_change_date);


$row_id = 0;
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;
    $json_array_ = array(
        "row_id" => $row_id,
        "in_id" => $objResult['in_id'],
        "installment" => $objResult['installment'],
        "repn_fg_code_gdj" => $objResult['repn_fg_code_gdj'],
        "qty" => $objResult['qty'],
		"price" => $objResult['price'],
        "b2c_contact_name" => $objResult['b2c_contact_name'],
        "installment_payment_date" => $objResult['installment_payment_date'],
        "description" => $objResult['description'],
        "b2c_delivery_address" => $objResult['b2c_delivery_address'],
        "issue_date" => $objResult['issue_date'],
    );
	
    array_push($json, $json_array_);
}
echo json_encode($json);
?>

