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
$tag_code = isset($_POST['tag_code']) ? $_POST['tag_code'] : '';
$project_name = isset($_POST['project_name']) ? $_POST['project_name'] : '';

$strSql = " SELECT 
receive_pallet_code
,receive_tags_code
,tags_fg_code_gdj
,receive_location
,receive_status
,receive_date
,tags_packing_std
FROM tbl_pallet_running
left join tbl_receive
on tbl_pallet_running.pallet_code = tbl_receive.receive_pallet_code
left join tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
where
receive_status != 'Received' and receive_tags_code = '$tag_code' and receive_status = '$project_name'
order by 
receive_pallet_code desc
,receive_tags_code desc ";

$objQuery = sqlsrv_query($db_con, $strSql);
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{  
    $fg_code_gdj = $objResult['tags_fg_code_gdj'];
    $stock_wms_qty = $objResult['tags_packing_std'];
    $stock_location = $objResult['receive_location'];
    $stock_plate_code = $objResult['receive_pallet_code'];
}
if(!isset($fg_code_gdj)&& !isset($stock_wms_qty) && !isset($stock_location) && !isset($stock_plate_code)){
    
die(json_encode('NULL'));

}else{

$detail_wms_back = array($fg_code_gdj, $stock_wms_qty, $stock_location, $stock_plate_code);  
die(json_encode($detail_wms_back));

}


  
?>

<?
sqlsrv_close($db_con);
?>