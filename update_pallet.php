<?
    require_once("application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

    $buffer_tags_code = 16513;
    $id = 17589;
    for($row = 0; $row < 38; $row++){

        
        $sum_tags = $buffer_tags_code + 1;//sum + 1
        $sprintf_tags = sprintf("%09d",$sum_tags);//generate to 9 digit
        $full_tags = "PL".$sprintf_tags;//full tags

        $strSQL_update = "UPDATE [VMI].[dbo].[tbl_pallet_running] set pallet_code = '$full_tags', [pallet_status] = 'Matched' where [pallet_id] = '$id'";
        $objQuery = sqlsrv_query($db_con, $strSQL_update);

        $buffer_tags_code++;
        $id++;
    }

    echo "OK";

?>