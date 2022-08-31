<?
require_once("../../application.php");


$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");
 
$buffer_date_3 = date('Y-m-d', strtotime($buffer_date.' +7 day'));

/**********************************************************************************/
/*var *****************************************************************************/
$ajax_province = isset($_POST['ajax_province']) ? $_POST['ajax_province'] : '';
$ajax_district = isset($_POST['ajax_district']) ? $_POST['ajax_district'] : '';
$ajax_sel_sub_district = isset($_POST['ajax_sel_sub_district']) ? $_POST['ajax_sel_sub_district'] : '';
$ajax_name_customer = isset($_POST['ajax_name_customer']) ? $_POST['ajax_name_customer'] : '';
$ajax_name_business = isset($_POST['ajax_name_business']) ? $_POST['ajax_name_business'] : '';
$ajax_address = isset($_POST['ajax_address']) ? $_POST['ajax_address'] : '';
$ajax_postcode = isset($_POST['ajax_postcode']) ? $_POST['ajax_postcode'] : '';
$ajax_tel = isset($_POST['ajax_tel']) ? $_POST['ajax_tel'] : '';
$ajax_sel_product_code = isset($_POST['ajax_sel_product_code']) ? $_POST['ajax_sel_product_code'] : '';
$ajax_sale_price = isset($_POST['ajax_sale_price']) ? $_POST['ajax_sale_price'] : '';
$ajax_id_card_tax_full = isset($_POST['ajax_id_card_tax_full']) ? $_POST['ajax_id_card_tax_full'] : '';
$ajax_branch_id_tax_full = isset($_POST['ajax_branch_id_tax_full']) ? $_POST['ajax_branch_id_tax_full'] : '';
$ajax_bom_fg_code_gdj = isset($_POST['ajax_bom_fg_code_gdj']) ? $_POST['ajax_bom_fg_code_gdj'] : '';
$ajax_price_qty = isset($_POST['ajax_price_qty']) ? $_POST['ajax_price_qty'] : '';
$ajax_bom_fg_code_set_abt = isset($_POST['ajax_bom_fg_code_set_abt']) ? $_POST['ajax_bom_fg_code_set_abt'] : '';
$ajax_bom_ship_type = isset($_POST['ajax_bom_ship_type']) ? $_POST['ajax_bom_ship_type'] : '';
$ajax_bom_part_customer = isset($_POST['ajax_bom_part_customer']) ? $_POST['ajax_bom_part_customer'] : '';
$ajax_qty = isset($_POST['ajax_qty']) ? $_POST['ajax_qty'] : '';   
$ajax_bom_fg_sku_code_abt= isset($_POST['ajax_bom_fg_sku_code_abt']) ? $_POST['ajax_bom_fg_sku_code_abt'] : '';  
$ajax_selling_pcs = isset($_POST['ajax_selling_pcs']) ? $_POST['ajax_selling_pcs'] : ''; 
$ajax_sender_ = isset($_POST['ajax_sender_']) ? $_POST['ajax_sender_'] : ''; 
$ajax_sel_InstallMent = isset($_POST['ajax_sel_InstallMent']) ? $_POST['ajax_sel_InstallMent'] : '';   
$ajax_desc = isset($_POST['ajax_desc']) ? $_POST['ajax_desc'] : ''; 

if($ajax_sender_ == 'true'){
   $ajax_sender_ = 'SALE';
}else{
   $ajax_sender_ = null;
}

$order_ref_num = 0;

//check ref id by repn_id
$str_order_ref = "select top(1) repn_id from tbl_replenishment order by repn_id desc";

$objQuery = sqlsrv_query($db_con, $str_order_ref);
	
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$order_ref_num =  $objResult['repn_id'];
}

$order_ref_num = $order_ref_num + 1;

$strSQL_insert = "
INSERT INTO [dbo].[tbl_replenishment]
	   (
	   [repn_order_ref]
	   ,[repn_fg_code_set_abt]
	   ,[repn_sku_code_abt]
	   ,[repn_fg_code_gdj]
	   ,[repn_pj_name]
	   ,[repn_ship_type]
	   ,[repn_part_customer]
	   ,[repn_qty]
	   ,[repn_unit_type]
	   ,[repn_terminal_name]
	   ,[repn_order_type]
	   ,[repn_delivery_date]
	   ,[repn_by]
	   ,[repn_date]
	   ,[repn_time]
	   ,[repn_datetime]
	   )
 VALUES
	   (
	   '$order_ref_num'
	   ,'$ajax_bom_fg_code_set_abt'
	   ,'$ajax_bom_fg_sku_code_abt'
	   ,'$ajax_bom_fg_code_gdj'
	   ,'B2C'
	   ,'$ajax_bom_ship_type'
	   ,'$ajax_bom_part_customer'
	   ,'$ajax_qty'
	   ,'set'
	   ,'B2C'
	   ,'B2C'
	   ,'$buffer_date'
	   ,'Sale'
	   ,'$buffer_date'
	   ,'$buffer_time'
	   ,'$buffer_datetime'
	   )
