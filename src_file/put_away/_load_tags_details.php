<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_CMS_NCR = isset($_SESSION['t_cur_user_code_CMS_NCR']) ? $_SESSION['t_cur_user_code_CMS_NCR'] : '';
$t_cur_user_type_CMS_NCR = isset($_SESSION['t_cur_user_type_CMS_NCR']) ? $_SESSION['t_cur_user_type_CMS_NCR'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$t_fg_code = isset($_POST['t_fg_code']) ? $_POST['t_fg_code'] : '';

$strSql = " SELECT 
	receive_tags_code
	,receive_pallet_code
	,tags_fg_code_gdj
	,tags_project_name
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
((receive_status = 'Received') or (receive_status = 'Sinbin'))
and
tags_fg_code_gdj = '$t_fg_code'
order by 
receive_tags_code desc
";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;
    $json_array_ = array(
        "row_no" => $row_id,
        "receive_tags_code" => $objResult['receive_tags_code'],
        "receive_pallet_code" => $objResult['receive_pallet_code'],
		"tags_fg_code_gdj" => $objResult['tags_fg_code_gdj'],
		"tags_project_name" => $objResult['tags_project_name'],
        "receive_location" => $objResult['receive_location'],
		"receive_status" => $objResult['receive_status'],
		"receive_date" => $objResult['receive_date'],
        "tags_packing_std" => $objResult['tags_packing_std']
    );
	
    array_push($json, $json_array_);
}
	
echo json_encode($json);
?>