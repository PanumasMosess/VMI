<?
require_once("../../application.php");


$buffer_date = date("Y-m-d");

$strSql = " select * from tbl_fg_ft2_mst order by ft2_issue_datetime desc ";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;
    $inround__ = array(
        "no" => $row_id,
        "ft2_id" => $objResult['ft2_id'],
		"ft2_fg_code" => $objResult['ft2_fg_code'],
		"ft2_value" => $objResult['ft2_value'],
		"ft2_issue_by" => $objResult['ft2_issue_by'],
		"ft2_issue_date" => $objResult['ft2_issue_date'],
		"ft2_issue_time" => $objResult['ft2_issue_time'],
		"ft2_issue_datetime" => $objResult['ft2_issue_datetime']
    );

    array_push($json, $inround__);

}
		
    echo json_encode($json);
	
?>	