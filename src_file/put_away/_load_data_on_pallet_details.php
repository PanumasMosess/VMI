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
$t_receive_pallet_code = isset($_POST['t_receive_pallet_code']) ? $_POST['t_receive_pallet_code'] : '';
$t_tags_fg_code_gdj = isset($_POST['t_tags_fg_code_gdj']) ? $_POST['t_tags_fg_code_gdj'] : '';
$t_receive_location = isset($_POST['t_receive_location']) ? $_POST['t_receive_location'] : '';
$t_receive_status = isset($_POST['t_receive_status']) ? $_POST['t_receive_status'] : '';
$t_receive_date = isset($_POST['t_receive_date']) ? $_POST['t_receive_date'] : '';

$strSql = " SELECT 
	receive_pallet_code
	,receive_tags_code
	,tags_fg_code_gdj
	,tags_project_name
	,receive_location
	,receive_status
	,receive_date
	,tags_packing_std
FROM tbl_receive
left join tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
where
receive_status in ('Rework','Reject','Wrong Spec','Quality','Damage','Received', 'Sinbin')
and
receive_pallet_code = '$t_receive_pallet_code'
and
tags_fg_code_gdj = '$t_tags_fg_code_gdj'
and 
receive_date = '$t_receive_date'
and 
receive_repn_id is null
order by 
receive_pallet_code desc
,receive_tags_code desc
";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;
    $json_array_ = array(
        "row_no" => $row_id,
        "receive_pallet_code" => $objResult['receive_pallet_code'],
        "receive_tags_code" => $objResult['receive_tags_code'],
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