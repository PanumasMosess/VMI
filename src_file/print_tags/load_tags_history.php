<?
require_once("../../application.php");


$strSql = " EXEC sp_print_tags_history ";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$str_icon_rec = "";
$str_status_rec = "";

$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;
    $receive_status = $objResult['receive_status'];
    	//check status received
	if(ltrim(rtrim($receive_status)) != "")
	{
		$str_status_rec = "Received";
	}
	else
	{
		$str_status_rec = "";
	}
	
    $inround__ = array(
      "no" => $row_id,
      "tags_code" => $objResult['tags_code'],
      "tags_fg_code_gdj" => $objResult['tags_fg_code_gdj'],
      "tags_fg_code_gdj_desc" => $objResult['tags_fg_code_gdj_desc'],
      "tags_prod_plan" => $objResult['tags_prod_plan'],
      "tags_packing_std" => $objResult['tags_packing_std'],
      "tags_total_qty" => $objResult['tags_total_qty'],
      "tags_token" => $objResult['tags_token'],
	  "tags_trading_from" => $objResult['tags_trading_from'],
      "tags_code_endcode" => var_encode($objResult['tags_code']),
      "tags_token_endcode" => var_encode($objResult['tags_token']),
      "tags_issue_by" => $objResult['tags_issue_by'],
      "tags_issue_date" => $objResult['tags_issue_date'],
      "tags_issue_time" => $objResult['tags_issue_time'],
      "tags_issue_datetime" => $objResult['tags_issue_datetime'],
      "receive_status" =>  $str_status_rec,
      "tags_project_name"  =>  $objResult['tags_project_name']

    );
    array_push($json, $inround__);

}
		
    echo json_encode($json);
	
?>


