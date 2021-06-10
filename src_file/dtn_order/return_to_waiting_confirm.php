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
$iden_t_dtn_id = isset($_POST['iden_t_dtn_id']) ? $_POST['iden_t_dtn_id'] : '';

//decode
$iden_t_dtn_id = var_decode($iden_t_dtn_id);


//get details for tbl_picking_head
$strSql_data_picking_t = " 
SELECT dn_t_picking_code
FROM tbl_dn_tail
where dn_t_dtn_code = '$iden_t_dtn_id' 
";
$objQuery_data_picking_t = sqlsrv_query($db_con, $strSql_data_picking_t);
$num_row_data_picking_t = sqlsrv_num_rows($objQuery_data_picking_t);

while($objResult_data_picking_h = sqlsrv_fetch_array($objQuery_data_picking_t, SQLSRV_FETCH_ASSOC))
{
	$dn_t_picking_code = $objResult_data_picking_h['dn_t_picking_code'];

    	//read tbl_picking_tail for update tags ID to Delivery Transfer Note
    	$strSql_update_tags_to_dtn = " 
        SELECT 
            [ps_t_picking_code]
            ,[ps_t_tags_code]
        FROM [tbl_picking_tail]
        where
        [ps_t_picking_code] = '$dn_t_picking_code' 
    ";
    $objQuery_update_tags_to_dtn = sqlsrv_query($db_con, $strSql_update_tags_to_dtn, $params, $options);
    $num_row_update_tags_to_dtn = sqlsrv_num_rows($objQuery_update_tags_to_dtn);

    $index_update_picking = 0;
    
    while($objResult_update_tags_to_dtn = sqlsrv_fetch_array($objQuery_update_tags_to_dtn, SQLSRV_FETCH_ASSOC))
    {
        
        $ps_t_picking_code = $objResult_update_tags_to_dtn['ps_t_picking_code'];
        $ps_t_tags_code = $objResult_update_tags_to_dtn['ps_t_tags_code'];
        
        //update tbl_receive - receive_status = re
        $sqlUpdateDTN = " UPDATE tbl_receive SET receive_status = 'Picking' WHERE receive_tags_code = '$ps_t_tags_code' ";
        $result_sqlUpdateDTN = sqlsrv_query($db_con, $sqlUpdateDTN);

        if($index_update_picking == 0){
            //update tbl_picking_head - ps_h_status = Picking
            $sqlUpdatePicking_head = " UPDATE tbl_picking_head SET ps_h_status = 'Picking' WHERE ps_h_picking_code = '$ps_t_picking_code' and ps_h_status = 'Delivery Transfer Note'";
            $result_sqlUpdatePicking_head = sqlsrv_query($db_con, $sqlUpdatePicking_head);

            //update tbl_picking_tail - ps_t_status = Picking
            $sqlUpdatePicking_tail = " UPDATE tbl_picking_tail SET ps_t_status = 'Picking' WHERE ps_t_picking_code = '$ps_t_picking_code' and ps_t_status = 'Delivery Transfer Note' ";
            $result_sqlUpdatePicking_tail = sqlsrv_query($db_con, $sqlUpdatePicking_tail);

        }

        $index_update_picking++;
    }
}


    //update [tbl_dn_head] - dn_h_status = return
    $sqlUpdatedn_head = " UPDATE tbl_dn_head SET dn_h_status = 'Return', dn_h_issue_by = '$t_cur_user_code_VMI_GDJ', dn_h_issue_date = '$buffer_date', dn_h_issue_time = '$buffer_time', dn_h_issue_datetime = '$buffer_datetime' WHERE dn_h_dtn_code = '$iden_t_dtn_id' and dn_h_status = 'Delivery Transfer Note'";
    $result_sqlUpdateDN_head = sqlsrv_query($db_con, $sqlUpdatedn_head);

    //update [tbl_dn_tail] - dn_t_status = return
    $sqlUpdatedn_head = " UPDATE tbl_dn_tail SET dn_t_status = 'Return', dn_t_issue_by = '$t_cur_user_code_VMI_GDJ', dn_t_issue_date = '$buffer_date', dn_t_issue_time = '$buffer_time', dn_t_issue_datetime = '$buffer_datetime' WHERE dn_t_dtn_code = '$iden_t_dtn_id' and dn_t_status = 'Delivery Transfer Note'";
    $result_sqlUpdateDN_head = sqlsrv_query($db_con, $sqlUpdatedn_head);

    //update tbl_dn_running - dn_status = Matched
    $sqlUpdateDTNRunning = " UPDATE tbl_dn_running SET dn_status = 'Return' WHERE dn_dtn_code = '$iden_t_dtn_id' ";
    $result_sqlUpdateDTNRunning = sqlsrv_query($db_con, $sqlUpdateDTNRunning);
	

sqlsrv_close($db_con);

?>