";
$objQuery_insert = sqlsrv_query($db_con, $strSQL_insert);

$strSQL_insert_detail = "
INSERT INTO [dbo].[tbl_b2c_detail]
  ([b2c_repn_order_ref]
  ,[b2c_customer_code]
  ,[b2c_cus_company]
  ,[b2c_inv_company]
  ,[b2c_cus_branch_id]
  ,[b2c_customer_name]
  ,[b2c_delivery_address]
  ,[b2c_cus_zipcode]
  ,[b2c_inv_address]
  ,[b2c_zipcode]
  ,[b2c_contact_name]
  ,[b2c_tel]
  ,[b2c_note]
  ,[b2c_order_date]
  ,[b2c_dtn]
  ,[b2c_track_num]
  ,[b2c_tax_inv]
  ,[b2c_status]
  ,[b2c_case]
  ,[b2c_sender]
  )
VALUES
   (
	'$order_ref_num'
   ,''
   ,'$ajax_name_business'
   ,'$ajax_name_business'
   ,'$ajax_branch_id_tax_full'
   ,'$ajax_name_customer'
   ,'$ajax_address'
   ,'$ajax_postcode'
   ,'$ajax_address'
   ,'$ajax_postcode'
   ,'$ajax_name_customer'
   ,'$ajax_tel'
   ,''
   ,'$buffer_date'
   ,''
   ,''
   ,'$ajax_id_card_tax_full'
   ,'Production'
   ,'Special'
   ,'$ajax_sender_'
   )
";

$objQuery_insert_detail = sqlsrv_query($db_con, $strSQL_insert_detail);


$strSQL_insert_sale = "
INSERT INTO [dbo].[tbl_b2c_sale]
   ([b2c_sale_date]
   ,[b2c_sale_time]
   ,[b2c_sale_count_time]
   ,[b2c_sale_order_id]
   ,[b2c_sale_user_id]
   ,[b2c_sale_pos_no]
   ,[b2c_sale_inv_no]
   ,[b2c_sale_excluding_vat]
   ,[b2c_sale_tax]
   ,[b2c_sale_including_vat]
   ,[b2c_sale_remark]
   ,[b2c_sale_branch]
   ,[b2c_sale_transport_fee]
   ,[b2c_sale_discount_amount])
VALUES
   ('$buffer_date'
   ,'$buffer_time'
   ,''
   ,'$order_ref_num'
   ,''
   ,'E052010002A1948'
   ,''
   ,'0'
   ,'0'
   ,'$ajax_selling_pcs'
   ,''
   ,''
   ,''
   ,'0')
";

$objQuery_insert_sale = sqlsrv_query($db_con, $strSQL_insert_sale);


if($ajax_sel_InstallMent == ''){
   $sql_insatllment_insert = "INSERT INTO [dbo].[tbl_installment]
   ([installment]
   ,[description]
   ,[qty]
   ,[price]
   ,[order_ref]
   ,[installment_payment_date]
   ,[issue_date]
   ,[issue_time]
   ,[issue_date_time])
VALUES
   ('1'
   ,'$ajax_desc'
   ,'$ajax_qty'
   ,'$ajax_selling_pcs'
   ,'$order_ref_num'
   ,'' 
   ,'$buffer_date'
   ,'$buffer_time'
   ,'$buffer_datetime')";

$objQuery_insert_Installment = sqlsrv_query($db_con, $sql_insatllment_insert);

}else{

   for($incress = 1; $incress <= $ajax_sel_InstallMent; $incress++){

   $ajax_qty_per = (int)$ajax_qty / (int)$ajax_sel_InstallMent;
   $ajax_selling_pcs_per = (int)$ajax_selling_pcs / (int)$ajax_sel_InstallMent;

   $sql_insatllment_insert = "INSERT INTO [dbo].[tbl_installment]
   ([installment]
   ,[description]
   ,[qty]
   ,[price]
   ,[order_ref]
   ,[installment_payment_date]
   ,[issue_date]
   ,[issue_time]
   ,[issue_date_time])
VALUES
   ('$incress'
   ,'$ajax_desc'
   ,'$ajax_qty_per'
   ,'$ajax_selling_pcs_per'
   ,'$order_ref_num'
   ,'' 
   ,'$buffer_date'
   ,'$buffer_time'
   ,'$buffer_datetime')";

   $objQuery_insert_Installment = sqlsrv_query($db_con, $sql_insatllment_insert);
}

}


sqlsrv_close($db_con);
?>