<?
require_once("../../application.php");
/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$ajax_bom_id = isset($_POST['ajax_bom_id']) ? $_POST['ajax_bom_id'] : '';
$ajax_text_fg_code_set_abt = isset($_POST['ajax_text_fg_code_set_abt']) ? $_POST['ajax_text_fg_code_set_abt'] : '';
$ajax_text_component_abt = isset($_POST['ajax_text_component_abt']) ? $_POST['ajax_text_component_abt'] : '';
$ajax_text_fg_code_gdj = isset($_POST['ajax_text_fg_code_gdj']) ? $_POST['ajax_text_fg_code_gdj'] : '';
$ajax_text_des = isset($_POST['ajax_text_des']) ? $_POST['ajax_text_des'] : '';
$ajax_text_customer_code = isset($_POST['ajax_text_customer_code']) ? $_POST['ajax_text_customer_code'] : '';
$ajax_text_customer_name = isset($_POST['ajax_text_customer_name']) ? $_POST['ajax_text_customer_name'] : '';
$ajax_text_project_name = isset($_POST['ajax_text_project_name']) ? $_POST['ajax_text_project_name'] : '';
$ajax_text_ctn_code_normal = isset($_POST['ajax_text_ctn_code_normal']) ? $_POST['ajax_text_ctn_code_normal'] : '';
$ajax_text_snp = isset($_POST['ajax_text_snp']) ? $_POST['ajax_text_snp'] : '';
$ajax_text_type_code = isset($_POST['ajax_text_type_code']) ? $_POST['ajax_text_type_code'] : '';
$ajax_text_ship_type = isset($_POST['ajax_text_ship_type']) ? $_POST['ajax_text_ship_type'] : '';
$ajax_text_package_type = isset($_POST['ajax_text_package_type']) ? $_POST['ajax_text_package_type'] : '';
$ajax_text_w = isset($_POST['ajax_text_w']) ? $_POST['ajax_text_w'] : '';
$ajax_text_l = isset($_POST['ajax_text_l']) ? $_POST['ajax_text_l'] : '';
$ajax_text_h = isset($_POST['ajax_text_h']) ? $_POST['ajax_text_h'] : '';
$ajax_text_usage = isset($_POST['ajax_text_usage']) ? $_POST['ajax_text_usage'] : '';
$ajax_text_space_paper = isset($_POST['ajax_text_space_paper']) ? $_POST['ajax_text_space_paper'] : '';
$ajax_text_flute = isset($_POST['ajax_text_flute']) ? $_POST['ajax_text_flute'] : '';
$ajax_text_packing = isset($_POST['ajax_text_packing']) ? $_POST['ajax_text_packing'] : '';
$ajax_text_wms_min = isset($_POST['ajax_text_wms_min']) ? $_POST['ajax_text_wms_min'] : '';
$ajax_text_wms_max = isset($_POST['ajax_text_wms_max']) ? $_POST['ajax_text_wms_max'] : '';
$ajax_text_vmi_min = isset($_POST['ajax_text_vmi_min']) ? $_POST['ajax_text_vmi_min'] : '';
$ajax_text_vmi_max = isset($_POST['ajax_text_vmi_max']) ? $_POST['ajax_text_vmi_max'] : '';
$ajax_text_partcustomer = isset($_POST['ajax_text_partcustomer']) ? $_POST['ajax_text_partcustomer'] : '';
$ajax_text_cost = isset($_POST['ajax_text_cost']) ? $_POST['ajax_text_cost'] : '';
$ajax_text_sale = isset($_POST['ajax_text_sale']) ? $_POST['ajax_text_sale'] : '';
$ajax_str_vmi_app = isset($_POST['ajax_str_vmi_app']) ? $_POST['ajax_str_vmi_app'] : '';
$ajax_str_bom_status = isset($_POST['ajax_str_bom_status']) ? $_POST['ajax_str_bom_status'] : '';


//update bom
$sql_update_bom ="
UPDATE [dbo].[tbl_bom_mst]
SET [bom_fg_code_set_abt] = '$ajax_text_fg_code_set_abt'
   ,[bom_fg_sku_code_abt] = '$ajax_text_component_abt'
   ,[bom_fg_code_gdj] = '$ajax_text_fg_code_gdj'
   ,[bom_fg_desc] = '$ajax_text_des'
   ,[bom_cus_code] = '$ajax_text_customer_code'
   ,[bom_cus_name] = '$ajax_text_customer_name'
   ,[bom_pj_name] = '$ajax_text_project_name'
   ,[bom_ctn_code_normal] = '$ajax_text_ctn_code_normal'
   ,[bom_snp] = '$ajax_text_snp'
   ,[bom_sku_code] = '$ajax_text_type_code'
   ,[bom_ship_type] = '$ajax_text_ship_type' 
   ,[bom_pckg_type] = '$ajax_text_package_type'
   ,[bom_dims_w] = '$ajax_text_w'
   ,[bom_dims_l] = '$ajax_text_l'
   ,[bom_dims_h] = '$ajax_text_h'
   ,[bom_usage] = '$ajax_text_usage'
   ,[bom_space_paper] = '$ajax_text_space_paper'
   ,[bom_flute] = '$ajax_text_flute'
   ,[bom_packing] = '$ajax_text_packing'
   ,[bom_wms_min] = '$ajax_text_wms_min'
   ,[bom_wms_max] = '$ajax_text_wms_max'
   ,[bom_vmi_min] = '$ajax_text_vmi_min'
   ,[bom_vmi_max] = '$ajax_text_vmi_max'
   ,[bom_vmi_app] = '$ajax_str_vmi_app'
   ,[bom_part_customer] = '$ajax_text_partcustomer'
   ,[bom_cost_per_pcs] = '$ajax_text_cost'
   ,[bom_price_sale_per_pcs] = '$ajax_text_sale'
   ,[bom_status] = '$ajax_str_bom_status'
   ,[bom_issue_by] = '$t_cur_user_code_VMI_GDJ'
   ,[bom_issue_date] = '$buffer_date'
   ,[bom_issue_time] = '$buffer_time'
   ,[bom_issue_datetime] = '$buffer_datetime'
    WHERE bom_id = '$ajax_bom_id'
";

$result_sql_update_bom = sqlsrv_query($db_con, $sql_update_bom);

if($result_sql_update_bom){
    echo "UPDATE_SUCCESS";
}else{
    echo "UPDATE_NOTWORK";
}

sqlsrv_close($db_con);
?>