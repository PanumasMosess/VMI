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
$iden_tag_code = isset($_POST['iden_tag_code']) ? $_POST['iden_tag_code'] : '';


////tags 9 digit (000000001)////
///////////////////Get tags no.///////////////////
$strSql_get_tags = " SELECT top(1) * FROM tbl_tags_running where  tags_code = '$iden_tag_code'";
$objQuery_get_tags = sqlsrv_query($db_con, $strSql_get_tags, $params, $options);
$num_row_get_tags = sqlsrv_num_rows($objQuery_get_tags);

if($num_row_get_tags == 0){

    echo "Not Found";

}else{
    $tags_code = '';
    $tags_fg_code_gdj_desc = '';
    $tags_token = '';
    while($objResult_reject_tags = sqlsrv_fetch_array($objQuery_get_tags, SQLSRV_FETCH_ASSOC))
    {
        $tags_code = $objResult_reject_tags['tags_code'];
        $tags_fg_code_gdj_desc = $objResult_reject_tags['tags_fg_code_gdj_desc'];
        $tags_token = $objResult_reject_tags['tags_token'];
    }

    $sql_insert_log_reject = "INSERT INTO tbl_adjust_inventory_log
    (
     [tags_log_tags_code]
    ,[tags_log_fg]
    ,[tags_log_token]
    ,[tags_log_query]
    ,[tags_log_by]
    ,[tags_log_date]
    ,[tags_log_time]
    ,[tags_log_datetime]
    )
VALUES
    (
     '$tags_code'
    ,'$tags_fg_code_gdj_desc'
    ,'$tags_token'
    ,'reject'
    ,'$t_cur_user_code_VMI_GDJ'
    ,'$buffer_date'
    ,'$buffer_time'
    ,'$buffer_datetime'
    )
    ";

$objQuery_insert_log_reject = sqlsrv_query($db_con, $sql_insert_log_reject);

if($objQuery_insert_log_reject){
    $sql_delete_tag = "DELETE FROM tbl_tags_running WHERE tags_code = '$tags_code'";
    $objQuery_delete_tags_running = sqlsrv_query($db_con, $sql_delete_tag);
    echo "success_reject_tag";
}

}


sqlsrv_close($db_con);
?>