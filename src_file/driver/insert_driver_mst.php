<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");


/**********************************************************************************/
/*var *****************************************************************************/
$txt_dri_user_en_add = isset($_POST['txt_dri_user_en_add_']) ? $_POST['txt_dri_user_en_add_'] : '';
$txt_dri_name_th_add = isset($_POST['txt_dri_name_th_add_']) ? $_POST['txt_dri_name_th_add_'] : '';
$txt_dri_name_en_add = isset($_POST['txt_dri_name_en_add_']) ? $_POST['txt_dri_name_en_add_'] : '';
$txt_dri_company_add = isset($_POST['txt_dri_company_add_']) ? $_POST['txt_dri_company_add_'] : '';
$txt_sel_dri_shift_add = isset($_POST['txt_sel_dri_shift_add_']) ? $_POST['txt_sel_dri_shift_add_'] : '';
$txt_dri_head_add = isset($_POST['txt_dri_head_add_']) ? $_POST['txt_dri_head_add_'] : '';
$txt_dri_tail_add = isset($_POST['txt_dri_tail_add_']) ? $_POST['txt_dri_tail_add_'] : '';


$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';

$pass_set = md5('123456789');

//Update Driver mst
$strSql_insert_driver = " 
INSERT INTO tbl_driver_mst
(
     [driver_code]
    ,[driver_pass_md5]
    ,[driver_name_th]
    ,[driver_name_en]
    ,[driver_company]
    ,[driver_section]
    ,[driver_truck_head_no]
    ,[driver_truck_tail_no]
    ,[driver_status]
    ,[driver_issue_by]
    ,[driver_issue_date]
    ,[driver_issue_time]
    ,[driver_issue_datetime]
)
VALUES
(
 '$txt_dri_user_en_add'
,'$pass_set'
,'$txt_dri_name_th_add'
,'$txt_dri_name_en_add'
,'$txt_dri_company_add'
,'$txt_sel_dri_shift_add'
,'$txt_dri_head_add'
,'$txt_dri_tail_add'
,'Active'
,'$t_cur_user_code_VMI_GDJ'
,'$buffer_date'
,'$buffer_time'
,'$buffer_datetime'
)
";

$objQuery_insert_driver = sqlsrv_query($db_con, $strSql_insert_driver);

 
    echo "INSERT_OK";
        
sqlsrv_close($db_con);
?>