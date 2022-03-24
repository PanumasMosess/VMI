<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

$strSql = " SELECT 
	receive_pallet_code
	,tags_fg_code_gdj
	,tags_fg_code_gdj_desc
	,tags_project_name
	,receive_location
	,receive_status
	,receive_date
	,sum(tags_packing_std) as sum_pkg_std
FROM  tbl_receive
left join tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
where
receive_status in ('Rework','Reject','Wrong Spec','Quality','Damage','Received') 
and
receive_repn_id is NULL
group by
	receive_pallet_code
	,tags_fg_code_gdj
	,tags_fg_code_gdj_desc
	,tags_project_name
	,receive_location
	,receive_status
	,receive_date
order by 
receive_pallet_code desc ";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$date_receive = "";
$date_now = "";
$diff = "";
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;
	
	$date_receive = date_create($objResult['receive_date']);
	$date_now = date_create($buffer_date);
	$diff = date_diff($date_receive,$date_now);
	
    $json_array_ = array(
        "row_no" => $row_id,
        "receive_pallet_code" => $objResult['receive_pallet_code'],
		"var_encode_receive_pallet_code" => var_encode($objResult['receive_pallet_code']),
        "tags_fg_code_gdj" => $objResult['tags_fg_code_gdj'],
		"tags_fg_code_gdj_desc" => $objResult['tags_fg_code_gdj_desc'],
		"tags_project_name" => $objResult['tags_project_name'],
        "receive_location" => $objResult['receive_location'],
		"receive_status" => $objResult['receive_status'],
        "receive_date" => $objResult['receive_date'],
		"tags_packing_std" => number_format($objResult['sum_pkg_std']),
		"diff_aging" => number_format($diff->format("%a"))
    );
	
    array_push($json, $json_array_);
}
	
echo json_encode($json);
?>