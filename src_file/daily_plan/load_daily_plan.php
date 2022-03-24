<?
require_once("../../application.php");


$buffer_date = date("Y-m-d");

$strSql = " select * from tbl_daily_plan order by plan_issue_datetime desc ";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;
    $inround__ = array(
        "no" => $row_id,
        "plan_id" => $objResult['plan_id'],
		"plan_date" => $objResult['plan_date'],
		"plan_ft2_value" => $objResult['plan_ft2_value'],
		"plan_issue_by" => $objResult['plan_issue_by'],
		"plan_issue_date" => $objResult['plan_issue_date'],
		"plan_issue_time" => $objResult['plan_issue_time'],
		"plan_issue_datetime" => $objResult['plan_issue_datetime']
    );

    array_push($json, $inround__);

}
		
    echo json_encode($json);
	
?>	